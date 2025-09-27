<?php
class Pos {
    private $db;

    public function __construct() {
        $this->db = (new BaseDatos())->conectar();
    }

    // Obtener todos los productos del inventario con stock disponible
    public function obtenerDatos() {
        $sql = 'SELECT * FROM inventario WHERE un_disponibles > 0';
        $resultado = $this->db->query($sql);

        if (!$resultado) {
            return [];
        }

        $datos = [];
        while ($row = $resultado->fetch_assoc()) {
            $datos[] = $row;
        }
        return $datos;
    }

    // Buscar productos por nombre o código
    public function buscarProductos($termino) {
        $termino = $this->db->real_escape_string($termino);
        $sql = "SELECT * FROM inventario WHERE (nombre LIKE '%$termino%' OR codigo LIKE '%$termino%') AND un_disponibles > 0 LIMIT 10";
        $resultado = $this->db->query($sql);

        if (!$resultado) {
            return [];
        }

        $datos = [];
        while ($row = $resultado->fetch_assoc()) {
            $datos[] = $row;
        }
        return $datos;
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM inventario WHERE id = $id AND un_disponibles > 0";
        $resultado = $this->db->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

    // Procesar venta
    public function procesarVenta($fecha, $cliente, $tipo_pago, $tipo_venta, $total_usd, $productos) {
        try {
            $this->db->begin_transaction();
            
            // 1. Validar stock
            foreach ($productos as $prod) {
                if (!isset($prod['id']) || !isset($prod['cantidad'])) {
                    throw new Exception('Datos de producto incompletos');
                }
                
                $sqlCheck = "SELECT un_disponibles, nombre FROM inventario WHERE id_producto = ?";
                $stmtCheck = $this->db->prepare($sqlCheck);
                $stmtCheck->bind_param('i', $prod['id']);
                $stmtCheck->execute();
                $result = $stmtCheck->get_result();
                
                if ($result->num_rows === 0) {
                    throw new Exception("Producto ID {$prod['id']} no existe");
                }
                
                $row = $result->fetch_assoc();
                if ($row['un_disponibles'] < $prod['cantidad']) {
                    throw new Exception("Stock insuficiente para: {$row['nombre']}");
                }
            }
            
            // 2. Convertir productos a JSON
            $productos_json = json_encode($productos, JSON_UNESCAPED_UNICODE);
            
            // 3. Insertar en historial
            $sqlHistorial = "INSERT INTO historial (fecha, cliente, tipo_pago, tipo_venta, total_usd, productos_vendidos) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtHistorial = $this->db->prepare($sqlHistorial);
            
            if (!$stmtHistorial) {
                throw new Exception("Error preparando consulta historial: " . $this->db->error);
            }
            
            $stmtHistorial->bind_param('ssssds', $fecha, $cliente, $tipo_pago, $tipo_venta, $total_usd, $productos_json);
            
            if (!$stmtHistorial->execute()) {
                throw new Exception("Error al registrar venta: " . $stmtHistorial->error);
            }
            
            $venta_id = $this->db->insert_id;
            
            // 4. Si es crédito, insertar en cuentascobrar
            if ($tipo_pago === 'credito') {
                $sqlCredito = "INSERT INTO cuentascobrar (fecha, cliente, tipo_pago, tipo_venta, total_usd, productos_vendidos) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtCredito = $this->db->prepare($sqlCredito);
                
                if (!$stmtCredito) {
                    throw new Exception("Error preparando consulta crédito: " . $this->db->error);
                }
                
                $stmtCredito->bind_param('ssssds', $fecha, $cliente, $tipo_pago, $tipo_venta, $total_usd, $productos_json);
                
                if (!$stmtCredito->execute()) {
                    throw new Exception("Error al registrar crédito: " . $stmtCredito->error);
                }
            }
            
            // 5. Actualizar inventario
            $sqlUpdate = "UPDATE inventario SET un_disponibles = un_disponibles - ? WHERE id_producto = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            
            if (!$stmtUpdate) {
                throw new Exception("Error preparando actualización inventario: " . $this->db->error);
            }
            
            foreach ($productos as $prod) {
                $stmtUpdate->bind_param('ii', $prod['cantidad'], $prod['id']);
                
                if (!$stmtUpdate->execute()) {
                    throw new Exception("Error actualizando inventario: " . $stmtUpdate->error);
                }
                
                if ($stmtUpdate->affected_rows === 0) {
                    throw new Exception("Producto ID {$prod['id']} no se actualizó");
                }
            }
            
            // 6. Confirmar transacción
            $this->db->commit();
            
            return [
                'success' => true, 
                'venta_id' => $venta_id,
                'message' => 'Venta procesada correctamente'
            ];
            
        } catch (Exception $e) {
            $this->db->rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
}
?>
