<?php
class Usuarios {
    private $db;

    public function __construct(){
        $this->db = (new BaseDatos())->conectar();
    }

    public function obtenerUsuarios(){
        try {
            $sql = 'SELECT * FROM clientes ORDER BY id_cliente DESC';
            $resul = $this->db->query($sql);

            if (!$resul) {
                error_log("Error en consulta SQL: " . $this->db->error);
                return [];
            }

            $datos = [];
            while ($rows = $resul->fetch_assoc()) {
                $datos[] = $rows;
            }
            return $datos;
        } catch (Exception $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    public function agregarUsuario($nombre, $cedula, $telefono){
        try {
            // Verificar si la cédula ya existe
            $sqlCheck = "SELECT id_cliente FROM clientes WHERE cedula = ?";
            $stmtCheck = $this->db->prepare($sqlCheck);
            
            if ($stmtCheck) {
                $stmtCheck->bind_param("s", $cedula);
                $stmtCheck->execute();
                $result = $stmtCheck->get_result();
                
                if ($result->num_rows > 0) {
                    error_log("La cédula ya existe en la base de datos");
                    return false;
                }
            }

            // Insertar nuevo cliente
            $sql = "INSERT INTO clientes (nombre_apellido, cedula, telefono) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sss", $nombre, $cedula, $telefono);
                $resultado = $stmt->execute();
                
                if (!$resultado) {
                    error_log("Error al ejecutar insert: " . $stmt->error);
                }
                
                return $resultado;
            } else {
                error_log("Error al preparar statement: " . $this->db->error);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al agregar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCliente($id){
        try {
            // CORRECCIÓN: El campo en la base de datos se llama id_cliente, no id
            $sql = "DELETE FROM clientes WHERE id_cliente = ?";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $resultado = $stmt->execute();
                
                if (!$resultado) {
                    error_log("Error al eliminar cliente: " . $stmt->error);
                    return false;
                }
                
                // Verificar si realmente se eliminó algo
                if ($stmt->affected_rows > 0) {
                    return true;
                } else {
                    error_log("No se encontró cliente con ID: " . $id);
                    return false;
                }
            } else {
                error_log("Error al preparar statement delete: " . $this->db->error);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerClientePorId($id){
        try {
            $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    return $result->fetch_assoc();
                }
            }
            return null;
        } catch (Exception $e) {
            error_log("Error al obtener cliente por ID: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarCliente($id, $nombre, $cedula, $telefono){
        try {
            $sql = "UPDATE clientes SET nombre_apellido = ?, cedula = ?, telefono = ? WHERE id_cliente = ?";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sssi", $nombre, $cedula, $telefono, $id);
                return $stmt->execute();
            }
            return false;
        } catch (Exception $e) {
            error_log("Error al actualizar cliente: " . $e->getMessage());
            return false;
        }
    }
}
?>