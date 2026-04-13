<?php
require_once '../../db_connect.php';
require_once 'functions.php';

echo "<h2>Testing saveContent Function Directly</h2>";

// Check database connection
if (isset($db_connection_error)) {
    echo "<p style='color: red;'>❌ Database connection error: $db_connection_error</p>";
} else {
    echo "<p style='color: green;'>✅ Database connected successfully</p>";
    
    // Test saveContent with sample data
    $title = "Direct Test Post";
    $slug = "direct-test-post";
    $body = "This is a direct test of saveContent function";
    $author_id = 1;
    
    echo "<h3>Testing saveContent function:</h3>";
    echo "<p>Title: $title</p>";
    echo "<p>Slug: $slug</p>";
    echo "<p>Body: $body</p>";
    echo "<p>Author ID: $author_id</p>";
    
    // Call the function
    $result = saveContent($conn, $title, $slug, $body, $author_id);
    
    echo "<h4>Result:</h4>";
    if ($result === true) {
        echo "<p style='color: green;'>✅ saveContent returned TRUE</p>";
        
        // Check if record was actually inserted
        $insert_id = $conn->insert_id;
        if ($insert_id) {
            echo "<p style='color: green;'>✅ Record inserted with ID: $insert_id</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ No insert ID returned</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ saveContent returned FALSE</p>";
        
        // Show error details
        echo "<p>MySQL Error:</p>";
        echo "<pre>" . $conn->error . "</pre>";
    }
    
    $conn->close();
}
?>
