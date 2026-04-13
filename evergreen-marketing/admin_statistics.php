<?php
if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

include("db_connect.php");

// Fetch statistics
$stats = [];

// Total Users
$result = $conn->query("SELECT COUNT(*) as total FROM bank_customers");
$stats['total_users'] = $result->fetch_assoc()['total'] ?? 0;

// New Users This Month
$result = $conn->query("SELECT COUNT(*) as total FROM bank_customers WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stats['new_users_month'] = $result->fetch_assoc()['total'] ?? 0;

// Total Applications
$result = $conn->query("SELECT COUNT(*) as total FROM card_applications");
$stats['total_applications'] = $result->fetch_assoc()['total'] ?? 0;

// Pending Applications
$result = $conn->query("SELECT COUNT(*) as total FROM card_applications WHERE status = 'pending'");
$stats['pending_applications'] = $result->fetch_assoc()['total'] ?? 0;

// Approved Applications
$result = $conn->query("SELECT COUNT(*) as total FROM card_applications WHERE status = 'approved'");
$stats['approved_applications'] = $result->fetch_assoc()['total'] ?? 0;

// Declined Applications
$result = $conn->query("SELECT COUNT(*) as total FROM card_applications WHERE status = 'declined'");
$stats['declined_applications'] = $result->fetch_assoc()['total'] ?? 0;

// Card Type Breakdown
$result = $conn->query("SELECT card_type, COUNT(*) as count FROM card_applications GROUP BY card_type");
$card_types = [];
while ($row = $result->fetch_assoc()) {
    $card_types[$row['card_type']] = $row['count'];
}

// Recent Applications (Last 5)
$result = $conn->query("SELECT ca.*, bc.first_name, bc.last_name, bc.email 
                        FROM card_applications ca 
                        JOIN bank_customers bc ON ca.customer_id = bc.customer_id 
                        ORDER BY ca.application_date DESC 
                        LIMIT 5");
$recent_applications = [];
while ($row = $result->fetch_assoc()) {
    $recent_applications[] = $row;
}

// Total Referrals (if table exists)
$check_referrals = $conn->query("SHOW TABLES LIKE 'referrals'");
if ($check_referrals && $check_referrals->num_rows > 0) {
    $result = $conn->query("SELECT COUNT(*) as total FROM referrals WHERE status = 'completed'");
    $stats['total_referrals'] = $result->fetch_assoc()['total'] ?? 0;
} else {
    $stats['total_referrals'] = 0;
}

// Total Points Distributed
$result = $conn->query("SELECT SUM(total_points) as total FROM bank_customers");
$stats['total_points'] = $result->fetch_assoc()['total'] ?? 0;

$conn->close();
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .content-header {
        margin-bottom: 30px;
    }

    .content-header h1 {
        color: #003631;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .content-header p {
        color: #666;
        font-size: 14px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #003631 0%, #F1B24A 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .stat-icon.users {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-icon.applications {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #d35400;
    }

    .stat-icon.approved {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #27ae60;
    }

    .stat-icon.declined {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #c0392b;
    }

    .stat-icon.referrals {
        background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
        color: #8e44ad;
    }

    .stat-icon.points {
        background: linear-gradient(135deg, #fddb92 0%, #d1fdff 100%);
        color: #f39c12;
    }

    .stat-icon.new-users {
        background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        color: #2980b9;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #003631;
        line-height: 1;
    }

    .stat-change {
        font-size: 12px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stat-change.positive {
        color: #27ae60;
    }

    .stat-change.negative {
        color: #c0392b;
    }

    .chart-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .chart-header h3 {
        color: #003631;
        font-size: 20px;
        font-weight: 600;
    }

    .chart-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .chart-bar {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .chart-bar-label {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .chart-bar-value {
        font-size: 28px;
        font-weight: 700;
        color: #003631;
        margin-bottom: 10px;
    }

    .chart-bar-fill {
        height: 8px;
        background: linear-gradient(90deg, #003631 0%, #F1B24A 100%);
        border-radius: 4px;
        margin-top: 10px;
    }

    .recent-activity {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .recent-activity h3 {
        color: #003631;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
        font-size: 16px;
        flex-shrink: 0;
    }

    .activity-details {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #003631;
        margin-bottom: 3px;
    }

    .activity-meta {
        font-size: 12px;
        color: #666;
    }

    .activity-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .activity-status.pending {
        background: #fff3cd;
        color: #856404;
    }

    .activity-status.approved {
        background: #d4edda;
        color: #155724;
    }

    .activity-status.declined {
        background: #f8d7da;
        color: #721c24;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .chart-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-header">
    <h1>Dashboard Overview</h1>
    <p>System statistics and recent activity</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon users">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-label">Total Users</div>
        <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>All registered customers</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon new-users">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="stat-label">New Users (This Month)</div>
        <div class="stat-value"><?php echo number_format($stats['new_users_month']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-calendar"></i>
            <span><?php echo date('F Y'); ?></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon applications">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-label">Total Applications</div>
        <div class="stat-value"><?php echo number_format($stats['total_applications']); ?></div>
        <div class="stat-change">
            <i class="fas fa-chart-line"></i>
            <span>All card applications</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-label">Pending Applications</div>
        <div class="stat-value"><?php echo number_format($stats['pending_applications']); ?></div>
        <div class="stat-change">
            <i class="fas fa-hourglass-half"></i>
            <span>Awaiting review</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon approved">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">Approved Applications</div>
        <div class="stat-value"><?php echo number_format($stats['approved_applications']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-thumbs-up"></i>
            <span>Successfully approved</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon declined">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-label">Declined Applications</div>
        <div class="stat-value"><?php echo number_format($stats['declined_applications']); ?></div>
        <div class="stat-change negative">
            <i class="fas fa-thumbs-down"></i>
            <span>Not approved</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon referrals">
            <i class="fas fa-share-alt"></i>
        </div>
        <div class="stat-label">Total Referrals</div>
        <div class="stat-value"><?php echo number_format($stats['total_referrals']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-users"></i>
            <span>Successful referrals</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon points">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-label">Total Points</div>
        <div class="stat-value"><?php echo number_format($stats['total_points'], 2); ?></div>
        <div class="stat-change">
            <i class="fas fa-gift"></i>
            <span>Points distributed</span>
        </div>
    </div>
</div>

<!-- Card Type Breakdown Chart -->
<div class="chart-section">
    <div class="chart-header">
        <h3><i class="fas fa-chart-bar"></i> Card Type Breakdown</h3>
    </div>
    <div class="chart-container">
        <?php foreach (['credit', 'debit', 'prepaid'] as $type): ?>
            <div class="chart-bar">
                <div class="chart-bar-label"><?php echo ucfirst($type); ?> Card</div>
                <div class="chart-bar-value"><?php echo number_format($card_types[$type] ?? 0); ?></div>
                <div class="chart-bar-fill" style="width: <?php echo $stats['total_applications'] > 0 ? (($card_types[$type] ?? 0) / $stats['total_applications'] * 100) : 0; ?>%;"></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Recent Activity -->
<div class="recent-activity">
    <h3><i class="fas fa-history"></i> Recent Applications</h3>
    <?php if (count($recent_applications) > 0): ?>
        <div class="activity-list">
            <?php foreach ($recent_applications as $app): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="activity-details">
                        <div class="activity-title">
                            <?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?>
                        </div>
                        <div class="activity-meta">
                            <?php echo ucfirst($app['card_type']); ?> Card • 
                            <?php echo date('M d, Y g:i A', strtotime($app['application_date'])); ?>
                        </div>
                    </div>
                    <span class="activity-status <?php echo $app['status']; ?>">
                        <?php echo ucfirst($app['status']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No recent applications</p>
        </div>
    <?php endif; ?>
</div>
