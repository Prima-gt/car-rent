<?php
session_start();
require_once __DIR__ . '/../model/db.php';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_SESSION['user_id'])){
  $conn = db_connect();
  $id = (int)$_SESSION['user_id'];
  $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
  $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
  $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
  $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id";
  mysqli_query($conn, $sql);
  mysqli_close($conn);
  $_SESSION['name'] = $name;
  $_SESSION['email'] = $email;
  $_SESSION['phone'] = $phone;
}
header('Location: ../profile.php');
exit;
?>

