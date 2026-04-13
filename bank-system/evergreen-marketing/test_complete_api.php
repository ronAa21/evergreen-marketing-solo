<?php
// Test all API endpoints
echo "<h2>Testing Complete API Functionality</h2>";

// Test provinces
echo "<h3>1. Testing Provinces API:</h3>";
$_GET['action'] = 'get_provinces';
echo "<pre>";
include 'get_locations_db.php';
echo "</pre>";

echo "<hr>";

// Test cities for a specific province
echo "<h3>2. Testing Cities API (Province ID 1 - Abra):</h3>";
$_GET['action'] = 'get_cities';
$_GET['province_id'] = '1';
echo "<pre>";
include 'get_locations_db.php';
echo "</pre>";

echo "<hr>";

// Test barangays for a specific city
echo "<h3>3. Testing Barangays API (City ID 1 - Bangued):</h3>";
$_GET['action'] = 'get_barangays';
$_GET['city_id'] = '1';
echo "<pre>";
include 'get_locations_db.php';
echo "</pre>";

echo "<hr>";

// Test cities for another province
echo "<h3>4. Testing Cities API (Province ID 24 - Cavite):</h3>";
$_GET['action'] = 'get_cities';
$_GET['province_id'] = '24';
echo "<pre>";
include 'get_locations_db.php';
echo "</pre>";

echo "<hr>";

// Test barangays for another city
echo "<h3>5. Testing Barangays API (City ID 25 - Cavite City):</h3>";
$_GET['action'] = 'get_barangays';
$_GET['city_id'] = '25';
echo "<pre>";
include 'get_locations_db.php';
echo "</pre>";

echo "<p style='color: green;'><strong>✅ API testing complete!</strong></p>";
echo "<p><a href='signup.php'>Test the signup form now</a></p>";
?>
