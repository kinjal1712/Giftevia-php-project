<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT * FROM orders 
WHERE user_id = ?
ORDER BY created_at DESC
");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
.star {
    font-size: 22px;
    cursor: pointer;
    color: #ccc;
}
.star.selected {
    color: gold;
}
</style>

<div class="container mt-5 mb-5">

<h2 class="text-center mb-4">My Orders</h2>

<!-- SUCCESS MESSAGE -->
<?php if(isset($_GET['review']) && $_GET['review']=="success"): ?>
<div class="alert alert-success alert-dismissible fade show text-center">
    🎉 Thank you for your review!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- CANCEL MESSAGE -->
<?php if(isset($_GET['cancel']) && $_GET['cancel']=="done"): ?>
<div class="alert alert-danger text-center">
    ❌ Order cancelled successfully
</div>
<?php endif; ?>


<?php if($result->num_rows > 0): ?>

<?php while($order = $result->fetch_assoc()): ?>

<?php
$status = $order['status'];
$progress = 0;

if($status=="Pending") $progress = 25;
elseif($status=="Processing") $progress = 50;
elseif($status=="Shipped") $progress = 75;
elseif($status=="Delivered") $progress = 100;
elseif($status=="Cancelled") $progress = 0;
?>

<div class="card mb-4 shadow">
<div class="card-body">

<!-- ORDER INFO -->
<div class="row mb-3 align-items-center">

<div class="col-md-2"><strong>#<?php echo $order['id']; ?></strong></div>

<div class="col-md-2">₹<?php echo $order['total_amount']; ?></div>

<div class="col-md-2"><?php echo $order['payment_method']; ?></div>

<div class="col-md-3">
<span class="badge 
<?php
if($status=="Pending") echo "bg-warning";
elseif($status=="Processing") echo "bg-primary";
elseif($status=="Shipped") echo "bg-info";
elseif($status=="Delivered") echo "bg-success";
elseif($status=="Cancelled") echo "bg-danger";
?>">
<?php echo $status; ?>
</span>
</div>

<div class="col-md-3 text-end">

<!-- CANCEL BUTTON -->
<?php if($status == "Pending" || $status == "Processing"): ?>
<a href="cancel_order.php?id=<?php echo $order['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Cancel this order?')">
Cancel
</a>
<?php endif; ?>

</div>

</div>

<!-- PROGRESS BAR -->
<div class="progress mb-4" style="height:8px">
<div class="progress-bar bg-success" style="width:<?php echo $progress ?>%"></div>
</div>

<hr>

<!-- ORDER ITEMS -->
<?php
$items = $conn->prepare("
SELECT order_items.*, products.name, products.image
FROM order_items
JOIN products ON order_items.product_id = products.id
WHERE order_items.order_id = ?
");
$items->bind_param("i",$order['id']);
$items->execute();
$items_result = $items->get_result();
?>

<?php while($item = $items_result->fetch_assoc()): ?>

<div class="row align-items-center mb-3">

<div class="col-md-3"><strong><?php echo $item['name']; ?></strong></div>

<div class="col-md-2">Qty: <?php echo $item['quantity']; ?></div>

<div class="col-md-2">₹<?php echo $item['price']; ?></div>

<div class="col-md-2">

<?php if(!empty($item['custom_image'])): ?>
<img src="<?php echo $item['custom_image']; ?>" width="70">
<?php else: ?>
<img src="assets/images/<?php echo $item['image']; ?>" width="70">
<?php endif; ?>

</div>

<div class="col-md-3 text-end">

<?php if($status == "Delivered"): ?>

<?php
$checkReview = $conn->prepare("
SELECT id FROM reviews WHERE user_id=? AND product_id=?
");
$checkReview->bind_param("ii",$user_id,$item['product_id']);
$checkReview->execute();
$res = $checkReview->get_result();
?>

<?php if($res->num_rows > 0): ?>

<button class="btn btn-outline-primary btn-sm openReviewModal"
data-product="<?php echo $item['product_id']; ?>">
Edit Review
</button>

<span style="font-size:12px;color:green;">✔ Reviewed</span>

<?php else: ?>

<button class="btn btn-success btn-sm openReviewModal"
data-product="<?php echo $item['product_id']; ?>">
Write Review
</button>

<?php endif; ?>

<?php endif; ?>

</div>

</div>

<?php endwhile; ?>

</div>
</div>

<?php endwhile; ?>
<?php else: ?>

<div class="alert alert-info text-center">
No orders found.
</div>

<?php endif; ?>

</div>


<!-- REVIEW MODAL -->
<div class="modal fade" id="reviewModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Write Review</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST" action="submit_review.php">

<input type="hidden" name="product_id" id="product_id">
<input type="hidden" name="rating" id="rating">

<div class="mb-3">
<span class="star">★</span>
<span class="star">★</span>
<span class="star">★</span>
<span class="star">★</span>
<span class="star">★</span>
</div>

<textarea name="review" class="form-control mb-3" required></textarea>

<button class="btn btn-success">Submit</button>

</form>

</div>
</div>
</div>
</div>

<?php include 'includes/footer.php'; ?>

<script>

// OPEN MODAL
document.querySelectorAll(".openReviewModal").forEach(btn=>{
btn.addEventListener("click",function(){

document.getElementById("product_id").value=this.dataset.product;
document.getElementById("rating").value="";

document.querySelectorAll(".star").forEach(s=>s.classList.remove("selected"));

new bootstrap.Modal(document.getElementById('reviewModal')).show();

});
});

// STAR CLICK
document.querySelectorAll(".star").forEach((star,index)=>{
star.addEventListener("click",function(){

document.getElementById("rating").value=index+1;

document.querySelectorAll(".star").forEach(s=>s.classList.remove("selected"));

for(let i=0;i<=index;i++){
document.querySelectorAll(".star")[i].classList.add("selected");
}

});
});

setTimeout(() => {
    let msg = document.querySelector(".alert-success");
    if(msg){
        msg.style.display = "none";
    }
}, 5000);

</script>