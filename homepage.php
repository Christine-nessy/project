<?php
include 'general_navbar.php';
$title = "Welcome to HookedByNessy!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        /* Full-page background */
        body {
            height: 100vh;
            width: 100vw;
            background: 
                url('images/rb_45678.png') no-repeat center center fixed, 
                linear-gradient(135deg, #FFB200, #EB5B00, #D91656, #640D5F);
            background-size: cover;
            background-blend-mode: overlay;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
        }

        /* Fixed Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            z-index: 1000;
        }

        /* Main container */
        .container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            width: 80%;
            max-width: 1200px;
            padding: 50px 20px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 80px; /* To prevent content from being hidden behind navbar */
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            margin-top: 20px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        /* Content box */
        .content-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        /* Service boxes */
        .services {
            margin-top: 40px;
        }
        .service-box {
            background: rgba(255, 255, 255, 0.3);
            padding: 15px;
            border-radius: 10px;
            margin: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Register button */
        .register button {
            background-color: #FFB200;
            color: #fff;
            border: none;
            padding: 15px 25px;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        .register button:hover {
            background-color: #EB5B00;
        }

        /* Image section */
        .image-section img {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <img src="group.jpg" alt="Beautiful yarn display">
        </div>

        <div class="content-box">
            <p>We are passionate about bringing color and creativity to your crafting journey. With our premium-quality yarns, we aim to inspire crafters of all levels to create beautiful, unique projects that bring joy and comfort to their lives.</p>
            <p>Our mission is to provide a wide range of yarns, patterns, and tools that cater to every style and preference. Whether you're a seasoned knitter or just picking up a crochet hook for the first time, HookedByNessy is here to support and celebrate your creativity.</p>
            <p>Thank you for choosing HookedByNessy for your crafting needs. Together, let's make something extraordinary!</p>
        </div>
        
        <!-- Services Section with Individual Boxes -->
        <div class="services content-box">
            <h2>Our Services</h2>
            <div class="service-box">Premium-Quality Yarns</div>
            <div class="service-box">Exclusive Crochet and Knitting Patterns</div>
            <div class="service-box">DIY Craft Kits</div>
            <div class="service-box">Workshops and Tutorials</div>
            <div class="service-box">Community Support and Inspiration</div>
        </div>
        
        <!-- Register Section -->
        <div class="register content-box">
            <p>Are you registered? If not, click the button below to sign up and start your crafting journey with us!</p>
            <button onclick="window.location.href='register.php';">Register Now</button>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> HookedByNessy. All rights reserved.</p>
    </div>
</body>
</html>
