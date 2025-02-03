<?php
session_start(); // Start the session

// Include database connection
require_once 'database.php';

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: Settings.php"); // Redirect to login if not logged in
    exit;
}

// Fetch current user data
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session
$query = "SELECT username, email FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Simple validation
    if ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } else {
        // Update user settings in the database
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash password before saving
            $query = "UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
        } else {
            // If no password is provided, update only username and email
            $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
            $stmt = $db->prepare($query);
        }

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect or show success message
        header("Location: settings.php?status=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Settings</h2>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success') { ?>
            <div class="alert alert-success">Settings updated successfully!</div>
        <?php } ?>

        <form action="settings.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
