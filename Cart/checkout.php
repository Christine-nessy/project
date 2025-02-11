<?php
session_start();
include '../database.php'; // Include database connection

// Ensure user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login.php?error=Please log in to proceed to checkout");
    exit;
}

// Connect to database
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch cart items
$stmt = $db->prepare("SELECT sc.product_id, p.name, p.image_url, sc.quantity, sc.price, (sc.quantity * sc.price) AS total_price 
                      FROM shopping_cart sc 
                      JOIN products p ON sc.product_id = p.product_id 
                      WHERE sc.user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total cost
$total_cost = 0;
foreach ($cart_items as $item) {
    $total_cost += $item['total_price'];
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    try {
        // Insert order details
        $stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (:user_id, :total_amount, NOW())");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount', $total_cost, PDO::PARAM_STR);
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

        // Clear cart after checkout
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: order_confirmation.php?success=Order placed successfully");
        exit;
    } catch (PDOException $e) {
        header("Location: checkout.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Checkout</h2>
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php if (!empty($cart_items)) : ?>
            <?php foreach ($cart_items as $item) : ?>
                <tr>
                    <td><img src="<?= $item['image_url'] ?>" width="50" height="50"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>KES <?= number_format($item['price'], 2) ?></td>
                    <td>KES <?= number_format($item['total_price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Total:</strong></td>
                <td><strong>KES <?= number_format($total_cost, 2) ?></strong></td>
            </tr>
        <?php else : ?>
            <tr><td colspan="5">Your cart is empty.</td></tr>
        <?php endif; ?>
    </table>

    <?php if (!empty($cart_items)) : ?>
        <form method="post">
            <button type="submit" name="checkout">Confirm Order</button>
            <a href="order_confirmation.php"></a>
        </form>
    <?php endif; ?>
</body>
</html>
