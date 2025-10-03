<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de <?= APP_NAME ?? 'Inicio' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
</head>
<style>
/* Contenedor principal del formulario */
form {
    display: flex;
    align-items: center;
    gap: 15px;
    background-color: #ffffff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    max-width: 400px;
    margin: 0 auto;
}

form:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

/* Contenedor de la información */
.card-infoo {
    display: flex;
    flex-grow: 1;
    gap: 10px;
}

/* Campo de entrada */
#dollar {
    flex-grow: 1;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    outline: none;
    transition: all 0.3s ease;
    min-width: 0;
}

#dollar:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2);
}

/* Botón de actualizar */
#uptade {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(76, 175, 80, 0.3);
}

#uptade:hover {
    background-color: #43A047;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.4);
}

#uptade:active {
    transform: translateY(0);
}

/* Icono del botón */
#uptade i {
    font-size: 14px;
}

/* Diseño responsive */
@media (max-width: 480px) {
    form {
        flex-direction: column;
        padding: 20px;
    }
    
    .card-info {
        width: 100%;
        flex-direction: column;
    }
    
    #uptade {
        width: 100%;
        justify-content: center;
    }
}
</style>
<body>
    <div class="dashboard">
        <?=  require_once 'views/inc/heder.php'; ?>

        <!-- Contenido Principal -->
        <main class="main-content">
            <div class="page-header">
                <h1>Panel de Control</h1>
                <h4>Hoy es: <?= APP_Date ?> </h4>
            </div>

            <div class="cards-container">
                <div class="card">
                    <div class="card-icon blue">
                        <i class="fas fa-dollar"></i>
                    </div>
                    <div class="card-info">
                        <h3><?= number_format(APP_Dollar,'2',',','.') ?> Bs</h3>
                        <p>Precio del Dollar</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon blue">
                        <i class="fas fa-dollar"></i>
                    </div>
                    <form method="post">
                        <?php 
                        if(isset($_SESSION['mensaje'])):
                            $tipo = $_SESSION['tipo_mensaje'];
                            $mensaje = $_SESSION['mensaje'];
                            $color = $tipo === 'success' ? '#28a745' : '#dc3545';
                            $icono = $tipo === 'success' ? 'success' : 'error';
                        ?>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: '<?= $icono ?>',
                                title: '<?= addslashes($mensaje) ?>',
                                position: 'top-end',
                                background: '#343a40',
                                color: '#fff',
                                iconColor: '<?= $color ?>',
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                timerProgressBar: true,
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp'
                                }
                            });
                        });
                        </script>
                        <?php 
                            unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
                        endif; 
                        ?>
                        <div class="card-infoo">
                            <input type="text" id="dollar" name="dollar" value="<?= number_format(APP_Dollar,'2') ?>">
                            <button type="submit" id="uptade" name="uptade"> <i class="fas fa-check"></i> Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>