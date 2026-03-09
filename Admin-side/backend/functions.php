<?php

// fetch all the contents
function getAllContent($conn) {
  $sql = "SELECT * FROM cms_content ORDER BY created_at DESC";

  return $conn->query($sql);
}

// save content on the db (cms_content)
function saveContent($conn, $title, $slug, $body, $author_id) {
    $sql = "INSERT INTO cms_content (title, slug, body, author_id, status) 
            VALUES (?, ?, ?, ?, 'Published')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $slug, $body, $author_id);
    
    if ($stmt->execute()) {
        return true; // Success
    } else {
        return false; // Failure
    }
}

// deletes content from the db
function deleteContent($conn, $id) {
    // 1. Prepare the statement
    $stmt = $conn->prepare("DELETE FROM cms_content WHERE id = ?");
    
    // 2. Bind the ID as an integer ("i")
    $stmt->bind_param("i", $id);
    
    // 3. Execute and return status
    return $stmt->execute();
}

// edit contents from the db
function editContent($conn, $id, $title, $body) {
    // 1. Prepare the update statement
    $stmt = $conn->prepare("UPDATE cms_content SET title = ?, body = ? WHERE id = ?");
    
    // 2. Bind parameters (string, string, integer)
    $stmt->bind_param("ssi", $title, $body, $id);
    
    // 3. Execute
    return $stmt->execute();
}

?>