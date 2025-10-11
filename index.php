<?php
require_once 'config/config.php';

// Función para verificar si la base de datos existe
function verificarBaseDeDatosExiste() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bodega";

    try {
        $conn = new mysqli($servername, $username, $password);
        
        if ($conn->connect_error) {
            return false;
        }

        // Verificar si la base de datos existe
        $result = $conn->query("SHOW DATABASES LIKE '$dbname'");
        $exists = $result->num_rows > 0;
        
        $conn->close();
        return $exists;

    } catch (Exception $e) {
        return false;
    }
}

// Función para crear la base de datos
function crearBaseDeDatos() {
    // Configuración de la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bodega";

    try {
        // Crear conexión sin seleccionar base de datos
        $conn = new mysqli($servername, $username, $password);
        
        // Verificar conexión
        if ($conn->connect_error) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }

        // Crear base de datos
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if ($conn->query($sql) === TRUE) {
            // Base de datos creada exitosamente
        } else {
            throw new Exception("Error creando base de datos: " . $conn->error);
        }

        // Seleccionar la base de datos
        $conn->select_db($dbname);

        // Array con todas las consultas SQL
        $queries = [
            // Tabla admin
            "DROP TABLE IF EXISTS `admin`",
            "CREATE TABLE `admin` (
                `id` int NOT NULL AUTO_INCREMENT,
                `claveSuper` varchar(100) NOT NULL,
                `NombreAPP` varchar(100) NOT NULL,
                `precio_dollar` double(100,2) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

            // Tabla clientes
            "DROP TABLE IF EXISTS `clientes`",
            "CREATE TABLE `clientes` (
                `id_cliente` int NOT NULL AUTO_INCREMENT,
                `nombre_apellido` varchar(100) NOT NULL,
                `cedula` varchar(100) NOT NULL,
                `telefono` varchar(100) NOT NULL,
                PRIMARY KEY (`id_cliente`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

            // Tabla cuentascobrar
            "DROP TABLE IF EXISTS `cuentascobrar`",
            "CREATE TABLE `cuentascobrar` (
                `id_historial` int NOT NULL AUTO_INCREMENT,
                `fecha` date NOT NULL,
                `cliente` varchar(100) NOT NULL,
                `tipo_pago` varchar(100) NOT NULL,
                `tipo_venta` varchar(100) NOT NULL,
                `total_usd` decimal(10,2) NOT NULL,
                `productos_vendidos` json NOT NULL,
                PRIMARY KEY (`id_historial`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

            // Tabla historial
            "DROP TABLE IF EXISTS `historial`",
            "CREATE TABLE `historial` (
                `id_historial` int NOT NULL AUTO_INCREMENT,
                `fecha` date NOT NULL,
                `cliente` varchar(100) NOT NULL,
                `tipo_pago` varchar(100) NOT NULL,
                `tipo_venta` varchar(100) NOT NULL,
                `total_usd` decimal(10,2) NOT NULL,
                `productos_vendidos` json NOT NULL,
                PRIMARY KEY (`id_historial`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

            // Tabla inf_usuarios
            "DROP TABLE IF EXISTS `inf_usuarios`",
            "CREATE TABLE `inf_usuarios` (
                `id` int NOT NULL AUTO_INCREMENT,
                `cedula` varchar(100) NOT NULL,
                `clave` varchar(100) NOT NULL,
                `id_cargo` int NOT NULL,
                `nombre` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

            // Tabla inventario
            "DROP TABLE IF EXISTS `inventario`",
            "CREATE TABLE `inventario` (
                `id_producto` int NOT NULL AUTO_INCREMENT,
                `codigo` int NOT NULL,
                `nombre` varchar(100) NOT NULL,
                `un_disponibles` int DEFAULT '0',
                `precio_compra` decimal(10,2) NOT NULL,
                `precio_venta` decimal(10,2) NOT NULL,
                `medida` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`id_producto`),
                UNIQUE KEY `codigo` (`codigo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"
        ];

        // Ejecutar consultas de creación de tablas
        foreach ($queries as $query) {
            if ($conn->query($query) !== TRUE) {
                throw new Exception("Error ejecutando consulta: " . $conn->error . "<br>Consulta: " . $query);
            }
        }

        // Insertar datos básicos
        // Insertar datos en admin (clave del 1 al 8)
        $conn->query("INSERT INTO `admin` (`id`, `claveSuper`, `NombreAPP`, `precio_dollar`) VALUES (1, '12345678', 'App', 0.00)");

        // Insertar usuario por defecto en inf_usuarios
        $conn->query("INSERT INTO `inf_usuarios` (`id`, `cedula`, `clave`, `id_cargo`, `nombre`) VALUES (1, 'admin', 'admin123', 1, 'Administrador')");

        return true;

    } catch (Exception $e) {
        error_log("Error creando base de datos: " . $e->getMessage());
        return false;
    } finally {
        if (isset($conn)) {
            $conn->close();
        }
    }
}

// Verificar si estamos en proceso de creación de base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_bd'])) {
    if (crearBaseDeDatos()) {
        // Redirigir para evitar reenvío del formulario
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $errorBD = "Error al crear la base de datos. Verifica los logs para más información.";
    }
}

// Verificar si la base de datos existe
if (!verificarBaseDeDatosExiste()) {
    // Mostrar formulario de creación de base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Base de Datos Bodega</title>
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Base de Datos Bodega</h1>

        <?php if (isset($errorBD)): ?>
            <div class="message error"><?= $errorBD ?></div>
        <?php endif; ?>

        <div class="info">
            <p>Este proceso creará la base de datos "bodega" con todas las tablas vacías y datos básicos.</p>
            <p>Después de crear la base de datos, serás redirigido automáticamente al login.</p>
            <br> <br>
            <p><strong>Datos de inicio de sesión:</strong></p>
            <p>- Super clave: 12345678</p>
            <p>- Usuario: admin</p>
            <p>- Clave: admin123</p>
        </div>
        
        <form method="POST">
            <button type="submit" name="crear_bd">Crear Base de Datos</button>
        </form>
    </div>
</body>
</html>
<?php
    exit();
}

// Si la base de datos existe, continuar con el enrutamiento normal

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

// Función auxiliar para detectar peticiones AJAX
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ||
            (isset($_SERVER['CONTENT_TYPE']) && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);
}

// Función para enviar error JSON
function sendJsonError($message, $code = 400) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

// Enrutamiento 
$action = $_GET['action'] ?? 'Inicio';
$actionName = ucfirst($action) . 'Controller';
$method = $_GET['method'] ?? 'home';
$controllerFile = 'controllers/' . $actionName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // verificación de las clases
    if (!class_exists($actionName)) {
        if (isAjaxRequest()) {
            sendJsonError("La clase $actionName no está definida");
        }
        die("Error: La clase $actionName no está definida en $controllerFile");
    }
    
    $controller = new $actionName();
    
    // verificación del método
    if (!method_exists($controller, $method)) {
        if (isAjaxRequest()) {
            sendJsonError("El método $method() no existe en $actionName");
        }
        die("Error: El método $method() no existe en $actionName");
    }
    
    $controller->$method();
} else {
    error_log("Controlador no encontrado: $controllerFile");
    
    if (isAjaxRequest()) {
        sendJsonError("Controlador no encontrado", 404);
    }
    
    require_once 'views/error/404.php';
}