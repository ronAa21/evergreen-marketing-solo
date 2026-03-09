<?php
// Suppress warnings for clean JSON output
error_reporting(0);

require_once '../../db_connect.php';
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    $action = $data['action'] ?? '';
    $response = ['success' => false, 'message' => 'Invalid action'];

// Debug: Log what action we received
error_log("Received action: " . $action);

try {
    switch ($action) {
        case 'create_post':
                $slug = strtolower(str_replace(' ', '-', $data['title'] ?? ''));
                $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
                
                $result = saveContent($conn, $data['title'], $slug, $data['body'], $data['author_id'] ?? 1);
                $response = $result ? ['success' => true, 'message' => 'Post published!'] : ['success' => false, 'message' => 'Save failed'];
                break;

            case 'edit_post':
                // Ensure we have an ID and new data
                if (isset($data['id'], $data['title'], $data['body'])) {
                    $result = editContent($conn, $data['id'], $data['title'], $data['body']);
                    $response = $result ? ['success' => true, 'message' => 'Post updated!'] : ['success' => false, 'message' => 'Update failed'];
                } else {
                    $response['message'] = 'Missing data for update';
                }
                break;

            case 'delete_post':
                if (isset($data['id'])) {
                    $result = deleteContent($conn, $data['id']);
                    $response = $result ? ['success' => true, 'message' => 'Post deleted!'] : ['success' => false, 'message' => 'Delete failed'];
                } else {
                    $response['message'] = 'Missing ID for deletion';
                }
                break;
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }

    echo json_encode($response);
    exit;
} else {
    // Handle GET requests (Fetching all content)
    $result = getAllContent($conn);
    $data = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo json_encode($data);
}
?>