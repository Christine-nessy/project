<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            font-family: Arial, sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .dashboard-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .dashboard-container h1 {
            font-size: 2rem;
            color: #4a90e2;
            margin-bottom: 20px;
        }
        .dashboard-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .dashboard-container a {
            text-decoration: none;
            color: #ffffff;
            background-color: #4a90e2;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .dashboard-container a:hover {
            background-color: #357abd;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>You are now logged in.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
