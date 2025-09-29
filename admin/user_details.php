<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$user_id) {
    header("Location: users.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: users.php");
    exit();
}


$vehicles = [];
if ($user['user_type'] === 'car_owner') {
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE owner_id = ?");
    $stmt->execute([$user_id]);
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$bookings = [];
if ($user['user_type'] === 'driver') {
    $stmt = $pdo->prepare("
        SELECT b.*, v.car_brand, v.car_model, v.license_plate 
        FROM bookings b 
        JOIN vehicles v ON b.vehicle_id = v.id 
        WHERE b.driver_id = ? 
        ORDER BY b.booking_date DESC
    ");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>User Details</p>
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
        <div class="profile-container">
            <div class="profile-info">
                <h3>User Information</h3>
                <div class="info-row">
                    <span class="info-label">ID:</span>
                    <span class="info-value"><?php echo $user['id']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['phone']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['address']); ?></span>
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
                    <span class="info-label">Registered:</span>
                    <span class="info-value"><?php echo date('F j, Y g:i A', strtotime($user['created_at'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value"><?php echo date('F j, Y g:i A', strtotime($user['updated_at'])); ?></span>
                </div>
            </div>

            <?php if ($user['user_type'] === 'car_owner' && !empty($vehicles)): ?>
                <div class="table-container">
                    <h3>Owned Vehicles</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>License Plate</th>
                                <th>Color</th>
                                <th>Daily Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($vehicle['car_brand']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['car_model']); ?></td>
                                    <td><?php echo $vehicle['car_year']; ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['license_plate']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['color']); ?></td>
                                    <td>$<?php echo number_format($vehicle['rental_price_per_day'], 2); ?></td>
                                    <td>
                                        <span class="status <?php echo $vehicle['status']; ?>">
                                            <?php echo ucfirst($vehicle['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if ($user['user_type'] === 'driver' && !empty($bookings)): ?>
                <div class="table-container">
                    <h3>Booking History</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>License Plate</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Booked On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['license_plate']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($booking['start_date'])); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($booking['end_date'])); ?></td>
                                    <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="status <?php echo $booking['status']; ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div style="text-align: center; margin-top: 20px;">
                <a href="users.php" class="btn">Back to Users</a>
            </div>
        </div>
    </div>
</body>
</html>
