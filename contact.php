<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;

if(!isset($_SESSION['email'])){
    header('location:login.php');
    exit();
}

$message_sent = '';

if(isset($_POST['send'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $message_content = mysqli_real_escape_string($conn, $_POST['message']);

    $insert_query = mysqli_query($conn, "INSERT INTO `messages` (user_id, name, email, number, message) VALUES ('$user_id', '$name', '$email', '$number', '$message_content')");

    if($insert_query){
        $message_sent = "Message sent successfully!";
    } else {
        $message_sent = "Failed to send message!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Page</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="user_page.css">

</head>
<body>
  
<div class="navbar">
<ul>
  <div class="logo-cont">
     <img src="book_logo.png" alt="">

        <a href="user_page.php" class="book_logo">BooksHeaven</a>
  </div>
   &nbsp;
  <li><a class="active" href="user_page.php">Home</a></li>
  <li><a href="about.php">About</a></li>
  <li><a href="shop.php">Shop</a></li>
   <li><a href="contact.php">Contact</a></li>
   <li><a href="order.php">Order</a></li>
   
    <li>

      <div class="last_part">
        <div class="loginorreg">
          <p style="color:white; font-weight: bold;">New <a href="login.php">Login | Register</a> </p>
        </div>

        <div class="icons">
       <!-- /* <i class="fa-solid fa-magnifying-glass"></i> */ -->
          <a class="fa-solid fa-magnifying-glass" href="search_page.php"></a>

          <div class="fas fa-user" id="user_btn"></div>
          <?php
          $select_cart_number=mysqli_query($conn,"SELECT * FROM `carts` where user_id='$user_id'") or die('query failed');
          $cart_row_number=mysqli_num_rows($select_cart_number);
          ?>

          <a href="cart.php"><i class="fas fa-shopping-cart"></i> <span class="quantity">(<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</span></a>

        </div>

</div>
      <div class="header_acc_box" id="header_acc_box">
        <p>name: <span><?php echo $_SESSION['name'];?></span></p>
        <p>email: <span><?php echo $_SESSION['email'];?></span></p>
        <a href="logout.php" class="delete-btn">Logout</a>
      </div>

</ul>
</div>  

<section class="contact_us">

<form action="" method="post">
   <h2>Contact Us!</h2>
   <input type="text" name="name" required placeholder="Enter your name">
   <input type="email" name="email" required placeholder="Enter your email">
   <input type="phone" name="number" required placeholder="Enter your number">
   <textarea name="message" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
   <input type="submit" value="Send Message" name="send" class="discover-btn">
</form>

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
const userBtn = document.getElementById('user_btn');
const accBox = document.getElementById('header_acc_box');

userBtn.addEventListener('click', function () {
    accBox.classList.toggle('active');
});
</script>
</body>
</html>