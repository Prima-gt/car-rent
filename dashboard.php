<?php
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user = get_user_by_id($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</p>
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
        <div class="dashboard">
            <div class="dashboard-card">
                <h3>Your Account Status</h3>
                <div class="status <?php echo $user['status']; ?>">
                    <?php echo ucfirst($user['status']); ?>
                </div>
                <p>Account Type: <?php echo ucfirst(str_replace('_', ' ', $user['user_type'])); ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Quick Actions</h3>
                <div style="margin-top: 15px;">
                    <a href="profile.php" class="btn" style="margin: 5px;">View Profile</a>
                    <a href="change_password.php" class="btn" style="margin: 5px;">Change Password</a>
                </div>
            </div>
        </div>

        <?php if ($user['status'] === 'pending'): ?>
            <div class="message info">
                <strong>Account Pending Approval</strong><br>
                Your account is currently pending approval from the administrator. You will receive access to additional features once your account is approved.
            </div>
        <?php elseif ($user['status'] === 'rejected'): ?>
            <div class="message error">
                <strong>Account Rejected</strong><br>
                Your account has been rejected. Please contact the administrator for more information.
            </div>
        <?php else: ?>
            <div class="message success">
                <strong>Account Approved</strong><br>
                Your account has been approved! You now have full access to the system.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
