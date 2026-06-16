
<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;

/* ===== DELETE ALL CART ===== */
if (isset($_GET['delete_all'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

/* ===== INIT CART ===== */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ===== QUANTITY ADD / SUB ===== */
if (isset($_GET['id']) && isset($_GET['action'])) {

    $id = $_GET['id'];
    $action = $_GET['action'];

    if (isset($_SESSION['cart'][$id])) {

        if ($action === 'plus') {
            $_SESSION['cart'][$id]['quantity'] += 1;
        }

        if ($action === 'minus') {
            $_SESSION['cart'][$id]['quantity'] -= 1;

            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    header("Location: cart.php");
    exit();
}

/* ===== LOAD CART ===== */
$cart = $_SESSION['cart'];
?>


<!DOCTYPE html>
<html>
<head>
<title>My Cart</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user_page.css">
<style>
   body{
    background-color: #f1e9f3ff;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    table-layout: fixed; /* ensures proper column widths */
}

th {
    background-color: #003049;
    color: white;
    padding: 12px;
    text-align: center;
}

td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
    word-wrap: break-word;
}

tr:hover {
    background-color: #f5f5f5;
}

.remove-btn {
    background: #d62828;
    color: white;
    padding: 7px 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.remove-btn:hover {
    background: #9b1c1c;
}
  


.cart_total{
  background-color: #669bbc;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.btns_cart{
  width: 100%;
  text-align:center;
}

.btns_cart a{
  width: 20%;
  margin-bottom: 1rem;
}
.btns_cart a:nth-child(1){
  background-color: #003049;
}
.btns_cart a:nth-child(3){
  background-color: white;
  color: black;
  font-weight: 900;
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
          <p style="color:white;font-weight: bold;">New <a href="login.php">Login | Register</a> </p>
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
<h1 style="text-align:center; font-weight:bold; color:red;">My Cart</h1>

<table>
<tr>
     <th>Book Image</th>
    <th>Book Title</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Action</th>
</tr>
<?php 
$grand_total = 0;

foreach ($cart as $id => $item):
    $price = (float) $item['price'];
    $quantity = (int) $item['quantity'];
    $total = $price * $quantity;
    $grand_total += $total;
?>
<tr>
    <td><img src="uploads/<?= $item['image'] ?>" width="70" height="90"></td>
    <td><?= $item['name'] ?></td>
    <td>Rs <?= number_format($price, 2) ?></td>
<td>
  <a href="cart.php?id=<?= $id ?>&action=minus"
     class="btn btn-sm btn-danger">−</a>

  <span style="margin:0 10px; font-weight:bold;">
    <?= $quantity ?>
  </span>

  <a href="cart.php?id=<?= $id ?>&action=plus"
     class="btn btn-sm btn-success">+</a>
</td>

    <td>Rs <?= number_format($total, 2) ?></td>
    <td>
        <a href="remove_cart.php?id=<?= $id ?>">
            <button class="remove-btn">Remove</button>
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

  </div>

  <div class="cart_total">
    <h2 style="text-align:center;">Total Cart Price : <span>Rs <?php echo $grand_total;?>/-</span></h2>
    <div class="btns_cart">
    <a href="cart.php?delete_all" class="discover-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Are you sure you want to delete all cart items from cart?');">Delete All</a>
      <a href="shop.php" class="discover-btn">Continue Shopping</a>
      <a href="checkout.php" class="discover-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Checkout</a>
    </div>
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
