<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <i class="bi bi-shield-lock"></i>
                <h1>Iniciar Sesión</h1>
            </div>
            
            <form action="?action=inicio&method=loginAuthenticate" method="post">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Usuario o contraseña incorrectos</span>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="user">
                        <i class="bi bi-person icon"></i>
                        <span>Cédula</span>
                    </label>
                    <input type="text" id="user" name="user" placeholder="Ingresa tu cédula" required>
                </div>
            
                <div class="form-group">
                    <label for="password">
                        <i class="bi bi-lock icon"></i>
                        <span>Contraseña</span>
                    </label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                        <button class="btn-toggle" type="button" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" name="init" class="btn-submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Iniciar Sesión</span>
                </button>
            </form>
            
            <div class="footer-links">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
    </div>
    
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'bi bi-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'bi bi-eye';
        }
    }
    </script>
</body>
</html>