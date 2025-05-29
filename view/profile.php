<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/ProfileController.php';
require_once __DIR__ . '/../controller/WebsiteController.php';
$webCtrl = new WebsiteController($conn);
extract($webCtrl->index());

$ctrl = new ProfileController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl->save();
}
extract($ctrl->showForm());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../style/profile.css">
</head>
<body>
  <?php if ($showSuccess): ?>
    <div class="banner success">Profile updated successfully.</div>
  <?php endif; ?>

  <header class="header">
    <!-- Nav Header -->
    <nav class="nav container">
      <div class="nav__data">
        <a href="../index.php" class="nav__logo">
          <img class ="nav__logo" src="../<?= htmlspecialchars($webInfo['logo']); ?>"> <?= htmlspecialchars($webInfo['name']); ?>
        </a>

        <!-- Widgets -->
        <div class="nav__widgets">
          <div class="nav__toggle" id="nav-toggle">
            <i class="ri-menu-line nav__burger"></i>
            <i class="ri-close-line nav__close"></i>
          </div>
        </div>
      </div>

      <!-- Nav Menu -->

      <div class="nav__menu" id="nav-menu">
        <ul class ="nav__list">
          <li class="dropdown__item">
            <a href="shop.php" class="nav__link">Shop <i class="ri-arrow-down-s-line dropdown__arrow"></i>
            </a>

            <ul class="dropdown__menu">
              <li>
                <a href="shop.php?platform=PlayStation" class="dropdown__link">
                  <img src ="data/pictures/playstation.png">PlayStation
                </a>
              </li>
              <li>
                <a href="shop.php?platform=Sega" class="dropdown__link">
                  <img src="data/pictures/sega (2).png">Sega
                </a>
              </li>
              <li>
                <a href="shop.php?platform=Nintendo" class="dropdown__link">
                  <img src="data/pictures/nintendo.png">Nintendo
                </a>
              </li>
            </ul>
          </li>
          <?php if(!isset($_SESSION['user_id'])): ?>
            <li class="dropdown__item">
              <a href="../login.php" class="nav__link">Sign Up / Login</a>
            </li>
          <?php else: ?>
          <!-- Profile Dropdown -->
          <li class="dropdown__item">
            <a href="#" class="nav__link">
              Profile <i class="ri-arrow-down-s-line dropdown__arrow"></i>
            </a>

            <ul class="dropdown__menu">
              <li>
                <a href="profile.php" class="dropdown__link">
                  <i class="ri-user-line"></i> Edit
                </a>
              </li>
              <li>
                <a href="wishlist.php" class="dropdown__link">
                  <i class="ri-heart-fill"></i> Wishlist
                </a>
              </li>
              <li>
                <a href="../logout.php" class="dropdown__link">
                  <i class="ri-logout-box-line"></i> Logout
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
</header>

  <?php if ($showSuccess): ?>
    <div class="banner success">Profile updated successfully.</div>
  <?php endif; ?>

  <?php if ($activeTab==='profile'): ?>
    <section class="section profile-info">
      <?php if (!$editMode): ?>
        <img src="../<?= htmlspecialchars($user['image'] ?: 'data/pictures/defaultBg.jpg') ?>" alt="Profile Image" class="profile-image">
        <p><strong>Name:</strong>     <?= htmlspecialchars("$user[firstName] $user[lastName]") ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong>    <?= htmlspecialchars($user['email']) ?></p>
        <a href="?tab=profile&edit=1" class="button">Edit Profile</a>
      <?php else: ?>
        <form method="POST" enctype="multipart/form-data" class="form">
          <div><label>First Name</label>
            <input type="text" name="firstName" required value="<?= htmlspecialchars($user['firstName']) ?>">
          </div>
          <div><label>Last Name</label>
            <input type="text" name="lastName" required value="<?= htmlspecialchars($user['lastName']) ?>">
          </div>
          <div><label>Username</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>">
          </div>
          <div><label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
          </div>
          <div><label>New Password <small>(leave blank to keep)</small></label>
            <input type="password" name="password">
          </div>
          <div><label>Profile Image</label>
            <input type="file" name="image" accept="image/*">
          </div>
          <button type="submit" class="button">Save Changes</button>
          <a href="?tab=profile" class="button outline">Cancel</a>
        </form>
      <?php endif; ?>
    </section>
  <?php endif; ?>

  <footer class="footer">
    <div class="footer__container">
      <div class="footer__content">
        <div class="footer__section">
          <h3 class="footer__title">GameStore</h3>
          <p class="footer__description">Your ultimate destination for retro and modern gaming experiences.</p>
          <div class="footer__social">
            <a href="#" class="footer__social-link">
              <i class="ri-facebook-fill"></i>
            </a>
            <a href="#" class="footer__social-link">
              <i class="ri-twitter-fill"></i>
            </a>
            <a href="#" class="footer__social-link">
              <i class="ri-instagram-fill"></i>
            </a>
            <a href="#" class="footer__social-link">
              <i class="ri-youtube-fill"></i>
            </a>
          </div>
        </div>

        <div class="footer__section">
          <h4 class="footer__subtitle">Quick Links</h4>
          <ul class="footer__links">
            <li><a href="index.php" class="footer__link">Home</a></li>
            <li><a href="products.php" class="footer__link">All Games</a></li>
            <li><a href="categories.php" class="footer__link">Categories</a></li>
            <li><a href="about.php" class="footer__link">About Us</a></li>
            <li><a href="contact.php" class="footer__link">Contact</a></li>
          </ul>
        </div>

        <div class="footer__section">
          <h4 class="footer__subtitle">Customer Service</h4>
          <ul class="footer__links">
            <li><a href="support.php" class="footer__link">Help Center</a></li>
            <li><a href="shipping.php" class="footer__link">Shipping Info</a></li>
            <li><a href="returns.php" class="footer__link">Returns</a></li>
            <li><a href="faq.php" class="footer__link">FAQ</a></li>
            <li><a href="track-order.php" class="footer__link">Track Order</a></li>
          </ul>
        </div>

        <div class="footer__section">
          <h4 class="footer__subtitle">Account</h4>
          <ul class="footer__links">
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a href="profile.php" class="footer__link">My Profile</a></li>
              <li><a href="orders.php" class="footer__link">My Orders</a></li>
              <li><a href="wishlist.php" class="footer__link">Wishlist</a></li>
              <li><a href="logout.php" class="footer__link">Logout</a></li>
            <?php else: ?>
              <li><a href="login.php" class="footer__link">Login</a></li>
              <li><a href="register.php" class="footer__link">Register</a></li>
            <?php endif; ?>
            <li><a href="cart.php" class="footer__link">Shopping Cart</a></li>
          </ul>
        </div>
      </div>

      <div class="footer__bottom">
        <div class="footer__bottom-content">
          <p class="footer__copyright">
            &copy; <?= date('Y') ?> GameStore. All rights reserved.
          </p>
          <div class="footer__bottom-links">
            <a href="privacy.php" class="footer__bottom-link">Privacy Policy</a>
            <a href="terms.php" class="footer__bottom-link">Terms of Service</a>
            <a href="cookies.php" class="footer__bottom-link">Cookie Policy</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
