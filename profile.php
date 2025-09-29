<?php
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user = get_user_by_id($_SESSION['user_id']);
$error = '';
$success = '';

if ($_POST) {
    $full_name = sanitize_input($_POST['full_name']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    
    if (empty($full_name) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields.';
    } else {
        if (update_user($_SESSION['user_id'], $full_name, $phone, $address)) {
            $success = 'Profile updated successfully!';
            $user = get_user_by_id($_SESSION['user_id']); // Refresh user data
            $_SESSION['user_name'] = $user['full_name']; // Update session
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>User Profile</p>
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
        <div class="profile-container">
            <div class="profile-info">
                <h3>Profile Information</h3>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">User Type:</span>
                    <span class="info-value"><?php echo ucfirst(str_replace('_', ' ', $user['user_type'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status <?php echo $user['status']; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></span>
                </div>
            </div>

            <h3>Update Profile</h3>
            
            <?php if ($error): ?>
                <div class="message error" id="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="profileForm" method="POST" onsubmit="return validateForm('profileForm')">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required data-label="Full Name" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required data-label="Phone" 
                           value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required data-label="Address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
