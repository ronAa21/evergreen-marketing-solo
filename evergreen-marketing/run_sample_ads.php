<?php
/**
 * Sample Ads Insertion Script
 * This script inserts 20 sample advertisements into the database
 */

// Include database connection
include('db_connect.php');

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Sample Advertisements</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 800px;
            width: 100%;
        }

        h1 {
            color: #003631;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .status-box {
            background: #f8f9fa;
            border-left: 4px solid #003631;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            border-left-color: #28a745;
            background: #d4edda;
            color: #155724;
        }

        .error {
            border-left-color: #dc3545;
            background: #f8d7da;
            color: #721c24;
        }

        .warning {
            border-left-color: #ffc107;
            background: #fff3cd;
            color: #856404;
        }

        .info {
            border-left-color: #17a2b8;
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .message {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .details {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .detail-item {
            padding: 8px 0;
            display: flex;
            justify-content: space-between;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .detail-value {
            color: #6c757d;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #003631;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn:hover {
            background: #005a50;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 54, 49, 0.3);
        }

        .ad-list {
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .ad-item {
            padding: 12px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }

        .ad-title {
            font-weight: 600;
            color: #003631;
            margin-bottom: 5px;
        }

        .ad-desc {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.5;
        }

        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📢 Sample Advertisements Insertion</h1>
        <p class="subtitle">Adding 20 sample advertisements to the database</p>

        <?php
        // Read the SQL file
        $sqlFile = 'sql/insert_sample_ads.sql';
        
        if (!file_exists($sqlFile)) {
            echo '<div class="status-box error">';
            echo '<div class="message"><span class="status-icon">❌</span> SQL file not found!</div>';
            echo '<p>The file <code>' . $sqlFile . '</code> does not exist.</p>';
            echo '</div>';
            exit;
        }

        $sql = file_get_contents($sqlFile);
        
        // Remove comments and split by semicolon
        $sql = preg_replace('/--.*$/m', '', $sql);
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Execute queries
        foreach ($queries as $query) {
            if (empty($query)) continue;
            
            if ($conn->query($query)) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = $conn->error;
            }
        }

        // Display results
        if ($errorCount === 0) {
            echo '<div class="status-box success">';
            echo '<div class="message"><span class="status-icon">✅</span> Sample Ads Inserted Successfully!</div>';
            echo '<div class="details">';
            echo '<div class="detail-item">';
            echo '<span class="detail-label">Total Ads Inserted:</span>';
            echo '<span class="detail-value">' . $successCount . ' advertisements</span>';
            echo '</div>';
            echo '<div class="detail-item">';
            echo '<span class="detail-label">Status:</span>';
            echo '<span class="detail-value">All active and ready to display</span>';
            echo '</div>';
            echo '<div class="detail-item">';
            echo '<span class="detail-label">Date Range:</span>';
            echo '<span class="detail-value">Last 15 days (for testing "New" badge)</span>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Fetch and display the inserted ads
            $result = $conn->query("SELECT title, description, created_at FROM advertisements ORDER BY created_at DESC LIMIT 20");
            
            if ($result && $result->num_rows > 0) {
                echo '<div class="status-box info">';
                echo '<div class="message"><span class="status-icon">📋</span> Inserted Advertisements Preview</div>';
                echo '<div class="ad-list">';
                
                while ($row = $result->fetch_assoc()) {
                    $isNew = (time() - strtotime($row['created_at'])) < (7 * 24 * 60 * 60);
                    echo '<div class="ad-item">';
                    echo '<div class="ad-title">' . htmlspecialchars($row['title']);
                    if ($isNew) {
                        echo ' <span style="background: #F1B24A; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 700;">NEW</span>';
                    }
                    echo '</div>';
                    echo '<div class="ad-desc">' . htmlspecialchars(substr($row['description'], 0, 150)) . '...</div>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }

            echo '<div class="status-box warning">';
            echo '<div class="message"><span class="status-icon">⚠️</span> Important Note</div>';
            echo '<p>The sample ads use placeholder image paths (<code>uploads/ads/sample1.jpg</code>, etc.). ';
            echo 'These images don\'t exist yet. You can:</p>';
            echo '<ul style="margin: 10px 0 0 20px; line-height: 1.8;">';
            echo '<li>Upload actual images through the admin panel</li>';
            echo '<li>Edit the ads to use real image paths</li>';
            echo '<li>Delete these samples and create real ads</li>';
            echo '</ul>';
            echo '</div>';

        } else {
            echo '<div class="status-box error">';
            echo '<div class="message"><span class="status-icon">❌</span> Some Errors Occurred</div>';
            echo '<div class="details">';
            echo '<div class="detail-item">';
            echo '<span class="detail-label">Successful:</span>';
            echo '<span class="detail-value">' . $successCount . '</span>';
            echo '</div>';
            echo '<div class="detail-item">';
            echo '<span class="detail-label">Failed:</span>';
            echo '<span class="detail-value">' . $errorCount . '</span>';
            echo '</div>';
            echo '</div>';
            
            if (!empty($errors)) {
                echo '<div style="margin-top: 15px;">';
                echo '<strong>Errors:</strong>';
                echo '<ul style="margin: 10px 0 0 20px;">';
                foreach ($errors as $error) {
                    echo '<li style="color: #721c24; margin: 5px 0;">' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }
            echo '</div>';
        }

        $conn->close();
        ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="Content-view/ads-view.php" class="btn">📢 View Advertisements Page</a>
            <a href="admin_ads_management.php" class="btn" style="background: #F1B24A; color: #003631;">⚙️ Manage Ads (Admin)</a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e9ecef; text-align: center; color: #6c757d; font-size: 14px;">
            <p>💡 <strong>Tip:</strong> You can run this script multiple times. It will insert duplicate ads each time.</p>
            <p style="margin-top: 10px;">To clear all ads, run: <code>DELETE FROM advertisements;</code> in your database.</p>
        </div>
    </div>
</body>
</html>
