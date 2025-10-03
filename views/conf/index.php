<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="stylesheet" href="public/css/config.css">
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

            <!-- Sección: Clave Maestra -->
            <section class="config-section">
                <h3><i class="fas fa-key"></i> Cambio de Clave Maestra</h3>
                
                <div class="alert-box alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>La clave maestra es utilizada para acciones críticas del sistema. Guárdala en un lugar seguro.</span>
                </div>

                <form class="config-form" method="POST" action="?action=admin&method=config" autocomplete="off">
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

                <form class="config-form" method="POST" action="?action=admin&method=config" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre">
                            Nombre de la Aplicación<span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre"
                            placeholder="Ingrese el nuevo nombre de la aplicación"
                            value="<?= APP_NAME ?? '' ?>"
                            required
                            maxlength="50">
                    </div>

                    <button type="submit" name="uptade">
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

                <form class="config-form" id="formAgregarUsuario" autocomplete="off">
                    <div class="form-group">
                        <label for="cedula">
                            Cédula<span class="required">*</span>
                        </label>
                        <input type="text" id="cedula" name="cedula" 
                            placeholder="Ingrese la cédula del usuario" required maxlength="10">
                    </div>

                    <div class="form-group">
                        <label for="nombre">
                            Nombre Completo<span class="required">*</span>
                        </label>
                        <input type="text"id="nombre" name="nombre" 
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
            
        </main>
    </div>
</body>
</html>