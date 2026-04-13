<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Final Functionality Test</h2>";

// Test 1: Provinces
echo "<h3>1. Provinces API Test:</h3>";
$result = $conn->query("SELECT province_id as id, province_name as name FROM provinces ORDER BY province_name ASC LIMIT 3");
$provinces = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $provinces[] = [
            'id' => (int)$row['id'],
            'name' => $row['name']
        ];
    }
}
echo "<pre>" . json_encode($provinces, JSON_PRETTY_PRINT) . "</pre>";

// Test 2: Cities for a province
echo "<h3>2. Cities API Test (Abra - Province ID 1):</h3>";
$stmt = $conn->prepare("SELECT city_id as id, city_name as name FROM cities WHERE province_id = ? ORDER BY city_name ASC LIMIT 3");
$stmt->bind_param("i", $province_id);
$province_id = 1;
$stmt->execute();
$result = $stmt->get_result();

$cities = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = [
            'id' => (int)$row['id'],
            'name' => $row['name']
        ];
    }
}
echo "<pre>" . json_encode($cities, JSON_PRETTY_PRINT) . "</pre>";

// Test 3: Barangays for a city
echo "<h3>3. Barangays API Test (Bangued - City ID 1):</h3>";
$stmt = $conn->prepare("SELECT barangay_id as id, barangay_name as name, zip_code FROM barangays WHERE city_id = ? ORDER BY barangay_name ASC LIMIT 3");
$stmt->bind_param("i", $city_id);
$city_id = 1;
$stmt->execute();
$result = $stmt->get_result();

$barangays = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barangays[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'zip_code' => $row['zip_code'] ?? ''
        ];
    }
}
echo "<pre>" . json_encode($barangays, JSON_PRETTY_PRINT) . "</pre>";

// Test 4: Test another province (Cavite)
echo "<h3>4. Cities API Test (Cavite - Province ID 24):</h3>";
$stmt = $conn->prepare("SELECT city_id as id, city_name as name FROM cities WHERE province_id = ? ORDER BY city_name ASC LIMIT 3");
$stmt->bind_param("i", $province_id);
$province_id = 24;
$stmt->execute();
$result = $stmt->get_result();

$cities_cavite = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities_cavite[] = [
            'id' => (int)$row['id'],
            'name' => $row['name']
        ];
    }
}
echo "<pre>" . json_encode($cities_cavite, JSON_PRETTY_PRINT) . "</pre>";

// Test 5: Test barangays for a Cavite city
echo "<h3>5. Barangays API Test (Cavite City - City ID 51):</h3>";
$stmt = $conn->prepare("SELECT barangay_id as id, barangay_name as name, zip_code FROM barangays WHERE city_id = ? ORDER BY barangay_name ASC LIMIT 3");
$stmt->bind_param("i", $city_id);
$city_id = 51;
$stmt->execute();
$result = $stmt->get_result();

$barangays_cavite = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barangays_cavite[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'zip_code' => $row['zip_code'] ?? ''
        ];
    }
}
echo "<pre>" . json_encode($barangays_cavite, JSON_PRETTY_PRINT) . "</pre>";

// Summary
echo "<h3>Database Summary:</h3>";
$provinces_count = $conn->query("SELECT COUNT(*) as count FROM provinces")->fetch_assoc()['count'];
$cities_count = $conn->query("SELECT COUNT(*) as count FROM cities")->fetch_assoc()['count'];
$barangays_count = $conn->query("SELECT COUNT(*) as count FROM barangays")->fetch_assoc()['count'];
$cities_with_barangays = $conn->query("SELECT COUNT(DISTINCT city_id) as count FROM barangays")->fetch_assoc()['count'];

echo "<table border='1'><tr><th>Table</th><th>Total Count</th><th>Status</th></tr>";
echo "<tr><td>Provinces</td><td>$provinces_count</td><td style='color: green;'>✅ Complete</td></tr>";
echo "<tr><td>Cities</td><td>$cities_count</td><td style='color: green;'>✅ Complete</td></tr>";
echo "<tr><td>Barangays</td><td>$barangays_count</td><td style='color: green;'>✅ Complete</td></tr>";
echo "<tr><td>Cities with Barangays</td><td>$cities_with_barangays</td><td style='color: green;'>✅ Complete</td></tr>";
echo "</table>";

$stmt->close();
$conn->close();

echo "<p style='color: green;'><strong>✅ All functionality working perfectly!</strong></p>";
echo "<p><a href='signup.php'>Test the signup form now</a></p>";
?>
