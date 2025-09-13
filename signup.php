<?php 
session_start();
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
  <title>Sign Up</title>
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <div class="box" style="max-width: 450px;">
    <div class="header">Create New Account</div>

    <form action="./controller/auth_controller.php" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">
      <input type="hidden" name="action" value="signup" />
      <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname">
        <span id="firstError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname">
        <span id="lastError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob">
        <span id="dobError" class="error"></span>
      </div>


      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email">
        <span id="emailError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="role">Sign Up As</label>
        <select id="role" name="role" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
          <option value="customer">Customer</option>
          <!-- <option value="driver">Driver</option> -->
        </select>
      </div>

      <div id="driverFields" style="display:none;">
        <div class="form-group">
          <label for="license">License Number</label>
          <input type="text" id="license" name="license">
          <span id="licenseError" class="error"></span>
        </div>
        <div class="form-group">
          <label for="car_model">Car Model</label>
          <input type="text" id="car_model" name="car_model">
          <span id="carModelError" class="error"></span>
        </div>
        <div class="form-group">
          <label for="car_owner">Car Owner</label>
          <input type="text" id="car_owner" name="car_owner">
          <span id="carOwnerError" class="error"></span>
        </div>
        <div class="form-group">
          <label for="car_image">Car Image (optional)</label>
          <input type="file" id="car_image" name="car_image" accept="image/*">
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <span id="passwordError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <input type="password" id="confirm" name="confirm">
        <span id="confirmError" class="error"></span>
      </div>

      <span id="formError" class="error"></span>

      <button type="submit" class="login-btn" name="submit">Sign Up</button>
    </form>

    <a href="./login.php" class="link">Back to Login</a>
  </div>

  <script src="./js/signup.js"></script>
  <script>
    (function(){
      var role=document.getElementById('role');
      var df=document.getElementById('driverFields');
      function toggle(){ df.style.display = role.value==='driver' ? 'block' : 'none'; }
      role.addEventListener('change',toggle); toggle();
    })();
  </script>
</body>
</html>

