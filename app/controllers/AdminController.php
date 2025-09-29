<?php

class AdminController extends Controller
{
    public function dashboard(): string
    {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: login.php');
            exit;
        }

        $total_users = $this->scalar("SELECT COUNT(*) as total_users FROM users", 'total_users');
        $pending_users = $this->scalar("SELECT COUNT(*) as pending_users FROM users WHERE status = 'pending'", 'pending_users');
        $total_vehicles = $this->scalar("SELECT COUNT(*) as total_vehicles FROM vehicles", 'total_vehicles');
        $total_bookings = $this->scalar("SELECT COUNT(*) as total_bookings FROM bookings", 'total_bookings');
        $total_drivers = $this->scalar("SELECT COUNT(*) as drivers FROM users WHERE user_type = 'driver'", 'drivers');
        $total_car_owners = $this->scalar("SELECT COUNT(*) as car_owners FROM users WHERE user_type = 'car_owner'", 'car_owners');

        return $this->view('admin/dashboard', compact(
            'total_users',
            'pending_users',
            'total_vehicles',
            'total_bookings',
            'total_drivers',
            'total_car_owners'
        ));
    }

    private function scalar(string $sql, string $key)
    {
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row[$key] ?? 0;
    }
}

?>

