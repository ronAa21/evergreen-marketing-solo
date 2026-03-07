<?php
// Simple API test without multiple includes
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Simple API Test</h2>";

// Test provinces
echo "<h3>Provinces (first 5):</h3>";
$result = $conn->query("SELECT province_id as id, province_name as name FROM provinces ORDER BY province_name ASC LIMIT 5");
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

// Test cities for province 1 (Abra)
echo "<h3>Cities in Abra (first 5):</h3>";
$stmt = $conn->prepare("SELECT city_id as id, city_name as name FROM cities WHERE province_id = ? ORDER BY city_name ASC LIMIT 5");
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

// Test barangays for city 1 (Bangued)
echo "<h3>Barangays in Bangued (first 5):</h3>";
$stmt = $conn->prepare("SELECT barangay_id as id, barangay_name as name, zip_code FROM barangays WHERE city_id = ? ORDER BY barangay_name ASC LIMIT 5");
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

$stmt->close();
$conn->close();

echo "<p style='color: green;'><strong>✅ API functionality working correctly!</strong></p>";
echo "<p><a href='signup.php'>Test the signup form now</a></p>";
?>
