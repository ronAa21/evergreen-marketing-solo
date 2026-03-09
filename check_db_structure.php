<?php
// Check database structure to diagnose the issue
include("db_connect.php");

echo "<h2>Database Structure Diagnostic</h2>";

// Check if bank_customers table exists
$result = $conn->query("SHOW TABLES LIKE 'bank_customers'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ bank_customers table exists</p>";
    
    // Show table structure
    echo "<h3>bank_customers Table Structure:</h3>";
    $result = $conn->query("DESCRIBE bank_customers");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>✗ bank_customers table does NOT exist</p>";
}

// Check if referrals table exists
echo "<h3>Referrals Table:</h3>";
$result = $conn->query("SHOW TABLES LIKE 'referrals'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ referrals table exists</p>";
    
    // Show table structure
    $result = $conn->query("DESCRIBE referrals");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>✗ referrals table does NOT exist</p>";
    echo "<p><strong>Action needed:</strong> Run the SQL file: evergreen-marketing/sql/add_referrals_table.sql</p>";
}

// Show current database name
$result = $conn->query("SELECT DATABASE() as db_name");
$row = $result->fetch_assoc();
echo "<h3>Current Database: " . htmlspecialchars($row['db_name']) . "</h3>";

$conn->close();
?>
