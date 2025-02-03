<?php
session_start();
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

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
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
                    <?php 
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total_price += $subtotal;
                    ?>
                        <tr>
                            <td><img src="<?= $item['image']; ?>" width="50"></td>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>$<?= number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="update_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $id; ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1">
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </form>
                            </td>
                            <td>$<?= number_format($subtotal, 2); ?></td>
                            <td>
                                <a href="remove_from_cart.php?product_id=<?= $id; ?>" class="btn btn-sm btn-danger">Remove</a>
                            </td>
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
