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
    public function __construct() 
    {
        $this->iniciarSesion();
        $this->bdatos = new Inicio();
        $this->pos = new Pos();
        $this->clientes = new Usuarios();
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
}