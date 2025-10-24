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
function crearBaseDeDatos($datosIniciales) {
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

            // Tabla proveedores
            "DROP TABLE IF EXISTS `proveedores`",
            "CREATE TABLE `proveedores` (
                `id_proveedor` int NOT NULL AUTO_INCREMENT,
                `nombre_proveedor` varchar(100) NOT NULL,
                `email` varchar(100) NOT NULL,
                `telefono` varchar(100) NOT NULL,
                `direccion` varchar(255) NOT NULL,
                `nombre_encargado` varchar(100) NOT NULL,
                `estado` varchar(100) NOT NULL,
                `nota` varchar(100) NOT NULL,
                PRIMARY KEY (`id_proveedor`)
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

        // Insertar datos en admin con la clave proporcionada
        $claveSuper = $conn->real_escape_string($datosIniciales['claveSuper']);
        $conn->query("INSERT INTO `admin` (`id`, `claveSuper`, `NombreAPP`, `precio_dollar`) VALUES (1, '$claveSuper', 'App', 0.00)");

        // Insertar usuario con los datos proporcionados
        $cedula = $conn->real_escape_string($datosIniciales['cedula']);
        $clave = $conn->real_escape_string($datosIniciales['clave']);
        $id_cargo = (int)$datosIniciales['id_cargo'];
        $nombre = $conn->real_escape_string($datosIniciales['nombre']);
        
        $conn->query("INSERT INTO `inf_usuarios` (`id`, `cedula`, `clave`, `id_cargo`, `nombre`) VALUES (1, '$cedula', '$clave', $id_cargo, '$nombre')");

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

// Variables para mensajes
$errorBD = '';
$erroresValidacion = [];
$mostrarExito = false;

// Verificar si estamos en proceso de creación de base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_bd'])) {
    // Validar datos del formulario
    $datosIniciales = [
        'claveSuper' => trim($_POST['claveSuper'] ?? ''),
        'cedula' => trim($_POST['cedula'] ?? ''),
        'clave' => trim($_POST['clave'] ?? ''),
        'id_cargo' => 1, // Valor por defecto
        'nombre' => trim($_POST['nombre'] ?? '')
    ];

    // Validaciones
    if (empty($datosIniciales['claveSuper'])) {
        $erroresValidacion[] = "La clave super es obligatoria";
    } elseif (strlen($datosIniciales['claveSuper']) < 8) {
        $erroresValidacion[] = "La clave super debe tener al menos 8 caracteres";
    }

    if (empty($datosIniciales['cedula'])) {
        $erroresValidacion[] = "La cédula es obligatoria";
    }

    if (empty($datosIniciales['clave'])) {
        $erroresValidacion[] = "La clave de usuario es obligatoria";
    } elseif (strlen($datosIniciales['clave']) < 6) {
        $erroresValidacion[] = "La clave de usuario debe tener al menos 6 caracteres";
    }

    if (empty($datosIniciales['nombre'])) {
        $erroresValidacion[] = "El nombre es obligatorio";
    }

    // Si no hay errores, crear la base de datos
    if (empty($erroresValidacion)) {
        if (crearBaseDeDatos($datosIniciales)) {
            $mostrarExito = true;
        } else {
            $errorBD = "Error al crear la base de datos. Verifica los logs para más información.";
        }
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
    <title>Configuración Inicial - Sistema de Bodega</title>
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: #2d3748;
        }
        
        .container {
            background: #ffffff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 10px 20px rgba(0, 0, 0, 0.05);
            max-width: 580px;
            width: 100%;
            border: 1px solid #e2e8f0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f7fafc;
        }
        
        .logo-container {
            width: 70px;
            height: 70px;
            background: #22c55e;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .logo-container i {
            font-size: 36px;
            color: white;
        }
        
        h1 {
            color: #1a202c;
            margin-bottom: 8px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .subtitle {
            color: #718096;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.5;
        }
        
        .form-section {
            margin-bottom: 35px;
        }
        
        .section-title {
            color: #1a202c;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: #22c55e;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }
        
        label .required {
            color: #ef4444;
            margin-left: 2px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            background: #ffffff;
            color: #1f2937;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        
        .help-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
            line-height: 1.4;
        }
        
        .btn-primary {
            width: 100%;
            background: #22c55e;
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary i {
            font-size: 16px;
        }
        
        .btn-primary:hover {
            background: #16a34a;
        }
        
        .btn-primary:active {
            background: #15803d;
        }
        
        .divider {
            height: 1px;
            background: #f1f5f9;
            margin: 30px 0;
        }
        
        .info-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 30px;
        }
        
        .info-box-title {
            color: #15803d;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-box-title i {
            font-size: 16px;
        }
        
        .info-box-text {
            color: #166534;
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 640px) {
            .container {
                padding: 30px 24px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container animate__animated animate__fadeIn">
        <div class="header">
            <div class="logo-container">
                <i class="fas fa-warehouse"></i>
            </div>
            <h1>Configuración Inicial</h1>
            <p class="subtitle">Configure los datos de acceso para su sistema de gestión de bodega</p>
        </div>

        <div class="info-box animate__animated animate__fadeInUp">
            <div class="info-box-title">
                <i class="fas fa-info-circle"></i>
                Información Importante
            </div>
            <div class="info-box-text">
                Este proceso creará la base de datos "bodega22" con todas las tablas necesarias. 
                Los datos que ingrese serán sus credenciales de acceso al sistema.
            </div>
        </div>

        <form method="POST" id="setupForm">
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-shield-alt"></i>
                    Clave de Seguridad
                </div>
                
                <div class="form-group">
                    <label for="claveSuper">
                        Clave Super <span class="required">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="claveSuper" 
                        name="claveSuper" 
                        placeholder="Ingrese la clave super"
                        value="<?= htmlspecialchars($_POST['claveSuper'] ?? '') ?>"
                        required
                        minlength="8"
                    >
                    <div class="help-text">Mínimo 8 caracteres. Clave para funciones administrativas críticas.</div>
                </div>
            </div>

            <div class="divider"></div>
            
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user-shield"></i>
                    Usuario Administrador
                </div>
                
                <div class="form-group">
                    <label for="nombre">
                        Nombre Completo <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        placeholder="Juan Pérez"
                        value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="cedula">
                        Usuario / Cédula <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="cedula" 
                        name="cedula" 
                        placeholder="admin o V12345678"
                        value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>"
                        required
                    >
                    <div class="help-text">Este será su nombre de usuario para iniciar sesión.</div>
                </div>

                <div class="form-group">
                    <label for="clave">
                        Contraseña <span class="required">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="clave" 
                        name="clave" 
                        placeholder="Ingrese su contraseña"
                        required
                        minlength="6"
                    >
                    <div class="help-text">Mínimo 6 caracteres. Contraseña de acceso al sistema.</div>
                </div>
            </div>

            <input type="hidden" name="id_cargo" value="1">
            
            <button type="submit" name="crear_bd" class="btn-primary">
                <i class="fas fa-database"></i>
                Crear Base de Datos
            </button>
        </form>
    </div>

    <script>
        <?php if ($mostrarExito): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Base de Datos Creada!',
            text: 'La configuración se completó exitosamente. Redirigiendo al sistema...',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            iconColor: '#22c55e',
            customClass: {
                popup: 'animate__animated animate__fadeInDown'
            }
        }).then(() => {
            window.location.href = '<?= $_SERVER['PHP_SELF'] ?>';
        });
        <?php endif; ?>

        <?php if (!empty($erroresValidacion)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Errores de Validación',
            html: '<div style="text-align: left;"><ul style="margin: 0; padding-left: 25px; line-height: 1.8;">' +
                <?php 
                $erroresHTML = '';
                foreach ($erroresValidacion as $error) {
                    $erroresHTML .= '<li style="margin-bottom: 8px;">' . htmlspecialchars($error) . '</li>';
                }
                echo json_encode($erroresHTML);
                ?> +
                '</ul></div>',
            confirmButtonColor: '#22c55e',
            confirmButtonText: '<i class="fas fa-check"></i> Entendido',
            customClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
        <?php endif; ?>

        <?php if (!empty($errorBD)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error al Crear Base de Datos',
            text: '<?= htmlspecialchars($errorBD) ?>',
            confirmButtonColor: '#22c55e',
            confirmButtonText: '<i class="fas fa-redo"></i> Reintentar',
            customClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
        <?php endif; ?>

        // Validación en tiempo real
        document.getElementById('setupForm').addEventListener('submit', function(e) {
            const claveSuper = document.getElementById('claveSuper').value;
            const clave = document.getElementById('clave').value;
            
            if (claveSuper.length < 8) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Clave Super Inválida',
                    text: 'La clave super debe tener al menos 8 caracteres',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: '<i class="fas fa-check"></i> Entendido',
                    customClass: {
                        popup: 'animate__animated animate__headShake'
                    }
                });
                return false;
            }
            
            if (clave.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña Inválida',
                    text: 'La contraseña debe tener al menos 6 caracteres',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: '<i class="fas fa-check"></i> Entendido',
                    customClass: {
                        popup: 'animate__animated animate__headShake'
                    }
                });
                return false;
            }
        });
    </script>
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