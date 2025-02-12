<?php
include 'database.php';
include 'general_navbar.php';
// Array of products (You can replace this with a database connection if needed)
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Check if a search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch product data from the database with an optional search filter
$query = "SELECT product_id, name, price, image_url FROM products";
if ($searchTerm) {
    $query .= " WHERE name LIKE :searchTerm"; // Filter products by name
}

$stmt = $db->prepare($query);
if ($searchTerm) {
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%'); // Bind the search term
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        /* Solid background color */
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(103, 28, 99); /* Dark Purple */
            color: #FFF; /* White text for readability */
        }

        /* Product card styling */
        .product-card {
            border: 2px solid #EB5B00;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Hover effect */
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        /* Product image */
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        /* Product name */
        .product-card h5 {
            color: #D91656;
            font-weight: bold;
        }

        /* Price styling */
        .product-card p {
            color: #640D5F;
            font-weight: bold;
        }

        /* Buttons */
        .btn-primary {
            background-color: #D91656;
            border: none;
        }

        .btn-primary:hover {
            background-color: #EB5B00;
        }

        /* Search bar */
        .form-control {
            border: 2px solid #EB5B00;
        }

        .btn-search {
            background-color: #FFB200;
            border: none;
            color: black;
        }

        .btn-search:hover {
            background-color: #EB5B00;
            color: white;
        }

        /* Page Title */
        h1 {
            color: #FFF;
        }
    </style>
</head>
<body>

    <!-- Search Bar Section -->
    <div class="container mt-5">
        <form action="products.php" method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" name="search" class="form-control w-50" placeholder="Search products..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn btn-search ms-2">Search</button>
        </form>

        <h1 class="text-center mb-4">Our Products</h1>
        <div class="row">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (empty($products)): ?>
                <p class="text-center">No products found.</p>
            <?php endif; ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="data:<?php echo $imageType; ?>;base64,<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        <h5><?php echo $product['name']; ?></h5>
                        <p class="text-muted">$<?php echo number_format($product['price'], 2); ?></p>
                        <form action="Cart/add_to_cart.php" method="POST">
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
