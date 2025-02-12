<?php
// Start session and include necessary files
session_start();
include 'database.php';
include 'admin_navbar.php'; // Ensure this file contains navigation for admin users

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
try {
    $db_instance = new Database('mysql', 'localhost', '3308', 'root', 'root', 'user_data'); // Ensure 'mysql' matches PDO driver
    $db = $db_instance->getConnection();
    
    // Fetch all users
    $query = "SELECT id, username, email, created_at FROM users ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/admin_bg.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
            color: white;
            margin-top: 50px;
        }
        .table {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .table th, .table td {
            color: white !important;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center">All Registered Users</h2>

        <?php if (empty($users)): ?>
            <div class="alert alert-info mt-3">No users found.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
