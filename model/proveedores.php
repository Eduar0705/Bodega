<?php
class Proveedores{
    private $db;

    public function __construct() {
        $this->db = (new BaseDatos())->conectar();
    }

    public function obtenerProveedores() {
        $sql = 'SELECT * FROM proveedores';
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Error preparando consulta: " . $this->db->error);
            return [];
        }
        
        if (!$stmt->execute()) {
            error_log("Error ejecutando consulta: " . $stmt->error);
            return [];
        }
        
        $resultado = $stmt->get_result();
        $datos = [];
        
        while ($row = $resultado->fetch_assoc()) {
            $datos[] = $row;
        }
        
        $stmt->close();
        return $datos;
    }

    public function agregarProveedor($data) {
        $query = "INSERT INTO proveedores (nombre_proveedor, email, telefono, direccion, nombre_encargado, estado, nota) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            error_log("Error preparando consulta: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("sssssss", 
            $data['nombre_proveedor'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $data['nombre_encargado'],
            $data['estado'],
            $data['nota']
        );
        
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            error_log("Error ejecutando inserción: " . $stmt->error);
        }
        
        $stmt->close();
        
        return $resultado;
    }

    public function actualizarProveedor($id, $data) {
        $query = "UPDATE proveedores SET nombre_proveedor = ?, email = ?, telefono = ?, direccion = ?, nombre_encargado = ?, estado = ?, nota = ? WHERE id_proveedor = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            error_log("Error preparando consulta: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("sssssssi", 
            $data['nombre_proveedor'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $data['nombre_encargado'],
            $data['estado'],
            $data['nota'],
            $id
        );
        
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            error_log("Error ejecutando actualización: " . $stmt->error);
        }
        
        $stmt->close();
        
        return $resultado;
    }

    public function eliminarProveedor($id) {
        $query = "DELETE FROM proveedores WHERE id_proveedor = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            error_log("Error preparando consulta: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        
        return $resultado;
    }

    public function obtenerProveedorPorId($id) {
        $query = "SELECT * FROM proveedores WHERE id_proveedor = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            error_log("Error preparando consulta: " . $this->db->error);
            return null;
        }
        
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            error_log("Error ejecutando consulta: " . $stmt->error);
            return null;
        }
        
        $resultado = $stmt->get_result();
        $proveedor = $resultado->fetch_assoc();
        $stmt->close();
        
        return $proveedor;
    }
}
?>