<?php
include 'includes/db.php';
require_once __DIR__ . '/stripe-php/init.php';

\Stripe\Stripe::setApiKey("Your_secret_key");
$order_id = $_POST['order_id'];
$amount = $_POST['amount'] * 100; // paise

$session = \Stripe\Checkout\Session::create([

    'payment_method_types' => ['card'],

    'line_items' => [[
        'price_data' => [
            'currency' => 'inr',
            'product_data' => [
                'name' => 'GiftEvia Order #' . $order_id . ' 🎁',
            ],
            'unit_amount' => $amount,
        ],
        'quantity' => 1,
    ]],

    'mode' => 'payment',

    // ✅ SUCCESS / CANCEL
    'success_url' => "http://localhost/giftevia/order_success.php?order_id=".$order_id,
        'cancel_url'  => "http://localhost/giftevia/cancel_order.php?order_id=".$order_id,

    // ✅ ORDER LINK (important)
    'metadata' => [
        'order_id' => $order_id
    ],

    // 💖 CUSTOM MESSAGE
    'custom_text' => [
        'submit' => [
            'message' => 'Your gift is almost ready 💝'
        ],
    ],

]);

header("Location: " . $session->url);
exit();
?>