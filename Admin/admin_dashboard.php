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
            background-color: #DAD2FF; /* #DAD2FF */
            color: #493D9E; /* #493D9E */
        }

        /* Style the main content box */
        .content-box {
            background: #B2A5FF; /* #B2A5FF */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 550px;
            margin-bottom: 40px;
            color: #493D9E; /* #493D9E */
            font-size: 24px;
            font-weight: bold;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        /* Container for the links */
        .link-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            max-width: 900px;
        }

        /* Style individual link boxes */
        .link-box {
            background: #493D9E; /* #493D9E */
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            width: 280px;
            font-size: 22px;
            font-weight: bold;
            color: #FFF2AF; /* #FFF2AF */
        }

        .link-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            background: #B2A5FF; /* #B2A5FF */
            color: #493D9E; /* #493D9E */
        }

        .link-box a {
            text-decoration: none;
            color: inherit;
            display: block;
            padding: 15px;
            border-radius: 5px;
            transition: color 0.3s ease;
        }

        .link-box a:hover {
            color: #DAD2FF; /* #DAD2FF */
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
