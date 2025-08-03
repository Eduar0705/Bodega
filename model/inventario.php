<?php
class Inventario {
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

    public function guardarDatos($datos) {
        $conn = (new BaseDatos())->conectar();
        $stmt = $conn->prepare("INSERT INTO inventario (codigo, nombre, un_disponibles, precio_compra, precio_venta, medida) VALUES (?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            $conn->close();
            die('Error en la preparación de la consulta SQL');
        }

        // Asegurar tipos correctos antes de bindear
        $codigo = $datos['codigo'];
        $nombre = $datos['nombre'];
        $un_disponibles = (int)$datos['un_disponibles'];
        $precio_compra = (float)$datos['precio_compra'];
        $precio_venta = (float)$datos['precio_venta']; 
        $medida = $datos['medida'];

        $stmt->bind_param('ssidds', $codigo, $nombre, $un_disponibles, $precio_compra, $precio_venta, $medida);
        $result = $stmt->execute();
        if (!$result) {
            error_log("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
        $conn->close();
        
        return $result;
    }
        public function eliminarDatos($id) {
            $conexion = (new BaseDatos())->conectar();
            $stmt = $conexion->prepare("DELETE FROM inventario WHERE id_producto = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conexion->error);
            }
            try {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    return true;
                } else {
                    throw new Exception("No se encontró el producto con ID $id");
                }
            } finally {
                $stmt->close();
            }
        }
    public function obtenerProductoPorId($id) {
        $conexion = (new BaseDatos())->conectar();
        $stmt = $conexion->prepare("SELECT * FROM inventario WHERE id_producto = ?");
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public function actualizarProducto($datos) {
        $conexion = (new BaseDatos())->conectar();
        $stmt = $conexion->prepare("UPDATE inventario SET 
            codigo = ?, 
            nombre = ?, 
            medida = ?, 
            un_disponibles = ?, 
            precio_compra = ?, 
            precio_venta = ? 
            WHERE id_producto = ?");
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("sssiddi", 
            $datos['codigo'],
            $datos['nombre'],
            $datos['medida'],
            $datos['un_disponibles'],
            $datos['precio_compra'],
            $datos['precio_venta'],
            $datos['id_producto']
        );
        
        return $stmt->execute();
    }
}
?>