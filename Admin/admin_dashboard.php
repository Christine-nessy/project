<?php
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection

// Ensure $db is available and get the connection
if (!isset($db)) {
    die("Database connection not established.");
}

$conn = $db->getConnection(); // Get connection from Database class

// Fetch total products
$stmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products");
$stmt->execute();
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Fetch pending orders
$stmt = $conn->prepare("SELECT COUNT(*) AS pending_orders FROM orders WHERE status='pending'");
$stmt->execute();
$pending_orders = $stmt->fetch(PDO::FETCH_ASSOC)['pending_orders'];

// Fetch total users
$stmt = $conn->prepare("SELECT COUNT(*) AS total_users FROM users");
$stmt->execute();
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Fetch total revenue
$stmt = $conn->prepare("SELECT SUM(total_price) AS revenue FROM orders WHERE status='completed'");
$stmt->execute();
$revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            background-color: #DAD2FF;
            color: #493D9E;
            transition: background 0.3s;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #493D9E;
            color: white;
            padding: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #B2A5FF;
            border-radius: 5px;
        }
        .content {
            margin-left: 270px;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: #B2A5FF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .chart-container {
            margin-top: 40px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_product.php"><i class="fas fa-plus-circle"></i> Add Product</a>
        <a href="update_product.php"><i class="fas fa-edit"></i> Update Product</a>
        <a href="delete_product.php"><i class="fas fa-trash"></i> Delete Product</a>
        <a href="view_products.php"><i class="fas fa-box"></i> View Products</a>
        <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> View Orders</a>
        <a href="view_users.php"><i class="fas fa-users"></i> View Users</a>
        <a href="view_analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
        <a href="add_stock.php"><i class="fas fa-warehouse"></i> Manage Stock</a>
        <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, Admin!</h1>
        <div class="dashboard-cards">
            <div class="card">Total Products: <?php echo $total_products; ?></div>
            <div class="card">Orders Pending: <?php echo $pending_orders; ?></div>
            <div class="card">Users Registered: <?php echo $total_users; ?></div>
            <div class="card">Revenue: $<?php echo number_format($revenue, 2); ?></div>
        </div>

        <div class="chart-container">
            <h2>Analytics Overview</h2>
            <canvas id="analyticsChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        const analyticsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Products', 'Pending Orders', 'Total Users', 'Revenue'],
                datasets: [{
                    label: 'Statistics',
                    data: [<?php echo $total_products; ?>, <?php echo $pending_orders; ?>, <?php echo $total_users; ?>, <?php echo $revenue; ?>],
                    backgroundColor: ['#FF6384', '#FFCE56', '#36A2EB', '#4CAF50'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
