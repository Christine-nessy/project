<?php
session_start();
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['payment_method'])) {
    header("Location: checkout.php?error=Please select a payment method.");
    exit;
}

// Ensure user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login.php?error=Please log in to checkout");
    exit;
}

$payment_method = $_POST['payment_method'];
$total_price = floatval($_POST['total_price']);

// Database connection
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

try {
    $db->beginTransaction();

    // Fetch cart items
    $stmt = $db->prepare("
        SELECT sc.product_id, sc.quantity, p.price 
        FROM shopping_cart sc
        JOIN products p ON sc.product_id = p.product_id
        WHERE sc.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        throw new Exception("Your cart is empty.");
    }

    // Insert order
    $stmt = $db->prepare("
        INSERT INTO orders (user_id, total_amount, payment_method, order_date, status) 
        VALUES (:user_id, :total_amount, :payment_method, NOW(), 'Pending')
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':total_amount', $total_price, PDO::PARAM_STR);
    $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
    $stmt->execute();
    $order_id = $db->lastInsertId();

    // Insert order items
    $stmt = $db->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price) 
        VALUES (:order_id, :product_id, :quantity, :price)
    ");
    foreach ($cart_items as $item) {
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

    $db->commit();

    // Redirect based on payment method
    if ($payment_method == "paypal") {
        header("Location: paypal_payment.php?order_id=" . $order_id);
    } elseif ($payment_method == "credit_card") {
        header("Location: credit_card_payment.php?order_id=" . $order_id);
    } elseif ($payment_method == "mpesa") {
        header("Location: mpesa_payment.php?order_id=" . $order_id);
    } else {
        header("Location: order_confirmation.php?order_id=" . $order_id);
    }
    exit;
} catch (Exception $e) {
    $db->rollBack();
    header("Location: checkout.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>
