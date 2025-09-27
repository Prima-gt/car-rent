<?php

session_start();
require_once('../model/userModel.php');

$username = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

$isValid = true;
$successURL = "Location: ../views/home.php";
$successURL2 = "Location: ../views/admin.php";
$errorURL = "Location: ../views/login.php?login_failed";

if (empty($username)) {
    $errorURL .= "e1=Please fill up the email properly";
    $isValid = false;
}

if (empty($password)) {
    $errorURL .= "&e2=Please fill up the password properly";
    $isValid = false;
}

if ($isValid) {
    if ($username === 'user' && $password === "user") {
        $_SESSION['email'] = $username;
        $_SESSION['user_fname'] = 'Guest';
        $_SESSION['user_pic'] = '../123/assets/profile_picture/default.jpg';
        if (isset($_POST['rememberMe'])) {
            setcookie('remembered_user', $username, time() + (86400 * 30), "/");
        } 
        header($successURL);
        exit();
    }
    else if ($username === 'admin' && $password === "admin") {
        $_SESSION['email'] = $username;
        $_SESSION['user_fname'] = 'admin';
        $_SESSION['user_pic'] = '../123/assets/profile_picture/default.jpg';
        if (isset($_POST['rememberMe'])) {
            setcookie('remembered_user', $username, time() + (86400 * 30), "/");
        } 
        header($successURL2);
        exit();
    }
    else {
        $user_data = ['email' => $username, 'password' => $password];

        try {
            if (login($user_data)) {
                $userData = getUserByEmail($username);

                $_SESSION['email'] = $username;
                $_SESSION['user_fname'] = $userData['Fname'];
                $_SESSION['user_pic'] = $userData['profile_picture'];

                if (isset($_POST['rememberMe'])) {
                    setcookie('remembered_user', $username, time() + (86400 * 30), "/");
                } 
                
                header($successURL);
                exit();
            } else {
                $errorURL .= "&e3=Username or password does not match";
            }
        } catch (Exception $e) {
            $errorURL .= "&e3=Database connection error: " . urlencode($e->getMessage());
            $isValid = false;
        }
    }
}


header($errorURL);
exit();

?>


