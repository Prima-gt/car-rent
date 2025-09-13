<?php
require_once __DIR__ . '/db.php';

function ride_create($user_id,$car_id,$pickup,$dropoff){
  $conn = db_connect();
  $user_id = (int)$user_id;
  $car_id = (int)$car_id;
  $pickup = mysqli_real_escape_string($conn, $pickup);
  $dropoff = mysqli_real_escape_string($conn, $dropoff);
  // Simple cost calc: days * 50
  $days = max(1, (strtotime($dropoff)-strtotime($pickup))/(60*60*24));
  $cost = 50 * (int)$days;
  $sql = "INSERT INTO rides(user_id, car_id, pickup, dropoff, status, total_cost) VALUES($user_id, $car_id, '$pickup', '$dropoff', 'pending', $cost)";
  mysqli_query($conn, $sql);
  $id = mysqli_insert_id($conn);
  mysqli_close($conn);
  return $id;
}

function ride_all(){
  $conn = db_connect();
  $res = mysqli_query($conn, "SELECT * FROM rides ORDER BY id DESC");
  $list = [];
  while($row = $res && mysqli_fetch_assoc($res)){$list[]=$row;}
  mysqli_close($conn);
  return $list;
}

function ride_all_by_user($user_id){
  $conn=db_connect();
  $uid=(int)$user_id;
  $res=mysqli_query($conn,"SELECT * FROM rides WHERE user_id=$uid ORDER BY id DESC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function ride_all_by_user_detailed($user_id){
  $conn=db_connect();
  $uid=(int)$user_id;
  $sql = "SELECT r.*, c.model AS car_model, u.name AS driver_name
          FROM rides r
          LEFT JOIN cars c ON r.car_id=c.id
          LEFT JOIN users u ON r.driver_id=u.id
          WHERE r.user_id=$uid
          ORDER BY r.id DESC";
  $res=mysqli_query($conn,$sql);
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function ride_update_status($ride_id,$status,$driver_id=null){
  $conn=db_connect();
  $rid=(int)$ride_id; $status=mysqli_real_escape_string($conn,$status);
  $driver = $driver_id? (int)$driver_id : 'NULL';
  $sql="UPDATE rides SET status='$status', driver_id=$driver WHERE id=$rid";
  mysqli_query($conn,$sql);
  mysqli_close($conn);
}

function rides_pending(){
  $conn=db_connect();
  $res=mysqli_query($conn,"SELECT * FROM rides WHERE status='pending' ORDER BY id DESC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function rides_pending_for_driver($driver_id){
  $conn=db_connect();
  $did=(int)$driver_id;
  $sql = "SELECT r.* FROM rides r JOIN cars c ON r.car_id=c.id WHERE r.status='pending' AND c.driver_id=$did ORDER BY r.id DESC";
  $res=mysqli_query($conn,$sql);
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function rides_for_driver($driver_id){
  $conn=db_connect();
  $did=(int)$driver_id;
  $res=mysqli_query($conn,"SELECT * FROM rides WHERE driver_id=$did ORDER BY id DESC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function earnings_for_driver($driver_id){
  $conn=db_connect();
  $did=(int)$driver_id;
  $res=mysqli_query($conn,"SELECT SUM(total_cost) as total FROM rides WHERE driver_id=$did AND status='completed'");
  $row=$res? mysqli_fetch_assoc($res) : ['total'=>0];
  mysqli_close($conn);
  return (float)($row['total'] ?: 0);
}
?>

