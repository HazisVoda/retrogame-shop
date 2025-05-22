<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/CartController.php';

$ctrl = new CartController($conn);

// Handle actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    match ($_GET['action']) {
        'add' => $ctrl->add($id),
        'remove' => $ctrl->remove($id),
        default => null,
    };
} elseif (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $ctrl->clear();
} elseif (isset($_GET['action']) && $_GET['action'] === 'checkout') {
    $ctrl->checkout();
}

extract($ctrl->index());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <h1>Your Cart</h1>

  <?php if (empty($games)): ?>
    <p>Your cart is empty. <a href="games.php">Browse games</a>.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Game</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($games as $g): ?>
          <tr>
            <td><?= htmlspecialchars($g['name']) ?></td>
            <td>$<?= number_format($g['price'], 2) ?></td>
            <td><?= $g['quantity'] ?></td>
            <td>$<?= number_format($g['subtotal'], 2) ?></td>
            <td>
              <a href="cart.php?action=remove&id=<?= $g['id'] ?>">Remove</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p>Total: €<?= number_format($total, 2) ?></p>
    <p>
      <a href="cart.php?action=checkout">Checkout</a> |
      <a href="cart.php?action=clear">Clear Cart</a>
    </p>
  <?php endif; ?>
</body>
</html>