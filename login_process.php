<?php
session_start(); // Start the session
require_once 'Database.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // create a Database instance and retrieve the connection
    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                // Redirect to the dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Invalid password
                echo "<script>alert('Invalid password.'); window.location.href='login_form.php';</script>";
            }
        } else {
            // Email does not exist
            echo "<script>alert('Email not found. Please register first.'); window.location.href='login_form.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the request method is not POST
    echo "<script>alert('Invalid request method.'); window.location.href='login_form.php';</script>";
}
?>
