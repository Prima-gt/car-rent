<?php
session_start();
require_once __DIR__ . '/../model/db.php';
require_once __DIR__ . '/../model/car_model.php';
require_once __DIR__ . '/../model/ride_model.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='admin'){
  header('Location: ../login.php');
  exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['action'] ?? '';
  if($action==='approve_driver'){
    $id=(int)($_POST['user_id'] ?? 0);
    $conn=db_connect();
    mysqli_query($conn,"UPDATE users SET approved=1 WHERE id=$id AND role='driver'");
    mysqli_close($conn);
    header('Location: ../panel_admin.php?success='.urlencode('Driver approved'));
    exit;
  }
  if($action==='add_car'){
    $model=$_POST['model'] ?? '';
    $owner=$_POST['owner'] ?? '';
    $driver_id=!empty($_POST['driver_id'])? (int)$_POST['driver_id'] : null;
    $imagePath=null;
    if(isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])){
      $ext=pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $safeName='car_'.time().'_'.rand(100,999).'.'.preg_replace('/[^a-zA-Z0-9]/','',$ext);
      $dest=dirname(__DIR__).'/pic/'.$safeName;
      if(move_uploaded_file($_FILES['image']['tmp_name'],$dest)){
        $imagePath='pic/'.$safeName;
      }
    }
    car_create($model,$owner,$driver_id,$imagePath);
    header('Location: ../panel_admin.php?success='.urlencode('Car added'));
    exit;
  }
  if($action==='set_booking_status'){
    $rid=(int)($_POST['ride_id'] ?? 0);
    $status=$_POST['status'] ?? 'pending';
    ride_update_status($rid,$status,null);
    header('Location: ../panel_admin.php?success='.urlencode('Booking updated'));
    exit;
  }
  if($action==='delete_car'){
    $car_id=(int)($_POST['car_id'] ?? 0);
    $conn=db_connect();
    $imgRes=mysqli_query($conn,"SELECT image FROM cars WHERE id=$car_id LIMIT 1");
    $imgRow=$imgRes? mysqli_fetch_assoc($imgRes):null;
    mysqli_query($conn,"DELETE FROM cars WHERE id=$car_id");
    if($imgRow && !empty($imgRow['image'])){
      $file=dirname(__DIR__).'/'. $imgRow['image'];
      if(is_file($file)){ @unlink($file); }
    }
    mysqli_close($conn);
    header('Location: ../panel_admin.php?success='.urlencode('Car deleted'));
    exit;
  }
}

header('Location: ../panel_admin.php?error='.urlencode('No action'));
exit;
?>

