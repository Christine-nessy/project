<?php
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
include 'admin_nav.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Create database instance
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    $stmt = $db->prepare("DELETE FROM products WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: delete_product.php"); // Refresh page after deletion
        exit;
    } else {
        echo "<script>alert('Failed to delete product.');</script>";
    }
}

// Fetch all products
$stmt = $db->prepare("SELECT product_id, name FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Delete Product</h1>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning">No products available.</div>
        <?php else: ?>
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
