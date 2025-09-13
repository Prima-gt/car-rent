<?php 
require_once __DIR__ . '/model/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email'] ?? '');
  if($email!==''){
    $conn=db_connect();
    $safe=mysqli_real_escape_string($conn, strtolower($email));
    $uRes=mysqli_query($conn,"SELECT id FROM users WHERE LOWER(email)='$safe' LIMIT 1");
    $u=mysqli_fetch_assoc($uRes);
    // create table if not exists
    //mysqli_query($conn,"CREATE TABLE IF NOT EXISTS reset_tokens (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, token VARCHAR(64) NOT NULL, expires_at DATETIME NOT NULL, INDEX(token), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE)");
    if($u){
      $token=bin2hex(random_bytes(16));
      $uid=(int)$u['id'];
      $exp=date('Y-m-d H:i:s', time()+3600);
      mysqli_query($conn,"INSERT INTO reset_tokens(user_id,token,expires_at) VALUES($uid,'$token','$exp')");
      $link='savepass.php?token='.$token;
      $log=__DIR__.'/reset_requests.txt';
      file_put_contents($log, date('c')."\t".$email."\t".$link."\n", FILE_APPEND);
    }
    mysqli_close($conn);
    header('Location: ./ForgotPassword.php?success='.urlencode('If your email exists, a reset link was sent.'));
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./css/ForgotPassword.css">
  </head>
  <body class="forget-main">
    <p><b>Enter your email to receive reset link</b></p>
    <?php if(isset($_GET['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div><?php endif; ?>
    <form method="POST" style="display:grid;gap:8px;">
      <input type="text" name="email" placeholder="Email">
      <button type="submit" class="button">Send Reset Link</button>
    </form>
  </body>
  </html>

