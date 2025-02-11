<?php
session_start();
include '../database.php';

// Get order ID
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;
if (!$order_id) {
    header("Location: checkout.php?error=Invalid order ID.");
    exit;
}

// Simulate PayPal Payment (Redirecting after processing)
header("Refresh: 3; url=order_confirmation.php?order_id=$order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing PayPal Payment...</title>
</head>
<body>
    <h1>Processing PayPal Payment...</h1>
    <p>Please wait while we confirm your payment.</p>
</body>
</html>
