
<?php
session_start();
include 'config.php';

// Redirect if not admin
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
if(isset($_GET['delete'])){
  $delete_id=$_GET['delete'];
  mysqli_query($conn,"DELETE FROM `userss` WHERE id='$delete_id'");
  $message[]='1 user has been deleted';
  header("location:admin_users.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

<section class="admin_users">
  <div class="admin_box_container">
    <?php
      $select_users = mysqli_query($conn,"SELECT * FROM `userss` WHERE role='user'");


      while($fetch_users=mysqli_fetch_assoc($select_users)){

    ?>
    <div class="admin_box">
      <p>name : <span><?php echo $fetch_users['name']?></span></p>
      <p>email : <span><?php echo $fetch_users['email']?></span></p>
      <p>role : <span style="color:<?php if($fetch_users['role']=='admin'){echo 'blue';}else{echo 'green';}?>"><?php echo $fetch_users['role']?></span></p>
      <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="delete-btn">delete</a>
    </div>
      <?php
        };
      ?>
    
  </div>
</section>

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

    userBtn.addEventListener('click', function (e) {
        accBox.classList.toggle('active');
    });

    // Optional: click outside to hide
    document.addEventListener('click', function(e){
        if(accBox.classList.contains('active') && !accBox.contains(e.target) && e.target !== userBtn){
            accBox.classList.remove('active');
        }
    });
});

</script>

</body>
</html>
