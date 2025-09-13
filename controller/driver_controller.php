<?php
session_start();
require_once __DIR__ . '/../model/ride_model.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!=='driver'){
  header('Location: ../login.php');
  exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
  $action=$_POST['action'] ?? '';
  $rid=(int)($_POST['ride_id'] ?? 0);
  $did=(int)$_SESSION['user_id'];
  if($action==='accept'){
    ride_update_status($rid,'accepted',$did);
    header('Location: ../panel_driver.php?success='.urlencode('Ride accepted'));
    exit;
  } elseif($action==='cancel'){
    ride_update_status($rid,'cancelled',$did);
    header('Location: ../panel_driver.php?success='.urlencode('Ride cancelled'));
    exit;
  } elseif($action==='complete'){
    ride_update_status($rid,'completed',$did);
    header('Location: ../panel_driver.php?success='.urlencode('Ride completed'));
    exit;
  }
}

header('Location: ../panel_driver.php?error='.urlencode('No action'));
exit;
?>

