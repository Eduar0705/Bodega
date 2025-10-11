<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Variables CSS para facilitar el mantenimiento */
    :root {
        --color-primario: #3498db;
        --color-peligro: #e74c3c;
        --color-peligro-hover: #c0392b;
        --color-barra-lateral: #2c3e50;
        --color-barra-lateral-hover: #34495e;
        --texto-claro: #ecf0f1;
        --texto-oscuro: #2c3e50;
        --sombra-base: 0 2px 5px rgba(0,0,0,0.1);
        --transicion-suave: all 0.3s ease;
    }

    /* Barra de Navegación Superior */
    .barra-navegacion {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        padding: 0.75rem 1.5rem;
        box-shadow: var(--sombra-base);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        height: 60px;
    }

    .seccion-izquierda {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .boton-menu {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--texto-oscuro);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 4px;
        transition: var(--transicion-suave);
    }

    .boton-menu:hover {
        background-color: #f0f0f0;
    }

    .logotipo {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--color-primario);
    }

    .seccion-derecha {
        display: flex;
        align-items: center;
    }

    .contenedor-usuario {
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    .perfil-usuario {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 4px;
        transition: var(--transicion-suave);
    }

    .perfil-usuario:hover {
        background-color: #f0f0f0;
    }

    .perfil-usuario span {
        font-weight: 500;
    }

    .menu-desplegable {
        position: absolute;
        top: 100%;
        right: 0;
        background-color: white;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 180px;
        padding: 0.5rem 0;
        display: none;
        z-index: 1001;
        margin-top: 0.5rem;
    }

    .menu-desplegable.mostrar {
        display: block;
    }

    .menu-desplegable a, 
    .menu-desplegable button {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--texto-oscuro);
        text-decoration: none;
        transition: var(--transicion-suave);
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
    }

    .menu-desplegable a:hover,
    .menu-desplegable button:hover {
        background-color: #f5f5f5;
    }

    .boton-salir a {
        color: #fff; 
        text-decoration: none; 
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px; 
        padding: 8px 12px;
        background-color: var(--color-peligro); 
        border-radius: 4px; 
        transition: var(--transicion-suave); 
    }

    .boton-salir a:hover {
        background-color: var(--color-peligro-hover);
    }

    /* Barra Lateral de Navegación */
    .barra-lateral {
        position: fixed;
        top: 60px;
        left: 0;
        bottom: 0;
        width: 250px;
        background-color: var(--color-barra-lateral);
        color: var(--texto-claro);
        transition: var(--transicion-suave);
        z-index: 999;
        overflow-y: auto;
    }

    .barra-lateral.contraida {
        width: 60px;
    }

    .barra-lateral.contraida .navegacion-lateral span {
        display: none;
    }

    .navegacion-lateral ul {
        list-style: none;
        padding: 1rem 0;
    }

    .navegacion-lateral li {
        margin-bottom: 0.25rem;
    }

    .navegacion-lateral a {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1.5rem;
        color: var(--texto-claro);
        text-decoration: none;
        transition: var(--transicion-suave);
    }

    .navegacion-lateral a:hover {
        background-color: var(--color-barra-lateral-hover);
    }

    .navegacion-lateral a.activo {
        background-color: var(--color-primario);
    }

    .navegacion-lateral i {
        width: 20px;
        text-align: center;
    }

    /* Contenido Principal */
    .contenido-principal {
        margin-left: 250px;
        margin-top: 60px;
        padding: 2rem;
        transition: var(--transicion-suave);
    }

    .contenido-principal.expandido {
        margin-left: 60px;
    }

    /* Responsividad */
    @media (max-width: 768px) {
        .barra-lateral {
            transform: translateX(-100%);
        }
        
        .barra-lateral.visible {
            transform: translateX(0);
        }
        
        .contenido-principal {
            margin-left: 0;
        }
        
        .perfil-usuario span {
            display: none;
        }
        
        .logotipo {
            font-size: 1.25rem;
        }
    }

    /* Estilos para tarjetas de contenido */
    .tarjeta-contenido {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--sombra-base);
        margin-bottom: 1.5rem;
    }

    /* Modal de Verificación */
    .ventana-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        overflow: auto;
        background: rgba(44, 62, 80, 0.35);
        backdrop-filter: blur(2px);
        transition: var(--transicion-suave);
        align-items: center;
        justify-content: center;
    }

    .ventana-modal form {
        background: #fff;
        margin: 8% auto;
        padding: 2rem 2.5rem 1.5rem 2.5rem;
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(44,62,80,0.18);
        max-width: 350px;
        width: 90%;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        border: 1px solid #e0e0e0;
    }

    .boton-cerrar {
        color: #888;
        position: absolute;
        top: 18px;
        right: 22px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s;
        z-index: 10;
    }

    .boton-cerrar:hover {
        color: var(--color-peligro);
    }

    .formulario-verificacion label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--texto-oscuro);
        font-size: 15px;
    }

    .campo-clave {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        font-size: 15px;
        margin-bottom: 8px;
        background: #f8f9fa;
        transition: border-color 0.2s;
    }

    .campo-clave:focus {
        border-color: var(--color-primario);
        outline: none;
        background: #fff;
    }

    .boton-verificar {
        background-color: #38d718ff;
        color: white;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        border: none;
        margin-top: 8px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(52,152,219,0.08);
        transition: background 0.2s;
        text-align: center;
    }

    .boton-verificar:hover {
        background-color: #21bb236c;
        color: #2c3e50;
    }

    @media (max-width: 480px) {
        .ventana-modal form {
            padding: 1.2rem 1rem 1rem 1rem;
            max-width: 95vw;
        }
        .boton-cerrar {
            top: 10px;
            right: 14px;
            font-size: 24px;
        }
    }
