<?php
require_once 'Database.php';
require_once 'User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['code']);
    $db = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $user = new User($db);

    // Check if the code exists and is not expired
    if ($user->verifyResetCode($code)) {
        $_SESSION['reset_code'] = $code;
        header("Location: reset_password.php"); // Redirect to password reset page
        exit;
    } else {
        $_SESSION['error'] = "Invalid or expired reset code.";
        header("Location: message.php"); // Show error
        exit;
    }
}
?>
