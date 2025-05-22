<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: ../../login.php');
    exit();
}

require_once '../../controller/AdminGamesController.php';

$controller = new AdminGamesController($conn);
$data = $controller->listGames();
extract($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Games Management</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Games Management</h1>

<form method="GET" action="admin_games.php">
    <input type="text" name="search" placeholder="Search games by name or category" value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit">Search</button>
</form>

<a href="admin_game_form.php">Create New Game</a>

<div class="recent_orders">
    <table>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Category</th><th>Platform</th><th>Price</th><th>Stock</th><th>Date Released</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($games)): ?>
            <tr><td colspan="8">No games found.</td></tr>
        <?php else: ?>
            <?php foreach($games as $game): ?>
            <tr>
                <td><?= htmlspecialchars($game['id']) ?></td>
                <td><?= htmlspecialchars($game['name']) ?></td>
                <td><?= htmlspecialchars($game['category']) ?></td>
                <td><?= htmlspecialchars($game['platform']) ?></td>
                <td>$<?= htmlspecialchars(number_format($game['price'], 2)) ?></td>
                <td><?= htmlspecialchars($game['stock']) ?></td>
                <td><?= htmlspecialchars($game['dateReleased']) ?></td>
                <td>
                    <a href="admin_game_form.php?id=<?= $game['id'] ?>">Edit</a>
                    <form method="POST" action="../../controller/admin_games_delete.php" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this game?');">
                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div>
    Pages:
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" <?= $i == $page ? 'style="font-weight:bold"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>
</div>
</div>

</body>
</html>
