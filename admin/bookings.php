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
    $booking_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'confirm') {
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
        if ($stmt->execute([$booking_id])) {
            $message = "Booking confirmed successfully!";
        }
    } elseif ($action === 'complete') {
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE id = ?");
        if ($stmt->execute([$booking_id])) {
            $message = "Booking marked as completed!";
        }
    } elseif ($action === 'cancel') {
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
        if ($stmt->execute([$booking_id])) {
            $message = "Booking cancelled successfully!";
        }
    }
}

$where_clause = "";
if ($filter === 'pending') {
    $where_clause = "WHERE b.status = 'pending'";
} elseif ($filter === 'confirmed') {
    $where_clause = "WHERE b.status = 'confirmed'";
} elseif ($filter === 'completed') {
    $where_clause = "WHERE b.status = 'completed'";
} elseif ($filter === 'cancelled') {
    $where_clause = "WHERE b.status = 'cancelled'";
}


$stmt = $pdo->prepare("
    SELECT b.*, 
           u.full_name as driver_name, u.email as driver_email, u.phone as driver_phone,
           v.car_brand, v.car_model, v.license_plate, v.rental_price_per_day,
           owner.full_name as owner_name
    FROM bookings b
    JOIN users u ON b.driver_id = u.id
    JOIN vehicles v ON b.vehicle_id = v.id
    LEFT JOIN users owner ON v.owner_id = owner.id
    $where_clause
    ORDER BY b.booking_date DESC
");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Manage Bookings</p>
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
            <h2>Booking Management</h2>
            
            <!-- Filter buttons -->
            <div style="margin-bottom: 20px;">
                <a href="bookings.php?filter=all" class="btn <?php echo $filter === 'all' ? 'btn-success' : ''; ?>">All Bookings</a>
                <a href="bookings.php?filter=pending" class="btn <?php echo $filter === 'pending' ? 'btn-warning' : ''; ?>">Pending</a>
                <a href="bookings.php?filter=confirmed" class="btn <?php echo $filter === 'confirmed' ? 'btn-success' : ''; ?>">Confirmed</a>
                <a href="bookings.php?filter=completed" class="btn <?php echo $filter === 'completed' ? 'btn-success' : ''; ?>">Completed</a>
                <a href="bookings.php?filter=cancelled" class="btn <?php echo $filter === 'cancelled' ? 'btn-danger' : ''; ?>">Cancelled</a>
            </div>

            <?php if (empty($bookings)): ?>
                <p>No bookings found for the selected filter.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Owner</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Booked On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                $start_date = new DateTime($booking['start_date']);
                                $end_date = new DateTime($booking['end_date']);
                                $days = $start_date->diff($end_date)->days + 1;
                                ?>
                                <tr>
                                    <td><?php echo $booking['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($booking['driver_name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($booking['driver_email']); ?></small><br>
                                        <small><?php echo htmlspecialchars($booking['driver_phone']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($booking['license_plate']); ?></small><br>
                                        <small>$<?php echo number_format($booking['rental_price_per_day'], 2); ?>/day</small>
                                    </td>
                                    <td><?php echo htmlspecialchars($booking['owner_name'] ?: 'N/A'); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($booking['start_date'])); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($booking['end_date'])); ?></td>
                                    <td><?php echo $days; ?> day<?php echo $days > 1 ? 's' : ''; ?></td>
                                    <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="status <?php echo $booking['status']; ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                                    <td>
                                        <?php if ($booking['status'] === 'pending'): ?>
                                            <a href="bookings.php?action=confirm&id=<?php echo $booking['id']; ?>&filter=<?php echo $filter; ?>" 
                                               class="btn btn-success" style="margin: 2px; padding: 5px 8px; font-size: 11px;"
                                               onclick="return confirm('Confirm this booking?')">Confirm</a>
                                            <a href="bookings.php?action=cancel&id=<?php echo $booking['id']; ?>&filter=<?php echo $filter; ?>" 
                                               class="btn btn-danger" style="margin: 2px; padding: 5px 8px; font-size: 11px;"
                                               onclick="return confirm('Cancel this booking?')">Cancel</a>
                                        <?php elseif ($booking['status'] === 'confirmed'): ?>
                                            <a href="bookings.php?action=complete&id=<?php echo $booking['id']; ?>&filter=<?php echo $filter; ?>" 
                                               class="btn btn-success" style="margin: 2px; padding: 5px 8px; font-size: 11px;"
                                               onclick="return confirm('Mark as completed?')">Complete</a>
                                            <a href="bookings.php?action=cancel&id=<?php echo $booking['id']; ?>&filter=<?php echo $filter; ?>" 
                                               class="btn btn-danger" style="margin: 2px; padding: 5px 8px; font-size: 11px;"
                                               onclick="return confirm('Cancel this booking?')">Cancel</a>
                                        <?php else: ?>
                                            <span style="font-size: 11px; color: #666;">No actions</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
