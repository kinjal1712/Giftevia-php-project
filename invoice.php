
<?php
session_start();
include 'includes/db.php';

$order_id = $_GET['order_id'];

$order = $conn->prepare("
SELECT * FROM orders WHERE id=?
");

$order->bind_param("i",$order_id);
$order->execute();
$order_data = $order->get_result()->fetch_assoc();

$items = $conn->prepare("
SELECT order_items.*, products.name
FROM order_items
JOIN products ON order_items.product_id = products.id
WHERE order_id=?
");

$items->bind_param("i",$order_id);
$items->execute();
$result = $items->get_result();
?>

<html>

<head>

<title>Invoice</title>

<style>

body{
font-family:Arial;
padding:40px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th,td{
border:1px solid #ddd;
padding:10px;
}

h1{
color:#c94f7c;
}

</style>

</head>

<body>

<h1>GIFTEVIA Invoice</h1>

<p>Order ID: #<?php echo $order_id; ?></p>

<p>Customer: <?php echo $order_data['full_name']; ?></p>

<p>Phone: <?php echo $order_data['phone']; ?></p>

<p>Address: <?php echo $order_data['address']; ?></p>

<table>

<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

</tr>

<?php } ?>

</table>

<h3>Total: ₹<?php echo $order_data['total_amount']; ?></h3>

</body>

</html>

