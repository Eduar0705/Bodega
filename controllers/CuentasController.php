<?php
class CuentasController extends AdminController
{
    private $ccobrar;
    public function __construct()
    {
        parent::__construct();
        $this->ccobrar = new Ccobrar();
    }
//Funciones de cuestas por cobrar o fiados
    public function home(){
        $this->validarSesion();
        $titulo = 'Cuentas por cobrar';
        $cuentas = $this->ccobrar->obtenerCC();
        require_once 'views/cuentas/index.php';
    }

    public function descontarMonto() {
        // IMPORTANTE: Asegurarse de que no haya salida antes de esto
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        try {
            // Capturar datos RAW
            $rawData = file_get_contents('php://input');
            
            error_log("=== INICIO DESCONTAR MONTO ===");
            error_log("Raw Data: " . $rawData);
            
            if (empty($rawData)) {
                throw new Exception('No se recibieron datos');
            }
            
            $data = json_decode($rawData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
            }
            
            error_log("Datos decodificados: " . print_r($data, true));
            
            // Validaciones
            if (!isset($data['id_historial'])) {
                throw new Exception('Falta el ID del historial');
            }
            
            if (!isset($data['monto'])) {
                throw new Exception('Falta el monto a descontar');
            }
            
            $id_historial = intval($data['id_historial']);
            $monto = floatval($data['monto']);
            
            // Validar valores
            if ($id_historial <= 0) {
                throw new Exception('ID de historial inválido');
            }
            
            if ($monto <= 0) {
                throw new Exception('El monto debe ser mayor a 0');
            }
            
            // Redondear a 2 decimales
            $monto = round($monto, 2);
            
            error_log("ID: $id_historial, Monto: $monto");
            
            // Verificar que el modelo esté instanciado
            if (!isset($this->ccobrar)) {
                throw new Exception('Modelo Ccobrar no está instanciado');
            }
            
            // Verificar que la cuenta existe
            $cuenta = $this->ccobrar->obtenerCuentaPorId($id_historial);
            
            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }
            
            error_log("Cuenta encontrada: " . print_r($cuenta, true));
            
            $total_actual = floatval($cuenta['total_usd']);
            
            // Validar que el monto no sea mayor al disponible (con margen de 0.01)
            if ($monto > ($total_actual + 0.01)) {
                throw new Exception('El monto ($' . number_format($monto, 2) . ') es mayor al saldo disponible ($' . number_format($total_actual, 2) . ')');
            }
            
            // Ejecutar descuento
            $resultado = $this->ccobrar->descontarMonto($id_historial, $monto) && $this->ccobrar->deletePagadas();
            
            if (!$resultado) {
                throw new Exception('Error al procesar el descuento en la base de datos');
            }
            
            $nuevo_total = round($total_actual - $monto, 2);
            if ($nuevo_total < 0) $nuevo_total = 0;
            
            error_log("✓ Descuento exitoso. Nuevo total: $" . $nuevo_total);
            
            // Respuesta exitosa
            echo json_encode([
                'success' => true,
                'message' => $nuevo_total <= 0.01 
                    ? 'Cuenta saldada completamente' 
                    : 'Pago de $' . number_format($monto, 2) . ' registrado. Saldo restante: $' . number_format($nuevo_total, 2),
                'nuevo_total' => $nuevo_total,
                'monto_pagado' => $monto
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("✗ ERROR descontarMonto: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

}