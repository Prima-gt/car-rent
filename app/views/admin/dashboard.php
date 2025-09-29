<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Car Management System</title>
    <link rel="stylesheet" href="/Carmanagement/css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Admin Dashboard</p>
    </div>

    <div class="nav">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Manage Users</a></li>
            <li><a href="vehicles.php">Manage Vehicles</a></li>
            <li><a href="bookings.php">Manage Bookings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>System Overview</h2>
        
        <div class="dashboard">
            <div class="dashboard-card">
                <h3>Total Users</h3>
                <div class="count"><?php echo $total_users; ?></div>
                <p>Registered users in the system</p>
            </div>

            <div class="dashboard-card">
                <h3>Pending Approvals</h3>
                <div class="count"><?php echo $pending_users; ?></div>
                <p>Users waiting for approval</p>
                <?php if ($pending_users > 0): ?>
                    <a href="users.php?filter=pending" class="btn btn-warning" style="margin-top: 10px;">Review Now</a>
                <?php endif; ?>
            </div>

            <div class="dashboard-card">
                <h3>Total Vehicles</h3>
                <div class="count"><?php echo $total_vehicles; ?></div>
                <p>Vehicles in the system</p>
            </div>

            <div class="dashboard-card">
                <h3>Total Bookings</h3>
                <div class="count"><?php echo $total_bookings; ?></div>
                <p>Car rental bookings</p>
            </div>

            <div class="dashboard-card">
                <h3>Drivers</h3>
                <div class="count"><?php echo $total_drivers; ?></div>
                <p>Registered drivers</p>
            </div>

            <div class="dashboard-card">
                <h3>Car Owners</h3>
                <div class="count"><?php echo $total_car_owners; ?></div>
                <p>Registered car owners</p>
            </div>
        </div>

        <div class="table-container">
            <h3>Quick Actions</h3>
            <div style="text-align: center; padding: 20px;">
                <a href="users.php" class="btn" style="margin: 10px;">Manage Users</a>
                <a href="vehicles.php" class="btn" style="margin: 10px;">Manage Vehicles</a>
                <a href="bookings.php" class="btn" style="margin: 10px;">Manage Bookings</a>
                <a href="vehicles.php?action=add" class="btn btn-success" style="margin: 10px;">Add New Vehicle</a>
            </div>
        </div>

        <?php if ($pending_users > 0): ?>
            <div class="message info">
                <strong>Attention:</strong> You have <?php echo $pending_users; ?> user(s) pending approval. 
                <a href="users.php?filter=pending">Click here to review them.</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function checkLoginEffect() {
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return null;
            }

            console.log('All cookies:', document.cookie);
            const loginEffect = getCookie('admin_login_effect');
            console.log('Login effect cookie value:', loginEffect);
            if (loginEffect === '1') {
                console.log('Login effect triggered!');
                document.body.classList.add('login-effect');
                document.cookie = 'admin_login_effect=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                setTimeout(function() {
                    document.body.classList.remove('login-effect');
                    console.log('Login effect removed after 5 seconds');
                }, 5000);
            } else {
                console.log('No login effect cookie found');
            }
        }

        document.addEventListener('DOMContentLoaded', checkLoginEffect);
    </script>
</body>
</html>

