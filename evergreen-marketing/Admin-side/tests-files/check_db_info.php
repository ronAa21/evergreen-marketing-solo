<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 

// Test connection to BankingDB
echo "<h2>Testing BankingDB Connection:</h2>";
$conn1 = new mysqli($host, $user, $pass, "BankingDB");
if ($conn1->connect_error) {
    echo "<p style='color: red;'>❌ BankingDB connection failed: " . $conn1->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ BankingDB connected successfully</p>";
    
    // Check if cms_content exists in BankingDB
    $result = $conn1->query("SHOW TABLES LIKE 'cms_content'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✅ cms_content table found in BankingDB</p>";
    } else {
        echo "<p style='color: red;'>❌ cms_content table NOT found in BankingDB</p>";
    }
    $conn1->close();
}

echo "<hr>";

// Test connection to default database (no database specified)
echo "<h2>Testing Default Database Connection:</h2>";
$conn2 = new mysqli($host, $user, $pass);
if ($conn2->connect_error) {
    echo "<p style='color: red;'>❌ Default connection failed: " . $conn2->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Default connected successfully</p>";
    
    // List all databases
    $databases = $conn2->query("SHOW DATABASES");
    echo "<h3>Available Databases:</h3>";
    echo "<ul>";
    while ($db = $databases->fetch_array()) {
        echo "<li>" . $db[0] . "</li>";
    }
    echo "</ul>";
    
    // Check if cms_content exists in default
    $result = $conn2->query("SHOW TABLES LIKE 'cms_content'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✅ cms_content table found in default database</p>";
    } else {
        echo "<p style='color: red;'>❌ cms_content table NOT found in default database</p>";
    }
    $conn2->close();
}
?>