</style>

<!-- Barra de Navegación Superior -->
<nav class="barra-navegacion">
    <div class="seccion-izquierda">
        <button class="boton-menu" id="alternadorMenu">
            <i class="fas fa-bars"></i>
        </button>
        <span class="logotipo"><?= APP_NAME ?></span>
    </div>
    <div class="seccion-derecha">
        <div class="contenedor-usuario">
            <div class="perfil-usuario" id="perfilUsuario">
                <i class="fas fa-user-circle"></i>
                <span><?= $_SESSION['nombre'] ?></span>
                <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
            </div>
            <div class="menu-desplegable" id="menuDesplegableUsuario">
                <button id="abrirConfiguracion" name="abrirConfiguracion">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </button>
                <a href="./">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Barra Lateral de Navegación -->
<aside class="barra-lateral" id="barraLateral">
    <div class="navegacion-lateral">
        <ul>
            <li>
                <a href="?action=admin">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=inventario">
                    <i class="fas fa-boxes"></i>
                    <span>Inventario</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=punto">
                    <i class="fas fa-cash-register"></i>
                    <span>Punto de venta</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=historial">
                    <i class="fas fa-history"></i>
                    <span>Historial</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=cuentas">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Cuentas por cobrar</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=users">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=proveedores">
                    <i class="fas fa-truck"></i>
                    <span>Proveedores</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=estadisticas">
                    <i class="fas fa-chart-bar"></i>
                    <span>Estadísticas</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Modal de Verificación -->
<div id="modalVerificacion" class="ventana-modal">
    <form action="" method="post" class="formulario-verificacion" id="formularioVerificacion" autocomplete="off">
        <span class="boton-cerrar">&times;</span>
        <h2>Validación de Clave</h2>
        <label for="campoClaveSeguridad">Clave Superior:</label>
        <div style="position:relative;">
            <input type="password" name="clave" id="campoClaveSeguridad" class="campo-clave" required>
            <button type="button" id="alternadorVisibilidad" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer;">
                <i class="fas fa-eye" id="iconoOjo"></i>
            </button>
        </div>
        <button type="submit" name="verificar" id="botonVerificar" class="boton-verificar">Verificar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script para el Modal de Verificación -->
