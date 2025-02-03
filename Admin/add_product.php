<?php
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
// Start the session

// Check if the admin is logged in (optional)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']; // File upload input

    // Create a Database instance and retrieve the connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    // Validate inputs
    if (empty($name) || empty($price)) {
        $error = "Name and price are required.";
    } elseif ($image['error'] !== UPLOAD_ERR_OK) {
        $error = "Error uploading the image.";
    } 
    else{
        $imageData = file_get_contents($image['tmp_name']); // Read file content
        $base64Image = base64_encode($imageData); // Convert to Base64
 // Insert the product into the database
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
    
    /*
    elseif ($image['error'] !== UPLOAD_ERR_OK) {
        $error = "Error uploading the image.";
    } 
    else {
        // Handle file upload
        $upload_dir = 'uploads/'; // Directory where images will be stored
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create the directory if it doesn't exist
        }

        $file_name = basename($image['name']);
        $target_file = $upload_dir . time() . '_' . $file_name;

        // Check file type
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        echo "Upload file type: $file_type";
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Insert the product into the database
            $stmt = $db->prepare("INSERT INTO products (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':image_url', $target_file);
            
            

            if ($stmt->execute()) {
                $success = "Product added successfully!";
            } else {
                $error = "Failed to add the product. Please try again.";
            }
        } else {
            $error = "Failed to upload the image.";
        }
        
        
    }*/
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Product</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST"  enctype="multipart/form-data">
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
</body>
</html>
