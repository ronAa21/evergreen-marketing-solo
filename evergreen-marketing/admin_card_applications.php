<?php
if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

include("db_connect.php");

$message = '';
$message_type = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $application_id = intval($_POST['application_id']);
        $new_status = $_POST['status'];
        $admin_id = $_SESSION['admin_id'];
        
        $sql = "UPDATE card_applications SET status = ?, reviewed_by = ?, reviewed_at = NOW() WHERE application_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $new_status, $admin_id, $application_id);
        
        if ($stmt->execute()) {
            $message = 'Application status updated successfully!';
            $message_type = 'success';
        } else {
            $message = 'Error updating status: ' . $conn->error;
            $message_type = 'error';
        }
        $stmt->close();
    }
}

// Fetch all card applications with customer details
$sql = "SELECT 
            ca.application_id,
            ca.card_type,
            ca.application_date,
            ca.status,
            ca.reviewed_at,
            bc.customer_id,
            bc.first_name,
            bc.last_name,
            bc.email,
            bc.contact_number
        FROM card_applications ca
        JOIN bank_customers bc ON ca.customer_id = bc.customer_id
        ORDER BY 
            CASE ca.status 
                WHEN 'pending' THEN 1 
                WHEN 'approved' THEN 2 
                WHEN 'declined' THEN 3 
            END,
            ca.application_date DESC";

$result = $conn->query($sql);
$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

// Count statistics
$stats_sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined
              FROM card_applications";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>

<div class="content-header">
    <h1>Card Applications</h1>
    <p>Review and manage customer card applications</p>
</div>

<?php if ($message): ?>
    <div class="message <?php echo $message_type; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
    }

    .stat-number {
        font-size: 36px;
        font-weight: bold;
        color: #003631;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card.pending .stat-number { color: #F1B24A; }
    .stat-card.approved .stat-number { color: #28a745; }
    .stat-card.declined .stat-number { color: #dc3545; }

    .applications-table {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #003631;
        color: white;
    }

    th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.approved {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.declined {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .approve-btn {
        background: #28a745;
        color: white;
    }

    .approve-btn:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .decline-btn {
        background: #dc3545;
        color: white;
    }

    .decline-btn:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    .action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .card-type-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #e9ecef;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .no-applications {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .no-applications-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .applications-table {
            overflow-x: auto;
        }

        table {
            min-width: 800px;
        }
    }
</style>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total']; ?></div>
        <div class="stat-label">Total Applications</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-number"><?php echo $stats['pending']; ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card approved">
        <div class="stat-number"><?php echo $stats['approved']; ?></div>
        <div class="stat-label">Approved</div>
    </div>
    <div class="stat-card declined">
        <div class="stat-number"><?php echo $stats['declined']; ?></div>
        <div class="stat-label">Declined</div>
    </div>
</div>

<!-- Applications Table -->
<div class="applications-table">
    <?php if (count($applications) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Card Type</th>
                    <th>Application Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td>#<?php echo $app['application_id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($app['email']); ?></td>
                        <td>
                            <span class="card-type-badge">
                                <?php echo htmlspecialchars($app['card_type']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo $app['status']; ?>">
                                <?php echo $app['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($app['status'] === 'pending'): ?>
                                <div class="action-buttons">
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="action-btn approve-btn">✓ Approve</button>
                                    </form>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                                        <input type="hidden" name="status" value="declined">
                                        <button type="submit" class="action-btn decline-btn">✗ Decline</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span style="color: #999; font-size: 12px;">
                                    Reviewed on <?php echo date('M d, Y', strtotime($app['reviewed_at'])); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-applications">
            <div class="no-applications-icon">📋</div>
            <h3>No Applications Yet</h3>
            <p>Card applications will appear here when customers apply.</p>
        </div>
    <?php endif; ?>
</div>

<?php $conn->close(); ?>
