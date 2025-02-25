<?php

require_once 'Database.php';
require_once 'User.php';
require 'C:\Apache24\htdocs\project\PHPMailer\vendor\autoload.php';

session_start(); // Start the session at the beginning of the script

// // Check if the user is logged in
// if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
//     $_SESSION['error'] = "You are not logged in or the session has expired. Please log in first.";
//     header("Location: login_form.php");
//     exit;
// }



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['verify_email'];
    $enteredCode = trim($_POST['2facode']); // Retrieve and sanitize the entered 2FA code

    $db = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $user = new User($db);

    // if ($user->verify2FACode($email, $enteredCode)) {
    //     $_SESSION['success'] = "2FA verified successfully. You are logged in!";
    //     header("Location: dashboard.php");
    //     exit;
    // } else {
    //     $_SESSION['error'] = "Invalid or expired 2FA code. Please try again.";
    //     header("Location: verify_2fa.php");
    //     exit;
    // }
    $tab=$user->verify2FACode($email, $enteredCode);
    if($tab===true){
        $_SESSION['success'] = "2FA verified successfully. You are logged in!";
             header("Location: dashboard.php");
           exit; 
    }
    else{
        $_SESSION['error'] = "Invalid or expired 2FA code. Please try again.";
         header("Location: verify_2fa.php");
         exit;
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
                <label  class="form-label">Enter 2FA Code</label>
                <input type="text" class="form-control" id="2fa_code" name="2facode" placeholder="Enter your 6-digit code" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
