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
    <link rel="stylesheet" href="admin.css">
</head>
<body>

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

<a href="admin_users.php">Back to Users List</a>

</body>
</html>
