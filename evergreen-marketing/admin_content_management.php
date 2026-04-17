<?php
if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

include("db_connect.php");

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'bulk_update') {
        $admin_id = $_SESSION['admin_id'];
        $updated_count = 0;
        $error_count = 0;
        
        // Handle file uploads first
        $upload_dir = 'uploads/content/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $uploaded_files = [];
        if (!empty($_FILES['content_files']['name'])) {
            foreach ($_FILES['content_files']['name'] as $content_key => $filename) {
                if (!empty($filename) && $_FILES['content_files']['error'][$content_key] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['content_files']['tmp_name'][$content_key];
                    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                    
                    if (in_array($file_ext, $allowed_extensions)) {
                        $new_filename = $content_key . '_' . time() . '.' . $file_ext;
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($file_tmp, $upload_path)) {
                            $uploaded_files[$content_key] = $upload_path;
                        }
                    }
                }
            }
        }
        
        // Prepare statement for efficiency
        $stmt = $conn->prepare("UPDATE site_content SET content_value = ?, updated_by = ?, updated_at = NOW() WHERE content_key = ?");
        
        foreach ($_POST['content'] as $content_key => $content_value) {
            // Use uploaded file path if available
            if (isset($uploaded_files[$content_key])) {
                $content_value = $uploaded_files[$content_key];
            }
            
            $stmt->bind_param("sis", $content_value, $admin_id, $content_key);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $updated_count++;
            }
        }
        
        $stmt->close();
        
        if ($updated_count > 0) {
            $message = "Successfully updated {$updated_count} content item(s)!";
            $message_type = 'success';
        } else {
            $message = 'No changes were made.';
            $message_type = 'info';
        }
    }
}

// Fetch all content and group by category
$sql = "SELECT * FROM site_content ORDER BY content_key";
$result = $conn->query($sql);

// Organize content by categories
$content_categories = [
    'Company Information' => [],
    'Contact Details' => [],
    'Hero Section' => [],
    'About Section' => [],
    'Services' => [],
    'Social Media' => [],
    'Other' => []
];

while ($row = $result->fetch_assoc()) {
    $key = $row['content_key'];
    
    // Categorize based on key patterns
    if (preg_match('/^(company_|business_)/i', $key)) {
        $content_categories['Company Information'][] = $row;
    } elseif (preg_match('/^(contact_|email|phone)/i', $key)) {
        $content_categories['Contact Details'][] = $row;
    } elseif (preg_match('/^hero_/i', $key)) {
        $content_categories['Hero Section'][] = $row;
    } elseif (preg_match('/^about_/i', $key)) {
        $content_categories['About Section'][] = $row;
    } elseif (preg_match('/^service_/i', $key)) {
        $content_categories['Services'][] = $row;
    } elseif (preg_match('/^(facebook|twitter|instagram|linkedin|social_)/i', $key)) {
        $content_categories['Social Media'][] = $row;
    } else {
        $content_categories['Other'][] = $row;
    }
}

// Remove empty categories
$content_categories = array_filter($content_categories, function($items) {
    return !empty($items);
});
?>

