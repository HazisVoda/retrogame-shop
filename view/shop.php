<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/GameController.php';
require_once __DIR__ . '/../controller/WebsiteController.php';
$webCtrl = new WebsiteController($conn);
extract($webCtrl->index());

$ctrl = new GameController($conn);
extract($ctrl->index());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop</title>
  <title>Shop</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Remix Icon CDN -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
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

  <div class="shop-page">
    <div class="shop-page__container">
      <!-- Enhanced Sidebar -->
      <aside class="shop__sidebar">
        <div class="sidebar__header">
          <h3 class="sidebar__title">
            <i class="ri-filter-3-line"></i>
            Filters
          </h3>
        </div>

        <form id="filterForm" method="GET" action="shop.php">
          <!-- Search -->
          <div class="filter__section">
            <h4 class="filter__title">
              <i class="ri-search-line"></i>
              Search Games
            </h4>
            <div class="search__container">
              <input
                type="text"
                name="search"
                placeholder="Search for games..."
                value="<?= htmlspecialchars($search) ?>"
                class="search__input"
              >
            </div>
          </div>

          <!-- Categories -->
          <div class="filter__section">
            <h4 class="filter__title">
              <i class="ri-folder-line"></i>
              Category
            </h4>
            <div class="filter__options">
              <label class="filter__checkbox">
                <input class="checkmark" type="radio" name="category" value="" <?= $category === '' ? 'checked' : '' ?>>
                <span class="filter__label">All Categories</span>
              </label>
              <?php foreach ($categories as $cat): ?>
                <label class="filter__checkbox">
                  <input
                    class="checkmark" 
                    type="radio"
                    name="category"
                    value="<?= htmlspecialchars($cat) ?>"
                    <?= $category === $cat ? 'checked' : '' ?>
                  >
                  <span class="filter__label"><?= htmlspecialchars($cat) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Platforms -->
          <div class="filter__section">
            <h4 class="filter__title">
              <i class="ri-device-line"></i>
              Platform
            </h4>
            <div class="filter__options">
              <label class="filter__checkbox">
                <input class="checkmark" type="radio" name="platform" value="" <?= $platform === '' ? 'checked' : '' ?>>
                <span class="filter__label">All Platforms</span>
              </label>
              <?php foreach ($platforms as $plat): ?>
                <label class="filter__checkbox">
                  <input
                    class="checkmark" 
                    type="radio"
                    name="platform"
                    value="<?= htmlspecialchars($plat) ?>"
                    <?= $platform === $plat ? 'checked' : '' ?>
                  >
                  <span class="filter__label"><?= htmlspecialchars($plat) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Price Range -->
          <div class="filter__section">
            <h4 class="filter__title">
              <i class="ri-money-dollar-circle-line"></i>
              Price Range
            </h4>
            <div class="price__range">
              <div class="price__inputs">
                <div class="price__group">
                  <label>Min</label>
                  <input
                    type="number"
                    step="0.01"
                    name="price_min"
                    placeholder="0.00"
                    value="<?= htmlspecialchars($priceMin) ?>"
                    class="price__input"
                  >
                </div>
                <div class="price__group">
                  <label>Max</label>
                  <input
                    type="number"
                    step="0.01"
                    name="price_max"
                    placeholder="999.99"
                    value="<?= htmlspecialchars($priceMax) ?>"
                    class="price__input"
                  >
                </div>
              </div>
            </div>
          </div>

          <div class="game__actions">
            <button type="submit" class="button shop-button-cart">
              <i class="ri-check-line"></i>
              Apply Filters
            </button>
          </div>
        </form>
      </aside>

      <!-- Main Content -->
      <div class="shop__main">
        <!-- Shop Header -->
        <div class="shop__header">
          <div class="shop__header-left">
            <h1 class="shop__title">All Games</h1>
            <p class="shop__count">Showing <?= count($games) ?> games</p>
          </div>
        </div>

        <!-- Games Grid (4 columns, homepage-style cards) -->
        <div class="games__grid">
          <?php if (!empty($games)): ?>
            <?php foreach ($games as $game): ?>
              <div class="game__card">
                <img src="../<?= htmlspecialchars($game['image']) ?>" 
                     alt="<?= htmlspecialchars($game['name']) ?>" 
                     class="game__img">
                
                <div class="game__content">
                  <h3 class="game__name"><?= htmlspecialchars($game['name']) ?></h3>
                  <p class="game__price">€<?= number_format($game['price'], 2) ?></p>
                  
                  <div class="game__actions">
                    <a href="gameDetails.php?id=<?= $game['id'] ?>" class="button shop-button-info">View Details</a>
                    <form method="POST" action="viewCart.php" class="add-to-cart-form">
                      <input type="hidden" name="action" value="add">
                      <input type="hidden" name="id" value="<?= $game['id'] ?>">
                      <button type="submit" class="button shop-button-cart">Buy</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="games__empty">
              <div class="empty__icon">
                <i class="ri-gamepad-line"></i>
              </div>
              <h3>No games found</h3>
              <p>Try adjusting your filters or search terms</p>
              <div class="game__actions">
                <a href="shop.php" class="button shop-button-info">
                  Clear All Filters
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Enhanced Pagination -->
        <div class="pagination">
          <div class="pagination__info">
            Page <?= $page ?> of <?= $totalPages ?>
          </div>
          <br>
          
          <div class="pagination__controls">
            <?php if ($page > 1): ?>
              <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="pagination__btn">
                <i class="ri-skip-back-line"></i>
              </a>
              <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="pagination__btn">
                <i class="ri-arrow-left-line"></i>
              </a>
            <?php endif; ?>
            
            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($totalPages, $page + 2);
            
            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
              <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                 class="pagination__btn <?= $i === $page ? 'pagination__btn--active' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
              <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="pagination__btn">
                <i class="ri-arrow-right-line"></i>
              </a>
              <a href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>" class="pagination__btn">
                <i class="ri-skip-forward-line"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Sidebar Overlay -->
  <div class="sidebar__overlay" onclick="closeMobileFilters()"></div>

  <!-- Footer -->
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
  <script src="../style/js/main.js"></script>
</body>
</html>
