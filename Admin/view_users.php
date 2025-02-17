<?php
// Start session and include necessary files
session_start();
include 'admin_auth.php'; // Ensure admin is logged in
include '../database.php'; // Database connection
include 'admin_nav.php'; // Admin navigation

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Create a Database instance
$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Search users if query is provided
$searchQuery = "";
if (!empty($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    $stmt = $db->prepare("SELECT * FROM users WHERE username LIKE :search OR email LIKE :search ORDER BY created_at DESC");
    $stmt->bindValue(':search', "%$searchQuery%", PDO::PARAM_STR);
} else {
    $stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC");
}
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Two-Factor Authentication toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_2fa'])) {
    $user_id = intval($_POST['user_id']);
    $new_2fa_code = rand(100000, 999999); // Generate a random 6-digit code

    $stmt = $db->prepare("UPDATE users SET two_factor_code = :two_factor_code WHERE id = :user_id");
    $stmt->bindParam(':two_factor_code', $new_2fa_code, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $success = "Two-Factor Authentication enabled for user ID: $user_id.";
    } else {
        $error = "Failed to enable Two-Factor Authentication.";
    }
    header("Location: view_users.php");
    exit;
}

// Handle Password Reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $user_id = intval($_POST['user_id']);
    $new_password = password_hash("Default123", PASSWORD_BCRYPT); // Default reset password

    $stmt = $db->prepare("UPDATE users SET password = :new_password WHERE id = :user_id");
    $stmt->bindParam(':new_password', $new_password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $success = "Password reset successfully for user ID: $user_id.";
    } else {
        $error = "Failed to reset password.";
    }
    header("Location: view_users.php");
    exit;
}

// Handle User Deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);

    $stmt = $db->prepare("DELETE FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $success = "User ID: $user_id has been deleted successfully.";
    } else {
        $error = "Failed to delete the user.";
    }
    header("Location: view_users.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(userId) {
            return confirm("Are you sure you want to delete user ID " + userId + "? This action cannot be undone.");
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">User Management</h1>

        <!-- Display Messages -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Search Bar -->
        <form method="GET" class="mb-4">
            <input type="text" name="search" class="form-control w-50 d-inline" placeholder="Search by username or email" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- User Table -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>2FA Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        <td><?php echo $user['two_factor_code'] ?? 'Disabled'; ?></td>
                        <td>
                            <!-- Enable/Disable Two-Factor Authentication -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo intval($user['id']); ?>">
                                <button type="submit" name="toggle_2fa" class="btn btn-warning btn-sm">
                                    Enable 2FA
                                </button>
                            </form>

                            <!-- Reset Password -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo intval($user['id']); ?>">
                                <button type="submit" name="reset_password" class="btn btn-danger btn-sm">
                                    Reset Password
                                </button>
                            </form>

                            <!-- Delete User -->
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete(<?php echo intval($user['id']); ?>);">
                                <input type="hidden" name="user_id" value="<?php echo intval($user['id']); ?>">
                                <button type="submit" name="delete_user" class="btn btn-dark btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($users)): ?>
            <p class="alert alert-info">No users found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
