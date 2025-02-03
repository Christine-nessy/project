<?php
include '../database.php'; // Include database connection

// Create a Database instance and retrieve the connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch product data from the database
$stmt = $db->prepare("SELECT name, description, price, image_url FROM products WHERE product_id = 17");
//$stmt->bindParam(':product_id', $_GET['product_id']);  Assuming product ID is passed in the URL
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if product exists
if ($product) {
    $imageBase64 = $product['image_url']; // Get the Base64 image string from the database
    $imageType = 'image/jpeg'; // Assuming the image is a JPEG (adjust as needed)
} else {
    $error = "Product not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>

            <!-- Display the image using the Base64 string -->
            <img src="data:<?php echo $imageType; ?>;base64,<?php echo $imageBase64; ?>" alt="Product Image" class="img-fluid">
        </div>
    </div>
</body>
</html>
