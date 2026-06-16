<?php
session_start();
include 'config.php';

// Redirect if not admin
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM book");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="admin_page.css">


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
        <!-- <a href="order_items.php">Order Items</a> -->
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

    <!-- Welcome Box -->
    <div class="welcome-box">
        <h1>Welcome, <?= $_SESSION['name']; ?>!</h1>
        <p>This is Our admin dashboard. We can manage all books from here.</p>
    </div>

    <!-- Books Table -->
    <h2 style="text-align:center; color:red; font-weight:bold; margin-top:15px;">Books List</h2>
   <table class="books-table">
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><img src="uploads/<?= $row['image'] ?>" alt="<?= $row['title'] ?>"></td>
        <td><?= $row['title'] ?></td>
        <td><?= $row['author'] ?></td>
        <td>Rs <?= $row['price'] ?></td>
        <td><?= $row['description'] ?></td>
        <td class="action-btns">
                <a href="edit_book.php?id=<?= $row['id'] ?>">Update</a>
               <a class="delete-btn" href="delete_book.php?id=<?= $row['id'] ?>"
   onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
            </td>
    </tr>
    <?php endwhile; ?>
</table>

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


<script>
document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('user_btn');
    const accBox = document.getElementById('header_acc_box');

    userBtn.addEventListener('click', function () {
        accBox.classList.toggle('active');
    });
});
</script>

</body>
</html>






