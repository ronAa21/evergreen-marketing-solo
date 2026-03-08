<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Administrator';
$current_page = $_GET['page'] ?? 'content';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Evergreen Bank</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #003631;
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .menu-item.active {
            background: rgba(241,178,74,0.2);
            color: #F1B24A;
            border-left-color: #F1B24A;
        }

        .menu-icon {
            font-size: 20px;
        }

        .logout-section {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .logout-btn {
            width: 100%;
            padding: 12px;
            background: rgba(255,255,255,0.1);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .content-header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .content-header h1 {
            color: #003631;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .content-header p {
            color: #666;
            font-size: 14px;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        /* Success/Error Messages */
        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .logout-section {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Dashboard</h2>
                <p><?php echo htmlspecialchars($admin_name); ?></p>
            </div>

            <div class="sidebar-menu">
                <a href="?page=content" class="menu-item <?php echo $current_page === 'content' ? 'active' : ''; ?>">
                    <span class="menu-icon">📝</span>
                    <span>Content Management</span>
                </a>
                <a href="?page=applications" class="menu-item <?php echo $current_page === 'applications' ? 'active' : ''; ?>">
                    <span class="menu-icon">💳</span>
                    <span>Card Applications</span>
                </a>
            </div>

            <div class="logout-section">
                <form action="admin_logout.php" method="POST">
                    <button type="submit" class="logout-btn">🚪 Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Content Management Section -->
            <div class="content-section <?php echo $current_page === 'content' ? 'active' : ''; ?>">
                <?php include('admin_content_management.php'); ?>
            </div>

            <!-- Card Applications Section -->
            <div class="content-section <?php echo $current_page === 'applications' ? 'active' : ''; ?>">
                <?php include('admin_card_applications.php'); ?>
            </div>
        </div>
    </div>
</body>
</html>
