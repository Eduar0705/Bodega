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
    public function procesarVenta($productos, $subtotal_dolares, $subtotal_bolivares, $cliente_nombre = null, $metodo_pago = 'contado') {
        $this->db->begin_transaction();

        try {
            // Insertar en historial de ventas
            $productos_json = json_encode($productos);
            $fecha = date('Y-m-d H:i:s');

            $sql_venta = "INSERT INTO historial (productos, subtotal_dolares, subtotal_bolivares, cliente_nombre, metodo_pago, fecha) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql_venta);
            $stmt->bind_param('sddsss', $productos_json, $subtotal_dolares, $subtotal_bolivares, $cliente_nombre, $metodo_pago, $fecha);

            if (!$stmt->execute()) {
                throw new Exception('Error al registrar la venta');
            }

            $venta_id = $this->db->insert_id;

            // Si es a crédito, registrar en cuentas por cobrar
            if ($metodo_pago === 'credito' && !empty($cliente_nombre)) {
                $sql_credito = "INSERT INTO cuentas_por_cobrar (venta_id, cliente_nombre, monto_dolares, monto_bolivares, fecha, estado) 
                                VALUES (?, ?, ?, ?, ?, 'pendiente')";
                $stmt_credito = $this->db->prepare($sql_credito);
                $stmt_credito->bind_param('isdds', $venta_id, $cliente_nombre, $subtotal_dolares, $subtotal_bolivares, $fecha);

                if (!$stmt_credito->execute()) {
                    throw new Exception('Error al registrar la cuenta por cobrar');
                }
            }

            // Validar stock y actualizar inventario
            foreach ($productos as $producto) {
                // Verificar stock actual
                $sql_check = "SELECT un_disponibles FROM inventario WHERE id = ?";
                $stmt_check = $this->db->prepare($sql_check);
                $stmt_check->bind_param('i', $producto['id']);
                $stmt_check->execute();
                $resultado_check = $stmt_check->get_result();

                if ($resultado_check->num_rows === 0) {
                    throw new Exception('Producto no encontrado: ' . $producto['nombre']);
                }

                $stock_actual = $resultado_check->fetch_assoc()['un_disponibles'];
                if ($stock_actual < $producto['cantidad']) {
                    throw new Exception('Stock insuficiente para: ' . $producto['nombre']);
                }

                // Actualizar stock
                $sql_stock = "UPDATE inventario SET un_disponibles = un_disponibles - ? WHERE id = ?";
                $stmt_stock = $this->db->prepare($sql_stock);
                $stmt_stock->bind_param('ii', $producto['cantidad'], $producto['id']);

                if (!$stmt_stock->execute()) {
                    throw new Exception('Error al actualizar el stock de: ' . $producto['nombre']);
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
