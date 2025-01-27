<?php
include('../database.php');
session_start();


// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}


// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
     // create a Database instance and retrieve the connection
     $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
     $db = $db_instance->getConnection();
 

    // Validate credentials using PDO
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username AND password = :password");

    // Bind parameters using PDO
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch the result (since we are using PDO, fetch results manually)
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // If login is successful, store session variable and redirect
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        // If credentials are incorrect
        $error = "Invalid username or password.";
    }

    
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
