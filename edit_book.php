<?php
session_start();
require_once 'config.php';

// Check admin access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing book info
$result = $conn->query("SELECT * FROM book WHERE id=$id");
$book = $result->fetch_assoc();

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Image handling (optional)
    if(!empty($_FILES['image']['name'])){
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = "uploads/";
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }
        move_uploaded_file($image_tmp, $upload_dir . $image_name);
        // Update including new image
        $conn->query("UPDATE book SET 
                      title='$title', 
                      author='$author', 
                      price='$price', 
                      description='$description', 
                      image='$image_name' 
                      WHERE id=$id");
    } else {
        // Update without image
        $conn->query("UPDATE book SET 
                      title='$title', 
                      author='$author', 
                      price='$price', 
                      description='$description' 
                      WHERE id=$id");
    }

    header("Location: admin_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Book</title>
  <link rel="stylesheet" href="style.css">
  
</head>
  
<body style="background:#f3fbff;">
  <div class="add-book-container">
  <h2>Update Book Details</h2>

  <form method="post" enctype="multipart/form-data">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?= $book['title'] ?>" required><br><br>

    <label>Author:</label><br>
    <input type="text" name="author" value="<?= $book['author'] ?>" required><br><br>

    <label>Price:</label><br>
    <input type="number" name="price" value="<?= $book['price'] ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" cols="30"><?= $book['description'] ?></textarea><br><br>

    <label>Current Image:</label><br>
    <img src="uploads/<?= $book['image'] ?>" width="100" height="120"><br><br>

    <label>Upload New Image (optional):</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit" name="update">Update Book</button>
  </form>

   <div class="back-link">
      <a href="admin_page.php">← Back to Admin Page</a>
    </div>
</div>
</body>
</html>
