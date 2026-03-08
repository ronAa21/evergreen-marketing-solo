<?php 
  // import backend
  require_once '../backend/functions.php';
  require_once '../../db_connect.php';

  session_start();

  // If the session variable is not set OR they aren't an admin, kick them out
  if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
      header("Location: ../../login.php?error=unauthorized");
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Admin</title>
</head>
<body>
  <nav>
    <h1>Content Management</h1>
    <a href="../backend/logout.php" id="logout" >Logout</a>
  </nav>
  <button id="add-content">
    Add Content
 </button>

<!-- Shows content  -->
<div class="content-view">
<!-- Place the content here po -->
</div>

<!-- Modal -->
<div class="modal-overlay" style="display: none">
  <div class="modal-popup" id="add-content">
    <button class="close-modal">close</button>
    <form action="../backend/actions.php" method="POST">
        <input type="hidden" name="action" value="create_post">
        
        <label>Title</label>
        <input type="text" id="title" name="title" required>
        
        <label>Content</label>
        <textarea id="body" name="body" id="editor"></textarea>
        
        <button id="publish-content">Publish Post</button>
    </form>
  </div>
</div>
</body>
</html>
<script src="admin-frontendlogic.js"></script>