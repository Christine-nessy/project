<?php
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection

// Start the session
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Create a Database instance and retrieve the connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    // Validate inputs
    if (empty($name) || empty($price)) {
        $error = "Name and price are required.";
    } elseif ($image['error'] !== UPLOAD_ERR_OK) {
        $error = "Error uploading the image.";
    } else {
        $imageData = file_get_contents($image['tmp_name']);
        $base64Image = base64_encode($imageData);
        $stmt = $db->prepare("INSERT INTO products (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $base64Image);

        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add the product. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            background-color: #DAD2FF;
            color: #493D9E;
            font-family: Arial, sans-serif;
        }
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
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="update_product.php">Update Product</a>
        <a href="delete_product.php">Delete Product</a>
        <a href="view_products.php">View Products</a>
        <a href="admin_orders.php">View Orders</a>
        <a href="view_users.php">View Users</a>
        <a href="view_analytics.php">Analytics</a>
        <a href="add_stock.php">Manage Stock</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <h1 class="mb-4">Add New Product</h1>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</body>
</html>
