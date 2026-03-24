<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$id = intval($_GET['id']);
$q = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $q->fetch_assoc();
?>

<div class="container mt-5 mb-5">

<h2 class="text-center mb-4">Customize Product</h2>

<div style="display:flex;gap:30px">

<div id="viewer" style="width:500px;height:450px;background:#000"></div>

<div>

<canvas id="designCanvas" width="450" height="450"
style="border:1px solid #ccc;background:#fff"></canvas>

<p style="font-size:13px">Scroll to zoom image</p>

</div>

<div style="width:260px;background:#fff;padding:20px;border-radius:8px">

<h4 id="priceDisplay">Price: ₹<?php echo $product['price']; ?></h4>
<label>Upload Image</label>
<input type="file" id="imageUpload" class="form-control">

<label class="mt-3">Add Text</label>
<input type="text" id="textInput" class="form-control">

<label>Text Color</label>
<input type="color" id="textColor" value="#000000" class="form-control">
<label>Text Size</label>
<select id="textSize" class="form-control">
    <option value="16">Small</option>
    <option value="20" selected>Normal</option>
    <option value="24">Medium</option>
    <option value="30">Large</option>
    <option value="36">Extra Large</option>
</select>
<button onclick="addText()" class="btn btn-danger w-100 mt-3">
Add Text
</button>

<button onclick="addToCart()" class="btn btn-success w-100 mt-2">
Add To Cart
</button>

</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/build/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

<script>
var PRODUCT_ID = <?php echo $product['id']; ?>;
var PRODUCT_TYPE = "<?php echo $product['type']; ?>";
var BASE_PRICE = <?php echo $product['price']; ?>;
</script>

<script src="assets/js/customizer.js"></script>

<?php include 'includes/footer.php'; ?>