<?php
require_once __DIR__ . '/../model/Cart.php';
require_once __DIR__ . '/../model/Game.php';
require_once __DIR__ . '/../model/Order.php';

class CartController {
    private PDO  $db;
    private Cart $cart;
    private Game $gameModel;
    private Order $orderModel;

    public function __construct(PDO $db) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../login.php');
            exit();
        }
        $this->db        = $db;
        $this->cart      = new Cart();
        $this->gameModel = new Game($db);
        $this->orderModel = new Order($db);
    }

    /**
     * Handle all incoming actions (add, remove, clear, checkout)
     * @param string $action
     * @param int|null $id
     */
    public function handle(string $action, ?int $id = null): void {
        switch ($action) {
            case 'add':
                $qty = isset($_REQUEST['quantity']) ? max(1, (int)$_REQUEST['quantity']) : 1;
                $this->add($id, $qty);
                break;
            case 'remove':
                $this->remove($id);
                break;
            case 'clear':
                $this->clear();
                break;
            case 'checkout':
                $this->checkout();
                break;
        }
    }

    public function index(): array {
        $items = $this->cart->getItems();
        $games = [];
        $total = 0.0;
        foreach ($items as $gameId => $qty) {
            $game = $this->gameModel->getGameById($gameId);
            if ($game) {
                $game['quantity'] = $qty;
                $game['subtotal'] = $game['price'] * $qty;
                $total += $game['subtotal'];
                $games[] = $game;
            }
        }
        $showSuccess = isset($_GET['success']) && $_GET['success'] == 1;
        return ['games' => $games, 'total' => $total, 'showSuccess' => $showSuccess];
    }

    public function add(int $gameId, int $quantity): void {
        // Add specified quantity, decrement stock
        $game = $this->gameModel->getGameById($gameId);
        if ($game && $game['stock'] >= $quantity) {
            $this->cart->addItem($gameId, $quantity);
        }
        header('Location: ../view/viewCart.php');
        exit();
    }

    public function remove(int $gameId): void {
        $this->cart->removeItem($gameId);
        header('Location: ../view/viewCart.php');
        exit();
    }

    public function clear(): void {
        $this->cart->clear();
        header('Location: ../view/viewCart.php');
        exit();
    }

    public function checkout(): void {
        $userId = (int)$_SESSION['user_id'];
        $items = $this->cart->getItems();
        $orderItems = [];
        $totalPrice = 0.0;
        foreach ($items as $gameId => $qty) {
            $game = $this->gameModel->getGameById($gameId);
            if ($game && $game['stock'] >= $qty) {
                $orderItems[] = ['game_id' => $gameId, 'quantity' => $qty, 'price' => $game['price']];
                $totalPrice += $game['price'] * $qty;
                // reduce stock
                $this->gameModel->updateStock($gameId, $game['stock'] - $qty);
            }
        }
        if (!empty($orderItems)) {
            $order = new Order($this->db);
            $order->userId = $userId;
            $order->create($orderItems, $totalPrice, 'Completed');
            $this->cart->clear();
            // redirect with success flag
            header('Location: ../view/viewCart.php?success=1');
            exit();
        }
        header('Location: ../view/viewCart.php?error=1');
        exit();
    }
}
