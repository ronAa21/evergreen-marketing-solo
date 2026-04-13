<?php
// Suppress warnings for clean JSON output
error_reporting(0);

require_once '../../db_connect.php';
require_once 'functions.php';

header('Content-Type: application/json');

// Get raw input
$input = file_get_contents('php://input');
echo "<h2>Raw Input Received:</h2>";
echo "<pre>" . $input . "</pre>";

// Decode JSON
$data = json_decode($input, true);
echo "<h2>Decoded Data:</h2>";
echo "<pre>" . print_r($data, true) . "</pre>";

// Check action
$action = $data['action'] ?? 'none';
echo "<h2>Action: $action</h2>";

if ($action === 'delete_post') {
    echo "<h2>Processing DELETE_POST</h2>";
    $id = $data['id'] ?? 0;
    echo "<h2>ID to delete: $id</h2>";
    
    if ($id > 0) {
        $result = deleteContent($conn, $id);
        echo "<h2>Delete result: " . ($result ? 'SUCCESS' : 'FAILED') . "</h2>";
        
        $response = [
            'success' => $result,
            'message' => $result ? 'Post deleted!' : 'Delete failed'
        ];
    } else {
        echo "<h2>No ID provided</h2>";
        $response = [
            'success' => false,
            'message' => 'Missing ID for deletion'
        ];
    }
} else {
    echo "<h2>Unknown action</h2>";
    $response = [
        'success' => false,
        'message' => 'Invalid action'
    ];
}

echo "<h2>Final Response:</h2>";
echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
?>
