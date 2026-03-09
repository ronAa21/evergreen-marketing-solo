<?php
if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

include("db_connect.php");

$message = '';
$message_type = '';

// Get filter from URL parameter
$status_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

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

// Fetch card applications with optional status filter and ID documents
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
            bc.contact_number,
            aa.id_type,
            aa.id_number,
            GROUP_CONCAT(CONCAT(ad.document_type, ':', ad.file_path) SEPARATOR '|') as id_documents
        FROM card_applications ca
        JOIN bank_customers bc ON ca.customer_id = bc.customer_id
        LEFT JOIN account_applications aa ON bc.customer_id = aa.customer_id
        LEFT JOIN application_documents ad ON aa.application_id = ad.application_id 
            AND ad.document_type IN ('id_front', 'id_back')";

// Add WHERE clause if filtering by status
if ($status_filter !== 'all') {
    $sql .= " WHERE ca.status = '" . $conn->real_escape_string($status_filter) . "'";
}

$sql .= " GROUP BY ca.application_id
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
    // Parse ID documents
    $id_docs = [];
    if (!empty($row['id_documents'])) {
        $docs = explode('|', $row['id_documents']);
        foreach ($docs as $doc) {
            list($type, $path) = explode(':', $doc);
            $id_docs[$type] = $path;
        }
    }
    $row['id_docs'] = $id_docs;
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

    .stat-card-link {
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
    }

    .stat-card-link:hover {
        transform: translateY(-5px);
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .stat-card.active-filter {
        border-color: #003631;
        box-shadow: 0 4px 20px rgba(0,54,49,0.2);
        transform: scale(1.05);
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
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
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

    .view-id-btn {
        background: #007bff;
        color: white;
    }

    .view-id-btn:hover {
        background: #0056b3;
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

<!-- Statistics Cards with Filter Links -->
<div class="stats-grid">
    <a href="?page=applications&filter=all" class="stat-card-link">
        <div class="stat-card <?php echo $status_filter === 'all' ? 'active-filter' : ''; ?>">
            <div class="stat-number"><?php echo $stats['total']; ?></div>
            <div class="stat-label">Total Applications</div>
        </div>
    </a>
    <a href="?page=applications&filter=pending" class="stat-card-link">
        <div class="stat-card pending <?php echo $status_filter === 'pending' ? 'active-filter' : ''; ?>">
            <div class="stat-number"><?php echo $stats['pending']; ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </a>
    <a href="?page=applications&filter=approved" class="stat-card-link">
        <div class="stat-card approved <?php echo $status_filter === 'approved' ? 'active-filter' : ''; ?>">
            <div class="stat-number"><?php echo $stats['approved']; ?></div>
            <div class="stat-label">Approved</div>
        </div>
    </a>
    <a href="?page=applications&filter=declined" class="stat-card-link">
        <div class="stat-card declined <?php echo $status_filter === 'declined' ? 'active-filter' : ''; ?>">
            <div class="stat-number"><?php echo $stats['declined']; ?></div>
            <div class="stat-label">Declined</div>
        </div>
    </a>
</div>

<?php if ($status_filter !== 'all'): ?>
    <div style="margin-bottom: 20px;">
        <span style="background: #f0f0f0; padding: 8px 16px; border-radius: 20px; font-size: 14px; color: #003631;">
            Showing: <strong><?php echo ucfirst($status_filter); ?></strong> applications
            <a href="?page=applications&filter=all" style="margin-left: 10px; color: #003631; text-decoration: none;">✕ Clear filter</a>
        </span>
    </div>
<?php endif; ?>

<!-- Applications Table -->
<div class="applications-table">
    <?php if (count($applications) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
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
                        <td><strong>#<?php echo $app['customer_id']; ?></strong></td>
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
                            <div class="action-buttons">
                                <?php if ($app['status'] === 'pending'): ?>
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
                                <?php else: ?>
                                    <span style="color: #999; font-size: 12px;">
                                        Reviewed on <?php echo date('M d, Y', strtotime($app['reviewed_at'])); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <!-- View ID Button -->
                                <button type="button" class="action-btn view-id-btn" onclick="viewID(<?php echo $app['customer_id']; ?>, '<?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?>', '<?php echo htmlspecialchars($app['id_type'] ?? 'N/A'); ?>', '<?php echo htmlspecialchars($app['id_number'] ?? 'N/A'); ?>', '<?php echo htmlspecialchars($app['id_docs']['id_front'] ?? ''); ?>', '<?php echo htmlspecialchars($app['id_docs']['id_back'] ?? ''); ?>')">
                                    🆔 View ID
                                </button>
                            </div>
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

<!-- ID Verification Modal -->
<div id="idModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10000; overflow-y: auto;">
    <div style="background: white; max-width: 900px; margin: 50px auto; border-radius: 15px; padding: 30px; position: relative;">
        <button onclick="closeIDModal()" style="position: absolute; top: 20px; right: 20px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 35px; height: 35px; font-size: 20px; cursor: pointer; line-height: 1;">×</button>
        
        <h2 style="color: #003631; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 28px;">🆔</span>
            <span>ID Verification</span>
        </h2>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div>
                    <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Customer Name:</strong>
                    <p id="modalCustomerName" style="font-size: 16px; color: #003631; margin-top: 5px;"></p>
                </div>
                <div>
                    <strong style="color: #666; font-size: 12px; text-transform: uppercase;">User ID:</strong>
                    <p id="modalCustomerID" style="font-size: 16px; color: #003631; margin-top: 5px;"></p>
                </div>
                <div>
                    <strong style="color: #666; font-size: 12px; text-transform: uppercase;">ID Type:</strong>
                    <p id="modalIDType" style="font-size: 16px; color: #003631; margin-top: 5px;"></p>
                </div>
                <div>
                    <strong style="color: #666; font-size: 12px; text-transform: uppercase;">ID Number:</strong>
                    <p id="modalIDNumber" style="font-size: 16px; color: #003631; margin-top: 5px;"></p>
                </div>
            </div>
        </div>
        
        <div id="idImagesContainer">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <h3 style="color: #003631; margin-bottom: 15px; font-size: 16px;">ID Front</h3>
                    <div id="idFrontContainer" style="border: 2px solid #e0e0e0; border-radius: 10px; padding: 10px; background: #f8f9fa; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                        <p style="color: #999;">No image available</p>
                    </div>
                </div>
                <div>
                    <h3 style="color: #003631; margin-bottom: 15px; font-size: 16px;">ID Back</h3>
                    <div id="idBackContainer" style="border: 2px solid #e0e0e0; border-radius: 10px; padding: 10px; background: #f8f9fa; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                        <p style="color: #999;">No image available</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 25px; text-align: center;">
            <button onclick="closeIDModal()" style="background: #003631; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

<script>
function viewID(customerID, customerName, idType, idNumber, idFront, idBack) {
    // Set customer info
    document.getElementById('modalCustomerName').textContent = customerName;
    document.getElementById('modalCustomerID').textContent = '#' + customerID;
    document.getElementById('modalIDType').textContent = idType || 'N/A';
    document.getElementById('modalIDNumber').textContent = idNumber || 'N/A';
    
    // Set ID front image
    const frontContainer = document.getElementById('idFrontContainer');
    if (idFront && idFront !== '') {
        // Path is relative to Basic-operation, so we need to adjust
        const frontPath = '../Basic-operation/' + idFront;
        frontContainer.innerHTML = '<img src="' + frontPath + '" style="max-width: 100%; height: auto; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src, \'_blank\')" alt="ID Front" onerror="this.parentElement.innerHTML=\'<p style=color:#dc3545>Image not found</p>\'">';
    } else {
        frontContainer.innerHTML = '<p style="color: #999;">No image uploaded</p>';
    }
    
    // Set ID back image
    const backContainer = document.getElementById('idBackContainer');
    if (idBack && idBack !== '') {
        const backPath = '../Basic-operation/' + idBack;
        backContainer.innerHTML = '<img src="' + backPath + '" style="max-width: 100%; height: auto; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src, \'_blank\')" alt="ID Back" onerror="this.parentElement.innerHTML=\'<p style=color:#dc3545>Image not found</p>\'">';
    } else {
        backContainer.innerHTML = '<p style="color: #999;">No image uploaded</p>';
    }
    
    // Show modal
    document.getElementById('idModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeIDModal() {
    document.getElementById('idModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('idModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeIDModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeIDModal();
    }
});
</script>

<?php $conn->close(); ?>
