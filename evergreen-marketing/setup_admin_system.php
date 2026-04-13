<?php
/**
 * Admin System Setup Script
 * Run this once to set up the admin system
 */

include("db_connect.php");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Admin System Setup</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #003631; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: #004085; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #003631; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Admin System Setup</h1>";

$errors = [];
$success = [];

// Read and execute SQL file
$sql_file = __DIR__ . '/sql/create_admin_system.sql';
if (file_exists($sql_file)) {
    $sql_content = file_get_contents($sql_file);
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql_content)));
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        if ($conn->multi_query($statement . ';')) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
        }
    }
    
    echo "<div class='success'>✓ Database tables created successfully</div>";
    $success[] = "Database setup complete";
} else {
    echo "<div class='error'>✗ SQL file not found: $sql_file</div>";
    $errors[] = "SQL file missing";
}

// Verify tables were created
$tables_to_check = ['admin_users', 'site_content', 'card_applications'];
echo "<h3>Verifying Tables:</h3>";

foreach ($tables_to_check as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<div class='success'>✓ Table '$table' exists</div>";
    } else {
        echo "<div class='error'>✗ Table '$table' not found</div>";
        $errors[] = "Table $table missing";
    }
}

// Check if default admin exists
$result = $conn->query("SELECT * FROM admin_users WHERE username = 'admin'");
if ($result && $result->num_rows > 0) {
    echo "<div class='success'>✓ Default admin user exists</div>";
    $admin = $result->fetch_assoc();
    echo "<div class='info'>
        <strong>Default Admin Credentials:</strong><br>
        Username: admin<br>
        Password: admin123<br>
        Email: " . htmlspecialchars($admin['email']) . "
    </div>";
} else {
    echo "<div class='error'>✗ Default admin user not found</div>";
    $errors[] = "Admin user missing";
}

// Check site content
$result = $conn->query("SELECT COUNT(*) as count FROM site_content");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<div class='success'>✓ Site content initialized ({$row['count']} items)</div>";
} else {
    echo "<div class='error'>✗ Site content not initialized</div>";
}

echo "<hr>";

if (count($errors) === 0) {
    echo "<div class='success' style='font-size: 18px; font-weight: bold;'>
        ✅ Setup Complete! Your admin system is ready to use.
    </div>";
    echo "<a href='admin_login.php' class='btn'>Go to Admin Login →</a>";
} else {
    echo "<div class='error' style='font-size: 18px; font-weight: bold;'>
        ⚠ Setup completed with errors. Please check the messages above.
    </div>";
    echo "<h3>Errors:</h3><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

$conn->close();

echo "    </div>
</body>
</html>";
?>
