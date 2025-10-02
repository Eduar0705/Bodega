<?php
class Historial{
    private $db;
    public function __construct(){
        $this->db = (new BaseDatos())->conectar();
    }

    public function obtenerHistorial(){
        $sql = 'SELECT * FROM historial';
        $result = $this->db->query($sql);

        if(!$result){
            return [];
        }

        $info = [];
        while($row = $result->fetch_assoc()){
            $info[] = $row;
        }
        return $info;
    }
    public function addHistorial($cliente, $tipoPago, $tipoVenta, $totalUSD, $listProductos){
        $fecha = date('d/m/y');
        $jsonProductos = json_encode($listProductos);
        $sql = 'INSERT INTO historial(fecha, cliente, tipo_pago, tipo_venta, total_usd, productos_venditos) VALUES ( ?, ?, ?, ?, ?, ?)';
    }
}
?>