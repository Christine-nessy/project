<?php
session_start();
include '../database.php';

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login.php");
    exit;
}

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch products in the shopping cart
$stmt = $db->prepare("SELECT sc.product_id, sc.quantity, p.name, p.price, p.image_url 
    FROM shopping_cart sc
    JOIN products p ON sc.product_id = p.product_id
    WHERE sc.user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    header("Location: cart.php?error=Your cart is empty.");
    exit;
}

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Create new order
$stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total_amount, 'Pending')");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':total_amount', $total_price, PDO::PARAM_STR);
$stmt->execute();
$order_id = $db->lastInsertId();

// Insert order items
foreach ($cart_items as $item) {
    $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
    $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
    $stmt->execute();
}

// Clear shopping cart
$stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Checkout</h1>
        <h3>Total Amount: $<?= number_format($total_price, 2); ?></h3>
        <p>Select a payment method:</p>
        <ul>
            <li><a href="paypal_payment.php?order_id=<?= $order_id; ?>" class="btn btn-primary">Pay with PayPal</a></li>
            <li><a href="credit_card_payment.php?order_id=<?= $order_id; ?>" class="btn btn-success">Pay with Credit/Debit Card</a></li>
            <li><a href="mpesa_payment.php?order_id=<?= $order_id; ?>" class="btn btn-warning">Pay with M-Pesa</a></li>
        </ul>
    </div>
</body>
</html>
