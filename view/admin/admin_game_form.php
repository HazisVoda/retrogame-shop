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
    <link rel="stylesheet" href="admin.css" />
</head>
<body>

<h1><?= $game ? "Edit Game" : "Create New Game" ?></h1>

<form method="POST" action="../../controller/admin_games_save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $game['id'] ?? '' ?>">

    <!-- Existing fields... -->
    <label>Name</label><br>
    <input type="text" name="name" required value="<?= htmlspecialchars($game['name'] ?? '') ?>">

    <label>Category</label><br>
    <input type="text" name="category" required value="<?= htmlspecialchars($game['category'] ?? '') ?>">

    <label>Platform</label><br>
    <input type="text" name="platform" required value="<?= htmlspecialchars($game['platform'] ?? '') ?>">

    <label>Details</label><br>
    <input type="text" name="details" required value="<?= htmlspecialchars($game['details'] ?? '') ?>">

    <label>Price</label><br>
    <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($game['price'] ?? '') ?>">

    <label>Stock</label><br>
    <input type="number" name="stock" required value="<?= htmlspecialchars($game['stock'] ?? '') ?>">

    <?php if (!empty($game['image'])): ?>
        <input type="hidden" name="existingImage" value="<?= htmlspecialchars($game['image']) ?>">
        <label>Current Cover Image</label><br>
        <img src="../../<?= htmlspecialchars($game['image']) ?>" alt="Cover Image" style="height:30px; width:auto;">
    <?php endif; ?>
    <label><?= $game ? 'Change' : 'Upload' ?> Background Image</label><br>
    <input type="file" name="image" accept="image/*"><br>

    <?php if (!empty($game['bgImage'])): ?>
        <input type="hidden" name="existingBgImage" value="<?= htmlspecialchars($game['bgImage']) ?>">
        <label>Current Background Image</label><br>
        <img src="../../<?= htmlspecialchars($game['bgImage']) ?>" alt="Background Image" style="height:50px; width:auto;">
    <?php endif; ?>
    <label><?= $game ? 'Change' : 'Upload' ?> Background Image</label><br>
    <input type="file" name="bgImage" accept="image/*"><br>

    <label>Date Released</label><br>
    <input type="date" name="dateReleased" required value="<?= htmlspecialchars($game['dateReleased'] ?? '') ?>">

    <button type="submit"><?= $game ? "Update Game" : "Create Game" ?></button>
</form>

<a href="admin_games.php">Back to Games List</a>

</body>
</html>
