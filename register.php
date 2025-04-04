<?php

include 'config.php';

if(isset($_POST['submit'])){
    $firstName = htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $cpassword = htmlspecialchars($_POST['cpassword'], ENT_QUOTES, 'UTF-8');

    $select = $conn->prepare("SELECT * FROM `user` WHERE email = ? OR username = ?");
    $select->execute([$email, $username]);

    if($select->rowCount() > 0){
        echo "<script>alert('User already exists!')</script>";
    }else{
        if($password != $cpassword){
            echo "<script>alert('Password not matched!')</script>";
        }
        else {
            $insert = $conn->prepare("INSERT INTO `user`(firstName, lastName, username, email, password) VALUES(?,?,?,?,?)");
            $insert->execute([$firstName, $lastName, $username, $email, $password]);
            echo "<script>alert('Registered successfully!')</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
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
    <!-- Left Section -->
     <div class="register-container">
     </div>

    <!-- Register Section -->
    <section class="form-container">
        <form action="" enctype="multipart/form-data" method="POST">
            <h3>Register</h3>
            <input type="text" name="firstName" required placeholder="Enter your first name" class="box">
            <input type="text" name="lastName" required placeholder="Enter your last name" class="box">
            <input type="text" name="username" required placeholder="Enter your username" class="box">
            <input type="email" name="email" required placeholder="Enter your email" class="box">
            <input type="password" name="password" required placeholder="Enter your password" class="box">
            <input type="password" name="cpassword" required placeholder="Confirm your password" class="box">
            <input type="submit" value="Register now" name="submit" class="btn">
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </section>

</body>
</html>