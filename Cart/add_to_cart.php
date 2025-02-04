<?php
session_start(); // Ensure session is started before accessing $_SESSION

include '../database.php'; // Include database connection
include 'admin_nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) {
        header("Location: ../products.php?error=Invalid quantity");
        exit;
    }

    // Create database connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    try {
        // Fetch product details from DB
        $stmt = $db->prepare("SELECT product_id, name, price, image_url FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Initialize cart session if not set
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check if product exists in cart
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

            // Redirect to cart with success message
            header("Location: ../Cart/cart.php?success=Product added to cart");
            exit;
        } else {
            header("Location: ../products.php?error=Product not found");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: ../products.php?error=Database error: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: ../products.php?error=Invalid request");
    exit;
}
