<?php
session_start();
include("db_connect.php");

echo "<h2>Card Applications Diagnostic</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #003631; color: white; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 15px 0; }
</style>";

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    echo "<div class='info'><strong>Logged in as User ID:</strong> " . $user_id . "</div>";
} else {
    echo "<div class='error'>⚠ Not logged in! Please login first.</div>";
    echo "<p><a href='login.php'>Go to Login</a></p>";
    exit;
}

// 1. Check if card_applications table exists
echo "<h3>1. Checking card_applications table</h3>";
$check_table = "SHOW TABLES LIKE 'card_applications'";
$result = $conn->query($check_table);

if ($result->num_rows > 0) {
    echo "<p class='success'>✓ card_applications table exists</p>";
    
    // Show table structure
    $structure = $conn->query("DESCRIBE card_applications");
    echo "<h4>Table Structure:</h4>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>✗ card_applications table does NOT exist</p>";
    echo "<p>Creating table...</p>";
    
    $create_table = "CREATE TABLE IF NOT EXISTS card_applications (
        application_id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT NOT NULL,
        card_type VARCHAR(50) NOT NULL,
        application_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'approved', 'declined') DEFAULT 'pending',
        reviewed_by INT NULL,
        reviewed_at DATETIME NULL,
        FOREIGN KEY (customer_id) REFERENCES bank_customers(customer_id) ON DELETE CASCADE
    )";
    
    if ($conn->query($create_table)) {
        echo "<p class='success'>✓ Table created successfully</p>";
    } else {
        echo "<p class='error'>✗ Failed to create table: " . $conn->error . "</p>";
    }
}

// 2. Check all card applications in database
echo "<h3>2. All Card Applications in Database</h3>";
$all_apps = "SELECT ca.*, bc.first_name, bc.last_name, bc.email 
              FROM card_applications ca 
              LEFT JOIN bank_customers bc ON ca.customer_id = bc.customer_id 
              ORDER BY ca.application_date DESC 
              LIMIT 20";
$result = $conn->query($all_apps);

if ($result->num_rows > 0) {
    echo "<p>Found " . $result->num_rows . " card application(s)</p>";
    echo "<table>";
    echo "<tr><th>App ID</th><th>Customer ID</th><th>Name</th><th>Card Type</th><th>Date</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['application_id'] . "</td>";
        echo "<td>" . $row['customer_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['card_type']) . "</td>";
        echo "<td>" . $row['application_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>No card applications found in database</p>";
}

// 3. Check card applications for current user
echo "<h3>3. Card Applications for Current User (ID: $user_id)</h3>";
$user_apps = "SELECT * FROM card_applications WHERE customer_id = ? ORDER BY application_date DESC";
$stmt = $conn->prepare($user_apps);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<p class='success'>Found " . $result->num_rows . " card application(s) for you</p>";
    echo "<table>";
    echo "<tr><th>App ID</th><th>Card Type</th><th>Date</th><th>Status</th><th>Reviewed At</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['application_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['card_type']) . "</td>";
        echo "<td>" . $row['application_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . ($row['reviewed_at'] ?? 'Not reviewed') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>No card applications found for your account</p>";
}
$stmt->close();

// 4. Check account_applications table
echo "<h3>4. Checking account_applications table</h3>";
$check_account_apps = "SHOW TABLES LIKE 'account_applications'";
$result = $conn->query($check_account_apps);

if ($result->num_rows > 0) {
    echo "<p class='success'>✓ account_applications table exists</p>";
    
    // Check for applications with selected_cards
    $account_apps = "SELECT application_id, customer_id, first_name, last_name, selected_cards, submitted_at 
                     FROM account_applications 
                     WHERE customer_id = ? 
                     ORDER BY submitted_at DESC";
    $stmt = $conn->prepare($account_apps);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<p>Found " . $result->num_rows . " account application(s) for you</p>";
        echo "<table>";
        echo "<tr><th>App ID</th><th>Name</th><th>Selected Cards</th><th>Submitted At</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['application_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['selected_cards'] ?? 'None') . "</td>";
            echo "<td>" . $row['submitted_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>No account applications found for your account</p>";
    }
    $stmt->close();
} else {
    echo "<p class='error'>✗ account_applications table does NOT exist</p>";
}

// 5. Test insert
echo "<h3>5. Test Card Application Insert</h3>";
echo "<form method='POST'>";
echo "<p>Test creating a card application:</p>";
echo "<select name='test_card_type'>";
echo "<option value='debit'>Debit Card</option>";
echo "<option value='credit'>Credit Card</option>";
echo "<option value='prepaid'>Prepaid Card</option>";
echo "</select>";
echo "<button type='submit' name='test_insert'>Create Test Application</button>";
echo "</form>";

if (isset($_POST['test_insert'])) {
    $test_card_type = $_POST['test_card_type'];
    $test_sql = "INSERT INTO card_applications (customer_id, card_type, application_date, status) 
                 VALUES (?, ?, NOW(), 'pending')";
    $test_stmt = $conn->prepare($test_sql);
    $test_stmt->bind_param("is", $user_id, $test_card_type);
    
    if ($test_stmt->execute()) {
        echo "<p class='success'>✓ Test card application created successfully! (ID: " . $test_stmt->insert_id . ")</p>";
        echo "<p><a href='check_card_applications.php'>Refresh page</a></p>";
    } else {
        echo "<p class='error'>✗ Failed to create test application: " . $test_stmt->error . "</p>";
    }
    $test_stmt->close();
}

// 6. Links
echo "<h3>6. Quick Links</h3>";
echo "<p><a href='profile.php'>View Profile (Card Status)</a></p>";
echo "<p><a href='admin_login.php'>Admin Login</a></p>";
echo "<p><a href='evergreen_form.php'>Account Application Form</a></p>";

$conn->close();
?>
