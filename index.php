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
          <h3 class="subtitle">Slide 01</h3>
          <h2 class="title">TITLE</h2>
          <p class="description">Description</p>
          <a href="" class="button">Learn More</a>
        </div>
      </div>
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">Slide 02</h3>
          <h2 class="title">TITLE</h2>
          <p class="description">Description</p>
          <a href="" class="button">Learn More</a>
        </div>
      </div>
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">Slide 03</h3>
          <h2 class="title">TITLE</h2>
          <p class="description">Description</p>
          <a href="" class="button">Learn More</a>
        </div>
      </div>
      <div class="item swiper-slide">
        <div class="content">
          <h3 class="subtitle">Slide 04</h3>
          <h2 class="title">TITLE</h2>
          <p class="description">Description</p>
          <a href="" class="button">Learn More</a>
        </div>
      </div>
    </div>

    <!-- Slider Controls -->
    <div class="controls">
      <ul class="pagination">
        <div class="slider-indicator"></div>
        <li class="slider-tab">Tab 01</li>
        <li class="slider-tab">Tab 02</li>
        <li class="slider-tab">Tab 03</li>
        <li class="slider-tab">Tab 04</li>
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

   <!-- Swipper JS CDN -->
   <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
   <!-- Custom JS File -->
  <script src="main.js"></script>
</body>
</html>