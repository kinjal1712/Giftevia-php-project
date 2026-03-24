<?php
session_start();
include 'includes/db.php';

if(isset($_POST['cart_id'])){

$cart_id = $_POST['cart_id'];
$qty = $_POST['quantity'];

if($qty < 1){
$qty = 1;
}

$stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
$stmt->bind_param("ii",$qty,$cart_id);
$stmt->execute();

}

header("Location: cart.php");
exit();
?>