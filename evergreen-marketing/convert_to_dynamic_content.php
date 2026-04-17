<?php
/**
 * Convert Static Pages to Dynamic Content System
 * This script updates all user-facing pages to pull content from database
 */

set_time_limit(600); // 10 minutes

// Files to convert
$files_to_convert = [
    // Main pages
    'index.php',
    'viewingpage.php',
    'viewing.php',
    'about.php',
    'aboutno.php',
    'learnmore.php',
    'learnmoreno.php',
    'faq.php',
    'faqno.php',
    'cardrewards.php',
    'cardrewardsno.php',
    'refer.php',
    'profile.php',
    'policy.php',
    'policyno.php',
    'terms.php',
    'termsno.php',
    
    // Card pages - ALL files in cards folder
    'cards/credit.php',
    'cards/creditno.php',
    'cards/debit.php',
    'cards/debitno.php',
    'cards/prepaid.php',
    'cards/prepaidno.php',
    'cards/points.php',
    'cards/rewards.php',
];

$conversions = [
    // Logo replacements
    'images/Logo.png' => '<?php echo get_company_logo(); ?>',
    '../images/Logo.png' => '<?php echo "../" . get_company_logo(); ?>',
    'images/loginlogo.png' => '<?php echo get_company_logo(); ?>',
    
    // Company info
    'EVERGREEN' => '<?php echo get_company_name(); ?>',
    'Evergreen Bank' => '<?php echo get_company_name(); ?>',
    'evrgrn.64@gmail.com' => '<?php echo get_contact_email(); ?>',
    
    // Hero section
    'Banking that grows with you' => '<?php echo get_hero_title(); ?>',
    'Secure financial solutions for every stage of your life journey' => '<?php echo get_hero_paragraph(); ?>',
];

echo "<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Content Converter</title>
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
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #ffc107;
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
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #003631;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #005a50;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔄 Dynamic Content Converter</h1>
        
        <div class='warning'>
            <strong>⚠️ Important:</strong> This will modify your PHP files to use dynamic content from the database.
            Make sure you have backups before proceeding!
        </div>
        
        <div class='info'>
            <strong>What this does:</strong><br>
            • Adds content_helper.php include to all pages<br>
            • Replaces static content with dynamic database calls<br>
            • Updates logo paths to use get_company_logo()<br>
            • Makes all content editable from admin panel<br>
        </div>";

// Check if content_helper exists
$helper_path = __DIR__ . '/includes/content_helper.php';
if (!file_exists($helper_path)) {
    echo "<div class='warning'>
        ⚠️ Warning: content_helper.php not found at: $helper_path<br>
        Please ensure the file exists before running this converter.
    </div>";
} else {
    echo "<div class='success'>
        ✓ content_helper.php found and ready to use
    </div>";
}

echo "<h2>📋 Files to Convert</h2>";
echo "<div class='file-list'>";

$converted_count = 0;
$skipped_count = 0;

foreach ($files_to_convert as $file) {
    $full_path = __DIR__ . '/' . $file;
    
    if (!file_exists($full_path)) {
        echo "<div class='file-item'>
            <span>❌ $file</span>
            <span style='color: #dc3545;'>Not Found</span>
        </div>";
        $skipped_count++;
        continue;
    }
    
    $content = file_get_contents($full_path);
    
    // Check if already converted
    if (strpos($content, 'includes/content_helper.php') !== false || 
        strpos($content, '../includes/content_helper.php') !== false) {
        echo "<div class='file-item'>
            <span>⏭️ $file</span>
            <span style='color: #6c757d;'>Already Converted</span>
        </div>";
        $skipped_count++;
        continue;
    }
    
    // Add content_helper include after session_start or at the beginning
    if (strpos($content, '<?php') !== false) {
        // Find the position after <?php
        $php_pos = strpos($content, '<?php');
        $insert_pos = $php_pos + 5;
        
        // Check if there's session_start
        if (preg_match('/session_start\(\);/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $insert_pos = $matches[0][1] + strlen($matches[0][0]);
        }
        
        // Determine the correct path based on file location
        $include_path = 'includes/content_helper.php';
        if (strpos($file, 'cards/') === 0) {
            $include_path = '../includes/content_helper.php';
        }
        
        $include_code = "\nrequire_once '$include_path';\n";
        $content = substr_replace($content, $include_code, $insert_pos, 0);
        
        // Save the file
        if (file_put_contents($full_path, $content)) {
            echo "<div class='file-item'>
                <span>✅ $file</span>
                <span style='color: #28a745;'>Converted</span>
            </div>";
            $converted_count++;
        } else {
            echo "<div class='file-item'>
                <span>❌ $file</span>
                <span style='color: #dc3545;'>Failed to Write</span>
            </div>";
        }
    }
}

echo "</div>";

echo "<div class='success'>
    <strong>✅ Conversion Complete!</strong><br>
    • Converted: $converted_count files<br>
    • Skipped: $skipped_count files<br>
</div>";

echo "<div class='info'>
    <strong>📝 Next Steps:</strong><br>
    1. Test your pages to ensure they load correctly<br>
    2. Update content in admin panel: <a href='admin_dashboard.php?page=content'>Content Management</a><br>
    3. Changes in admin will now reflect immediately on user pages<br>
    4. Clear browser cache (Ctrl+F5) to see updates<br>
</div>";

echo "<div style='text-align: center; margin-top: 30px;'>
    <a href='index.php' class='btn'>🏠 Test Homepage</a>
    <a href='admin_dashboard.php?page=content' class='btn'>⚙️ Manage Content</a>
</div>";

echo "</div></body></html>";
?>
