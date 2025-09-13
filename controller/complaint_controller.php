<?php
session_start();
require_once __DIR__ . '/../model/complaint_model.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='driver'){
  header('Location: ../login.php');
  exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $subject=trim($_POST['subject'] ?? '');
  $details=trim($_POST['details'] ?? '');
  if(strlen($subject)>=3 && strlen($details)>=5){
    complaint_create($_SESSION['user_id'],$subject,$details);
  }
}
header('Location: ../panel_driver.php');
exit;
?>

