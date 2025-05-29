<?php
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

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/WebsiteController.php';

$ctrl = new WebsiteController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl->update();
}
extract($ctrl->index());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Website Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="../../style/admin_settings.css">
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
                <a href="admin_analytics.php" class="sidebar-link">
                    <i class="ri-bar-chart-box-line"></i> <h4>Analytics</h4>
                </a>
            </li>
            <li>
                <a href="admin_web_settings.php" class="sidebar-link active">
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
            <h1>Website Information</h1>
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form__group">
            <label for="name">Website Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($webInfo['name']) ?>" required>
        </div>

        <div class="form__group">
            <label for="description">Website Description</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($webInfo['details']) ?></textarea>
        </div>

        <div class="form__group">
            <label for="footer">Footer Text</label>
            <textarea id="footer" name="footer" rows="2"><?= htmlspecialchars($webInfo['footer']) ?></textarea>
        </div>

        <div class="form__group">
            <label for="logo">Website Logo</label>
            <?php if (!empty($webInfo['logo'])): ?>
                <img src="../<?= htmlspecialchars($webInfo['logo']) ?>" alt="Current Logo" height="50">
            <?php endif; ?>
            <input type="file" id="logo" name="logo" accept="image/*">
        </div>

        <button type="submit" class="button">Save Changes</button>
    </form>
        </div>
    </div>
</body>
</html>