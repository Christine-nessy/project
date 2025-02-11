<?php
session_start();
include '../database.php';

// Get order ID
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;
if (!$order_id) {
    header("Location: checkout.php?error=Invalid order ID.");
    exit;
}

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch order details
$stmt = $db->prepare("
    SELECT o.order_id, o.total_amount, o.payment_method, o.order_date, o.status, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.order_id = :order_id
");
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: checkout.php?error=Order not found.");
    exit;
}

// Fetch ordered items
$stmt = $db->prepare("
    SELECT oi.product_id, oi.quantity, oi.price, p.name, p.image_url 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = :order_id
");
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Order Confirmation</h1>
        <h3>Order ID: <?= $order['order_id']; ?></h3>
        <h4>Customer: <?= htmlspecialchars($order['username']); ?></h4>
        <h4>Total Amount: $<?= number_format($order['total_amount'], 2); ?></h4>
        <h4>Payment Method: <?= ucfirst($order['payment_method']); ?></h4>
        <h4>Order Status: <?= $order['status']; ?></h4>

        <h3>Ordered Items:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['image_url']); ?>" width="100" alt="Product"></td>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td>$<?= number_format($item['price'], 2); ?></td>
                        <td><?= intval($item['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../products.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</body>
</html>
