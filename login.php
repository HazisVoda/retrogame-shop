<?php

include 'config.php';

session_start();

if(isset($_POST['submit'])){
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $select = $conn->prepare("SELECT * FROM `user` WHERE username = ? AND password = ?");
    $select->execute([$username, $password]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0){

        if($row['userType'] == 'Customer') {
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
        }
        elseif($row['userType'] == 'Staff') {
            $_SESSION['staff_id'] = $row['id'];
            header('location:index.php');
        }
        elseif($row['userType'] == 'Admin') {
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_home.php');
        }
        else {
            echo "<script>alert('Invalid user type!')</script>";
        }
    }
    else {
        echo "<script>alert('Incorrect username or password!')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
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

    <section class="form-container">
        <form action="" enctype="multipart/form-data" method="POST">
            <h3>Login</h3>
            <input type="text" name="username" required placeholder="Enter your username" class="box">
            <input type="password" name="password" required placeholder="Enter your password" class="box">
            <input type="submit" value="Login" name="submit" class="btn">
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </section>

</body>
</html>