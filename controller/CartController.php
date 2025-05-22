<?php
require_once __DIR__ . '/../model/Cart.php';
require_once __DIR__ . '/../model/Game.php';
require_once __DIR__ . '/../model/Order.php';

class CartController {
    private PDO  $db;
    private Cart $cart;
    private Game $gameModel;

    public function __construct(PDO $db) {
        $this->db        = $db;
        $this->cart      = new Cart();
        $this->gameModel = new Game($db);
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../login.php');
            exit();
        }
    }

    /**
     * @return array{games: array, total: float}
     */
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
        return ['games' => $games, 'total' => $total];
    }

    public function add(int $gameId): void {
        $this->cart->addItem($gameId);
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
        $order = new Order($this->db);
        $order->userId = $userId;
        $orderItems = [];
        $totalPrice = 0.0;

        foreach ($this->cart->getItems() as $gameId => $qty) {
            $game = $this->gameModel->getGameById($gameId);
            if ($game && $game['stock'] >= $qty) {
                $orderItems[] = [
                    'game_id' => $gameId,
                    'quantity' => $qty,
                    'price' => $game['price']
                ];
                $totalPrice += $game['price'] * $qty;
                // Optionally update stock here
            }
        }

        if ($order->create($orderItems, $totalPrice, 'Completed')) {
            $this->cart->clear();
            header('Location: ../view/checkoutSuccess.php');
            exit();
        }

        header('Location: ../view/viewCart.php?error=checkout_failed');
        exit();
    }
}