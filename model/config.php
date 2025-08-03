
<?php
class Config {
    private $db;

    public function __construct() {
        $this->db = (new BaseDatos())->conectar();
    }

    public function updateDollar($nuevoValor) {
        // Validación básica
        if (!is_numeric($nuevoValor) || $nuevoValor <= 0) {
            return [
                'success' => false,
                'message' => 'El valor debe ser un número mayor a cero'
            ];
        }

        $sql = "UPDATE admin SET precio_dollar = ? WHERE id = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("d", $nuevoValor);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Precio del dólar actualizado',
                'new_value' => $nuevoValor
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar: ' . $stmt->error
            ];
        }
    }
}
?>