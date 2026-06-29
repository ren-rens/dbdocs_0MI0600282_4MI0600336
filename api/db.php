<?php
require_once 'config.php';
session_start();

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Error while connecting to database.']));
}

try {
    $pdo->query("SELECT 1 FROM users LIMIT 1");
} catch (PDOException $e) {
    $sqlFilePath = __DIR__ . '/../database.sql';
    if (file_exists($sqlFilePath)) {
        $sql = file_get_contents($sqlFilePath);
        $pdo->exec($sql);
    }
}
?>