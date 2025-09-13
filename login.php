<?php session_start(); 
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
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <div class="box">
    <div class="header">Login</div>

    <form action="./controller/auth_controller.php" method="POST">
      <input type="hidden" name="action" value="login" />
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" autocomplete="username" required>
        <div id="emailError" class="error"></div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
        <div id="passwordError" class="error"></div>
      </div>

      <div style="margin-bottom: 10px;">
        <input type="checkbox" id="rememberMe" /> <span style="color: #6b7280;">Remember Me</span>
      </div>

      <div id="formError" class="error" style="font-weight: bold;"></div>

      <input type="submit" name="submit" id="loginBtn" class="login-btn" value="Login" />

      <a href="./ForgotPassword.php" class="link">Forgot Password?</a> 
      <hr>
      <a href="./signup.php" class="create-btn">Create New Account</a>
    </form>
  </div>

  <script src="./js/login.js"></script>
</body>
</html>

