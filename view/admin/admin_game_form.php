<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: ../../login.php');
    exit();
}

require_once '../../controller/AdminGamesController.php';

$controller = new AdminGamesController($conn);
$data = $controller->showGameForm();

extract($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $game ? "Edit Game" : "Create New Game" ?></title>
    <meta charset="UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/admin_games_form.css" />
    <link rel="stylesheet" href="admin.css" />
</head>
<body>

<div class="sidebar">
        <div class ="top-bar">
            <div class="profile-link">
                <a href="../profile.php" class="profile-img">
                    <img src="../../<?= $_SESSION['image']?>" alt="Profile Picture">
                </a>
                <div class="profile-details">
                    <h4><?= htmlspecialchars($_SESSION['username']); ?></h4>
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
                <a href="admin_games.php" class="sidebar-link active">
                    <i class="ri-store-2-line"></i> <h4>Games</h4>
                </a>
            </li>
            <li>
                <a href="admin_analytics.php" class="sidebar-link">
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

<div class="container">
    <div class="main-content">
        <h1><?= $game ? "Edit Game" : "Create New Game" ?></h1>

<form method="POST" action="../../controller/admin_games_save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $game['id'] ?? '' ?>">

    <!-- Existing fields... -->
    <label>Name</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($game['name'] ?? '') ?>">

    <label>Category</label>
    <select name="category" required>
        <option value="RPG">RPG</option>
        <option value="MMO">MMO</option>
        <option value="Action">Action</option>
        <option value="MOBA">MOBA</option>
        <option value="Arena">Arena</option>
        <option value="Puzzle">Puzzle</option>
    </select>

    <label>Platform</label>
    <select name="platform" required>
        <option value="PC">PC</option>
        <option value="PlayStation">PlayStation</option>
        <option value="Nintendo Wii">Nintendo Wii</option>
        <option value="Nintendo GameBoy">Nintendo GameBoy</option>
        <option value="Nintendo Switch">Nintendo Switch</option>
        <option value="Sega">Sega</option>
    </select>

    <label>Details</label>
    <input type="text" name="details" required value="<?= htmlspecialchars($game['details'] ?? '') ?>">

    <label>Price ($)</label>
    <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($game['price'] ?? '') ?>">

    <label>Stock</label>
    <input type="number" name="stock" required value="<?= htmlspecialchars($game['stock'] ?? '') ?>">

    <?php if (!empty($game['image'])): ?>
        <input type="hidden" name="existingImage" value="<?= htmlspecialchars($game['image']) ?>">
        <label>Current Cover Image</label>
        <img src="../../<?= htmlspecialchars($game['image']) ?>" alt="Cover Image" style="height:30px; width:auto;">
    <?php endif; ?>
    <label><?= $game ? 'Change' : 'Upload' ?> Cover Image</label>
    <input type="file" name="image" accept="image/*">

    <?php if (!empty($game['bgImage'])): ?>
        <input type="hidden" name="existingBgImage" value="<?= htmlspecialchars($game['bgImage']) ?>">
        <label>Current Background Image</label>
        <img src="../../<?= htmlspecialchars($game['bgImage']) ?>" alt="Background Image" style="height:50px; width:auto;">
    <?php endif; ?>
    <label><?= $game ? 'Change' : 'Upload' ?> Background Image</label>
    <input type="file" name="bgImage" accept="image/*">

    <label>Date Released</label>
    <input type="date" name="dateReleased" required value="<?= htmlspecialchars($game['dateReleased'] ?? '') ?>">

    <button type="submit"><?= $game ? "Update Game" : "Create Game" ?></button>
</form>

    </div>
</div>
</body>
</html>
