<?php
session_start();
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_email']);
session_destroy();
header("Location: login.php");
exit();
?>
