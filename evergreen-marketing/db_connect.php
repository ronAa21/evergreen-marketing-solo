<?php
/**
 * Database Connection - Smart Switch (Local + InfinityFree)
 * Automatically detects localhost for testing and uses InfinityFree for production
 */

// Suppress warnings for clean JSON output
error_reporting(0);

// ============================================
// DETECT ENVIRONMENT
// ============================================
$is_local = (
    isset($_SERVER['HTTP_HOST']) && 
    (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
     strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
) || (php_sapi_name() === 'cli' && gethostname() !== 'production');

if ($is_local) {
    // ============================================
    // LOCAL DEVELOPMENT - XAMPP/WAMP
    // ============================================
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "BankingDB";
    
    // Standard local connection
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        $db_connection_error = "Local connection failed: " . $conn->connect_error;
    } else {
        // Set charset
        $conn->set_charset("utf8mb4");
    }
    
} else {
    // ============================================
    // PRODUCTION - INFINITYFREE MYSQL
    // ============================================
    // TODO: Get these from InfinityFree Control Panel > MySQL Databases
    $host = "sql302.infinityfree.com";   // Replace with your hostname
    $user = "if0_41648719";               // Your InfinityFree username
    $pass = "Evergreen.01?";     // Your database password
    $db = "if0_41648719_bankingdb";       // Your database name
    
    // Standard connection (no SSL needed for InfinityFree)
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        $db_connection_error = "InfinityFree connection failed: " . $conn->connect_error;
    } else {
        // Set charset
        $conn->set_charset("utf8mb4");
    }
}

// Optional: Log connection errors
if (isset($db_connection_error)) {
    // Uncomment for debugging:
    // error_log($db_connection_error);
}
?>
