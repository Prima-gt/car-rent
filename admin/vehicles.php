<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$message = '';
$action = isset($_GET['action']) ? $_GET['action'] : '';


if ($action === 'add' && $_POST) {
    $owner_id = (int)$_POST['owner_id'];
    $car_brand = trim($_POST['car_brand']);
    $car_model = trim($_POST['car_model']);
    $car_year = (int)$_POST['car_year'];
    $license_plate = trim($_POST['license_plate']);
    $color = trim($_POST['color']);
    $rental_price = (float)$_POST['rental_price_per_day'];
    
    if (!empty($car_brand) && !empty($car_model) && !empty($license_plate) && $car_year > 1900 && $rental_price > 0) {
        $stmt = $pdo->prepare("INSERT INTO vehicles (owner_id, car_brand, car_model, car_year, license_plate, color, rental_price_per_day) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$owner_id, $car_brand, $car_model, $car_year, $license_plate, $color, $rental_price])) {
            $message = "Vehicle added successfully!";
            $action = ''; 
        } else {
            $message = "Error adding vehicle. License plate might already exist.";
        }
    } else {
        $message = "Please fill in all required fields correctly.";
    }
}


$stmt = $pdo->query("
    SELECT v.*, u.full_name as owner_name, u.email as owner_email 
    FROM vehicles v 
    LEFT JOIN users u ON v.owner_id = u.id 
    ORDER BY v.created_at DESC
");
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->query("SELECT id, full_name, email FROM users WHERE user_type = 'car_owner' AND status = 'approved' ORDER BY full_name");
$car_owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Manage Vehicles</p>
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
            <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'add'): ?>
           
            <div class="form-container">
                <h2>Add New Vehicle</h2>
                <form method="POST" onsubmit="return validateForm('addVehicleForm')" id="addVehicleForm">
                    <div class="form-group">
                        <label for="owner_id">Car Owner:</label>
                        <select id="owner_id" name="owner_id" required data-label="Car Owner">
                            <option value="">Select Car Owner</option>
                            <?php foreach ($car_owners as $owner): ?>
                                <option value="<?php echo $owner['id']; ?>">
                                    <?php echo htmlspecialchars($owner['full_name'] . ' (' . $owner['email'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="car_brand">Car Brand:</label>
                        <input type="text" id="car_brand" name="car_brand" required data-label="Car Brand">
                    </div>

                    <div class="form-group">
                        <label for="car_model">Car Model:</label>
                        <input type="text" id="car_model" name="car_model" required data-label="Car Model">
                    </div>

                    <div class="form-group">
                        <label for="car_year">Car Year:</label>
                        <input type="number" id="car_year" name="car_year" min="1900" max="2030" required data-label="Car Year">
                    </div>

                    <div class="form-group">
                        <label for="license_plate">License Plate:</label>
                        <input type="text" id="license_plate" name="license_plate" required data-label="License Plate">
                    </div>

                    <div class="form-group">
                        <label for="color">Color:</label>
                        <input type="text" id="color" name="color" data-label="Color">
                    </div>

                    <div class="form-group">
                        <label for="rental_price_per_day">Daily Rental Price ($):</label>
                        <input type="number" id="rental_price_per_day" name="rental_price_per_day" min="1" step="0.01" required data-label="Daily Rental Price">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Add Vehicle</button>
                        <a href="vehicles.php" class="btn">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Vehicle Management</h2>
                    <a href="vehicles.php?action=add" class="btn btn-success">Add New Vehicle</a>
                </div>

                <?php if (empty($vehicles)): ?>
                    <p>No vehicles found in the system.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>License Plate</th>
                                <th>Color</th>
                                <th>Daily Rate</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><?php echo $vehicle['id']; ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['car_brand']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['car_model']); ?></td>
                                    <td><?php echo $vehicle['car_year']; ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['license_plate']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['color']); ?></td>
                                    <td>$<?php echo number_format($vehicle['rental_price_per_day'], 2); ?></td>
                                    <td>
                                        <?php if ($vehicle['owner_name']): ?>
                                            <?php echo htmlspecialchars($vehicle['owner_name']); ?>
                                        <?php else: ?>
                                            <em>No Owner</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status <?php echo $vehicle['status']; ?>">
                                            <?php echo ucfirst($vehicle['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($vehicle['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="../js/validation.js"></script>
</body>
</html>
