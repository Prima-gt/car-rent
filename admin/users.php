<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$message = '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';


if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            $message = "User approved successfully!";
        }
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            $message = "User rejected successfully!";
        }
    }
}


$where_clause = "";
$params = [];

if ($filter === 'pending') {
    $where_clause = "WHERE status = 'pending'";
} elseif ($filter === 'approved') {
    $where_clause = "WHERE status = 'approved'";
} elseif ($filter === 'rejected') {
    $where_clause = "WHERE status = 'rejected'";
} elseif ($filter === 'drivers') {
    $where_clause = "WHERE user_type = 'driver'";
} elseif ($filter === 'car_owners') {
    $where_clause = "WHERE user_type = 'car_owner'";
}

$stmt = $pdo->prepare("SELECT * FROM users $where_clause ORDER BY created_at DESC");
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Manage Users</p>
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
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="table-container">
            <h2>User Management</h2>
            
            
            <div style="margin-bottom: 20px;">
                <a href="users.php?filter=all" class="btn <?php echo $filter === 'all' ? 'btn-success' : ''; ?>">All Users</a>
                <a href="users.php?filter=pending" class="btn <?php echo $filter === 'pending' ? 'btn-warning' : ''; ?>">Pending</a>
                <a href="users.php?filter=approved" class="btn <?php echo $filter === 'approved' ? 'btn-success' : ''; ?>">Approved</a>
                <a href="users.php?filter=rejected" class="btn <?php echo $filter === 'rejected' ? 'btn-danger' : ''; ?>">Rejected</a>
                <a href="users.php?filter=drivers" class="btn <?php echo $filter === 'drivers' ? 'btn-success' : ''; ?>">Drivers</a>
                <a href="users.php?filter=car_owners" class="btn <?php echo $filter === 'car_owners' ? 'btn-success' : ''; ?>">Car Owners</a>
            </div>

            <?php if (empty($users)): ?>
                <p>No users found for the selected filter.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $user['user_type'])); ?></td>
                                <td>
                                    <span class="status <?php echo $user['status']; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if ($user['status'] === 'pending'): ?>
                                        <a href="users.php?action=approve&id=<?php echo $user['id']; ?>&filter=<?php echo $filter; ?>" 
                                           class="btn btn-success" style="margin: 2px; padding: 5px 10px; font-size: 12px;"
                                           onclick="return confirm('Are you sure you want to approve this user?')">Approve</a>
                                        <a href="users.php?action=reject&id=<?php echo $user['id']; ?>&filter=<?php echo $filter; ?>" 
                                           class="btn btn-danger" style="margin: 2px; padding: 5px 10px; font-size: 12px;"
                                           onclick="return confirm('Are you sure you want to reject this user?')">Reject</a>
                                    <?php else: ?>
                                        <a href="user_details.php?id=<?php echo $user['id']; ?>" 
                                           class="btn" style="margin: 2px; padding: 5px 10px; font-size: 12px;">View Details</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
   
        <?php if ($filter === 'pending'): ?>
            setTimeout(function() {
                location.reload();
            }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>
