<?php
require_once('connection.php');

function login($user){
    $con = getConnection();
    $stmt = $con->prepare("SELECT Fname, profile_picture FROM user_info WHERE Email=? AND Password=?");
    $stmt->bind_param("ss", $user['email'], $user['password']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1){
        return $result->fetch_assoc();
    } else {
        return false;
    }
}
function getUserByEmail($email){
    $con = getConnection();
    $stmt = $con->prepare("SELECT Fname, Lname, Email, DoB, License, profile_picture FROM user_info WHERE Email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function addUser($user){
    $con = getConnection();
    
    $stmt = $con->prepare("INSERT INTO user_info (Fname, Lname, Email, Password, DoB, License, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssss", 
        $user['firstname'], 
        $user['lastname'], 
        $user['email'],
        $user['password'],
        $user['dob'], 
        $user['license'],
        $user['profile_picture']
    );
    
    $success = $stmt->execute();

    $stmt->close();

    return $success;
}

function updateUser($user){
    $con = getConnection();
    $stmt = $con->prepare("UPDATE user_info SET Fname=?, Lname=?, DoB=?, License=? WHERE Email=?");
    $stmt->bind_param("sssss", 
        $user['firstname'], 
        $user['lastname'], 
        $user['dob'], 
        $user['license'],
        $user['email']
    );
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function updatePassword($email, $newPassword){
    $con = getConnection();
    $stmt = $con->prepare("UPDATE user_info SET password=? WHERE email=?");
    $stmt->bind_param("ss", $newPassword, $email);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

?>