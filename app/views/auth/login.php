<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Car Management System</title>
    <link rel="stylesheet" href="/Carmanagement/css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>User Login</p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            
            <?php if (!empty($error)): ?>
                <div class="message error" id="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="/Carmanagement/login.php" onsubmit="return validateForm('loginForm')">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required data-label="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required data-label="Password">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p>Don't have an account? <a href="/Carmanagement/register.php">Register here</a></p>
                <p><a href="/Carmanagement/forgot_password.php">Forgot Password?</a></p>
                <p><a href="/Carmanagement/index.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script src="/Carmanagement/js/validation.js"></script>
</body>
</html>

