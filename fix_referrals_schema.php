<?php
/**
 * Fix referrals and user_missions table schema issues
 */

include("db_connect.php");

echo "<h2>Database Schema Fix Script</h2>";
echo "<p>This script will fix column name mismatches in your database.</p>";
echo "<hr>";

$errors = [];
$fixes = [];

// Check and fix referrals table
echo "<h3>1. Checking referrals table...</h3>";

$result = $conn->query("SHOW TABLES LIKE 'referrals'");
if ($result->num_rows == 0) {
    echo "<p style='color:orange;'>Creating referrals table...</p>";
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
        echo "<p style='color:green;'>✓ Created referrals table successfully</p>";
        $fixes[] = "Created referrals table";
    } else {
        echo "<p style='color:red;'>✗ Error creating referrals table: " . $conn->error . "</p>";
        $errors[] = "Failed to create referrals table";
    }
} else {
    echo "<p style='color:green;'>✓ referrals table exists</p>";
    
    // Check for wrong column name
    $result = $conn->query("SHOW COLUMNS FROM referrals LIKE 'customer_id'");
    if ($result->num_rows > 0) {
        echo "<p style='color:orange;'>Found incorrect 'customer_id' column. Fixing...</p>";
        
        // Drop the wrong column
        if ($conn->query("ALTER TABLE referrals DROP COLUMN customer_id")) {
            echo "<p style='color:green;'>✓ Removed customer_id column</p>";
            $fixes[] = "Removed customer_id from referrals";
        } else {
            echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            $errors[] = "Failed to remove customer_id from referrals";
        }
    }
    
    // Ensure correct columns exist
    $result = $conn->query("SHOW COLUMNS FROM referrals LIKE 'referrer_id'");
    if ($result->num_rows == 0) {
        echo "<p style='color:orange;'>Adding referrer_id column...</p>";
        if ($conn->query("ALTER TABLE referrals ADD COLUMN referrer_id INT(11) NOT NULL AFTER id")) {
            echo "<p style='color:green;'>✓ Added referrer_id column</p>";
            $fixes[] = "Added referrer_id to referrals";
        } else {
            echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            $errors[] = "Failed to add referrer_id";
        }
    }
    
    $result = $conn->query("SHOW COLUMNS FROM referrals LIKE 'referred_id'");
    if ($result->num_rows == 0) {
        echo "<p style='color:orange;'>Adding referred_id column...</p>";
        if ($conn->query("ALTER TABLE referrals ADD COLUMN referred_id INT(11) NOT NULL AFTER referrer_id")) {
            echo "<p style='color:green;'>✓ Added referred_id column</p>";
            $fixes[] = "Added referred_id to referrals";
        } else {
            echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            $errors[] = "Failed to add referred_id";
        }
    }
}

echo "<hr>";

// Check and fix user_missions table
echo "<h3>2. Checking user_missions table...</h3>";

$result = $conn->query("SHOW TABLES LIKE 'user_missions'");
if ($result->num_rows == 0) {
    echo "<p style='color:red;'>✗ user_missions table does not exist!</p>";
    $errors[] = "user_missions table missing";
} else {
    echo "<p style='color:green;'>✓ user_missions table exists</p>";
    
    // Check for wrong column name
    $result = $conn->query("SHOW COLUMNS FROM user_missions LIKE 'customer_id'");
    if ($result->num_rows > 0) {
        echo "<p style='color:orange;'>Found incorrect 'customer_id' column. Renaming to 'user_id'...</p>";
        
        if ($conn->query("ALTER TABLE user_missions CHANGE customer_id user_id INT(11) NOT NULL")) {
            echo "<p style='color:green;'>✓ Renamed customer_id to user_id</p>";
            $fixes[] = "Renamed customer_id to user_id in user_missions";
        } else {
            echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            $errors[] = "Failed to rename customer_id in user_missions";
        }
    } else {
        // Check if user_id exists
        $result = $conn->query("SHOW COLUMNS FROM user_missions LIKE 'user_id'");
        if ($result->num_rows > 0) {
            echo "<p style='color:green;'>✓ user_id column exists (correct)</p>";
        } else {
            echo "<p style='color:red;'>✗ Neither customer_id nor user_id found!</p>";
            $errors[] = "user_missions missing user_id column";
        }
    }
}

echo "<hr>";
echo "<h2>Summary</h2>";

if (count($fixes) > 0) {
    echo "<h3 style='color:green;'>Fixes Applied:</h3>";
    echo "<ul>";
    foreach ($fixes as $fix) {
        echo "<li>$fix</li>";
    }
    echo "</ul>";
}

if (count($errors) > 0) {
    echo "<h3 style='color:red;'>Errors:</h3>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:green; font-size:18px; font-weight:bold;'>✓ All checks passed! Your database schema is correct.</p>";
}

echo "<hr>";
echo "<p><a href='refer.php'>← Back to Refer Page</a></p>";

$conn->close();
?>
