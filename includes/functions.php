<?php
session_start();
require_once 'config/database.php';


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}


function verify_password($password, $hash) {
    return password_verify($password, $hash);
}


function is_logged_in() {
    return isset($_SESSION['user_id']);
}


function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}


function redirect($url) {
    header("Location: $url");
    exit();
}


function generate_token() {
    return bin2hex(random_bytes(32));
}


function get_user_by_email($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function get_user_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function create_user($full_name, $email, $password, $phone, $address, $user_type) {
    global $pdo;
    $hashed_password = hash_password($password);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone, address, user_type) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$full_name, $email, $hashed_password, $phone, $address, $user_type]);
}


function update_user($id, $full_name, $phone, $address) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
    return $stmt->execute([$full_name, $phone, $address, $id]);
}


function update_password($id, $new_password) {
    global $pdo;
    $hashed_password = hash_password($new_password);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$hashed_password, $id]);
}


function approve_user($id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    return $stmt->execute([$id]);
}


function reject_user($id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
    return $stmt->execute([$id]);
}
?>
