<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if (!isset($_SESSION['username'])) { 
    http_response_code(401); 
    echo json_encode(['error' => 'Unauthorized']); 
    exit; 
}

$file = __DIR__ . '/../data/snapshots.json';
$dir = dirname($file);

if (!is_dir($dir)) {
    @mkdir($dir, 0777, true);
}

$result = file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));

if ($result === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to clear history']);
    exit;
}

echo json_encode([
    'ok' => true, 
    'message' => 'History cleared successfully'
]);
?>

