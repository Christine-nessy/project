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
    <style>
        body {
            background: url('images/background.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #333;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 350px;
        }

        .dashboard-box {
            background: rgba(0, 0, 0, 0.85);
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
            background: #ff6600;
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 5px;
            margin-top: 10px;
            transition: 0.3s;
            font-size: 16px;
        }

        .track-order-btn:hover {
            background: #cc5500;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <div class="dashboard-box">
            <h3>Total Orders</h3>
            <p><?php echo $total_orders; ?></p>
        </div>

        <a href="track_order.php" class="track-order-btn">Track Your Orders</a>
    </div>

</body>
</html>
