<?php
require_once '../../db_connect.php';
require_once 'functions.php';

// Handle GET requests for debugging
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = getAllContent($conn);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    // Debug: Show raw output
    echo "<h2>Raw Output:</h2>";
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    
    echo "<h2>JSON Output:</h2>";
    echo "<pre>";
    echo json_encode($data, JSON_PRETTY_PRINT);
    echo "</pre>";
    
    // Check for JSON errors
    $json_output = json_encode($data);
    $json_error = json_last_error_msg();
    
    if ($json_error) {
        echo "<h2>JSON Error:</h2>";
        echo "<p style='color: red;'>$json_error</p>";
    } else {
        echo "<h2>JSON Status:</h2>";
        echo "<p style='color: green;'>Valid JSON</p>";
    }
    
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from JavaScript
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    echo "<h2>POST Debug:</h2>";
    echo "<pre>";
    echo "Raw JSON received: " . $json_data;
    echo "</pre>";
    echo "<pre>";
    echo "Decoded data: ";
    var_dump($data);
    echo "</pre>";
    
    if (isset($data['action']) && $data['action'] === 'create_post') {
        $title = $data['title'] ?? '';
        $body = $data['body'] ?? '';
        $author_id = $data['author_id'] ?? 1;
        
        // Generate slug from title
        $slug = strtolower(str_replace(' ', '-', $title));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        
        try {
            $result = saveContent($conn, $title, $slug, $body, $author_id);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Content published successfully!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to publish content'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
    }
}
?>
