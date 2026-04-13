<?php
// Test database connection and check provinces table
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

// Test connection
$host = "localhost";
$user = "root"; 
$pass = ""; 
$db = "BankingDB";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>";
    echo "<p>Trying to connect to MySQL server without database...</p>";
    
    // Try connecting without specifying database
    $conn_test = new mysqli($host, $user, $pass);
    if ($conn_test->connect_error) {
        echo "<p style='color: red;'>❌ MySQL connection failed: " . $conn_test->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ MySQL server connection successful</p>";
        echo "<p>Available databases:</p>";
        $result = $conn_test->query("SHOW DATABASES");
        while ($row = $result->fetch_array()) {
            echo "<p>- " . $row[0] . "</p>";
        }
        $conn_test->close();
    }
} else {
    echo "<p style='color: green;'>✅ Connected to database: $db</p>";
    
    // Check if provinces table exists
    echo "<h3>Checking provinces table...</h3>";
    $result = $conn->query("SHOW TABLES LIKE 'provinces'");
    
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✅ provinces table exists</p>";
        
        // Check table structure
        echo "<h4>Table structure:</h4>";
        $structure = $conn->query("DESCRIBE provinces");
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        while ($row = $structure->fetch_assoc()) {
            echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td></tr>";
        }
        echo "</table>";
        
        // Check if table has data
        $count_result = $conn->query("SELECT COUNT(*) as count FROM provinces");
        $count = $count_result->fetch_assoc()['count'];
        echo "<p>Number of provinces: $count</p>";
        
        if ($count > 0) {
            echo "<h4>First 5 provinces:</h4>";
            $data_result = $conn->query("SELECT * FROM provinces LIMIT 5");
            echo "<table border='1'><tr><th>province_id</th><th>province_name</th></tr>";
            while ($row = $data_result->fetch_assoc()) {
                echo "<tr><td>" . $row['province_id'] . "</td><td>" . $row['province_name'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ provinces table is empty</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ provinces table does not exist</p>";
        
        // Show all tables
        echo "<h4>Available tables in $db:</h4>";
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
    }
    
    // Test the API endpoint query directly
    echo "<h3>Testing API query directly...</h3>";
    $api_result = $conn->query("SELECT province_id as id, province_name as name FROM provinces ORDER BY province_name ASC");
    
    if ($api_result) {
        $provinces = [];
        while ($row = $api_result->fetch_assoc()) {
            $provinces[] = [
                'id' => (int)$row['id'],
                'name' => $row['name']
            ];
        }
        echo "<p>API query result: " . json_encode($provinces) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ API query failed: " . $conn->error . "</p>";
    }
    
    $conn->close();
}

echo "<h3>Testing get_locations_db.php endpoint...</h3>";
$api_url = "http://localhost/evergreen_marketing/bank-system/evergreen-marketing/get_locations_db.php?action=get_provinces";
$context = stream_context_create([
    'http' => [
        'timeout' => 10
    ]
]);
$response = file_get_contents($api_url, false, $context);
if ($response !== false) {
    echo "<p>API Response: " . htmlspecialchars($response) . "</p>";
} else {
    echo "<p style='color: red;'>❌ Failed to call API endpoint</p>";
}
?>
