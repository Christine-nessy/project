<?php
// Ensure the admin is authenticated
include 'admin_auth.php';
?>

<style>
    /* Navigation bar styling */
    .nav-container {
        text-align: center; /* Center the links */
        background-color: rgb(235, 230, 236); /* Light background color */
        padding: 15px 0; /* Spacing above and below */
    }

    .nav-container a {
        text-decoration: none;
        color: #4CAF50;
        font-size: 18px;
        margin: 0 15px; /* Space between links */
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .nav-container a:hover {
        background-color: #4CAF50;
        color: white;
    }
</style>

<div class="nav-container">
    <a href="add_product.php">Insert New Product</a>
    <a href="update_product.php">Update Product</a>
    <a href="delete_product.php">Delete Product</a>
    <a href="view_products.php">View Product</a>
    <a href="admin_orders.php">View Orders</a>
    <a href="view_users.php">View Users</a>
    <a href="analytics.php">Analytics</a>
    <a href="admin_login.php">Logout</a>
</div>
