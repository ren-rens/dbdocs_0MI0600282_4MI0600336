<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$pageTitle = "My Profile";

require 'views/profile.view.php';
?>
