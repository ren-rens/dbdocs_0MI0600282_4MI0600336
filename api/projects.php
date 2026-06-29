<?php
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Please login.']));
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'list') {
    $stmt = $pdo->prepare("SELECT id, title, folder_name, created_at FROM projects WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($projects as &$project) {
        $project['docs_url'] = "storage/" . $project['folder_name'] . "/custom_docs/index.html";

        $javadocIndex = __DIR__ . "/../storage/" . $project['folder_name'] . "/docs/index.html";
        if (file_exists($javadocIndex)) {
            $project['javadoc_url'] = "storage/" . $project['folder_name'] . "/docs/index.html";
        }
    }
    unset($project);

    echo json_encode(['success' => true, 'projects' => $projects]);
}
elseif ($action === 'delete') {
    $projectId = $input['id'] ?? 0;

    $stmt = $pdo->prepare("SELECT folder_name FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$projectId, $userId]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        echo json_encode(['success' => false, 'message' => 'Schema not found.']);
        exit;
    }

    $storageDir = __DIR__ . "/../storage/" . $project['folder_name'];
    if (is_dir($storageDir)) {
        deleteDirectory($storageDir);
    }

    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$projectId, $userId]);

    echo json_encode(['success' => true, 'message' => 'Schema deleted successfully.']);
}
else {
    echo json_encode(['success' => false, 'message' => 'Invalid action requested.']);
}

function deleteDirectory(string $dir): void {
    if (!is_dir($dir)) {
        return;
    }
    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . '/' . $item;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
}
?>
