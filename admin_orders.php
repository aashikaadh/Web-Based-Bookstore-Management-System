<?php
session_start();
include 'config.php';

// Redirect if not admin
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

if(isset($_POST['update_order'])){

  $order_update_id=$_POST['order_id'];
  $update_payment=$_POST['update_payment'];

  mysqli_query($conn,"UPDATE `orderss` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');

  $message[]='Order Payment status has been updated';
}

if(isset($_GET['delete'])){
  $delete_id=$_GET['delete'];
  mysqli_query($conn,"DELETE FROM `orderss` WHERE id='$delete_id'");
  $message[]='1 order has been deleted';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders</title>
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

<section class="admin_orders">
  <h1 class="title">Placed Orders</h1>

  <div class="admin_box_container">
    <?php
      $select_orders=mysqli_query($conn,"SELECT * FROM `orderss`") or die('query failed');

      if(mysqli_num_rows($select_orders)>0){
        while($fetch_orders=mysqli_fetch_assoc($select_orders)){

    ?>
    <div class="admin_box">
      <p>User Id : <span><?php echo $fetch_orders['user_id']?></span></p>
      <p>Placed On : <span><?php echo $fetch_orders['placed_on']?></span></p>
      <p>Name : <span><?php echo $fetch_orders['name']?></span></p>
      <p>Number : <span><?php echo $fetch_orders['number']?></span></p>
      <p>Email : <span><?php echo $fetch_orders['email']?></span></p>
      <p>Address : <span><?php echo $fetch_orders['address']?></span></p>
      <p>Total Products : <span><?php echo $fetch_orders['total_products']?></span></p>
      <p>Total Price : <span><?php echo $fetch_orders['total_price']?></span></p>
      <p>Payment Method : <span><?php echo $fetch_orders['method']?></span></p>

      <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');" class="delete-btn">delete</a>
         </form>
    </div>
    <?php
        }
      }else{
        echo '<p class="empty">No orders placed yet!</p>';
      }
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

    userBtn.addEventListener('click', function () {
        accBox.classList.toggle('active');
    });
});
</script>
</body>
</html>