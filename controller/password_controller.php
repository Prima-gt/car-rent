<?php
session_start();
require_once __DIR__ . '/../model/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $conn=db_connect();
  $pwd=mysqli_real_escape_string($conn, $_POST['password'] ?? '');
  if(isset($_POST['token'])){
    $token=mysqli_real_escape_string($conn, $_POST['token']);
    $now=date('Y-m-d H:i:s');
    $tRes=mysqli_query($conn,"SELECT user_id FROM reset_tokens WHERE token='$token' AND expires_at>'$now' LIMIT 1");
    $t=mysqli_fetch_assoc($tRes);
    if($t && strlen($pwd)>=6){
      $uid=(int)$t['user_id'];
      mysqli_query($conn,"UPDATE users SET password='$pwd' WHERE id=$uid");
      mysqli_query($conn,"DELETE FROM reset_tokens WHERE token='$token'");
      mysqli_close($conn);
      header('Location: ../login.php?success='.urlencode('Password updated'));
      exit;
    }
    mysqli_close($conn);
    header('Location: ../ForgotPassword.php?error='.urlencode('Invalid or expired token'));
    exit;
  }
  if(isset($_SESSION['user_id'])){
    $id=(int)$_SESSION['user_id'];
    if(strlen($pwd)>=6){ mysqli_query($conn, "UPDATE users SET password='$pwd' WHERE id=$id"); }
    mysqli_close($conn);
    header('Location: ../login.php?success='.urlencode('Password updated'));
    exit;
  }
}
header('Location: ../login.php');
exit;
?>

