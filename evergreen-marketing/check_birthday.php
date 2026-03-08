<?php
// Check birthday data in database
include("db_connect.php");

echo "<h2>Birthday Data Check</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #003631; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .null { color: red; font-weight: bold; }
    .has-value { color: green; font-weight: bold; }
</style>";

// Check if birthday column exists
$check_column = "SHOW COLUMNS FROM bank_customers LIKE 'birthday'";
$result = $conn->query($check_column);

if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Birthday column exists in bank_customers table</p>";
    
    // Fetch all customers with their birthday data
    $sql = "SELECT customer_id, first_name, last_name, email, birthday, created_at 
            FROM bank_customers 
            ORDER BY customer_id DESC 
            LIMIT 20";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Birthday</th>
                <th>Status</th>
                <th>Created At</th>
              </tr>";
        
        while ($row = $result->fetch_assoc()) {
            $birthday_status = empty($row['birthday']) || $row['birthday'] === '0000-00-00' 
                ? "<span class='null'>NULL/Empty</span>" 
                : "<span class='has-value'>Has Value</span>";
            
            $birthday_display = empty($row['birthday']) || $row['birthday'] === '0000-00-00' 
                ? 'N/A' 
                : $row['birthday'];
            
            echo "<tr>";
            echo "<td>" . $row['customer_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($birthday_display) . "</td>";
            echo "<td>" . $birthday_status . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Count statistics
        $stats_sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN birthday IS NULL OR birthday = '0000-00-00' THEN 1 ELSE 0 END) as null_count,
                        SUM(CASE WHEN birthday IS NOT NULL AND birthday != '0000-00-00' THEN 1 ELSE 0 END) as has_value_count
                      FROM bank_customers";
        
        $stats_result = $conn->query($stats_sql);
        $stats = $stats_result->fetch_assoc();
        
        echo "<div style='margin-top: 30px; padding: 20px; background: #f0f0f0; border-radius: 8px;'>";
        echo "<h3>Statistics</h3>";
        echo "<p><strong>Total Customers:</strong> " . $stats['total'] . "</p>";
        echo "<p><strong>With Birthday:</strong> <span class='has-value'>" . $stats['has_value_count'] . "</span></p>";
        echo "<p><strong>Without Birthday:</strong> <span class='null'>" . $stats['null_count'] . "</span></p>";
        echo "</div>";
        
    } else {
        echo "<p>No customers found in database.</p>";
    }
    
} else {
    echo "<p style='color: red;'>✗ Birthday column does NOT exist in bank_customers table</p>";
    echo "<p>You may need to add this column to the database.</p>";
}

$conn->close();
?>
