<?php

require_once('../models/userModel.php'); 

$firstname = htmlspecialchars($_POST['firstname']);
$lastname = htmlspecialchars($_POST['lastname']);
$dob = htmlspecialchars($_POST['dob']);
$license = htmlspecialchars($_POST['license']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$confirm = htmlspecialchars($_POST['confirm']);

$profilePicture = ''; 
$isValid = true;
$successURL = "Location: ../views/login.php?success=signup";
$errorURL = "Location: ../views/signup.php?";

if (empty($firstname)) {
	$errorURL .= "e1=Please fill out your first name";
	$isValid = false;
}

if (empty($lastname)) {
	$errorURL .= "&e2=Please fill out your last name";
	$isValid = false;
}

if (empty($dob)) {
	$errorURL .= "&e3=Please provide your date of birth";
	$isValid = false;
}

if (empty($license)) {
	$errorURL .= "&e4=Please provide your license number";
	$isValid = false;
}

if (empty($email) ) {
    $errorURL .= "&e5=Please provide a valid email address";
    $isValid = false;
}

if (empty($password)) {
	$errorURL .= "&e6=Please enter a password";
	$isValid = false;
}

if (empty($confirm)) {
	$errorURL .= "&e7=Please confirm your password";
	$isValid = false;
}

if ($password !== $confirm) {
	$errorURL .= "&e8=Passwords do not match";
	$isValid = false;
}

if ($isValid) {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/profile_picture/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicture = $fileName;
            $_SESSION['user_pic'] = $fileName; 
        } else {
            $errorURL .= "&e10=Failed to upload profile picture.";
            $isValid = false;
        }
    } else {
        $profilePicture = '';
    }
}

if ($isValid) {
    $user_data = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'dob' => $dob,
        'license' => $license,
        'email' => $email,
        'password' => $password,
        'profile_picture' => $profilePicture 
    ];

    if (addUser($user_data)) {
        header($successURL);
        exit();
    } else {
        $errorURL .= "&e9=Database Error: Could not create user.";
        header($errorURL);
        exit();
    }
} else {
    header($errorURL);
    exit();
}

?>
