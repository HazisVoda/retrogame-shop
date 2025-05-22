<?php

$db_name = "mysql:host=localhost:3330;dbname=retrogame_shop";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password) or die('connection failed');

?>