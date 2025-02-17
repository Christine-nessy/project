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
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: rgb(235, 230, 236);
        }

        /* Style the main content box */
        .content-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 500px;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Container for the links */
        .link-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 900px;
        }

        /* Style individual link boxes */
        .link-box {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            width: 220px;
        }

        .link-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .link-box a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 20px;
            display: block;
            padding: 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .link-box a:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="content-box">
        <h1>Welcome, Admin!</h1>
    </div>
    <div class="link-container">
        <div class="link-box"><a href="add_product.php">Insert New Product</a></div>
        <div class="link-box"><a href="update_product.php">Update Product</a></div>
        <div class="link-box"><a href="delete_product.php">Delete Product</a></div>
        <div class="link-box"><a href="view_products.php">View Product</a></div>
        <div class="link-box"><a href="admin_orders.php">View Orders</a></div>
        <div class="link-box"><a href="view_users.php">View Users</a></div>
        <div class="link-box"><a href="view_analytics.php">View Analytics</a></div>
        <div class="link-box"><a href="admin_logout.php">Logout</a></div>
    </div>
</body>
</html>
