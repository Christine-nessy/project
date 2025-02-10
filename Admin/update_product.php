<?php
session_start();
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection
include 'admin_nav.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

$product = null;

// Step 1: Ask for Product ID if not provided
if (!isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Enter Product ID</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="mb-3">Enter Product ID to Update</h2>
            <form method="GET">
                <div class="mb-3">
                    <label class="form-label">Product ID</label>
                    <input type="number" class="form-control" name="id" required>
                </div>
                <button type="submit" class="btn btn-primary">Fetch Product</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Step 2: Fetch product details
$product_id = $_GET['id'] ?? $_POST['id'] ?? null;

if ($product_id) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Error: Product not found.</div></div>";
        exit;
    }
}

// Step 3: Handle Update Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($name) || empty($price)) {
        $error = "Name and price are required.";
    } else {
        if ($image['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($image['tmp_name']);
            $base64Image = base64_encode($imageData);

            $stmt = $db->prepare("UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id");
            $stmt->bindParam(':image_url', $base64Image);
        } else {
            $stmt = $db->prepare("UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id");
        }

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $success = "Product updated successfully!";
        } else {
            $error = "Failed to update product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Update Product</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" class="form-control" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <p>Current Image:</p>
                <img src="data:image/png;base64,<?php echo $product['image_url']; ?>" width="100" />
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>
