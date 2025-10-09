<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Variables CSS para facilitar el mantenimiento */
    :root {
        --primary-color: #3498db;
        --danger-color: #e74c3c;
        --danger-hover: #c0392b;
        --sidebar-bg: #2c3e50;
        --sidebar-hover: #34495e;
        --text-light: #ecf0f1;
        --text-dark: #2c3e50;
        --shadow: 0 2px 5px rgba(0,0,0,0.1);
        --transition: all 0.3s ease;
    }

    /* Menú Superior */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        padding: 0.75rem 1.5rem;
        box-shadow: var(--shadow);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        height: 60px;
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .menu-toggle {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--text-dark);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 4px;
        transition: var(--transition);
    }

    .menu-toggle:hover {
        background-color: #f0f0f0;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .navbar-right {
        display: flex;
        align-items: center;
    }

    .user-menu {
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 4px;
        transition: var(--transition);
    }

    .user-profile:hover {
        background-color: #f0f0f0;
    }

    .user-profile span {
        font-weight: 500;
    }

    .user-dropdown {
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

    .user-dropdown.active {
        display: block;
    }

    .user-dropdown a, button {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--text-dark);
        text-decoration: none;
        transition: var(--transition);
    }

    .user-dropdown button{
        border: none;
    }

    .user-dropdown a:hover , button:hover{
        background-color: #f5f5f5;
    }

    .exit a {
        color: #fff; 
        text-decoration: none; 
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px; 
        padding: 8px 12px;
        background-color: var(--danger-color); 
        border-radius: 4px; 
        transition: var(--transition); 
    }

    .exit a:hover {
        background-color: var(--danger-hover);
    }

    .fas.fa-sign-out-alt {
        font-size: 14px;
    }

    /* Menú Lateral */
    .sidebar {
        position: fixed;
        top: 60px;
        left: 0;
        bottom: 0;
        width: 250px;
        background-color: var(--sidebar-bg);
        color: var(--text-light);
        transition: var(--transition);
        z-index: 999;
        overflow-y: auto;
    }

    .sidebar.collapsed {
        width: 60px;
    }

    .sidebar.collapsed .sidebar-menu span {
        display: none;
    }

    .sidebar-menu ul {
        list-style: none;
        padding: 1rem 0;
    }

    .sidebar-menu li {
        margin-bottom: 0.25rem;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1.5rem;
        color: var(--text-light);
        text-decoration: none;
        transition: var(--transition);
    }

    .sidebar-menu a:hover {
        background-color: var(--sidebar-hover);
    }

    .sidebar-menu a.active {
        background-color: var(--primary-color);
    }

    .sidebar-menu i {
        width: 20px;
        text-align: center;
    }

    /* Contenido principal */
    .main-content {
        margin-left: 250px;
        margin-top: 60px;
        padding: 2rem;
        transition: var(--transition);
    }

    .main-content.expanded {
        margin-left: 60px;
    }

    /* Responsividad */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .user-profile span {
            display: none;
        }
        
        .logo {
            font-size: 1.25rem;
        }
    }

    /* Estilos para el contenido de ejemplo */
    .content-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--shadow);
        margin-bottom: 1.5rem;
    }
    .modal {
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
        transition: var(--transition);
        align-items: center;
        justify-content: center;
    }

    .modal form {
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

    .close {
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

    .close:hover {
        color: var(--danger-color);
    }

    #veriFrom label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 15px;
    }

    #clave {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        font-size: 15px;
        margin-bottom: 8px;
        background: #f8f9fa;
        transition: border-color 0.2s;
    }

    #clave:focus {
        border-color: var(--primary-color);
        outline: none;
        background: #fff;
    }

    #verif {
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

    #verif:hover {
        background-color: #21bb236c;
        color: #2c3e50;
    }

    @media (max-width: 480px) {
        .modal form {
            padding: 1.2rem 1rem 1rem 1rem;
            max-width: 95vw;
        }
        .close {
            top: 10px;
            right: 14px;
            font-size: 24px;
        }
    }

</style>

<!-- Menú Superior -->
<nav class="navbar">
    <div class="navbar-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <span class="logo"><?= APP_NAME ?></span>
    </div>
    <div class="navbar-right">
        <div class="user-menu">
            <div class="user-profile" id="userProfile">
                <i class="fas fa-user-circle"></i>
                <span><?= $_SESSION['nombre'] ?></span>
                <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <button id="abrir" name="abrir" >
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
<!-- href="?action=admin&method=config" -->

<!-- Menú Lateral -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-menu">
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
                    <span>Usuarios</span>
                </a>
            </li>
            <li>
                <a href="?action=admin&method=estadisticas">
                    <i class="fas fa-users"></i>
                    <span>Edtadisticas</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<div id="verifModal" class="modal">
    <form action="" method="post" id="veriFrom" autocomplete="off">
        <span class="close">&times;</span>
        <h2>Validación de Clave</h2>
        <label for="clave">Clave Superior:</label>
        <div style="position:relative;">
            <input type="password" name="clave" id="clave" required>
            <button type="button" id="toggleClave" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer;">
                <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
        </div>
        <button type="submit" name="verif" id="verif">Verificar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- FUNCION DEL modal -->
<script>
    const modal = document.getElementById('verifModal');
    const abrir = document.getElementById('abrir');
    const closeBtn = document.querySelector('.close');
    const toggleClave = document.getElementById('toggleClave');
    const claveInput = document.getElementById('clave');
    const eyeIcon = document.getElementById('eyeIcon');
    const claveSuper = "<?= addslashes(APP_Password) ?>";

    // Abrir modal
    abrir.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    // Cerrar modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
        claveInput.value = '';
    });

    // Mostrar/ocultar clave
    toggleClave.addEventListener("click", () => {
        if (claveInput.type === "password") {
            claveInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            claveInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    });

    // Cerrar modal al hacer click fuera del formulario
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Verificación de clave y redirige a configuración
    document.getElementById('veriFrom').addEventListener('submit', function(e){
        e.preventDefault();
        if(claveInput.value === claveSuper){
            window.location.href = "?action=admin&method=config";
        } else {
            modal.style.display = 'none';
            Swal.fire({
                title: 'ERROR',
                text: 'Error, la clave ingresada es incorrecta',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Volver a intentar',
                cancelButtonText: 'Cancelar'
            }).then((resul)=>{
                if(resul.isConfirmed){
                    modal.style.display = 'flex';
                    claveInput.value = '';
                }
            })
        }
    });
</script>

<!-- PARA EL NAV Y EL ASIDE -->
<script>
    // Toggle del menú de usuario
    const userProfile = document.getElementById('userProfile');
    const userDropdown = document.getElementById('userDropdown');

    userProfile.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
    });

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });

    // Toggle del sidebar
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    menuToggle.addEventListener('click', function() {
        // Para desktop: colapsar
        if (window.innerWidth > 768) {
            sidebar.classList.toggle('collapsed');
            if (mainContent) {
                mainContent.classList.toggle('expanded');
            }
        } else {
            // Para móvil: mostrar/ocultar
            sidebar.classList.toggle('active');
        }
    });

    // Cerrar sidebar en móvil al hacer click en un enlace
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
    });
</script>