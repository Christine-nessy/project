<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if admin is not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>
