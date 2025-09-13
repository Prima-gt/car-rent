<?php
 
$DB_HOST =  'localhost';
$DB_USER =  'root';
$DB_PASS = '';
$DB_NAME =  'carrent';

function db_connect(){
  global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
  $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
  if(!$conn){
    die('Database connection failed: ' . mysqli_connect_error());
  }
  return $conn;
}
?>