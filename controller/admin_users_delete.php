<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: ../login.php');
    exit();
}

require_once 'AdminUsersController.php';

$controller = new AdminUsersController($conn);
$controller->deleteUser();

?>