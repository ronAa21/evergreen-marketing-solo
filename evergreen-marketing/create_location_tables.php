<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Creating Location Tables</h2>";

// Create cities table
echo "<h3>Creating cities table...</h3>";
$sql = "CREATE TABLE IF NOT EXISTS cities (
    city_id INT AUTO_INCREMENT PRIMARY KEY,
    city_name VARCHAR(100) NOT NULL,
    province_id INT NOT NULL,
    FOREIGN KEY (province_id) REFERENCES provinces(province_id) ON DELETE CASCADE,
    INDEX idx_province (province_id)
)";

if ($conn->query($sql)) {
    echo "<p style='color: green;'>✅ cities table created successfully</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating cities table: " . $conn->error . "</p>";
}

// Create barangays table
echo "<h3>Creating barangays table...</h3>";
$sql = "CREATE TABLE IF NOT EXISTS barangays (
    barangay_id INT AUTO_INCREMENT PRIMARY KEY,
    barangay_name VARCHAR(100) NOT NULL,
    city_id INT NOT NULL,
    zip_code VARCHAR(10),
    FOREIGN KEY (city_id) REFERENCES cities(city_id) ON DELETE CASCADE,
    INDEX idx_city (city_id)
)";

if ($conn->query($sql)) {
    echo "<p style='color: green;'>✅ barangays table created successfully</p>";
} else {
    echo "<p style='color: red;'>❌ Error creating barangays table: " . $conn->error . "</p>";
}

echo "<h3>Table structures:</h3>";

// Show cities table structure
echo "<h4>Cities table structure:</h4>";
$result = $conn->query("DESCRIBE cities");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td></tr>";
}
echo "</table>";

// Show barangays table structure
echo "<h4>Barangays table structure:</h4>";
$result = $conn->query("DESCRIBE barangays");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td></tr>";
}
echo "</table>";

$conn->close();
echo "<p style='color: green;'><strong>✅ Tables created successfully!</strong></p>";
?>
