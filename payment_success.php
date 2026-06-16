<?php
session_start();
include 'config.php';

if(!isset($_SESSION['order_data'])){
    header("Location: checkout.php");
    exit();
}

$order = $_SESSION['order_data'];

mysqli_query($conn,"INSERT INTO orderss
(user_id,name,number,email,method,address,total_products,total_price,placed_on)

VALUES(

'{$order['user_id']}',
'{$order['name']}',
'{$order['number']}',
'{$order['email']}',
'{$order['method']}',
'{$order['address']}',
'{$order['total_products']}',
'{$order['total_price']}',
'{$order['placed_on']}'

)") or die(mysqli_error($conn));

unset($_SESSION['cart']);
unset($_SESSION['order_data']);

header("Location: order_confirmed.php");
exit();
?>