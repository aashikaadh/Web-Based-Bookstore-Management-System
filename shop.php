<?php
include 'config.php';
session_start();

// User login check
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Initialize session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_POST['add_to_cart'])) {

    $book_id = $_POST['book_id'];       // New
    $name = $_POST['name'];
    $price = (int) $_POST['price'];
    $quantity = (int) $_POST['quantity'];
    $image = $_POST['image'];

    // Session Cart
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$book_id] = [
            'book_id' => $book_id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image
        ];
    }

    // Database Cart
    $check = mysqli_query($conn, "SELECT * FROM carts WHERE user_id='$user_id' AND book_id='$book_id'");
    
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "UPDATE carts SET quantity = quantity + $quantity WHERE user_id='$user_id' AND book_id='$book_id'");
    } else {
        mysqli_query($conn, "INSERT INTO carts(user_id, book_id, name, price, quantity, image) 
                            VALUES('$user_id', '$book_id', '$name', '$price', '$quantity', '$image')");
    }

    header("Location: cart.php");  // Redirect to cart page
    exit();
}


// Fetch books from database
$result = mysqli_query($conn, "SELECT * FROM book") or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Page</title>
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

<div class="books-container">
    <h2 class="section-title">Available Books</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($book = mysqli_fetch_assoc($result)): ?>
            <div class="book-card">
                <img src="uploads/<?= $book['image'] ?>" alt="<?= $book['title'] ?>">
                <h3><?= $book['title'] ?></h3>
                <p><b>Author:</b> <?= $book['author'] ?></p>
                <p><b>Price:</b> Rs <?= $book['price'] ?></p>
                <p><?= $book['description'] ?></p>

                <form  method="POST">
                     <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
    <input type="hidden" name="name" value="<?= $book['title'] ?>">
    <input type="hidden" name="price" value="<?= $book['price'] ?>">
    <input type="hidden" name="quantity" value="1">
    <input type="hidden" name="image" value="<?= $book['image'] ?>">
    <button type="submit" name="add_to_cart" class="add-cart-btn">
        Add to Cart
    </button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="empty">No books available</p>
    <?php endif; ?>
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


<script>
const userBtn = document.getElementById('user_btn');
const accBox = document.getElementById('header_acc_box');

userBtn.addEventListener('click', function () {
    accBox.classList.toggle('active');
});
</script>

</body>
</html>
