<?php
require_once __DIR__ . '/check_auth.php';

// If user is logged in, clear session and token
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    session_unset();
    session_destroy();

    // Remove token from database and cookie
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        require_once __DIR__ . '/config.php';
        $stmt = $conn->prepare("DELETE FROM tokens WHERE token = :token");
        $stmt->execute([':token' => $token]);

        setcookie('remember_token', '', time() - 3600, "/");
    }
}

header('Location: index.php');
exit();
