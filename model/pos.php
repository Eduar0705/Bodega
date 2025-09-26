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
        $this->db->begin_transaction();

        try {
            // Insertar la venta
            $sql_venta = "INSERT INTO historial (fecha, cliente, tipo_pago, tipo_venta, total_usd, productos_vendidos) 
                        VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql_venta);
            
            // Convertir productos a JSON
            $productos_json = json_encode($productos);
            $stmt->bind_param('ssssds', $fecha, $cliente, $tipo_pago, $tipo_venta, $total_usd, $productos_json);

            if (!$stmt->execute()) {
                throw new Exception('Error al registrar la venta: ' . $this->db->error);
            }

            $venta_id = $this->db->insert_id;

            // Si es a crédito, registrar en cuentas por cobrar
            if ($tipo_pago === 'credito') {
                $sql_credito = "INSERT INTO cuentascobrar (fecha, cliente, tipo_pago, tipo_venta, total_usd, productos_vendidos) 
                                VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_credito = $this->db->prepare($sql_credito);
                $stmt_credito->bind_param('ssssds', $fecha, $cliente, $tipo_pago, $tipo_venta, $total_usd, $productos_json);

                if (!$stmt_credito->execute()) {
                    throw new Exception('Error al registrar la cuenta por cobrar: ' . $this->db->error);
                }
            }

            // Actualizar inventario
            foreach ($productos as $producto) {
                $sql_stock = "UPDATE inventario SET un_disponibles = un_disponibles - ? WHERE id = ?";
                $stmt_stock = $this->db->prepare($sql_stock);
                $stmt_stock->bind_param('ii', $producto['cantidad'], $producto['id']);

                if (!$stmt_stock->execute()) {
                    throw new Exception('Error al actualizar el stock: ' . $this->db->error);
                }
            }

            $this->db->commit();
            return ['success' => true, 'venta_id' => $venta_id];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>
