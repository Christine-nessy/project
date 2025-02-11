<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
    exit;
}

include 'database.php'; // Ensure this path is correct

try {
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch the user's ID using 'id' column
$stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
$stmt->bindParam(':username', $_SESSION['username']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Error: User not found.");
}

$user_id = $user['id'];

// Fetch total orders for the user
$stmt = $db->prepare("SELECT COUNT(*) AS order_count FROM orders WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$order_data = $stmt->fetch(PDO::FETCH_ASSOC);
$total_orders = $order_data ? $order_data['order_count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100%;
            background: linear-gradient(
                to bottom, 
                rgba(243, 215, 244, 0.8),
                rgba(243, 215, 244, 0.8), 
                rgba(180, 120, 146, 0.7) 
            );
            backdrop-filter: blur(10px);
            padding: 15px;
            color: black;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar a {
            color: black;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sidebar a:hover {
            background-color: rgba(53, 122, 189, 0.7);
            transform: scale(1.05);
        }

        /* Main Content */
        .content {
            margin-left: 270px;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Dashboard Container */
        .dashboard-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(241, 36, 203, 0.3);
            text-align: center;
            width: 350px;
        }

        .dashboard-box {
            background: rgba(157, 39, 104, 0.85);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .dashboard-box h3 {
            margin: 0;
            font-size: 24px;
        }

        .dashboard-box p {
            font-size: 20px;
            margin: 10px 0;
            font-weight: bold;
        }

        .track-order-btn {
            display: block;
            background:rgb(123, 222, 2);
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 5px;
            margin-top: 10px;
            transition: 0.3s;
            font-size: 16px;
        }

        .track-order-btn:hover {
            background:rgb(0, 204, 14);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="about_us.php">About Us</a>
        <a href="products.php">Products</a>
        <a href="Cart/cart.php">Catalogue</a>
        <a href="Settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="dashboard-container">
           

            <div class="dashboard-box">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>

            <a href="track_order.php" class="track-order-btn">Track Your Orders</a>
        </div>
    </div>

</body>
</html>
