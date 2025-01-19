<?php
require_once 'Database.php';
require_once 'User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['reset_code'])) {
    $newPassword = trim($_POST['new_password']);
    $resetCode = $_SESSION['reset_code'];

    $db = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $user = new User($db);

    // Hash the new password before storing it
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    if ($user->resetPassword($resetCode, $hashedPassword)) {
        unset($_SESSION['reset_code']); // Remove the reset code from session
        $_SESSION['success'] = "Password has been reset successfully.";
        header("Location: message.php"); // Show success message
        exit;
    } else {
        $_SESSION['error'] = "Failed to reset the password.";
        header("Location: message.php"); // Show error message
        exit;
    }
} else {
    header("Location: forgot_password.php"); // Redirect if not coming from the correct page
    exit;
}
?>
