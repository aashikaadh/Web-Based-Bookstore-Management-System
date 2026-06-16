<!--  
// session_start();
// include 'config.php';

// $user_id = $_SESSION['user_id'] ?? null;
// if (!$user_id) {
//     header("Location: login.php");
//     exit();
// }

// if (!isset($_SESSION['cart'])) {
//     $_SESSION['cart'] = [];
// }

// if (isset($_POST['book_id'])) {

//     $book_id = $_POST['book_id'];
//     $name    = $_POST['title'];
//     $price   = $_POST['price'];
//     $image   = $_POST['image'];

//     if (isset($_SESSION['cart'][$book_id])) {
//         $_SESSION['cart'][$book_id]['quantity'] += 1;
//     } else {
//         $_SESSION['cart'][$book_id] = [
//             'name' => $name,
//             'price' => $price,
//             'image' => $image,
//             'quantity' => 1
//         ];
//     } -->

<!-- //     header("Location: cart.php");
//     exit();
// } -->
 
<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Init session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['book_id'])) {

    $book_id = $_POST['book_id'];
    $name    = $_POST['title'];
    $price   = $_POST['price'];
    $image   = $_POST['image'];
    $quantity = 1;

    // ===== SESSION CART =====
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$book_id] = [
             'book_id' => $book_id,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'quantity' => $quantity
        ];
    }

    // ===== DATABASE CART =====
    // Check if already in database
    $check_cart = mysqli_query($conn, "SELECT * FROM carts WHERE book_id='$book_id' AND user_id='$user_id'");
    
    if (mysqli_num_rows($check_cart) > 0) {
        // Already exists, update quantity
        mysqli_query($conn, "UPDATE carts SET quantity = quantity + 1 WHERE book_id='$book_id' AND user_id='$user_id'");
    } else {
        // Insert new
        mysqli_query($conn, "INSERT INTO carts(user_id, book_id, name, price, quantity, image) 
                             VALUES('$user_id','$book_id','$name','$price','$quantity','$image')");
    }

    header("Location: cart.php");
    exit();
}
?>


