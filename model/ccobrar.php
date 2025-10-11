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
            error_log("Error preparando consulta: " . $this->bd->error);
            return null;
        }
        
        $stmt->bind_param("i", $id_historial);
        $stmt->execute();
        $result = $stmt->get_result();
        $cuenta = $result->fetch_assoc();
        $stmt->close();
        
        return $cuenta;
    }

    public function deletePagadas() {
        $sql = "DELETE FROM cuentascobrar WHERE tipo_venta = 'pagado' or total_usd <= 0";
        $result = $this->bd->query($sql);
        if ($result) {
            return $this->bd->affected_rows;
        } else {
            error_log("Error al eliminar cuentas pagadas: " . $this->bd->error);
            return false;
        }
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
            
            error_log("Total actual: $total_actual, Monto a descontar: $monto");
            
            // 2. Validar que el monto no sea mayor al disponible
            if ($monto > $total_actual) {
                throw new Exception("El monto $monto es mayor al total disponible $total_actual");
            }
            
            // 3. Calcular nuevo total
            $nuevo_total = round($total_actual - $monto, 2);
            error_log("Nuevo total calculado: $nuevo_total");
            
            // 4. Actualizar SOLO cuentascobrar
            if ($nuevo_total <= 0.01) {
                // Pago completo
                $sql = "UPDATE cuentascobrar SET 
                        tipo_pago = 'pago', 
                        tipo_venta = 'pagado',
                        total_usd = 0 
                        WHERE id_historial = ?";
                
                $stmt = $this->bd->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparando UPDATE: " . $this->bd->error);
                }
                
                $stmt->bind_param("i", $id);
            } else {
                // Pago parcial
                $sql = "UPDATE cuentascobrar SET 
                        total_usd = ?,
                        tipo_venta = 'parcial'
                        WHERE id_historial = ?";
                
                $stmt = $this->bd->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparando UPDATE: " . $this->bd->error);
                }
                
                $stmt->bind_param("di", $nuevo_total, $id);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando UPDATE: " . $stmt->error);
            }
            
            $filas_afectadas = $stmt->affected_rows;
            error_log("Filas afectadas: $filas_afectadas");
            $stmt->close();
            
            // Validar que se actualizó al menos una fila
            if ($filas_afectadas === 0) {
                error_log("ADVERTENCIA: No se actualizó ninguna fila. ID: $id");
            }
            
            // Confirmar transacción
            $this->bd->commit();
            error_log("✓ Descuento exitoso - ID: $id, Monto: $monto, Nuevo total: $nuevo_total");
            return true;
            
        } catch (Exception $e) {
            // Revertir cambios en caso de error
            $this->bd->rollback();
            error_log("✗ Error en descontarMonto: " . $e->getMessage());
            throw $e;
        }
    }
}
?>