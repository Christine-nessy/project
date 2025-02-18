<?php
$title = "Welcome to HookedByNessy!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/homepage.css">  
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="logo">HookedByNessy</div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="products.php">Shop</a></li>
            <li><a href="">About</a></li>
            <li><a href="#">Contact</a></li>

            <!-- Dropdown Menu -->
            <li class="dropdown">
                <a href="#">Account ▼</a>
                <div class="dropdown-menu">
                    <a href="login_form.php">Login as User</a>
                    <a href="Admin/admin_login.php">Login as Admin</a>
                </div>
            </li>

        </ul>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h1>HookedByNessy</h1>
        <p>Your one-stop shop for high-quality yarns and knitting accessories!</p>
    </div>

    <!-- Image Section -->
    <div class="image-section">
        <img src="images/ball_knitting.png" alt="Beautiful yarn display">
    </div>

    <!-- Services Section -->
    <div class="services">
        <div class="service-box">Premium-Quality Yarns</div>
        <div class="service-box">Exclusive Crochet and Knitting Patterns</div>
        <div class="service-box">DIY Craft Kits</div>
        <div class="service-box">Workshops and Tutorials</div>
        <div class="service-box">Community Support and Inspiration</div>
    </div>

    <!-- Register Section -->
    <div class="register">
        <p>Are you registered? If not, click the button below to sign up and start your crafting journey with us!</p>
        <button onclick="window.location.href='register.php';">Register Now</button>
    </div>
 <br>
 
    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> HookedByNessy. All rights reserved.</p>
    </div>

</body>
</html>
