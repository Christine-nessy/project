<?php
include 'admin_auth.php'; // Ensure admin is logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Style the body */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: rgb(235, 230, 236);
        }

        /* Style the content box */
        .content-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Style the links */
        a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 18px;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Change color when hovering over the link */
        a:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="content-box">
        <h1>Welcome, Admin!</h1>
        <a href="add_product.php">Insert New Product</a>
        <a href="update_product.php">Update Product</a>
        <a href="delete_product.php">Delete Product</a>
        <a href="view_products.php">View Product</a>
        <a href="admin_orders.php">View Orders</a>
        <a href="view_users.php">View Users</a>
        <a href="view_analytics.php">View Analytics</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</body>
</html>
