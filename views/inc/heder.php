        <!-- Menú Superior -->
        <nav class="navbar">
            <div class="navbar-left">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="logo"><?= APP_NAME ?></span>
            </div>
            <div class="navbar-right">
                <div class="user-menu">
                    <div class="user-profile">
                        <span><?= $_SESSION['nombre'] ?></span>
                    </div>
                    <div class="exit">
                        <a href="./"><i class="fas fa-sign-out-alt"></i> Salir</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Menú Lateral -->
        <aside class="sidebar">
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
                            <span>Cuestas por cobrar</span>
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