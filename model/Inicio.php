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
            $stmt = mysqli_prepare($this->db, $consulta);
            
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . mysqli_error($this->db));
            }

            mysqli_stmt_bind_param($stmt, "ss", $usuario, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($fila = mysqli_fetch_array($result)) {
                $this->establecerSesionUsuario($fila);
                $this->redirigirSegunRol($fila['id_cargo']);
            } else {
                header("Location: ?action=inicio&method=login&error=1");
                exit();
            }

            mysqli_stmt_close($stmt);
            
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
        $stmt = mysqli_prepare($this->db, $consulta);

        if (!$stmt) {
            error_log("Error preparando consulta: " . mysqli_error($this->db));
            return null;
        }

        mysqli_stmt_bind_param($stmt, "s", $nombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

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