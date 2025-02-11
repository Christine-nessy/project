<?php
session_start();
include '../database.php';

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login_form.php");
    exit;
}

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch cart items
$stmt = $db->prepare("
    SELECT sc.product_id, sc.quantity, p.name, p.price, p.image_url 
    FROM shopping_cart sc
    JOIN products p ON sc.product_id = p.product_id
    WHERE sc.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Process checkout if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert order into database
    $stmt = $db->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, 'Pending')");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $stmt->execute();
    $order_id = $db->lastInsertId();

    // Insert order items
    foreach ($cart_items as $item) {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
        $stmt->execute();
    }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a unique confirmation code (6-character alphanumeric)
    function generateConfirmationCode($length = 6) {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
    }

    $confirmation_code = generateConfirmationCode();

    // Insert order into database
    $stmt = $db->prepare("INSERT INTO orders (user_id, total_price, status, confirmation_code) 
                          VALUES (:user_id, :total_price, 'Pending', :confirmation_code)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $stmt->bindParam(':confirmation_code', $confirmation_code, PDO::PARAM_STR);
    $stmt->execute();
    $order_id = $db->lastInsertId();

    // Insert order items
    foreach ($cart_items as $item) {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
        $stmt->execute();
    }

    // Clear shopping cart
    $stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to confirmation page
    header("Location: confirmation.php?order_id=$order_id&code=$confirmation_code");
    exit;
}

    // // Clear shopping cart
    // $stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
    // $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    // $stmt->execute();

    // Redirect to confirmation page
    header("Location: confirmation.php?order_id=" . $order_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Checkout</h1>
        
        <?php if (!empty($cart_items)): ?>
            <h3>Total Amount: $<?= number_format($total_price, 2); ?></h3>

            <form action="checkout.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Shipping Address</label>
                    <textarea id="address" name="address" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="payment" class="form-label">Payment Method</label>
                    <select id="payment" name="payment_method" class="form-control">
                        <option value="Credit Card">Credit Card</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Mpesa">Mpesa</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Confirm Order</button>
                <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
            </form>
        <?php else: ?>
            <p>Your cart is empty. <a href="../products.php">Shop now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
