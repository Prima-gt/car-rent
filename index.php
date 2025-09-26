<?php

  $url = $_SERVER['REQUEST_URI'];
$turl=  parse_url($url);
//path
$path = $turl['path'];
if ($path == '/' || $path == '/index.php') {
    // redirect to login.php
    header('Location: ./login.php');
    exit;
} 
// if have phpextension
if (strpos($path, '.php') !== false) {
    // do nothing
    $phpFileName = ltrim($path, '/');
    $phpFileName = 'views/' . $phpFileName; 
    
    if (file_exists($phpFileName)) { 
        include $phpFileName; 
        exit;
    } else {
        // redirect to login.php
        header('Location: ./login.php');
        exit;
    }
} else {
    // redirect to login.php
    echo "no php extension";
    exit;
}
 