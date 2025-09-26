<?php
include_once './model/conexion.php';

function obtenerDatos(){
    $conexion = new BaseDatos();
    $db = $conexion->conectar();

    $config = [
        'NombreApp' => '',
        'Clave' => '',
        'precio_dollar' => ''
    ];
    $sql = "SELECT claveSuper, NombreAPP, precio_dollar FROM `admin` LIMIT 1";
    $resul = mysqli_query($db, $sql);

    if(!$resul){
        die('La conexion no fue exitosa');
    }

    if($row = mysqli_fetch_assoc($resul)){
        $config['Clave'] = $row['claveSuper'];
        $config['NombreApp'] = $row['NombreAPP'];
        $config['precio_dollar'] = $row['precio_dollar'];
    }
    mysqli_free_result($resul);
    return $config;
}

$conf = obtenerDatos();
define('APP_NAME', $conf['NombreApp']);
define('APP_Date',date('d/m/y'));
define('APP_Password', $conf['Clave']);
define('APP_Dollar',$conf['precio_dollar']);
define('APP_Logo', 'public/img/logo2.png');
?>