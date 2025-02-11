<?php
include 'general_navbar.php';
//session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Yarn Creations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/rb_45678.png') no-repeat center center fixed;
            background-size: cover;
            background-position: center center;
            /* background: linear-gradient(to bottom, #f8f9fa, #e0e7f5); */
            color: #333;
            margin: 0;
            padding: 0;
        }
    
        .about-us-section {
            padding: 50px 20px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            text-align: center;
        }
        .about-us-section h1 {
            color: black;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .about-us-section p {
            font-size: 1.2rem;
            line-height: 1.8;
        }
        .about-us-section img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 20px auto;
            display: block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    
  <!-- About Us Section -->
  <div class="about-us-section">
        <img src="images/nessy.png" alt="Yarn Logo">
        <h1>About Us</h1>
        <p>
            Welcome to HookedByNessy! We are passionate about bringing color and creativity to your crafting journey. 
            With our premium-quality yarns, we aim to inspire crafters of all levels to create beautiful, unique projects 
            that bring joy and comfort to their lives.
        </p>
        <p>
            Our mission is to provide a wide range of yarns, patterns, and tools that cater to every style and preference. 
            Whether you're a seasoned knitter or just picking up a crochet hook for the first time, Yarn Creations is here 
            to support and celebrate your creativity.
        </p>
        <p>
            Thank you for choosing HookedByNessy for your crafting needs. Together, let's make something extraordinary!
        </p>
    </div>
  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
