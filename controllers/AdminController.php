<?php
require_once 'model/Conexion.php';
require_once 'model/Inicio.php';
require_once 'model/inventario.php';
require_once 'model/pos.php';
require_once 'model/config.php';
require_once 'model/usuarios.php';
require_once 'model/historial.php';
require_once 'model/ccobrar.php';

class AdminController 
{
    private $modeloDB;
    private $bdatos;
    private $inventario;
    private $pos;
    private $config;
    private $clientes;
    private $historial;
    private $ccobrar;
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

    }
    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    private function validarSesion()
    {
        if (!isset($_SESSION['nombre'])) {
            header("Location: ?action=login");
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

    public function historial(){
        $titulo = 'Historial de venta';
        $historial = $this->historial->obtenerHistorial();
        require_once 'views/historial/index.php';
    }
    public function cuentas(){
        $titulo = 'Cuentas por cobrar';
        $cuentas = $this->ccobrar->obtenerCC();
        require_once 'views/cuentas/index.php';
    }

    public function descontarMonto() {
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }
        
        try {
            // Capturar datos
            $rawData = file_get_contents('php://input');
            $data = json_decode($rawData, true);
            
            // Log básico
            error_log("Datos recibidos: " . print_r($data, true));
            
            // Validaciones básicas
            if (!isset($data['id_historial']) || !isset($data['monto'])) {
                throw new Exception('Datos incompletos');
            }
            
            $id_historial = intval($data['id_historial']);
            $monto = floatval($data['monto']);
            
            if ($id_historial <= 0 || $monto <= 0) {
                throw new Exception('Valores inválidos');
            }
            
            // Verificar cuenta
            $cuenta = $this->ccobrar->obtenerCuentaPorId($id_historial);
            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }
            
            if ($monto > floatval($cuenta['total_usd'])) {
                throw new Exception('Monto mayor al disponible');
            }
            
            // Ejecutar descuento
            $resultado = $this->ccobrar->descontarMonto($id_historial, $monto);
            
            if (!$resultado) {
                throw new Exception('Error en la base de datos');
            }
            
            $nuevo_total = floatval($cuenta['total_usd']) - $monto;
            
            echo json_encode([
                'success' => true,
                'message' => $nuevo_total <= 0 ? 'Cuenta saldada completamente' : 'Pago parcial registrado correctamente',
                'nuevo_total' => $nuevo_total
            ]);
            
        } catch (Exception $e) {
            error_log("ERROR descontarMonto: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    //Funcion de Usuarios o Clientes
    public function users(){
        $titulo = 'Usuarios';
        $Clientes =  $this->clientes->obtenerUsuarios();

        if (isset($_POST['btn-add'])) {
            $nombre = trim($_POST['name']);
            $cedula = trim($_POST['cedula']);
            $telefono = trim($_POST['cel']);

            if ($this->clientes->agregarUsuario($nombre, $cedula, $telefono)) {
                header('Location: ?action=admin&method=users&mensaje=exito');
                exit();
            } else {
                echo '<script>alert("Error al agregar el cliente. Intente nuevamente.")</script>';
            }
        }
        require_once 'views/usuarios/index.php';
    }

    public function EliminarUsuario(){
        try{
            if(!isset($_GET['id'])){
                echo '<script>alert("ID del cliente no encontra a surguido un error")</script>';
            }

            $id = $_GET['id'];
            if($this->clientes->eliminarUsuario($id)){
                echo '<script>alert("Cliente eliminado exitosamente")</script>';
            }else{
                echo '<script>alert("Error al eliminar")</script>';
            }
        }
        catch(Exception $e){
            echo '<script>alert("Error en el servidor")</script>';
        }
        header('Location: ?action=admin&method=users');
        exit();
    }


    //Funcion de configuracion
    public function config(){
        $titulo = 'Configuracion';
        $usuarios = $this->config->mostrarUsuarios();
        
        // Actualizar nombre de la aplicación
        if(isset($_POST['uptade'])){
            $nombre = trim($_POST['nombre']);
            $resultado = $this->config->updateNombre($nombre);
            
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: ?action=admin&method=config');
            exit();
        }
        
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
}