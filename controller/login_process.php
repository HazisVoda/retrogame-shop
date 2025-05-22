<?php
session_start();
require_once '../model/user.php';
include '../config.php';

$usernameOrEmail = $_POST['loginUsername'] ?? '';
$password = $_POST['loginPassword'] ?? '';

if (!$usernameOrEmail || !$password) {
    $_SESSION['login_error'] = "Please fill in all fields.";
    header('Location: ../login.php');
    exit();
}

$userModel = new User($conn);

if (!$userModel->loadByUsername($usernameOrEmail)) {
    if (!$userModel->loadByEmail($usernameOrEmail)) {
        $_SESSION['login_error'] = "User not found.";
        header('Location: ../login.php');
        exit();
    }
}

if (!$userModel->verifyPassword($password)) {
    $_SESSION['login_error'] = "Incorrect password.";
    header('Location: ../login.php');
    exit();
}

$_SESSION['user_id'] = $userModel->id;
$_SESSION['role_id'] = $userModel->roleId;
$_SESSION['username'] = $userModel->username;
$_SESSION['image'] = $userModel->image;
$_SESSION['firstName'] = $userModel->firstName;
$_SESSION['lastName'] = $userModel->lastName;
$_SESSION['email'] = $userModel->email;
$_SESSION['createdAt'] = $userModel->createdAt;

if ($userModel->roleId == 2) { // Admin
    header('Location: ../view/admin/admin_home.php');
    exit();
} else { // Regular user or staff
    header('Location: ../index.php');
    exit();
}
