<?php
require_once 'Database.php';
require_once 'User.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: login_form.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: login_form.php");
        exit;
    }

    // Instantiate Database and User class
    
    $user = new User($db);

    // Authenticate user
    $authenticatedUser = $user->authenticateUser($email, $password);

    if ($authenticatedUser) {
        // Set session variables
        $_SESSION['user_id'] = $authenticatedUser['id'];
        $_SESSION['username'] = $authenticatedUser['username'];
        $_SESSION['success'] = "Login successful. Welcome, " . $authenticatedUser['username'] . "!";
 
         // Debugging: Check session variables
    var_dump($_SESSION);
    exit;

        header("Location: dashboard.php"); // Redirect to a dashboard or homepage
        exit;
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login_form.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']);
        }
        ?>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


