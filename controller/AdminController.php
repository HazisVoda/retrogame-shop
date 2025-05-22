<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Order.php';
class AdminController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function showHomePage(): array {
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

        $userCount = User::registeredToday($this->db, $date);
        $orderCount = Order::getOrdersToday($this->db, $date);
        $totalProfit = Order::getTotalProfit($this->db, $date);

        $pageOrders = isset($_GET['page_orders']) ? (int)$_GET['page_orders'] : 1;
        $orders = Order::getOrdersTodayPaginated($this->db, $date, $pageOrders);

        $pageUsers = isset($_GET['page_users']) ? (int)$_GET['page_users'] : 1;
        $users = User::getUsersRegisteredTodayPaginated($this->db, $date, $pageUsers);
        
        return compact('date', 'userCount', 'orderCount', 'totalProfit', 'users', 'orders', 'pageUsers', 'pageOrders');
    }
}
