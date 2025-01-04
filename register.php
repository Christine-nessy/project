<?php
require_once 'Database.php';
require_once 'User.php';
session_start();



// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        header("Location: registration.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        header("Location: registration.php");
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Instantiate Database and User class
    $user = new User($db);

    // Store to database
    if ($user->createUser($username, $email, $hashedPassword)) {
        echo "User registered successfully!";
        // Get the user's ID from the database (for 2FA)
        $stmt = $db->getConnection()->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        // Start 2FA process
        $user->start2FA($userId, $email);

        // Redirect to the 2FA verification page
        header("Location: verify_2fa.php");
        exit;
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
</head>
<body>
    <div class="container mt-5">
        <h2>Register</h2>
         <?php
        // session_start();
        // if (isset($_SESSION['error'])) {
        //     echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
        //     unset($_SESSION['error']);
        // }
        // if (isset($_SESSION['success'])) {
        //     echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
        //     unset($_SESSION['success']);
        // }
        ?>  
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
