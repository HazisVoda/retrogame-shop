<?php

include 'config.php';

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shop</title>
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
  <?php include 'header.php'; ?>
  <!-- Carousel Slider -->
  <div class="slider">
    <!-- Slider List Items -->
    <div class="wrapper swiper-wrapper">
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">NEW RELEASE</h3>
          <h2 class="title">Metal Gear Solid 3: Subsistence</h2>
          <p class="description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Adipisci pariatur sit quaerat voluptate, autem, aperiam ea quidem iste nulla, molestias libero eaque rerum officia ipsa illo veritatis aliquam quibusdam vero.</p>
          <a href="" class="button button-cart">Add To Cart</a>
          <a href="" class="button button-info">Learn More</a>
        </div>
      </div>
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">NEW RELEASE</h3>
          <h2 class="title">Fire Emblem</h2>
          <p class="description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Adipisci pariatur sit quaerat voluptate, autem, aperiam ea quidem iste nulla, molestias libero eaque rerum officia ipsa illo veritatis aliquam quibusdam vero.</p>
          <a href="" class="button button-cart">Add To Cart</a>
          <a href="" class="button button-info">Learn More</a>
        </div>
      </div>
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">NEW RELEASE</h3>
          <h2 class="title">Pokemon Mystery Dungeon: Red Rescue Team</h2>
          <p class="description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Adipisci pariatur sit quaerat voluptate, autem, aperiam ea quidem iste nulla, molestias libero eaque rerum officia ipsa illo veritatis aliquam quibusdam vero.</p>
          <a href="" class="button button-cart">Add To Cart</a>
          <a href="" class="button button-info">Learn More</a>
        </div>
      </div>
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">NEW RELEASE</h3>
          <h2 class="title">The Legend of Zelda: The Minnish Cap</h2>
          <p class="description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Adipisci pariatur sit quaerat voluptate, autem, aperiam ea quidem iste nulla, molestias libero eaque rerum officia ipsa illo veritatis aliquam quibusdam vero.</p>
          <a href="" class="button button-cart">Add To Cart</a>
          <a href="" class="button button-info">Learn More</a>
        </div>
      </div>
    </div>

    <!-- Slider Controls -->
    <div class="controls">
      <ul class="pagination">
        <div class="slider-indicator"></div>
        <li class="slider-tab">Metal Gear Solid 3: Subsistence</li>
        <li class="slider-tab">Fire Emblem</li>
        <li class="slider-tab">Pokemon Mystery Dungeon: Red Rescue Team</li>
        <li class="slider-tab">The Legend of Zelda: The Minnish Cap</li>
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

  <div class="shop">
    <div class="shop__container">
      <div class="shop__header">
        <h2 class="shop__title">Shop</h2>
        <a href="#" class="shop__link">See All</a>
      </div>

      <div class="shop__content">
        <div class="shop__card">
          <img src="data/pictures/playstation.png" alt="" class="shop__img">
          <h3 class="shop__name">PlayStation</h3>
          <p class="shop__price">$49.99</p>
          <a href="#" class="button button-cart">Add To Cart</a>
        </div>

        <div class="shop__card">
          <img src="data/pictures/sega (2).png" alt="" class="shop__img">
          <h3 class="shop__name">Sega</h3>
          <p class="shop__price">$39.99</p>
          <a href="#" class="button button-cart">Add To Cart</a>
        </div>

        <div class="shop__card">
          <img src="data/pictures/nintendo.png" alt="" class="shop__img">
          <h3 class="shop__name">Nintendo</h3>
          <p class="shop__price">$29.99</p>
          <a href="#" class="button button-cart">Add To Cart</a>
        </div>

        <!-- Add more shop cards as needed -->
      </div>
    </div>
  </div>

   <!-- Swipper JS CDN -->
   <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
   <!-- Custom JS File -->
  <script src="style/js/main.js"></script>
</body>
</html>