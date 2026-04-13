<?php
session_start();
include("db_connect.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login first");
}

$user_id = $_SESSION['user_id'];

echo "<h2>Checking Address Data for User ID: $user_id</h2>";

// Check bank_customers table
echo "<h3>1. Bank Customers Table:</h3>";
$sql = "DESCRIBE bank_customers";
$result = $conn->query($sql);
echo "<pre>";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
echo "</pre>";

$sql = "SELECT * FROM bank_customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
echo "<h4>Your data in bank_customers:</h4>";
echo "<pre>";
print_r($data);
echo "</pre>";

// Check if addresses table exists
echo "<h3>2. Addresses Table:</h3>";
$sql = "SHOW TABLES LIKE 'addresses'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "Addresses table EXISTS<br>";
    
    $sql = "DESCRIBE addresses";
    $result = $conn->query($sql);
    echo "<pre>";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    echo "</pre>";
    
    $sql = "SELECT * FROM addresses WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<h4>Your data in addresses table:</h4>";
        while ($row = $result->fetch_assoc()) {
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }
    } else {
        echo "No address records found for your user ID<br>";
    }
} else {
    echo "Addresses table DOES NOT EXIST<br>";
}

// Check customer_profiles table
echo "<h3>3. Customer Profiles Table:</h3>";
$sql = "SHOW TABLES LIKE 'customer_profiles'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "Customer_profiles table EXISTS<br>";
    
    $sql = "SELECT * FROM customer_profiles WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<h4>Your data in customer_profiles:</h4>";
        $row = $result->fetch_assoc();
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    } else {
        echo "No profile records found<br>";
    }
} else {
    echo "Customer_profiles table DOES NOT EXIST<br>";
}

$conn->close();
?>
