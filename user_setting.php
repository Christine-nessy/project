<?php
session_start();

$settings_file = 'user_settings.php';

// Load existing settings
$user_settings = include($settings_file);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_settings['username'] = $_POST['username'];
    $user_settings['email'] = $_POST['email'];

    // Handle Profile Picture Upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $user_settings['profile_picture'] = $target_file;
    }

    // Convert array to PHP code
    $config_content = "<?php\nreturn " . var_export($user_settings, true) . ";\n?>";

    // Save new settings to user_settings.php
    file_put_contents($settings_file, $config_content);

    $_SESSION['success'] = "User settings updated successfully!";
    header("Location: user_setting.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Settings</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="user_setting.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user_settings['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_settings['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control">
                <?php if (!empty($user_settings['profile_picture'])): ?>
                    <img src="<?= $user_settings['profile_picture']; ?>" alt="Profile Picture" height="50">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
