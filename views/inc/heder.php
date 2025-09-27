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
        }

        .user-dropdown.active {
            display: block;
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .user-dropdown a:hover {
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
    </style>
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
                    <a href="#">
                        <i class="fas fa-user"></i>
                        <span>Mi Perfil</span>
                    </a>
                    <a href="#">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                    <a href="./">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menú Lateral -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="?action=admin" class="active">
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
                    <a href="?action=admin&method=config">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <script>
        // Funcionalidad para el menú lateral
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Cambiar ícono del botón
            const icon = menuToggle.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-bars');
            }
        });
        
        // Funcionalidad para el menú desplegable del usuario
        const userProfile = document.getElementById('userProfile');
        const userDropdown = document.getElementById('userDropdown');
        
        userProfile.addEventListener('click', function() {
            userDropdown.classList.toggle('active');
        });
        
        // Cerrar menú desplegable al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            if (!userProfile.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('active');
            }
        });
        
        // Funcionalidad para dispositivos móviles
        function handleResize() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        }
        
        window.addEventListener('resize', handleResize);
        handleResize(); // Ejecutar al cargar la página
    </script>