<?php
require_once 'includes/functions.php';

$error = '';
$success = '';
$token = isset($_GET['token']) ? $_GET['token'] : '';


$valid_token = false;
if ($token) {
    $stmt = $pdo->prepare("SELECT * FROM password_reset_tokens WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $token_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($token_data) {
        $valid_token = true;
        $email = $token_data['email'];
    }
}

if ($_POST && $valid_token) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $user = get_user_by_email($email);
        if ($user && update_password($user['id'], $new_password)) {
            
            $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
            $stmt->execute([$token]);
            
            $success = 'Password reset successfully! You can now login with your new password.';
        } else {
            $error = 'Failed to reset password. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Reset Password</p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Reset Password</h2>
            
            <?php if (!$valid_token): ?>
                <div class="message error">Invalid or expired reset token. Please request a new password reset.</div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="forgot_password.php" class="btn">Request New Reset</a>
                </div>
            <?php else: ?>
                
                <?php if ($error): ?>
                    <div class="message error" id="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="message success"><?php echo $success; ?></div>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="login.php" class="btn">Login Now</a>
                    </div>
                <?php else: ?>

                <form id="resetForm" method="POST" onsubmit="return validateForm('resetForm')">
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required data-label="New Password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required data-label="Confirm Password">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn">Reset Password</button>
                    </div>
                </form>
                
                <?php endif; ?>
            <?php endif; ?>

            <div style="text-align: center; margin-top: 20px;">
                <p><a href="login.php">Back to Login</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
