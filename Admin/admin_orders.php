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
$stmt = $db->prepare("SELECT * FROM orders ORDER BY order_date DESC");
$stmt->execute();
$orders = $stmt->fetchAll();

// Handle Order Status Update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status
    $stmt = $db->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':order_id', $order_id);

    if ($stmt->execute()) {
        $success = "Order status updated successfully!";
    } else {
        $error = "Failed to update order status. Please try again.";
    }
}

// Handle Return/Refund Request
if (isset($_POST['process_return'])) {
    $order_id = $_POST['order_id'];
    $return_status = $_POST['return_status'];

    // Update return status for the order
    $stmt = $db->prepare("UPDATE orders SET return_status = :return_status WHERE order_id = :order_id");
    $stmt->bindParam(':return_status', $return_status);
    $stmt->bindParam(':order_id', $order_id);

    if ($stmt->execute()) {
        $success = "Return/Refund processed successfully!";
    } else {
        $error = "Failed to process the return/refund. Please try again.";
    }
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Return Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                        <td>
                            <form method="POST">
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="completed" <?php echo ($order['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="update_status" class="btn btn-primary mt-2">Update Status</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST">
                                <select name="return_status" class="form-select">
                                    <option value="not_requested" <?php echo ($order['return_status'] == 'not_requested') ? 'selected' : ''; ?>>Not Requested</option>
                                    <option value="requested" <?php echo ($order['return_status'] == 'requested') ? 'selected' : ''; ?>>Return Requested</option>
                                    <option value="processed" <?php echo ($order['return_status'] == 'processed') ? 'selected' : ''; ?>>Return Processed</option>
                                </select>
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="process_return" class="btn btn-warning mt-2">Process Return/Refund</button>
                            </form>
                        </td>
                        <td>
                            <!-- Other Actions like view details or cancel -->
                            <a href="view_order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
