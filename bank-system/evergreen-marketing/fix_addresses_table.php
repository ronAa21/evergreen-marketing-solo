<?php
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Fixing Addresses Table Structure</h2>";

// Add missing columns to addresses table
echo "<h3>Adding missing columns...</h3>";

// Add city_id column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM addresses LIKE 'city_id'");
if ($result && $result->num_rows == 0) {
    $sql = "ALTER TABLE addresses ADD COLUMN city_id INT NULL AFTER province_id";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ Added city_id column</p>";
    } else {
        echo "<p style='color: red;'>❌ Error adding city_id: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ️ city_id column already exists</p>";
}

// Add barangay_id column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM addresses LIKE 'barangay_id'");
if ($result && $result->num_rows == 0) {
    $sql = "ALTER TABLE addresses ADD COLUMN barangay_id INT NULL AFTER city_id";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ Added barangay_id column</p>";
    } else {
        echo "<p style='color: red;'>❌ Error adding barangay_id: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ️ barangay_id column already exists</p>";
}

// Add foreign key constraints if they don't exist
echo "<h3>Adding foreign key constraints...</h3>";

// Check if foreign key for city_id exists
$result = $conn->query("
    SELECT CONSTRAINT_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME = 'addresses' 
    AND COLUMN_NAME = 'city_id' 
    AND REFERENCED_TABLE_NAME = 'cities'
");

if ($result && $result->num_rows == 0) {
    $sql = "ALTER TABLE addresses ADD CONSTRAINT fk_address_city FOREIGN KEY (city_id) REFERENCES cities(city_id) ON DELETE SET NULL";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ Added foreign key constraint for city_id</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Warning: Could not add foreign key for city_id (may already exist or data conflicts): " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ️ Foreign key for city_id already exists</p>";
}

// Check if foreign key for barangay_id exists
$result = $conn->query("
    SELECT CONSTRAINT_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME = 'addresses' 
    AND COLUMN_NAME = 'barangay_id' 
    AND REFERENCED_TABLE_NAME = 'barangays'
");

if ($result && $result->num_rows == 0) {
    $sql = "ALTER TABLE addresses ADD CONSTRAINT fk_address_barangay FOREIGN KEY (barangay_id) REFERENCES barangays(barangay_id) ON DELETE SET NULL";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ Added foreign key constraint for barangay_id</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Warning: Could not add foreign key for barangay_id (may already exist or data conflicts): " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ️ Foreign key for barangay_id already exists</p>";
}

// Show updated table structure
echo "<h3>Updated addresses table structure:</h3>";
$structure = $conn->query("DESCRIBE addresses");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $structure->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Key'] . "</td><td>" . $row['Default'] . "</td><td>" . $row['Extra'] . "</td></tr>";
}
echo "</table>";

// Test the INSERT query that verify.php uses
echo "<h3>Testing the INSERT query structure...</h3>";

// Create a test query similar to what verify.php uses
$test_sql = "INSERT INTO addresses (customer_id, address_type, address_line, province_id, city_id, barangay_id, postal_code, is_primary, created_at) VALUES (?, 'home', ?, ?, ?, ?, ?, 1, NOW())";

$stmt = $conn->prepare($test_sql);
if ($stmt) {
    echo "<p style='color: green;'>✅ INSERT query preparation successful</p>";
    echo "<p>Query structure: " . htmlspecialchars($test_sql) . "</p>";
    $stmt->close();
} else {
    echo "<p style='color: red;'>❌ INSERT query preparation failed: " . $conn->error . "</p>";
}

$conn->close();
echo "<p style='color: green;'><strong>✅ Addresses table fixed successfully!</strong></p>";
echo "<p><a href='verify.php'>Test the verification process now</a></p>";
?>
