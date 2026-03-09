<?php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true
]);

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include("db_connect.php");

// Include content helper for dynamic content
include_once(__DIR__ . '/includes/content_helper.php');

$success_message = '';
$error_message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Sanitize inputs
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city_province = trim($_POST['city_province'] ?? '');
    
    // Validate email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format. Please enter a valid email address.";
    }
    // Validate contact number (Philippine mobile format: 09XXXXXXXXX)
    elseif (!empty($contact_number) && !preg_match('/^09[0-9]{9}$/', $contact_number)) {
        $error_message = "Invalid contact number. Please use Philippine mobile format (09XXXXXXXXX).";
    }
    // Validate address length
    elseif (!empty($address) && (strlen($address) < 5 || strlen($address) > 200)) {
        $error_message = "Address must be between 5 and 200 characters.";
    }
    // Validate city/province length
    elseif (!empty($city_province) && (strlen($city_province) < 3 || strlen($city_province) > 100)) {
        $error_message = "City/Province must be between 3 and 100 characters.";
    }
    else {
        $conn->begin_transaction();
        
        try {
            // Update bank_customers table
            $sql = "UPDATE bank_customers SET 
                    contact_number = ?, 
                    email = ?
                    WHERE customer_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $contact_number, $email, $user_id);
            $stmt->execute();
            $stmt->close();
            
            // Check if addresses table exists and update/insert address
            $check_table = $conn->query("SHOW TABLES LIKE 'addresses'");
            if ($check_table->num_rows > 0) {
                // Check if user has an address record
                $sql = "SELECT address_id FROM addresses WHERE customer_id = ? AND is_primary = 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // Update existing address
                    $sql = "UPDATE addresses SET address_line = ? WHERE customer_id = ? AND is_primary = 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $address, $user_id);
                    $stmt->execute();
                } else {
                    // Insert new address
                    $sql = "INSERT INTO addresses (customer_id, address_line, is_primary) VALUES (?, ?, 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $user_id, $address);
                    $stmt->execute();
                }
                $stmt->close();
            } else {
                // Fallback: update bank_customers if addresses table doesn't exist
                $sql = "UPDATE bank_customers SET address = ?, city_province = ? WHERE customer_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $address, $city_province, $user_id);
                $stmt->execute();
                $stmt->close();
            }
            
            $conn->commit();
            $success_message = "Profile updated successfully!";
            $_SESSION['email'] = $email; // Update session email
            
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Failed to update profile. Please try again.";
        }
    }
}

// Fetch user profile data with address information
$user_id = $_SESSION['user_id'];

// Try to get data from bank_customers with LEFT JOIN to addresses table
$sql = "SELECT 
            bc.*,
            a.address_line,
            p.province_name,
            c.city_name,
            b.barangay_name
        FROM bank_customers bc
        LEFT JOIN addresses a ON bc.customer_id = a.customer_id AND a.is_primary = 1
        LEFT JOIN provinces p ON a.province_id = p.province_id
        LEFT JOIN cities c ON a.city_id = c.city_id
        LEFT JOIN barangays b ON a.barangay_id = b.barangay_id
        WHERE bc.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

