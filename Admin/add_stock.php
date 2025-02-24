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

    if ($quantity < 5) {
        mail("nessymungla5@gmail.com", "Low Stock Alert", "Product $product_id is running low!");
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #493D9E;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
            color: #333;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #493D9E;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 20px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #B2A5FF;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #493D9E;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-link:hover {
            text-decoration: underline;
        }
        
        .front-link {
            display: inline-block;
            margin-top: 15px;
            color: #493D9E;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .front-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-box"></i> Add Stock</h2>
        <form method="POST">
            <label for="product">Select Product:</label>
            <select name="product_id" required>
                <option value="">-- Select a Product --</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Enter Quantity:</label>
            <input type="number" name="quantity" required min="1">

            <button type="submit"><i class="fas fa-plus"></i> Add Stock</button>
        </form>
        <a href="admin_dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <br>
        <a href="manage_stock.php" class="front-link"><i class="fas fa-arrow-right"></i> Manage Stock</a>
    </div>
</body>
</html>
