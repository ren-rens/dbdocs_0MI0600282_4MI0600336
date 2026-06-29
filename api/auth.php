<?php
require_once 'db.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if ($action === 'register') {
    $hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
        $stmt->execute([$email, $hash]);
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! You are now logged in.'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'This email already exists.'
        ]);
    }
} 
elseif ($action === 'login') {
    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Wrong email or password.']);
    }
}
elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
} 
else {
    echo json_encode(['success' => false, 'message' => 'Invalid action requested.']);
}
?>