// Format data for display
$full_name = trim(($profile['first_name'] ?? '') . ' ' . ($profile['middle_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));
$email = $profile['email'] ?? 'N/A';
$contact_number = $profile['contact_number'] ?? 'N/A';

// Try to get address from addresses table first, fallback to bank_customers columns
$address = 'N/A';
if (!empty($profile['address_line'])) {
    $address = $profile['address_line'];
} elseif (!empty($profile['address'])) {
    $address = $profile['address'];
}

// Build city/province from location tables or fallback to bank_customers column
$city_province = 'N/A';
if (!empty($profile['city_name']) && !empty($profile['province_name'])) {
    $city_province = $profile['city_name'] . ', ' . $profile['province_name'];
} elseif (!empty($profile['city_province'])) {
    $city_province = $profile['city_province'];
}

$birthday = $profile['birthday'] ?? 'N/A';
if ($birthday !== 'N/A' && $birthday !== null) {
    $birthday = date('F j, Y', strtotime($birthday));
}
$bank_id = $profile['bank_id'] ?? '0';
$referral_code = $profile['referral_code'] ?? 'N/A';
$total_points = number_format($profile['total_points'] ?? 0, 2);
$member_since = date('F j, Y', strtotime($profile['created_at'] ?? 'now'));

// Fetch card applications for this user
$card_apps_sql = "SELECT application_id, card_type, application_date, status, reviewed_at 
                  FROM card_applications 
                  WHERE customer_id = ? 
                  ORDER BY application_date DESC";
$card_stmt = $conn->prepare($card_apps_sql);
$card_stmt->bind_param("i", $user_id);
$card_stmt->execute();
$card_result = $card_stmt->get_result();
$card_applications = [];
while ($row = $card_result->fetch_assoc()) {
    $card_applications[] = $row;
}
$card_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Evergreen Bank</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(180deg, #003631 0%, #004d47 100%);
            min-height: 100vh;
            color: #fff;
        }

        /* Navigation */
        nav {
            background: #003631;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: #F1B24A;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #fff;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background: #F1B24A;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .username-display {
            color: white;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout {
            background: #F1B24A;
            color: #003631;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #e69610;
            transform: translateY(-1px);
        }

        /* Profile Container */
        .profile-container {
            max-width: 900px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        /* Profile Icon */
        .profile-icon-wrapper {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-icon {
            width: 80px;
            height: 80px;
            background: rgba(241, 178, 74, 0.2);
            border: 3px solid #F1B24A;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #F1B24A;
            margin-bottom: 1rem;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .profile-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .profile-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(241, 178, 74, 0.15);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: #F1B24A;
            font-size: 1.3rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Profile Cards */
        .profile-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: rgba(0, 0, 0, 0.2);
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-header i {
            color: #F1B24A;
            font-size: 1.3rem;
        }

        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }

        .card-body {
            padding: 2rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-value .copy-icon {
            color: #F1B24A;
            cursor: pointer;
            transition: all 0.3s;
        }

        .info-value .copy-icon:hover {
            transform: scale(1.1);
        }

        /* Editable Fields */
        .edit-field-group {
            position: relative;
        }

        .edit-field-group.view-mode .edit-input {
            display: none;
        }

        .edit-field-group.edit-mode .info-value {
            display: none;
        }

        .edit-input {
            width: 100%;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 1rem;
            color: #fff;
            transition: all 0.3s;
        }

        .edit-input:focus {
            outline: none;
            border-color: #F1B24A;
            background: rgba(0, 0, 0, 0.4);
        }

        .edit-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .edit-btn-container {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .btn-edit, .btn-cancel {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit {
            background: rgba(241, 178, 74, 0.2);
            color: #F1B24A;
            border: 1px solid #F1B24A;
        }

        .btn-edit:hover {
            background: #F1B24A;
            color: #003631;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-submit {
            background: #F1B24A;
            color: #003631;
            padding: 1rem 3rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-submit:hover {
            background: #e69610;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(241, 178, 74, 0.3);
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            color: #5dff8f;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ff6b7a;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-container {
                padding: 0 1rem;
                margin: 2rem auto;
            }

            .profile-header h1 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }

            nav {
                padding: 1rem 2%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="viewingpage.php" class="logo">
            <div class="logo-icon">
                <img src="<?php echo htmlspecialchars(get_company_logo()); ?>" alt="<?php echo htmlspecialchars(get_company_name()); ?> Logo">
            </div>
            <span><?php echo htmlspecialchars(get_company_name()); ?></span>
        </a>

        <div class="nav-links">
            <a href="viewingpage.php">Home</a>
            <a href="profile.php" class="active">Profile</a>
            <a href="refer.php">Referral</a>
        </div>

        <div class="nav-buttons">
            <span class="username-display">
                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($full_name) ?>
            </span>
            <a href="logout.php" class="btn-logout">
                Logout
            </a>
        </div>
    </nav>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Profile Icon -->
        <div class="profile-icon-wrapper">
            <div class="profile-icon">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="profile-header">
            <h1>My Profile</h1>
            <p>Manage your account information and settings</p>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value"><?= $total_points ?></div>
                <div class="stat-label">Total Points</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="stat-value"><?= htmlspecialchars($bank_id) ?></div>
                <div class="stat-label">Bank ID</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-value"><?= $member_since ?></div>
                <div class="stat-label">Member Since</div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-briefcase"></i>
                <h2>Account Information</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Full Name</span>
                        <div class="info-value"><?= htmlspecialchars($full_name) ?></div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Date of Birth</span>
                        <div class="info-value"><?= htmlspecialchars($birthday) ?></div>
                    </div>

                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Referral Code</span>
                        <div class="info-value">
                            <span><?= htmlspecialchars($referral_code) ?></span>
                            <i class="fas fa-copy copy-icon" 
                               onclick="copyReferralCode('<?= htmlspecialchars($referral_code) ?>')" 
                               title="Copy to clipboard"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information (Editable) -->
        <form method="POST" id="profileForm">
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-envelope"></i>
                    <h2>Contact Information</h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item edit-field-group view-mode" data-field="email">
                            <span class="info-label">Email Address</span>
                            <div class="info-value"><?= htmlspecialchars($email) ?></div>
                            <input type="email" 
                                   name="email" 
                                   class="edit-input" 
                                   value="<?= htmlspecialchars($email) ?>" 
                                   required
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address">
                            <div class="edit-btn-container">
                                <button type="button" class="btn-edit" onclick="toggleEdit(this)">
                                    <i class="fas fa-edit"></i> Edit Email
                                </button>
                            </div>
                        </div>

                        <div class="info-item edit-field-group view-mode" data-field="contact_number">
                            <span class="info-label">Contact Number</span>
                            <div class="info-value"><?= htmlspecialchars($contact_number) ?></div>
                            <input type="tel" 
                                   name="contact_number" 
                                   class="edit-input" 
                                   value="<?= htmlspecialchars($contact_number) ?>" 
                                   placeholder="09171234567"
                                   pattern="09[0-9]{9}"
                                   maxlength="11"
                                   title="Please enter a valid Philippine mobile number (09XXXXXXXXX)"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="edit-btn-container">
                                <button type="button" class="btn-edit" onclick="toggleEdit(this)">
                                    <i class="fas fa-edit"></i> Edit Number
                                </button>
                            </div>
                        </div>

                        <div class="info-item edit-field-group view-mode" data-field="address">
                            <span class="info-label">Address</span>
                            <div class="info-value"><?= htmlspecialchars($address) ?></div>
                            <input type="text" 
                                   name="address" 
                                   class="edit-input" 
                                   value="<?= htmlspecialchars($address) ?>" 
                                   placeholder="Street, Barangay"
                                   minlength="5"
                                   maxlength="200"
                                   required
                                   title="Please enter your complete address">
                            <div class="edit-btn-container">
                                <button type="button" class="btn-edit" onclick="toggleEdit(this)">
                                    <i class="fas fa-edit"></i> Edit Address
                                </button>
                            </div>
                        </div>

                        <div class="info-item edit-field-group view-mode" data-field="city_province">
                            <span class="info-label">City/Province</span>
                            <div class="info-value"><?= htmlspecialchars($city_province) ?></div>
                            <input type="text" 
                                   name="city_province" 
                                   class="edit-input" 
                                   value="<?= htmlspecialchars($city_province) ?>" 
                                   placeholder="City, Province"
                                   minlength="3"
                                   maxlength="100"
                                   required
                                   title="Please enter your city and province">
                            <div class="edit-btn-container">
                                <button type="button" class="btn-edit" onclick="toggleEdit(this)">
                                    <i class="fas fa-edit"></i> Edit City
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Card Application Status Section -->
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-credit-card"></i>
                <h2>Card Application Status</h2>
            </div>
            <div class="card-body">
                <?php if (count($card_applications) > 0): ?>
                    <div class="card-applications-list">
                        <?php foreach ($card_applications as $app): ?>
                            <div class="application-item">
                                <div class="application-header">
                                    <div class="card-type-info">
                                        <i class="fas fa-credit-card" style="color: #F1B24A;"></i>
                                        <span class="card-type-name"><?= htmlspecialchars(ucfirst($app['card_type'])) ?> Card</span>
                                    </div>
                                    <span class="status-badge status-<?= $app['status'] ?>">
                                        <?= htmlspecialchars(ucfirst($app['status'])) ?>
                                    </span>
                                </div>
                                <div class="application-details">
                                    <div class="detail-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Applied: <?= date('M d, Y', strtotime($app['application_date'])) ?></span>
                                    </div>
                                    <?php if ($app['reviewed_at']): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Reviewed: <?= date('M d, Y', strtotime($app['reviewed_at'])) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($app['status'] === 'pending'): ?>
                                    <div class="application-message pending-message">
                                        <i class="fas fa-clock"></i>
                                        Your application is being reviewed. We'll notify you once a decision is made.
                                    </div>
                                <?php elseif ($app['status'] === 'approved'): ?>
                                    <div class="application-message success-message">
                                        <i class="fas fa-check-circle"></i>
                                        Congratulations! Your card application has been approved. Your card will be delivered soon.
                                    </div>
                                <?php elseif ($app['status'] === 'declined'): ?>
                                    <div class="application-message error-message">
                                        <i class="fas fa-times-circle"></i>
                                        Unfortunately, your application was not approved at this time. You may reapply after 30 days.
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-applications-message">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: rgba(255,255,255,0.3); margin-bottom: 1rem;"></i>
                        <h3 style="color: rgba(255,255,255,0.8); margin-bottom: 0.5rem;">No Card Applications Yet</h3>
                        <p style="color: rgba(255,255,255,0.5); margin-bottom: 1.5rem;">You haven't applied for any cards yet.</p>
                        <a href="cards/credit.php" class="btn-apply-card">
                            <i class="fas fa-plus-circle"></i> Apply for a Card
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        /* Card Application Status Styles */
        .card-applications-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .application-item {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .application-item:hover {
            background: rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-type-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-type-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(241, 178, 74, 0.2);
            color: #F1B24A;
            border: 1px solid #F1B24A;
        }

        .status-approved {
            background: rgba(40, 167, 69, 0.2);
            color: #5dff8f;
            border: 1px solid #5dff8f;
        }

        .status-declined {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b7a;
            border: 1px solid #ff6b7a;
        }

        .application-details {
            display: flex;
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .detail-item i {
            color: #F1B24A;
        }

        .application-message {
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .pending-message {
            background: rgba(241, 178, 74, 0.1);
            border: 1px solid rgba(241, 178, 74, 0.3);
            color: #F1B24A;
        }

        .success-message {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #5dff8f;
        }

        .error-message {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b7a;
        }

        .no-applications-message {
            text-align: center;
            padding: 3rem 2rem;
        }

        .btn-apply-card {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #F1B24A;
            color: #003631;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-apply-card:hover {
            background: #e69610;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(241, 178, 74, 0.3);
        }

        @media (max-width: 768px) {
            .application-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .application-details {
                flex-direction: column;
                gap: 0.75rem;
            }
        }
    </style>

    <script>
        function toggleEdit(button) {
            const fieldGroup = button.closest('.edit-field-group');
            
            if (fieldGroup.classList.contains('view-mode')) {
                // Switch to edit mode
                fieldGroup.classList.remove('view-mode');
                fieldGroup.classList.add('edit-mode');
                
                // Change button to cancel
                button.outerHTML = `
                    <button type="button" class="btn-cancel" onclick="cancelEdit(this)">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                `;
                
                // Focus on input
                const input = fieldGroup.querySelector('.edit-input');
                if (input) {
                    input.focus();
                }
            }
        }

        function cancelEdit(button) {
            const fieldGroup = button.closest('.edit-field-group');
            fieldGroup.classList.remove('edit-mode');
            fieldGroup.classList.add('view-mode');
            
            // Restore original value
            location.reload();
        }

        function copyReferralCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                // Create a temporary notification
                const notification = document.createElement('div');
                notification.textContent = 'Referral code copied!';
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: rgba(40, 167, 69, 0.9);
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    z-index: 10000;
                    animation: slideIn 0.3s ease;
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Failed to copy referral code');
            });
        }

        // Form validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            
            if (!email || !email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
        });
    </script>
</body>
</html>
