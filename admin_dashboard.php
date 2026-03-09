<?php
session_start();

// Prevent caching - force browser to always get fresh content
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Dashboard - Evergreen Bank</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #003631 0%, #002a26 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-header {
            padding: 35px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #F1B24A 0%, #e69610 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(241, 178, 74, 0.3);
        }

        .sidebar-header h2 {
            font-size: 22px;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(241, 178, 74, 0.15);
            color: #F1B24A;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .sidebar-header p {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            margin-top: 5px;
        }

        .sidebar-menu {
            padding: 25px 0;
        }

        .menu-section-title {
            padding: 0 25px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
        }

        .menu-item {
            padding: 14px 25px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border-left: 3px solid transparent;
            position: relative;
            margin: 4px 0;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, rgba(241, 178, 74, 0.2) 0%, transparent 100%);
            transition: width 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            padding-left: 30px;
        }

        .menu-item:hover::before {
            width: 100%;
        }

        .menu-item.active {
            background: linear-gradient(90deg, rgba(241, 178, 74, 0.25) 0%, rgba(241, 178, 74, 0.05) 100%);
            color: #F1B24A;
            border-left-color: #F1B24A;
            font-weight: 600;
            box-shadow: inset 0 0 20px rgba(241, 178, 74, 0.1);
        }

        .menu-item.active .menu-icon {
            transform: scale(1.1);
        }

        .menu-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .menu-text {
            font-size: 14px;
            font-weight: 500;
        }

        .logout-section {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 25px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        .logout-btn {
            width: 100%;
            padding: 14px;
            background: rgba(220, 53, 69, 0.15);
            color: #ff6b7a;
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.25);
            border-color: rgba(220, 53, 69, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 35px 40px;
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 20px 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .page-title-section h1 {
            color: #003631;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7f7d;
        }

        .breadcrumb i {
            font-size: 10px;
        }

        .top-bar-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .time-display {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            color: #003631;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #e0e0e0;
        }

        .admin-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #003631 0%, #005a50 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 54, 49, 0.2);
            border: 3px solid white;
        }

        .content-section {
            display: none;
            animation: fadeInUp 0.4s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success/Error Messages */
        .message {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.4s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .message i {
            font-size: 20px;
        }

        .message.success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #003631;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-content {
                padding: 25px 30px;
            }

            .top-bar {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .top-bar-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 80px 20px 20px;
            }

            .logout-section {
                position: relative;
            }

            .time-display {
                display: none;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #003631;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-section">
                    <div class="logo-icon">
                        <img src="images/Logo.png" alt="Evergreen Bank Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div>
                        <h2>Evergreen Bank</h2>
                    </div>
                </div>
                <div class="admin-badge">
                    <i class="fas fa-crown"></i>
                    <span><?php echo htmlspecialchars($admin_name); ?></span>
                </div>
                <p>Administrator Panel</p>
            </div>

            <div class="sidebar-menu">
                <div class="menu-section-title">Main Menu</div>
                <a href="?page=content" class="menu-item <?php echo $current_page === 'content' ? 'active' : ''; ?>">
                    <i class="fas fa-edit menu-icon"></i>
                    <span class="menu-text">Content Management</span>
                </a>
                <a href="?page=applications" class="menu-item <?php echo $current_page === 'applications' ? 'active' : ''; ?>">
                    <i class="fas fa-credit-card menu-icon"></i>
                    <span class="menu-text">Card Applications</span>
                </a>
                <a href="?page=ads" class="menu-item <?php echo $current_page === 'ads' ? 'active' : ''; ?>">
                    <i class="fas fa-bullhorn menu-icon"></i>
                    <span class="menu-text">Ads Management</span>
                </a>
            </div>

            <div class="logout-section">
                <form action="admin_logout.php" method="POST">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title-section">
                    <h1>
                        <?php 
                        if ($current_page === 'content') {
                            echo 'Content Management';
                        } elseif ($current_page === 'applications') {
                            echo 'Card Applications';
                        } elseif ($current_page === 'ads') {
                            echo 'Ads Management';
                        } else {
                            echo 'Dashboard';
                        }
                        ?>
                    </h1>
                    <div class="breadcrumb">
                        <span>Admin</span>
                        <i class="fas fa-chevron-right"></i>
                        <span><?php echo ucfirst($current_page); ?></span>
                    </div>
                </div>
                <div class="top-bar-actions">
                    <div class="time-display">
                        <i class="far fa-clock"></i>
                        <span id="currentTime"></span>
                    </div>
                    <div class="admin-avatar" title="<?php 
                        $current_day_full = date('l');
                        echo $current_day_full; 
                    ?>" id="dayAvatar">
                        <?php 
                            // Ensure timezone is set
                            date_default_timezone_set('Asia/Manila');
                            
                            // Get current day name
                            $current_day_full = date('l');
                            $first_letter = strtoupper(substr($current_day_full, 0, 1));
                            
                            echo $first_letter; 
                        ?>
                    </div>
                </div>
            </div>

            <!-- Content Management Section -->
            <div class="content-section <?php echo $current_page === 'content' ? 'active' : ''; ?>">
                <?php include('admin_content_management.php'); ?>
            </div>

            <!-- Card Applications Section -->
            <div class="content-section <?php echo $current_page === 'applications' ? 'active' : ''; ?>">
                <?php include('admin_card_applications.php'); ?>
            </div>

            <!-- Ads Management Section -->
            <div class="content-section <?php echo $current_page === 'ads' ? 'active' : ''; ?>">
                <?php include('admin_ads_management.php'); ?>
            </div>
        </div>
    </div>

    <script>
        // Update time display
        function updateTime() {
            const now = new Date();
            const options = { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            };
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', options);

            // Dynamic Avatar Update: Ensures the letter changes at midnight without refresh
            const dayNames = ["S", "M", "T", "W", "T", "F", "S"];
            const avatarElement = document.getElementById('dayAvatar');
            if (avatarElement) {
                avatarElement.textContent = dayNames[now.getDay()];
            }
        }
        
        updateTime();
        setInterval(updateTime, 1000);

        // Mobile menu toggle
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('mobile-open');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
            }
        });

        // Smooth page transitions
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.href) {
                    e.preventDefault();
                    document.getElementById('loadingOverlay').style.display = 'flex';
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 300);
                }
            });
        });

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.message');
            messages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateX(20px)';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
