<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("
SELECT cart.*, products.name, products.image
FROM cart
JOIN products ON cart.product_id = products.id
WHERE cart.user_id=?
");

$query->bind_param("i",$user_id);
$query->execute();
$result = $query->get_result();

$total = 0;
?>

<div class="container mt-5 mb-5">

<h2 class="text-center mb-4">🛒 Your Cart</h2>

<?php if($result->num_rows == 0){ ?>

<div class="alert alert-warning text-center">

Your cart is empty 🛒

<br><br>

<a href="shop.php" class="btn btn-primary">
Continue Shopping
</a>

</div>

<?php } else { ?>

<?php while($row = $result->fetch_assoc()){

$item_total = $row['price'] * $row['quantity'];
$total += $item_total;
?>

<div class="card mb-3 shadow-sm">

<div class="row g-0 align-items-center">

<!-- PRODUCT IMAGE -->

<div class="col-md-2 text-center p-2">

<?php if(!empty($row['custom_image'])){ ?>

<img src="<?php echo $row['custom_image']; ?>"
style="width:90px;height:90px;object-fit:cover">

<?php } else { ?>

<img src="assets/images/<?php echo $row['image']; ?>"
style="width:90px;height:90px;object-fit:cover">

<?php } ?>

</div>

<!-- PRODUCT DETAILS -->

<div class="col-md-4">

<h5 class="mb-1">
<?php echo $row['name']; ?>
</h5>

<?php if(!empty($row['custom_image'])){ ?>

<p style="color:#e91e63;font-size:13px;margin:0">
Customized Product
</p>

<?php } ?>

<p class="text-muted">
Price: ₹<?php echo $row['price']; ?>
</p>

</div>


<!-- QUANTITY -->

<div class="col-md-2">

<form method="POST" action="update_cart.php">

<input type="hidden" name="cart_id"
value="<?php echo $row['id']; ?>">

<input type="number"
name="quantity"
value="<?php echo $row['quantity']; ?>"
min="1"
class="form-control">

<button class="btn btn-sm btn-outline-primary mt-1">
Update
</button>

</form>

</div>


<!-- ITEM TOTAL -->

<div class="col-md-2 text-center">

<strong>
₹<?php echo $item_total; ?>
</strong>

</div>


<!-- REMOVE -->

<div class="col-md-2 text-center">

<a href="remove_cart.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Remove item from cart?')">

Remove

</a>

</div>

</div>

</div>

<?php } ?>

<hr>

<div class="text-end">

<h4 class="mb-3">
Subtotal: ₹<?php echo $total; ?>
</h4>

<a href="checkout.php"
class="btn btn-success btn-lg">

Proceed to Checkout

</a>

</div>

<?php } ?>

</div>

<?php include 'includes/footer.php'; ?>