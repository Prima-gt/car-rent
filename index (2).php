<?php
// Front controller: bootstrap minimal MVC while preserving existing URLs
session_start();

// If a direct file exists (legacy pages), serve it to preserve workflow
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
$projectRoot = rtrim(str_replace('\\', '/', __DIR__), '/');
$relative = trim(str_replace($docRoot, '', $projectRoot), '/');
$fullPath = $docRoot . '/' . ltrim($requestedPath, '/');

if ($requestedPath && $requestedPath !== '/' && is_file($fullPath)) {
    return require $fullPath;
}

// Bootstrap core
require_once __DIR__ . '/config/database.php';

// Minimal autoload for app classes
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/app/';
    $class = str_replace('\\', '/', $class);
    $paths = [
        $baseDir . 'core/' . basename($class) . '.php',
        $baseDir . 'controllers/' . basename($class) . '.php',
        $baseDir . 'models/' . basename($class) . '.php',
    ];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

// Simple router: map / to HomeController@index; otherwise fall back to legacy pages
$route = trim($requestedPath ?: '/', '/');
if ($route === '') {
    if (class_exists('HomeController')) {
        $controller = new HomeController($pdo);
        echo $controller->index();
        exit;
    }
}

// Default legacy homepage if no controller present
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Car Management System</h1>
        <p>Welcome to our Car Rental Platform</p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Welcome</h2>
            <p>Please choose an option to continue:</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="login.php" class="btn" style="margin: 10px;">User Login</a>
                <a href="register.php" class="btn" style="margin: 10px;">Register</a>
                <a href="admin/login.php" class="btn btn-warning" style="margin: 10px;">Admin Login</a>
            </div>
        </div>
    </div>
</body>
</html>
