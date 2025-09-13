<?php
require_once __DIR__ . '/db.php';

function car_create($model,$owner,$driver_id=null,$image=null){
  $conn=db_connect();
  $model=mysqli_real_escape_string($conn,$model);
  $owner=mysqli_real_escape_string($conn,$owner);
  $driver = $driver_id? (int)$driver_id : 'NULL';
  $img = $image? ("'".mysqli_real_escape_string($conn,$image)."'") : 'NULL';
  $sql="INSERT INTO cars(model,owner,image,driver_id) VALUES('$model','$owner',$img,$driver)";
  mysqli_query($conn,$sql);
  $id=mysqli_insert_id($conn);
  mysqli_close($conn);
  return $id;
}

function cars_all(){
  $conn=db_connect();
  $res=mysqli_query($conn,"SELECT * FROM cars ORDER BY id DESC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function car_find_by_id($id){
  $conn=db_connect();
  $cid=(int)$id;
  $res=mysqli_query($conn,"SELECT * FROM cars WHERE id=$cid LIMIT 1");
  $row=$res? mysqli_fetch_assoc($res):null;
  mysqli_close($conn);
  return $row?:null;
}

function cars_all_public(){
  $conn=db_connect();
  $sql = "SELECT c.* FROM cars c LEFT JOIN users u ON c.driver_id=u.id WHERE (c.driver_id IS NULL OR u.approved=1) ORDER BY c.id DESC";
  $res=mysqli_query($conn,$sql);
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}
?>

