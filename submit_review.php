<?php
session_start();
include 'includes/db.php';

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$rating = $_POST['rating'];
$review = $_POST['review'];

// VALIDATION
if (empty($rating) || empty($review)) {
    echo "All fields are required.";
    exit();
}

// CHECK IF USER PURCHASED PRODUCT (IMPORTANT)
$checkPurchase = $conn->prepare("
SELECT * FROM orders o
JOIN order_items oi ON o.id = oi.order_id
WHERE o.user_id = ? AND oi.product_id = ? AND o.status = 'Delivered'
");
$checkPurchase->bind_param("ii", $user_id, $product_id);
$checkPurchase->execute();
$purchaseResult = $checkPurchase->get_result();

if ($purchaseResult->num_rows == 0) {
    echo "You can review only delivered products.";
    exit();
}

// CHECK EXISTING REVIEW
$check = $conn->prepare("
SELECT id FROM reviews 
WHERE user_id = ? AND product_id = ?
");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {

    // UPDATE REVIEW
    $update = $conn->prepare("
   UPDATE reviews 
SET rating = ?, review = ?
WHERE user_id = ? AND product_id = ?
    ");
    $update->bind_param("isii", $rating, $review, $user_id, $product_id);
    $update->execute();

} else {

    // INSERT REVIEW
    $insert = $conn->prepare("
    INSERT INTO reviews(user_id, product_id, rating, review, created_at)
    VALUES(?, ?, ?, ?, NOW())
    ");
    $insert->bind_param("iiis", $user_id, $product_id, $rating, $review);
    $insert->execute();
}

// SUCCESS REDIRECT
header("Location: my_orders.php?review=success");
exit();
?>