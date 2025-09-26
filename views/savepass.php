<?php 
session_start();
// Allow access if token is present, otherwise require login
if(!isset($_GET['token']) && !isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}

$pageTitle='Change Password';
 include __DIR__ . '/includes/header.php'; 
 ?>
  <div class="forget-main">
    <p><b>Enter your new password</b></p>
    <form class="from" id="savepassForm" method="POST" action="./controller/password_controller.php"  >
      <input type="password" id="user" name="password" placeholder="New Password">
      <br>
      <input type="password" id="pass" placeholder="Confirm Password">
      <input type="hidden" name="action" value="change">
      <?php if(isset($_GET['token'])): ?><input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>"><?php endif; ?>
      <button type="submit" class="button">Save Password</button>
    </form>
 
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>