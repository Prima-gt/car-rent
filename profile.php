<?php 
$pageTitle='Profile'; 
session_start(); 
if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}

require_once __DIR__ . '/model/user_model.php';
$user = user_find_by_id($_SESSION['user_id']);

include __DIR__ . '/includes/header.php'; 
?>
  <div class="box" style="max-width: 500px;">
    <div class="header">My Profile</div>

    <form method="POST" action="./controller/profile_controller.php" enctype="multipart/form-data" onsubmit="return validateProfile();">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : '';?>">
        <span id="nameError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : '';?>">
        <span id="emailError" class="error"></span>
      </div>

      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : '';?>">
        <span id="phoneError" class="error"></span>
      </div>

      <div class="form-group">
        <label>Profile Image</label>
        <div style="margin-bottom:10px;">
          <?php 
            $currentAvatar = isset($user['profile_image']) && $user['profile_image'] ? $user['profile_image'] : null; 
          ?>
          <?php if($currentAvatar): ?>
            <img src="<?php echo htmlspecialchars($currentAvatar); ?>" alt="Profile Image" style="width:120px;height:120px;object-fit:cover;border-radius:60px;border:1px solid #ddd;" />
          <?php else: ?>
            <div style="width:120px;height:120px;border-radius:60px;background:#f0f0f0;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;color:#888;">No Image</div>
          <?php endif; ?>
        </div>
        <input type="file" id="profile_image" name="profile_image" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif">
        <span id="imageError" class="error"></span>
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

