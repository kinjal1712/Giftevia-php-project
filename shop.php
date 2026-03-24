<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

$limit = 8;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

/* FILTER CONDITIONS */

$where = [];

if(!empty($_GET['occasion'])){
$occasion = $_GET['occasion'];
$where[] = "occasion='$occasion'";
}

if(!empty($_GET['recipient'])){
$recipient = $_GET['recipient'];
$where[] = "recipient='$recipient'";
}

if(!empty($_GET['budget'])){
$budget = $_GET['budget'];
$where[] = "price <= $budget";
}

$where_sql = "";

if(count($where)>0){
$where_sql = "WHERE " . implode(" AND ", $where);
}

/* GET PRODUCTS */

$query = mysqli_query($conn,"
SELECT * FROM products
$where_sql
LIMIT $start,$limit
");

?>

<div class="container mt-5 mb-5">

<h2 class="text-center mb-4">🎁 Our Gifts</h2>

<div class="row">

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<div class="col-md-3 mb-4">

<div class="card product-card shadow-sm h-100">

<img src="assets/images/<?php echo $row['image']; ?>"
class="card-img-top"
style="height:200px;object-fit:cover">

<div class="card-body text-center">

<h5 class="mb-2">
<?php echo $row['name']; ?>
</h5>

<p class="price text-danger">
₹<?php echo $row['price']; ?>
</p>

<div class="d-grid gap-2">

<a href="product.php?id=<?php echo $row['id']; ?>"
class="btn btn-outline-dark btn-sm">

View

</a>
</div>

</div>

</div>

</div>

<?php } ?>

</div>

<?php

/* PAGINATION */

$total_q = mysqli_query($conn,"SELECT COUNT(*) as total FROM products $where_sql");
$total_res = mysqli_fetch_assoc($total_q);

$total_pages = ceil($total_res['total'] / $limit);

?>

<div class="text-center mt-4">

<?php for($i=1;$i<=$total_pages;$i++){ ?>

<a href="shop.php?page=<?php echo $i; ?>"
class="btn btn-outline-primary btn-sm m-1">

<?php echo $i; ?>

</a>

<?php } ?>

</div>

</div>

<?php include 'includes/footer.php'; ?>