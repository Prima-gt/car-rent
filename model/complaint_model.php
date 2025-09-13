<?php
require_once __DIR__ . '/db.php';

function complaint_create($driver_id,$subject,$details){
  $conn=db_connect();
  $did=(int)$driver_id; $s=mysqli_real_escape_string($conn,$subject); $d=mysqli_real_escape_string($conn,$details);
  $sql="CREATE TABLE IF NOT EXISTS complaints (id INT AUTO_INCREMENT PRIMARY KEY, driver_id INT NOT NULL, subject VARCHAR(120) NOT NULL, details VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
  mysqli_query($conn,$sql);
  mysqli_query($conn,"INSERT INTO complaints(driver_id,subject,details) VALUES($did,'$s','$d')");
  mysqli_close($conn);
}

function complaints_all(){
  $conn=db_connect();
  $res=mysqli_query($conn,"SELECT * FROM complaints ORDER BY id DESC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}
?>

