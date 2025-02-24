<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../database.php';

// Ensure the admin is logged in
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}*/

// Establish database connection
try {
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Handle stock updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_stock'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

        if ($new_quantity === false || $new_quantity < 0) {
            $error_message = "Invalid stock quantity.";
        } else {
            try {
                $stmt = $db->prepare("UPDATE stock SET quantity = :quantity WHERE product_id = :product_id");
                $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->execute();
                $success_message = "Stock updated successfully.";
            } catch (PDOException $e) {
                $error_message = "Error updating stock: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['delete_stock'])) {
        $product_id = $_POST['product_id'];

        try {
            $stmt = $db->prepare("DELETE FROM stock WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $success_message = "Stock deleted successfully.";
        } catch (PDOException $e) {
            $error_message = "Error deleting stock: " . $e->getMessage();
        }
    }
}

// Fetch stock data
try {
    $stmt = $db->prepare("
        SELECT s.product_id, p.name, s.quantity 
        FROM stock s
        JOIN products p ON s.product_id = p.product_id
    ");
    $stmt->execute();
    $stock_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching stock data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:  #B2A5FF;
            color: #640D5F;
        }
        .container {
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }
        h1 {
            color: #D91656;
            text-align: center;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #D91656;
            border: none;
        }
        .btn-primary:hover {
            background-color: #493D9E;
        }
        .btn-danger {
            background-color: #640D5F;
            border: none;
        }
        .btn-danger:hover {
            background-color: #D91656;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Stock</h1>

        <!-- Display Messages -->
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Current Stock</th>
                    <th>Update Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stock_items)) : ?>
                    <tr>
                        <td colspan="4" class="text-center">No stock available.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($stock_items as $item) : ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= htmlspecialchars($item['quantity']); ?></td>
                            <td>
                                <form action="manage_stock.php" method="POST" class="d-flex">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                                    <input type="number" name="quantity" class="form-control me-2" min="0" required>
                                    <button type="submit" name="update_stock" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                            <td>
                                <form action="manage_stock.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                                    <button type="submit" name="delete_stock" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this stock item?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
