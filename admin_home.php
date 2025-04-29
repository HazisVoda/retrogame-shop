<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if(!isset($admin_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Home</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Remix Icon CDN -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <!-- Swipper JS CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <!-- Custom CSS File -->
        <link rel="stylesheet" href="style\style.css">
    </head>
    <body>
        <?php include 'header.php'; ?>
        <div class="container">
            <div class="sidebar">

            </div>
            <div class="dashboard">
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="style\js\main.js"></script>
    </body>
</html>