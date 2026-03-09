<?php
require_once '../../db_connect.php';
require_once 'functions.php';

echo "<h2>Testing Delete API</h2>";

// Test delete via POST request like JavaScript would do
echo "<h3>Testing DELETE API call:</h3>";

// Test data
$test_data = [
    'action' => 'delete_post',
    'id' => 9 // Use the ID from our previous test
];

echo "<p>Sending data:</p>";
echo "<pre>" . json_encode($test_data, JSON_PRETTY_PRINT) . "</pre>";

// Simulate JavaScript fetch call
$options = [
    'http' => [
        'header' => "Content-Type: application/json",
        'method' => 'POST',
        'content' => json_encode($test_data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents('http://localhost/evergreen_marketing/bank-system/evergreen-marketing/Admin-side/backend/actions.php', false, $context);

echo "<h4>Response:</h4>";
echo "<pre>" . $result . "</pre>";

$response = json_decode($result, true);

if (isset($response['success']) && $response['success']) {
    echo "<p style='color: green;'>✅ Delete API working correctly!</p>";
} else {
    echo "<p style='color: red;'>❌ Delete API failed: " . ($response['message'] ?? 'Unknown error') . "</p>";
}
?>
