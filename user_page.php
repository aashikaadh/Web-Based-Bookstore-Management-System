 
<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}
$result = $conn->query("SELECT * FROM book");
?>
<!DOCTYPE html>
<html>
<head>
  <title>User Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="user_page.css">
   
</head>

 <body style="background: #a7ddecff;">
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
          <p style="color:white;  font-weight: bold;">New <a href="login.php">Login | Register</a> </p>
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
<!-- /*Welcome Carousel with Overlay */ -->
<div class="welcome-carousel-container">
  <div id="welcomeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="1.webp" class="d-block w-100" style="height:350px; object-fit:cover;">
      </div>
      <div class="carousel-item">
        <img src="2.webp" class="d-block w-100" style="height:350px; object-fit:cover;">
      </div>
      <div class="carousel-item">
        <img src="3.jpg" class="d-block w-100" style="height:350px; object-fit:cover;">
      </div>
      <div class="carousel-item">
        <img src="4.jpg" class="d-block w-100" style="height:350px; object-fit:cover;">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
  <div class="carousel-overlay">
    <h1>Welcome! <span><?= $_SESSION['name']; ?></span> Online BookStore</h1>
    <p>Explore, Discover and Buy Your Favourite Books</p>
     <button class="discover-btn" onclick="window.location.href='shop.php';" >Discover More</button>
  </div>
</div>

   <div class="books-container">
   <h2 class="section-title">Available Books</h2>
    <?php while($book = $result->fetch_assoc()): ?>
      <div class="book-card">
        <img src="uploads/<?= $book['image'] ?>" alt="<?= $book['title'] ?>">
        <h3><?= $book['title'] ?></h3>
        <p><b>Author:</b> <?= $book['author'] ?></p>
        <p><b>Price:</b> Rs <?= $book['price'] ?></p>
        <p><?= $book['description'] ?></p>


  <!-- Add to Cart Button -->
<form action="add_to_cart.php" method="POST">
    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
    <input type="hidden" name="title" value="<?= $book['title'] ?>">
    <input type="hidden" name="price" value="<?= $book['price'] ?>">
    <input type="hidden" name="image" value="<?= $book['image'] ?>">

    <button type="submit" class="add-cart-btn">Add to Cart</button>
</form>

      </div>


    <?php endwhile; ?>
  </div> 

    <section class="about_cont">
    <img src="5.jpg" alt="">
    <div class="about_descript">
      <h2>Discover Our Story</h2>
      <p>At online bookstore, we are passionate about connecting readers with captivating stories, inspiring ideas, and a world of knowledge. Our bookstore is more than just a place to buy books; it's a haven for book enthusiasts, where the love for literature thrives.
    </p>
    <button class="discover-btn" onclick="window.location.href='about.php';">Read More</button>
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


