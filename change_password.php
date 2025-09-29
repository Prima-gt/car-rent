<?php
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user = get_user_by_id($_SESSION['user_id']);
$error = '';
$success = '';

if ($_POST) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif (!verify_password($current_password, $user['password'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new_password) < 6) {
        $error = 'New password must be at least 6 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match.';
    } else {
        if (update_password($_SESSION['user_id'], $new_password)) {
            $success = 'Password changed successfully!';
        } else {
            $error = 'Failed to change password. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Change Password</p>
    </div>

    <div class="nav">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="change_password.php">Change Password</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Change Password</h2>
            
            <?php if ($error): ?>
                <div class="message error" id="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="changePasswordForm" method="POST" onsubmit="return validateForm('changePasswordForm')">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required data-label="Current Password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required data-label="New Password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required data-label="Confirm New Password">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Change Password</button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p><a href="dashboard.php">Back to Dashboard</a></p>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
