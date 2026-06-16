<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

// Order submit
// if(isset($_POST['order_btn'])){
//     if(!empty($cart)){
//         $name = mysqli_real_escape_string($conn, $_POST['name']);
//         $number = $_POST['number'];
//         $email = mysqli_real_escape_string($conn, $_POST['email']);
//         $method = mysqli_real_escape_string($conn, $_POST['method']);
//         $address = mysqli_real_escape_string($conn, $_POST['address']);


if(isset($_POST['order_btn'])){
    if(!empty($cart)){

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = $_POST['number'];
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        // // Online Pay भए payment page मा पठाउने
        // if($method == "gpay"){

        //     $_SESSION['order_data'] = [
        //         'name' => $name,
        //         'number' => $number,
        //         'email' => $email,
        //         'method' => $method,
        //         'address' => $address
        //     ];

        //     header("Location: payment.php");
        //     exit();
        // }

        if($method == "gpay"){

    $cart_total = 0;
    $cart_products = [];

    foreach($cart as $item){
        $cart_products[] = $item['name'].' ('.$item['quantity'].')';
        $cart_total += $item['price'] * $item['quantity'];
    }

    $_SESSION['order_data'] = [
        'user_id' => $user_id,
        'name' => $name,
        'number' => $number,
        'email' => $email,
        'method' => 'eSewa',
        'address' => $address,
        'total_products' => implode(', ', $cart_products),
        'total_price' => $cart_total,
        'placed_on' => date('Y-m-d')
    ];

    header("Location: payment.php");
    exit();
}
        
        $placed_on = date('Y-m-d');

        $cart_total = 0;
        $cart_products = [];

        foreach($cart as $item){
            $cart_products[] = $item['name'].' ('.$item['quantity'].')';
            $cart_total += $item['price'] * $item['quantity'];
        }

        $total_products = implode(', ', $cart_products);

        mysqli_query($conn, "INSERT INTO orderss
        (user_id, name, number, email, method, address, total_products, total_price, placed_on)
        VALUES
        ('$user_id','$name','$number','$email','$method','$address','$total_products','$cart_total','$placed_on')")
        or die(mysqli_error($conn));

        // ✅ success flag
        $_SESSION['order_success'] = true;

        // redirect
        header("Location: checkout.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout Page</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="user_page.css">
<style>
.display_order{ 
    display: flex; 
    flex-direction: column;
     gap: 1rem; 
     justify-content: center; 
     align-items: center; 
    }
.display_order h2{ 
    color: #C1121F;
     margin-top: 1rem;
     }
.single_order_product{
     width: 50%; 
     display: flex; 
     margin-bottom: 1rem; 
     padding: 1rem; 
     box-shadow: 2px 2px 10px gray; 
     gap: 1rem; 
     align-items: center; 
    }
.single_order_product img{ 
    width: 10%; 
    height: 100%; 
}
.single_order_product .single_des h3{ 
    color: #003049;
 }
.single_order_product .single_des p{
     color: rgb(65, 65, 65); 
    }
.single_order_product .single_des p:nth-child(odd){ 
    color: black;
 }
.checkout_grand_total{ 
    font-size: 1.5rem; 
    color: #C1121F;
     font-weight: 900; 
     letter-spacing: 1px; 
    }
.message{ 
    text-align:center; 
    color:green; 
    margin:10px 0; 
    font-weight:bold;
 }
.empty{ 
    text-align:center; 
    color:red; 
    margin:10px 0; 
    font-weight:bold; 
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
   &nbsp;
  <li><a class="active" href="user_page.php">Home</a></li>
  <li><a href="about.php">About</a></li>
  <li><a href="shop.php">Shop</a></li>
   <li><a href="contact.php">Contact</a></li>
   <li><a href="order.php">Order</a></li>
   
    <li>

      <div class="last_part">
        <div class="loginorreg">
          <p style="color:white; font-weight: bold;">New <a href=".php">Login | Register</a> </p>
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

<?php
if(isset($_SESSION['order_success'])){
    echo '<div class="message">Order placed successfully!</div>';

    unset($_SESSION['cart']);
    unset($_SESSION['order_success']);
}
?>


<section class="display_order">
  <h2>Ordered Products</h2>
  <?php
 $grand_total = 0;
if(!empty($cart)){
    foreach($cart as $item){
        $price = (int) $item['price'];
        $quantity = (int) $item['quantity'];
        $total_price = $price * $quantity;
        $grand_total += $total_price;
?>
<div class="single_order_product">
    <img src="./uploads/<?= $item['image'] ?>" alt="">
    <div class="single_des">
        <h3><?= $item['name'] ?></h3>
        <p>Rs. <?= $price ?></p>
        <p>Quantity: <?= $quantity ?></p>
    </div>
</div>
<?php
    }
} else {
    echo '<p class="empty">Your cart is empty</p>';
}
?>
  <div class="checkout_grand_total"> GRAND TOTAL : <span>Rs <?= $grand_total ?>/-</span> </div>
</section>

<section class="contact_us">
<form action="" method="post">
   <h2>Add Your Details</h2>
   <input type="text" name="name" required placeholder="Enter your name">
   <input type="phone" name="number" required placeholder="Enter your number">
   <input type="email" name="email" required placeholder="Enter your email">
   <select name="method" required>
       <option value="cash on delivery">Cash on Delivery</option>
       <option value="gpay">Online Pay</option>
   </select>
   <textarea name="address" required placeholder="Enter your address" cols="30" rows="10"></textarea>
   <input type="submit" value="Place Your Order" name="order_btn" class="discover-btn">
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
