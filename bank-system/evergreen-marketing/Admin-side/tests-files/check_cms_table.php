<?php
require_once '../../db_connect.php';

echo "<h2>Checking CMS Content Table</h2>";

// Check if cms_content table exists
$result = $conn->query("SHOW TABLES LIKE 'cms_content'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ cms_content table exists</p>";
    
    // Show table structure
    echo "<h3>Table structure:</h3>";
    $structure = $conn->query("DESCRIBE cms_content");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td><td>" . $row['Default'] . "</td><td>" . $row['Extra'] . "</td></tr>";
    }
    echo "</table>";
    
    // Show sample data
    echo "<h3>Sample data:</h3>";
    $data = $conn->query("SELECT * FROM cms_content LIMIT 5");
    if ($data && $data->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Slug</th><th>Author ID</th><th>Status</th><th>Created At</th></tr>";
        while ($row = $data->fetch_assoc()) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['slug'] . "</td><td>" . $row['author_id'] . "</td><td>" . $row['status'] . "</td><td>" . $row['created_at'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No data in cms_content table</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ cms_content table does not exist</p>";
    
    // Create the table
    echo "<h3>Creating cms_content table...</h3>";
    $sql = "CREATE TABLE IF NOT EXISTS cms_content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        body TEXT NOT NULL,
        author_id INT NOT NULL,
        status ENUM('Draft', 'Published') DEFAULT 'Draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ cms_content table created successfully</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating table: " . $conn->error . "</p>";
    }
}

$conn->close();
?>
