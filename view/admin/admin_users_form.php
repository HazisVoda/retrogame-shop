<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: ../../login.php');
    exit();
}

require_once '../../controller/AdminUsersController.php';

$controller = new AdminUsersController($conn);
$data = $controller->showUserForm();
extract($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $user ? "Edit User" : "Create New User" ?></title>
    <meta charset="UTF-8" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/admin_users.css">
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
                <a href="admin_users.php" class="sidebar-link active">
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

<h1><?= $user ? "Edit User" : "Create New User" ?></h1>

<form method="POST" action="../../controller/admin_users_save.php">
    <input type="hidden" name="id" value="<?= $user['id'] ?? '' ?>">

    <label>First Name</label><br>
    <input type="text" name="firstName" required value="<?= htmlspecialchars($user['firstName'] ?? '') ?>"><br>

    <label>Last Name</label><br>
    <input type="text" name="lastName" required value="<?= htmlspecialchars($user['lastName'] ?? '') ?>"><br>

    <label>Username</label><br>
    <input type="text" name="username" required value="<?= htmlspecialchars($user['username'] ?? '') ?>"><br>

    <label>Email</label><br>
    <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>"><br>

    <label>Role</label><br>
    <select name="role_id" required>
        <option value="1" <?= isset($user['role_id']) && $user['role_id'] == 1 ? 'selected' : '' ?>>Client</option>
        <option value="2" <?= isset($user['role_id']) && $user['role_id'] == 2 ? 'selected' : '' ?>>Admin</option>
        <option value="3" <?= isset($user['role_id']) && $user['role_id'] == 3 ? 'selected' : '' ?>>Staff</option>
    </select><br>

    <?php if (empty($user)): ?>
    <label>Password</label><br>
    <input type="password" name="password" required><br>
    <?php endif; ?>

    <button type="submit"><?= $user ? "Update User" : "Create User" ?></button>
</form>

</body>
</html>
