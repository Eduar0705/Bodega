<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'APP' ?> - <?= $titulo ?? 'Proveedores' ?></title>
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <?= include_once 'views/inc/heder.php' ?>

        <main class="main-content">
            <h1><?= $titulo ?? 'Proveedores' ?></h1>
            <p>Gestión de proveedores próximamente...</p>
        </main>
    </div>
</body>
</html>