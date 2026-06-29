<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die("Unauthorized");
}

$folder = $_GET['folder'] ?? '';
$type = $_GET['type'] ?? 'all'; 

if (empty($folder) || preg_match('/[^a-zA-Z0-9_-]/', $folder)) {
    http_response_code(400);
    die("Invalid folder requested.");
}

$basePath = realpath(__DIR__ . "/../storage/" . $folder);

if (!$basePath || !is_dir($basePath)) {
    http_response_code(404);
    die("Project storage directory not found.");
}

$zipName = $folder . "_" . $type . ".zip";
$sourceDir = $basePath;

if ($type === 'docs') {
    $sourceDir = $basePath . "/custom_docs";
    if (!is_dir($sourceDir)) {
        $sourceDir = $basePath . "/docs";
    }
} elseif ($type === 'src') {
    $sourceDir = $basePath . "/src";
}

if (!is_dir($sourceDir)) {
    http_response_code(404);
    die("Requested components (src/docs) do not exist for this project.");
}

$zip = new ZipArchive();
$tmpFile = tempnam(sys_get_temp_dir(), 'zip');

if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Cannot create zip file.");
}

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($sourceDir) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipName . '"');
header('Content-Length: ' . filesize($tmpFile));
header('Pragma: no-cache');
header('Expires: 0');

readfile($tmpFile);
unlink($tmpFile);
exit;
?>