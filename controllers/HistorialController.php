<?php
class HistorialController extends AdminController
{
    private $historial;
    public function __construct()
    {
        parent::__construct();
        $this->historial = new Historial();
    }
//Funciones de Historial de ventas
    public function home(){
        $this->validarSesion();
        $titulo = 'Historial de venta';
        $historial = $this->historial->obtenerHistorial();
        require_once 'views/historial/index.php';
    }
}