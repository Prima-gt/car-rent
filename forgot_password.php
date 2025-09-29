<?php
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_POST) {
    $email = sanitize_input($_POST['email']);
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!validate_email($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        $user = get_user_by_email($email);
        
        if ($user) {
           
            $token = generate_token();
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            
            $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expires_at]);
            
         
            $success = "Password reset instructions have been sent to your email. For demo purposes, use this link: <a href='reset_password.php?token=$token'>Reset Password</a>";
        } else {
            $error = 'Email address not found.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Forgot Password</p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <p>Enter your email address and we'll send you a link to reset your password.</p>
            
            <?php if ($error): ?>
                <div class="message error" id="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="forgotForm" method="POST" onsubmit="return validateForm('forgotForm')">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required data-label="Email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Send Reset Link</button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p><a href="login.php">Back to Login</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
