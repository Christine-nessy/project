<?php
session_start();
include 'database.php'; // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit;
}

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch all orders for the logged-in user
$stmt = $db->prepare("
    SELECT orders.order_id, orders.total_price, orders.status, orders.created_at,
           GROUP_CONCAT(products.name SEPARATOR ', ') AS names
    FROM orders
    LEFT JOIN order_items ON orders.order_id = order_items.order_id
    LEFT JOIN products ON order_items.product_id = products.id
    WHERE orders.user_id = :user_id
    GROUP BY orders.order_id
    ORDER BY orders.created_at DESC
");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>My Orders</h2>

        <?php if (empty($orders)): ?>
            <div class="alert alert-info mt-3">You have no orders yet.</div>
        <?php else: ?>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Products</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_names'] ?? 'N/A'); ?></td>
                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
