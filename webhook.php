<?php
include 'config.php';

$endpoint_secret = "whsec_YOUR_WEBHOOK_SECRET";

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );

    if ($event->type == 'checkout.session.completed') {
        $session = $event->data->object;

        $order_id = $session->metadata->order_id;

        $conn->query("UPDATE orders SET status='Paid' WHERE id=$order_id");
    }

    http_response_code(200);

} catch (Exception $e) {
    http_response_code(400);
}
?>