<?php
/**
 * COMPLETE Dynamic Content Converter
 * This script fully converts all pages to use dynamic content from database
 * Includes: Adding content_helper.php + Replacing static content with dynamic calls
 */

set_time_limit(600);
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    'signup.php',
    'login.php',
    
    // Card pages
    'cards/credit.php',
    'cards/creditno.php',
    'cards/debit.php',
    'cards/debitno.php',
    'cards/prepaid.php',
    'cards/prepaidno.php',
    'cards/points.php',
    'cards/rewards.php',
];

// Content replacements - static to dynamic
$main_replacements = [
    // Logo - main pages
    'src="images/Logo.png"' => 'src="<?php echo get_company_logo(); ?>"',
    'src="images/loginlogo.png"' => 'src="<?php echo get_company_logo(); ?>"',
    
    // Company name
    '>EVERGREEN<' => '><?php echo get_company_name(); ?><',
    '>Evergreen Bank<' => '><?php echo get_company_name(); ?><',
    'Evergreen Bank' => '<?php echo get_company_name(); ?>',
    
    // Contact info
    'evrgrn.64@gmail.com' => '<?php echo get_contact_email(); ?>',
    'href="mailto:evrgrn.64@gmail.com"' => 'href="mailto:<?php echo get_contact_email(); ?>"',
    
    // Hero section
    'Banking that grows with you' => '<?php echo get_hero_title(); ?>',
    'Secure financial solutions for every stage of your life journey' => '<?php echo get_hero_paragraph(); ?>',
];

