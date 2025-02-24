<?php
// Ensure the admin is authenticated
include 'admin_auth.php';
?>

<style>
    /* Navigation bar styling */
    .nav-container {
        display: flex;
        justify-content: center; /* Center the links */
        align-items: center;
        background-color: #DAD2FF; /* Light background color from palette */
        padding: 15px 0; /* Spacing above and below */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    .nav-container a {
        text-decoration: none;
        color: #493D9E; /* Dark color for links from palette */
        font-size: 18px;
        margin: 0 15px; /* Space between links */
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Active link styling */
    .nav-container a.active {
        background-color: #B2A5FF; /* Light purple background for active link */
        color: #FFF2AF; /* Light yellow text for active link */
    }

    .nav-container a:hover {
        background-color: #493D9E; /* Dark background on hover */
        color: #FFF2AF; /* Light yellow text on hover */
    }

    /* Adjusting the font size on smaller screens */
    @media (max-width: 768px) {
        .nav-container a {
            font-size: 16px;
            padding: 8px 12px;
            margin: 0 10px; /* Slightly smaller margins */
        }
    }
</style>

<div class="nav-container">
<a href="admin_dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
    <a href="add_product.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_product.php') ? 'active' : ''; ?>">Insert New Product</a>
    <a href="update_product.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'update_product.php') ? 'active' : ''; ?>">Update Product</a>
    <a href="delete_product.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'delete_product.php') ? 'active' : ''; ?>">Delete Product</a>
    <a href="view_products.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_products.php') ? 'active' : ''; ?>">View Product</a>
    <a href="admin_orders.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin_orders.php') ? 'active' : ''; ?>">View Orders</a>
    <a href="view_users.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_users.php') ? 'active' : ''; ?>">View Users</a>
    <a href="analytics.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'active' : ''; ?>">Analytics</a>
    <a href="manage_stock.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage_stock.php') ? 'active' : ''; ?>">Manage Stock</a>
    <a href="admin_login.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin_login.php') ? 'active' : ''; ?>">Logout</a>
</div>
