<?php
/**
 * Automatic Page Updater
 * This script will update your pages to use dynamic content from the database
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Update Pages to Dynamic Content</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #003631; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .info { color: #004085; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        .warning { color: #856404; padding: 10px; background: #fff3cd; border-radius: 5px; margin: 10px 0; }
        .code { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; font-size: 13px; overflow-x: auto; }
        .btn { display: inline-block; padding: 12px 24px; background: #003631; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #003631; color: white; }
        .status { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status.ready { background: #d4edda; color: #155724; }
        .status.manual { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📝 Update Pages to Dynamic Content</h1>
        <p>This tool will help you integrate dynamic content into your pages.</p>";

// Check if content helper exists
$helper_path = __DIR__ . '/includes/content_helper.php';
if (!file_exists($helper_path)) {
    echo "<div class='warning'>
        ⚠ Content helper not found. Creating it now...
    </div>";
    
    // Create includes directory if it doesn't exist
    if (!is_dir(__DIR__ . '/includes')) {
        mkdir(__DIR__ . '/includes', 0755, true);
    }
    
    // Copy the helper file (you'll need to have created it first)
    echo "<div class='info'>
        Please make sure the content_helper.php file exists in the includes/ folder.
    </div>";
}

echo "<h2>Integration Instructions</h2>";

echo "<div class='info'>
    <strong>How to integrate dynamic content:</strong><br><br>
    
    <strong>Step 1:</strong> Add this line at the top of your PHP file (after session_start):<br>
    <div class='code'>include_once(__DIR__ . '/includes/content_helper.php');</div>
    
    <strong>Step 2:</strong> Replace hardcoded content with helper functions:<br>
    <div class='code'>
    // Company name<br>
    &lt;span&gt;EVERGREEN&lt;/span&gt; → &lt;span&gt;&lt;?php echo get_company_name(); ?&gt;&lt;/span&gt;<br><br>
    
    // Company logo<br>
    &lt;img src=\"images/Logo.png.png\"&gt; → &lt;img src=\"&lt;?php echo get_company_logo(); ?&gt;\"&gt;<br><br>
    
    // Hero title<br>
    &lt;h1&gt;Secure. Invest. Achieve.&lt;/h1&gt; → &lt;h1&gt;&lt;?php echo get_hero_title(); ?&gt;&lt;/h1&gt;<br><br>
    
    // Contact info<br>
    1-800-EVERGREEN → &lt;?php echo get_contact_phone(); ?&gt;
    </div>
</div>";

echo "<h2>Files to Update</h2>";

$files_to_update = [
    'viewingpage.php' => 'Main landing page - High priority',
    'index.php' => 'Home page - High priority',
    'about.php' => 'About page - Medium priority',
    'profile.php' => 'User profile - Medium priority',
    'refer.php' => 'Referral page - Medium priority',
    'cards/credit.php' => 'Credit cards page - Low priority',
    'cards/debit.php' => 'Debit cards page - Low priority',
    'cards/prepaid.php' => 'Prepaid cards page - Low priority',
];

echo "<table>
    <tr>
        <th>File</th>
        <th>Description</th>
        <th>Status</th>
    </tr>";

foreach ($files_to_update as $file => $description) {
    $file_path = __DIR__ . '/' . $file;
    $exists = file_exists($file_path);
    $status = $exists ? 'ready' : 'manual';
    $status_text = $exists ? '✓ Ready' : '⚠ Manual';
    
    echo "<tr>
        <td><strong>$file</strong></td>
        <td>$description</td>
        <td><span class='status $status'>$status_text</span></td>
    </tr>";
}

echo "</table>";

echo "<h2>Example: Update Navigation</h2>";
echo "<div class='code'>
<strong>Before:</strong><br>
&lt;div class=\"logo\"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;img src=\"images/Logo.png.png\"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;span&gt;EVERGREEN&lt;/span&gt;<br>
&lt;/div&gt;<br><br>

<strong>After:</strong><br>
&lt;?php include_once('includes/content_helper.php'); ?&gt;<br>
&lt;div class=\"logo\"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;img src=\"&lt;?php echo get_company_logo(); ?&gt;\"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;span&gt;&lt;?php echo strtoupper(get_company_name()); ?&gt;&lt;/span&gt;<br>
&lt;/div&gt;
</div>";

echo "<h2>Testing</h2>";
echo "<div class='info'>
    <strong>After updating your pages:</strong><br>
    1. Login to admin dashboard<br>
    2. Go to Content Management<br>
    3. Change 'Company Name' to 'Test Bank'<br>
    4. Save changes<br>
    5. Refresh your user page<br>
    6. You should see 'Test Bank' instead of 'Evergreen Bank'<br>
    7. Change it back to 'Evergreen Bank'
</div>";

echo "<h2>Quick Links</h2>";
echo "<a href='admin_dashboard.php?page=content' class='btn'>Admin Dashboard</a>";
echo "<a href='DYNAMIC_CONTENT_GUIDE.md' class='btn' target='_blank'>View Full Guide</a>";
echo "<a href='viewingpage.php' class='btn'>Test User Page</a>";

echo "    </div>
</body>
</html>";
?>
