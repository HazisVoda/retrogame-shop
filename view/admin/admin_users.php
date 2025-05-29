<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: ../../login.php');
    exit();
}

require_once '../../controller/AdminUsersController.php';
require_once '../../controller/WebsiteController.php';

$controller = new AdminUsersController($conn);
$data = $controller->listUsers();

extract($data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users Management</title>
    <meta charset="UTF-8" />
    <style href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css"></style>
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

<h2>Users Management</h2>

<form method="GET" action="admin_users.php">
    <input type="text" name="search" placeholder="Search username" value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit">Search</button>
</form>

<a href="admin_users_form.php">Create New User</a>

<div class="recent_orders">
    <table>
        <tr>
            <thead>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($users)): ?>
            <tr><td colspan="7">No users found.</td></tr>
            <?php else: ?>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['firstName']) ?></td>
                    <td><?= htmlspecialchars($user['lastName']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role_name']) ?></td>
                    <td>
                        <a href="admin_users_form.php?id=<?= $user['id'] ?>">Edit</a>
                        <form method="POST" action="../../controller/admin_users_delete.php" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div>
    Pages:
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" <?= $i == $page ? 'style="font-weight:bold"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>
</div>

</body>
</html>
