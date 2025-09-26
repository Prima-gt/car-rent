<?php 
if(session_status()===PHP_SESSION_NONE){ 
  session_start(); 
  } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/login.css">
  <link rel="stylesheet" href="./assets/css/home.css">
  <link rel="stylesheet" href="./assets/css/layout.css">
  <title><?php echo isset($pageTitle)? htmlspecialchars($pageTitle) : 'Car Rent'; ?></title>
</head>
<body>
  <center class="site-header">
    <div class="brand"><a href="./home.php">CarRent</a></div>
    <nav class="nav">
      <a href="./home.php">Home</a>
      <a href="./rent.php">Rent</a>
      <a href="./myrent.php">My Rides</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="./messages.php">Messages<?php 
          require_once   'model/db.php'; 
          $hconn=db_connect(); 
          $uid=(int)$_SESSION['user_id']; 
          $hCntRes=mysqli_query($hconn,"SELECT COUNT(*) AS c FROM messages WHERE receiver_id=$uid AND is_read=0"); 
          $hCntRow=$hCntRes? mysqli_fetch_assoc($hCntRes):['c'=>0]; 
          mysqli_close($hconn);
          echo ' ('.(int)$hCntRow['c'].')';
        ?></a>
      <?php endif; ?>
      <?php if(isset($_SESSION['role']) && $_SESSION['role']==='admin'): ?>
        <a href="./panel_admin.php">Admin</a>
      <?php endif; ?>
      <?php if(isset($_SESSION['role']) && $_SESSION['role']==='driver'): ?>
        <a href="./panel_driver.php">Driver</a>
      <?php endif; ?>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="./profile.php">Profile</a>
        <a href="./logout.php">Logout</a>
      <?php else: ?>
        <a href="./login.php">Login</a>
        <a class="btn" href="./signup.php">Sign Up</a>
      <?php endif; ?>
    </nav>
      </center>
  <main class="site-main">
    <?php 
      $flashSuccess = $_SESSION['flash_success'] ?? null; 
      $flashError = $_SESSION['flash_error'] ?? null; 
      if(isset($_GET['success'])){ $flashSuccess = $_GET['success']; }
      if(isset($_GET['error'])){ $flashError = $_GET['error']; }
    ?>
    <?php if($flashSuccess): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($flashSuccess); ?></div>
    <?php endif; ?>
    <?php if($flashError): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($flashError); ?></div>
    <?php endif; ?>
    <?php unset($_SESSION['flash_success'], $_SESSION['flash_error']); ?>

