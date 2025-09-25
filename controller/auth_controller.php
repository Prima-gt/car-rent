<?php
session_start();
require_once __DIR__ . '/../model/user_model.php';
require_once __DIR__ . '/../model/car_model.php';

if(isset($_POST['action']) && $_POST['action']==='login'){
  $email = strtolower(trim($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  if(empty($email) || empty($password)){
    $_SESSION['flash_error'] = 'Email and password are required.';
    header('Location: ../login.php');
    exit;
  }
  $u = user_find_by_email($email);
  if($u && $u['password']===$password){
   if(($u['role']==='driver') && (int)($u['approved'] ?? 0)!==1){
      $_SESSION['flash_error'] = 'Your driver account is pending approval.';
      header('Location: ../login.php');
      exit;
    } 
    $_SESSION['user_id']=$u['id'];
    $_SESSION['name']=$u['name'];
    $_SESSION['email']=$u['email'];
    $_SESSION['role']=$u['role'] ?? 'customer';
    $_SESSION['flash_success'] = 'Logged in successfully.';
    header('Location: ../home.php');
    exit;
  }
  $_SESSION['flash_error'] = 'Invalid email or password.';
  header('Location: ../login.php');
  exit;
}

if(isset($_POST['action']) && $_POST['action']==='signup'){
  $firstname = trim($_POST['firstname'] ?? '');
  $lastname = trim($_POST['lastname'] ?? '');
  $email = strtolower(trim($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  $name = trim($firstname . ' ' . $lastname);
  $role = isset($_POST['role']) ? $_POST['role'] : 'customer';
  if(empty($firstname) || empty($lastname) || empty($email) || empty($password)){
    $_SESSION['flash_error'] = 'All fields are required.';
    header('Location: ../signup.php');
    exit;
  }
  // Prevent duplicate email error by checking first
  $existing = user_find_by_email($email);
  if($existing){
    $_SESSION['flash_error'] = 'An account with this email already exists.';
    header('Location: ../signup.php');
    exit;
  }

  $id = user_create($name,$email,$password,$role);
  if($id && $id > 0){
    $_SESSION['user_id']=$id;
    $_SESSION['name']=$name;
    $_SESSION['email']=$email;
    $_SESSION['role']=$role;
    $_SESSION['flash_success'] = ($role==='driver') ? 'Signup successful. Your driver account awaits approval.' : 'Signup successful. Welcome!';
    header('Location: ../home.php');
    exit;
  } else {
    $_SESSION['flash_error'] = 'Could not create account. Please try again.';
    header('Location: ../signup.php');
    exit;
  }
}

header('Location: ../login.php');
exit;
?>

