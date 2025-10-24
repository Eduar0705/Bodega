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
        .btn-eliminar, .btn-editar {
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
        
        .btn-editar {
            background-color: #eaea36ff;
        }
        
        .btn-eliminar:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }
        .btn-editar:hover {
            background-color: #d4d434ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(234, 234, 54, 0.3);
        }
        
        .btn-eliminar:active, .btn-editar:active {
            transform: translateY(0);
        }
        
        .btn-eliminar i, .btn-editar i {
            font-size: 12px;
        }
        
        .btn-eliminar:disabled, .btn-editar:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .btn-eliminar:disabled:hover, .btn-editar:disabled:hover {
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
            <section class="config-section">
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
                                        <button class="btn-editar" onclick="editarUsuario(<?= $usuario['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if(isset($_SESSION['cedula']) && $_SESSION['cedula'] == $usuario['cedula']): ?>
                                            <button class="btn-eliminar" disabled title="No puedes eliminar tu propia cuenta">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-eliminar" onclick="eliminarUsuario(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')">
                                                <i class="fas fa-trash"></i> 
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

    <!--ELIMINAR USUARIO-->
    <script>
        function eliminarUsuario(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar al usuario "${nombre}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario por POST para eliminar el usuario
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '?action=admin&method=config';

                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'id_usuario';
                    inputId.value = id;
                    form.appendChild(inputId);

                    const inputEliminar = document.createElement('input');
                    inputEliminar.type = 'hidden';
                    inputEliminar.name = 'eliminar_usuario';
                    inputEliminar.value = '1';
                    form.appendChild(inputEliminar);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <!-- TOGGLE PASSWORD VISIBILITY -->
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(element) {
            element.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>