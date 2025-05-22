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
  <link rel="stylesheet" href="admin.css">
  <style>
    .totals { display: flex; gap:2rem; margin-bottom:2rem; }
    .totals div { padding:1rem; border:1px solid #ccc; border-radius:4px; text-align:center; width:8rem; }
    .charts { display: grid; grid-template-columns:1fr; gap:2rem; }
    .chart-container { width:100%; max-width:600px; margin:auto; }
  </style>
</head>
<body>
  <h1>Business Analytics (last 30 days)</h1>

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

  <p><a href="admin_home.php">← Back to Dashboard</a></p>
</body>
</html>
