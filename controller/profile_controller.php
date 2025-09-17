<?php
session_start();
require_once __DIR__ . '/../model/db.php';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_SESSION['user_id'])){
  $conn = db_connect();
  // Ensure profile_image column exists (in case migration not applied)
  $colRes = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'profile_image'");
  if(!$colRes || mysqli_num_rows($colRes) === 0){
    @mysqli_query($conn, "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) NULL AFTER phone");
  }
  $id = (int)$_SESSION['user_id'];
  $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
  $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
  $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
  $profileImagePath = null;

  // Handle optional profile image upload
  if(isset($_FILES['profile_image']) && is_array($_FILES['profile_image']) && ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK)){
    $tmpPath = $_FILES['profile_image']['tmp_name'];
    $origName = $_FILES['profile_image']['name'] ?? '';
    $size = (int)($_FILES['profile_image']['size'] ?? 0);

    // Basic validations: size and mime type
    $maxBytes = 2 * 1024 * 1024; // 2MB
    if($size <= 0){
      $_SESSION['flash_error'] = 'Selected file is empty.';
    }
    if($size > 0 && $size <= $maxBytes){
      $info = @getimagesize($tmpPath);
      if($info && isset($info[2])){
        $mime = $info['mime'] ?? '';
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        if(in_array($mime, $allowed, true)){
          // Prepare destination directory
          $destDir = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . 'Profile pic';
          if(!is_dir($destDir)){
            @mkdir($destDir, 0775, true);
          }
          // Generate safe filename
          $ext = pathinfo($origName, PATHINFO_EXTENSION);
          $ext = $ext ? strtolower($ext) : 'jpg';
          $safeBase = 'avatar_' . $id . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4));
          $fileName = $safeBase . '.' . $ext;
          $destPath = $destDir . DIRECTORY_SEPARATOR . $fileName;

          if(move_uploaded_file($tmpPath, $destPath)){
            // Web path relative to document root
            $profileImagePath = 'Profile pic/' . $fileName;
          } else {
            $_SESSION['flash_error'] = 'Failed to save uploaded file.';
          }
        } else { $_SESSION['flash_error'] = 'Unsupported image type.'; }
      } else { $_SESSION['flash_error'] = 'Not a valid image file.'; }
    } else if($size > $maxBytes){
      $_SESSION['flash_error'] = 'Image too large. Max 2MB.';
    }
  }

  if($profileImagePath){
    $safePath = mysqli_real_escape_string($conn, $profileImagePath);
    $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', profile_image='$safePath' WHERE id=$id";
  } else {
    $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id";
  }
  mysqli_query($conn, $sql);
  mysqli_close($conn);
  $_SESSION['name'] = $name;
  $_SESSION['email'] = $email;
  $_SESSION['phone'] = $phone;
  if($profileImagePath){
    $_SESSION['profile_image'] = $profileImagePath;
  }
  $_SESSION['flash_success'] = 'Profile updated successfully.';
}
header('Location: ../profile.php');
exit;
?>

