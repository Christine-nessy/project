<?php
session_start();
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

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <h3>Order Summary</h3>
            <ul>
                <?php 
                $total_price = 0;
                foreach ($_SESSION['cart'] as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_price += $subtotal;
                ?>
                    <li><?= htmlspecialchars($item['name']); ?> - <?= $item['quantity']; ?> x $<?= number_format($item['price'], 2); ?> = $<?= number_format($subtotal, 2); ?></li>
                <?php endforeach; ?>
            </ul>

            <h3>Total: $<?= number_format($total_price, 2); ?></h3>

            <form action="process_order.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">Place Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
