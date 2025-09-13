<?php 
$pageTitle='Profile'; 
session_start(); 
if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}

include __DIR__ . '/includes/header.php'; 
?>
  <div class="box" style="max-width: 500px;">
    <div class="header">My Profile</div>

    <form method="POST" action="./controller/profile_controller.php" onsubmit="return validateProfile();">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : '';?>">
        <span id="nameError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';?>">
        <span id="emailError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : '';?>">
        <span id="phoneError" class="error"></span>
      </div>

      <span id="formError" class="error"></span>

      <button type="submit" class="login-btn" name="submit">Save</button>
    </form>

    <a href="./home.php" class="link">Back to Home</a>
    <a href="./logout.php" class="link">Logout</a>
  </div>

  <script>
    function validateProfile(){
      var name = document.getElementById('name').value.trim();
      var email = document.getElementById('email').value.trim();
      var phone = document.getElementById('phone').value.trim();
      var valid = true;
      document.getElementById('nameError').innerHTML='';
      document.getElementById('emailError').innerHTML='';
      document.getElementById('phoneError').innerHTML='';
      document.getElementById('formError').innerHTML='';
      if(name.length<2){document.getElementById('nameError').innerHTML='Enter your name.';valid=false;}
      var emailPattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
      if(!emailPattern.test(email)){document.getElementById('emailError').innerHTML='Enter a valid email.';valid=false;}
      if(phone.length<6){document.getElementById('phoneError').innerHTML='Enter valid phone.';valid=false;}
      if(!valid){document.getElementById('formError').innerHTML='Please fix the above errors.';}
      return valid;
    }
  </script>
<?php include __DIR__ . '/includes/footer.php'; ?>

