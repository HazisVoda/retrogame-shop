<header class="header">
    <!-- Nav Header -->
    <nav class="nav container">
      <div class="nav__data">
        <a href="#" class="nav__logo">
          <img class ="nav__logo" src=".\data\pictures\shop logo png.png"></img> Retroshop
        </a>

        <!-- Widgets -->
        <div class="nav__widgets">
          <a class="nav__search" href="#">
            <i class="ri-search-line"></i> Search
          </a>

          <div class="nav__toggle" id="nav-toggle">
            <i class="ri-menu-line nav__burger"></i>
            <i class="ri-close-line nav__close"></i>
          </div>
        </div>
      </div>

      <!-- Search Menu -->

      <!--
      <div class="nav__search-menu" id="nav-search">
        <input type="text" placeholder="Search..." class="nav__input">
        <button class="nav__close-search" id="nav-close-search">
          <i class="ri-close-line"></i>
        </button>
      -->

      <!-- Nav Menu -->

      <div class="nav__menu" id="nav-menu">
        <ul class ="nav__list">
          <li class="dropdown__item">
            <a href="#" class="nav__link">Explore</a>
          </li>
          <li class="dropdown__item">
            <a href="#" class="nav__link">Shop <i class="ri-arrow-down-s-line dropdown__arrow"></i>
            </a>

            <ul class="dropdown__menu">
              <li>
                <a href="" class="dropdown__link">
                  <img src ="data/pictures/playstation.png">PlayStation
                </a>
              </li>
              <li>
                <a href="" class="dropdown__link">
                  <img src="data/pictures/sega (2).png">Sega
                </a>
              </li>

              <!-- Nav Sub Menu -->

              <li class="dropdown__subitem">
                <div class="dropdown__link">
                  <img src="data/pictures/nintendo.png">Nintendo <i class="ri-add-line dropdown__add"></i>
                </div>

                <ul class="dropdown__submenu">
                  <li>
                    <a href="" class="dropdown__sublink">
                      Gameboy
                    </a>
                  </li>

                  <li>
                    <a href="" class="dropdown__sublink">
                      Wii 
                    </a>
                  </li>

                  <li>
                    <a href="" class="dropdown__sublink">
                      DS
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="dropdown__item">
            <a href="#" class="nav__link">Contact Us</a>
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
                <a href="" class="dropdown__link">
                  <i class="ri-user-line"></i> Edit
                </a>
              </li>
              <li>
                <a href="" class="dropdown__link">
                  <i class="ri-heart-fill"></i> Favorites
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