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
    <title>Business Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">📊 Business Analytics Dashboard</h1>
        
        <button class="btn btn-primary mb-4" onclick="downloadPDF()">Download Report 📄</button>

        <div class="row">
            <!-- 🔹 Top-Selling Products -->
            <div class="col-md-6">
                <div class="card p-3 text-center">
                    <h4>🔥 Top-Selling Products</h4>
                    <p>These are the most purchased products.</p>
                    <canvas id="topProductsChart" width="400" height="300"></canvas>
                    <table class="table table-sm mt-3">
                        <thead><tr><th>Product</th><th>Sales</th></tr></thead>
                        <tbody>
                            <?php foreach ($top_products as $product) {
                                echo "<tr><td>{$product['name']}</td><td>{$product['sales_count']}</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 🔹 User Activity Trends -->
            <div class="col-md-6">
                <div class="card p-3 text-center">
                    <h4>📈 User Activity Trends</h4>
                    <p>Tracks daily order trends.</p>
                    <canvas id="orderTrendsChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- 🔹 Payment Statistics -->
            <div class="col-md-6">
                <div class="card p-3 text-center">
                    <h4>💳 Payment Statistics</h4>
                    <p>Breakdown of payment methods used.</p>
                    <canvas id="paymentChart" width="300" height="300"></canvas>
                </div>
            </div>

            <!-- 🔹 Most Active Users -->
            <div class="col-md-6">
                <div class="card p-3 text-center">
                    <h4>🏆 Most Active Users</h4>
                    <p>Top users who place the most orders.</p>
                    <canvas id="activeUsersChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chartOptions = { responsive: false, maintainAspectRatio: false };

        // 🔹 Top-Selling Products
        new Chart(document.getElementById("topProductsChart"), {
            type: 'bar',
            data: {
                labels: <?php echo $product_names; ?>,
                datasets: [{ label: "Sales Count", data: <?php echo $product_sales; ?>, backgroundColor: 'rgba(54, 162, 235, 0.6)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 1 }]
            },
            options: chartOptions
        });

        // 🔹 User Activity Trends
        new Chart(document.getElementById("orderTrendsChart"), {
            type: 'line',
            data: {
                labels: <?php echo $order_dates; ?>,
                datasets: [{ label: "Orders per Day", data: <?php echo $order_counts; ?>, backgroundColor: 'rgba(255, 99, 132, 0.6)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 2 }]
            },
            options: chartOptions
        });

        // 🔹 Payment Statistics (Pie Chart)
        new Chart(document.getElementById("paymentChart"), {
            type: 'pie',
            data: {
                labels: <?php echo $payment_methods; ?>,
                datasets: [{ data: <?php echo $payment_counts; ?>, backgroundColor: ['#4CAF50', '#FF9800', '#2196F3', '#9C27B0'] }]
            },
            options: chartOptions
        });

        // 🔹 Most Active Users
        new Chart(document.getElementById("activeUsersChart"), {
            type: 'bar',
            data: {
                labels: <?php echo $usernames; ?>,
                datasets: [{ label: "Total Orders", data: <?php echo $user_orders; ?>, backgroundColor: 'rgba(255, 206, 86, 0.6)', borderColor: 'rgba(255, 206, 86, 1)', borderWidth: 1 }]
            },
            options: chartOptions
        });

        // 🔹 Download Charts as PDF
        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            let pdf = new jsPDF();
            pdf.text("Business Analytics Report", 10, 10);
            pdf.text(new Date().toLocaleString(), 10, 20);

            document.querySelectorAll("canvas").forEach((canvas, index) => {
                html2canvas(canvas).then(canvas => {
                    pdf.addImage(canvas.toDataURL("image/png"), "PNG", 10, 30, 180, 90);
                    if (index !== document.querySelectorAll("canvas").length - 1) pdf.addPage();
                });
            });

            setTimeout(() => pdf.save("analytics_report.pdf"), 2000);
        }
    </script>
</body>
</html>