<style>
    .content-management-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .content-header {
        margin-bottom: 30px;
    }

    .content-header h1 {
        color: #003631;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .content-header p {
        color: #666;
        font-size: 14px;
    }

    .message {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.4s ease;
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

    .message.info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .action-bar {
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .action-bar-left {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 10px 15px 10px 40px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        width: 300px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #003631;
        box-shadow: 0 0 0 3px rgba(0, 54, 49, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .filter-select {
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        background: white;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #003631;
    }

    .bulk-save-btn {
        padding: 12px 30px;
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bulk-save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 54, 49, 0.3);
    }

    .category-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        border-left: 4px solid #003631;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .category-header h2 {
        color: #003631;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .category-badge {
        background: #F1B24A;
        color: #003631;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
    }

    .content-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .content-item:hover {
        border-color: #F1B24A;
        box-shadow: 0 4px 12px rgba(241, 178, 74, 0.2);
    }

    .content-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .content-label {
        color: #003631;
        font-weight: 600;
        font-size: 14px;
        text-transform: capitalize;
        flex: 1;
    }

    .content-type-badge {
        background: #e9ecef;
        color: #666;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .content-input,
    .content-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .content-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .content-input:focus,
    .content-textarea:focus {
        outline: none;
        border-color: #003631;
        box-shadow: 0 0 0 3px rgba(0, 54, 49, 0.1);
    }

    .content-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        font-size: 11px;
        color: #999;
    }

    .content-meta i {
        margin-right: 4px;
    }

    .image-preview {
        margin-top: 12px;
        max-width: 200px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e9ecef;
        position: relative;
    }

    .image-preview img {
        width: 100%;
        display: block;
    }

    .file-upload-wrapper {
        position: relative;
        margin-top: 10px;
    }

    .file-upload-input {
        display: none;
    }

    .file-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #F1B24A 0%, #e69610 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(241, 178, 74, 0.3);
    }

    .file-upload-btn i {
        font-size: 14px;
    }

    .current-file-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding: 8px 12px;
        background: #e9ecef;
        border-radius: 6px;
        font-size: 12px;
        color: #666;
    }

    .current-file-info i {
        color: #28a745;
    }

    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .remove-image-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .stats-bar {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-item {
        background: white;
        padding: 20px;
        border-radius: 12px;
        flex: 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.primary {
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
    }

    .stat-icon.secondary {
        background: linear-gradient(135deg, #F1B24A 0%, #e69610 100%);
        color: white;
    }

    .stat-icon.tertiary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-details h4 {
        color: #003631;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-details p {
        color: #666;
        font-size: 13px;
    }

    @media (max-width: 1200px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .action-bar {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .action-bar-left {
            flex-direction: column;
        }

        .search-box input {
            width: 100%;
        }

        .stats-bar {
            flex-direction: column;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-management-container">
    <div class="content-header">
        <h1><i class="fas fa-edit"></i> Content Management System</h1>
        <p>Manage all website content from a centralized dashboard</p>
    </div>

    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : ($message_type === 'error' ? 'exclamation-circle' : 'info-circle'); ?>"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Bar -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon primary">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-details">
                <h4><?php echo array_sum(array_map('count', $content_categories)); ?></h4>
                <p>Total Content Items</p>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon secondary">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-details">
                <h4><?php echo count($content_categories); ?></h4>
                <p>Categories</p>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon tertiary">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h4><?php echo date('M d, Y'); ?></h4>
                <p>Last Updated</p>
            </div>
        </div>
    </div>

    <form method="POST" action="" id="contentForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="bulk_update">

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="action-bar-left">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search content..." onkeyup="filterContent()">
                </div>
                <select class="filter-select" id="categoryFilter" onchange="filterByCategory()">
                    <option value="">All Categories</option>
                    <?php foreach (array_keys($content_categories) as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bulk-save-btn">
                <i class="fas fa-save"></i>
                Save All Changes
            </button>
        </div>

        <!-- Content Categories -->
        <?php foreach ($content_categories as $category => $items): ?>
            <div class="category-section" data-category="<?php echo htmlspecialchars($category); ?>">
                <div class="category-header">
                    <h2>
                        <i class="fas fa-<?php 
                            echo $category === 'Company Information' ? 'building' : 
                                ($category === 'Contact Details' ? 'phone' : 
                                ($category === 'Hero Section' ? 'star' : 
                                ($category === 'About Section' ? 'info-circle' : 
                                ($category === 'Services' ? 'cogs' : 
                                ($category === 'Social Media' ? 'share-alt' : 'folder')))));
                        ?>"></i>
                        <?php echo htmlspecialchars($category); ?>
                    </h2>
                    <span class="category-badge"><?php echo count($items); ?> items</span>
                </div>

                <div class="content-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="content-item" data-key="<?php echo htmlspecialchars($item['content_key']); ?>">
                            <div class="content-item-header">
                                <label class="content-label" for="<?php echo htmlspecialchars($item['content_key']); ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $item['content_key'])); ?>
                                </label>
                                <span class="content-type-badge"><?php echo htmlspecialchars($item['content_type']); ?></span>
                            </div>

                            <?php if ($item['content_type'] === 'html' || strlen($item['content_value']) > 100): ?>
                                <textarea 
                                    class="content-textarea" 
                                    id="<?php echo htmlspecialchars($item['content_key']); ?>" 
                                    name="content[<?php echo htmlspecialchars($item['content_key']); ?>]"
                                ><?php echo htmlspecialchars($item['content_value']); ?></textarea>
                            <?php elseif ($item['content_type'] === 'image'): ?>
                                <input 
                                    type="text" 
                                    class="content-input" 
                                    id="<?php echo htmlspecialchars($item['content_key']); ?>" 
                                    name="content[<?php echo htmlspecialchars($item['content_key']); ?>]" 
                                    value="<?php echo htmlspecialchars($item['content_value']); ?>"
                                    readonly
                                    placeholder="Upload an image file..."
                                >
                                <div class="file-upload-wrapper">
                                    <input 
                                        type="file" 
                                        class="file-upload-input" 
                                        id="file_<?php echo htmlspecialchars($item['content_key']); ?>"
                                        name="content_files[<?php echo htmlspecialchars($item['content_key']); ?>]"
                                        accept="image/*"
                                        onchange="handleFileSelect(this, '<?php echo htmlspecialchars($item['content_key']); ?>')"
                                    >
                                    <button type="button" class="file-upload-btn" onclick="document.getElementById('file_<?php echo htmlspecialchars($item['content_key']); ?>').click()">
                                        <i class="fas fa-upload"></i>
                                        Choose Image
                                    </button>
                                    <span class="current-file-info" id="fileInfo_<?php echo htmlspecialchars($item['content_key']); ?>" style="display: none;">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="filename"></span>
                                    </span>
                                </div>
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    class="content-input" 
                                    id="<?php echo htmlspecialchars($item['content_key']); ?>" 
                                    name="content[<?php echo htmlspecialchars($item['content_key']); ?>]" 
                                    value="<?php echo htmlspecialchars($item['content_value']); ?>"
                                >
                            <?php endif; ?>

                            <?php if ($item['content_type'] === 'image' && !empty($item['content_value'])): ?>
                                <div class="image-preview" id="preview_<?php echo htmlspecialchars($item['content_key']); ?>">
                                    <img src="<?php echo htmlspecialchars($item['content_value']); ?>" alt="Preview" onerror="this.parentElement.style.display='none'">
                                    <button type="button" class="remove-image-btn" onclick="removeImage('<?php echo htmlspecialchars($item['content_key']); ?>')" title="Remove image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <div class="content-meta">
                                <span><i class="far fa-clock"></i> <?php echo date('M d, Y H:i', strtotime($item['updated_at'])); ?></span>
                                <span><i class="fas fa-key"></i> <?php echo htmlspecialchars($item['content_key']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($content_categories)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No content items found</p>
                <small>Add content items to the database to manage them here.</small>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    // Handle file selection
    function handleFileSelect(input, contentKey) {
        const file = input.files[0];
        if (file) {
            // Show file info
            const fileInfo = document.getElementById('fileInfo_' + contentKey);
            const filename = fileInfo.querySelector('.filename');
            filename.textContent = file.name;
            fileInfo.style.display = 'flex';
            
            // Update the text input with filename
            const textInput = document.getElementById(contentKey);
            textInput.value = 'New file: ' + file.name;
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('preview_' + contentKey);
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'preview_' + contentKey;
                    preview.className = 'image-preview';
                    input.closest('.content-item').querySelector('.file-upload-wrapper').after(preview);
                }
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-image-btn" onclick="removeImage('${contentKey}')" title="Remove image">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
            
            formChanged = true;
        }
    }

    // Remove image
    function removeImage(contentKey) {
        if (confirm('Are you sure you want to remove this image?')) {
            const preview = document.getElementById('preview_' + contentKey);
            const textInput = document.getElementById(contentKey);
            const fileInput = document.getElementById('file_' + contentKey);
            const fileInfo = document.getElementById('fileInfo_' + contentKey);
            
            if (preview) preview.style.display = 'none';
            if (textInput) textInput.value = '';
            if (fileInput) fileInput.value = '';
            if (fileInfo) fileInfo.style.display = 'none';
            
            formChanged = true;
        }
    }

    // Search functionality
    function filterContent() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.content-item');
        
        items.forEach(item => {
            const key = item.dataset.key.toLowerCase();
            const label = item.querySelector('.content-label').textContent.toLowerCase();
            const input = item.querySelector('.content-input, .content-textarea');
            const value = input ? input.value.toLowerCase() : '';
            
            if (key.includes(searchTerm) || label.includes(searchTerm) || value.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
        
        updateCategoryVisibility();
    }

    // Category filter
    function filterByCategory() {
        const selectedCategory = document.getElementById('categoryFilter').value;
        const sections = document.querySelectorAll('.category-section');
        
        sections.forEach(section => {
            if (!selectedCategory || section.dataset.category === selectedCategory) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });
    }

    // Update category visibility based on visible items
    function updateCategoryVisibility() {
        const sections = document.querySelectorAll('.category-section');
        
        sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.content-item:not([style*="display: none"])');
            if (visibleItems.length === 0) {
                section.style.display = 'none';
            } else {
                section.style.display = '';
            }
        });
    }

    // Form change detection
    let formChanged = false;
    const form = document.getElementById('contentForm');
    const inputs = form.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    // Warn before leaving if changes not saved
    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Reset flag on form submit
    form.addEventListener('submit', () => {
        formChanged = false;
    });

    // Auto-save draft to localStorage
    function saveDraft() {
        const formData = new FormData(form);
        const draft = {};
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('content[')) {
                draft[key] = value;
            }
        }
        localStorage.setItem('contentDraft', JSON.stringify(draft));
    }

    // Save draft every 30 seconds
    setInterval(saveDraft, 30000);
</script>

<?php $conn->close(); ?>
