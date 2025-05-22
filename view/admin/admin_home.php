<?php

include '../../config.php';
require_once '../../controller/AdminController.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}

if ($_SESSION['role_id'] !== 2) {
    header('HTTP/1.1 403 Forbidden');
    echo "Access denied. Admins only.";
    exit();
}

$adminUsername = $_SESSION['username'];
$adminImage = $_SESSION['image'];

$adminController = new AdminController($conn);
$data = $adminController->showHomePage();

extract($data);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Remix Icon CDN -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <!-- Swipper JS CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <!-- Custom CSS File -->
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>
        <?php include 'dashboard-sidebar.php'; ?>
        <div class="container">
            <div class="main-content">
                <h1>Dashboard</h1>

                <form method="GET" action="admin_home.php" class="date">
                    <div class="date">
                        <input type="date" name="date" id="date" value="<?= date('Y-m-d', strtotime($date)) ?>">
                    </div>
                    <button type="submit">Show</button>
                </form>

                <div class="insights">
                    <div class="sales">
                        <i class="ri-line-chart-fill"></i>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Users</h3>
                                <h1 id="orderCount"><?= $userCount ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="expenses">
                        <i class="ri-money-dollar-circle-fill"></i>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Orders</h3>
                                <h1 id="userCount"><?= $orderCount ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="sales">
                    <i class="ri-bar-chart-line"></i>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Profit</h3>
                                <h1 id="gameCount">$<?= number_format($totalProfit, 2) ?></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="recent_orders">
                    <h1>Recent Orders</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Total Price</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['id']) ?></td>
                                    <td><?= htmlspecialchars($order['username']) ?></td>
                                    <td><?= htmlspecialchars($order['total_price']) ?></td>
                                    <td><?= htmlspecialchars($order['placed_on']) ?></td>
                                    <td><?= htmlspecialchars($order['payment_status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div>
                        <a href="?date=<?= $date ?>&page_orders=<?= $pageOrders - 1 ?>#ordersTableBody" <?= $pageOrders <= 1 ? 'style="visibility:hidden"' : '' ?>>Previous</a>
                        <a href="?date=<?= $date ?>&page_orders=<?= $pageOrders + 1 ?>#ordersTableBody">Next</a>
                    </div>
                </div>

                <div class="recent_orders">
                    <h1>All Users</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['firstName']) ?></td>
                                    <td><?= htmlspecialchars($user['lastName']) ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role_name']) ?></td>
                                    <td><?= htmlspecialchars($user['createdAt']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div>
                        <a href="?date=<?= $date ?>&page_users=<?= $pageUsers - 1 ?>#usersTableBody" <?= $pageUsers <= 1 ? 'style="visibility:hidden"' : '' ?>>Previous</a>
                        <a href="?date=<?= $date ?>&page_users=<?= $pageUsers + 1 ?>#usersTableBody">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>