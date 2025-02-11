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

// Create a Database instance and retrieve the connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch orders from the database
$stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Order Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Update the order status
    $stmt = $db->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $success = "Order status updated successfully!";
    } else {
        $error = "Failed to update order status. Please try again.";
    }

    // Refresh page to reflect changes
    header("Location: admin_orders.php");
    exit;
}

// Handle Return/Refund Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_return'])) {
    $order_id = intval($_POST['order_id']);
    $return_status = $_POST['return_status'];

    // Update return status for the order
    $stmt = $db->prepare("UPDATE orders SET return_status = :return_status WHERE order_id = :order_id");
    $stmt->bindParam(':return_status', $return_status, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $success = "Return/Refund processed successfully!";
    } else {
        $error = "Failed to process the return/refund. Please try again.";
    }

    // Refresh page to reflect changes
    header("Location: admin_orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Customer Orders</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Orders Table -->
        <?php if (!empty($orders)): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Order Date</th>
                    <th>Total Price ($)</th>
                    <th>Status</th>
                    <th>Return Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td><?php echo number_format($order['total_price'], 2); ?></td>
                        <td>
                            <form method="POST">
                                <select name="status" class="form-select">
                                    <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="order_id" value="<?php echo intval($order['order_id']); ?>">
                                <button type="submit" name="update_status" class="btn btn-primary mt-2">Update Status</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST">
                                <select name="return_status" class="form-select">
                                    <option value="Not Requested" <?php echo ($order['return_status'] == 'Not Requested') ? 'selected' : ''; ?>>Not Requested</option>
                                    <option value="Requested" <?php echo ($order['return_status'] == 'Requested') ? 'selected' : ''; ?>>Return Requested</option>
                                    <option value="Processed" <?php echo ($order['return_status'] == 'Processed') ? 'selected' : ''; ?>>Return Processed</option>
                                </select>
                                <input type="hidden" name="order_id" value="<?php echo intval($order['order_id']); ?>">
                                <button type="submit" name="process_return" class="btn btn-warning mt-2">Process Return</button>
                            </form>
                        </td>
                        <td>
                            <a href="view_order_details.php?order_id=<?php echo intval($order['order_id']); ?>" class="btn btn-info btn-sm">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="alert alert-info">No orders available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
