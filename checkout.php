<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

/* CHECK CART EMPTY */

$check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
$check_cart->bind_param("i",$user_id);
$check_cart->execute();
$cart_result = $check_cart->get_result();

if($cart_result->num_rows == 0){

echo "<div class='container mt-5'>
<div class='alert alert-warning text-center'>
Your cart is empty 🛒<br><br>
<a href='shop.php' class='btn btn-primary'>Continue Shopping</a>
</div>
</div>";

include 'includes/footer.php';
exit();
}


/* GET CART ITEMS */

$cart_query = $conn->prepare("
SELECT cart.*, products.name, products.price
FROM cart
JOIN products ON cart.product_id = products.id
WHERE cart.user_id=?
");

$cart_query->bind_param("i",$user_id);
$cart_query->execute();
$result = $cart_query->get_result();

$total = 0;
?>

<div class="checkout-wrapper">

<div class="checkout-grid">


<!-- LEFT SIDE -->

<div class="checkout-box">

<h2>Checkout Details</h2>

<form method="POST" action="place_order.php">

<input type="text" name="fullname" placeholder="Full Name" required>

<input type="text" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" required>

<textarea name="address" placeholder="Delivery Address" required></textarea>

<input type="text" name="coupon_code" placeholder="Enter Coupon Code (Optional)">

<p style="font-size:13px;color:#777;margin-top:5px;">
Try code <b>LOVE20</b> for 20% off
</p>

<select name="payment_method" required>

<option value="">Select Payment Method</option>

<option value="COD">Cash On Delivery</option>

<option value="UPI">UPI Payment</option>

<option value="STRIPE">Pay with Card 💳</option>

</select>

<button type="submit" class="checkout-btn">
Place Order
</button>

</form>


</form>
</div>



<!-- RIGHT SIDE -->

<div class="order-review">

<h3>Order Summary</h3>

<?php
while($row = $result->fetch_assoc()){

$item_total = $row['price'] * $row['quantity'];
$total += $item_total;
?>

<div class="review-item">

<span><?php echo $row['name']; ?> × <?php echo $row['quantity']; ?></span>

<span>₹<?php echo $item_total; ?></span>

</div>

<?php } ?>

<hr>

<div class="review-item">

<span>Subtotal</span>

<span>₹<?php echo $total; ?></span>

</div>

<div class="review-item" style="color:green">

<span>Coupon Discount</span>

<span id="discount">₹0</span>

</div>

<hr>

<div class="review-total">

<strong>Final Total:</strong>

<strong id="finalTotal">₹<?php echo $total; ?></strong>

</div>

</div>

</div>

</div>

<script>
const couponInput = document.querySelector("input[name='coupon_code']");
const subtotal = <?php echo $total; ?>;

couponInput.addEventListener("keyup", function(){

let code = this.value.trim();
let discount = 0;

if(code === "LOVE20"){
    discount = subtotal * 0.20;
}

let finalAmount = (subtotal - discount).toFixed(0);

document.getElementById("discount").innerText = "₹" + discount.toFixed(0);
document.getElementById("finalTotal").innerText = "₹" + finalAmount;

/* UPDATE STRIPE AMOUNT */
document.getElementById("stripeAmount").value = finalAmount;

});
</script>
<?php include 'includes/footer.php'; ?>