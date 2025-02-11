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

$order_details = null;
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    // Fetch order details
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = :order_id AND user_id = :user_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $order_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order_details) {
        $error = "Order not found or does not belong to you.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Track Your Order</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="order_id" class="form-label">Enter Order ID:</label>
                <input type="number" name="order_id" id="order_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Track Order</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($order_details): ?>
            <div class="alert alert-success mt-3">
                <h4>Order Details</h4>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_details['order_id']); ?></p>
                <p><strong>Total Price:</strong> $<?php echo number_format($order_details['total_price'], 2); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order_details['status']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order_details['created_at']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
