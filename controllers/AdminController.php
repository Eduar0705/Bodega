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

class AdminController 
{
    protected $bdatos;
    protected $pos;
    protected $clientes;
    private $proveedores;
    public function __construct() 
    {
        $this->iniciarSesion();
        $this->bdatos = new Inicio();
        $this->pos = new Pos();
        $this->clientes = new Usuarios();
        $this->proveedores = new Proveedores();
    }
    protected function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    protected function validarSesion()
    {
        $this->iniciarSesion();
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

        if(isset($_POST['uptade'])){
            $precio = trim($_POST['dollar']);
            $resultado = (new Config())->updateDollar($precio);
            
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: ?action=admin');
            exit();
        }

        require_once 'views/home/admin.php';
    }
    //Funciones de estadisticas
    public function estadisticas(){
        $this->validarSesion();
        $titulo = 'Estadisticas';
        require_once 'views/estadisticas/index.php';
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
            $requiredFields = ['nombre', 'email', 'telefono', 'nombre_encargado', 'estado'];
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
                'nota' => isset($_POST['nota']) ? trim($_POST['nota']) : ''
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
                'nombre_proveedor' => trim($_POST['nombre']), // ✅ Del form 'nombre' a DB 'nombre_proveedor'
                'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
                'telefono' => trim($_POST['telefono']),
                'nombre_encargado' => trim($_POST['nombre_encargado']),
                'estado' => trim($_POST['estado']),
                'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
                'nota' => isset($_POST['nota']) ? trim($_POST['nota']) : ''
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
        exit; // ✅ Importante: detener la ejecución
    }
}