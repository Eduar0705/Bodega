<?php
session_start();
class BaseDatos {
    public function conectar() {
        $host = "localhost";
        $root = "root";
        $pass = "";
        $bd = "bodega22";
        $conexion = mysqli_connect($host, $root, $pass, $bd);
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        return $conexion;
    }
}
?>