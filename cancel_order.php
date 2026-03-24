<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: my_orders.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// UPDATE ONLY IF PENDING OR PROCESSING
$stmt = $conn->prepare("
    UPDATE orders 
    SET status='Cancelled'
    WHERE id=? AND user_id=? AND (status='Pending' OR status='Processing')
");

$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();

header("Location: my_orders.php?cancel=done");
exit();
?>