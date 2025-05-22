<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/GameController.php';
$ctrl = new GameController($conn);
extract($ctrl->index());
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Game Shop</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <h1>Game Shop</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="cart.php">Cart</a>
                <a href="wishlist.php">Wishlist</a>
                <a href="profile.php">Profile</a>
            </nav>
        </header>

        <main>
            <h1>Available Games</h1>
            <div class="game-grid">
              <?php foreach ($games as $g): ?>
                <div class="game-card">
                  <a href="gameDetails.php?id=<?= $g['id'] ?>">
                    <img src="<?= htmlspecialchars($g['image']) ?>" alt="<?= htmlspecialchars($g['name']) ?>">
                    <h2><?= htmlspecialchars($g['name']) ?></h2>
                    <p>€<?= number_format($g['price'], 2) ?></p>
                  </a>
                  <button onclick="location.href='cart.php?action=add&id=<?= $g['id'] ?>'">
                    Add to Cart
                  </button>
                  <button onclick="location.href='wishlist.php?action=add&id=<?= $g['id'] ?>'">
                    ❤️ Wishlist
                  </button>
                </div>
              <?php endforeach; ?>
            </div>
        </main>

        <footer>
            <p>&copy; 2023 Game Shop. All rights reserved.</p>
        </footer>
