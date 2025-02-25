<?php
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';
require 'C:\Apache24\htdocs\project\PHPMailer\vendor\autoload.php';

require_once 'Database.php';
require_once 'user.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Instantiate Database and User class
$db = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$user = new User($db);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to send the 2FA code via email
function send2FACode($email, $code) {
    $mail = new PHPMailer(true);

    try {
        // Enable debugging (optional)
        // $mail->SMTPDebug = 2;  

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'christinemungla16@gmail.com'; // Use your Gmail
        $mail->Password = 'bksagxgtoopsyzzl'; // Use your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('christinemungla16@gmail.com', 'HookedByNessy');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your 2FA Verification Code';
        $mail->Body = "Your 2FA verification code is: <b>$code</b>";
        $mail->AltBody = "Your 2FA verification code is: $code";

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the email is already registered
    if ($user->emailExists($email)) {
        echo "This email is already registered. Please use a different email.";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store user in database
    if ($user->createUser($username, $email, $hashedPassword)) {
        // Generate 2FA code and store it
        $twoFACode = rand(100000, 999999);
        $user->store2FACode($email, $twoFACode);

        // Send the code via email
        if (send2FACode($email, $twoFACode)) {
            // Store email in session for verification
            $_SESSION['verify_email'] = $email;
            
            // Redirect to verify_2fa.php
            header("Location: verify_2fa.php");
            exit;
        } else {
            echo "Error sending 2FA code. Please check your email settings.";
            exit;
        }
    } else {
        echo "Error registering user.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
    background: url('./images/Nessy.png') no-repeat center center/cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: Arial, sans-serif;
}

        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 420px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, #ff6f91, #ff9671, #ffc75f, #f9f871);
        }
        .form-container h2 {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .form-container label {
            font-weight: bold;
            color: #333;
        }
        .form-container input {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
        }
        .form-container input:focus {
            border-color: #ff9671;
            box-shadow: 0 0 5px rgba(255, 150, 113, 0.5);
        }
        .form-container .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .form-container .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            transform: scale(1.05);
        }
        .alert-success {
            position: absolute;
            top: -40px;
            right: 10px;
            width: auto;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="form-container">
     
        <h2>Register</h2>
       
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <a href="login_form.php">Do you have an Account?Login</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
