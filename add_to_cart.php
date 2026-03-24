<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user_id'];

$product_id = $_POST['product_id'];
$q = $_POST['quantity'] ?? 1;

/* CUSTOM DESIGN IMAGE */
$image = $_POST['design_image'] ?? "";
$original_image = $_POST['original_image'] ?? "";
/* convert filename to full path */
if($image != ""){
    $image = "assets/uploads/" . $image;
}

/* PRICE */
if(isset($_POST['price'])){
    $price = $_POST['price']; 
}else{
    $result = $conn->query("SELECT price FROM products WHERE id=$product_id");
    $row = $result->fetch_assoc();
    $price = $row['price'];
}

/* INSERT INTO CART */
mysqli_query($conn,"
INSERT INTO cart(user_id,product_id,custom_image,original_image,quantity,price)
VALUES('$user','$product_id','$image','$original_image','$q','$price')
");

header("Location: cart.php");
exit();
?> 