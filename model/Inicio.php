<?php
class Inicio 
{
    private $db; 
    private $modeloDB;
    
    public function __construct() 
    {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
        $this->iniciarSesion();
    }

    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    //Autenticación de usuario
    public function loginAuthenticate($usuario, $password)
    {
        try {
            // Validar parámetros
            if (empty($usuario) || empty($password)) {
                echo "<script>alert('Usuario y contraseña son obligatorios');</script>";
                return false;
            }

            // Consulta preparada para prevenir inyección SQL
            $consulta = "SELECT * FROM inf_usuarios WHERE cedula = ? AND clave = ?";
            $stmt = $this->db->prepare($consulta);
            
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . ($this->db->error));
            }

            $stmt->bind_param("ss", $usuario, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($fila = $result->fetch_array()) {
                $this->establecerSesionUsuario($fila);
                $this->redirigirSegunRol($fila['id_cargo']);
            } else {
                header("Location: ?action=inicio&method=login&error=1");
                exit();
            }

            $stmt->close();
            
        } catch (Exception $e) {
            error_log("Error en loginAuthenticate: " . $e->getMessage());
            echo "<script>alert('Ocurrió un error al iniciar sesión. Por favor, inténtelo de nuevo más tarde.');</script>";
        }
    }

    private function establecerSesionUsuario($datosUsuario)
    {
        $_SESSION['nombre'] = $datosUsuario['nombre'];
    }

    public function obtenerNombreUsuario($nombre)
    {
        $consulta = "SELECT nombre FROM inf_usuarios WHERE nombre = ?";
        $stmt = $this->db->prepare($consulta);

        if (!$stmt) {
            error_log("Error preparando consulta: " . ($this->db->error));
            return null;
        }

        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        return $usuario['nombre'] ?? null;
    }
    private function redirigirSegunRol($idCargo)
    {
        switch ($idCargo) {
            case 1:
                header("Location: ?action=admin");
                break;
            default:
                echo "<script>alert('Rol de usuario no válido');</script>";
                return;
        }
        exit();
    }
}