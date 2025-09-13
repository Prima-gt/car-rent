<?php
session_start();
require_once __DIR__ . '/../model/rating_model.php';
if(!isset($_SESSION['user_id'])){ header('Location: ../login.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $ride_id=(int)($_POST['ride_id'] ?? 0);
  $driver_id=(int)($_POST['driver_id'] ?? 0);
  $stars=(int)($_POST['stars'] ?? 0);
  $review=trim($_POST['review'] ?? '');
  if($ride_id && $driver_id && $stars>=1 && $stars<=5){ rating_create($ride_id,$driver_id,$stars,$review); }
}
header('Location: ../myrent.php');
exit;
?>

