<?php
session_start();
include '../database.php'; // Include database connection

// Check if product_id and quantity are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

    // Validate quantity
    if ($quantity === false || $quantity < 1) {
        header("Location: ../products.php?error=Invalid quantity");
        exit;
    }

    // Create database connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    try {
        // Fetch product details from the database
        $stmt = $db->prepare("SELECT product_id, name, price, image_url FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Initialize cart session if not set
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check if the product exists in the cart
            if (isset($_SESSION['cart'][$product_id])) {
                // Update the quantity if it already exists in the cart
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                // Add the product to the cart
                $_SESSION['cart'][$product_id] = [
                    'id' => $product['product_id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image_url'],
                    'quantity' => $quantity
                ];
            }

            // Redirect to cart page with success message
            header("Location: cart.php?success=Product added to cart");
            exit;
        } else {
            // If product not found in the database
            header("Location: ../products.php?error=Product not found");
            exit;
        }
    } catch (PDOException $e) {
        // Handle database errors
        header("Location: ../products.php?error=Database error: " . $e->getMessage());
        exit;
    }
} else {
    // If invalid request
    header("Location: ../products.php?error=Invalid request");
    exit;
}
?>