<script>
    const ventanaModal = document.getElementById('modalVerificacion');
    const btnAbrirConfig = document.getElementById('abrirConfiguracion');
    const btnCerrarModal = document.querySelector('.boton-cerrar');
    const btnAlternarVisibilidad = document.getElementById('alternadorVisibilidad');
    const inputClave = document.getElementById('campoClaveSeguridad');
    const iconoVisibilidad = document.getElementById('iconoOjo');
    const claveSeguridad = "<?= addslashes(APP_Password) ?>";

    // Abrir modal
    btnAbrirConfig.addEventListener("click", () => {
        ventanaModal.style.display = "flex";
    });

    // Cerrar modal
    btnCerrarModal.addEventListener("click", () => {
        ventanaModal.style.display = "none";
        inputClave.value = '';
    });

    // Mostrar/ocultar clave
    btnAlternarVisibilidad.addEventListener("click", () => {
        if (inputClave.type === "password") {
            inputClave.type = "text";
            iconoVisibilidad.classList.remove("fa-eye");
            iconoVisibilidad.classList.add("fa-eye-slash");
        } else {
            inputClave.type = "password";
            iconoVisibilidad.classList.remove("fa-eye-slash");
            iconoVisibilidad.classList.add("fa-eye");
        }
    });

    // Cerrar modal al hacer clic fuera del formulario
    window.addEventListener("click", (evento) => {
        if (evento.target === ventanaModal) {
            ventanaModal.style.display = "none";
        }
    });

    // Verificación de clave y redirección a configuración
    document.getElementById('formularioVerificacion').addEventListener('submit', function(evento){
        evento.preventDefault();
        if(inputClave.value === claveSeguridad){
            window.location.href = "?action=admin&method=config";
        } else {
            ventanaModal.style.display = 'none';
            Swal.fire({
                title: 'ERROR',
                text: 'Error, la clave ingresada es incorrecta',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Volver a intentar',
                cancelButtonText: 'Cancelar'
            }).then((resultado) => {
                if(resultado.isConfirmed){
                    ventanaModal.style.display = 'flex';
                    inputClave.value = '';
                }
            });
        }
    });
</script>

<!-- Script para Navegación -->
<script>
    // Alternar menú desplegable de usuario
    const elementoPerfil = document.getElementById('perfilUsuario');
    const menuUsuario = document.getElementById('menuDesplegableUsuario');

    elementoPerfil.addEventListener('click', function(evento) {
        evento.stopPropagation();
        menuUsuario.classList.toggle('mostrar');
    });

    // Cerrar menú desplegable al hacer clic fuera
    document.addEventListener('click', function(evento) {
        if (!elementoPerfil.contains(evento.target) && !menuUsuario.contains(evento.target)) {
            menuUsuario.classList.remove('mostrar');
        }
    });

    // Alternar barra lateral
    const botonAlternador = document.getElementById('alternadorMenu');
    const barraNavegacion = document.getElementById('barraLateral');
    const contenidoPrincipal = document.querySelector('.contenido-principal');

    botonAlternador.addEventListener('click', function() {
        // Para desktop: contraer/expandir
        if (window.innerWidth > 768) {
            barraNavegacion.classList.toggle('contraida');
            if (contenidoPrincipal) {
                contenidoPrincipal.classList.toggle('expandido');
            }
        } else {
            // Para móvil: mostrar/ocultar
            barraNavegacion.classList.toggle('visible');
        }
    });

    // Cerrar barra lateral en móvil al hacer clic en un enlace
    const enlacesNavegacion = document.querySelectorAll('.navegacion-lateral a');
    enlacesNavegacion.forEach(enlace => {
        enlace.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                barraNavegacion.classList.remove('visible');
            }
        });
    });
</script>