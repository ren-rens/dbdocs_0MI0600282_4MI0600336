<?php
require_once 'config.php';
require_once 'db.php';
require_once 'JavadocBuilder.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Please login.']));
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];
$title = htmlspecialchars($input['title'] ?? 'My DB Project');
$rawJson = $input['structure'] ?? '{"tables":[]}';
$data = json_decode($rawJson, true);

$folderName = uniqid('proj_');
$tables = $data['tables'] ?? [];

$builder = new JavadocBuilder($folderName, $javadoc_path, $title, $enable_javadoc);
$result = $builder->build($tables);

if ($result['success']) {
    $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, raw_input, folder_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $title, $rawJson, $folderName]);
    
    $response = [
        'success' => true, 
        'message' => 'Documentation generated successfully!',
        'docs_url' => $result['docs_url']
    ];
    
    if (isset($result['javadoc_url'])) {
        $response['javadoc_url'] = $result['javadoc_url'];
    }
    
    echo json_encode($response);
} else {
    echo json_encode($result);
}
?>