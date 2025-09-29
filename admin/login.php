<?php
session_start();

$error = '';

$admin_email = 'admin@gmail.com';
$admin_password = 'topo12';

if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif ($email === $admin_email && $password === $admin_password) {
        
        setcookie('admin_login_effect', '1', time() + 5, '/Carmanagement/');
        
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $admin_email;
        
        
        error_log("Cookie set for admin login effect");
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Car Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Admin Login</p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="message error" id="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form id="adminLoginForm" method="POST" onsubmit="return validateForm('adminLoginForm')">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required data-label="Email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
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
                <p><a href="../index.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script src="../js/validation.js"></script>
</body>
</html>
