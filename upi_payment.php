<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if(!isset($_GET['order_id'])){
header("Location: index.php");
exit();
}

$order_id = $_GET['order_id'];
?>

<div class="container mt-5 mb-5">

<div class="card shadow p-4 text-center">

<h3 class="mb-4">Complete Your Payment</h3>

<p>Scan this QR using any UPI app</p>

<img src="assets/images/upi_qr.jpeg" width="220">

<p class="mt-3">After payment click confirm</p>

<a href="order_success.php?order_id=<?php echo $order_id; ?>" 
class="btn btn-success mt-3">

I Have Paid

</a>

</div>

</div>

<?php include 'includes/footer.php'; ?>