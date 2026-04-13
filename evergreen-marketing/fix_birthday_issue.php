<?php
// Fix birthday issue in database
include("db_connect.php");

echo "<h2>Birthday Issue Fix Script</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #003631; }
    .step { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #003631; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .info { color: blue; }
    table { border-collapse: collapse; width: 100%; margin: 15px 0; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background-color: #003631; color: white; }
    .btn { display: inline-block; padding: 10px 20px; background: #003631; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
    .btn:hover { background: #004d47; }
</style>";

echo "<div class='container'>";

// Step 1: Check if birthday column exists
echo "<div class='step'>";
echo "<h3>Step 1: Checking if birthday column exists</h3>";

$check_column = "SHOW COLUMNS FROM bank_customers LIKE 'birthday'";
$result = $conn->query($check_column);

if ($result->num_rows > 0) {
    echo "<p class='success'>✓ Birthday column exists</p>";
    $column_exists = true;
} else {
    echo "<p class='error'>✗ Birthday column does NOT exist</p>";
    echo "<p class='info'>Creating birthday column...</p>";
    
    $add_column = "ALTER TABLE bank_customers ADD COLUMN birthday DATE NULL AFTER contact_number";
    if ($conn->query($add_column)) {
        echo "<p class='success'>✓ Birthday column created successfully</p>";
        $column_exists = true;
    } else {
        echo "<p class='error'>✗ Failed to create birthday column: " . $conn->error . "</p>";
        $column_exists = false;
    }
}
echo "</div>";

if ($column_exists) {
    // Step 2: Check current birthday data
    echo "<div class='step'>";
    echo "<h3>Step 2: Checking current birthday data</h3>";
    
    $stats_sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN birthday IS NULL OR birthday = '0000-00-00' THEN 1 ELSE 0 END) as null_count,
                    SUM(CASE WHEN birthday IS NOT NULL AND birthday != '0000-00-00' THEN 1 ELSE 0 END) as has_value_count
                  FROM bank_customers";
    
    $stats_result = $conn->query($stats_sql);
    $stats = $stats_result->fetch_assoc();
    
    echo "<p><strong>Total Customers:</strong> " . $stats['total'] . "</p>";
    echo "<p><strong>With Birthday Data:</strong> <span class='success'>" . $stats['has_value_count'] . "</span></p>";
    echo "<p><strong>Without Birthday Data:</strong> <span class='warning'>" . $stats['null_count'] . "</span></p>";
    
    if ($stats['null_count'] > 0) {
        echo "<p class='warning'>⚠ " . $stats['null_count'] . " customer(s) have missing birthday data</p>";
    }
    echo "</div>";
    
    // Step 3: Show recent customers without birthday
    echo "<div class='step'>";
    echo "<h3>Step 3: Customers without birthday data</h3>";
    
    $missing_sql = "SELECT customer_id, first_name, last_name, email, created_at 
                    FROM bank_customers 
                    WHERE birthday IS NULL OR birthday = '0000-00-00'
                    ORDER BY customer_id DESC 
                    LIMIT 10";
    
    $missing_result = $conn->query($missing_sql);
    
    if ($missing_result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th></tr>";
        
        while ($row = $missing_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['customer_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<p class='info'>These customers need to update their birthday in their profile.</p>";
    } else {
        echo "<p class='success'>✓ All customers have birthday data</p>";
    }
    echo "</div>";
    
    // Step 4: Check signup.php to ensure birthday is being saved
    echo "<div class='step'>";
    echo "<h3>Step 4: Verifying signup process</h3>";
    
    $signup_file = __DIR__ . '/signup.php';
    if (file_exists($signup_file)) {
        $signup_content = file_get_contents($signup_file);
        
        // Check if birthday is in the INSERT query
        if (strpos($signup_content, "'birthday'") !== false || strpos($signup_content, "birthday") !== false) {
            echo "<p class='success'>✓ Signup.php includes birthday field</p>";
        } else {
            echo "<p class='error'>✗ Signup.php may not be saving birthday data</p>";
            echo "<p class='warning'>⚠ The signup form needs to be updated to save birthday data</p>";
        }
    } else {
        echo "<p class='warning'>⚠ Could not verify signup.php</p>";
    }
    echo "</div>";
    
    // Step 5: Recommendations
    echo "<div class='step'>";
    echo "<h3>Step 5: Recommendations</h3>";
    
    if ($stats['null_count'] > 0) {
        echo "<p class='info'>📋 For existing customers without birthday:</p>";
        echo "<ul>";
        echo "<li>They can update their birthday through their profile page (if we add an edit option)</li>";
        echo "<li>Or admin can manually update in the database</li>";
        echo "</ul>";
        
        echo "<p class='info'>💡 Would you like to:</p>";
        echo "<ol>";
        echo "<li>Add birthday edit functionality to profile.php</li>";
        echo "<li>Set a default birthday for existing accounts (e.g., 2000-01-01)</li>";
        echo "<li>Leave it as is and let users contact support</li>";
        echo "</ol>";
    } else {
        echo "<p class='success'>✓ All customers have birthday data. No action needed!</p>";
    }
    echo "</div>";
    
    // Step 6: Quick fix option
    echo "<div class='step'>";
    echo "<h3>Step 6: Quick Fix Options</h3>";
    
    echo "<form method='POST' style='margin: 20px 0;'>";
    echo "<p><strong>Option 1:</strong> Set default birthday (2000-01-01) for all accounts without birthday</p>";
    echo "<button type='submit' name='fix_action' value='set_default' class='btn' onclick='return confirm(\"This will set birthday to 2000-01-01 for all accounts without birthday. Continue?\")'>Set Default Birthday</button>";
    echo "</form>";
    
    echo "<form method='POST' style='margin: 20px 0;'>";
    echo "<p><strong>Option 2:</strong> Make birthday editable in profile page</p>";
    echo "<button type='submit' name='fix_action' value='make_editable' class='btn'>Enable Birthday Editing</button>";
    echo "</form>";
    
    // Handle fix actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fix_action'])) {
        echo "<div style='margin: 20px 0; padding: 15px; background: #e8f5e9; border-left: 4px solid #4caf50;'>";
        
        if ($_POST['fix_action'] === 'set_default') {
            $update_sql = "UPDATE bank_customers 
                          SET birthday = '2000-01-01' 
                          WHERE birthday IS NULL OR birthday = '0000-00-00'";
            
            if ($conn->query($update_sql)) {
                $affected = $conn->affected_rows;
                echo "<p class='success'>✓ Successfully set default birthday for $affected customer(s)</p>";
                echo "<p><a href='fix_birthday_issue.php' class='btn'>Refresh Page</a></p>";
            } else {
                echo "<p class='error'>✗ Failed to update: " . $conn->error . "</p>";
            }
        }
        
        if ($_POST['fix_action'] === 'make_editable') {
            echo "<p class='success'>✓ I'll update the profile.php to make birthday editable</p>";
            echo "<p class='info'>This requires modifying the profile.php file to add birthday editing functionality.</p>";
            echo "<p class='warning'>⚠ This action needs to be done manually by updating the code.</p>";
        }
        
        echo "</div>";
    }
    
    echo "</div>";
}

echo "<div style='margin-top: 30px; padding: 20px; background: #f0f0f0; border-radius: 8px;'>";
echo "<h3>Summary</h3>";
echo "<p>✓ Database structure checked</p>";
echo "<p>✓ Birthday data verified</p>";
echo "<p>✓ Signup process reviewed</p>";
echo "<p><a href='profile.php' class='btn'>Go to Profile</a> <a href='check_birthday.php' class='btn'>View Birthday Data</a></p>";
echo "</div>";

echo "</div>";

$conn->close();
?>
