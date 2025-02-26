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
class DatabaseConnection {
    private $db;

    public function __construct() {
        $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
        $this->db = $db_instance->getConnection();
    }

    public function getConnection() {
        return $this->db;
    }
}

// Shopping Cart Class
class ShoppingCart {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function getCartItems() {
        $stmt = $this->db->prepare("
            SELECT sc.product_id, sc.quantity, p.name, p.price, p.image_url 
            FROM shopping_cart sc
            JOIN products p ON sc.product_id = p.product_id
            WHERE sc.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearCart() {
        $stmt = $this->db->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// Order Processing Class
class OrderProcessor {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function placeOrder($name, $shipping_address, $payment_method, $cart_items) {
        try {
            $this->db->beginTransaction();

            $total_price = array_reduce($cart_items, function ($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            // Insert order
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, name, shipping_address, payment_method, total_price, status) 
                VALUES (:user_id, :name, :shipping_address, :payment_method, :total_price, 'Pending')
            ");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':shipping_address', $shipping_address, PDO::PARAM_STR);
            $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
            $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
            $stmt->execute();
            $order_id = $this->db->lastInsertId();

            // Insert order items
            foreach ($cart_items as $item) {
                $stmt = $this->db->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (:order_id, :product_id, :quantity, :price)
                ");
                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
                $stmt->execute();
            }

            // Commit transaction
            $this->db->commit();

            return $order_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error processing order: " . $e->getMessage());
        }
    }
}

// Initialize database connection
$db_instance = new DatabaseConnection();
$db = $db_instance->getConnection();

// Handle cart operations
$cart = new ShoppingCart($db, $user_id);
$cart_items = $cart->getCartItems();

if (empty($cart_items)) {
    echo "<p>Your cart is empty. <a href='../products.php'>Shop now</a></p>";
    exit;
}

// Handle order processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $shipping_address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    $orderProcessor = new OrderProcessor($db, $user_id);
    try {
        $order_id = $orderProcessor->placeOrder($name, $shipping_address, $payment_method, $cart_items);
        $cart->clearCart();
        header("Location: confirmation.php?order_id=" . $order_id);
        exit;
    } catch (Exception $e) {
        die($e->getMessage());
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