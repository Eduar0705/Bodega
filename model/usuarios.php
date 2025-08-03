<?php
class Usuarios{
    private $db;

    public function __construct(){
        $this->db = (new BaseDatos())->conectar();
    }

    public function obtenerUsuarios(){
        $sql = 'SELECT * FROM clientes';
        $resul = $this->db->query($sql);

        if(!$resul){
            return [];
        }
        $datos = [];
        while($rows = $resul->fetch_assoc()){
            $datos[] = $rows;
        }
        return $datos;
    }

    public function agregarUsuario($nombre, $cedula, $telefono){
        $sql = "INSERT INTO clientes (nombre_apellido, cedula, telefono) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $cedula, $telefono);
            return $stmt->execute();
        }
        return false;
    }

    public function eliminarUsuario($id){
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }
}
?>