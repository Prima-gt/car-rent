<?php
session_start();
require_once __DIR__ . '/../model/ride_model.php';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_SESSION['user_id'])){
  $pickup = $_POST['pickup'] ?? '';
  $dropoff = $_POST['dropoff'] ?? '';
  $car_id = (int)($_POST['car_id'] ?? 0);
  $id=ride_create($_SESSION['user_id'],$car_id,$pickup,$dropoff);
  header('Location: ../myrent.php?success='.urlencode('Ride #'.$id.' booked successfully'));
  exit;
}
header('Location: ../rent.php?error='.urlencode('Unable to book ride'));
exit;
?>

