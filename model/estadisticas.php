<?php
class Estadisticas {
    private $db;
    
    public function __construct() {
        $this->db = (new BaseDatos())->conectar();
    }

    public function obtenerEstadisticasGenerales() {
        // Ingresos del mes actual desde historial
        $query = "SELECT COALESCE(SUM(total_usd), 0) as ingresos_mes 
                    FROM historial 
                    WHERE MONTH(fecha) = MONTH(CURDATE()) 
                    AND YEAR(fecha) = YEAR(CURDATE())";
        $result = $this->db->query($query);
        $ingresos = $result->fetch_assoc();
        $result->free();

        // Total de ventas completadas este mes
        $query = "SELECT COUNT(*) as total_ventas 
                    FROM historial 
                    WHERE MONTH(fecha) = MONTH(CURDATE()) 
                    AND YEAR(fecha) = YEAR(CURDATE())";
        $result = $this->db->query($query);
        $ventas = $result->fetch_assoc();
        $result->free();

        // Productos con stock bajo (menos de 10 unidades)
        $query = "SELECT COUNT(*) as stock_bajo 
                    FROM inventario 
                    WHERE un_disponibles < 10";
        $result = $this->db->query($query);
        $stock = $result->fetch_assoc();
        $result->free();

        // Cuentas por cobrar total
        $query = "SELECT COALESCE(SUM(total_usd), 0) as cuentas_cobrar 
                    FROM cuentascobrar";
        $result = $this->db->query($query);
        $cobrar = $result->fetch_assoc();
        $result->free();

        return [
            'ingresos_mes' => floatval($ingresos['ingresos_mes']),
            'total_ventas' => intval($ventas['total_ventas']),
            'stock_bajo' => intval($stock['stock_bajo']),
            'cuentas_cobrar' => floatval($cobrar['cuentas_cobrar'])
        ];
    }

    public function obtenerVentasMensuales() {
        $query = "SELECT 
                        MONTH(fecha) as mes,
                        COALESCE(SUM(total_usd), 0) as total_ventas
                    FROM historial
                    WHERE YEAR(fecha) = YEAR(CURDATE())
                    GROUP BY MONTH(fecha)
                    ORDER BY mes";
        
        $result = $this->db->query($query);
        $ventasMensuales = array_fill(1, 12, 0);
        
        while ($row = $result->fetch_assoc()) {
            $ventasMensuales[intval($row['mes'])] = floatval($row['total_ventas']);
        }
        
        $result->free();
        return $ventasMensuales;
    }

    public function obtenerDistribucionProductos() {
        // Distribución basada en el valor del inventario por rango de precios
        $query = "SELECT 
                        CASE 
                            WHEN precio_venta < 50 THEN 'Bajo Precio'
                            WHEN precio_venta BETWEEN 50 AND 200 THEN 'Medio Precio'
                            ELSE 'Alto Precio'
                        END as categoria,
                        COUNT(*) as cantidad,
                        COALESCE(SUM(precio_venta * un_disponibles), 0) as valor_total
                    FROM inventario
                    WHERE un_disponibles > 0
                    GROUP BY categoria
                    ORDER BY valor_total DESC";
        
        $result = $this->db->query($query);
        $distribucion = [];
        
        while ($row = $result->fetch_assoc()) {
            $distribucion[] = $row;
        }
        
        $result->free();
        return $distribucion;
    }

    public function obtenerActividadReciente($limite = 10) {
        $query = "SELECT 
                        fecha,
                        cliente,
                        'Venta realizada' as accion,
                        total_usd as valor,
                        'active' as estado
                    FROM historial
                    UNION ALL
                    SELECT 
                        fecha,
                        cliente,
                        'Cuenta por cobrar' as accion,
                        total_usd as valor,
                        'pending' as estado
                    FROM cuentascobrar
                    ORDER BY fecha DESC 
                    LIMIT $limite";
        
        $result = $this->db->query($query);
        $actividad = [];
        
        while ($row = $result->fetch_assoc()) {
            $actividad[] = $row;
        }
        
        $result->free();
        return $actividad;
    }

    public function obtenerTopProductos($limite = 5) {
        // Productos con mayor valor en inventario
        $query = "SELECT 
                    nombre,
                    un_disponibles as stock,
                    precio_venta,
                    (precio_venta * un_disponibles) as valor_inventario
                    FROM inventario
                    WHERE un_disponibles > 0
                    ORDER BY valor_inventario DESC 
                    LIMIT $limite";
        
        $result = $this->db->query($query);
        $productos = [];
        
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        
        $result->free();
        return $productos;
    }

    public function obtenerClientesActivos() {
        // Clientes que han realizado compras este mes
        $query = "SELECT COUNT(DISTINCT cliente) as clientes_activos
                    FROM historial
                    WHERE MONTH(fecha) = MONTH(CURDATE()) 
                    AND YEAR(fecha) = YEAR(CURDATE())";
        
        $result = $this->db->query($query);
        $clientes = $result->fetch_assoc();
        $result->free();
        
        return intval($clientes['clientes_activos']);
    }
}
?>