<?php
session_start();
include 'admin_auth.php'; 
include '../database.php'; 
include 'admin_nav.php'; 

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Business Analytics</h1>

        <!-- Download PDF Button -->
        <div class="text-center mb-4">
            <button id="downloadPDF" class="btn btn-primary">Download as PDF</button>
        </div>

        <div id="chart-container">
            <!-- 🔹 Top-Selling Products -->
            <div class="card mb-4 p-3">
                <h3>Top-Selling Products</h3>
                <canvas id="topProductsChart"></canvas>
            </div>

            <!-- 🔹 User Activity Trends -->
            <div class="card mb-4 p-3">
                <h3>User Activity Trends</h3>
                <canvas id="orderTrendsChart"></canvas>
            </div>

            <!-- 🔹 Payment Statistics -->
            <div class="card mb-4 p-3">
                <h3>Payment Statistics</h3>
                <canvas id="paymentChart"></canvas>
            </div>

            <!-- 🔹 Most Active Users -->
            <div class="card mb-4 p-3">
                <h3>Most Active Users</h3>
                <canvas id="activeUsersChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const { jsPDF } = window.jspdf;

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
            type: 'bar',
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

        // 📥 Download Graphs as PDF
        document.getElementById("downloadPDF").addEventListener("click", function() {
            const pdf = new jsPDF('p', 'mm', 'a4');
            const chartContainer = document.getElementById("chart-container");

            html2canvas(chartContainer, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const imgWidth = 210; // A4 width in mm
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                pdf.addImage(imgData, 'PNG', 0, 10, imgWidth, imgHeight);
                pdf.save("business_analytics.pdf");
            });
        });
    </script>
</body>
</html>
