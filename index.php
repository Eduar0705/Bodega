<?php
require_once 'config/config.php';

// autoload
spl_autoload_register(function($className) {
    $paths = [
        'controllers/' . $className . '.php',
        'models/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

//Enrutamiento 
$action = $_GET['action'] ?? 'Inicio';
$actionName = ucfirst($action) . 'Controller';
$method = $_GET['method'] ?? 'home';
$controllerFile = 'controllers/' . $actionName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    //verificación de las clases
    if (!class_exists($actionName)) {
        die("Error: La clase $actionName no está definida en $controllerFile");
    }
    $controller = new $actionName();
    //verificación del método inicial
    if (!method_exists($controller, $method)) {
        die("Error: El método " . $method . "() no existe en $controllerName");
    }
    
    $controller->$method();
} else {
    error_log("Controlador no encontrado: $controllerFile");
    require_once 'views/error/404.php';
}
