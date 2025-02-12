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
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #FFB200, #EB5B00, #D91656, #640D5F);
            color: #fff;
            text-align: center;
        }
        .header {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            font-size: 1.5rem;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 50px 20px;
        }
        h1 {
            font-size: 2.5rem;
        }
        p {
            font-size: 1.2rem;
            line-height: 1.6;
            max-width: 800px;
            margin: 20px auto;
        }
        .content-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
        }
        .footer {
            margin-top: 50px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
        }
        .services {
            margin-top: 40px;
        }
        .services h2 {
            font-size: 2rem;
        }
        .services ul {
            list-style: none;
            padding: 0;
        }
        .services li {
            font-size: 1.2rem;
            margin: 10px 0;
        }
        .register {
            margin-top: 40px;
        }
        .register p {
            font-size: 1.2rem;
        }
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
        .image-section img {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>HookedByNessy</h2>
    </div>
    <div class="container">
        <h1><?php echo $title; ?></h1>
        <div class="image-section">
            <img src="images/group.jpg" alt="Beautiful yarn display">
        </div>
        <div class="content-box">
            <p>We are passionate about bringing color and creativity to your crafting journey. With our premium-quality yarns, we aim to inspire crafters of all levels to create beautiful, unique projects that bring joy and comfort to their lives.</p>
            <p>Our mission is to provide a wide range of yarns, patterns, and tools that cater to every style and preference. Whether you're a seasoned knitter or just picking up a crochet hook for the first time, HookedByNessy is here to support and celebrate your creativity.</p>
            <p>Thank you for choosing HookedByNessy for your crafting needs. Together, let's make something extraordinary!</p>
        </div>
        
        <div class="services content-box">
            <h2>Our Services</h2>
            <ul>
                <li>Premium-Quality Yarns</li>
                <li>Exclusive Crochet and Knitting Patterns</li>
                <li>DIY Craft Kits</li>
                <li>Workshops and Tutorials</li>
                <li>Community Support and Inspiration</li>
            </ul>
        </div>
        
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
