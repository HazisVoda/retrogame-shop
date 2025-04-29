<?php

include 'config.php';

session_start();

if(isset($_POST['loginSubmit'])){
    $username = htmlspecialchars($_POST['loginUsername'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['loginPassword'], ENT_QUOTES, 'UTF-8');

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

if(isset($_POST['registerSubmit'])){
    $firstName = htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($_POST['registerUsername'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['registerPassword'], ENT_QUOTES, 'UTF-8');
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
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Swipper JS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="style/login.css">
</head>
<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="#" enctype="multipart/form-data" method="POST">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social1"><i class="ri-facebook-fill"></i></a>
                    <a href="#" class="social2"><i class="ri-google-fill"></i></a>
                </div>
                <span>or use your email for registration</span>
                <div class="infield">
                    <input type="text" name="firstName" required placeholder="Enter your first name">
                    <label></label>
                </div>
                <div class="infield">
                    <input type="text" name="lastName" required placeholder="Enter your last name">
                    <label></label>
                </div>
                <div class="infield">
                    <input type="text" name="registerUsername" required placeholder="Enter your username" class="box">
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" name="email" required placeholder="Enter your email" class="box">
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" name="registerPassword" required placeholder="Enter your password" class="box">
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" name="cpassword" required placeholder="Confirm your password" class="box">
                    <label></label>
                </div>
                <input type="submit" value="Register now" name="registerSubmit" class="registerBtn">
            </form>
            <button class="mobileSwapBtn" id="signInBtn">Login to your Account</button>
        </div>
        <div class="form-container sign-in-container">
            <form action="#" enctype="multipart/form-data" method="POST">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social1"><i class="ri-facebook-fill"></i></a>
                    <a href="#" class="social2"><i class="ri-google-fill"></i></a>
                </div>
                <span>or use your account</span>
                <div class="infield">
                    <input type="username" required placeholder="Username" name="loginUsername"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" required placeholder="Password" name="loginPassword" />
                    <label></label>
                </div>
                <a href="#" class="forgot">Forgot your password?</a>
                <input type="submit" value="Login" name="loginSubmit" class="loginBtn">
            </form>
            <button class="mobileSwapBtn" id="signUpBtn">Create an Account</button>
        </div>
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info.</p>
                    <button class ="overlayButton" id="overlaySIBtn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Welcome!</h1>
                    <p>Enter your personal details and start your journey with us.</p>
                    <button class ="overlayButton" id="overlaySUBtn">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- js code -->
    <script>
        const container = document.getElementById('container');
        const overlayCon = document.getElementById('overlayCon');
        const overlaySIBtn = document.getElementById('overlaySIBtn');
        const overlaySUBtn = document.getElementById('overlaySUBtn');
        const signInBtnM = document.getElementById('signInBtn');
        const signUpBtnM = document.getElementById('signUpBtn');

        overlaySIBtn.addEventListener('click', () => {
            container.classList.toggle('right-panel-active');
        });

        overlaySUBtn.addEventListener('click', () => {
            container.classList.toggle('right-panel-active');
        });

        signInBtnM.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });

        signUpBtnM.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });
    </script>

</body>
</html>