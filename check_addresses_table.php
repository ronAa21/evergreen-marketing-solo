<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Checking Addresses Table Structure</h2>";

// Check if addresses table exists
$result = $conn->query("SHOW TABLES LIKE 'addresses'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ addresses table exists</p>";
    
    // Show table structure
    echo "<h3>Current addresses table structure:</h3>";
    $structure = $conn->query("DESCRIBE addresses");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td><td>" . $row['Default'] . "</td><td>" . $row['Extra'] . "</td></tr>";
    }
    echo "</table>";
    
    // Check if the required columns exist
    echo "<h3>Checking required columns:</h3>";
    $required_columns = ['customer_id', 'address_type', 'address_line', 'province_id', 'city_id', 'barangay_id', 'postal_code', 'is_primary', 'created_at'];
    
    foreach ($required_columns as $column) {
        $result = $conn->query("SHOW COLUMNS FROM addresses LIKE '$column'");
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ $column column exists</p>";
        } else {
            echo "<p style='color: red;'>❌ $column column missing</p>";
        }
    }
    
    // Show sample data if any
    echo "<h3>Sample data in addresses table:</h3>";
    $data = $conn->query("SELECT * FROM addresses LIMIT 5");
    if ($data && $data->num_rows > 0) {
        echo "<table border='1'>";
        // Header
        echo "<tr>";
        $fields = $data->fetch_fields();
        foreach ($fields as $field) {
            echo "<th>" . $field->name . "</th>";
        }
        echo "</tr>";
        // Data rows
        $data->data_seek(0); // Reset pointer
        while ($row = $data->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . ($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No data in addresses table</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ addresses table does not exist</p>";
    
    // Show all tables to see if there's a similar table
    echo "<h3>All tables in database:</h3>";
    $tables = $conn->query("SHOW TABLES");
    if ($tables && $tables->num_rows > 0) {
        echo "<ul>";
        while ($row = $tables->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    }
}

$conn->close();
?>
