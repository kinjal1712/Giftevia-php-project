<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if(!isset($_GET['id'])){
header("Location: shop.php");
exit();
}

$id = intval($_GET['id']);

$product = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM products WHERE id='$id'
"));
?>

<div class="container mt-5 mb-5">

<div class="row">

<!-- IMAGE -->

<div class="col-md-5">

<img src="assets/images/<?php echo $product['image']; ?>"
class="img-fluid rounded shadow">

</div>

<!-- DETAILS -->

<div class="col-md-7">

<h2><?php echo $product['name']; ?></h2>

<h4 class="text-danger mb-3">
₹<?php echo $product['price']; ?>
</h4>

<p class="text-muted">
<?php echo $product['description']; ?>
</p>

<hr>

<div class="d-flex gap-3">

<!-- ADD TO CART -->

<form method="POST" action="add_to_cart.php">

<input type="hidden" name="product_id"
value="<?php echo $product['id']; ?>">

<button class="btn btn-success">
Add to Cart
</button>

</form>

<!-- CUSTOMIZE -->

<?php if($product['customizable']=="yes"){ ?>

<a href="customize.php?id=<?php echo $product['id']; ?>"
class="btn btn-primary">

Customize Product

</a>

<?php } ?>

</div>

</div>

</div>

<!-- REVIEW FORM -->

<h4 class="mt-4 text-center">User Feedback</h4>
<p class="text-center text-muted mb-4">What our users say about us</p>

<div class="row">

<?php

$reviews = $conn->query("
SELECT r.*,u.name,u.email
FROM reviews r
JOIN users u ON r.user_id = u.id
WHERE r.product_id=".$product['id']."
ORDER BY r.id DESC
");

while($r = $reviews->fetch_assoc()){
?>

<div class="col-md-4 mb-4">

<div class="review-card">

<h5><?php echo $r['name']; ?></h5>

<p class="review-email">
<?php echo $r['email']; ?>
</p>

<hr>

<p class="review-text">
“<?php echo $r['review']; ?>”
</p>

<div class="review-stars">

<?php
for($i=1;$i<=5;$i++){
if($i <= $r['rating']){
echo "⭐";
}
}
?>

</div>

</div>

</div>

<?php } ?>

</div>
<?php include 'includes/footer.php'; ?>