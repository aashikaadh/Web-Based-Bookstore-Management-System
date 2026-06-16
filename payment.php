<?php
session_start();

if(!isset($_SESSION['order_data'])){
    header("Location: checkout.php");
    exit();
}

$order = $_SESSION['order_data'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>eSewa Payment</title>

    <style>
        body{
            font-family: Arial;
            text-align:center;
            margin-top:50px;
        }

        .box{
            width:400px;
            margin:auto;
            border:1px solid #ddd;
            padding:30px;
            box-shadow:0 0 10px gray;
        }

        .pay-btn{
            background:#60BB46;
            color:white;
            border:none;
            padding:12px 25px;
            cursor:pointer;
            font-size:18px;
        }

        img{
            width:250px;
        }
    </style>
</head>

<body>

<div class="box">

    <h2 style="color:#60BB46;">eSewa Payment Gateway</h2>

    <p><strong>Amount:</strong>
        Rs. <?= $order['total_price']; ?>
    </p>

    <h3>Scan QR Code</h3>

    <!-- Fake QR -->
    <img src="uploads/esewa_qr.jpeg" alt="QR Code">

    <p>Merchant: BooksHeaven Store</p>

    <form action="payment_success.php" method="post">

        <button class="pay-btn" type="submit">
            Payment Completed
        </button>

    </form>

</div>

</body>
</html>