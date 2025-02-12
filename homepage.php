<?php
$title = "Welcome to HookedByNessy!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Full-page Background */
        body {
            height: 100vh;
            width: 100vw;
            background: linear-gradient(135deg, #FFB200, #EB5B00, #D91656, #640D5F);
            background-attachment: fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
            padding-top: 80px; /* Prevent overlap with navbar */
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }
        .navbar ul {
            list-style: none;
            display: flex;
        }
        .navbar ul li {
            margin: 0 15px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 1rem;
            transition: color 0.3s ease-in-out;
        }
        .navbar ul li a:hover {
            color: #FFB200;
        }

        /* Main Container */
        .container {
            width: 90%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
            margin-bottom: 60px;
        }

        /* Image Section */
        .image-section img {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Content Box */
        .content-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Services Section */
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .service-box {
            background: rgba(255, 255, 255, 0.3);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Register Button */
        .register button {
            background-color: #FFB200;
            color: white;
            border: none;
            padding: 15px 25px;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s ease;
        }
        .register button:hover {
            background-color: #EB5B00;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            font-size: 0.9rem;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
                padding: 10px;
            }
            .navbar ul {
                margin-top: 10px;
            }
            .navbar ul li {
                margin: 5px;
            }
        }
    </style>
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
        </ul>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="image-section">
            <img src="group.jpg" alt="Beautiful yarn display">
        </div>

        <div class="content-box">
            <p>We are passionate about bringing color and creativity to your crafting journey. With our premium-quality yarns, we aim to inspire crafters of all levels to create beautiful, unique projects that bring joy and comfort to their lives.</p>
            <p>Our mission is to provide a wide range of yarns, patterns, and tools that cater to every style and preference. Whether you're a seasoned knitter or just picking up a crochet hook for the first time, HookedByNessy is here to support and celebrate your creativity.</p>
            <p>Thank you for choosing HookedByNessy for your crafting needs. Together, let's make something extraordinary!</p>
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
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> HookedByNessy. All rights reserved.</p>
    </div>

</body>
</html>
