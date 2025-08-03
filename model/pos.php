<?php
class Pos{
    private $db;

    public function __construct(){
        $this->db = (new BaseDatos())->conectar();
    }

    public function obtenerDatos(){
        $sql = 'SELECT * FROM inventario';
        $resultado = $this->db->query($sql);

        if(!$resultado) {
            return [];
        }

        $datos = [];
        while($row = $resultado->fetch_assoc()) {
            $datos[] = $row;
        }
        return $datos;
    }
    
}
?>