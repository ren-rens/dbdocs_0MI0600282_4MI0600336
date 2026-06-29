<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$pageTitle = "New Schema Dashboard";

require 'views/dashboard.view.php';
?>