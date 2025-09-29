<?php
session_start();
require_once __DIR__ . '/../config/database.php';

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../app/';
    $class = str_replace('\\', '/', $class);
    $paths = [
        $baseDir . 'core/' . basename($class) . '.php',
        $baseDir . 'controllers/' . basename($class) . '.php',
        $baseDir . 'models/' . basename($class) . '.php',
    ];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
        }
    }
});

$controller = new AdminController($pdo);
echo $controller->dashboard();
