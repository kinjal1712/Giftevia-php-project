<?php
include 'includes/header.php';
include 'includes/db.php';
?>

<!-- HERO SECTION -->

<section class="hero-section text-center">

<div class="container">

<h1 class="hero-title">Gifts That Bloom With Love</h1>

<p class="hero-subtitle">
Design personalized moments wrapped in elegance & charm
</p>

<a href="shop.php" class="btn cta-btn mt-3">
Start Customizing
</a>

</div>

</section>



<!-- OFFER STRIP -->

<section class="offer-strip">

<div class="offer-pill">

<div id="offerCarousel" class="carousel slide" data-bs-ride="carousel">

<div class="carousel-inner text-center">

<div class="carousel-item active">
🎁 Flat 20% Off – Use Code <b>LOVE20</b>
</div>

<div class="carousel-item">
🚚 Free Shipping on Orders Above ₹999
</div>

<div class="carousel-item">
🎉 Buy 2 Get 1 Free – Limited Time
</div>

</div>

</div>

</div>

</section>


<!-- GIFT FINDER -->

<section class="container section-spacing">

<div class="gift-finder-card">

<h2 class="text-center mb-4">🎁 Find the Perfect Gift</h2>

<form method="GET" action="shop.php">

<div class="row g-3">

<div class="col-md-4">
<select name="occasion" class="form-control">
<option value="">Occasion</option>
<option value="birthday">Birthday</option>
<option value="anniversary">Anniversary</option>
<option value="valentine">Valentine</option>
<option value="festival">Festival</option>
</select>
</div>

<div class="col-md-4">
<select name="recipient" class="form-control">
<option value="">For</option>
<option value="her">For Her</option>
<option value="him">For Him</option>
<option value="kids">Kids</option>
<option value="couple">Couple</option>
</select>
</div>

<div class="col-md-4">
<select name="budget" class="form-control">
<option value="">Budget</option>
<option value="500">Under ₹500</option>
<option value="1000">Under ₹1000</option>
<option value="2000">Under ₹2000</option>
</select>
</div>

</div>

<button class="btn auth-btn w-100 mt-4">
Find Gifts
</button>

</form>

</div>

</section>



<!-- TRENDING PRODUCTS -->

<section class="container section-spacing">

<h2 class="section-title text-center mb-5">🔥 Trending Gifts</h2>

<div class="row">

<?php

$q = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");

while($row = $q->fetch_assoc()){

?>

<div class="col-md-3 mb-4">

<div class="product-card">

<img src="assets/images/<?php echo $row['image']; ?>" class="product-img">

<div class="product-info">

<h5><?php echo $row['name']; ?></h5>

<p class="price">₹<?php echo $row['price']; ?></p>

<?php if($row['customizable']=="yes"){ ?>

<a href="customize.php?id=<?php echo $row['id']; ?>" class="cta-btn-sm">
Customize
</a>

<?php } else { ?>

<a href="product.php?id=<?php echo $row['id']; ?>" class="cta-btn-sm">
View
</a>

<?php } ?>

</div>

</div>

</div>

<?php } ?>

</div>

</section>



<!-- SHOP BY OCCASION -->

<section class="container section-spacing">

<h2 class="section-title text-center mb-4">🎉 Shop By Occasion</h2>

<div class="row text-center g-4">

<div class="col-md-3">
<a href="shop.php?occasion=birthday" class="category-card">
<div class="category-icon">🎂</div>
<h5>Birthday</h5>
<p>Surprise them with personalized birthday gifts.</p>
</a>
</div>

<div class="col-md-3">
<a href="shop.php?occasion=anniversary" class="category-card">
<div class="category-icon">💍</div>
<h5>Anniversary</h5>
<p>Celebrate love with custom memories.</p>
</a>
</div>

<div class="col-md-3">
<a href="shop.php?occasion=valentine" class="category-card">
<div class="category-icon">❤️</div>
<h5>Valentine</h5>
<p>Romantic gifts made specially for them.</p>
</a>
</div>

<div class="col-md-3">
<a href="shop.php?occasion=festival" class="category-card">
<div class="category-icon">🎄</div>
<h5>Festivals</h5>
<p>Make every celebration extra special.</p>
</a>
</div>

</div>

</section>



<!-- SHOP FOR -->

<section class="container section-spacing">

<h2 class="section-title text-center mb-4">💖 Shop For</h2>

<div class="row text-center g-4">

<div class="col-md-3">
<a href="shop.php?recipient=her" class="category-card">
<div class="category-icon">🎀</div>
<h5>For Her</h5>
<p>Elegant gifts she'll absolutely love.</p>
</a>
</div>

<div class="col-md-3">
<a href="shop.php?recipient=him" class="category-card">
<div class="category-icon">🎁</div>
<h5>For Him</h5>
<p>Unique gifts perfect for every occasion.</p>
</a>
</div>

<div class="col-md-3">
<a href="shop.php?recipient=kids" class="category-card">
<div class="category-icon">🧸</div>
<h5>Kids</h5>
<p>Fun and adorable gifts for little ones.</p>
</a>
</div>

<div class="col-md-3">
<a href="shop.php?recipient=couple" class="category-card">
<div class="category-icon">💞</div>
<h5>Couples</h5>
<p>Celebrate love with matching gifts.</p>
</a>
</div>

</div>

</section>

<?php include 'includes/footer.php'; ?>