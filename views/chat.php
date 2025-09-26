<?php 
session_start(); 
if(!isset($_SESSION['user_id'])){ 
    header('Location: ./login.php'); 
    exit; 
  }

require_once 'model/message_model.php'; 
$pageTitle='Chat'; 
include __DIR__ . '/includes/header.php'; ?>
  <div class="box" style="max-width: 700px;">
    <div class="header">Chat</div>
    <?php 
    $ride_id = isset($_GET['ride_id'])? (int)$_GET['ride_id'] : 0;
    if($ride_id){ messages_mark_read($ride_id,(int)$_SESSION['user_id']); }
    $msgs = $ride_id? messages_for_ride($ride_id):[]; $me=(int)$_SESSION['user_id']; 
     ?>
    <style>
      .chat-wrap { background:#f9fafb; border-radius:6px; min-height:200px; padding:10px; max-height:380px; overflow:auto; }
      .msg { max-width:70%; margin:6px 0; padding:8px 10px; border-radius:10px; word-wrap:break-word; }
      .me { margin-left:auto; background:#2563eb; color:#fff; border-bottom-right-radius:2px; }
      .other { margin-right:auto; background:#e5e7eb; color:#111827; border-bottom-left-radius:2px; }
      .chat-form { display:flex; gap:8px; align-items:center; margin-top:10px; }
      .chat-form input[type=text] { flex:1; }
    </style>
    <div class="chat-wrap">
      <?php if(empty($msgs)){ echo '<div class="car-owner">No messages.</div>'; } else { foreach($msgs as $m){ $cls=((int)$m['sender_id']===$me)? 'msg me':'msg other'; echo '<div class="'.$cls.'">'.htmlspecialchars($m['content']).'</div>'; } } ?>
    </div>
    <form method="POST" action="./controller/message_controller.php" class="chat-form">
      <input type="hidden" name="ride_id" value="<?php echo $ride_id; ?>">
      <input type="hidden" name="receiver_id" value="0">
      <input type="text" id="content" name="content" placeholder="Type a message...">
      <button class="login-btn" style="width:auto;min-width:90px;">Send</button>
    </form>
    <a class="link" href="./home.php">Back</a>
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>

