<?php
/**
 * Check the actual structure of the referrals table in the database
 */

include("db_connect.php");

echo "<h2>Checking referrals table structure</h2>";

// Check if referrals table exists
$result = $conn->query("SHOW TABLES LIKE 'referrals'");
if ($result->num_rows == 0) {
    echo "<p style='color:red;'>ERROR: referrals table does not exist!</p>";
    echo "<p>You need to create it. Run this SQL:</p>";
    echo "<pre>";
    echo "CREATE TABLE `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer_id` int(11) NOT NULL COMMENT 'Customer who referred (from bank_customers)',
  `referred_id` int(11) NOT NULL COMMENT 'Customer who was referred (from bank_customers)',
  `points_earned` decimal(10,2) DEFAULT 0.00 COMMENT 'Points earned by referrer',
  `status` enum('pending','completed','cancelled') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_referrer` (`referrer_id`),
  KEY `idx_referred` (`referred_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    echo "</pre>";
} else {
    echo "<p style='color:green;'>✓ referrals table exists</p>";
    
    // Show table structure
    $result = $conn->query("DESCRIBE referrals");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if customer_id column exists (it shouldn't)
    $result = $conn->query("SHOW COLUMNS FROM referrals LIKE 'customer_id'");
    if ($result->num_rows > 0) {
        echo "<p style='color:red;'>⚠ WARNING: Found 'customer_id' column in referrals table!</p>";
        echo "<p>This column should not exist. The correct columns are 'referrer_id' and 'referred_id'.</p>";
        echo "<p>To fix, run: <code>ALTER TABLE referrals DROP COLUMN customer_id;</code></p>";
    } else {
        echo "<p style='color:green;'>✓ No 'customer_id' column found (correct)</p>";
    }
}

echo "<hr>";
echo "<h2>Checking user_missions table structure</h2>";

// Check user_missions table
$result = $conn->query("SHOW TABLES LIKE 'user_missions'");
if ($result->num_rows == 0) {
    echo "<p style='color:red;'>ERROR: user_missions table does not exist!</p>";
} else {
    echo "<p style='color:green;'>✓ user_missions table exists</p>";
    
    // Show table structure
    $result = $conn->query("DESCRIBE user_missions");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if customer_id column exists (it shouldn't - should be user_id)
    $result = $conn->query("SHOW COLUMNS FROM user_missions LIKE 'customer_id'");
    if ($result->num_rows > 0) {
        echo "<p style='color:red;'>⚠ WARNING: Found 'customer_id' column in user_missions table!</p>";
        echo "<p>This column should be 'user_id' instead.</p>";
        echo "<p>To fix, run: <code>ALTER TABLE user_missions CHANGE customer_id user_id INT(11) NOT NULL;</code></p>";
    } else {
        echo "<p style='color:green;'>✓ No 'customer_id' column found (correct)</p>";
    }
    
    // Check if user_id exists
    $result = $conn->query("SHOW COLUMNS FROM user_missions LIKE 'user_id'");
    if ($result->num_rows > 0) {
        echo "<p style='color:green;'>✓ 'user_id' column exists (correct)</p>";
    } else {
        echo "<p style='color:red;'>⚠ WARNING: 'user_id' column not found!</p>";
    }
}

$conn->close();
?>
