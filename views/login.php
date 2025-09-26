<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="..\assets\CSS\login.css">
</head>
<body>
  <div class="box">
    <div class="header">Login</div>
    <form action="..\controllers\login_action.php" method="post" >
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" >
        <span><?php echo $_GET['e1'] ?? ''  ?></span>
        <div id="emailError" class="error"></div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="off" >
        <span><?php echo $_GET['e2'] ?? ''  ?></span>
        <div id="passwordError" class="error"></div>
      </div>
      <input type="checkbox" id="rememberMe" name="rememberMe" /> <span style="color: #DCFFB7;">Remember Me</span>
      <div id="formError" class="error" style="font-weight: bold;"></div>
       <input type="submit" name="submit" id="loginBtn" class="login-btn" value="login"/> <br>

      <a href="forgot_pass.html" class="link">Reset password</a>
      <a href="change_pass.html" class="link">Change password</a>
      <hr>
      <a href="signup.php" class="create-btn">Create New Account</a>
    </form>

    <span><?php echo $_GET['e3'] ?? ''  ?></span>
    
  </div>

  <script src="..\assets\Javascript\login.js"></script>

</body>
</html>
