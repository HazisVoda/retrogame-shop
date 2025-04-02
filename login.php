<?php

include 'config.php';

if(isset($_POST['submit'])){
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $select = $conn->prepare("SELECT * FROM `user` WHERE username = ? AND password = ?");
    $select->execute([$username, $password]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0){

        if($row['user_type'] == 'Customer') {
            $_SESSION['id'] = $row['id'];
            header('location: index.php');
        }
        elseif($row['user_type'] == 'Staff') {
            $_SESSION['id'] = $row['id'];
            header('location: staff.php');
        }
        elseif($row['user_type'] == 'Admin') {
            $_SESSION['id'] = $row['id'];
            header('location: admin.php');
        }
        else {
            echo "<script>alert('Invalid user type!')</script>";
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