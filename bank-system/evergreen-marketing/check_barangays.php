<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Checking Barangays Coverage</h2>";

// Check which cities have barangays
echo "<h3>Cities with barangays data:</h3>";
$result = $conn->query("SELECT DISTINCT c.city_id, c.city_name, p.province_name, COUNT(b.barangay_id) as barangay_count 
                        FROM cities c 
                        LEFT JOIN barangays b ON c.city_id = b.city_id 
                        LEFT JOIN provinces p ON c.province_id = p.province_id 
                        GROUP BY c.city_id, c.city_name, p.province_name 
                        ORDER BY barangay_count DESC, c.city_name");

echo "<table border='1'><tr><th>City ID</th><th>City Name</th><th>Province</th><th>Barangays Count</th></tr>";
$cities_with_barangays = 0;
$total_barangays = 0;

while ($row = $result->fetch_assoc()) {
    $barangay_count = $row['barangay_count'];
    if ($barangay_count > 0) {
        $cities_with_barangays++;
        $total_barangays += $barangay_count;
        echo "<tr><td>" . $row['city_id'] . "</td><td>" . $row['city_name'] . "</td><td>" . $row['province_name'] . "</td><td style='color: green;'>" . $barangay_count . "</td></tr>";
    } else {
        echo "<tr><td>" . $row['city_id'] . "</td><td>" . $row['city_name'] . "</td><td>" . $row['province_name'] . "</td><td style='color: red;'>0</td></tr>";
    }
}
echo "</table>";

echo "<h3>Summary:</h3>";
echo "<p>Total cities with barangays: $cities_with_barangays</p>";
echo "<p>Total barangays: $total_barangays</p>";

// Get total cities count
$result = $conn->query("SELECT COUNT(*) as total FROM cities");
$total_cities = $result->fetch_assoc()['total'];
echo "<p>Total cities in database: $total_cities</p>";
echo "<p>Cities without barangays: " . ($total_cities - $cities_with_barangays) . "</p>";

echo "<h3>Sample cities without barangays:</h3>";
$result = $conn->query("SELECT c.city_id, c.city_name, p.province_name 
                        FROM cities c 
                        LEFT JOIN barangays b ON c.city_id = b.city_id 
                        LEFT JOIN provinces p ON c.province_id = p.province_id 
                        WHERE b.barangay_id IS NULL 
                        ORDER BY c.city_name 
                        LIMIT 10");

echo "<table border='1'><tr><th>City ID</th><th>City Name</th><th>Province</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['city_id'] . "</td><td>" . $row['city_name'] . "</td><td>" . $row['province_name'] . "</td></tr>";
}
echo "</table>";

$conn->close();
?>
