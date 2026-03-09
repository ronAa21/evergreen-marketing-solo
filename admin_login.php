<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

include("db_connect.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $sql = "SELECT admin_id, username, email, password_hash, full_name FROM admin_users WHERE username = ? AND is_active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $admin['password_hash'])) {
                // Set session variables
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['full_name'];
                
                // Update last login
                $update_sql = "UPDATE admin_users SET last_login = NOW() WHERE admin_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $admin['admin_id']);
                $update_stmt->execute();
                
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Evergreen Bank</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #003631 0%, #005544 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            padding: 50px 40px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-section img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .logo-section h1 {
            color: #003631;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .logo-section p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #003631;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #F1B24A;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #f5c6cb;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: #003631;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-btn:hover {
            background: #005544;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,54,49,0.3);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #003631;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #F1B24A;
        }

        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <img src="images/Logo.png.png" alt="Evergreen Bank">
            <h1>Admin Login</h1>
            <p>Evergreen Bank Administration</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Login to Dashboard</button>
        </form>

        <div class="back-link">
            <a href="index.php">← Back to Main Site</a>
        </div>

        <div class="info-box">
            <strong>Default Credentials:</strong>
            Username: admin<br>
            Password: admin123
        </div>
    </div>
</body>
</html>
