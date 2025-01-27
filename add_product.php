<?php
session_start();

// Redirect to login page if the admin is not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location:  dashboard.php");
    exit;
}

// Include database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Check if the image file is a valid image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Move the uploaded image to the target directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert the product details into the database
        $query = "INSERT INTO products (name, description, price, category, image) VALUES (:name, :description, :price, :category, :image)";
        $stmt = $db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':name', $product_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image', $image);
        
        // Execute query
        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error adding product.";
        }
    } else {
        echo "Sorry, there was an error uploading your image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Product</h2>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Books">Books</option>
                    <!-- Add more categories as needed -->
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>
</html>
