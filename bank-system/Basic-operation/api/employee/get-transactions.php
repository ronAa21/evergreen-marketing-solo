<?php
/**
 * Get Transactions API
 * Retrieves all transactions with optional filtering
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';

try {
    // Get filter parameters
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $fromDate = isset($_GET['from']) ? trim($_GET['from']) : '';
    $toDate = isset($_GET['to']) ? trim($_GET['to']) : '';
    $dateFilter = isset($_GET['dateFilter']) ? trim($_GET['dateFilter']) : '';

    // Connect to database
    $db = getDBConnection();
    if (!$db) {
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed'
        ]);
        exit();
    }

    // Build base query using unified_schema table names:
    // bank_transactions (bt), customer_accounts (ca), bank_customers (bc), bank_employees (be), transaction_types (tt)
    // Use LEFT JOINs for optional relationships to ensure all transactions are fetched
    $query = "
        SELECT 
            bt.transaction_id,
            bt.transaction_ref,
            bt.created_at,
            bt.amount,
            bt.description,
            bt.related_account_id,
            ca.account_number,
            COALESCE(
                CONCAT(
                    COALESCE(bc.first_name, ''), 
                    CASE WHEN bc.middle_name IS NOT NULL AND bc.middle_name != '' THEN CONCAT(' ', bc.middle_name) ELSE '' END,
                    CASE WHEN bc.last_name IS NOT NULL AND bc.last_name != '' THEN CONCAT(' ', bc.last_name) ELSE '' END
                ),
                CONCAT(
                    COALESCE(aa.first_name, ''), 
                    CASE WHEN aa.middle_name IS NOT NULL AND aa.middle_name != '' THEN CONCAT(' ', aa.middle_name) ELSE '' END,
                    CASE WHEN aa.last_name IS NOT NULL AND aa.last_name != '' THEN CONCAT(' ', aa.last_name) ELSE '' END
                ),
                'Unknown Customer'
            ) as customer_name,
            tt.type_name as transaction_type,
            be.employee_name
        FROM bank_transactions bt
        INNER JOIN transaction_types tt ON bt.transaction_type_id = tt.transaction_type_id
        LEFT JOIN customer_accounts ca ON bt.account_id = ca.account_id
        LEFT JOIN bank_customers bc ON ca.customer_id = bc.customer_id
        LEFT JOIN account_applications aa ON bc.application_id = aa.application_id
        LEFT JOIN bank_employees be ON bt.employee_id = be.employee_id
        WHERE 1=1
    ";

    $params = [];
    
    // Add type filter
    if (!empty($type) && $type !== 'all') {
        // Convert input types to match potential type_name values in the DB
        if ($type === 'withdrawal') {
            $query .= " AND tt.type_name = :type_name";
            $params[':type_name'] = 'Withdrawal';
        } elseif ($type === 'deposit') {
            $query .= " AND tt.type_name = :type_name";
            $params[':type_name'] = 'Deposit';
        } elseif ($type === 'transfer') {
            // Match both Transfer In and Transfer Out (unified schema has both)
            $query .= " AND (tt.type_name = 'Transfer In' OR tt.type_name = 'Transfer Out')";
            // No parameter binding needed for this case
        } else {
            // Use the provided type directly if it's not one of the common aliases
            $query .= " AND tt.type_name = :type_name";
            $params[':type_name'] = ucfirst($type);
        }
    }

    // Add date filter
    if (!empty($dateFilter)) {
        $today = date('Y-m-d');
        
        if ($dateFilter === 'last30days') {
            $startDate = date('Y-m-d', strtotime('-30 days'));
            $query .= " AND DATE(bt.created_at) >= :startDate";
            $params[':startDate'] = $startDate;
        } elseif ($dateFilter === 'lastmonth') {
            $startDate = date('Y-m-01', strtotime('first day of last month'));
            $endDate = date('Y-m-t', strtotime('last day of last month'));
            $query .= " AND DATE(bt.created_at) BETWEEN :startDate AND :endDate";
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
        } elseif ($dateFilter === 'thisyear') {
            $startDate = date('Y-01-01');
            $query .= " AND DATE(bt.created_at) >= :startDate";
            $params[':startDate'] = $startDate;
        }
    }

    // Add custom date range filter
    if (!empty($fromDate)) {
        $query .= " AND DATE(bt.created_at) >= :fromDate";
        $params[':fromDate'] = $fromDate;
    }
    
    if (!empty($toDate)) {
        $query .= " AND DATE(bt.created_at) <= :toDate";
        $params[':toDate'] = $toDate;
    }

    // Order by most recent first
    $query .= " ORDER BY bt.created_at DESC";

    // Execute query
    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format transactions for frontend
    $formattedTransactions = [];
    foreach ($transactions as $transaction) {
        // Use the transaction_ref field from the database, if available
        $reference = $transaction['transaction_ref'] ?? '';
        
        // If transaction_ref is empty, generate one from transaction_id or parse from description
        if (empty($reference)) {
            $description = $transaction['description'] ?? '';
            $referenceMatch = [];
            if (preg_match('/Ref:\s*([A-Z0-9]+)/', $description, $referenceMatch)) {
                $reference = $referenceMatch[1];
            } else {
                // Generate reference from transaction_id
                $reference = 'TXN-' . str_pad($transaction['transaction_id'], 8, '0', STR_PAD_LEFT);
            }
        }

        // Format date - better readable format
        $date = new DateTime($transaction['created_at']);
        $formattedDate = $date->format('M d, Y h:i A'); // e.g., "Jan 15, 2024 02:30 PM"

        // Determine method based on description or type
        $transactionType = $transaction['transaction_type'] ?? 'Unknown';
        $description = $transaction['description'] ?? '';
        
        $method = 'Cash'; // Default
        if (strpos($transactionType, 'Transfer') !== false) {
            $method = 'Electronic';
        } elseif (strpos($transactionType, 'Loan') !== false || 
                  strpos($transactionType, 'Interest') !== false || 
                  strpos($transactionType, 'Charge') !== false ||
                  strpos($transactionType, 'Service') !== false) {
            $method = 'System';
        } elseif (strpos($description, 'Teller') !== false) {
            $method = 'Teller';
        }

        $formattedTransactions[] = [
            'transaction_id' => $transaction['transaction_id'],
            'reference' => $reference,
            'date' => $formattedDate,
            'account_number' => $transaction['account_number'] ?? 'N/A',
            'title' => !empty($description) ? $description : $transactionType,
            'customer_name' => trim($transaction['customer_name']) ?: 'Unknown Customer',
            'type' => $transactionType,
            'method' => $method,
            'amount' => number_format(abs($transaction['amount']), 2),
            'status' => 'Completed', // All transactions in DB are completed
            'employee' => $transaction['employee_name'] ?? 'System',
            'raw_date' => $transaction['created_at']
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $formattedTransactions,
        'count' => count($formattedTransactions)
    ]);

} catch (Exception $e) {
    error_log("Get transactions error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to retrieve transactions',
        'error' => $e->getMessage()
    ]);
}
?>