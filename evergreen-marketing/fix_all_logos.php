<?php
/**
 * Automated Logo Path Fixer
 * This script updates all logo references across the entire system
 * 
 * Usage: Run this file once from browser or CLI
 * URL: http://localhost/evergreen-marketing/fix_all_logos.php
 */

set_time_limit(300); // 5 minutes max execution

// Configuration
$new_main_logo = 'images/Logo.png';
$new_login_logo = 'images/loginlogo.png';

// Patterns to fix
$replacements = [
    // Fix double extension - all variations
    'images/Logo.png.png' => $new_main_logo,
    '../images/Logo.png.png' => '../images/Logo.png',
    '../../images/Logo.png.png' => '../../images/Logo.png',
    
    // Standardize login logo
    'images/loginlogo.png' => $new_login_logo,
    '../images/loginlogo.png' => '../images/loginlogo.png',
    '../../images/loginlogo.png' => '../../images/loginlogo.png',
    
    // Fix any remaining .png.png patterns
    'Logo.png.png' => 'Logo.png',
    'loginlogo.png.png' => 'loginlogo.png',
];

// Directories to scan
$directories = [
    __DIR__,                                    // evergreen-marketing root
    __DIR__ . '/cards',                         // evergreen-marketing/cards
    __DIR__ . '/Content-view',                  // evergreen-marketing/Content-view
    __DIR__ . '/includes',                      // evergreen-marketing/includes
    dirname(__DIR__) . '/cards',                // Root cards folder
    dirname(__DIR__),                           // Root directory (for signup.php, login.php, etc.)
];

$files_updated = 0;
$total_replacements = 0;
$errors = [];

echo "<!DOCTYPE html>
<html>
<head>
    <title>Logo Path Fixer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #003631;
            border-bottom: 3px solid #F1B24A;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .file-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            max-height: 400px;
            overflow-y: auto;
        }
        .file-item {
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #003631 0%, #005a50 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 36px;
        }
        .stat-card p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #F1B24A;
            color: #003631;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background: #e69610;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎨 Logo Path Fixer</h1>
        <div class='info'>
            <strong>Starting logo path update...</strong><br>
            This will scan all PHP files and update logo references.
        </div>";

// Function to recursively get all PHP files
function getPhpFiles($dir) {
    $files = [];
    if (is_dir($dir)) {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') continue;
            
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $files = array_merge($files, getPhpFiles($path));
            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                $files[] = $path;
            }
        }
    }
    return $files;
}

// Get all PHP files
$all_files = [];
foreach ($directories as $dir) {
    $all_files = array_merge($all_files, getPhpFiles($dir));
}

echo "<div class='info'>Found " . count($all_files) . " PHP files to scan.</div>";

// Show directories being scanned
echo "<div class='info'>
    <strong>📁 Scanning directories:</strong><br>
    • evergreen-marketing/ (root)<br>
    • evergreen-marketing/cards/<br>
    • evergreen-marketing/Content-view/<br>
    • evergreen-marketing/includes/<br>
    • cards/ (root level)<br>
    • Root directory files (signup.php, login.php, etc.)<br>
</div>";

echo "<div class='file-list'>";

// Process each file
foreach ($all_files as $file) {
    try {
        $content = file_get_contents($file);
        $original_content = $content;
        $file_changes = 0;
        
        // Apply all replacements
        foreach ($replacements as $old => $new) {
            $count = 0;
            $content = str_replace($old, $new, $content, $count);
            if ($count > 0) {
                $file_changes += $count;
                $total_replacements += $count;
            }
        }
        
        // Save if changes were made
        if ($content !== $original_content) {
            if (file_put_contents($file, $content)) {
                $files_updated++;
                $relative_path = str_replace(__DIR__ . '/', '', $file);
                echo "<div class='file-item'>✅ Updated: <strong>$relative_path</strong> ($file_changes changes)</div>";
            } else {
                $errors[] = "Failed to write: $file";
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error processing $file: " . $e->getMessage();
    }
}

echo "</div>";

// Display statistics
echo "<div class='stats'>
    <div class='stat-card'>
        <h3>$files_updated</h3>
        <p>Files Updated</p>
    </div>
    <div class='stat-card'>
        <h3>$total_replacements</h3>
        <p>Total Replacements</p>
    </div>
    <div class='stat-card'>
        <h3>" . count($all_files) . "</h3>
        <p>Files Scanned</p>
    </div>
</div>";

// Display results
if ($files_updated > 0) {
    echo "<div class='success'>
        <strong>✅ Success!</strong><br>
        Updated $files_updated files with $total_replacements logo path replacements.
    </div>";
} else {
    echo "<div class='info'>
        <strong>ℹ️ No Changes Needed</strong><br>
        All logo paths are already correct!
    </div>";
}

// Display errors if any
if (!empty($errors)) {
    echo "<div class='error'>
        <strong>⚠️ Errors Encountered:</strong><br>";
    foreach ($errors as $error) {
        echo "• " . htmlspecialchars($error) . "<br>";
    }
    echo "</div>";
}

// Next steps
echo "<div class='info'>
    <strong>📋 Next Steps:</strong><br>
    1. Clear your browser cache (Ctrl+F5)<br>
    2. Test the website to verify logos appear correctly<br>
    3. Check mobile responsive view<br>
    4. Delete this script file for security (fix_all_logos.php)
</div>";

echo "<a href='index.php' class='btn'>🏠 Go to Homepage</a>";
echo "<a href='admin_dashboard.php' class='btn'>👨‍💼 Go to Admin</a>";

echo "</div></body></html>";

// Log the operation
$log_entry = date('Y-m-d H:i:s') . " - Logo paths updated: $files_updated files, $total_replacements replacements\n";
file_put_contents(__DIR__ . '/logo_update.log', $log_entry, FILE_APPEND);
?>
