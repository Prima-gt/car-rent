<?php 
session_start();
// login check
if(isset($_SESSION['user_id'])){
  header('Location: ./home.php'); 
  exit; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="./assets/css/login.css">
  <link rel="stylesheet" href="./assets/css/layout.css">
</head>
<body>
  <div class="box">
    <div class="header">Login</div>
    <?php 
      $flashSuccess = $_SESSION['flash_success'] ?? null; 
      $flashError = $_SESSION['flash_error'] ?? null; 
    ?>
    <?php if($flashSuccess): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($flashSuccess); ?></div>
    <?php endif; ?>
    <?php if($flashError): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($flashError); ?></div>
    <?php endif; ?>
    <?php unset($_SESSION['flash_success'], $_SESSION['flash_error']); ?>

    <form action="./controller/auth_controller.php" method="POST">
      <input type="hidden" name="action" value="login" />
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" autocomplete="username" >
        <div id="emailError" class="error"></div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" >
        <div id="passwordError" class="error"></div>
      </div>

   
      <div id="formError" class="error" style="font-weight: bold;"></div>

      <input type="submit" name="submit" id="loginBtn" class="login-btn" value="Login" />

      <a href="./ForgotPassword.php" class="link">Forgot Password?</a> 
      <hr>
      <a href="./signup.php" class="create-btn">Create New Account</a>
    </form>
  </div>

  <!-- <script src="./assets/js/login.js"></script> -->
</body>
</html>

