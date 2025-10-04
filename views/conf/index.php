<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="stylesheet" href="public/css/config.css">
    <style>
        /* Estilos para alertas modernas */
        .swal2-popup {
            font-family: Arial, sans-serif;
        }
        
        /* Estilos para el botón eliminar */
        .btn-eliminar {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-eliminar:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }
        
        .btn-eliminar:active {
            transform: translateY(0);
        }
        
        .btn-eliminar i {
            font-size: 12px;
        }
        
        .btn-eliminar:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .btn-eliminar:disabled:hover {
            transform: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <?php include_once 'views/inc/heder.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1><?= $titulo ?></h1>
                <p style="color: #7f8c8d; margin-top: 5px;">
                    <i class="far fa-calendar-alt"></i> Hoy es: <?= APP_Date ?>
                </p>
            </div>

            <?php if(isset($_SESSION['mensaje'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '<?php echo $_SESSION['tipo_mensaje'] === 'success' ? 'success' : 'error'; ?>',
                            title: '<?php echo $_SESSION['tipo_mensaje'] === 'success' ? '¡Éxito!' : 'Error'; ?>',
                            text: '<?php echo addslashes($_SESSION['mensaje']); ?>',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                </script>
                <?php 
                unset($_SESSION['mensaje']);
                unset($_SESSION['tipo_mensaje']);
                ?>
            <?php endif; ?>

            <!-- Sección: Clave Maestra -->
            <section class="config-section">
                <h3><i class="fas fa-key"></i> Cambio de Clave Maestra</h3>
                
                <div class="alert-box alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>La clave maestra es utilizada para acciones críticas del sistema. Guárdala en un lugar seguro.</span>
                </div>

                <form class="config-form" id="formClaveMaestra" method="POST" action="?action=admin&method=config" autocomplete="off">
                    <div class="form-group">
                        <label for="clave_actual">
                            Clave Maestra Actual<span class="required">*</span>
                        </label>
                        <div class="password-toggle">
                            <input 
                                type="password" 
                                id="clave_actual" 
                                name="clave_actual"  
                                placeholder="Ingrese la clave maestra actual"
                                required>
                            <i class="fas fa-eye toggle-password" data-target="clave_actual"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="clave_nueva">
                            Nueva Clave Maestra<span class="required">*</span>
                        </label>
                        <div class="password-toggle">
                            <input 
                                type="password" 
                                id="clave_nueva" 
                                name="clave_nueva"
                                placeholder="Ingrese la nueva clave maestra"
                                required
                                minlength="6">
                            <i class="fas fa-eye toggle-password" data-target="clave_nueva"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_clave">
                            Confirmar Nueva Clave<span class="required">*</span>
                        </label>
                        <div class="password-toggle">
                            <input 
                                type="password" 
                                id="confirmar_clave" 
                                name="confirmar_clave"
                                placeholder="Confirme la nueva clave maestra"
                                required
                                minlength="6">
                            <i class="fas fa-eye toggle-password" data-target="confirmar_clave"></i>
                        </div>
                    </div>

                    <button type="submit" name="cambiar_clave">
                        <i class="fas fa-check-circle"></i> Actualizar Clave Maestra
                    </button>
                </form>
            </section>

            <!-- Sección: Nombre de la Aplicación -->
            <section class="config-section">
                <h3><i class="fas fa-tag"></i> Cambio de Nombre de la Aplicación</h3>
                
                <div class="alert-box alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Este nombre aparecerá en el título y encabezado de todas las páginas.</span>
                </div>

                <form class="config-form" id="formNombreApp" method="POST" action="?action=admin&method=cambiarNombreApp" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_app">
                            Nombre de la Aplicación<span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nombre_app" 
                            name="nombre_app"
                            placeholder="Ingrese el nuevo nombre de la aplicación"
                            value="<?= APP_NAME ?? '' ?>"
                            required
                            maxlength="50">
                    </div>

                    <button type="submit" name="cambiar_nombre">
                        <i class="fas fa-check-circle"></i> Actualizar Nombre
                    </button>
                </form>
            </section>

            <!-- Sección: Agregar Usuario -->
            <section class="config-section">
                <h3><i class="fas fa-user-plus"></i> Agregar Nuevo Usuario</h3>
                
                <div class="alert-box alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Los nuevos usuarios serán creados con cargo de usuario estándar por defecto.</span>
                </div>

                <form class="config-form" id="formAgregarUsuario" method="POST" action="?action=admin&method=config" autocomplete="off">
                    <div class="form-group">
                        <label for="cedula">
                            Cédula<span class="required">*</span>
                        </label>
                        <input type="text" id="cedula" name="cedula" 
                            placeholder="Ingrese la cédula del usuario" required maxlength="10">
                    </div>

                    <div class="form-group">
                        <label for="nombre_usuario">
                            Nombre Completo<span class="required">*</span>
                        </label>
                        <input type="text" id="nombre_usuario" name="nombre_usuario" 
                            placeholder="Ingrese el nombre completo" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="clave_usuario">
                            Clave de Acceso<span class="required">*</span>
                        </label>
                        <div class="password-toggle">
                            <input type="password" id="clave_usuario" name="clave_usuario" 
                                placeholder="Ingrese la clave del usuario" required minlength="6">
                            <i class="fas fa-eye toggle-password" data-target="clave_usuario"></i>
                        </div>
                    </div>
                    <input type="hidden" name="id_cargo" value="1">
                    <button type="submit" name="agregar_usuario">
                        <i class="fas fa-user-plus"></i> Agregar Usuario
                    </button>
                </form>
            </section>

            <!-- Sección: Lista de Usuarios -->
            <section class="mostrar-usuarios">
                <h3><i class="fas fa-users"></i> Lista de Usuarios</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosTableBody">
                        <?php if(isset($usuarios['success']) && $usuarios['success'] && !empty($usuarios['data'])): ?>
                            <?php foreach($usuarios['data'] as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                                    <td><?= htmlspecialchars($usuario['cedula']) ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                    <td><?= $usuario['id_cargo'] == 1 ? 'Administrador' : 'Usuario' ?></td>
                                    <td>
                                        <?php if(isset($_SESSION['cedula']) && $_SESSION['cedula'] == $usuario['cedula']): ?>
                                            <button class="btn-eliminar" disabled title="No puedes eliminar tu propia cuenta">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-eliminar" onclick="eliminarUsuario(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No hay usuarios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const ClaveMaestra = '<?= APP_Password ?>';

        // Toggle de contraseñas
        function togglePassword(){
            const toggleIcons = document.querySelectorAll('.toggle-password');
            toggleIcons.forEach(icon => {
                icon.addEventListener('click', () => {
                    const targetId = icon.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const isVisible = input.type === 'text';
                    input.type = isVisible ? 'password' : 'text';
                    icon.classList.toggle('fa-eye', isVisible);
                    icon.classList.toggle('fa-eye-slash', !isVisible);
                });
            });
        }

        // Validación del formulario de cambio de clave maestra
        document.getElementById('formClaveMaestra')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const claveActual = document.getElementById('clave_actual').value;
            const claveNueva = document.getElementById('clave_nueva').value;
            const confirmarClave = document.getElementById('confirmar_clave').value;

            // Verificar que la clave actual sea correcta (comparar con ClaveMaestra)
            if(claveActual !== ClaveMaestra) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Autenticación',
                    text: 'La clave maestra actual es incorrecta',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Verificar que las claves nuevas coincidan
            if(claveNueva !== confirmarClave) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Validación',
                    text: 'Las claves nuevas no coinciden',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Verificar longitud mínima
            if(claveNueva.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Clave Insegura',
                    text: 'La clave debe tener al menos 6 caracteres',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Verificar que la nueva clave sea diferente a la actual
            if(claveNueva === ClaveMaestra) {
                Swal.fire({
                    icon: 'error',
                    title: 'Clave Repetida',
                    text: 'La nueva clave debe ser diferente a la actual',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Confirmar cambio
            Swal.fire({
                title: '¿Confirmar cambio?',
                text: "Se actualizará la clave maestra del sistema",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Validación del formulario de cambio de nombre de la aplicación
        document.getElementById('formNombreApp')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const nuevoNombre = document.getElementById('nombre_app').value.trim();
            const nombreActual = '<?= APP_NAME ?? '' ?>';

            // Validaciones (mantén las que ya tienes)
            if (nuevoNombre.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo Vacío',
                    text: 'El nombre de la aplicación no puede estar vacío',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (nuevoNombre === nombreActual) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin Cambios',
                    text: 'El nuevo nombre es igual al actual',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Solicitar clave maestra
            Swal.fire({
                title: 'Autenticación Requerida',
                html: '<input type="password" id="swal-clave" class="swal2-input" placeholder="Ingrese la clave maestra">',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const clave = document.getElementById('swal-clave').value;
                    if (!clave) {
                        Swal.showValidationMessage('Debe ingresar la clave maestra');
                        return false;
                    }
                    // Aquí va tu validación de clave maestra
                    if (clave !== 'tu_clave_maestra') { // Reemplaza con tu validación real
                        Swal.showValidationMessage('Clave maestra incorrecta');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar formulario normalmente (sin esperar JSON)
                    this.submit();
                }
            });
        });

        // Validación del formulario de agregar usuario
        document.getElementById('formAgregarUsuario')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const cedula = document.getElementById('cedula').value.trim();
            const nombre = document.getElementById('nombre_usuario').value.trim();
            const clave = document.getElementById('clave_usuario').value;

            // Validar cédula
            if(!/^[0-9]{7,10}$/.test(cedula)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cédula Inválida',
                    text: 'La cédula debe contener entre 7 y 10 dígitos numéricos',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Validar nombre
            if(nombre.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nombre Requerido',
                    text: 'Debe ingresar el nombre completo del usuario',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Validar clave
            if(clave.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Clave Insegura',
                    text: 'La clave debe tener al menos 6 caracteres',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Solicitar clave maestra
            Swal.fire({
                title: 'Autenticación Requerida',
                html: '<input type="password" id="swal-clave" class="swal2-input" placeholder="Ingrese la clave maestra">',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Agregar Usuario',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const clave = document.getElementById('swal-clave').value;
                    if (!clave) {
                        Swal.showValidationMessage('Debe ingresar la clave maestra');
                        return false;
                    }
                    if (clave !== ClaveMaestra) {
                        Swal.showValidationMessage('Clave maestra incorrecta');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Función para eliminar usuario
        function eliminarUsuario(id, nombre) {
            Swal.fire({
                title: '¿Está seguro?',
                html: `Se eliminará al usuario: <strong>${nombre}</strong><br><br>` +
                        '<input type="password" id="swal-clave" class="swal2-input" placeholder="Ingrese la clave maestra">',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const clave = document.getElementById('swal-clave').value;
                    if (!clave) {
                        Swal.showValidationMessage('Debe ingresar la clave maestra');
                        return false;
                    }
                    if (clave !== ClaveMaestra) {
                        Swal.showValidationMessage('Clave maestra incorrecta');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear formulario y enviarlo
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '?action=admin&method=config';
                    
                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'id_usuario';
                    inputId.value = id;
                    
                    const inputEliminar = document.createElement('input');
                    inputEliminar.type = 'hidden';
                    inputEliminar.name = 'eliminar_usuario';
                    inputEliminar.value = '1';
                    
                    form.appendChild(inputId);
                    form.appendChild(inputEliminar);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', togglePassword);
    </script>
</body>
</html>