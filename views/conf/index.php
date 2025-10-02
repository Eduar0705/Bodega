<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<style>
    .claveMaestra{
        background-color: #5e59590f;
        padding: 20px;
        border-radius: 10px;
    }
    form{
        background-color: #5e595926;
        padding: 15px;
        border-radius: 10px;
        max-width: 400px;
    }
    form button{
        background-color: #3ace3aff;
    }
    .clave{
        padding: 15px;
        border: solid 1px;
        padding: 10px;
        border-radius: 15px;
    }
</style>
<body>
    <div class="dashboard">
        <?= include_once 'views/inc/heder.php'; ?>
        <main class="main-content">
            <div class="page-header">
                <h1><?= $titulo ?></h1>
                <h4>Hoy es: <?= APP_Date ?> </h4>
            </div>
            <section class="claveMaestra">
                <h3>Cambio de clave maestra</h3>
                <form action="" method="post" autocomplete="off">
                    <input type="text" id="clave" name="clave" placeholder="Ingrese la nueva clave maestra">
                    <button type="submit" name="uptade">Actucalizar clave</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>s