$cards_replacements = [
    // Logo - cards folder (different path)
    'src="../images/Logo.png"' => 'src="../<?php echo get_company_logo(); ?>"',
    'src="../images/loginlogo.png"' => 'src="../<?php echo get_company_logo(); ?>"',
    
    // Company name
    '>EVERGREEN<' => '><?php echo get_company_name(); ?><',
    '>Evergreen Bank<' => '><?php echo get_company_name(); ?><',
    
    // Contact info
    'evrgrn.64@gmail.com' => '<?php echo get_contact_email(); ?>',
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Complete Dynamic Content Converter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #003631;
            font-size: 32px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .alert {
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            border-left: 5px solid;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-color: #17a2b8;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }
        .alert-icon {
            font-size: 24px;
            flex-shrink: 0;
        }
        .progress-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
        }
        .progress-bar {
            width: 100%;
            height: 30px;
            background: #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            margin: 20px 0;
            position: relative;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #003631 0%, #00a896 100%);
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .file-list {
            max-height: 500px;
            overflow-y: auto;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            background: #f8f9fa;
        }
        .file-item {
            padding: 15px;
            margin: 10px 0;
            background: white;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .file-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .file-item.success {
            border-left-color: #28a745;
            background: #f0fff4;
        }
        .file-item.skipped {
            border-left-color: #6c757d;
            background: #f8f9fa;
        }
        .file-item.error {
            border-left-color: #dc3545;
            background: #fff5f5;
        }
        .file-name {
            font-weight: 600;
            color: #003631;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .file-status {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-success {
            background: #28a745;
            color: white;
        }
        .status-skipped {
            background: #6c757d;
            color: white;
        }
        .status-error {
            background: #dc3545;
            color: white;
        }
        .status-notfound {
            background: #ffc107;
            color: #000;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid #e9ecef;
        }
        .summary-card h3 {
            font-size: 36px;
            color: #003631;
            margin-bottom: 10px;
        }
        .summary-card p {
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 15px 35px;
            background: linear-gradient(135deg, #003631 0%, #00a896 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin: 10px 5px;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 54, 49, 0.3);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #F1B24A 0%, #e69610 100%);
        }
        .actions {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        .detail-item {
            padding: 10px;
            font-size: 13px;
            color: #666;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <span style="font-size: 48px;">🔄</span>
            Complete Dynamic Content Converter
        </h1>
        <p class="subtitle">Converting all pages to use dynamic content from database</p>

        <div class="alert alert-warning">
            <span class="alert-icon">⚠️</span>
            <div>
                <strong>Important:</strong> This will modify your PHP files. Make sure you have backups!<br>
                <small>The converter will add content_helper.php includes and replace static content with dynamic function calls.</small>
            </div>
        </div>

<?php
$converted_count = 0;
$skipped_count = 0;
$error_count = 0;
$notfound_count = 0;
$results = [];

foreach ($files_to_convert as $file) {
    $full_path = __DIR__ . '/' . $file;
    $result = [
        'file' => $file,
        'status' => 'unknown',
        'message' => '',
        'details' => []
    ];
    
    if (!file_exists($full_path)) {
        $result['status'] = 'notfound';
        $result['message'] = 'File not found';
        $notfound_count++;
        $results[] = $result;
        continue;
    }
    
    $content = file_get_contents($full_path);
    $original_content = $content;
    $changes_made = false;
    
    // Step 1: Add content_helper include if not present
    $is_card_file = (strpos($file, 'cards/') === 0);
    $include_path = $is_card_file ? '../includes/content_helper.php' : 'includes/content_helper.php';
    $include_check = str_replace('../', '', $include_path);
    
    if (strpos($content, 'content_helper.php') === false) {
        // Find position to insert include
        if (preg_match('/<\?php\s*/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $php_pos = $matches[0][1] + strlen($matches[0][0]);
            
            // Check if there's session_start
            if (preg_match('/session_start\s*\([^)]*\)\s*;/', $content, $session_matches, PREG_OFFSET_CAPTURE, $php_pos)) {
                $insert_pos = $session_matches[0][1] + strlen($session_matches[0][0]);
            } else {
                $insert_pos = $php_pos;
            }
            
            $include_code = "\nrequire_once '$include_path';\n";
            $content = substr_replace($content, $include_code, $insert_pos, 0);
            $changes_made = true;
            $result['details'][] = "Added content_helper.php include";
        }
    } else {
        $result['details'][] = "Already has content_helper.php";
    }
    
    // Step 2: Replace static content with dynamic calls
    $replacements = $is_card_file ? $cards_replacements : $main_replacements;
    $replacement_count = 0;
    
    foreach ($replacements as $search => $replace) {
        $count = 0;
        $content = str_replace($search, $replace, $content, $count);
        if ($count > 0) {
            $replacement_count += $count;
            $changes_made = true;
        }
    }
    
    if ($replacement_count > 0) {
        $result['details'][] = "Replaced $replacement_count static content references";
    }
    
    // Step 3: Save if changes were made
    if ($changes_made) {
        if (file_put_contents($full_path, $content)) {
            $result['status'] = 'success';
            $result['message'] = 'Successfully converted';
            $converted_count++;
        } else {
            $result['status'] = 'error';
            $result['message'] = 'Failed to write file';
            $error_count++;
        }
    } else {
        $result['status'] = 'skipped';
        $result['message'] = 'No changes needed';
        $skipped_count++;
    }
    
    $results[] = $result;
}

$total_files = count($files_to_convert);
$processed_files = $converted_count + $skipped_count + $error_count + $notfound_count;
$progress_percent = ($processed_files / $total_files) * 100;
?>

        <div class="progress-container">
            <h3 style="color: #003631; margin-bottom: 15px;">Conversion Progress</h3>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $progress_percent; ?>%">
                    <?php echo round($progress_percent); ?>%
                </div>
            </div>
            <p style="text-align: center; color: #666; margin-top: 10px;">
                Processed <?php echo $processed_files; ?> of <?php echo $total_files; ?> files
            </p>
        </div>

        <div class="summary">
            <div class="summary-card" style="border-color: #28a745;">
                <h3 style="color: #28a745;">✅ <?php echo $converted_count; ?></h3>
                <p>Converted</p>
            </div>
            <div class="summary-card" style="border-color: #6c757d;">
                <h3 style="color: #6c757d;">⏭️ <?php echo $skipped_count; ?></h3>
                <p>Skipped</p>
            </div>
            <div class="summary-card" style="border-color: #dc3545;">
                <h3 style="color: #dc3545;">❌ <?php echo $error_count; ?></h3>
                <p>Errors</p>
            </div>
            <div class="summary-card" style="border-color: #ffc107;">
                <h3 style="color: #ffc107;">🔍 <?php echo $notfound_count; ?></h3>
                <p>Not Found</p>
            </div>
        </div>

        <h2 style="color: #003631; margin: 30px 0 20px;">📋 Detailed Results</h2>
        <div class="file-list">
            <?php foreach ($results as $result): ?>
                <div class="file-item <?php echo $result['status']; ?>">
                    <div class="file-name">
                        <span style="font-size: 20px;">
                            <?php 
                            echo $result['status'] === 'success' ? '✅' : 
                                ($result['status'] === 'skipped' ? '⏭️' : 
                                ($result['status'] === 'error' ? '❌' : '🔍'));
                            ?>
                        </span>
                        <div>
                            <div><?php echo htmlspecialchars($result['file']); ?></div>
                            <?php if (!empty($result['details'])): ?>
                                <?php foreach ($result['details'] as $detail): ?>
                                    <div class="detail-item"><?php echo htmlspecialchars($detail); ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="file-status status-<?php echo $result['status']; ?>">
                        <?php echo $result['message']; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($converted_count > 0): ?>
            <div class="alert alert-success">
                <span class="alert-icon">🎉</span>
                <div>
                    <strong>Success!</strong> <?php echo $converted_count; ?> file(s) have been converted to use dynamic content.<br>
                    <small>All changes from the admin panel will now reflect immediately on these pages.</small>
                </div>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <span class="alert-icon">📝</span>
            <div>
                <strong>Next Steps:</strong><br>
                1. Test your pages to ensure they load correctly<br>
                2. Go to Admin Panel → Content Management<br>
                3. Update logo, company name, or any content<br>
                4. Visit user pages to see changes reflected immediately<br>
                5. Clear browser cache (Ctrl+F5) if needed
            </div>
        </div>

        <div class="actions">
            <a href="index.php" class="btn">🏠 Test Homepage</a>
            <a href="cards/debit.php" class="btn btn-secondary">💳 Test Card Page</a>
            <a href="admin_dashboard.php?page=content" class="btn">⚙️ Manage Content</a>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 12px; text-align: center;">
            <p style="color: #666; font-size: 14px;">
                <strong>System Status:</strong> ✅ Dynamic Content System Active<br>
                <strong>Files Processed:</strong> <?php echo $total_files; ?> files<br>
                <strong>Conversion Date:</strong> <?php echo date('F d, Y H:i:s'); ?>
            </p>
        </div>
    </div>
</body>
</html>
