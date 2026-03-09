<?php
if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

include("db_connect.php");

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_content') {
        $content_key = $_POST['content_key'];
        $content_value = $_POST['content_value'];
        $admin_id = $_SESSION['admin_id'];
        
        $sql = "UPDATE site_content SET content_value = ?, updated_by = ? WHERE content_key = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $content_value, $admin_id, $content_key);
        
        if ($stmt->execute()) {
            $message = 'Content updated successfully!';
            $message_type = 'success';
        } else {
            $message = 'Error updating content: ' . $conn->error;
            $message_type = 'error';
        }
        $stmt->close();
    }
}

// Fetch all content
$sql = "SELECT * FROM site_content ORDER BY content_key";
$result = $conn->query($sql);
$content_items = [];
while ($row = $result->fetch_assoc()) {
    $content_items[] = $row;
}
?>

<div class="content-header">
    <h1>Manage Website Content</h1>
    <p>Manage website content, company information, and marketing materials</p>
</div>

<?php if ($message): ?>
    <div class="message <?php echo $message_type; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<style>
    .content-grid {
        display: grid;
        gap: 20px;
    }

    .content-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .content-card h3 {
        color: #003631;
        font-size: 18px;
        margin-bottom: 15px;
        text-transform: capitalize;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #003631;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #F1B24A;
    }

    .save-btn {
        padding: 12px 30px;
        background: #003631;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .save-btn:hover {
        background: #005544;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,54,49,0.2);
    }

    .content-info {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    .image-preview {
        margin-top: 10px;
        max-width: 200px;
    }

    .image-preview img {
        width: 100%;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
    }
</style>

<div class="content-grid">
    <?php foreach ($content_items as $item): ?>
        <div class="content-card">
            <h3><?php echo str_replace('_', ' ', $item['content_key']); ?></h3>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_content">
                <input type="hidden" name="content_key" value="<?php echo htmlspecialchars($item['content_key']); ?>">
                
                <div class="form-group">
    
                    <?php if ($item['content_type'] === 'html' || strlen($item['content_value']) > 100): ?>
                        <textarea 
                            id="<?php echo $item['content_key']; ?>" 
                            name="content_value" 
                            required
                        ><?php echo htmlspecialchars($item['content_value']); ?></textarea>
                    <?php else: ?>
                        <input 
                            type="text" 
                            id="<?php echo $item['content_key']; ?>" 
                            name="content_value" 
                            value="<?php echo htmlspecialchars($item['content_value']); ?>" 
                            required
                        >
                    <?php endif; ?>
                    
                    <div class="content-info">
                        Type: <?php echo $item['content_type']; ?> | 
                        Last updated: <?php echo date('M d, Y H:i', strtotime($item['updated_at'])); ?>
                    </div>
                    
                    <?php if ($item['content_type'] === 'image'): ?>
                        <div class="image-preview">
                            <img src="<?php echo htmlspecialchars($item['content_value']); ?>" alt="Preview">
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="save-btn">💾 Save Changes</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<?php $conn->close(); ?>
