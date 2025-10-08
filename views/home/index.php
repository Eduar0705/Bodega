<?php
// Verificar si la base de datos ya existe
if (verificarBaseDeDatosExiste()) {
    // Si la base de datos existe, mostrar la vista de login
    require 'views/auth/login.php';
    exit();
}

// Si no existe la base de datos, mostrar el formulario de creación
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

        <div class="info">
            <p>Este proceso creará la base de datos "bodega" con todas las tablas vacías y datos básicos.</p>
            <p>Después de crear la base de datos, serás redirigido automáticamente al login.</p>
            <br> <br>
            <p><strong>Datos de inicio de secion:</strong></p>
            <p>- Super clave: 12345678</p>
            <p>- Usuario: admin </p>
            <p>- Clave: admin123</p>
        </div>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_bd'])) {
            crearBaseDeDatos();
        }

        function verificarBaseDeDatosExiste() {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bodega22";

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

        function crearBaseDeDatos() {
            // Configuración de la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bodega22";

            try {
                // Crear conexión sin seleccionar base de datos
                $conn = new mysqli($servername, $username, $password);
                
                // Verificar conexión
                if ($conn->connect_error) {
                    throw new Exception("Error de conexión: " . $conn->connect_error);
                }

                echo "<div class='message warning'>Conectado al servidor MySQL correctamente.</div>";

                // Crear base de datos
                $sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                if ($conn->query($sql) === TRUE) {
                    echo "<div class='message success'>Base de datos '$dbname' creada exitosamente.</div>";
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
                    if ($conn->query($query) === TRUE) {
                        echo "<div class='message success'>Tabla creada: " . getTableName($query) . "</div>";
                    } else {
                        throw new Exception("Error ejecutando consulta: " . $conn->error . "<br>Consulta: " . $query);
                    }
                }

                // Insertar datos básicos
                echo "<div class='message warning'>Insertando datos básicos...</div>";

                // Insertar datos en admin (clave del 1 al 8)
                $conn->query("INSERT INTO `admin` (`id`, `claveSuper`, `NombreAPP`, `precio_dollar`) VALUES (1, '12345678', 'App', 0.00)");
                echo "<div class='message success'>Datos insertados en tabla 'admin'</div>";

                // Insertar usuario por defecto en inf_usuarios
                $conn->query("INSERT INTO `inf_usuarios` (`id`, `cedula`, `clave`, `id_cargo`, `nombre`) VALUES (1, 'admin', 'admin123', 1, 'Administrador')");
                echo "<div class='message success'>Usuario por defecto creado en 'inf_usuarios'</div>";

                // Mostrar información de acceso
                echo "<div class='message success'>
                    <strong>Información de acceso por defecto:</strong><br>
                    - Super clave: 12345678<br>
                    - Usuario: admin<br>
                    - Clave: admin123
                </div>";

                echo "<div class='message success'>¡Base de datos creada exitosamente! Redirigiendo al login...</div>";
                
                // Redirigir después de crear la base de datos
                echo "<script>
                    setTimeout(function() {
                        window.location.href = window.location.href;
                    }, 3000);
                </script>";

            } catch (Exception $e) {
                echo "<div class='message error'>Error: " . $e->getMessage() . "</div>";
            } finally {
                if (isset($conn)) {
                    $conn->close();
                }
            }
        }

        // Función auxiliar para obtener el nombre de la tabla de la consulta
        function getTableName($query) {
            if (strpos($query, 'CREATE TABLE') !== false) {
                preg_match('/`([^`]+)`/', $query, $matches);
                return $matches[1] ?? 'tabla';
            }
            return 'tabla';
        }

        // Mostrar el botón solo si la base de datos no existe y no se está procesando el formulario
        if (!verificarBaseDeDatosExiste() && !isset($_POST['crear_bd'])) {
            echo '<p>Este proceso creará la base de datos "bodega" con todas las tablas vacías y datos básicos.</p>';
            echo '<form method="POST">';
            echo '<button type="submit" name="crear_bd">Crear Base de Datos</button>';
            echo '</form>';
        }
        ?>
    </div>
</body>
</html>