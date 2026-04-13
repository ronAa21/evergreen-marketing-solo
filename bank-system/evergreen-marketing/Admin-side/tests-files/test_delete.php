<?php
require_once '../../db_connect.php';
require_once 'functions.php';

echo "<h2>Testing Delete Functionality</h2>";

// Check database connection
if (isset($db_connection_error)) {
    echo "<p style='color: red;'>❌ Database connection error: $db_connection_error</p>";
} else {
    echo "<p style='color: green;'>✅ Database connected successfully</p>";
}

// Test delete function
echo "<h3>Testing deleteContent function:</h3>";

// First, let's add a test record
$title = "Test Post for Deletion";
$slug = "test-post-for-deletion";
$body = "This is a test post that will be deleted";
$author_id = 1;

// Insert test record
$result = saveContent($conn, $title, $slug, $body, $author_id);
if ($result) {
    $test_id = $conn->insert_id;
    echo "<p style='color: green;'>✅ Test record created with ID: $test_id</p>";
    
    // Now test deleting it
    echo "<h4>Attempting to delete record ID: $test_id</h4>";
    $delete_result = deleteContent($conn, $test_id);
    
    if ($delete_result) {
        echo "<p style='color: green;'>✅ Delete function returned true</p>";
    } else {
        echo "<p style='color: red;'>❌ Delete function returned false</p>";
    }
    
    // Clean up test record
    $conn->query("DELETE FROM cms_content WHERE id = $test_id");
    echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
    
} else {
    echo "<p style='color: red;'>❌ Failed to create test record</p>";
    // Show error details
    echo "<p>Error details:</p>";
    echo "<pre>";
    var_dump($conn->error);
    echo "</pre>";
}

// Check table structure
echo "<h3>Current table structure:</h3>";
$result = $conn->query("DESCRIBE cms_content");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td><td>" . $row['Default'] . "</td><td>" . $row['Extra'] . "</td></tr>";
}
echo "</table>";

$conn->close();
?>
