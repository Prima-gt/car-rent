<?php

if (isset($_GET['message'])) {
    $message = $_GET['message'];

    $log_file = '../assets/temp/chat_log.txt'; 
    
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] Received message: " . $message . "\n";
    
    $dir = dirname($log_file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    
    echo "Message received successfully.";
} else {
    http_response_code(400); 
    echo "Error: 'message' parameter is missing.";
}
?>