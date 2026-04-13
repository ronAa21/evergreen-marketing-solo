<?php
require_once 'db_connect.php';

echo "<h2>Setup Admin User for Testing</h2>";

// Check if there are any users
$result = $conn->query("SELECT customer_id, email, first_name, last_name, is_admin FROM bank_customers ORDER BY customer_id");
$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo "<h3>Current Users:</h3>";
echo "<table border='1'><tr><th>ID</th><th>Email</th><th>Name</th><th>Admin Status</th><th>Action</th></tr>";

foreach ($users as $user) {
    $admin_status = $user['is_admin'] == 1 ? 'Admin' : 'Regular User';
    $action = $user['is_admin'] == 1 ? 
        "<a href='?action=remove_admin&id={$user['customer_id']}'>Remove Admin</a>" : 
        "<a href='?action=make_admin&id={$user['customer_id']}'>Make Admin</a>";
    
    echo "<tr><td>{$user['customer_id']}</td><td>{$user['email']}</td><td>{$user['first_name']} {$user['last_name']}</td><td>$admin_status</td><td>$action</td></tr>";
}
echo "</table>";

// Handle admin status changes
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $user_id = (int)$_GET['id'];
    
    if ($action === 'make_admin') {
        $stmt = $conn->prepare("UPDATE bank_customers SET is_admin = 1 WHERE customer_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✅ User ID $user_id is now an admin!</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to make user admin: " . $conn->error . "</p>";
        }
        $stmt->close();
    } elseif ($action === 'remove_admin') {
        $stmt = $conn->prepare("UPDATE bank_customers SET is_admin = 0 WHERE customer_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<p style='color: orange;'>⚠️ Admin status removed from User ID $user_id</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to remove admin status: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
    
    echo "<p><a href='setup_admin_user.php'>Refresh to see changes</a></p>";
}

echo "<h3>How to Test:</h3>";
echo "<ol>";
echo "<li>Use the links above to make a user an admin</li>";
echo "<li>Go to <a href='login.php'>login.php</a></li>";
echo "<li>Login with the admin user's credentials</li>";
echo "<li>You should be redirected to the admin dashboard</li>";
echo "<li>Regular users will be redirected to the customer portal</li>";
echo "</ol>";

$conn->close();
?>
