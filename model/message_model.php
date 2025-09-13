<?php
require_once __DIR__ . '/db.php';

function message_send($ride_id,$sender_id,$receiver_id,$content){
  $conn=db_connect();
  $rid=(int)$ride_id; $sid=(int)$sender_id; $rcv=(int)$receiver_id;
  $txt=mysqli_real_escape_string($conn,$content);
  mysqli_query($conn,"INSERT INTO messages(ride_id,sender_id,receiver_id,content,is_read) VALUES($rid,$sid,$rcv,'$txt',0)");
  mysqli_close($conn);
}

function messages_for_ride($ride_id){
  $conn=db_connect();
  $rid=(int)$ride_id; $res=mysqli_query($conn,"SELECT * FROM messages WHERE ride_id=$rid ORDER BY id ASC");
  $list=[]; while($res && $row=mysqli_fetch_assoc($res)){ $list[]=$row; }
  mysqli_close($conn);
  return $list;
}

function messages_mark_read($ride_id,$user_id){
  $conn=db_connect();
  $rid=(int)$ride_id; $uid=(int)$user_id;
  mysqli_query($conn,"UPDATE messages SET is_read=1 WHERE ride_id=$rid AND receiver_id=$uid");
  mysqli_close($conn);
}
?>

