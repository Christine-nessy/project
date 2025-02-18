<?php
// Include necessary files
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
include 'admin_nav.php'; // Admin navigation

// Start session and check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Get the order ID from the query parameter
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
} else {
    // If no order ID is passed, redirect back to orders list
    header("Location: admin_orders.php");
    exit;
}

// Create a Database instance and retrieve the connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch the order details based on order_id
$stmt = $db->prepare("SELECT * FROM orders WHERE order_id = :order_id");
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the products in this order
$stmt_products = $db->prepare("SELECT oi.product_id, p.name, oi.quantity, oi.price 
                               FROM order_items oi 
                               JOIN products p ON oi.product_id = p.product_id 
                               WHERE oi.order_id = :order_id");

$stmt_products->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

// Check if the order exists
if (!$order) {
    echo "<p>Order not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #DAD2FF; /* Light lavender background */
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: white; /* White background for the main content */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            padding: 20px;
        }

        h1 {
            color: #493D9E; /* Dark purple for the main heading */
        }

        h4 {
            color: #493D9E; /* Dark purple for section headings */
            margin-top: 20px;
        }

        .table {
            background-color: #FFF2AF; /* Light yellow background for the tables */
        }

        th, td {
            color: #493D9E; /* Dark purple for table headings and content */
            text-align: center;
        }

        .thead-dark th {
            background-color: #B2A5FF; /* Light purple background for table headers */
            color: white;
        }

        .btn-secondary {
            background-color: #B2A5FF; /* Light purple for button */
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #493D9E; /* Dark purple on hover */
            color: #FFF2AF; /* Light yellow text on hover */
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Order Details - #<?php echo htmlspecialchars($order['order_id']); ?></h1>

        <h4>Order Information</h4>
        <table class="table table-bordered">
            <tr>
                <th>Order ID</th>
                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
            </tr>
            <tr>
                <th>User ID</th>
                <td><?php echo htmlspecialchars($order['user_id']); ?></td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td><?php echo number_format($order['total_price'], 2); ?> $</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
            </tr>
            <tr>
                <th>Return Status</th>
                <td><?php echo htmlspecialchars($order['return_status']); ?></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
            </tr>
        </table>

        <h4>Products in Order</h4>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price ($)</th>
                    <th>Total ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td><?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo number_format($product['quantity'] * $product['price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="admin_orders.php" class="btn btn-secondary">Back to Orders</a>
    </div>
</body>
</html>
