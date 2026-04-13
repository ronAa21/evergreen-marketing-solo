<?php
require_once 'db_connect.php';

echo "<h2>Adding is_admin column to bank_customers table</h2>";

// Check if column exists
$result = $conn->query("SHOW COLUMNS FROM bank_customers LIKE 'is_admin'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: blue;'>ℹ️ is_admin column already exists</p>";
} else {
    // Add the column
    $sql = "ALTER TABLE bank_customers ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER bank_id";
    
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ is_admin column added successfully</p>";
        
        // Set first user as admin for testing
        $conn->query("UPDATE bank_customers SET is_admin = 1 WHERE customer_id = 1 LIMIT 1");
        echo "<p style='color: green;'>✅ Set first user as admin for testing</p>";
    } else {
        echo "<p style='color: red;'>❌ Error adding column: " . $conn->error . "</p>";
    }
}

// Show current table structure
echo "<h3>Current bank_customers structure:</h3>";
$result = $conn->query("DESCRIBE bank_customers");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td><td>" . $row['Default'] . "</td><td>" . $row['Extra'] . "</td></tr>";
}
echo "</table>";

$conn->close();
?>
