<?php
require_once 'Database.php';
require_once 'User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $db = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $user = new User($db);

    if ($user->emailExists($email)) {
        $resetCode = bin2hex(random_bytes(16)); // Unique reset code
        $expiryTime = date('Y-m-d H:i:s', strtotime('+10 minutes')); // Code expiry time

        if ($user->storeResetCode($email, $resetCode, $expiryTime)) {
            $resetLink = "http://localhost/project/verify_code.php?code=$resetCode"; // Change the link to your website
            

            // Send reset link via email
            mail($email, "Password Reset", "Click the link to reset your password: $resetLink");

            $_SESSION['message'] = "A reset link has been sent to your email.";
            header("Location: message.php"); // Show the success message on a new page
            exit;
        } else {
            $_SESSION['error'] = "Failed to generate reset code.";
            header("Location: message.php"); // Show the error message
            exit;
        }
    } else {
        $_SESSION['error'] = "Email not found in our system.";
        header("Location: message.php"); // Show the error message
        exit;
    }
}
?>
