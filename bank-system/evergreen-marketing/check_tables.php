<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Checking Database Tables</h2>";

// Check cities table
echo "<h3>Cities Table:</h3>";
$result = $conn->query("SHOW TABLES LIKE 'cities'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ cities table exists</p>";
    
    // Check structure
    $structure = $conn->query("DESCRIBE cities");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td></tr>";
    }
    echo "</table>";
    
    // Check data count
    $count_result = $conn->query("SELECT COUNT(*) as count FROM cities");
    $count = $count_result->fetch_assoc()['count'];
    echo "<p>Number of cities: $count</p>";
    
    if ($count == 0) {
        echo "<p style='color: orange;'>⚠️ cities table is empty</p>";
    }
} else {
    echo "<p style='color: red;'>❌ cities table does not exist</p>";
}

echo "<hr>";

// Check barangays table
echo "<h3>Barangays Table:</h3>";
$result = $conn->query("SHOW TABLES LIKE 'barangays'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ barangays table exists</p>";
    
    // Check structure
    $structure = $conn->query("DESCRIBE barangays");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td></tr>";
    }
    echo "</table>";
    
    // Check data count
    $count_result = $conn->query("SELECT COUNT(*) as count FROM barangays");
    $count = $count_result->fetch_assoc()['count'];
    echo "<p>Number of barangays: $count</p>";
    
    if ($count == 0) {
        echo "<p style='color: orange;'>⚠️ barangays table is empty</p>";
    }
} else {
    echo "<p style='color: red;'>❌ barangays table does not exist</p>";
}

echo "<hr>";

// Show all tables
echo "<h3>All tables in database:</h3>";
$tables = $conn->query("SHOW TABLES");
if ($tables && $tables->num_rows > 0) {
    echo "<ul>";
    while ($row = $tables->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No tables found in database</p>";
}

$conn->close();
?>
