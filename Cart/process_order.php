<?php
session_start();
include '../database.php';

// Ensure user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login_form.php");
    exit;
}

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch cart items with stock validation
$stmt = $db->prepare("
    SELECT sc.product_id, sc.quantity, p.price, s.quantity AS stock_quantity 
    FROM shopping_cart sc
    JOIN products p ON sc.product_id = p.product_id
    JOIN stock s ON sc.product_id = s.product_id
    WHERE sc.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    die("<p>Your cart is empty. <a href='../products.php'>Shop now</a></p>");
}

// Validate stock availability
foreach ($cart_items as $item) {
    if ($item['quantity'] > $item['stock_quantity']) {
        die("<p style='color: red;'>Error: Insufficient stock for some items. <a href='../cart.php'>Return to cart</a></p>");
    }
}

// Process order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db->beginTransaction();

        // Get form data
        $name = $_POST['name'];
        $shipping_address = $_POST['address'];
        $payment_method = $_POST['payment_method'];
        $total_price = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart_items));

        // Insert order into orders table
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, name, shipping_address, payment_method, total_price, status) 
            VALUES (:user_id, :name, :shipping_address, :payment_method, :total_price, 'Pending')
        ");
        $stmt->execute([':user_id' => $user_id, ':name' => $name, ':shipping_address' => $shipping_address, ':payment_method' => $payment_method, ':total_price' => $total_price]);
        $order_id = $db->lastInsertId();

        // Insert order items and update stock
        foreach ($cart_items as $item) {
            $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            
            $stmt = $db->prepare("UPDATE stock SET quantity = quantity - ? WHERE product_id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }

        // Clear shopping cart
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $db->commit();
        
        header("Location: confirmation.php?order_id=" . $order_id);
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        die("Error processing order: " . $e->getMessage());
    }
} else {
    header("Location: checkout.php");
    exit;
}
?>
