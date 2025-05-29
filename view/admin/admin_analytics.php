<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/AdminAnalyticsController.php';

$controller = new AdminAnalyticsController($conn);
extract($controller->analytics());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Analytics</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../../style/admin_analytics.css">
  
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <style>
    .totals { display: flex; gap:2rem; margin-bottom:2rem; }
    .totals div { padding:1rem; border:1px solid #ccc; border-radius:4px; text-align:center; width:8rem; }
    .charts { display: grid; grid-template-columns:1fr; gap:2rem; }
    .chart-container { width:100%; max-width:600px; margin:auto; }
  </style>
</head>
<body>

<div class="sidebar">
        <div class ="top-bar">
            <div class="profile-link">
                <a href="../profile.php" class="profile-img">
                    <img src="../../<?= $_SESSION['image']?>" alt="Profile Picture">
                </a>
                <div class="profile-details">
                    <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="admin_home.php" class="sidebar-link">
                    <i class="ri-dashboard-fill"></i> <h4>Dashboard</h4>
                </a>
            </li>
            <li>
                <a href="admin_users.php" class="sidebar-link">
                    <i class="ri-group-fill"></i> <h4>Users</h4>
                </a>
            </li>
            <li>
                <a href="admin_games.php" class="sidebar-link">
                    <i class="ri-store-2-line"></i> <h4>Games</h4>
                </a>
            </li>
            <li>
                <a href="admin_analytics.php" class="sidebar-link active">
                    <i class="ri-bar-chart-box-line"></i> <h4>Analytics</h4>
                </a>
            </li>
            <li>
                <a href="admin_web_settings.php" class="sidebar-link">
                    <i class="ri-pages-line"></i> <h4>Website</h4>
                </a>
            </li>
            <li>
                <a href="../../logout.php" class="sidebar-link">
                    <i class="ri-logout-box-line"></i> <h4>Logout</h4>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
      <h1>Monthly Business Analytics</h1>

  <!-- Totals -->
  <div class="totals">
    <div>
      <h2><?= $totalUsers ?></h2>
      <p>Total Users</p>
    </div>
    <div>
      <h2><?= $totalGames ?></h2>
      <p>Total Games</p>
    </div>
    <div>
      <h2><?= $totalOrders ?></h2>
      <p>Total Orders</p>
    </div>
  </div>

  <!-- Charts -->
  <div class="charts">
    <div class="chart-container">
      <canvas id="usersChart"></canvas>
    </div>
    <div class="chart-container">
      <canvas id="ordersChart"></canvas>
    </div>
    <div class="chart-container">
      <canvas id="profitChart"></canvas>
    </div>
  </div>
    </div>

  <script>
    // shared labels
    const labels = <?= json_encode($labels) ?>;

    // helper to map our associative arrays into a zero-filled array
    function buildSeries(dataMap) {
      return labels.map(d => dataMap[d] || 0);
    }

    // 1) New Users Chart
    new Chart(document.getElementById('usersChart'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'New Users',
          data: buildSeries(<?= json_encode($dailyNewUsers) ?>),
          fill: false,
          tension: 0.2
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // 2) New Orders Chart
    new Chart(document.getElementById('ordersChart'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'New Orders',
          data: buildSeries(<?= json_encode($dailyNewOrders) ?>),
          fill: false,
          tension: 0.2
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // 3) Daily Profit Chart
    new Chart(document.getElementById('profitChart'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Profit ($)',
          data: buildSeries(<?= json_encode($dailyProfit) ?>),
          fill: false,
          tension: 0.2
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });
  </script>
</body>
</html>
