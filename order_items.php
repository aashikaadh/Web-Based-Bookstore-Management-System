<?php
session_start();
include 'config.php';

// Admin check
if(!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin'){
    header("Location: login.php");
    exit();
}

// Fetch all order items with book title
$order_items_query = mysqli_query($conn, "
    SELECT oi.order_item_id, oi.order_id, oi.book_id, b.title AS book_title, oi.price, oi.quantity
    FROM order_items oi
    JOIN book b ON oi.book_id = b.id
    ORDER BY oi.order_item_id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="admin_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{font-family:Arial,sans-serif;}
        .orders_cont{width:90%; margin:auto; margin-top:30px;}
        .orders_box table{width:100%; border-collapse: collapse;}
        .orders_box table th, .orders_box table td{border:1px solid #ccc; padding:8px; text-align:center;}
        .orders_box table th{background:#f2f2f2;}
        .orders_box{padding:10px; margin-bottom:20px;}
        .empty{text-align:center; color:red; font-weight:bold; margin-top:50px;}
        .header_acc_box p{margin:0;}
    </style>
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
            <a href="order_items.php">Order Items</a>
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

<h1 style="text-align:center; color:red; margin-top:20px;">Order Items</h1>

<div class="orders_cont">
<?php
if(mysqli_num_rows($order_items_query) > 0){
    echo "<div class='orders_box'>";
    echo "<table class='table table-bordered'>
            <tr>
                <th>Order Item ID</th>
                <th>Order ID</th>
                <th>Book ID</th>
                <th>Book Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>";
    while($item = mysqli_fetch_assoc($order_items_query)){
        $total = $item['price'] * $item['quantity'];
        echo "<tr>
                <td>".$item['order_item_id']."</td>
                <td>".$item['order_id']."</td>
                <td>".$item['book_id']."</td>
                <td>".$item['book_title']."</td>
                <td>Rs ".$item['price']."</td>
                <td>".$item['quantity']."</td>
                <td>Rs ".$total."</td>
              </tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "<p class='empty'>No order items found!</p>";
}
?>
</div>

<footer class="footer">
    <div class="footer-social">
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://instagram.com" target="_blank" class="instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://twitter.com" target="_blank" class="twitter"><i class="fab fa-twitter"></i></a>
            <a href="https://youtube.com" target="_blank" class="youtube"><i class="fab fa-youtube"></i></a>
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