<?php
// Include necessary files
session_start();
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
include 'admin_nav.php'; // Admin navigation

// Create database instance
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// 🔹 Fetch Top-Selling Products
$stmt = $db->prepare("
    SELECT products.name, COUNT(order_items.product_id) AS sales_count 
    FROM order_items 
    JOIN products ON order_items.product_id = products.product_id 
    GROUP BY order_items.product_id 
    ORDER BY sales_count DESC 
    LIMIT 5
");
$stmt->execute();
$top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for Chart.js
$product_names = json_encode(array_column($top_products, 'name'));
$product_sales = json_encode(array_column($top_products, 'sales_count'));

// 🔹 Fetch User Activity Trends
$stmt = $db->prepare("
    SELECT DATE(created_at) AS order_date, COUNT(order_id) AS order_count 
    FROM orders 
    GROUP BY order_date 
    ORDER BY order_date
");
$stmt->execute();
$order_trends = $stmt->fetchAll(PDO::FETCH_ASSOC);

$order_dates = json_encode(array_column($order_trends, 'order_date'));
$order_counts = json_encode(array_column($order_trends, 'order_count'));

// 🔹 Fetch Payment Statistics
$stmt = $db->prepare("
    SELECT payment_method, COUNT(order_id) AS count 
    FROM orders 
    GROUP BY payment_method
");
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$payment_methods = json_encode(array_column($payments, 'payment_method'));
$payment_counts = json_encode(array_column($payments, 'count'));

// 🔹 Fetch Most Active Users
$stmt = $db->prepare("
    SELECT users.username, COUNT(orders.order_id) AS total_orders 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    GROUP BY users.id 
    ORDER BY total_orders DESC 
    LIMIT 5
");
$stmt->execute();
$active_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usernames = json_encode(array_column($active_users, 'username'));
$user_orders = json_encode(array_column($active_users, 'total_orders'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Business Analytics</h1>

        <!-- 🔹 Top-Selling Products -->
        <h3>Top-Selling Products</h3>
        <canvas id="topProductsChart"></canvas>

        <!-- 🔹 User Activity Trends -->
        <h3 class="mt-5">User Activity Trends</h3>
        <canvas id="orderTrendsChart"></canvas>

        <!-- 🔹 Payment Statistics -->
        <h3 class="mt-5">Payment Statistics</h3>
        <canvas id="paymentChart"></canvas>

        <!-- 🔹 Most Active Users -->
        <h3 class="mt-5">Most Active Users</h3>
        <canvas id="activeUsersChart"></canvas>
    </div>

    <script>
        // 🔹 Top-Selling Products
        new Chart(document.getElementById("topProductsChart"), {
            type: 'bar',
            data: {
                labels: <?php echo $product_names; ?>,
                datasets: [{
                    label: "Sales Count",
                    data: <?php echo $product_sales; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // 🔹 User Activity Trends
        new Chart(document.getElementById("orderTrendsChart"), {
            type: 'line',
            data: {
                labels: <?php echo $order_dates; ?>,
                datasets: [{
                    label: "Orders per Day",
                    data: <?php echo $order_counts; ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }]
            }
        });

        // 🔹 Payment Statistics
        new Chart(document.getElementById("paymentChart"), {
            type: 'pie',
            data: {
                labels: <?php echo $payment_methods; ?>,
                datasets: [{
                    data: <?php echo $payment_counts; ?>,
                    backgroundColor: ['#4CAF50', '#FF9800', '#2196F3', '#9C27B0']
                }]
            }
        });

        // 🔹 Most Active Users
        new Chart(document.getElementById("activeUsersChart"), {
            type: 'horizontalBar',
            data: {
                labels: <?php echo $usernames; ?>,
                datasets: [{
                    label: "Total Orders",
                    data: <?php echo $user_orders; ?>,
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
