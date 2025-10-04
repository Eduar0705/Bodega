<?php
class Ccobrar{
    private $bd;
    
    public function __construct(){
        $this->bd = (new BaseDatos())->conectar();
    }

    public function __destruct() {
        if ($this->bd) {
            $this->bd->close();
        }
    }

    public function obtenerCC(){
        $sql = "SELECT * FROM cuentascobrar ORDER BY fecha DESC";
        $result = $this->bd->query($sql);

        if(!$result){
            return [];
        }

        $infor = [];
        while($row = $result->fetch_assoc()){
            $infor[] = $row;
        }
        return $infor;
    }

    public function obtenerCuentaPorId($id_historial) {
        $sql = "SELECT * FROM cuentascobrar WHERE id_historial = ?";
        $stmt = $this->bd->prepare($sql);
        
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param("i", $id_historial);
        $stmt->execute();
        $result = $stmt->get_result();
        $cuenta = $result->fetch_assoc();
        $stmt->close();
        
        return $cuenta;
    }

    public function descontarMonto($id, $monto){
        // Iniciar transacción
        $this->bd->begin_transaction();
        
        try {
            // 1. Obtener el total actual
            $cuenta = $this->obtenerCuentaPorId($id);
            
            if (!$cuenta) {
                throw new Exception("Cuenta no encontrada con ID: $id");
            }
            
            $total_actual = floatval($cuenta['total_usd']);
            $monto = floatval($monto);
            
            // 2. Validar que el monto no sea mayor al disponible
            if ($monto > $total_actual) {
                throw new Exception("El monto $monto es mayor al total disponible $total_actual");
            }
            
            // 3. Calcular nuevo total
            $nuevo_total = $total_actual - $monto;
            
            // 4. Actualizar cuentascobrar
            if ($nuevo_total <= 0) {
                // Pago completo
                $sql_cc = "UPDATE cuentascobrar SET 
                            tipo_pago = 'pago', 
                            tipo_venta = 'pagado',
                            total_usd = 0 
                            WHERE id_historial = ?";
            } else {
                // Pago parcial
                $sql_cc = "UPDATE cuentascobrar SET 
                            total_usd = ?,
                            tipo_venta = 'parcial'
                            WHERE id_historial = ?";
            }
            
            $stmt_cc = $this->bd->prepare($sql_cc);
            if (!$stmt_cc) {
                throw new Exception("Error al preparar consulta de cuentascobrar");
            }
            
            if ($nuevo_total <= 0) {
                $stmt_cc->bind_param("i", $id);
            } else {
                $stmt_cc->bind_param("di", $nuevo_total, $id);
            }
            
            if (!$stmt_cc->execute()) {
                throw new Exception("Error al actualizar cuentascobrar");
            }
            $stmt_cc->close();
            
            // 5. Actualizar historial_ventas
            if ($nuevo_total <= 0) {
                // Pago completo
                $sql_hv = "UPDATE historial_ventas SET 
                            tipo_pago = 'pago',
                            tipo_venta = 'pagado'
                            WHERE id = ?";
            } else {
                // Pago parcial
                $sql_hv = "UPDATE historial_ventas SET 
                            tipo_venta = 'parcial'
                            WHERE id = ?";
            }
            
            $stmt_hv = $this->bd->prepare($sql_hv);
            if (!$stmt_hv) {
                throw new Exception("Error al preparar consulta de historial_ventas");
            }
            
            $stmt_hv->bind_param("i", $id);
            
            if (!$stmt_hv->execute()) {
                throw new Exception("Error al actualizar historial_ventas");
            }
            $stmt_hv->close();
            
            // Confirmar transacción
            $this->bd->commit();
            
            // Log de éxito
            error_log("Descuento exitoso - ID: $id, Monto: $monto, Nuevo total: $nuevo_total");
            
            return true;
            
        } catch (Exception $e) {
            // Revertir cambios en caso de error
            $this->bd->rollback();
            error_log("Error en descontarMonto: " . $e->getMessage() . " | ID: $id | Monto: $monto");
            return false;
        }
    }
}
?>