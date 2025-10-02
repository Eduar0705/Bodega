<?php
class Ccobrar{
    private $bd;
    public function __construct(){
        $this->bd = (new BaseDatos())->conectar();
    }

    public function obtenerCC(){
        $sql = "SELECT * FROM cuentascobrar";
        $result = $this->bd->query($sql);

        if(!$result){
            return [];
        }

        $infor = [];
        while($row = $result->fetch_assoc()){
            $infor[] = $row;
        }
        return $infor;
    }
}