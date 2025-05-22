<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/ProfileController.php';

$controller = new ProfileController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->save();
}

extract($controller->showForm());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="admin/admin.css">
</head>
<body>
  <h1>Edit Your Profile</h1>

  <?php if (isset($_GET['updated'])): ?>
    <p class="success">Profile updated successfully!</p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <!-- Preview & preserve existing image -->
    <?php if (!empty($user['image'])): ?>
      <img src="../<?= htmlspecialchars($user['image']) ?>" alt="Profile" style="max-width:120px"><br>
      <input type="hidden" name="existingImage" value="<?= htmlspecialchars($user['image']) ?>">
    <?php endif; ?>

    <label>First Name</label><br>
    <input type="text" name="firstName" required value="<?= htmlspecialchars($user['firstName']) ?>"><br>

    <label>Last Name</label><br>
    <input type="text" name="lastName" required value="<?= htmlspecialchars($user['lastName']) ?>"><br>

    <label>Username</label><br>
    <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>"><br>

    <label>Email</label><br>
    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"><br>

    <label>Password <small>(leave blank to keep current)</small></label><br>
    <input type="password" name="password"><br>

    <label>Profile Image</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit">Update Profile</button>
  </form>

  <p><a href="index.php">← Back to Home</a></p>
</body>
</html>
