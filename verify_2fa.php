<?php

require_once 'Database.php';
require_once 'User.php';
require 'C:\Apache24\htdocs\project\PHPMailer\vendor\autoload.php';

session_start(); // Start the session at the beginning of the script

// Check if user_id exists in session before proceeding
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in or the session has expired. Please log in first.";
    header("Location: login.php"); // Redirect to the login page
    exit;
}

$userId = $_SESSION['user_id']; // Retrieve the user_id from the session

// Handle form submission for 2FA verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredCode = trim($_POST['2fa_code']); // Retrieve and sanitize the entered 2FA code

    // Instantiate the Database and User classes
    $db = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $user = new User($db);

    // Verify the 2FA code
    if ($user->verify2FACode($userId, $enteredCode)) {
        echo "2FA verified successfully. You are logged in!";
        // Redirect the user to their dashboard or a secure page
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid or expired 2FA code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: bold;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .alert {
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Two-Factor Authentication</h2>
        <!-- Display error or success messages -->
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']);
        }
        ?>
        <p>A 2FA code has been sent to your email. Please enter it below to complete the verification process.</p>
        
        <form action="verify_2fa.php" method="POST">
            <div class="mb-3">
                <label for="2fa_code" class="form-label">Enter 2FA Code</label>
                <input type="text" class="form-control" id="2fa_code" name="2fa_code" placeholder="Enter your 6-digit code" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
