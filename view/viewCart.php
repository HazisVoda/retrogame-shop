<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/CartController.php';
require_once __DIR__ . '/../controller/WebsiteController.php';
$webCtrl = new WebsiteController($conn);
extract($webCtrl->index());

$ctrl = new CartController($conn);
// Centralize all actions via handle()
if (isset($_REQUEST['action'])) {
    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
    $ctrl->handle($_REQUEST['action'], $id);
}

extract($ctrl->index());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - GameStore</title>
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/cart-wishlist.css">
</head>
<body>
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
                  <img src ="../data/pictures/playstation.png">PlayStation
                </a>
              </li>
              <li>
                <a href="shop.php?platform=Sega" class="dropdown__link">
                  <img src="../data/pictures/sega (2).png">Sega
                </a>
              </li>
              <li>
                <a href="shop.php?platform=Nintendo" class="dropdown__link">
                  <img src="../data/pictures/nintendo.png">Nintendo
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
                <a href="viewCart.php" class="dropdown__link">
                  <i class="ri-shopping-cart-line"></i> Cart
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

    <!-- Success Message -->
    <?php if ($showSuccess): ?>
        <div class="success-banner">
            <div class="success-banner__container">
                <div class="success-banner__content">
                    <i class="ri-check-double-line"></i>
                    <div class="success-banner__text">
                        <h3>Order Completed Successfully!</h3>
                    </div>
                    <button class="success-banner__close" onclick="closeSuccessBanner()">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Cart Content -->
    <div class="cart-page">
        <div class="cart-page__container">
            <div class="cart__header">
                <h1 class="cart__title">
                    <i class="ri-shopping-cart-line"></i>
                    Shopping Cart
                </h1>
                <?php if (!empty($games)): ?>
                    <p class="cart__count"><?= count($games) ?> item(s) in your cart</p>
                <?php endif; ?>
            </div>

            <?php if (empty($games)): ?>
                <!-- Empty Cart -->
                <div class="cart__empty">
                    <div class="empty__icon">
                        <i class="ri-shopping-cart-line"></i>
                    </div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any games to your cart yet.</p>
                    <a href="shop.php" class="button button-cart button-large">
                        <i class="ri-gamepad-line"></i>
                        Browse Games
                    </a>
                </div>
            <?php else: ?>
                <!-- Cart Items -->
                <div class="cart__content">
                    <div class="cart__items">
                        <?php foreach ($games as $game): ?>
                            <div class="cart__item">
                                <div class="item__image">
                                    <img src="../<?= htmlspecialchars($game['image']) ?>" 
                                         alt="<?= htmlspecialchars($game['name']) ?>">
                                </div>
                                
                                <div class="item__details">
                                    <h3 class="item__name">
                                        <a href="gameDetails.php?id=<?= $game['id'] ?>">
                                            <?= htmlspecialchars($game['name']) ?>
                                        </a>
                                    </h3>
                                    <p class="item__price">€<?= number_format($game['price'], 2) ?></p>
                                    <div class="item__meta">
                                        <span class="item__platform">
                                            <i class="ri-device-line"></i>
                                            <?= htmlspecialchars($game['platform'] ?? 'Multi-platform') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="item__subtotal">
                                    <span class="subtotal__label">Subtotal:</span>
                                    <span class="subtotal__amount">€<?= number_format($game['subtotal'], 2) ?></span>
                                </div>

                                <div class="item__actions">
                                    <form method="POST" class="remove-form">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                                        <button type="submit" class="button button-remove">
                                            <i class="ri-delete-bin-line"></i>
                                            Remove
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="wishlist.php">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart__summary">
                        <div class="summary__card">
                            <h3 class="summary__title">Order Summary</h3>
                            
                            <div class="summary__details">
                                <div class="summary__row summary__total">
                                    <span>Total:</span>
                                    <span>€<?= number_format($total, 2) ?></span>
                                </div>
                            </div>

                            <div class="summary__actions">
                                <form method="POST">
                                    <input type="hidden" name="action" value="checkout">
                                    <button type="submit" class="button button-cart button-large checkout-btn">
                                        <i class="ri-secure-payment-line"></i>
                                        Proceed to Checkout
                                    </button>
                                </form>
                                
                                <a href="shop.php" class="button button-outline button-large">
                                    <i class="ri-arrow-left-line"></i>
                                    Continue Shopping
                                </a>
                                
                                <form method="POST" class="clear-cart-form">
                                    <input type="hidden" name="action" value="clear">
                                    <button type="submit" class="button button-remove button-large">
                                        <i class="ri-delete-bin-line"></i>
                                        Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
    <div class="footer__container">
      <div class="footer__content">
        <div class="footer__section">
          <h3 class="footer__title"><?= htmlspecialchars($webInfo['name'])?></h3>
          <p class="footer__description"><?= htmlspecialchars($webInfo['footer'])?></p>
          <div class="footer__social">
            <a href="<?= htmlspecialchars($webInfo['facebook'])?>" class="footer__social-link">
              <i class="ri-facebook-fill"></i>
            </a>
            <a href="<?= htmlspecialchars($webInfo['twitter'])?>" class="footer__social-link">
              <i class="ri-twitter-fill"></i>
            </a>
            <a href="<?= htmlspecialchars($webInfo['instagram'])?>" class="footer__social-link">
              <i class="ri-instagram-fill"></i>
            </a>
            <a href="<?= htmlspecialchars($webInfo['youtube'])?>" class="footer__social-link">
              <i class="ri-youtube-fill"></i>
            </a>
          </div>
        </div>

        <div class="footer__section">
          <h4 class="footer__subtitle">Quick Links</h4>
          <ul class="footer__links">
            <li><a href="../index.php" class="footer__link">Home</a></li>
            <li><a href="shop.php" class="footer__link">All Games</a></li>
            <li><a href="" class="footer__link">Contact Us: <?= htmlspecialchars($webInfo['email'])?></a></li>
          </ul>
        </div>

        <div class="footer__section">
          <h4 class="footer__subtitle">Account</h4>
          <ul class="footer__links">
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a href="profile.php" class="footer__link">My Profile</a></li>
              <li><a href="wishlist.php" class="footer__link">My Wishlist</a></li>
              <li><a href="viewCart.php" class="footer__link">My Cart</a></li>
              <li><a href="../logout.php" class="footer__link">Logout</a></li>
            <?php else: ?>
              <li><a href="../login.php" class="footer__link">Login</a></li>
              <li><a href="../login.php" class="footer__link">Register</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <div class="footer__bottom">
        <div class="footer__bottom-content">
          <p class="footer__copyright">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($webInfo['name'])?>. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </footer>

    <!-- Custom JS -->
    <script src="../style/js/main.js"></script>
    <script>
        function closeSuccessBanner() {
            const banner = document.querySelector('.success-banner');
            banner.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => banner.remove(), 300);
        }

        // Auto-hide success banner after 10 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successBanner = document.querySelector('.success-banner');
            if (successBanner) {
                setTimeout(() => {
                    closeSuccessBanner();
                }, 10000);
            }
        });
    </script>
</body>
</html>
