<?php
/**
 * Database Connection - Production Ready
 * For InfinityFree Deployment
 * 
 * INSTRUCTIONS:
 * 1. Rename this file to db_connect.php after updating credentials
 * 2. Update the PRODUCTION section with your InfinityFree database details
 * 3. Keep the original db_connect.php as db_connect_local.php for backup
 */

// Detect environment (local vs production)
$is_local = (
    $_SERVER['SERVER_NAME'] === 'localhost' || 
    $_SERVER['SERVER_ADDR'] === '127.0.0.1' ||
    strpos($_SERVER['SERVER_NAME'], 'localhost') !== false
);

if ($is_local) {
    // ============================================
    // LOCAL DEVELOPMENT CONFIGURATION
    // ============================================
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'bankingdb';
    $port = '3306';
    
} else {
    // ============================================
    // PRODUCTION CONFIGURATION (InfinityFree)
    // ============================================
    // TODO: Update these with your InfinityFree credentials
    
    $host = 'sqlXXX.infinityfreeapp.com';  // Replace XXX with your SQL server number
    $username = 'epiz_XXXXXXX';             // Your InfinityFree database username
    $password = 'YOUR_PASSWORD_HERE';       // Your database password
    $database = 'epiz_XXXXXXX_bankingdb';   // Your database name
    $port = '3306';
    
    // Example (replace with your actual credentials):
    // $host = 'sql200.infinityfreeapp.com';
    // $username = 'epiz_12345678';
    // $password = 'MySecurePassword123';
    // $database = 'epiz_12345678_bankingdb';
}

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    // Log error (don't show details in production)
    error_log("Database connection failed: " . $conn->connect_error);
    
    if ($is_local) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        die("Database connection error. Please contact support.");
    }
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Optional: Set SQL mode for better compatibility
$conn->query("SET sql_mode = ''");

// Success (optional, comment out in production)
// echo "Connected successfully to " . ($is_local ? "LOCAL" : "PRODUCTION") . " database";
?>
