<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("
        SELECT users.id, users.username, users.role_id, users.image
        FROM tokens
        JOIN users ON tokens.user_id = users.id
        WHERE tokens.token = :token
        LIMIT 1
    ");
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id']  = $user['role_id'];
        $_SESSION['image']    = $user['image'];

        if($_SESSION['role_id'] == 1) {
            header('Location: index.php');
        } else {
            header('Location: view/admin/admin_home.php');
        }
    }
}
