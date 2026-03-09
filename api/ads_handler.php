<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include('../db_connect.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Get all ads
if ($action === 'get_all') {
    $sql = "SELECT * FROM advertisements ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $ads = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ads[] = $row;
        }
    }
    
    echo json_encode(['success' => true, 'ads' => $ads]);
    exit;
}

// Add new ad
if ($action === 'add') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($title) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'Title and description are required']);
        exit;
    }
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!in_array(strtolower($filetype), $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP allowed']);
            exit;
        }
        
        // Create uploads directory if it doesn't exist
        $upload_dir = '../uploads/ads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $new_filename = 'ad_' . time() . '_' . uniqid() . '.' . $filetype;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = 'uploads/ads/' . $new_filename;
            
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO advertisements (title, description, image_path, status) VALUES (?, ?, ?, 'active')");
            $stmt->bind_param("sss", $title, $description, $image_path);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Advertisement added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image is required']);
    }
    exit;
}

// Update ad
if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($id) || empty($title) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'ID, title and description are required']);
        exit;
    }
    
    // Check if new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!in_array(strtolower($filetype), $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }
        
        $upload_dir = '../uploads/ads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $new_filename = 'ad_' . time() . '_' . uniqid() . '.' . $filetype;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = 'uploads/ads/' . $new_filename;
            
            // Delete old image
            $old_image_query = $conn->query("SELECT image_path FROM advertisements WHERE id = $id");
            if ($old_image_query && $old_row = $old_image_query->fetch_assoc()) {
                $old_image = '../' . $old_row['image_path'];
                if (file_exists($old_image)) {
                    unlink($old_image);
                }
            }
            
            // Update with new image
            $stmt = $conn->prepare("UPDATE advertisements SET title = ?, description = ?, image_path = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $description, $image_path, $id);
        }
    } else {
        // Update without changing image
        $stmt = $conn->prepare("UPDATE advertisements SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Advertisement updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
    $stmt->close();
    exit;
}

// Delete ad
if ($action === 'delete') {
    $id = $_POST['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        exit;
    }
    
    // Get image path before deleting
    $image_query = $conn->query("SELECT image_path FROM advertisements WHERE id = $id");
    if ($image_query && $row = $image_query->fetch_assoc()) {
        $image_path = '../' . $row['image_path'];
        
        // Delete from database
        $delete_query = $conn->query("DELETE FROM advertisements WHERE id = $id");
        
        if ($delete_query) {
            // Delete image file
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            echo json_encode(['success' => true, 'message' => 'Advertisement deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Advertisement not found']);
    }
    exit;
}

// Toggle status
if ($action === 'toggle_status') {
    $id = $_POST['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        exit;
    }
    
    $sql = "UPDATE advertisements SET status = IF(status = 'active', 'inactive', 'active') WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
$conn->close();
?>
