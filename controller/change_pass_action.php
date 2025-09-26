<?php
session_start();
require_once('../models/userModel.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $oldPassword = htmlspecialchars($_POST['oldPassword']);
    $newPassword = htmlspecialchars($_POST['newPassword']);
    $confirmNewPassword = htmlspecialchars($_POST['confirmNewPassword']);

    $isValid = true;
    $errorURL = "Location: ../views/change_pass.html?";

    // 1. Check if new passwords match
    if ($newPassword !== $confirmNewPassword) {
        $errorURL .= "e1=New Passwords dont match&";
        $isValid = false;
    }

    // 2. Check for empty fields
    if (empty($email) || empty($oldPassword) || empty($newPassword) || empty($confirmNewPassword)) {
        $errorURL .= "e2=All fields are required&";
        $isValid = false;
    }
    
    // If client-side validation passes, proceed with database checks
    if ($isValid) {
        $user_data = ['email' => $email, 'password' => $oldPassword];
        
        if (login($user_data)) {
            if (updatePassword($email, $newPassword)) {
                header("Location: ../views/login.php?success=password_changed");
                exit();
            } else {
                $errorURL .= "e4=Password update failed&";
                header($errorURL);
                exit();
            }
        } else {
            $errorURL .= "e3=Incorrect email or old password&";
            header($errorURL);
            exit();
        }
    } else {
        header($errorURL);
        exit();
    }
} else {
    header('Location: ../views/change_pass.html');
    exit();
}