<?php
session_start();
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
include 'admin_nav.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Create database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch all products
$stmt = $db->prepare("SELECT product_id, name, description, price, image_url FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #DAD2FF; /* Light lavender background for a calm, sophisticated look */
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #FFFFFF; /* White content area */
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); /* Deeper shadow for more emphasis */
            padding: 40px;
        }

        h1 {
            color: #493D9E; /* Deep purple heading */
            font-weight: bold;
        }

        .table {
            background-color: #FFF2AF; /* Soft yellow background for the table */
            color: #444; /* Darker text for readability */
        }

        .table th {
            background-color: #493D9E; /* Deep purple header */
            color: white; /* White text for contrast */
        }

        .btn {
            background-color: #B2A5FF; /* Soft lavender button */
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #493D9E; /* Darker purple on hover */
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .table img {
            border-radius: 8px; /* Rounded images for a smoother look */
        }

        .alert {
            background-color: #FFF2AF; /* Soft yellow for alerts */
            color: #493D9E; /* Deep purple text */
            border-radius: 5px;
            padding: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Products</h1>
        
        <?php if (empty($products)): ?>
            <div class="alert">No products found.</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                        <td>
                            <img src="data:image/png;base64,<?php echo $product['image_url']; ?>" width="80" height="80">
                        </td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <a href="update_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm">Update</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
