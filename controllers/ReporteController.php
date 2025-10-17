<?php
class ReporteController extends AdminController
{
   //Funciones de estadisticas
    public function home(){
        $this->validarSesion();
        $titulo = 'Estadisticas';
        require_once 'views/estadisticas/index.php';
    }
}