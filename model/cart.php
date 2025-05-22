<?php
class Cart {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addItem(int $gameId, int $quantity = 1): void {
        if (isset($_SESSION['cart'][$gameId])) {
            $_SESSION['cart'][$gameId] += $quantity;
        } else {
            $_SESSION['cart'][$gameId] = $quantity;
        }
    }

    public function removeItem(int $gameId): void {
        unset($_SESSION['cart'][$gameId]);
    }

    public function clear(): void {
        $_SESSION['cart'] = [];
    }

    /**
     * @return array<int,int>  [gameId => quantity]
     */
    public function getItems(): array {
        return $_SESSION['cart'];
    }
}