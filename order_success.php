<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$order_id = $_GET['order_id'];

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM orders WHERE id='$order_id'
"));

$total = $order['total_amount'];
?>

<!-- SUCCESS ANIMATION -->

<div id="successAnimation" class="success-container">

<div class="success-icon">

<svg viewBox="0 0 120 120">

<circle cx="60" cy="60" r="55" class="circle"/>

<polyline points="35,65 55,85 85,45" class="check"/>

</svg>

</div>

<h2 class="success-text">Order Confirmed 🎉</h2>

<p>Your order is being processed...</p>

</div>


<!-- SOUND -->

<audio id="successSound">
<source src="https://assets.mixkit.co/active_storage/sfx/270/270-preview.mp3">
</audio>

<!-- order details  -->

<div id="orderDetails" style="display:none">

<div class="container mt-5 mb-5">

<div class="order-card">

<!-- welcome message -->

<p class="text-muted">
Your order has been placed successfully!
</p>


<!-- coupon message -->

<?php if(isset($_GET['coupon']) && $_GET['coupon']=="LOVE20"){ ?>

<div class="coupon-box">

🎉 Coupon <strong>LOVE20</strong> applied successfully! <br>

You saved <strong>20%</strong> on this order.

</div>

<?php } ?>


<div class="order-row">

<span>Order ID</span>
<strong>#<?php echo $order_id; ?></strong>

</div>

<div class="order-row">

<span>Total Paid</span>
<strong class="price">₹<?php echo $total; ?></strong>

</div>

<div class="order-row">

<span>Estimated Delivery</span>
<strong><?php echo date("d M Y", strtotime("+5 days")); ?></strong>

</div>

<div class="order-actions">

<a href="my_orders.php" class="btn track-btn">
Track Order
</a>

<a href="shop.php" class="btn shop-btn">
Continue Shopping
</a>
<br>
</div>
<br>
<h4 class="welcome-msg">
Welcome again <?php echo $_SESSION['user_name']; ?> 💖
</h4>

</div>

</div>

</div>


<!-- CONFETTI LIBRARY -->

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>


<!-- SCRIPT -->

<script>

window.onload=function(){

/* play sound */

setTimeout(function(){

document.getElementById("successSound").play();

},600);

/* confetti burst */

setTimeout(function(){

confetti({
particleCount:120,
spread:90,
origin:{y:0.6}
});

},900);

/* show order details */

setTimeout(function(){

document.getElementById("successAnimation").style.display="none";
document.getElementById("orderDetails").style.display="block";

},4000);

}

</script>


<!-- STYLE -->

<style>

.success-container{

text-align:center;
padding:100px 20px;
background:#f6f7fb;

}

.success-icon{

width:120px;
margin:auto;

}

/* circle draw */

.circle{

stroke:#4CAF50;
stroke-width:5;
fill:none;
stroke-dasharray:350;
stroke-dashoffset:350;
animation:circle 0.8s ease forwards;

}

/* check draw */

.check{

stroke:#4CAF50;
stroke-width:5;
fill:none;
stroke-dasharray:100;
stroke-dashoffset:100;
animation:check 0.6s ease forwards;
animation-delay:0.8s;

}

@keyframes circle{
to{stroke-dashoffset:0;}
}

@keyframes check{
to{stroke-dashoffset:0;}
}

.success-text{

margin-top:20px;
color:#4CAF50;
font-weight:600;

}


/* ORDER CARD */

.order-card{

max-width:500px;
margin:auto;
background:white;
padding:35px;
border-radius:14px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
text-align:center;

}

.order-row{

display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #eee;

}

.price{

color:#e91e63;
font-size:18px;

}

.order-actions{

margin-top:25px;
display:flex;
gap:10px;
justify-content:center;

}

.track-btn{

background:#e91e63;
color:white;

}

.shop-btn{

border:1px solid #ccc;

}

.welcome-msg{

font-weight:600;
color:#4CAF50;
margin-bottom:5px;

}

.coupon-box{

background:#e8f5e9;
border:1px solid #4CAF50;
padding:12px;
border-radius:8px;
margin:15px 0;
font-size:14px;

}

</style>

<?php include 'includes/footer.php'; ?>