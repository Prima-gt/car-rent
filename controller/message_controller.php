<?php
session_start();
require_once __DIR__ . '/../model/message_model.php';
require_once __DIR__ . '/../model/db.php';
if(!isset($_SESSION['user_id'])){
  header('Location: ../login.php');
  exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $ride_id=(int)($_POST['ride_id'] ?? 0);
  $content=trim($_POST['content'] ?? '');
  if($ride_id && $content!==''){
    // Determine receiver based on ride ownership/driver
    $conn=db_connect();
    $res=mysqli_query($conn,"SELECT user_id, driver_id FROM rides WHERE id=$ride_id LIMIT 1");
    $row=$res? mysqli_fetch_assoc($res):null;
    mysqli_close($conn);
    $receiver = 1; // default to admin fallback
    if($row){
      if(isset($_SESSION['role']) && $_SESSION['role']==='driver'){
        $receiver = (int)$row['user_id'];
      } else {
        $receiver = !empty($row['driver_id']) ? (int)$row['driver_id'] : 1;
      }
    }
    message_send($ride_id,$_SESSION['user_id'],$receiver,$content);
  }
  header('Location: ../chat.php?ride_id='.$ride_id);
  exit;
}
header('Location: ../home.php');
exit;
?>

