<?php
/**
 * Reset Admin Password Script
 * This will reset the admin password to: admin123
 */

include("db_connect.php");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Reset Admin Password</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #003631; }
        .success { color: green; padding: 15px; background: #d4edda; border-radius: 5px; margin: 15px 0; }
        .error { color: red; padding: 15px; background: #f8d7da; border-radius: 5px; margin: 15px 0; }
        .info { color: #004085; padding: 15px; background: #d1ecf1; border-radius: 5px; margin: 15px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #003631; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .credentials { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #003631; }
        .credentials strong { display: block; margin-bottom: 10px; color: #003631; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔐 Reset Admin Password</h1>";

// Generate new password hash for "admin123"
$new_password = 'admin123';
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Check if admin user exists
$check_sql = "SELECT admin_id, username, email FROM admin_users WHERE username = 'admin'";
$result = $conn->query($check_sql);

if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    
    // Update password
    $update_sql = "UPDATE admin_users SET password_hash = ? WHERE username = 'admin'";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("s", $password_hash);
    
    if ($stmt->execute()) {
        echo "<div class='success'>
            ✅ <strong>Password Reset Successful!</strong><br>
            The admin password has been reset.
        </div>";
        
        echo "<div class='credentials'>
            <strong>Admin Login Credentials:</strong>
            <div style='margin: 10px 0;'>
                <strong>Username:</strong> admin<br>
                <strong>Password:</strong> admin123<br>
                <strong>Email:</strong> " . htmlspecialchars($admin['email']) . "
            </div>
        </div>";
        
        echo "<div class='info'>
            <strong>Next Steps:</strong><br>
            1. Go to the admin login page<br>
            2. Use the credentials above to login<br>
            3. Consider changing the password after login for security
        </div>";
        
        echo "<a href='admin_login.php' class='btn'>Go to Admin Login →</a>";
    } else {
        echo "<div class='error'>
            ❌ <strong>Error updating password:</strong><br>
            " . htmlspecialchars($conn->error) . "
        </div>";
    }
    
    $stmt->close();
} else {
    echo "<div class='error'>
        ❌ <strong>Admin user not found!</strong><br>
        The admin user doesn't exist in the database.
    </div>";
    
    echo "<div class='info'>
        <strong>Solution:</strong><br>
        Run the setup script first to create the admin user:<br>
        <a href='setup_admin_system.php'>setup_admin_system.php</a>
    </div>";
}

// Also show current admin users for debugging
echo "<hr>";
echo "<h3>Current Admin Users:</h3>";
$all_admins = $conn->query("SELECT admin_id, username, email, full_name, is_active, created_at FROM admin_users");

if ($all_admins && $all_admins->num_rows > 0) {
    echo "<table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
            <tr style='background: #003631; color: white;'>
                <th style='padding: 10px; text-align: left;'>ID</th>
                <th style='padding: 10px; text-align: left;'>Username</th>
                <th style='padding: 10px; text-align: left;'>Email</th>
                <th style='padding: 10px; text-align: left;'>Status</th>
            </tr>";
    
    while ($row = $all_admins->fetch_assoc()) {
        $status = $row['is_active'] ? '✅ Active' : '❌ Inactive';
        echo "<tr style='border-bottom: 1px solid #ddd;'>
                <td style='padding: 10px;'>" . $row['admin_id'] . "</td>
                <td style='padding: 10px;'><strong>" . htmlspecialchars($row['username']) . "</strong></td>
                <td style='padding: 10px;'>" . htmlspecialchars($row['email']) . "</td>
                <td style='padding: 10px;'>$status</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>No admin users found in database.</div>";
}

$conn->close();

echo "    </div>
</body>
</html>";
?>
