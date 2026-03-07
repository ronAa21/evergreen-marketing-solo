<?php 
session_start();

// 1. Check if the user is actually logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Combine names from session or fallback to a default
// This checks if 'full_name' exists; if not, it joins first and last names
$fullName = $_SESSION['full_name'] ?? ($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);

// 3. Fallback if everything is empty
if (trim($fullName) == '') {
    $fullName = "Valued Customer";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <title>What's new</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Navigation -->
<nav>
    <div class="logo">
        <div class="logo-icon">
            <a href="viewingpage.php">
                <img src="../images/Logo.png.png">
            </a>
        </div>
        <span>
            <a href="../viewingpage.php">EVERGREEN</a>
        </span>
    </div>

    <div class="nav-links">
        <a href="../viewingpage.php">Home</a>

        <div class="dropdown">
            <button class="dropbtn" onclick="toggleDropdown()">Cards ⏷</button>
            <div class="dropdown-content" id="cardsDropdown">
                <a href="../cards/credit.php">Credit Cards</a>
                <a href="../cards/debit.php">Debit Cards</a>
                <a href="../cards/prepaid.php">Prepaid Cards</a>
                <a href="../cards/rewards.php">Card Rewards</a>
            </div>
        </div>

        <!-- replaced: loans -->
        <a href="../Content-view/index.php">What's new</a>
        <a href="../about.php">About Us</a>
    </div>

    <div class="nav-buttons">
        <a href="#" class="username-profile"><?php echo htmlspecialchars($fullName); ?></a>

        <div class="profile-actions">
            <div class="logo-icon" style="width:40px;height:40px;">
                <button id="profileBtn" class="profile-btn" aria-haspopup="true" aria-expanded="false" onclick="toggleProfileDropdown(event)" title="Open profile menu">
                    <img src="../images/pfp.png" alt="Profile Icon">
                </button>
            </div>

            <div id="profileDropdown" class="profile-dropdown" role="menu" aria-labelledby="profileBtn">
                <a href="../../Basic-operation/operations/public/customer/profile" role="menuitem">Profile</a>
                <a href="../cards/points.php" role="menuitem">Missions</a>
                <a href="viewing.php" role="menuitem" onclick="showSignOutModal(event)">Sign Out</a>
            </div>
        </div>
    </div>
</nav>

<!-- main content -->
<main>

    <!-- Initial Display (will be changed) -->
    <div class="content-view">
        <!-- Display content -->
    </div>

</main>
</body>
</html>
<script src="script.js"></script>