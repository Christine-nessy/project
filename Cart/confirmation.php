<?php
session_start();
include '../database.php';

if (!isset($_GET['order_id'])) {
    header("Location: ../products.php");
    exit;
}

$order_id = $_GET['order_id'];

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch order details
$stmt = $db->prepare("SELECT total_price, status FROM orders WHERE order_id = :order_id");
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items
$stmt = $db->prepare("
    SELECT p.name, oi.quantity, oi.price 
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
    <style>
        /* Page Background */
        body {
            font-family: Arial, sans-serif;
            background-color: #FFB200; /* Warm yellow background */
            color: #640D5F; /* Dark purple for contrast */
        }

        /* Container Styling */
        .container {
            max-width: 700px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        /* Headings */
        h1, h3 {
            color: #D91656;
            font-weight: bold;
        }

        /* Text Styling */
        p {
            font-size: 1.1rem;
            color: #640D5F;
        }

        /* Table Styling */
        .table {
            border: 2px solid #EB5B00;
        }

        .table thead {
            background-color: #EB5B00;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #FFF3E0;
        }

        /* Buttons */
        .btn-primary {
            background-color: #D91656;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #EB5B00;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Order Confirmation</h1>
        <?php if ($order): ?>
            <p>Thank you for your purchase! Your order has been placed successfully.</p>
            <h3>Order Details</h3>
            <p><strong>Order Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
            <p><strong>Total Price:</strong> $<?= number_format($order['total_price'], 2); ?></p>

            <h3>Items Ordered:</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= intval($item['quantity']); ?></td>
                            <td>$<?= number_format($item['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="../products.php" class="btn btn-primary">Continue Shopping</a>
        <?php else: ?>
            <p>Invalid order details.</p>
        <?php endif; ?>
    </div>
</body>
</html>
