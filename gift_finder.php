<?php
include 'includes/db.php';
include 'includes/header.php';

$occasion = $_GET['occasion'] ?? '';
$recipient = $_GET['recipient'] ?? '';
$budget = $_GET['budget'] ?? '';

$query = "SELECT * FROM products WHERE 1=1";

if($occasion != ''){
$query .= " AND occasion='$occasion'";
}

if($recipient != ''){
$query .= " AND recipient='$recipient'";
}

if($budget != ''){
$query .= " AND price <= $budget";
}

$result = $conn->query($query);
?>

<div class="container mt-5">

<h2 class="text-center mb-4">Recommended Gifts</h2>

<div class="row">

<?php while($row=$result->fetch_assoc()){ ?>

<div class="col-md-3 mb-4">

<div class="product-card">

<img src="assets/images/<?php echo $row['image']; ?>"
class="product-img w-100">

<h4><?php echo $row['name']; ?></h4>

<p class="price">₹<?php echo $row['price']; ?></p>

<a href="product.php?id=<?php echo $row['id']; ?>"
class="cta-btn-sm">
View
</a>

</div>

</div>

<?php } ?>

</div>

</div>

<?php include 'includes/footer.php'; ?>