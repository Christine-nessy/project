<?php
include 'database.php';
// Array of products (You can replace this with a database connection if needed)
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch product data from the database
$stmt = $db->prepare("SELECT product_id, name, price, image_url FROM products ");
//$stmt->bindParam(':product_id', $_GET['product_id']);  Assuming product ID is passed in the URL
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if product exists

   // $imageBase64 = $products['image_url']; Get the Base64 image string from the database
    $imageType = 'image/jpeg'; // Assuming the image is a JPEG (adjust as needed)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - HookedByNessy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
             background: linear-gradient(to bottom, #f8f9fa, #e0e7f5); 
           
            color: #333;
        }
        .navbar {
            background: linear-gradient(
        to bottom, 
        rgba(243, 215, 244, 0.8),
        rgba(243, 215, 244, 0.8), /* Light mint green at the top */
        rgba(180, 120, 146, 0.7)  /* Darker green at the bottom */
            )
        }
        .navbar .navbar-brand, .navbar .nav-link {
            color: black !important;
        }
        .navbar .nav-link:hover {
            color: #ddd !important;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .product-card h5 {
            color: #4a90e2;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HookedByNessy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about_us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Products Section -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Our Products</h1>
        <div class="row">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="data:<?php echo $imageType; ?>;base64,<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        <h5><?php echo $product['name']; ?></h5>
                        <p class="text-muted">$<?php echo number_format($product['price'], 2); ?></p>
                        <form action="Cart/cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
                            <input type="hidden" name="price" value="<?= $product['price'] ?>">
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
