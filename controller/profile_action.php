<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_SESSION['email'];

    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $license = htmlspecialchars(trim($_POST['license']));

    $isValid = true;
    $errorURL = "Location: ../views/profile.php?";

    if (empty($firstname)) {
        $errorURL .= "e1=First name is required&";
        $isValid = false;
    }
    if (empty($lastname)) {
        $errorURL .= "e2=Last name is required&";
        $isValid = false;
    }
    if (empty($dob)) {
        $errorURL .= "e3=Date of Birth is required&";
        $isValid = false;
    }
    if (empty($license)) {
        $errorURL .= "e4=License Number is required&";
        $isValid = false;
    }

    if ($isValid) {
        $userData = [
            'email' => $email, 
            'firstname' => $firstname,
            'lastname' => $lastname,
            'dob' => $dob,
            'license' => $license
        ];

        if (updateUser($userData)) {
            $_SESSION['user_fname'] = $firstname;
            header("Location: ../views/profile.php?success=1");
            exit();
        } else {
            $errorURL .= "e5=Update failed. Please try again.&";
            header($errorURL);
            exit();
        }
    } else {
        header($errorURL);
        exit();
    }
} else {
    header('Location: ../views/profile.php');
    exit();
}
?>