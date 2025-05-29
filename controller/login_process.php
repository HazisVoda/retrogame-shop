<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/LoginController.php';

$controller = new LoginController($conn);
$controller->handleLogin();