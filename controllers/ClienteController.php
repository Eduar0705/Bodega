<?php
class ClienteController extends AdminController
{
    protected $clientes;
// Función de Clientes
    public function users(){
        $this->validarSesion();
        $titulo = 'Clientes';
        $Clientes = $this->clientes->obtenerUsuarios();

        if (isset($_POST['btn-add'])) {
            $nombre = trim($_POST['name']);
            $cedula = trim($_POST['cedula']);
            $telefono = trim($_POST['cel']);

            // Validar que los campos no estén vacíos
            if (empty($nombre) || empty($cedula)) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Campos Requeridos",
                        text: "El nombre y la cédula son obligatorios",
                        confirmButtonColor: "#e74c3c"
                    });
                </script>';
            } else {
                if ($this->clientes->agregarUsuario($nombre, $cedula, $telefono)) {
                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "Cliente agregado correctamente",
                            confirmButtonColor: "#3498db",
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = "?action=cliente&method=users";
                        });
                    </script>';
                } else {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Error al agregar el cliente. Intente nuevamente.",
                            confirmButtonColor: "#e74c3c"
                        });
                    </script>';
                }
            }
        }
        require_once 'views/usuarios/index.php';
    }

    public function deleteCliente(){
        try {
            // Verificar que el ID exista en la petición
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "ID del cliente no encontrado",
                        confirmButtonColor: "#e74c3c"
                    }).then(() => {
                        window.location.href = "?action=cliente&method=users";
                    });
                </script>';
                exit();
            }

            $id = intval($_GET['id']); // Convertir a entero para seguridad

            // Intentar eliminar el cliente
            if ($this->clientes->deleteCliente($id)) {
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Eliminado!",
                        text: "Cliente eliminado exitosamente",
                        confirmButtonColor: "#3498db",
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = "?action=cliente&method=users";
                    });
                </script>';
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo eliminar el cliente. Verifique que exista.",
                        confirmButtonColor: "#e74c3c"
                    }).then(() => {
                        window.location.href = "?action=cliente&method=users";
                    });
                </script>';
            }
        } catch (Exception $e) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error del Servidor",
                    text: "Ocurrió un error inesperado: ' . $e->getMessage() . '",
                    confirmButtonColor: "#e74c3c"
                }).then(() => {
                    window.location.href = "?action=cliente&method=users";
                });
            </script>';
        }
        
        // No usar header() después de echo
        require_once 'views/usuarios/index.php';
        exit();
    }

}