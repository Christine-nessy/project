<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
    exit;
}


?>
 <style>
        body {
            background: url('images/rb_45678.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #333;
            height: 100vh;
            margin: 0;
        }
        .sidebar {
    width: 250px;
    position: fixed;
    height: 100%;
    background: linear-gradient(
        to bottom, 
        rgba(243, 215, 244, 0.8),
        rgba(243, 215, 244, 0.8), /* Light mint green at the top */
        rgba(180, 120, 146, 0.7) /* Softer green in the middle */
          
    );
    backdrop-filter: blur(10px); /* Frosted glass effect */
    padding: 15px;
    color: black;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    border-right: 1px solid rgba(255, 255, 255, 0.2); /* Soft border effect */
}

    .sidebar a {
        color: black;
        text-decoration: none;
        display: block;
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .sidebar a:hover {
        background-color: rgba(53, 122, 189, 0.7);
        transform: scale(1.05); /* Slight zoom on hover */
    }

    /* Content Area */
    .content {
        margin-left: 250px;
        padding: 20px;
    }

    
    </style>
</head>
<body>
   

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
        <a href="#">Dashboard</a>
        <a href="about_us.php">AboutUs</a>
        <a href="products.php">Products</a>
        <a href="#">Catalogue</a>
        <a href="Settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>