<?php
session_start();
require_once __DIR__ . '/../model/user_model.php';
require_once __DIR__ . '/../model/car_model.php';

if(isset($_POST['action']) && $_POST['action']==='login'){
  $email = strtolower(trim($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  $u = user_find_by_email($email);
  if($u && $u['password']===$password){
    if(($u['role']==='driver') && (int)($u['approved'] ?? 0)!==1){
      header('Location: ../login.php');
      exit;
    }
    $_SESSION['user_id']=$u['id'];
    $_SESSION['name']=$u['name'];
    $_SESSION['email']=$u['email'];
    $_SESSION['role']=$u['role'] ?? 'customer';
    header('Location: ../home.php');
    exit;
  }
  header('Location: ../login.php');
  exit;
}

if(isset($_POST['action']) && $_POST['action']==='signup'){
  $firstname = trim($_POST['firstname'] ?? '');
  $lastname = trim($_POST['lastname'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $name = trim($firstname . ' ' . $lastname);
  $role = isset($_POST['role']) ? $_POST['role'] : 'customer';
  $id = user_create($name,$email,$password,$role);
  if($role==='driver'){
    $car_model = trim($_POST['car_model'] ?? '');
    $car_owner = trim($_POST['car_owner'] ?? '');
    $imagePath=null;
    if(isset($_FILES['car_image']) && is_uploaded_file($_FILES['car_image']['tmp_name'])){
      $ext=pathinfo($_FILES['car_image']['name'], PATHINFO_EXTENSION);
      $safeName='drv_'.time().'_'.rand(100,999).'.'.preg_replace('/[^a-zA-Z0-9]/','',$ext);
      $dest=dirname(__DIR__).'/pic/'.$safeName;
      if(move_uploaded_file($_FILES['car_image']['tmp_name'],$dest)){
        $imagePath='pic/'.$safeName;
      }
    }
    if($car_model!=='' && $car_owner!==''){
      car_create($car_model,$car_owner,$id,$imagePath);
    }
  }
  $_SESSION['user_id']=$id;
  $_SESSION['name']=$name;
  $_SESSION['email']=$email;
  $_SESSION['role']=$role;
  header('Location: ../home.php');
  exit;
}

header('Location: ../login.php');
exit;
?>

