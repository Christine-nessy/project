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
//width="50" onerror="this.src='default.jpg';" 
// Fetch products in the shopping cart with details from the products table <?= !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : 'default.jpg'"
$stmt = $db->prepare("
    SELECT sc.product_id, sc.quantity, p.name, p.price, p.image_url 
    FROM shopping_cart sc
    JOIN products p ON sc.product_id = p.product_id
    WHERE sc.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$image= $_SESSION["image"];
$imageType = 'image/jpeg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
           .navbar {
            background: linear-gradient(
                to right, 
                #FFB200,  /* Yellow */
                #EB5B00,  /* Orange */
                #D91656,  /* Pink */
                #640D5F   /* Dark Purple */
            );
        }

        /* Navbar text styles */
        .navbar .navbar-brand {
            color: #FFF !important;
            font-weight: bold;
        }

        .navbar .nav-link {
            color: #FFF !important;
            font-weight: 500;
            transition: color 0.3s ease-in-out;
        }

        /* Hover effect */
        .navbar .nav-link:hover {
            color: #FFB200 !important; /* Yellow on hover */
        }

        /* Navbar toggler */
        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar --> 
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HookedByNessy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../about_us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                        <?php 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total_price += $subtotal;
                        ?>
                        <tr>
                        
                            <td>
                                <img src="data:<?php echo $imageType; ?>;base64,<?php echo $item['image_url']; ?>" 
                                     width="130"
                                     alt="Product Image">
                            </td>
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
                            <td>
                                <a href="remove_from_cart.php?product_id=<?= intval($item['product_id']); ?>" class="btn btn-sm btn-danger">
                                    Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: $<?= number_format($total_price, 2); ?></h3>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            <a href="../products.php" class="btn btn-primary">Add More Products</a>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
