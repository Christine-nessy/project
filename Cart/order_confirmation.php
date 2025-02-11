<?php
session_start();
include '../database.php'; // Include database connection

// Ensure user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login.php?error=Please log in to view your order");
    exit;
}

// Connect to database
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch latest order for the user
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id DESC LIMIT 1");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: ../products.php?error=No recent orders found");
    exit;
}

// Fetch order items
$stmt = $db->prepare("SELECT oi.product_id, p.name, p.image_url, oi.quantity, oi.price, (oi.quantity * oi.price) AS total_price
                      FROM order_items oi
                      JOIN products p ON oi.product_id = p.product_id
                      WHERE oi.order_id = :order_id");
$stmt->bindParam(':order_id', $order['order_id'], PDO::PARAM_INT);
$stmt->execute();
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Order Confirmation</h2>
    <p>Thank you for your purchase! Your order has been successfully placed.</p>
    
    <h3>Order Details</h3>
    <p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
    <p><strong>Total Amount:</strong> KES <?= number_format($order['total_amount'], 2) ?></p>

    <h3>Items Purchased</h3>
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php foreach ($order_items as $item) : ?>
            <tr>
                <td><img src="<?= $item['image_url'] ?>" width="50" height="50"></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>KES <?= number_format($item['price'], 2) ?></td>
                <td>KES <?= number_format($item['total_price'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="../products.php">Continue Shopping</a></p>
</body>
</html>
