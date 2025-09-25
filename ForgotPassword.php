<?php 
require_once __DIR__ . '/model/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email'] ?? '');
  if(empty($email)){
    header('Location: ./ForgotPassword.php?error='.urlencode('Email is required.'));
    exit;
  }
  if($email!==''){
    $conn=db_connect();
    $safe=mysqli_real_escape_string($conn, strtolower($email));
    $uRes=mysqli_query($conn,"SELECT id FROM users WHERE LOWER(email)='$safe' LIMIT 1");
    $u=mysqli_fetch_assoc($uRes);
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
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/layout.css">
  </head>
  <body class="forget-main">
    <p><b>Enter your email to receive reset link</b></p>
    <?php if(isset($_GET['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div><?php endif; ?>
    
    <?php
      if(isset($_GET['error'])){ ?><div class="alert alert-error"
      ><?php echo htmlspecialchars($_GET['error']); ?></div><?php
      }

?>
      <form method="POST" style="display:grid;gap:8px;"  >
      <input type="text" id="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      <div id="emailError" class="error"></div>
      <div id="formError" class="error" style="font-weight: bold;"></div>
      <button type="submit" class="button">Send Reset Link</button>
    </form>

    <!-- <script>
      function validateForgotPassword(){
        var email = document.getElementById('email').value.trim();
        var emailError = document.getElementById('emailError');
        var formError = document.getElementById('formError');
        
        emailError.innerHTML = '';
        formError.innerHTML = '';
        
        var valid = true;
        
        if(email === ''){
          emailError.innerHTML = 'Email is required.';
          valid = false;
        } else {
          var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if(!emailPattern.test(email)){
            emailError.innerHTML = 'Enter a valid email address.';
            valid = false;
          }
        }
        
        if(!valid){
          formError.innerHTML = 'Please fix the errors above.';
        }
        
        return valid;
      }
    </script> -->
  </body>
  </html>

