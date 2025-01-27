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
        /* Style the links */
        a {
            text-decoration: none; /* Remove underline */
            color: #4CAF50; /* Green color */
            font-size: 18px; /* Set font size */
            margin: 10px 0; /* Space between links */
            padding: 10px; /* Add padding around the link text */
            display: inline-block; /* Display links as block-level elements */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s ease, color 0.3s ease; /* Add transition effect */
        }

        /* Change color when hovering over the link */
        a:hover {
            background-color: #4CAF50; /* Green background on hover */
            color: white; /* White text on hover */
        }

        /* Add some spacing and center the links */
        body {
            font-family: Arial, sans-serif; /* Set font */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Full viewport height */
            margin: 0;
            background-color:rgb(235, 230, 236); /* Light background color */
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin!</h1>
    <a href="add_product.php">Insert New Product</a>
    <a href="update_product.php">Update Product</a>
    <a href="delete_product.php">Delete Product</a>
    <a href="view_products.php">View Product</a>
    <a href="logout.php">Logout</a>
</body>
</html>
