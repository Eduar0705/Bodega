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

    public function updateNombre($nuevoValor) {
        // Validación básica
        if (empty($nuevoValor) || strlen($nuevoValor) > 100) {
            return [
                'success' => false,
                'message' => 'El nombre no puede estar vacío y debe tener menos de 100 caracteres'
            ];
        }

        $sql = "UPDATE admin SET nombreAPP = ? WHERE id = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $nuevoValor);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Nombre de la empresa actualizado',
                'new_value' => $nuevoValor
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar: ' . $stmt->error
            ];
        }
    }

    public function updateClave($nuevoValor) {
        // Validación básica
        if (empty($nuevoValor) || strlen($nuevoValor) > 100) {
            return [
                'success' => false,
                'message' => 'La clave no puede estar vacía y debe tener menos de 100 caracteres'
            ];
        }

        // Validación mínima de longitud para seguridad
        if (strlen($nuevoValor) < 6) {
            return [
                'success' => false,
                'message' => 'La clave debe tener al menos 6 caracteres'
            ];
        }

        // Hash de la contraseña (seguridad básica)
        $claveHash = password_hash($nuevoValor, PASSWORD_DEFAULT);

        $sql = 'UPDATE admin SET claveSuper = ? WHERE id = 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $claveHash);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Clave actualizada correctamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar: ' . $stmt->error
            ];
        }
    }

    public function addUsuario($cedula, $nombre, $clave_usuario, $id_cargo) {
        // Validaciones básicas
        if (!preg_match('/^[0-9]{7,10}$/', $cedula)) {
            return [
                'success' => false,
                'message' => 'Cédula inválida. Debe contener entre 7 y 10 dígitos numéricos.'
            ];
        }

        if (!in_array($id_cargo, [1, 2, 3])) { // Ajusta según tus cargos reales
            return [
                'success' => false,
                'message' => 'Cargo inválido.'
            ];
        }

        // Verificar si la cédula ya existe
        $sqlCheck = "SELECT id FROM inf_usuarios WHERE cedula = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $cedula);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $stmtCheck->close();
            return [
                'success' => false,
                'message' => 'La cédula ya está registrada.'
            ];
        }
        $stmtCheck->close();

        // Insertar nuevo usuario
        $sql = "INSERT INTO inf_usuarios (cedula, nombre, clave, id_cargo) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $cedula, $nombre, $clave_usuario, $id_cargo);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Usuario agregado exitosamente.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al agregar usuario: ' . $stmt->error
            ];
        }
    }
}
?>