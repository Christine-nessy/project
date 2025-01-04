<?php
require_once 'Database.php';
session_start();

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

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Instantiate Database and User class
    $db = new Database();
    $user = new User($db);

    // Store to database
    if ($user->createUser($username, $email, $hashedPassword)) {
        echo "User registered successfully!";
    } else {
        echo "Error registering user.";
    }
}
?>
