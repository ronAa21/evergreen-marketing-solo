<?php
// Ads Management Page
// This page will handle advertisement management for the Evergreen Bank website

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Create ads table if it doesn't exist
include('db_connect.php');
$create_table_sql = "CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$conn->query($create_table_sql);
$conn->close();
?>

<style>
    .ads-container {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .ads-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .ads-header h2 {
        color: #003631;
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .add-ad-btn {
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 54, 49, 0.2);
    }

    .add-ad-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 54, 49, 0.3);
    }

    .ads-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .ad-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .ad-card:hover {
        border-color: #003631;
        box-shadow: 0 4px 12px rgba(0, 54, 49, 0.1);
    }

    .ad-preview {
        width: 100%;
        height: 150px;
        background: #dee2e6;
        border-radius: 8px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .ad-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ad-info h3 {
        color: #003631;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .ad-info p {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .ad-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-edit, .btn-delete {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #F1B24A;
        color: #003631;
    }

    .btn-edit:hover {
        background: #e69610;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #003631;
        font-size: 20px;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 14px;
        margin-bottom: 20px;
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
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 25px 30px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        color: #003631;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover {
        color: #003631;
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #003631;
        font-weight: 600;
        font-size: 14px;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input[type="text"]:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #003631;
        box-shadow: 0 0 0 3px rgba(0, 54, 49, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-upload-input {
        position: absolute;
        left: -9999px;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 40px 20px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .file-upload-label:hover {
        border-color: #003631;
        background: #e9ecef;
    }

    .file-upload-label i {
        font-size: 32px;
        color: #6c757d;
    }

    .file-upload-text {
        text-align: center;
    }

    .file-upload-text p {
        margin: 5px 0;
        color: #6c757d;
        font-size: 14px;
    }

    .file-upload-text .file-name {
        color: #003631;
        font-weight: 600;
        margin-top: 10px;
    }

    .image-preview {
        margin-top: 15px;
        display: none;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 2px solid #f0f0f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel, .btn-submit {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #e9ecef;
        color: #6c757d;
    }

    .btn-cancel:hover {
        background: #dee2e6;
    }

    .btn-submit {
        background: linear-gradient(135deg, #003631 0%, #005a50 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 54, 49, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 54, 49, 0.3);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.active {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<div class="ads-container">
    <div class="ads-header">
        <h2>
            <i class="fas fa-bullhorn"></i>
            Advertisement Management
        </h2>
        <button class="add-ad-btn" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            Add New Ad
        </button>
    </div>

    <div class="empty-state" id="emptyState">
        <i class="fas fa-ad"></i>
        <h3>No Advertisements Yet</h3>
        <p>Start creating advertisements to display on your website</p>
        <button class="add-ad-btn" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            Create Your First Ad
        </button>
    </div>

    <!-- Ads Grid -->
    <div class="ads-grid" id="adsGrid">
        <!-- Ad cards will be dynamically loaded here -->
    </div>
</div>

<!-- Add/Edit Ad Modal -->
<div id="adModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">
                <i class="fas fa-plus-circle"></i>
                <span id="modalTitleText">Add New Advertisement</span>
            </h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="adForm" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="adId" name="id">
                <input type="hidden" id="formAction" name="action" value="add">
                
                <div class="form-group">
                    <label for="adTitle">Advertisement Title *</label>
                    <input type="text" id="adTitle" name="title" required placeholder="Enter advertisement title">
                </div>
                
                <div class="form-group">
                    <label for="adDescription">Description *</label>
                    <textarea id="adDescription" name="description" required placeholder="Enter advertisement description"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Advertisement Image *</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="adImage" name="image" class="file-upload-input" accept="image/*" onchange="previewImage(event)">
                        <label for="adImage" class="file-upload-label">
                            <div class="file-upload-text">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p><strong>Click to upload</strong> or drag and drop</p>
                                <p style="font-size: 12px;">PNG, JPG, GIF, WEBP (MAX. 5MB)</p>
                                <p class="file-name" id="fileName"></p>
                            </div>
                        </label>
                    </div>
                    <div class="image-preview" id="imagePreview">
                        <img id="previewImg" src="" alt="Preview">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    <span id="submitBtnText">Save Advertisement</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load ads on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAds();
});

// Load all advertisements
function loadAds() {
    fetch('api/ads_handler.php?action=get_all')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAds(data.ads);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Display ads in grid
function displayAds(ads) {
    const adsGrid = document.getElementById('adsGrid');
    const emptyState = document.getElementById('emptyState');
    
    if (ads.length === 0) {
        emptyState.style.display = 'block';
        adsGrid.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        adsGrid.style.display = 'grid';
        
        adsGrid.innerHTML = ads.map(ad => `
            <div class="ad-card">
                <div class="ad-preview">
                    <img src="${ad.image_path}" alt="${ad.title}">
                </div>
                <div class="ad-info">
                    <h3>${ad.title}</h3>
                    <p>${ad.description}</p>
                    <span class="status-badge ${ad.status}">${ad.status}</span>
                </div>
                <div class="ad-actions">
                    <button class="btn-edit" onclick="editAd(${ad.id}, '${ad.title.replace(/'/g, "\\'")}', '${ad.description.replace(/'/g, "\\'")}', '${ad.image_path}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-delete" onclick="deleteAd(${ad.id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `).join('');
    }
}

// Open add modal
function openAddModal() {
    document.getElementById('adModal').style.display = 'block';
    document.getElementById('modalTitleText').textContent = 'Add New Advertisement';
    document.getElementById('formAction').value = 'add';
    document.getElementById('adForm').reset();
    document.getElementById('adId').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('fileName').textContent = '';
    document.getElementById('submitBtnText').textContent = 'Save Advertisement';
}

// Edit ad
function editAd(id, title, description, imagePath) {
    document.getElementById('adModal').style.display = 'block';
    document.getElementById('modalTitleText').textContent = 'Edit Advertisement';
    document.getElementById('formAction').value = 'update';
    document.getElementById('adId').value = id;
    document.getElementById('adTitle').value = title;
    document.getElementById('adDescription').value = description;
    document.getElementById('submitBtnText').textContent = 'Update Advertisement';
    
    // Show current image
    document.getElementById('imagePreview').style.display = 'block';
    document.getElementById('previewImg').src = imagePath;
    document.getElementById('fileName').textContent = 'Current image (optional: upload new image to replace)';
}

// Close modal
function closeModal() {
    document.getElementById('adModal').style.display = 'none';
}

// Preview image
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        document.getElementById('fileName').textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('previewImg').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// Submit form
document.getElementById('adForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = formData.get('action');
    
    // For update, image is optional
    if (action === 'update' && !document.getElementById('adImage').files[0]) {
        formData.delete('image');
    }
    
    fetch('api/ads_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeModal();
            loadAds();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

// Delete ad
function deleteAd(id) {
    if (confirm('Are you sure you want to delete this advertisement?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        
        fetch('api/ads_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadAds();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('adModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
