<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/GameController.php';
$ctrl = new GameController($conn);
extract($ctrl->show());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($game['name']) ?></title>
    <link rel="stylesheet" href="../style.css">
</html>
<body>
    <h1><?= htmlspecialchars($game['name']) ?></h1>
    <img src="<?= htmlspecialchars($game['image']) ?>" alt="">
    <p><?= nl2br(htmlspecialchars($game['details'])) ?></p>
    <p>Price: €<?= number_format($game['price'],2) ?></p>
    <p>Stock: <?= $game['stock'] ?></p>
    <a href="cart.php?action=add&id=<?= $game['id'] ?>">Add to Cart</a>
    <a href="wishlist.php?action=add&id=<?= $game['id'] ?>">Add to Wishlist</a>
</body>
