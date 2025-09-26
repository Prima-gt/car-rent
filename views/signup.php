<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="..\assets\CSS\login.css">
</head>
<body>

  <div class="box" style="max-width: 450px;">
    <div class="header">Create New Account</div>

    <form action="..\controllers\signup_action.php" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">
          
      <div class="form-group">
        <label for="profile_picture">Profile Picture</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
        <span id="profilePicError" class="error"></span>
      </div>
      
      <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname">
        <span id="firstError" class="error"></span>
        <span><?php echo $_GET['e1'] ?? ''  ?></span>
      </div>

      <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname">
        <span id="lastError" class="error"></span>
        <span><?php echo $_GET['e2'] ?? ''  ?></span>
      </div>

      <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob">
        <span id="dobError" class="error"></span>
        <span><?php echo $_GET['e3'] ?? ''  ?></span>
      </div>

      <div class="form-group">
        <label for="license">License Number</label>
        <input type="text" id="license" name="license">
        <span id="licenseError" class="error"></span>
        <span><?php echo $_GET['e4'] ?? ''  ?></span>
      </div>

      <div class="form-group">
        <label for="email">Email </label>
        <input type="text" id="email" name="email" >
         <span id="email-msg"></span>
        <span id="emailError" class="error"></span>
        <span><?php echo $_GET['e5'] ?? ''  ?></span>
      </div>


      <div class="form-group">
        <label for="password"> Password</label>
        <input type="password" id="password" name="password">
        <span id="passwordError" class="error"></span>
        <span><?php echo $_GET['e6'] ?? ''  ?></span>
      </div>

      <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <input type="password" id="confirm" name="confirm">
        <span id="confirmError" class="error"></span>
        <span><?php echo $_GET['e7'] ?? ''  ?></span>
      </div>

      <span><?php echo $_GET['e8'] ?? ''  ?></span>

      <span id="formError" class="error"></span>

      
      <button type="submit" class="login-btn" name="submit">Sign Up</button>
    </form>
    <a href="login.php" class="link">Back to Login</a>
  </div>

<script src="..\assets\Javascript\signup.js"></script>


</body>
</html>