<?php
session_start();
require_once __DIR__ . '/../model/ride_model.php';

function respond_json($data){
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_SESSION['user_id'])){
  $pickup = $_POST['pickup'] ?? '';
  $dropoff = $_POST['dropoff'] ?? '';
  $car_id = (int)($_POST['car_id'] ?? 0);

  // Basic validation
  if(!$pickup || !$dropoff || !$car_id){
    if(isset($_POST['ajax'])){ respond_json(['ok'=>false,'error'=>'Missing required fields']); }
    header('Location: ../rent.php?error='.urlencode('Missing required fields'));
    exit;
  }

  $id=ride_create($_SESSION['user_id'],$car_id,$pickup,$dropoff);
  if(isset($_POST['ajax'])){
    respond_json(['ok'=>true,'id'=>$id,'message'=>'Ride #'.$id.' booked successfully']);
  }
  header('Location: ../myrent.php?success='.urlencode('Ride #'.$id.' booked successfully'));
  exit;
}

if(isset($_POST['ajax'])){ respond_json(['ok'=>false,'error'=>'Unauthorized or invalid request']); }
header('Location: ../rent.php?error='.urlencode('Unable to book ride'));
exit;
?>

