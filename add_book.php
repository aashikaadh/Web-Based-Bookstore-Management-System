<?php
session_start();
require_once 'config.php';
if($_SESSION['role'] != 'admin') header("Location: login.php");

if(isset($_POST['add'])){
  $title = $_POST['title'];
  $author = $_POST['author'];
  $price = $_POST['price'];
  $description = $_POST['description'];
 
      // Handle image upload
  $image = $_FILES['image']['name'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $folder = "uploads/";

  if(!is_dir($folder)){
    mkdir($folder, 0777, true);
  }

  move_uploaded_file($tmp_name, $folder . $image);


  $conn->query("INSERT INTO book (title,author,price,description,image) VALUES ('$title','$author','$price','$description','$image')");
  header("Location: admin_page.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Book</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="admin_page.css">
  <style>
 /* Ensure body and html take full height */
body {
    height: 100%;
    margin: 0;
    padding: 0;
}

/* Wrapper to center container */
.add-book-wrapper {
    display: flex;
    justify-content: center;  /* horizontal center */
    align-items: center;      /* vertical center */
    min-height: calc(100vh - 70px); /* adjust 70px to your navbar height */
    padding-top: 30px;        /* space for navbar so container doesn't go under it */
    background-color: #f5f5f5; /* optional background */
}
h2{
  font-weight:bold;
}


    </style>
</head>
<body>
   <header class="admin_header">
    <div class="header_navigation">
      <a href="admin_page.php" class="header_logo">Admin <span>Dashboard</span></a>

      <nav class="header_navbar">
        <a href="admin_page.php">Home</a>
        <a href="add_book.php">Add Books</a>
        <a href="admin_orders.php">Orders</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_messages.php">Messages</a>
      </nav>
      <div class="header_icons">
        <div id="menu_btn" class="fas fa-bars"></div>
        <div id="user_btn" class="fas fa-user"></div>
      </div>
    <div class="header_acc_box" id="header_acc_box">
        <p>name : <span><?php echo $_SESSION['name'];?></span></p>
        <p>email : <span><?php echo $_SESSION['email'];?></span></p>
        <a href="logout.php" class="delete-btn">Logout</a>
      </div>
    </div>
  </header>
<div class="add-book-wrapper">
  <div class="add-book-container">
    <h2>Add New Book</h2>
   <form method="post" enctype="multipart/form-data">

      <input type="text" name="title" placeholder="Title" required>
      <input type="text" name="author" placeholder="Author" required>
      <input type="number" name="price" placeholder="Price" required>
      <textarea name="description" rows="5" cols="20" placeholder="Description"></textarea>
      <input type="file" name="image" accept="image/*" required>
      <button type="submit" name="add">Add Book</button>
    </form>

    <div class="back-link">
      <a href="admin_page.php">← Back to Admin Page</a>
    </div>
</div>
  </div>

  <footer class="footer">
    <div class="footer-social">
  <div class="social-icons">
            <a href="https://facebook.com" target="_blank" class="facebook">
                <i class="fab fa-facebook-f"></i>
            </a>

            <a href="https://instagram.com" target="_blank" class="instagram">
                <i class="fab fa-instagram"></i>
            </a>

            <a href="https://twitter.com" target="_blank" class="twitter">
                <i class="fab fa-twitter"></i>
            </a>

            <a href="https://youtube.com" target="_blank" class="youtube">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
    </div>

    <p>&copy; <?= date("Y") ?> Online BookStore Management System.</p>
</footer>
</body>
</html>