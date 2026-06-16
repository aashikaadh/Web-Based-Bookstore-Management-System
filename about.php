<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if(!isset($_SESSION['email'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page</title>

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

          <a href="cart.php"><i class="fas fa-shopping-cart"></i><span class="quantity">(<?php echo $cart_row_number?>)</span></a>

          <!-- <div class="fas fa-bars" id="user_menu_btn"></div> -->

        </div>

</div>
      <div class="header_acc_box" id="header_acc_box">
        <p>name: <span><?php echo $_SESSION['name'];?></span></p>
        <p>email: <span><?php echo $_SESSION['email'];?></span></p>
        <a href="logout.php" class="delete-btn">Logout</a>
      </div>
      
</ul>
</div>  

<section class="about_cont">
    <img src="about1.jpg" alt="">
    <div class="about_descript">
      <h2>Why Choose Us ?</h2>
      <p>With our extensive collection of books spanning various genres, you'll find the perfect read to satisfy your cravings. Our knowledgeable staff of passionate book enthusiasts is always ready to offer personalized recommendations and guide you toward hidden gems. We take pride in fostering an inclusive community, hosting engaging events, book clubs, and author meet-ups. Additionally, our seamless online presence allows you to browse, explore, and order books from the comfort of your home, ensuring secure transactions and timely deliveries. At Bookiee, customer satisfaction is paramount. We are dedicated to delivering exceptional service, promptly addressing any queries or concerns. Join us in celebrating the power of books to inspire, educate, and entertain. Let us be your trusted companion on your literary adventures.
    </p>
    </div>
  </section>

  <section class="questions_cont">
    <div class="questions">
    <h2>Have Any Queries?</h2>
    <p>At Online bookstore, we value your satisfaction and strive to provide exceptional customer service. If you have any questions, concerns, or inquiries, our dedicated team is here to assist you every step of the way.</p>
    <button class="discover-btn" onclick="window.location.href='contact.php'">Contact Us</button>
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
const userBtn = document.getElementById('user_btn');
const accBox = document.getElementById('header_acc_box');

userBtn.addEventListener('click', function () {
    accBox.classList.toggle('active');
});
</script>

</body>
</html>
