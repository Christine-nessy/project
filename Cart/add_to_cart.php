<?php
session_start();
include '../database.php'; // Include database connection

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);

    // Create database connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    try {
        // Fetch product details
        $stmt = $db->prepare("SELECT product_id, name, price, image_url FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Initialize cart if empty
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Add or update cart
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $product['product_id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image_url'],
                    'quantity' => $quantity
                ];
            }

            header("Location: Cart/cart.php?success=Product added to cart");
            exit;
        } else {
            header("Location: products.php?error=Product not found");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: products.php?error=Database error: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: products.php?error=Invalid request");
    exit;
}
?>
