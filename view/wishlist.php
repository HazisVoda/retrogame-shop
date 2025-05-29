<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/WishlistController.php';
require_once __DIR__ . '/../controller/WebsiteController.php';
$webCtrl = new WebsiteController($conn);
extract($webCtrl->index());

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist - GameStore</title>
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="breadcrumb__container">
            <nav class="breadcrumb__nav">
                <a href="../index.php" class="breadcrumb__link">
                    <i class="ri-home-line"></i> Home
                </a>
                <i class="ri-arrow-right-s-line breadcrumb__separator"></i>
                <span class="breadcrumb__current">My Wishlist</span>
            </nav>
        </div>
    </div>

    <!-- Wishlist Content -->
    <div class="wishlist-page">
        <div class="wishlist-page__container">
            <div class="wishlist__header">
                <h1 class="wishlist__title">
                    <i class="ri-heart-fill"></i>
                    My Wishlist
                </h1>
                <?php if (!empty($games)): ?>
                    <p class="wishlist__count"><?= count($games) ?> item(s) in your wishlist</p>
                <?php endif; ?>
            </div>

            <?php if (empty($games)): ?>
                <!-- Empty Wishlist -->
                <div class="wishlist__empty">
                    <div class="empty__icon">
                        <i class="ri-heart-line"></i>
                    </div>
                    <h2>Your wishlist is empty</h2>
                    <p>Save your favorite games here so you can easily find them later.</p>
                    <a href="shop.php" class="button button-cart button-large">
                        <i class="ri-gamepad-line"></i>
                        Browse Games
                    </a>
                </div>
            <?php else: ?>
                <!-- Wishlist Actions -->
                <div class="wishlist__actions">
                    <div class="actions__left">
                        <form method="POST" action="viewCart.php" class="add-all-form">
                            <?php foreach ($games as $game): ?>
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id" value="<?= $game['id'] ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="button checkout-btn">
                                <i class="ri-shopping-cart-line"></i>
                                Add All to Cart
                            </button>
                        </form>
                    </div>
                    
                    <div class="actions__right">
                        <div class="view__toggle">
                            <button class="view__btn active" onclick="switchView('grid')" data-view="grid">
                                <i class="ri-grid-line"></i>
                            </button>
                            <button class="view__btn" onclick="switchView('list')" data-view="list">
                                <i class="ri-list-check"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Wishlist Items -->
                <div class="wishlist__items" id="wishlistItems">
                    <?php foreach ($games as $game): ?>
                        <div class="wishlist__item">
                            <div class="item__image">
                                <a href="gameDetails.php?id=<?= $game['id'] ?>">
                                    <img src="../<?= htmlspecialchars($game['image']) ?>" 
                                         alt="<?= htmlspecialchars($game['name']) ?>">
                                </a>
                                <div class="item__overlay">
                                    <a href="gameDetails.php?id=<?= $game['id'] ?>" class="overlay__btn">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="item__content">
                                <h3 class="item__name">
                                    <a href="gameDetails.php?id=<?= $game['id'] ?>">
                                        <?= htmlspecialchars($game['name']) ?>
                                    </a>
                                </h3>
                                
                                <div class="item__meta">
                                    <span class="item__platform">
                                        <i class="ri-device-line"></i>
                                        <?= htmlspecialchars($game['platform'] ?? 'Multi-platform') ?>
                                    </span>
                                    <span class="item__category">
                                        <i class="ri-folder-line"></i>
                                        <?= htmlspecialchars($game['category'] ?? 'Action') ?>
                                    </span>
                                </div>
                                
                                <p class="item__price">€<?= number_format($game['price'], 2) ?></p>
                                
                                <div class="item__rating">
                                    <div class="rating__stars">
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-line"></i>
                                    </div>
                                    <span class="rating__text">(4.2)</span>
                                </div>
                                
                                <div class="item__actions">
                                    <form method="POST" action="viewCart.php" class="add-to-cart-form">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                                        <button type="submit" class="button checkout-btn">
                                            <i class="ri-shopping-cart-line"></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                    
                                    <form method="POST" class="remove-form">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                                        <button type="submit" class="button button-remove">
                                            <i class="ri-heart-fill"></i>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
            <li><a href="index.php" class="footer__link">Home</a></li>
            <li><a href="view/shop.php" class="footer__link">All Games</a></li>
            <li><a href="#" class="footer__link">Contact Us: <?= htmlspecialchars($webInfo['email'])?></a></li>
          </ul>
        </div>

        <div class="footer__section">
          <h4 class="footer__subtitle">Account</h4>
          <ul class="footer__links">
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a href="profile.php" class="footer__link">My Profile</a></li>
              <li><a href="wishlist.php" class="footer__link">My Wishlist</a></li>
              <li><a href="cart.php" class="footer__link">My Cart</a></li>
              <li><a href="logout.php" class="footer__link">Logout</a></li>
            <?php else: ?>
              <li><a href="login.php" class="footer__link">Login</a></li>
              <li><a href="register.php" class="footer__link">Register</a></li>
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
        function switchView(viewType) {
            const items = document.getElementById('wishlistItems');
            const buttons = document.querySelectorAll('.view__btn');
            
            // Update active button
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.view === viewType) {
                    btn.classList.add('active');
                }
            });
            
            // Update view
            if (viewType === 'list') {
                items.classList.add('wishlist__items--list');
            } else {
                items.classList.remove('wishlist__items--list');
            }
        }

        // Add to cart form handling
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartForms = document.querySelectorAll('.add-to-cart-form');
            addToCartForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('.button-cart');
                    const originalText = button.innerHTML;
                    
                    button.innerHTML = '<i class="ri-loader-4-line"></i> Adding...';
                    button.disabled = true;
                    
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 1000);
                });
            });
        });
    </script>
</body>
</html>
