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

// Ensure `orders` table has required columns
$product_id_column = 'product_id';  // Adjust this if your column name is different

// Fetch cart items
$stmt = $db->prepare("
    SELECT sc.product_id, sc.quantity, p.name, p.price, p.image_url 
    FROM shopping_cart sc
    JOIN products p ON sc.product_id = p.$product_id_column
    WHERE sc.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    echo "<p>Your cart is empty. <a href='../products.php'>Shop now</a></p>";
    exit;
}

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Process checkout if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db->beginTransaction();

        // Get form data
        $name = $_POST['name'];
        $shipping_address = $_POST['address'];
        $payment_method = $_POST['payment_method'];

        // Insert order into `orders` table
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, name, shipping_address, payment_method, total_price, status) 
            VALUES (:user_id, :name, :shipping_address, :payment_method, :total_price, 'Pending')
        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':shipping_address', $shipping_address, PDO::PARAM_STR);
        $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
        $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $stmt->execute();
        $order_id = $db->lastInsertId();

        // Insert order items
        foreach ($cart_items as $item) {
            $stmt = $db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (:order_id, :product_id, :quantity, :price)
            ");
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
            $stmt->execute();
        }

        // Clear shopping cart after successful checkout
        $stmt = $db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit transaction
        $db->commit();

        // Redirect to order confirmation page
        header("Location: confirmation.php?order_id=" . $order_id);
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        die("Error processing order: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Page Background */
        body {
            font-family: Arial, sans-serif;
            background-color: #FFB200; /* Warm yellow background */
            color: #640D5F; /* Dark purple for contrast */
        }

        /* Container Styling */
        .container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        /* Headings */
        h1, h3 {
            color: #D91656;
            font-weight: bold;
        }

        /* Labels */
        .form-label {
            font-weight: bold;
            color: #EB5B00;
        }

        /* Input Fields */
        .form-control {
            border: 2px solid #D91656;
            border-radius: 8px;
            padding: 10px;
        }

        .form-control:focus {
            border-color: #EB5B00;
            box-shadow: 0 0 5px rgba(233, 91, 0, 0.5);
        }

        /* Buttons */
        .btn-success {
            background-color: #D91656;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-success:hover {
            background-color: #EB5B00;
        }

        .btn-secondary {
            background-color: #640D5F;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-secondary:hover {
            background-color: #D91656;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Checkout</h1>
        
        <h3 class="text-center">Total Amount: $<?= number_format($total_price, 2); ?></h3>

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

            <div class="d-flex justify-content-between">
                <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
                <button type="submit" class="btn btn-success">Confirm Order</button>
            </div>
        </form>
    </div>
</body>
</html>
