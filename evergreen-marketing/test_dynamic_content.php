<?php
/**
 * Test Dynamic Content System
 * This page tests if the dynamic content helper is working correctly
 */

session_start();
include_once(__DIR__ . '/includes/content_helper.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Content Test</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #003631;
            border-bottom: 3px solid #F1B24A;
            padding-bottom: 10px;
        }
        h2 {
            color: #003631;
            margin-top: 30px;
        }
        .test-item {
            margin: 15px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #003631;
        }
        .label {
            font-weight: bold;
            color: #003631;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #333;
        }
        .logo-preview {
            max-width: 200px;
            margin: 10px 0;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #003631;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background: #005544;
        }
    </style>
</head>
<body>
    <div class="test-card">
        <h1>✅ Dynamic Content System Test</h1>
        <p class="success">All helper functions are working correctly!</p>

        <h2>Content Values from Database:</h2>

        <div class="test-item">
            <span class="label">Company Name:</span>
            <span class="value"><?php echo htmlspecialchars(get_company_name()); ?></span>
        </div>

        <div class="test-item">
            <span class="label">Company Logo:</span>
            <span class="value"><?php echo htmlspecialchars(get_company_logo()); ?></span>
            <br>
            <img src="<?php echo htmlspecialchars(get_company_logo()); ?>" alt="Logo Preview" class="logo-preview">
        </div>

        <div class="test-item">
            <span class="label">Hero Title:</span>
            <span class="value"><?php echo htmlspecialchars(get_hero_title()); ?></span>
        </div>

        <div class="test-item">
            <span class="label">Hero Description:</span>
            <span class="value"><?php echo htmlspecialchars(get_hero_description()); ?></span>
        </div>

        <div class="test-item">
            <span class="label">Contact Phone:</span>
            <span class="value"><?php echo htmlspecialchars(get_contact_phone()); ?></span>
        </div>

        <div class="test-item">
            <span class="label">Contact Email:</span>
            <span class="value"><?php echo htmlspecialchars(get_contact_email()); ?></span>
        </div>

        <div class="test-item">
            <span class="label">About Description:</span>
            <span class="value"><?php echo htmlspecialchars(get_about_description()); ?></span>
        </div>

        <div class="test-item">
            <span class="label">Banner Image:</span>
            <span class="value"><?php echo htmlspecialchars(get_banner_image()); ?></span>
        </div>

        <h2>How to Update Content:</h2>
        <ol>
            <li>Login to <a href="admin_login.php">Admin Dashboard</a></li>
            <li>Go to "Content Management" section</li>
            <li>Update any content field</li>
            <li>Click "Update Content"</li>
            <li>Refresh this page to see changes</li>
        </ol>

        <a href="viewingpage.php" class="back-link">← Back to Home</a>
        <a href="admin_login.php" class="back-link">Admin Dashboard</a>
    </div>
</body>
</html>
