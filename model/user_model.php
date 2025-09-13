<?php
require_once __DIR__ . '/db.php';

function user_find_by_email($email){
  $conn = db_connect();
  $safe = mysqli_real_escape_string($conn, strtolower(trim($email)));
  $sql = "SELECT * FROM users WHERE LOWER(email)='$safe' LIMIT 1";
  $res = mysqli_query($conn, $sql);
  $row = $res ? mysqli_fetch_assoc($res) : null;
  mysqli_close($conn);
  return $row ?: null;
}

function user_create($name,$email,$password,$role='customer'){
  $conn = db_connect();
  $name = mysqli_real_escape_string($conn, $name);
  $email = mysqli_real_escape_string($conn, strtolower(trim($email)));
  $password = mysqli_real_escape_string($conn, $password);
  $role = mysqli_real_escape_string($conn, $role);
  $approved = ($role==='driver')? 0 : 1;
  $sql = "INSERT INTO users(name,email,password,role,approved) VALUES('$name','$email','$password','$role',$approved)";
  $ok = mysqli_query($conn, $sql);
  $id = $ok ? mysqli_insert_id($conn) : 0;
  mysqli_close($conn);
  return $id;
}

function user_find_by_id($id){
  $conn=db_connect();
  $id=(int)$id;
  $res=mysqli_query($conn,"SELECT * FROM users WHERE id=$id LIMIT 1");
  $row=$res? mysqli_fetch_assoc($res):null;
  mysqli_close($conn);
  return $row?:null;
}

function users_pending_drivers(){
  $conn=db_connect();
  $res=mysqli_query($conn,"SELECT * FROM users WHERE role='driver' AND approved=0 ORDER BY id DESC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

?>

