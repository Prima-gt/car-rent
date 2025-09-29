<?php
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_POST) {
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    $user_type = sanitize_input($_POST['user_type']);
    
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password) || empty($phone) || empty($address) || empty($user_type)) {
        $error = 'Please fill in all fields.';
    } elseif (!validate_email($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (get_user_by_email($email)) {
        $error = 'Email address already exists.';
    } else {
        if (create_user($full_name, $email, $password, $phone, $address, $user_type)) {
            $success = 'Registration successful! Your account is pending approval. You will be notified once approved.';
        } else {
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>User Registration</p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Register</h2>
            
            <?php if ($error): ?>
                <div class="message error" id="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" onsubmit="return validateForm('registerForm')">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required data-label="Full Name" 
                           value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>

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
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required data-label="Confirm Password">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required data-label="Phone" 
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required data-label="Address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="user_type">User Type:</label>
                    <select id="user_type" name="user_type" required data-label="User Type">
                        <option value="">Select User Type</option>
                        <option value="driver" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'driver') ? 'selected' : ''; ?>>Driver</option>
                        <option value="car_owner" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'car_owner') ? 'selected' : ''; ?>>Car Owner</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Register</button>
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
