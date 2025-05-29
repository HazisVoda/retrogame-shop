<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/GameDetailsController.php';
require_once __DIR__ . '/../controller/WebsiteController.php';
$webCtrl = new WebsiteController($conn);
extract($webCtrl->index());

$ctrl = new GameDetailsController($conn);
extract($ctrl->show());

$quantity = 1;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($game['name']) ?> - GameStore</title>
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="../style/shop.css">
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

    <!-- Product Detail Section -->
    <div class="product-detail">
        <div class="product-detail__container">

            <!-- Product Images -->
            <div class="product__gallery">
                <div class="gallery__main">
                    <img id="mainImage"
                         src="../<?= htmlspecialchars($gallery[0] ?? 'data/pictures/defaultBg.jpg') ?>"
                         alt="<?= htmlspecialchars($game['name']) ?>"
                         class="gallery__main-image">
                </div>
                <!-- Thumbnail Gallery -->
                <?php if (!empty($gallery)): ?>
                <div class="gallery__thumbnails">
                    <?php foreach ($gallery as $i => $imgPath): ?>
                    <div class="thumbnail <?= $i === 0 ? 'active' : '' ?>"
                         onclick="document.getElementById('mainImage').src='../<?= htmlspecialchars($imgPath) ?>';">
                        <img src="../<?= htmlspecialchars($imgPath) ?>" alt="Thumbnail <?= $i + 1 ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product__info">
                <h1 class="product__title"><?= htmlspecialchars($game['name']) ?></h1>
                <div class="product__price">
                    <span class="price__current">€<?= number_format($game['price'], 2) ?></span>
                    <?php if (!empty($game['original_price']) && $game['original_price'] > $game['price']): ?>
                    <span class="price__original">€<?= number_format($game['original_price'], 2) ?></span>
                    <span class="price__discount"><?= round((($game['original_price'] - $game['price']) / $game['original_price']) * 100) ?>% OFF</span>
                    <?php endif; ?>
                </div>
                <div class="product__stock">
                    <?php if ($game['stock'] > 0): ?>
                    <div class="stock__available"><i class="ri-checkbox-circle-fill"></i><span><?= $game['stock'] ?> in stock</span></div>
                    <?php else: ?>
                    <div class="stock__unavailable"><i class="ri-close-circle-fill"></i><span>Out of stock</span></div>
                    <?php endif; ?>
                </div>
                <div class="product__description">
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($game['details'])) ?></p>
                </div>
                <div class="product__specs">
                    <h3>Specifications</h3>
                    <div class="specs__grid">
                        <div class="spec__item"><span class="spec__label">Platform:</span><span class="spec__value"><?= htmlspecialchars($game['platform'] ?? 'Multi-platform') ?></span></div>
                        <div class="spec__item"><span class="spec__label">Genre:</span><span class="spec__value"><?= htmlspecialchars($game['category'] ?? 'Action') ?></span></div>
                    </div>
                    <br>
                    <div class="spec__item"><span class="spec__label">Release Date:</span><span class="spec__value"><?= htmlspecialchars($game['dateReleased'] ?? 'Unknown') ?></span></div>
                </div>

                <!-- Quantity and Actions -->
                <div class="product__actions">
                    <?php if ($game['stock'] > 0): ?>
                    <form method="get" action="../view/viewCart.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                        <button type="submit" class="button shop-button-cart button-large">
                            <i class="ri-shopping-cart-line"></i> Add to Cart
                        </button>
                    </form>
                    <br>
                    <?php else: ?>
                    <button class="button button-disabled button-large" disabled><i class="ri-close-circle-line"></i> Out of Stock</button>
                    <?php endif; ?>

                    <form method="get" action="../view/wishlist.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?= $game['id'] ?>">
                        <button type="submit" class="button shop-button-info button-large">
                            <i class="ri-heart-line"></i> Add to Wishlist
                        </button>
                    </form>
                </div>
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
</body>
</html>
