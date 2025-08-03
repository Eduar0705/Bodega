<?php
require_once __DIR__ . '/../model/Conexion.php';
require_once __DIR__ . '/../model/Inicio.php';
class InicioController
{
    public $modelo;
    public function __construct()
    {
        $this->modelo = new Inicio();
    }
    public function home() {
        require_once 'views/home/index.php';
    }
    public function login() {
        require_once 'views/auth/login.php';
    }
    public function loginAuthenticate() {
        if(isset($_POST['init'])){
            if(strlen($_POST['user']) >= 3 && strlen($_POST['password']) >= 3) {
                $usuario = trim($_POST['user']);
                $password = trim($_POST['password']);
                $this->modelo->loginAuthenticate($usuario, $password);
            }
            else{
                echo "
                <script>alert('Los campos debe ser mayores a 3 digitos');
                setTimeout(()=>{
                    window.location.href = './'
                },500)
                </script>";
            }
        }
    }
}