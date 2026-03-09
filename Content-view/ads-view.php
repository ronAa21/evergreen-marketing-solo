<?php
// Ads View Page - Display advertisements for public viewing
session_start();
include('../db_connect.php');

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['email']);
$fullName = '';
if ($isLoggedIn) {
    $fullName = $_SESSION['full_name'] ?? (trim(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? '')));
}

// Include content helper for dynamic content
include_once('../includes/content_helper.php');

// Fetch active advertisements
$sql = "SELECT * FROM advertisements WHERE status = 'active' ORDER BY created_at DESC";
$result = $conn->query($sql);

$ads = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ads[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisements - Evergreen Bank</title>
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
            padding-top: 80px;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: #003631;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: transparent;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            overflow: hidden;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
            border-radius: 50%;
            background: transparent;
        }

        .logo a {
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #F1B24A;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login {
            background: transparent;
            color: #FFFFFF;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .btn-login:hover {
            background: rgba(255,255,255,0.1);
            color: #F1B24A;
        }

        .btn-primary {
            background: #F1B24A;
            color: #003631;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: #e69610;
        }

        .username-profile {
            background: transparent;
            color: #FFFFFF;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .username-profile:hover {
            color: #F1B24A;
        }

        .profile-btn {
            width: 40px;
            height: 40px;
            background: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            background-color: #003631;
            display: block;
        }

        .profile-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #D9D9D9;
            color: #003631;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            min-width: 160px;
            z-index: 200;
        }

        .profile-dropdown a {
            display: block;
            padding: 0.65rem 1rem;
            color: #003631;
            text-decoration: none;
            font-weight: 600;
        }

        .profile-dropdown a:hover {
            background: rgba(0,0,0,0.04);
        }

        .profile-dropdown.show {
            display: block;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropbtn {
            background: none;
            border: none;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
        }

        .dropbtn:hover {
            color: #F1B24A;
        }

        .dropdown-content {
            display: none;
            position: fixed;
            left: 0;
            top: 70px;
            width: 100vw;
            background-color: #D9D9D9;
            padding: 1.5rem 5%;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            z-index: 99;
            text-align: center;
        }

        .dropdown-content a {
            color: #003631;
            margin: 0 2rem;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-content a:hover {
            color: #F1B24A;
            transform: translateY(-2px);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-header h1 {
            color: #003631;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .page-header p {
            color: #6c757d;
            font-size: 18px;
        }

        .ads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .ad-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .ad-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 54, 49, 0.15);
        }

        .ad-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .ad-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .ad-card:hover .ad-image img {
            transform: scale(1.05);
        }

        .ad-content {
            padding: 25px;
        }

        .ad-content h3 {
            color: #003631;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .ad-content p {
            color: #6c757d;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .ad-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }

        .ad-date {
            color: #adb5bd;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .learn-more-btn {
            background: linear-gradient(135deg, #003631 0%, #005a50 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .learn-more-btn:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 54, 49, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 80px;
            color: #dee2e6;
            margin-bottom: 25px;
        }

        .empty-state h3 {
            color: #003631;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #6c757d;
            font-size: 16px;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #003631;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 30px;
            padding: 10px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                padding: 1rem 3%;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .logo {
                font-size: 1.1rem;
            }

            .logo-icon {
                width: 45px;
                height: 45px;
            }

            .nav-links {
                order: 3;
                width: 100%;
                justify-content: center;
                gap: 0.8rem;
                flex-wrap: wrap;
            }

            .nav-links a {
                font-size: 0.9rem;
            }

            .dropdown-content {
                top: 120px;
                padding: 1rem 2%;
            }

            .dropdown-content a {
                margin: 0.3rem 0.5rem;
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }

            .nav-buttons {
                gap: 0.5rem;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .ads-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .ad-image {
                height: 200px;
            }

            .ad-content {
                padding: 20px;
            }

            .ad-content h3 {
                font-size: 18px;
            }

            .ad-content p {
                font-size: 14px;
            }
        }

        /* Loading Animation */
        .loading {
            text-align: center;
            padding: 60px 20px;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #003631;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Badge */
        .new-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #F1B24A 0%, #e69610 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(241, 178, 74, 0.4);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 54, 49, 0.9);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 900px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
            overflow: hidden;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            position: relative;
            padding: 0;
        }

        .modal-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #003631;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .modal-close:hover {
            background: #F1B24A;
            color: white;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 40px;
        }

        .modal-title {
            color: #003631;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .modal-meta {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .modal-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 14px;
        }

        .modal-meta-item i {
            color: #F1B24A;
            font-size: 16px;
        }

        .modal-description {
            color: #495057;
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .modal-footer {
            padding: 25px 40px;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-share {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .modal-share span {
            color: #6c757d;
            font-weight: 600;
            margin-right: 10px;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .share-btn:hover {
            background: #003631;
            border-color: #003631;
            color: white;
            transform: translateY(-3px);
        }

        .modal-action-btn {
            background: linear-gradient(135deg, #003631 0%, #005a50 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 54, 49, 0.3);
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .modal-image {
                height: 250px;
            }

            .modal-body {
                padding: 25px;
            }

            .modal-title {
                font-size: 24px;
            }

            .modal-description {
                font-size: 16px;
            }

            .modal-footer {
                flex-direction: column;
                gap: 20px;
                padding: 20px 25px;
            }

            .modal-share {
                width: 100%;
                justify-content: center;
            }

            .modal-action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <div class="logo-icon">
                <a href="<?php echo $isLoggedIn ? '../viewingpage.php' : '../viewing.php'; ?>">
                    <img src="../<?php echo htmlspecialchars(get_company_logo()); ?>">
                </a>
            </div>
            <span>
                <a href="<?php echo $isLoggedIn ? '../viewingpage.php' : '../viewing.php'; ?>">
                    <?php echo htmlspecialchars(strtoupper(get_company_name())); ?>
                </a>
            </span>
        </div>

        <div class="nav-links">
            <a href="<?php echo $isLoggedIn ? '../viewingpage.php' : '../viewing.php'; ?>">Home</a>

            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Cards ⏷</button>
                <div class="dropdown-content" id="cardsDropdown">
                    <a href="../cards/credit<?php echo $isLoggedIn ? '' : 'no'; ?>.php">Credit Cards</a>
                    <a href="../cards/debit<?php echo $isLoggedIn ? '' : 'no'; ?>.php">Debit Cards</a>
                    <a href="../cards/prepaid<?php echo $isLoggedIn ? '' : 'no'; ?>.php">Prepaid Cards</a>
                    <a href="../cards/rewards.php">Card Rewards</a>
                </div>
            </div>

            <a href="ads-view.php" style="color: #F1B24A;">What's new</a>
            <a href="../about<?php echo $isLoggedIn ? '' : 'no'; ?>.php">About Us</a>
        </div>

        <div class="nav-buttons">
            <?php if ($isLoggedIn): ?>
                <a href="#" class="username-profile"><?php echo htmlspecialchars($fullName); ?></a>
                <div class="profile-actions">
                    <div class="logo-icon" style="width:40px;height:40px;">
                        <button id="profileBtn" class="profile-btn" aria-haspopup="true" aria-expanded="false" onclick="toggleProfileDropdown(event)" title="Open profile menu">
                            <img src="../images/pfp.png" alt="Profile Icon">
                        </button>
                    </div>
                    <div id="profileDropdown" class="profile-dropdown" role="menu" aria-labelledby="profileBtn">
                        <a href="../profile.php" role="menuitem">Profile</a>
                        <a href="../cards/points.php" role="menuitem">Missions</a>
                        <a href="../logout.php" role="menuitem">Sign Out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../login.php" class="btn btn-login">Login</a>
                <a href="../signup.php" class="btn btn-primary">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>
                <i class="fas fa-bullhorn"></i>
                Our Latest Advertisements
            </h1>
            <p>Discover our latest offers, promotions, and announcements</p>
        </div>

        <?php if (count($ads) > 0): ?>
            <div class="ads-grid">
                <?php foreach ($ads as $ad): 
                    // Check if ad is new (created within last 7 days)
                    $created_date = strtotime($ad['created_at']);
                    $is_new = (time() - $created_date) < (7 * 24 * 60 * 60);
                ?>
                    <div class="ad-card">
                        <div class="ad-image">
                            <?php if ($is_new): ?>
                                <span class="new-badge">New</span>
                            <?php endif; ?>
                            <?php 
                            // Check if image path is external URL or local path
                            $imagePath = $ad['image_path'];
                            $imageUrl = (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) 
                                ? $imagePath 
                                : '../' . $imagePath;
                            ?>
                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                                 alt="<?php echo htmlspecialchars($ad['title']); ?>">
                        </div>
                        <div class="ad-content">
                            <h3><?php echo htmlspecialchars($ad['title']); ?></h3>
                            <p><?php echo htmlspecialchars($ad['description']); ?></p>
                            <div class="ad-footer">
                                <span class="ad-date">
                                    <i class="far fa-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($ad['created_at'])); ?>
                                </span>
                                <a href="#" class="learn-more-btn" onclick="openAdModal(<?php echo $ad['id']; ?>, '<?php echo addslashes($ad['title']); ?>', '<?php echo addslashes($ad['description']); ?>', '<?php echo addslashes($ad['image_path']); ?>', '<?php echo date('M d, Y', strtotime($ad['created_at'])); ?>'); return false;">
                                    Learn More
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Advertisements Available</h3>
                <p>Check back soon for exciting offers and promotions!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Ad Details Modal -->
    <div id="adModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <img id="modalImage" class="modal-image" src="" alt="Advertisement">
                <button class="modal-close" onclick="closeAdModal()">&times;</button>
            </div>
            <div class="modal-body">
                <h2 id="modalTitle" class="modal-title"></h2>
                <div class="modal-meta">
                    <div class="modal-meta-item">
                        <i class="far fa-calendar"></i>
                        <span id="modalDate"></span>
                    </div>
                    <div class="modal-meta-item">
                        <i class="fas fa-tag"></i>
                        <span>Promotion</span>
                    </div>
                </div>
                <div id="modalDescription" class="modal-description"></div>
            </div>
            <div class="modal-footer">
                <div class="modal-share">
                    <span>Share:</span>
                    <button class="share-btn" onclick="shareAd('facebook')" title="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="share-btn" onclick="shareAd('twitter')" title="Share on Twitter">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="share-btn" onclick="shareAd('linkedin')" title="Share on LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </button>
                    <button class="share-btn" onclick="shareAd('copy')" title="Copy Link">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
                <button class="modal-action-btn" onclick="contactUs()">
                    <i class="fas fa-envelope"></i>
                    Contact Us
                </button>
            </div>
        </div>
    </div>

    <script>
        // Modal Functions
        function openAdModal(id, title, description, imagePath, date) {
            const modal = document.getElementById('adModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            const modalDate = document.getElementById('modalDate');

            // Check if image path is external URL or local path
            const imageUrl = (imagePath.startsWith('http://') || imagePath.startsWith('https://')) 
                ? imagePath 
                : '../' + imagePath;

            // Set modal content
            modalImage.src = imageUrl;
            modalImage.alt = title;
            modalTitle.textContent = title;
            modalDescription.textContent = description;
            modalDate.textContent = date;

            // Show modal
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeAdModal() {
            const modal = document.getElementById('adModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('adModal');
            if (event.target === modal) {
                closeAdModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAdModal();
            }
        });

        // Share functions
        function shareAd(platform) {
            const title = document.getElementById('modalTitle').textContent;
            const url = window.location.href;

            switch(platform) {
                case 'facebook':
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
                    break;
                case 'twitter':
                    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
                    break;
                case 'linkedin':
                    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank');
                    break;
                case 'copy':
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Link copied to clipboard!');
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                    });
                    break;
            }
        }

        function contactUs() {
            // Redirect to contact page or open email client
            window.location.href = '../about<?php echo $isLoggedIn ? '' : 'no'; ?>.php#contact';
        }

        // Dropdown toggle
        function toggleDropdown() {
            const dropdown = document.getElementById("cardsDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        // Close dropdown when clicking outside
        window.addEventListener("click", function(e) {
            if (!e.target.matches('.dropbtn')) {
                const dropdown = document.getElementById("cardsDropdown");
                if (dropdown && dropdown.style.display === "block") {
                    dropdown.style.display = "none";
                }
            }
        });

        // Profile dropdown toggle
        function toggleProfileDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('profileDropdown');
            const btn = document.getElementById('profileBtn');
            if (dd && btn) {
                const isOpen = dd.classList.toggle('show');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
        }

        // Close profile dropdown when clicking outside
        window.addEventListener('click', function (e) {
            const dd = document.getElementById('profileDropdown');
            const btn = document.getElementById('profileBtn');
            if (!dd || !btn) return;
            if (dd.classList.contains('show') && !e.composedPath().includes(dd) && e.target !== btn) {
                dd.classList.remove('show');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on Escape key
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dd = document.getElementById('profileDropdown');
                const btn = document.getElementById('profileBtn');
                if (dd && dd.classList.contains('show')) {
                    dd.classList.remove('show');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Add smooth scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.ad-card');
            
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Optional: Add click event to cards
        document.querySelectorAll('.ad-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.classList.contains('learn-more-btn')) {
                    // You can add navigation or modal functionality here
                    console.log('Card clicked');
                }
            });
        });
    </script>
</body>
</html>
