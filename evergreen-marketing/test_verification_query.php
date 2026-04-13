<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Testing Verification Query</h2>";

// Simulate the verification process with test data
echo "<h3>Simulating account creation process...</h3>";

// Test data (similar to what would be in session)
$test_data = [
    'first_name' => 'Test',
    'middle_name' => 'User',
    'last_name' => 'Account',
    'email' => 'test@example.com',
    'contact_number' => '09123456789',
    'password' => password_hash('test123', PASSWORD_DEFAULT),
    'verification_code' => '123456',
    'bank_id' => '0001',
    'referral_code' => 'TEST123',
    'address_line' => '123 Test Street',
    'province_id' => 1, // Abra
    'city_id' => 1, // Bangued
    'barangay_id' => 123, // A barangay in Bangued
    'zip_code' => '2800',
    'birthday' => '1990-01-01'
];

echo "<h4>Test data:</h4>";
echo "<pre>" . json_encode($test_data, JSON_PRETTY_PRINT) . "</pre>";

// Start transaction
$conn->begin_transaction();

try {
    // Test 1: Insert into bank_customers
    echo "<h3>Test 1: bank_customers insert</h3>";
    $sql = "INSERT INTO bank_customers (first_name, middle_name, last_name, email, contact_number, password_hash, verification_code, bank_id, referral_code, total_points, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0.00, 1)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Bank customers preparation error: " . $conn->error);
    }
    
    $stmt->bind_param("sssssssss",
        $test_data['first_name'],
        $test_data['middle_name'],
        $test_data['last_name'],
        $test_data['email'],
        $test_data['contact_number'],
        $test_data['password'],
        $test_data['verification_code'],
        $test_data['bank_id'],
        $test_data['referral_code']
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to create customer account: " . $stmt->error);
    }
    
    $customer_id = $conn->insert_id;
    $stmt->close();
    echo "<p style='color: green;'>✅ Customer account created with ID: $customer_id</p>";
    
    // Test 2: Insert into customer_profiles
    echo "<h3>Test 2: customer_profiles insert</h3>";
    $birthday_formatted = date('Y-m-d', strtotime($test_data['birthday']));
    $sql_profile = "INSERT INTO customer_profiles (customer_id, date_of_birth, profile_created_at) VALUES (?, ?, NOW())";
    
    $stmt_profile = $conn->prepare($sql_profile);
    if (!$stmt_profile) {
        throw new Exception("Profile preparation error: " . $conn->error);
    }
    
    $stmt_profile->bind_param("is", $customer_id, $birthday_formatted);
    
    if (!$stmt_profile->execute()) {
        throw new Exception("Failed to create customer profile: " . $stmt_profile->error);
    }
    
    $stmt_profile->close();
    echo "<p style='color: green;'>✅ Customer profile created</p>";
    
    // Test 3: Insert into addresses (the problematic one)
    echo "<h3>Test 3: addresses insert (the main issue)</h3>";
    $sql_address = "INSERT INTO addresses (customer_id, address_type, address_line, province_id, city_id, barangay_id, postal_code, is_primary, created_at) VALUES (?, 'home', ?, ?, ?, ?, ?, 1, NOW())";
    
    $stmt_address = $conn->prepare($sql_address);
    if (!$stmt_address) {
        throw new Exception("Address preparation error: " . $conn->error);
    }
    
    echo "<p>Query: " . htmlspecialchars($sql_address) . "</p>";
    
    $stmt_address->bind_param("isiiis",
        $customer_id,
        $test_data['address_line'],
        $test_data['province_id'],
        $test_data['city_id'],
        $test_data['barangay_id'],
        $test_data['zip_code']
    );
    
    if (!$stmt_address->execute()) {
        throw new Exception("Failed to create address: " . $stmt_address->error);
    }
    
    $address_id = $conn->insert_id;
    $stmt_address->close();
    echo "<p style='color: green;'>✅ Address created with ID: $address_id</p>";
    
    // Rollback the test transaction (we don't want to keep test data)
    $conn->rollback();
    echo "<p style='color: blue;'>ℹ️ Test transaction rolled back (no data saved)</p>";
    
    echo "<h3 style='color: green;'>✅ All tests passed! Verification process should work now.</h3>";
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo "<p style='color: red;'>❌ Test failed: " . $e->getMessage() . "</p>";
    error_log("Verification test error: " . $e->getMessage());
}

$conn->close();
echo "<p><a href='signup.php'>Test the complete signup process</a></p>";
?>
