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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #DAD2FF; /* Light Purple */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #493D9E; /* Deep Purple */
        }

        .login-container {
            background: linear-gradient(to bottom right, #B2A5FF, #DAD2FF); /* Gradient */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        h2 {
            color: #493D9E; /* Deep Purple */
            font-size: 28px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #FFF2AF; /* Light Yellow */
            transition: border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #B2A5FF; /* Purple focus */
            background-color: #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #493D9E; /* Deep Purple */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #B2A5FF; /* Light Purple */
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #493D9E;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>

            <button type="submit">Login</button>
        </form>
        <div class="footer">
            <p>&copy; 2025 Admin Panel. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
