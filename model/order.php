<?php

class Order {
    private PDO $db;

    public ?int $id = null;
    public int $userId;
    public string $placedOn;
    public float $totalPrice;
    public string $paymentStatus;

    public array $orderItems = []; 
    
    public function __construct(PDO $db, ?int $id = null) {
        $this->db = $db;

        if ($id !== null) {
            $this->loadById($id);
        }
    }
    public function loadById(int $id): bool {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $this->id = (int)$order['id'];
            $this->userId = (int)$order['user_id'];
            $this->placedOn = $order['placed_on'];
            $this->totalPrice = (float)$order['total_price'];
            $this->paymentStatus = $order['payment_status'];

            $this->loadOrderItems();
            return true;
        }
        return false;
    }

    private function loadOrderItems(): void {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $this->id]);

        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderItem = new OrderItem($this->db);
            $orderItem->loadFromDB($item);
            $this->orderItems[] = $orderItem;
        }
    }

    public function create(array $orderItemsData, float $totalPrice, string $paymentStatus = 'Pending'): bool {
        $stmt = $this->db->prepare("
            INSERT INTO orders (user_id, total_price, payment_status) 
            VALUES (:user_id, :total_price, :payment_status)
        ");
        $success = $stmt->execute([
            ':user_id' => $this->userId,
            ':total_price' => $totalPrice,
            ':payment_status' => $paymentStatus
        ]);

        if ($success) {
            $this->id = (int)$this->db->lastInsertId();
            foreach ($orderItemsData as $itemData) {
                $orderItem = new OrderItem($this->db);
                $orderItem->orderId = $this->id;
                $orderItem->gameId = $itemData['game_id'];
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->price = $itemData['price'];
                $orderItem->save();
            }
            return true;
        }
        return false;
    }
    public function updatePaymentStatus(string $status): bool {
        $stmt = $this->db->prepare("UPDATE orders SET payment_status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $this->id
        ]);
    }
    public function getTotalItems(): int {
        return count($this->orderItems);
    }

    public function getTotalPrice(): float {
        return array_sum(array_map(fn($item) => $item->price * $item->quantity, $this->orderItems));
    }

     public static function getOrdersToday(PDO $db, string $date): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE DATE(placed_on) = :date");
        $stmt->execute([':date' => $date]);
        return $stmt->fetchColumn();
    }
    public function getTotalOrders(): int {
        $sql = "SELECT COUNT(*) FROM orders";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public static function getTotalProfit(PDO $db, string $date): float {
        $stmt = $db->prepare("SELECT SUM(total_price) FROM orders WHERE DATE(placed_on) = :date AND payment_status = 'Completed'");
        $stmt->execute([':date' => $date]);
        return (float)$stmt->fetchColumn();
    }

    public static function getOrdersTodayPaginated(PDO $db, string $date, int $page = 1): array {
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT o.id, o.placed_on, o.total_price, u.username, o.payment_status
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE DATE(o.placed_on) = :date
            LIMIT $limit OFFSET $offset
        ";
            
        $stmt = $db->prepare($sql);
        $stmt->execute([':date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDailyNewOrders(string $startDate, string $endDate): array
    {
        $sql = "
            SELECT DATE(placed_on) AS date, COUNT(*) AS count
            FROM orders
            WHERE DATE(placed_on) BETWEEN :start AND :end
            GROUP BY DATE(placed_on)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':start' => $startDate,
            ':end'   => $endDate
        ]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['date']] = (int) $row['count'];
        }
        return $result;
    }
    public function getDailyProfit(string $startDate, string $endDate): array
    {
        $sql = "
            SELECT DATE(placed_on) AS date, SUM(total_price) AS profit
            FROM orders
            WHERE DATE(placed_on) BETWEEN :start AND :end
              AND payment_status = 'Completed'
            GROUP BY DATE(placed_on)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':start' => $startDate,
            ':end'   => $endDate
        ]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['date']] = (float) $row['profit'];
        }
        return $result;
    }
    public function getOrdersByUser(int $userId): array
    {
        // 1) Pull the base order rows
        $stmt = $this->db->prepare("
            SELECT id, placed_on, payment_status AS status, total_price AS total
            FROM orders
            WHERE user_id = :uid
            ORDER BY placed_on DESC
        ");
        $stmt->execute([':uid' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2) For each order, pull its items
        $itemStmt = $this->db->prepare("
            SELECT oi.quantity, oi.price, g.name, g.image
            FROM order_items oi
            JOIN games g ON g.id = oi.game_id
            WHERE oi.order_id = :oid
        ");
        foreach ($orders as &$ord) {
            $itemStmt->execute([':oid' => $ord['id']]);
            $ord['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $orders;
    }
}

class OrderItem {
    private PDO $db;

    public ?int $id = null;
    public int $orderId;
    public int $gameId;
    public int $quantity;
    public float $price;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    public function loadFromDB(array $data): void {
        $this->id = (int)$data['id'];
        $this->orderId = (int)$data['order_id'];
        $this->gameId = (int)$data['game_id'];
        $this->quantity = (int)$data['quantity'];
        $this->price = (float)$data['price'];
    }

    public function save(): bool {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, game_id, quantity, price)
            VALUES (:order_id, :game_id, :quantity, :price)
        ");
        return $stmt->execute([
            ':order_id' => $this->orderId,
            ':game_id' => $this->gameId,
            ':quantity' => $this->quantity,
            ':price' => $this->price
        ]);
    }
}
