<?php
if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

$view = $_GET['view'] ?? '';
$page_title = '';
$records = [];

// Fetch data based on view type
switch ($view) {
    case 'total_users':
        $page_title = 'All Registered Users';
        $query = "SELECT customer_id, first_name, middle_name, last_name, email, contact_number, 
                  created_at, total_points, referral_code 
                  FROM bank_customers 
                  ORDER BY created_at DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    case 'new_users':
        $page_title = 'New Users This Month';
        $query = "SELECT customer_id, first_name, middle_name, last_name, email, contact_number, 
                  created_at, total_points, referral_code 
                  FROM bank_customers 
                  WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                  AND YEAR(created_at) = YEAR(CURRENT_DATE())
                  ORDER BY created_at DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    case 'total_applications':
        $page_title = 'All Card Applications';
        $query = "SELECT ca.*, bc.first_name, bc.middle_name, bc.last_name, bc.email 
                  FROM card_applications ca 
                  JOIN bank_customers bc ON ca.customer_id = bc.customer_id 
                  ORDER BY ca.application_date DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    case 'pending_applications':
        $page_title = 'Pending Applications';
        $query = "SELECT ca.*, bc.first_name, bc.middle_name, bc.last_name, bc.email 
                  FROM card_applications ca 
                  JOIN bank_customers bc ON ca.customer_id = bc.customer_id 
                  WHERE ca.status = 'pending'
                  ORDER BY ca.application_date DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    case 'approved_applications':
        $page_title = 'Approved Applications';
        $query = "SELECT ca.*, bc.first_name, bc.middle_name, bc.last_name, bc.email 
                  FROM card_applications ca 
                  JOIN bank_customers bc ON ca.customer_id = bc.customer_id 
                  WHERE ca.status = 'approved'
                  ORDER BY ca.application_date DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    case 'declined_applications':
        $page_title = 'Declined Applications';
        $query = "SELECT ca.*, bc.first_name, bc.middle_name, bc.last_name, bc.email 
                  FROM card_applications ca 
                  JOIN bank_customers bc ON ca.customer_id = bc.customer_id 
                  WHERE ca.status = 'declined'
                  ORDER BY ca.application_date DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    case 'total_referrals':
        $page_title = 'All Referrals';
        $check_referrals = $conn->query("SHOW TABLES LIKE 'referrals'");
        if ($check_referrals && $check_referrals->num_rows > 0) {
            $query = "SELECT r.*, 
                      bc1.first_name as referrer_first, bc1.last_name as referrer_last, bc1.email as referrer_email,
                      bc2.first_name as referred_first, bc2.last_name as referred_last, bc2.email as referred_email
                      FROM referrals r
                      LEFT JOIN bank_customers bc1 ON r.referrer_id = bc1.customer_id
                      LEFT JOIN bank_customers bc2 ON r.referred_id = bc2.customer_id
                      WHERE r.status = 'completed'
                      ORDER BY r.created_at DESC";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        break;
        
    case 'total_points':
        $page_title = 'Users with Points';
        $query = "SELECT customer_id, first_name, middle_name, last_name, email, 
                  total_points, referral_code, created_at 
                  FROM bank_customers 
                  WHERE total_points > 0
                  ORDER BY total_points DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        break;
        
    default:
        header("Location: ?page=dashboard");
        exit;
}

$conn->close();
?>

<style>
    .detailed-view-container {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .view-header h2 {
        color: #003631;
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .back-btn {
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 54, 49, 0.3);
    }
    
    .records-count {
        background: #f8f9fa;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #666;
        font-weight: 600;
    }
    
    .records-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .records-table thead {
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
    }
    
    .records-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .records-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #333;
    }
    
    .records-table tbody tr {
        transition: all 0.3s ease;
    }
    
    .records-table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
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
    
    .status-badge.completed {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .empty-state p {
        font-size: 16px;
        margin-bottom: 10px;
    }
    
    .search-filter-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .search-box {
        flex: 1;
        min-width: 250px;
    }
    
    .search-box input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        outline: none;
        border-color: #003631;
        box-shadow: 0 0 0 3px rgba(0, 54, 49, 0.1);
    }
    
    .export-btn {
        background: #F1B24A;
        color: #003631;
        padding: 12px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .export-btn:hover {
        background: #e69610;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(241, 178, 74, 0.3);
    }
    
    @media (max-width: 768px) {
        .detailed-view-container {
            padding: 20px;
        }
        
        .view-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .records-table {
            font-size: 12px;
        }
        
        .records-table th,
        .records-table td {
            padding: 10px;
        }
        
        .search-filter-bar {
            flex-direction: column;
        }
        
        .search-box {
            min-width: 100%;
        }
    }
</style>

<div class="detailed-view-container">
    <div class="view-header">
        <h2>
            <i class="fas fa-list"></i>
            <?php echo htmlspecialchars($page_title); ?>
        </h2>
        <a href="?page=dashboard" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
    
    <div class="records-count">
        <i class="fas fa-database"></i>
        Total Records: <strong><?php echo count($records); ?></strong>
    </div>
    
    <div class="search-filter-bar">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search records..." onkeyup="searchTable()">
        </div>
        <button class="export-btn" onclick="exportToCSV()">
            <i class="fas fa-download"></i>
            Export to CSV
        </button>
    </div>
    
    <?php if (count($records) > 0): ?>
        <div style="overflow-x: auto;">
            <table class="records-table" id="recordsTable">
                <thead>
                    <tr>
                        <?php if (in_array($view, ['total_users', 'new_users', 'total_points'])): ?>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <?php if ($view === 'total_points'): ?>
                                <th>Points</th>
                            <?php endif; ?>
                            <th>Referral Code</th>
                            <th>Registered</th>
                        <?php elseif (strpos($view, 'applications') !== false): ?>
                            <th>Application ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Card Type</th>
                            <th>Status</th>
                            <th>Application Date</th>
                        <?php elseif ($view === 'total_referrals'): ?>
                            <th>Referral ID</th>
                            <th>Referrer</th>
                            <th>Referred User</th>
                            <th>Status</th>
                            <th>Date</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <?php if (in_array($view, ['total_users', 'new_users', 'total_points'])): ?>
                                <td><?php echo htmlspecialchars($record['customer_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['first_name'] . ' ' . $record['middle_name'] . ' ' . $record['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                                <td><?php echo htmlspecialchars($record['contact_number']); ?></td>
                                <?php if ($view === 'total_points'): ?>
                                    <td><strong><?php echo number_format($record['total_points'], 2); ?></strong></td>
                                <?php endif; ?>
                                <td><code><?php echo htmlspecialchars($record['referral_code']); ?></code></td>
                                <td><?php echo date('M d, Y g:i A', strtotime($record['created_at'])); ?></td>
                            <?php elseif (strpos($view, 'applications') !== false): ?>
                                <td><?php echo htmlspecialchars($record['application_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['first_name'] . ' ' . $record['middle_name'] . ' ' . $record['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($record['card_type'])); ?></td>
                                <td><span class="status-badge <?php echo $record['status']; ?>"><?php echo ucfirst($record['status']); ?></span></td>
                                <td><?php echo date('M d, Y g:i A', strtotime($record['application_date'])); ?></td>
                            <?php elseif ($view === 'total_referrals'): ?>
                                <td><?php echo htmlspecialchars($record['referral_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['referrer_first'] . ' ' . $record['referrer_last']); ?><br><small><?php echo htmlspecialchars($record['referrer_email']); ?></small></td>
                                <td><?php echo htmlspecialchars($record['referred_first'] . ' ' . $record['referred_last']); ?><br><small><?php echo htmlspecialchars($record['referred_email']); ?></small></td>
                                <td><span class="status-badge <?php echo $record['status']; ?>"><?php echo ucfirst($record['status']); ?></span></td>
                                <td><?php echo date('M d, Y g:i A', strtotime($record['created_at'])); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No records found</p>
            <small>There are currently no records to display for this category.</small>
        </div>
    <?php endif; ?>
</div>

<script>
    // Search functionality
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('recordsTable');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            let found = false;
            const td = tr[i].getElementsByTagName('td');
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            tr[i].style.display = found ? '' : 'none';
        }
    }
    
    // Export to CSV
    function exportToCSV() {
        const table = document.getElementById('recordsTable');
        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const row = [];
            const cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            
            csv.push(row.join(','));
        }
        
        const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        const downloadLink = document.createElement('a');
        downloadLink.download = '<?php echo str_replace(' ', '_', strtolower($page_title)); ?>_' + new Date().getTime() + '.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
