<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)) {
    header('location:index.php');
    exit();
}

$sql = "SELECT * FROM user WHERE id = :admin_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch();

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
        <div class="container">
            <div class="sidebar">
                <div class ="profile">
                    <a href="#" class="profile-link">
                        <img src="data/pictures/defaultpfp.png" alt="Profile Picture">
                        <h3><?php echo htmlspecialchars($admin['firstName']); ?></h3>
                    </a>
                </div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="admin_home.php" class="sidebar-link active">
                            <i class="ri-dashboard-fill"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="ri-group-fill"></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="ri-store-2-line"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="ri-bar-chart-box-line"></i> Analytics
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="ri-notification-2-fill"></i> Messages
                            <span class="notification-count">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="sidebar-link">
                            <i class="ri-logout-box-line"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <div class="main-content">
                <h1>Dashboard</h1>

                <div class="date">
                    <input type="date" id="date-picker" value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="insights">
                    <div class="sales">
                        <i class="ri-line-chart-fill"></i>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Sales</h3>
                                <h1>$25,000</h1>
                            </div>
                            <div class="progress">
                                <svg>
                                    <circle cx="40" cy="40" r="30"></circle>
                                </svg>
                                <div class="number">80%</div>
                            </div>
                        </div>
                        <p>Last 24 Hours</p>
                    </div>
                    <div class="expenses">
                        <i class="ri-money-dollar-circle-fill"></i>
                        <div class="middle">
                            <div class="left">
                                <h3>Total Expenses</h3>
                                <h1>$25,000</h1>
                            </div>
                            <div class="progress">
                                <svg>
                                    <circle cx="40" cy="40" r="30"></circle>
                                </svg>
                                <div class="number">80%</div>
                            </div>
                        </div>
                        <p>Last 24 Hours</p>
                    </div>
                    <div class="sales">
                    <i class="ri-bar-chart-line"></i>
                        <div class="middle">
                            <div class="left">
                                <h3>Income</h3>
                                <h1>$25,000</h1>
                            </div>
                            <div class="progress">
                                <svg>
                                    <circle cx="40" cy="40" r="30"></circle>
                                </svg>
                                <div class="number">80%</div>
                            </div>
                        </div>
                        <p>Last 24 Hours</p>
                    </div>
                </div>

                <div class="recent_orders">
                    <h2>Recent Orders</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Number</th>
                                <th>Payments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Metal Gear Solid</td>
                                <td>2</td>
                                <td>Due</td>
                                <td class ="warning">Pending</td>
                                <td class ="primary">Details</td>
                            </tr>
                            <tr>
                                <td>Pokemon</td>
                                <td>2</td>
                                <td>Due</td>
                                <td class ="warning">Pending</td>
                                <td class ="primary">Details</td>
                            </tr>
                            <tr>
                                <td>The Legend of Zelda</td>
                                <td>2</td>
                                <td>Due</td>
                                <td class ="warning">Pending</td>
                                <td class ="primary">Details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="right-sidebar">
                <div class="top">
                    <h2>Notifications</h2>
                    <i class="ri-notification-2-fill"></i>
                </div>
                <div class="notifications">
                    <div class="notification-item">
                        <i class="ri-user-add-fill"></i>
                        <p>New user registered</p>
                    </div>
                    <div class="notification-item">
                        <i class="ri-shopping-cart-fill"></i>
                        <p>New order placed</p>
                    </div>
                    <div class="notification-item">
                        <i class="ri-message-2-fill"></i>
                        <p>New message received</p>
                    </div>
                </div>
            </div>

            <script src="style/js/main.js"></script>
        </div>
    </body>
</html>