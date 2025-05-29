<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controller/HomeController.php';
require_once __DIR__ . '/controller/WebsiteController.php';

$ctrl = new HomeController($conn);
$webCtrl = new WebsiteController($conn);
extract($webCtrl->index());
extract($ctrl->index());

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Retroshop</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Swipper JS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="header">
    <!-- Nav Header -->
    <nav class="nav container">
      <div class="nav__data">
        <a href="index.php" class="nav__logo">
          <img class ="nav__logo" src="<?= htmlspecialchars($webInfo['logo']); ?>"> <?= htmlspecialchars($webInfo['name']); ?>
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
            <a href="view/shop.php" class="nav__link">Shop <i class="ri-arrow-down-s-line dropdown__arrow"></i>
            </a>

            <ul class="dropdown__menu">
              <li>
                <a href="view/shop.php?platform=PlayStation" class="dropdown__link">
                  <img src ="data/pictures/playstation.png">PlayStation
                </a>
              </li>
              <li>
                <a href="view/shop.php?platform=Sega" class="dropdown__link">
                  <img src="data/pictures/sega (2).png">Sega
                </a>
              </li>
              <li>
                <a href="view/shop.php?platform=Nintendo" class="dropdown__link">
                  <img src="data/pictures/nintendo.png">Nintendo
                </a>
              </li>
            </ul>
          </li>
          <?php if(!isset($_SESSION['user_id'])): ?>
            <li class="dropdown__item">
              <a href="/retrogame-shop/login.php" class="nav__link">Sign Up / Login</a>
            </li>
          <?php else: ?>
          <!-- Profile Dropdown -->
          <li class="dropdown__item">
            <a href="#" class="nav__link">
              Profile <i class="ri-arrow-down-s-line dropdown__arrow"></i>
            </a>

            <ul class="dropdown__menu">
              <li>
                <a href="view/profile.php" class="dropdown__link">
                  <i class="ri-user-line"></i> Edit
                </a>
              </li>
              <li>
                <a href="view/wishlist.php" class="dropdown__link">
                  <i class="ri-heart-fill"></i> Wishlist
                </a>
              </li>
              <li>
                <a href="/retrogame-shop/logout.php" class="dropdown__link">
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

  <!-- Carousel Slider -->
  <div class="slider">
    <!-- Slider List Items -->
    <div class="wrapper swiper-wrapper">
      <?php foreach($recentGames as $rcgm): ?>
      <div class="item swiper-slide"
      <?= !empty($rcgm['bgImage'])
          ? "style=\"--bg-image: url('".htmlspecialchars($rcgm['bgImage'])."')\""
          : "" ?>
      >
        <div class="content">
          <h3 class="subtitle">NEW RELEASE</h3>
          <h2 class="title"><?= htmlspecialchars($rcgm['name'])?></h2>
          <p class="description"><?= htmlspecialchars($rcgm['details'])?></p>
          <a href="view/viewCart.php?action=add&id=<?= $rcgm['id'] ?>" class="button button-cart">Add To Cart</a>
          <a href="view/gameDetails.php?id=<?= $rcgm['id'] ?>" class="button button-info">Learn More</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Slider Controls -->
    <div class="controls">
      <ul class="pagination">
        <div class="slider-indicator"></div>
        <?php foreach($recentGames as $rcgm): ?>
        <li class="slider-tab"><?= htmlspecialchars($rcgm['name'])?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="slider-navigation">
      <button id="slide-prev" class="slider-button">
        <i class="ri-arrow-left-s-line"></i>
      </button>
      <button id="slide-next" class="slider-button">
        <i class="ri-arrow-right-s-line"></i>
      </button>
    </div>
  </div>

  <!-- Recent Games Shop Section -->
  <div class="shop">
    <div class="shop__container">
      <div class="shop__header">
        <h2 class="shop__title">Best-Selling Games</h2>
        <a href="products.php" class="shop__link">See All</a>
      </div>

      <div class="shop__content">
        <?php if (!empty($topSellingGames)): ?>
          <?php foreach ($topSellingGames as $game): ?>
            <div class="shop__card">
              <img src="<?= htmlspecialchars($game['image'] ?? 'data/pictures/default-game.png') ?>" 
                   alt="<?= htmlspecialchars($game['name']) ?>" 
                   class="shop__img">
              <h3 class="shop__name"><?= htmlspecialchars($game['name']) ?></h3>
              <p class="shop__price"><?= number_format($game['price'], 2) ?></p>
              <div class="shop__actions">
                <a href="view/gameDetails.php?id=<?= $game['id'] ?>" class="button shop-button-info">View Details</a>
                <a href="view/viewCart.php?action=add&id=<?= $game['id'] ?>" class="button shop-button-cart">Add To Cart</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="shop__empty">
            <p>No games available at the moment.</p>
            <a href="products.php" class="button button-info">Browse All Products</a>
          </div>
        <?php endif; ?>
      </div>
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

   <!-- Swipper JS CDN -->
   <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
   <!-- Custom JS File -->
  <script src="style/js/main.js"></script>
  <!-- Simple Add to Cart JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const addToCartForms = document.querySelectorAll('.add-to-cart-form');
      
      addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          const button = this.querySelector('.button-cart');
          const originalText = button.textContent;
          
          button.textContent = 'Adding...';
          button.disabled = true;
          
          // Re-enable button after form submission
          setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
          }, 1000);
        });
      });
    });
  </script>
</body>
</html>