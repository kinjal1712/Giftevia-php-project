<?php
session_start();
include("includes/db.php");

/* CHECK LOGIN */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* CHECK CART EMPTY */
$check_cart = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$user_id'");

if(mysqli_num_rows($check_cart) == 0){
    header("Location: cart.php");
    exit();
}

/* GET FORM DATA */
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$payment = $_POST['payment_method'];
$coupon = trim($_POST['coupon_code']);

/* CALCULATE CART TOTAL */
$total = 0;

$cart_items = mysqli_query($conn,"
SELECT cart.*, products.price
FROM cart
JOIN products ON cart.product_id = products.id
WHERE cart.user_id='$user_id'
");

while($row = mysqli_fetch_assoc($cart_items)){
    $total += $row['price'] * $row['quantity'];
}

/* APPLY COUPON */
$discount = 0;

if(strtoupper($coupon) == "LOVE20"){
    $discount = ($total * 20) / 100;
}

$final_total = $total - $discount;

/* CREATE ORDER (PENDING DEFAULT) */
mysqli_query($conn,"
INSERT INTO orders
(user_id,full_name,phone,address,total_amount,payment_method,status)
VALUES
('$user_id','$fullname','$phone','$address','$final_total','$payment','Pending')
");

$order_id = mysqli_insert_id($conn);

/* INSERT ORDER ITEMS */
$cart_products = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$user_id'");

while($item = mysqli_fetch_assoc($cart_products)){

    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $custom_image = $item['custom_image'];
    $original_image = $item['original_image'];

    mysqli_query($conn,"
    INSERT INTO order_items
    (order_id,product_id,quantity,price,custom_image,original_image)
    VALUES
    ('$order_id','$product_id','$quantity','$price','$custom_image','$original_image')
    ");
}

/* CLEAR CART */
mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");


/* ================= PAYMENT FLOW ================= */

if($payment == "STRIPE"){

    // 🔥 Redirect to Stripe (NO NEW ORDER)
    echo "
    <form id='stripeForm' action='create_checkout.php' method='POST'>
        <input type='hidden' name='order_id' value='$order_id'>
        <input type='hidden' name='amount' value='$final_total'>
        <input type='hidden' name='coupon_code' value='$coupon'>
    </form>

    <script>
        document.getElementById('stripeForm').submit();
    </script>
    ";
    exit();
}


/* UPI FLOW */
if($payment == "UPI"){
    header("Location: upi_payment.php?order_id=".$order_id."&coupon=".$coupon);
}

/* COD FLOW */
else{
    header("Location: order_success.php?order_id=".$order_id."&coupon=".$coupon);
}

exit();
?>