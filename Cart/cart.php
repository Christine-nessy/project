
<?php
session_start();
include '../database.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

$stmt = $db->prepare("SELECT product_id,quantity FROM shopping_cart  WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$price=$cart_items['product_id'];
$sqlt = $db->prepare("SELECT price, FROM shopping_cart  WHERE user_id = :user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Shopping Cart</h1>
        <?php if (!empty($cart_items)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total_price = 0; ?>
                    <?php foreach ($cart_items as $item): ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; ?>
                        <?php $total_price += $subtotal; ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['image_url']); ?>" width="50" onerror="this.src='default.jpg';" alt="Product Image"></td>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>$<?= number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="update_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= intval($item['product_id']); ?>">
                                    <input type="number" name="quantity" value="<?= intval($item['quantity']); ?>" min="1">
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </form>
                            </td>
                            <td>$<?= number_format($subtotal, 2); ?></td>
                            <td><a href="remove_from_cart.php?product_id=<?= intval($item['product_id']); ?>" class="btn btn-sm btn-danger">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: $<?= number_format($total_price, 2); ?></h3>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
