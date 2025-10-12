<?php
session_start();
class BaseDatos {
    public function conectar() {
        $host = "localhost";
        $root = "root";
        $pass = "";
        $bd = "bodega";
        
        // Conectar especificando la base de datos
        $conexion = @mysqli_connect($host, $root, $pass, $bd);
        
        // Si falla la conexión (BD no existe o error), conectar sin BD
        if (!$conexion) {
            $conexion = @mysqli_connect($host, $root, $pass);
            
            // Si aún así falla, retornar null o false
            if (!$conexion) {
                return null;
            }
        }
        
        return $conexion;
    }
}
?>