<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location:login.php');
}


if (isset($_POST['add_to_cart'])) {

    $book_id = $_POST['book_id'];
    $name    = $_POST['name'];
    $price   = $_POST['price'];
    $image   = $_POST['image'];
    $quantity = $_POST['quantity'];
    
    $check_cart_numbers = mysqli_query(
        $conn,
        "SELECT * FROM carts WHERE book_id='$book_id' AND user_id='$user_id'"
    );

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query(
            $conn,
            "INSERT INTO carts(user_id, book_id, name, price, quantity, image) 
             VALUES('$user_id', '$book_id', '$name', '$price', '$quantity', '$image')"
        );
        $message[] = 'Product added to cart!';
    }

    // Session cart
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][$book_id] = [
        'name' => $name,
        'price' => $price,
        'image' => $image,
        'quantity' => $quantity
    ];

    header("Location: search_page.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="user_page.css">
<style>
    /*Search*/
    .empty {
    margin: 100px auto;
    padding: 15px 30px;
    font-size: 22px;
    font-weight: bold;
    color: #C1121F;
    background: #fff3f3;
    border-radius: 10px;
    width: fit-content;
    text-align: center;
    
}
.search_cont{
  display: flex;
  flex-direction: column;
  gap: 2rem;
  justify-content: center;
  align-items: center;
}
.search_cont form{
  width: 80%;
  display: flex;
  margin: 2rem;
}
.search_cont form input[type='text']{
  width: 80%;
  padding: 0.2rem;
  font-size: 1rem;
  box-shadow: 2px 2px 10px gray;
  border: none;
  margin-right: 1rem;
}
.product_btn{
  padding: 10px 25px;
  font-size: 16px;
  font-weight: 600;
  background: #e78547ff;   /* purple */
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.product_btn:hover{
  background: #56d6d6ff;
  transform: scale(1.05);
}


    </style>


</head>

<body>

 <div class="navbar">
<ul>
  <div class="logo-cont">
     <img src="book_logo.png" alt="">

        <a href="user_page.php" class="book_logo">BooksHeaven</a>
  </div>
  <li><a class="active" href="user_page.php">Home</a></li>
  <li><a href="about.php">About</a></li>
  <li><a href="shop.php">Shop</a></li>
   <li><a href="contact.php">Contact</a></li>
   <li><a href="order.php">Order</a></li>
   
    <li>

      <div class="last_part">
        <div class="loginorreg">
          <p style="color:white; font-weight: bold;">New <a href="index.php">Login | Register</a> </p>
        </div>

        <div class="icons">
       <!-- /* <i class="fa-solid fa-magnifying-glass"></i> */ -->
          <a class="fa-solid fa-magnifying-glass" href="search_page.php"></a>

          <div class="fas fa-user" id="user_btn"></div>
          <?php
          $select_cart_number=mysqli_query($conn,"SELECT * FROM `carts` where user_id='$user_id'") or die('query failed');
          $cart_row_number=mysqli_num_rows($select_cart_number);
          ?>

      <a href="cart.php"><i class="fas fa-shopping-cart"></i> 
    <span class="quantity">(<?php echo $cart_row_number; ?>)</span>
</a>


        </div>

</div>
      <div class="header_acc_box" id="header_acc_box">
        <p>name: <span><?php echo $_SESSION['name'];?></span></p>
        <p>email: <span><?php echo $_SESSION['email'];?></span></p>
        <a href="logout.php" class="delete-btn">Logout</a>
      </div>

</ul>
</div>  

  <section class="search_cont">
    <form action="" method="post">
      <input type="text" name="search" placeholder="Search Products......">
      <input type="submit" value="Search" name="submit" class="product_btn">
    </form>
  </section>

 <section class="products_cont">
  <div class="pro_box_cont">
    <?php
    if (isset($_POST['submit'])) {

      $search_item = mysqli_real_escape_string($conn, $_POST['search']);

      $select_products = mysqli_query(
        $conn,
        "SELECT * FROM `book` WHERE title LIKE '%$search_item%'"
      ) or die('query failed');

      if (mysqli_num_rows($select_products) > 0) {

        echo '<h2 class="section-title">Available Books</h2>';

        while ($book = mysqli_fetch_assoc($select_products)) {
    ?>
          <div class="book-card">
            <img src="uploads/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>">

            <h3><?php echo $book['title']; ?></h3>
            <p><b>Author:</b> <?php echo $book['author']; ?></p>
            <p><b>Price:</b> Rs <?php echo $book['price']; ?></p>
            <p><?php echo $book['description']; ?></p>

            <!-- Add to Cart -->
            <form action="search_page.php" method="POST">
              <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
              <input type="hidden" name="name" value="<?php echo $book['title']; ?>">
              <input type="hidden" name="price" value="<?php echo $book['price']; ?>">
              <input type="hidden" name="image" value="<?php echo $book['image']; ?>">
              <input type="hidden" name="quantity" value="1">

              <button type="submit" name="add_to_cart" class="add-cart-btn">
                Add to Cart
              </button>
            </form>
          </div>
    <?php
        }
      } else {
        echo '<p class="empty">No result found!</p>';
      }
    } else {
      echo '<p class="empty">Search Something!</p>';
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
const userBtn = document.getElementById('user_btn');
const accBox = document.getElementById('header_acc_box');

userBtn.addEventListener('click', function () {
    accBox.classList.toggle('active');
});
</script>

</body>

</html>