<?php
require_once __DIR__ . '/db.php';

function rating_create($ride_id,$driver_id,$stars,$review){
  $conn=db_connect();
  $rid=(int)$ride_id; $did=(int)$driver_id; $s=(int)$stars;
  $rev=mysqli_real_escape_string($conn,$review);
  mysqli_query($conn,"INSERT INTO ratings(ride_id,driver_id,stars,review) VALUES($rid,$did,$s,'$rev')");
  mysqli_close($conn);
}

function rating_find_by_ride($ride_id){
  $conn=db_connect();
  $rid=(int)$ride_id;
  $res=mysqli_query($conn,"SELECT * FROM ratings WHERE ride_id=$rid LIMIT 1");
  $row=$res? mysqli_fetch_assoc($res):null;
  mysqli_close($conn);
  return $row?:null;
}
?>

