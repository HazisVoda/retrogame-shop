<?php

include 'config.php';

session_start();

if(isset($_POST['registerSubmit'])){
    $firstName = htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($_POST['registerUsername'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['registerPassword'], ENT_QUOTES, 'UTF-8');
    $cpassword = htmlspecialchars($_POST['cpassword'], ENT_QUOTES, 'UTF-8');

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR username = ?");
    $select->execute([$email, $username]);

    if($select->rowCount() > 0){
        echo "<script>alert('User already exists!')</script>";
    }else{
        if($password != $cpassword){
            echo "<script>alert('Password not matched!')</script>";
        }
        else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO `users`(firstName, lastName, username, email, password, role_id) VALUES(?,?,?,?,?,?)");
            $insert->execute([$firstName, $lastName, $username, $email, $password, 1]);
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
            <?php if (isset($_SESSION['login_error'])): ?>
                <p style="color:red;"><?= $_SESSION['login_error'] ?></p>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
            <form action="controller/login_process.php" method="POST">
                <h1>Sign in</h1>
                <div class="infield">
                    <input type="text" required placeholder="Username" name="loginUsername"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" required placeholder="Password" name="loginPassword" />
                    <label></label>
                </div>
                <a href="#" class="forgot">Forgot your password?</a>
                <input type="submit" value="Login" name="loginSubmit" class="loginBtn">
            </form>
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