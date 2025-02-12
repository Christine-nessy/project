<?php
// Include necessary files
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
include 'admin_nav.php'; // Admin navigation

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch total sales per month
$sales_query = $db->prepare("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_price) AS total_sales
    FROM orders 
    GROUP BY month 
    ORDER BY month ASC
");
$sales_query->execute();
$sales_data = $sales_query->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for Chart.js
$months = [];
$total_sales = [];
foreach ($sales_data as $row) {
    $months[] = $row['month'];
    $total_sales[] = $row['total_sales'];
}

// Fetch total orders by status
$status_query = $db->prepare("
    SELECT status, COUNT(*) AS count FROM orders GROUP BY status
");
$status_query->execute();
$status_data = $status_query->fetchAll(PDO::FETCH_ASSOC);

$statuses = [];
$order_counts = [];
foreach ($status_data as $row) {
    $statuses[] = $row['status'];
    $order_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <div class="container mt-5">
        <h1>Analytics Dashboard</h1>

        <!-- Sales Trend Chart -->
        <div class="card mt-4">
            <div class="card-header">Monthly Sales Trend</div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="card mt-4">
            <div class="card-header">Orders by Status</div>
            <div class="card-body">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Sales Trend Chart
        var ctx1 = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Total Sales ($)',
                    data: <?php echo json_encode($total_sales); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Orders by Status Chart
        var ctx2 = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($statuses); ?>,
                datasets: [{
                    label: 'Order Count',
                    data: <?php echo json_encode($order_counts); ?>,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
