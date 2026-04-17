<?php
/**
 * Logo Status Checker
 * Check if logo files exist and show their details
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logo Status Checker</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
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
        .logo-check {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #003631;
        }
        .logo-preview {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 10px 0;
            text-align: center;
            border: 2px solid #e0e0e0;
        }
        .logo-preview img {
            max-width: 300px;
            max-height: 150px;
            border: 1px solid #ddd;
            padding: 10px;
            background: white;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .status.exists {
            background: #d4edda;
            color: #155724;
        }
        .status.missing {
            background: #f8d7da;
            color: #721c24;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 10px;
            margin: 15px 0;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .instructions {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .instructions h3 {
            margin-top: 0;
            color: #856404;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
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
        .btn.secondary {
            background: #F1B24A;
            color: #003631;
        }
        .btn.secondary:hover {
            background: #e69610;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Logo Status Checker</h1>
        
        <?php
        $logo_files = [
            'Main Logo' => 'images/Logo.png',
            'Login Logo' => 'images/loginlogo.png',
            'Icon' => 'images/icon.png',
        ];
        
        foreach ($logo_files as $name => $path) {
            $full_path = __DIR__ . '/' . $path;
            $exists = file_exists($full_path);
            
            echo "<div class='logo-check'>";
            echo "<h3>$name</h3>";
            
            if ($exists) {
                echo "<span class='status exists'>✓ EXISTS</span>";
                
                $file_info = [
                    'Path' => $path,
                    'Full Path' => $full_path,
                    'Size' => number_format(filesize($full_path) / 1024, 2) . ' KB',
                    'Last Modified' => date('F d, Y H:i:s', filemtime($full_path)),
                    'Dimensions' => 'Loading...',
                ];
                
                // Get image dimensions
                $image_info = @getimagesize($full_path);
                if ($image_info) {
                    $file_info['Dimensions'] = $image_info[0] . ' x ' . $image_info[1] . ' pixels';
                    $file_info['Type'] = $image_info['mime'];
                }
                
                echo "<div class='info-grid'>";
                foreach ($file_info as $label => $value) {
                    echo "<div class='info-label'>$label:</div>";
                    echo "<div>$value</div>";
                }
                echo "</div>";
                
                // Show preview
                echo "<div class='logo-preview'>";
                echo "<h4>Current Logo Preview:</h4>";
                echo "<img src='$path?v=" . time() . "' alt='$name'>";
                echo "</div>";
                
            } else {
                echo "<span class='status missing'>✗ MISSING</span>";
                echo "<p style='color: #721c24; margin-top: 10px;'>⚠️ File not found at: <code>$full_path</code></p>";
            }
            
            echo "</div>";
        }
        ?>
        
        <div class="instructions">
            <h3>📝 How to Update Your Logo</h3>
            <ol>
                <li><strong>Prepare your new logo:</strong>
                    <ul>
                        <li>Format: PNG with transparent background</li>
                        <li>Recommended size: 200x60 pixels</li>
                        <li>File name: <code>Logo.png</code></li>
                    </ul>
                </li>
                <li><strong>Backup the old logo:</strong>
                    <ul>
                        <li>Rename current <code>Logo.png</code> to <code>Logo_old.png</code></li>
                    </ul>
                </li>
                <li><strong>Upload new logo:</strong>
                    <ul>
                        <li>Upload your new logo as <code>Logo.png</code> to <code>evergreen-marketing/images/</code></li>
                    </ul>
                </li>
                <li><strong>Clear cache:</strong>
                    <ul>
                        <li>Press <code>Ctrl + F5</code> in your browser</li>
                        <li>Or use incognito/private mode to test</li>
                    </ul>
                </li>
            </ol>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="btn">🏠 Go to Homepage</a>
            <a href="?refresh=1" class="btn secondary">🔄 Refresh Status</a>
            <a href="cards/debit.php" class="btn">💳 Test Card Page</a>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #e9ecef; border-radius: 8px;">
            <h4>🔍 Quick Diagnosis:</h4>
            <p><strong>If logo appears in this checker but not on your pages:</strong></p>
            <ul>
                <li>✓ Clear browser cache (Ctrl + F5)</li>
                <li>✓ Check browser console for errors (F12)</li>
                <li>✓ Try incognito/private browsing mode</li>
                <li>✓ Verify file permissions (should be 644)</li>
            </ul>
            
            <p><strong>If you want to use a different logo file:</strong></p>
            <ul>
                <li>✓ Simply replace the <code>Logo.png</code> file in <code>images/</code> folder</li>
                <li>✓ Keep the same filename to avoid code changes</li>
                <li>✓ Make sure it's PNG format with transparency</li>
            </ul>
        </div>
    </div>
</body>
</html>
