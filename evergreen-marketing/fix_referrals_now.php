<?php
/**
 * Emergency fix for referrals table
 * This will drop and recreate the table with correct column names
 */

include("db_connect.php");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Referrals Table</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #003631; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px 0; }
        .info { color: #004085; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #003631; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #005544; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Fix Referrals Table</h1>";

// Check if referrals table exists
$result = $conn->query("SHOW TABLES LIKE 'referrals'");
if ($result->num_rows > 0) {
    echo "<div class='info'>Found existing referrals table. Checking structure...</div>";
    
    // Check current structure
    $result = $conn->query("DESCRIBE referrals");
    echo "<h3>Current Structure:</h3>";
    echo "<pre>";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    echo "</pre>";
    
    // Check if it has the wrong column
    $result = $conn->query("SHOW COLUMNS FROM referrals LIKE 'customer_id'");
    if ($result->num_rows > 0) {
        echo "<div class='error'>❌ Found incorrect 'customer_id' column!</div>";
        echo "<div class='info'>Dropping and recreating table with correct structure...</div>";
        
        // Drop the table
        if ($conn->query("DROP TABLE IF EXISTS referrals")) {
            echo "<div class='success'>✓ Dropped old table</div>";
        } else {
            echo "<div class='error'>✗ Error dropping table: " . $conn->error . "</div>";
            exit;
        }
    } else {
        echo "<div class='success'>✓ Table structure looks correct!</div>";
        echo "<div class='info'>No fix needed.</div>";
        echo "<a href='refer.php' class='btn'>← Back to Refer Page</a>";
        echo "</div></body></html>";
        exit;
    }
}

// Create the table with correct structure
echo "<div class='info'>Creating referrals table with correct structure...</div>";

$sql = "CREATE TABLE `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer_id` int(11) NOT NULL COMMENT 'Customer who referred (from bank_customers)',
  `referred_id` int(11) NOT NULL COMMENT 'Customer who was referred (from bank_customers)',
  `points_earned` decimal(10,2) DEFAULT 0.00 COMMENT 'Points earned by referrer',
  `status` enum('pending','completed','cancelled') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_referrer` (`referrer_id`),
  KEY `idx_referred` (`referred_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql)) {
    echo "<div class='success'>✓ Successfully created referrals table!</div>";
    
    // Show new structure
    $result = $conn->query("DESCRIBE referrals");
    echo "<h3>New Structure:</h3>";
    echo "<pre>";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    echo "</pre>";
    
    echo "<div class='success' style='font-size: 18px; font-weight: bold;'>
        ✅ Fix Complete! Your referrals table is now correct.
    </div>";
    
    echo "<a href='refer.php' class='btn'>← Back to Refer Page</a>";
    
} else {
    echo "<div class='error'>✗ Error creating table: " . $conn->error . "</div>";
}

$conn->close();

echo "    </div>
</body>
</html>";
?>
