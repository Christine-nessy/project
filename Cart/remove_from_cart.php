<?php
session_start(); // Ensure session is started
include '../database.php'; // Include your database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Get the user ID and product ID
$user_id = $_SESSION['user_id'];
$product_id = intval($_GET['product_id']);

try {
    // Create a database connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    // Delete the product from the user's shopping cart
    $stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to the cart page with success message
    header("Location: cart.php?success=Item removed from cart");

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
