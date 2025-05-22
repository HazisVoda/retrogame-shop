<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/WishlistController.php';

$ctrl = new WishlistController($conn);

// Route add/remove actions via GET params
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'add') {
        $ctrl->add($id);
    } elseif ($_GET['action'] === 'remove') {
        $ctrl->remove($id);
    }
}

// Fetch the current wishlist
extract($ctrl->index());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Wishlist</title>
  <link rel="stylesheet" href="/../style.css">
</head>
<body>
  <h1>Your Wishlist</h1>

  <?php if (empty($games)): ?>
    <p>Your wishlist is empty. <a href="games.php">Browse games</a>.</p>
  <?php else: ?>
    <div class="game-grid">
      <?php foreach ($games as $g): ?>
        <div class="game-card">
          <a href="game_details.php?id=<?= $g['id'] ?>">
            <img src="<?= htmlspecialchars($g['image']) ?>" alt="<?= htmlspecialchars($g['name']) ?>">
            <h2><?= htmlspecialchars($g['name']) ?></h2>
            <p>€<?= number_format($g['price'], 2) ?></p>
          </a>
          <button onclick="location.href='wishlist.php?action=remove&id=<?= $g['id'] ?>'">
            Remove
          </button>
          <button onclick="location.href='cart.php?action=add&id=<?= $g['id'] ?>'">
            Add to Cart
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p><a href="shop.php">← Continue Browsing</a></p>
</body>
</html>
