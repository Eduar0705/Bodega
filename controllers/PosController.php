<?php
class PosController extends AdminController
{
        //FUNCIONES DEL PUNTO DE VENTA
    public function home(){
        $this->validarSesion();
        $titulo = 'Punto de venta';
        $datos = $this->pos->obtenerDatos();
        $clientes = $this->clientes->obtenerUsuarios();
        require_once 'views/punto/index.php';
    }
    public function confirmarVenta(){
        // Importante: limpiar buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Verificar que sea AJAX
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                throw new Exception('Acceso no permitido');
            }
            
            $input = file_get_contents('php://input');
            
            if (empty($input)) {
                throw new Exception('No se recibieron datos');
            }
            
            $data = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Datos JSON inválidos: ' . json_last_error_msg());
            }
            
            // Validar campos requeridos
            $required = ['fecha', 'cliente', 'tipo_pago', 'tipo_venta', 'total_usd', 'productos'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Campo requerido faltante: $field");
                }
            }
            
            if (!is_array($data['productos']) || empty($data['productos'])) {
                throw new Exception('Debe incluir al menos un producto');
            }
            
            // Procesar venta
            $resultado = $this->pos->procesarVenta(
                $data['fecha'],
                $data['cliente'],
                $data['tipo_pago'],
                $data['tipo_venta'],
                floatval($data['total_usd']),
                $data['productos']
            );
            
            // Limpiar buffer y enviar respuesta
            ob_end_clean();
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            ob_end_clean();
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'error' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }
}