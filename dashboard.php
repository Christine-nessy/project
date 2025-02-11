<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background */
        body {
            background: url('images/rb_45678.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #333;
            margin: 0;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: linear-gradient(to bottom, rgba(243, 215, 244, 0.9), rgba(180, 120, 146, 0.9));
            backdrop-filter: blur(10px);
            padding: 15px;
            color: black;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .sidebar a {
            color: black;
            text-decoration: none;
            display: block;
            padding: 12px;
            border-radius: 5px;
            margin: 8px 0;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .sidebar a:hover {
            background-color: rgba(53, 122, 189, 0.7);
            transform: scale(1.05);
            color: white;
        }

        /* Main Content */
        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        /* Glass Container */
        .glass-container {
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Track Order Box */
        .track-order-box {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #ffcccb, #ffebcd);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #333;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .track-order-box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Track Order Icon */
        .track-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 200px;
                width: calc(100% - 200px);
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                text-align: center;
            }
            .content {
                margin-left: 0;
                width: 100%;
                padding-top: 20px;
            }
            .glass-container {
                width: 90%;
                height: auto;
                padding: 15px;
            }
            .track-order-box {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
        <a href="#">Dashboard</a>
        <a href="about_us.php">About Us</a>
        <a href="products.php">Products</a>
        <a href="Cart/cart.php">Catalogue</a>
        <a href="Settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Glass Effect Container -->
        <div class="glass-container">
            <a href="track_order.php" class="track-order-box">
                <img src="images\cardboard-box-with-cargo-checklist-pencil.jpg" alt="Track Order" class="track-icon">
                <p>Track Order</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
