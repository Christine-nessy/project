<?php
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Include database connection file

// Get the database connection
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection error.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product already has stock
    $checkStock = $conn->prepare("SELECT * FROM stock WHERE product_id = ?");
    $checkStock->execute([$product_id]);

    if ($checkStock->rowCount() > 0) {
        // Update stock if product exists
        $updateStock = $conn->prepare("UPDATE stock SET quantity = quantity + ? WHERE product_id = ?");
        $updateStock->execute([$quantity, $product_id]);
    } else {
        // Insert new stock record
        $insertStock = $conn->prepare("INSERT INTO stock (product_id, quantity) VALUES (?, ?)");
        $insertStock->execute([$product_id, $quantity]);
    }

    echo "<script>alert('Stock updated successfully!'); window.location.href='add_stock.php';</script>";
}

// Fetch all products for the dropdown
$products = $conn->query("SELECT product_id, name FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content">
        <h2>Add Stock</h2>
        <form method="POST">
            <label for="product">Select Product:</label>
            <select name="product_id" required>
                <option value="">--Select a Product--</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Enter Quantity:</label>
            <input type="number" name="quantity" required min="1">

            <button type="submit">Add Stock</button>
        </form>
    </div>
</body>
</html>
