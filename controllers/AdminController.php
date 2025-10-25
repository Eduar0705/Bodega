<?php
require_once 'model/Conexion.php';
require_once 'model/Inicio.php';
require_once 'model/inventario.php';
require_once 'model/pos.php';
require_once 'model/config.php';
require_once 'model/usuarios.php';
require_once 'model/historial.php';
require_once 'model/ccobrar.php';
require_once 'model/proveedores.php';
require_once 'model/estadisticas.php';

class AdminController 
{
    private $bdatos;
    private $inventario;
    private $pos;
    private $config;
    private $clientes;
    private $historial;
    private $ccobrar;
    private $proveedores;
    private $estadisticas;
    public function __construct() 
    {
        $this->iniciarSesion();
        $this->bdatos = new Inicio();
        $this->inventario = new Inventario();
        $this->pos = new Pos();
        $this->config = new Config();
        $this->clientes = new Usuarios();
        $this->historial = new Historial();
        $this->ccobrar = new Ccobrar();
        $this->proveedores = new Proveedores();
        $this->estadisticas = new Estadisticas();
    }
    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    private function validarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['nombre'])) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "warning",
                        title: "Acceso denegado",
                        text: "Por favor, inicie sesión para continuar.",
                        confirmButtonColor: "#e74c3c"
                    }).then(() => {
                        window.location.href = "./";
                    });
                });
            </script>';
            exit();
        }
        // Verificar si el usuario existe en la base de datos
        $usuario = $this->bdatos->obtenerNombreUsuario($_SESSION['nombre']);
        if (!$usuario) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Usuario no existe",
                        text: "El usuario actual ha sido eliminado. Por favor, inicie sesión con otro usuario.",
                        confirmButtonColor: "#e74c3c"
                    }).then(() => {
                        window.location.href = "./";
                    });
                });
            </script>';
            session_destroy();
            exit();
        }
    }
    public function home() {
        $this->validarSesion();
        $this->config = new Config();

        if(isset($_POST['uptade'])){
            $precio = trim($_POST['dollar']);
            $resultado = $this->config->updateDollar($precio);
            
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: ?action=admin');
            exit();
        }

        require_once 'views/home/admin.php';
    }

    //FUNCIONES DEL INVETARIO
    public function inventario() {
        $this->validarSesion();
        $titulo = 'Inventario';
        $datosInven = $this->inventario->obtenerDatos();

        if (isset($_POST['add'])) {
            // Sanitizar y validar datos antes de guardar
            $datos = [
                'codigo' => trim($_POST['productCode'] ?? 'N/A'),
                'nombre' => trim($_POST['productName'] ?? 'N/A'),
                'un_disponibles' => (int)(trim($_POST['productStock'] ?? 0)),
                'precio_compra' => (float)(trim($_POST['purchasePrice'] ?? 0)),
                'precio_venta' => (float)(trim($_POST['salePrice'] ?? 0)),
                'medida' => trim($_POST['productMeasure'] ?? 'N/A')
            ];

            if (empty($datos['codigo'])){
                echo '<script>alert("El código del producto no puede estar vacío")</script>';
            } 
            else if ($this->inventario->guardarDatos($datos)) {
                header('Location: ?action=admin&method=inventario&mensaje=exito');
                exit();
            } 
            else {
                echo '<script>alert("Error al guardar el producto. Intente nuevamente.")</script>';
            }
        }
        require_once 'views/inventario/index.php';
    }
    public function eliminarProducto() {
        try{
            if(!isset($_GET['id'])){
                echo '<script>alert("ID del producto no encontra a surguido un error")</script>';
            }

            $id = $_GET['id'];
            if($this->inventario->eliminarDatos($id)){
                echo '<script>alert("Producto eliminado exitosamente")</script>';
            }else{
                echo '<script>alert("Error al eliminar")</script>';
            }
        }
        catch(Exception $e){
            echo '<script>alert("Error en el servidor")</script>';
        }
        header('Location: ?action=admin&method=inventario');
        exit();
    }
    public function obtenerProducto() {
        try {
            if(!isset($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                return;
            }

            $id = $_GET['id'];
            $producto = $this->inventario->obtenerProductoPorId($id);

            if($producto) {
                echo json_encode(['success' => true, 'producto' => $producto]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            }
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
        }
    }
    public function actualizarProducto() {
        try {
            if(!isset($_POST['id_producto'])) {
                echo '<script>alert("ID del producto no encontrado")</script>';
                return;
            }

            $datos = [
                'id_producto' => $_POST['id_producto'],
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'medida' => $_POST['medida'],
                'un_disponibles' => $_POST['un_disponibles'],
                'precio_compra' => $_POST['precio_compra'],
                'precio_venta' => $_POST['precio_venta']
            ];

            if($this->inventario->actualizarProducto($datos)) {
                echo '<script>alert("Producto actualizado correctamente")</script>';
            } else {
                echo '<script>alert("Error al actualizar el producto")</script>';
            }
        } catch(Exception $e) {
            echo '<script>alert("Error en el servidor")</script>';
        }
        header('Location: ?action=admin&method=inventario');
        exit();
    }

    //FUNCIONES DEL PUNTO DE VENTA

    public function punto(){
        $this->validarSesion();
        $titulo = 'Punto de venta';
        $datos = $this->pos->obtenerDatos();
        $clientes = $this->clientes->obtenerUsuarios();
        require_once 'views/punto/index.php';
    }
    public function confirmarVenta(){
        // Importante: limpiar buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Verificar que sea AJAX
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                throw new Exception('Acceso no permitido');
            }
            
            $input = file_get_contents('php://input');
            
            if (empty($input)) {
                throw new Exception('No se recibieron datos');
            }
            
            $data = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Datos JSON inválidos: ' . json_last_error_msg());
            }
            
            // Validar campos requeridos
            $required = ['fecha', 'cliente', 'tipo_pago', 'tipo_venta', 'total_usd', 'productos'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Campo requerido faltante: $field");
                }
            }
            
            if (!is_array($data['productos']) || empty($data['productos'])) {
                throw new Exception('Debe incluir al menos un producto');
            }
            
            // Procesar venta
            $resultado = $this->pos->procesarVenta(
                $data['fecha'],
                $data['cliente'],
                $data['tipo_pago'],
                $data['tipo_venta'],
                floatval($data['total_usd']),
                $data['productos']
            );
            
            // Limpiar buffer y enviar respuesta
            ob_end_clean();
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            ob_end_clean();
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'error' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

    //Funciones de Historial de ventas
    public function historial(){
        $this->validarSesion();
        $titulo = 'Historial de venta';
        $historial = $this->historial->obtenerHistorial();
        require_once 'views/historial/index.php';
    }

    //Funciones de cuestas por cobrar o fiados
    public function cuentas(){
        $this->validarSesion();
        $titulo = 'Cuentas por cobrar';
        $cuentas = $this->ccobrar->obtenerCC();
        require_once 'views/cuentas/index.php';
    }

    public function descontarMonto() {
        // IMPORTANTE: Asegurarse de que no haya salida antes de esto
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        try {
            // Capturar datos RAW
            $rawData = file_get_contents('php://input');
            
            error_log("=== INICIO DESCONTAR MONTO ===");
            error_log("Raw Data: " . $rawData);
            
            if (empty($rawData)) {
                throw new Exception('No se recibieron datos');
            }
            
            $data = json_decode($rawData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
            }
            
            error_log("Datos decodificados: " . print_r($data, true));
            
            // Validaciones
            if (!isset($data['id_historial'])) {
                throw new Exception('Falta el ID del historial');
            }
            
            if (!isset($data['monto'])) {
                throw new Exception('Falta el monto a descontar');
            }
            
            $id_historial = intval($data['id_historial']);
            $monto = floatval($data['monto']);
            
            // Validar valores
            if ($id_historial <= 0) {
                throw new Exception('ID de historial inválido');
            }
            
            if ($monto <= 0) {
                throw new Exception('El monto debe ser mayor a 0');
            }
            
            // Redondear a 2 decimales
            $monto = round($monto, 2);
            
            error_log("ID: $id_historial, Monto: $monto");
            
            // Verificar que el modelo esté instanciado
            if (!isset($this->ccobrar)) {
                throw new Exception('Modelo Ccobrar no está instanciado');
            }
            
            // Verificar que la cuenta existe
            $cuenta = $this->ccobrar->obtenerCuentaPorId($id_historial);
            
            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }
            
            error_log("Cuenta encontrada: " . print_r($cuenta, true));
            
            $total_actual = floatval($cuenta['total_usd']);
            
            // Validar que el monto no sea mayor al disponible (con margen de 0.01)
            if ($monto > ($total_actual + 0.01)) {
                throw new Exception('El monto ($' . number_format($monto, 2) . ') es mayor al saldo disponible ($' . number_format($total_actual, 2) . ')');
            }
            
            // Ejecutar descuento
            $resultado = $this->ccobrar->descontarMonto($id_historial, $monto) && $this->ccobrar->deletePagadas();
            
            if (!$resultado) {
                throw new Exception('Error al procesar el descuento en la base de datos');
            }
            
            $nuevo_total = round($total_actual - $monto, 2);
            if ($nuevo_total < 0) $nuevo_total = 0;
            
            error_log("✓ Descuento exitoso. Nuevo total: $" . $nuevo_total);
            
            // Respuesta exitosa
            echo json_encode([
                'success' => true,
                'message' => $nuevo_total <= 0.01 
                    ? 'Cuenta saldada completamente' 
                    : 'Pago de $' . number_format($monto, 2) . ' registrado. Saldo restante: $' . number_format($nuevo_total, 2),
                'nuevo_total' => $nuevo_total,
                'monto_pagado' => $monto
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("✗ ERROR descontarMonto: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

    // Función de Clientes
    public function users(){
        $this->validarSesion();
        $titulo = 'Clientes';
        $Clientes = $this->clientes->obtenerUsuarios();

        if (isset($_POST['btn-add'])) {
            $nombre = trim($_POST['name']);
            $cedula = trim($_POST['cedula']);
            $telefono = trim($_POST['cel']);

            // Validar que los campos no estén vacíos
            if (empty($nombre) || empty($cedula)) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Campos Requeridos",
                        text: "El nombre y la cédula son obligatorios",
                        confirmButtonColor: "#e74c3c"
                    });
                </script>';
            } else {
                if ($this->clientes->agregarUsuario($nombre, $cedula, $telefono)) {
                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "Cliente agregado correctamente",
                            confirmButtonColor: "#3498db",
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = "?action=admin&method=users";
                        });
                    </script>';
                } else {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Error al agregar el cliente. Intente nuevamente.",
                            confirmButtonColor: "#e74c3c"
                        });
                    </script>';
                }
            }
        }
        require_once 'views/usuarios/index.php';
    }

    public function DeleteCliente(){
        try {
            // Verificar que el ID exista en la petición
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "ID del cliente no encontrado",
                        confirmButtonColor: "#e74c3c"
                    }).then(() => {
                        window.location.href = "?action=admin&method=users";
                    });
                </script>';
                exit();
            }

            $id = intval($_GET['id']); // Convertir a entero para seguridad

            // Intentar eliminar el cliente
            if ($this->clientes->deleteCliente($id)) {
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Eliminado!",
                        text: "Cliente eliminado exitosamente",
                        confirmButtonColor: "#3498db",
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = "?action=admin&method=users";
                    });
                </script>';
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo eliminar el cliente. Verifique que exista.",
                        confirmButtonColor: "#e74c3c"
                    }).then(() => {
                        window.location.href = "?action=admin&method=users";
                    });
                </script>';
            }
        } catch (Exception $e) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error del Servidor",
                    text: "Ocurrió un error inesperado: ' . $e->getMessage() . '",
                    confirmButtonColor: "#e74c3c"
                }).then(() => {
                    window.location.href = "?action=admin&method=users";
                });
            </script>';
        }
        
        // No usar header() después de echo
        require_once 'views/usuarios/index.php';
        exit();
    }

    //Funciones de estadisticas
    public function estadisticas(){
        $this->validarSesion();
        $titulo = 'Estadísticas';
        
        // Obtener todos los datos
        $estadisticas = $this->estadisticas->obtenerEstadisticasGenerales();
        $ventasMensuales = $this->estadisticas->obtenerVentasMensuales();
        $distribucionProductos = $this->estadisticas->obtenerDistribucionProductos();
        $actividadReciente = $this->estadisticas->obtenerActividadReciente();
        $topProductos = $this->estadisticas->obtenerTopProductos();
        $clientesActivos = $this->estadisticas->obtenerClientesActivos();
        
        require_once 'views/estadisticas/index.php';
    }

    public function actualizarDatos() {
        header('Content-Type: application/json');
        
        $estadisticasModel = new Estadisticas();
        
        echo json_encode([
            'estadisticas' => $estadisticasModel->obtenerEstadisticasGenerales(),
            'ventasMensuales' => $estadisticasModel->obtenerVentasMensuales(),
            'actividadReciente' => $estadisticasModel->obtenerActividadReciente(5)
        ]);
    }

    //Funcion de configuracion
    public function config(){
        $this->validarSesion();
        $titulo = 'Configuracion';
        $usuarios = $this->config->mostrarUsuarios();
        
        // Cambiar clave maestra
        if(isset($_POST['cambiar_clave'])){
            $actual = trim($_POST['clave_actual']);
            $nueva = trim($_POST['clave_nueva']);
            $confirmar = trim($_POST['confirmar_clave']);

            // Validar que las claves coincidan
            if($nueva !== $confirmar){
                $_SESSION['mensaje'] = 'Las claves no coinciden';
                $_SESSION['tipo_mensaje'] = 'error';
                header('Location: ?action=admin&method=config');
                exit();
            }

            // Verificar la clave actual con la constante APP_Password
            if($actual !== APP_Password){
                $_SESSION['mensaje'] = 'La clave actual es incorrecta';
                $_SESSION['tipo_mensaje'] = 'error';
                header('Location: ?action=admin&method=config');
                exit();
            }

            // Actualizar la clave
            $resultado = $this->config->updateClave($nueva);
            
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: ?action=admin&method=config');
            exit();
        }

        // Agregar nuevo usuario administrativo
        if(isset($_POST['agregar_usuario'])){
            $nombre = trim($_POST['nombre_usuario']);
            $cedula = trim($_POST['cedula']);
            $clave = trim($_POST['clave_usuario']);
            $id_cargo = (int)$_POST['id_cargo'];

            $resultado = $this->config->addUsuario($cedula, $nombre, $clave, $id_cargo);
            
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: ?action=admin&method=config');
            exit();
        }

        // Eliminar usuario
        if(isset($_POST['eliminar_usuario'])){
            $id_usuario = (int)$_POST['id_usuario'];
            
            $resultado = $this->config->deleteUsuario($id_usuario);
            
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: ?action=admin&method=config');
            exit();
        }
        
        require_once 'views/conf/index.php';
    }

    public function cambiarNombreApp() {
        $this->iniciarSesion();
        if(isset($_POST['nombre_app'])) {
            $nombre = trim($_POST['nombre_app']);
            
            // Validación básica: no vacío y longitud razonable
            if(empty($nombre) || strlen($nombre) > 100){
                $_SESSION['mensaje'] = 'El nombre de la aplicación no puede estar vacío ni exceder 100 caracteres.';
                $_SESSION['tipo_mensaje'] = 'error';
                header('Location: ?action=admin&method=config');
                exit();
            }
            
            $resultado = $this->config->updateNombre($nombre);

            if(isset($resultado['success']) && $resultado['success']) {
                $_SESSION['mensaje'] = $resultado['message'];
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = isset($resultado['message']) ? $resultado['message'] : 'Error al actualizar el nombre.';
                $_SESSION['tipo_mensaje'] = 'error';
            }
            header('Location: ?action=admin&method=config');
            exit();
            
        } else {
            $_SESSION['mensaje'] = 'No se recibieron datos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?action=admin&method=config');
            exit();
        }
    }

    //Funciones de Proveedores
    public function proveedores(){
        $this->iniciarSesion();
        $titulo = 'Proveedores';
        $proveedores = $this->proveedores->obtenerProveedores();
        require_once 'views/provedor/index.php';
    }
    public function eliminarProveedor() {
        // Detener cualquier output previo
        ob_clean();
        
        try {
            header('Content-Type: application/json; charset=utf-8');
            
            // Obtener el ID desde POST o GET
            $id = null;
            if (!empty($_POST['id'])) {
                $id = intval($_POST['id']);
            } elseif (!empty($_GET['id'])) {
                $id = intval($_GET['id']);
            }
            
            if (!$id) {
                throw new Exception('ID del proveedor es requerido');
            }
            
            // Verificar que el proveedor existe
            $proveedorExiste = $this->proveedores->obtenerProveedorPorId($id);
            if (!$proveedorExiste) {
                throw new Exception('El proveedor no existe');
            }
            
            // Eliminar el proveedor
            $resultado = $this->proveedores->eliminarProveedor($id);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor eliminado correctamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception("Error al eliminar el proveedor de la base de datos");
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit; // MUY IMPORTANTE: detener la ejecución aquí
    }

    public function addProveedor() {
        try {
            // Forzar respuesta en JSON
            header('Content-Type: application/json; charset=utf-8');

            // Verificar que la solicitud sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }

            // Validar campos requeridos (usando el nombre del formulario 'nombre')
            $requiredFields = ['nombre', 'email', 'telefono', 'nombre_encargado', 'estado', 'rif'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }

            // Obtener y sanitizar los datos
            $data = [
                'nombre_proveedor' => trim($_POST['nombre']), // ✅ Del form 'nombre' a DB 'nombre_proveedor'
                'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
                'telefono' => trim($_POST['telefono']),
                'nombre_encargado' => trim($_POST['nombre_encargado']),
                'estado' => trim($_POST['estado']),
                'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
                'nota' => isset($_POST['nota']) ? trim($_POST['nota']) : '',
                'rif' => trim($_POST['rif'])
            ];

            // Validar formato de email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del email no es válido");
            }

            // Insertar en la base de datos
            $resultado = $this->proveedores->agregarProveedor($data);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor agregado correctamente'
                ]);
            } else {
                throw new Exception("Error al insertar en la base de datos");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit; // ✅ Importante: detener la ejecución
    }

    public function updateProveedor() {
        try {
            header('Content-Type: application/json; charset=utf-8');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (empty($_POST['id'])) {
                throw new Exception('ID del proveedor es requerido');
            }
            
            $requiredFields = ['nombre', 'email', 'telefono', 'nombre_encargado', 'estado'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }
            
            $id = intval($_POST['id']);
            $data = [
                'nombre_proveedor' => trim($_POST['nombre']), 
                'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
                'telefono' => trim($_POST['telefono']),
                'nombre_encargado' => trim($_POST['nombre_encargado']),
                'estado' => trim($_POST['estado']),
                'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
                'nota' => isset($_POST['nota']) ? trim($_POST['nota']) : '',
                'rif' => trim($_POST['rif'])
            ];
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del email no es válido");
            }
            
            $resultado = $this->proveedores->actualizarProveedor($id, $data);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor actualizado correctamente'
                ]);
            } else {
                throw new Exception("Error al actualizar en la base de datos");
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit; 
    }
}