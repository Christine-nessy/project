<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* Gradient Background */
        body {
    background: 
        url('images/rb_45678.png') no-repeat center center fixed, 
        linear-gradient(135deg, #FFB200, #EB5B00, #D91656, #640D5F);
    background-size: cover, cover; /* Ensures both image and gradient cover the screen */
    background-position: center center;
    background-attachment: fixed;
    font-family: 'Arial', sans-serif;
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}

   


        /* Glassmorphic Form */
        .glass-effect {
            background: rgba(145, 33, 33, 0.1);
            backdrop-filter: blur(6px);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 40px;
            max-width: 450px;
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }

        .glass-effect:hover {
            transform: scale(1.05);
        }

        h2 {
            color: #fff;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Input Fields */
        .form-label {
            color: #fff;
            font-weight: bold;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.5);
            margin-bottom: 15px;
            padding: 12px;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.4);
            border-color: #FFB200;
            box-shadow: 0 0 5px rgba(255, 178, 0, 0.5);
        }

        /* Buttons */
        .btn-custom {
            background-color: #D91656;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #EB5B00;
            transform: translateY(-2px);
        }

        /* Links */
        .forgot-password a {
            color: #FFB200;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            color: #EB5B00;
        }

        @media (max-width: 576px) {
            .glass-effect {
                padding: 30px;
                max-width: 90%;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="glass-effect">
        <h2>Login</h2>
        <form action="login_process.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom">Login</button>
        </form>
        <div class="forgot-password mt-3">
            <a href="forgot_password.php">Forgot your password?</a> |
            <a href="register.php">Don't have an account? Register</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
