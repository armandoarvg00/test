<?php
include_once 'Catalogo.class.php';
class Inventario_Almacen {
    private $id_inventario;
    private $id_articulo;
    private $id_articulo_sku;
    private $id_almacen;
    private $id_area_almacen;
    
    private $id_posicion;
    
    private $cantidad_existencia;
    private $UsuarioCreacion;
    private $FechaCreacion;
    private $UsuarioUltimaModificacion;
    private $Pantalla;
    
    private $id_detalle_ot;
    private $entrada_salida;
    private $existencia_inventario;
    
    private $id_OT;
    
    private $tabla = "det_inventario";
    private $nombreId = "id_inventario";
    private $tabla2 = "det_movimiento_almacen";
    
    public function getRegistroInventarioById($id){
        $catalogo = new Catalogo();
        $consulta = "SELECT * FROM $this->tabla WHERE $this->nombreId = $id";
        $result = $catalogo->obtenerLista($consulta);
        while($rs = mysql_fetch_array($result)){
            $this->id_inventario = $rs['id_inventario'];
            $this->id_articulo = $rs['id_articulo'];
            $this->id_articulo_sku = $rs['id_articulo_sku'];
            $this->id_almacen = $rs['id_almacen'];
            $this->id_area_almacen = $rs['id_area_almacen'];
            $this->cantidad_existencia = $rs['cantidad_existencia'];
            $this->id_posicion = $rs['id_posicion'];
            return true;
        }
        return false;
    }
    
    public function getRegistroByUPCSKUAlmacenArea($upc, $sku, $almacen, $areaAlm, $posAlmacen){
        $where = "";
        $wherePosi = "";
        if($sku != ""){
            $where = "OR id_articulo_sku = $sku";
        }
        
        if($posAlmacen != ""){
            $wherePosi = " AND id_posicion = $posAlmacen";
        }
        
        $catalogo = new Catalogo();
        $consulta = "SELECT * FROM $this->tabla WHERE (id_articulo = $upc $where ) AND id_almacen = $almacen AND id_area_almacen = $areaAlm $wherePosi";
        //echo "<br>Consulta de las existencias del inventario "
       // . $consulta
        //. "<br>";
        
        $result = $catalogo->obtenerLista($consulta);
        while($rs = mysql_fetch_array($result)){
            $this->id_inventario = $rs['id_inventario'];
            $this->id_articulo = $rs['id_articulo'];
            $this->id_articulo_sku = $rs['id_articulo_sku'];
            $this->id_almacen = $rs['id_almacen'];
            $this->id_area_almacen = $rs['id_area_almacen'];
            $this->cantidad_existencia = $rs['cantidad_existencia'];
          //  echo $rs['cantidad_existencia'];
            return true;
        }
        return false;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function newRegistroInventario(){
        $catalogo = new Catalogo();
        if(!isset($this->id_articulo_sku) || $this->id_articulo_sku == ""){
            $this->id_articulo_sku = "NULL";
        }
        if(!isset($this->cantidad_existencia) || $this->cantidad_existencia == ""){
            $this->cantidad_existencia = "0";
        }
        $consulta = "INSERT INTO $this->tabla (id_articulo, id_articulo_sku, id_almacen, id_area_almacen, cantidad_existencia, UsuarioCreacion, 
        FechaCreacion, UsuarioUltimaModificacion, FechaUltimaModificacion, Pantalla) VALUES ($this->id_articulo, $this->id_articulo_sku, $this->id_almacen,
        $this->id_area_almacen, $this->cantidad_existencia, '$this->UsuarioCreacion', NOW(), '$this->UsuarioUltimaModificacion', NOW(), '$this->Pantalla')";
        
        
        //echo "<br><br>Se inserta un registro ".$consulta;
        
        
        $this->id_inventario = $catalogo->insertarRegistro($consulta);
        if($this->id_inventario != null && $this->id_inventario != 0){
            return true;
        }
        return false;
    }
    
    public function editRegistroInventario(){
        $catalogo = new Catalogo();
        if(!isset($this->id_articulo_sku) || $this->id_articulo_sku == ""){
            $this->id_articulo_sku = "NULL";
        }
        if(!isset($this->cantidad_existencia) || $this->cantidad_existencia == ""){
            $this->cantidad_existencia = "0";
        }
        $where = "$this->nombreId = $this->id_inventario";
        $consulta = "UPDATE $this->tabla SET id_articulo = $this->id_articulo, id_articulo_sku = $this->id_articulo_sku, id_almacen = $this->id_almacen, 
        id_area_almacen = $this->id_area_almacen, cantidad_existencia = $this->cantidad_existencia, UsuarioUltimaModificacion = '$this->UsuarioUltimaModificacion', 
        FechaUltimaModificacion = NOW(), Pantalla = '$this->Pantalla' WHERE $where";
        
        
        //echo "<br><br>Se actualiza el inventario ".$consulta;
        $result = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function deleteRegistroInventario(){
        $catalogo = new Catalogo();
        $where = "$this->nombreId = $this->id_inventario";
        $consulta = "DELETE FROM $this->tabla WHERE $where";
        $result = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla, $where);
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function newRegistroMovimientoAlmacen(){
        $catalogo = new Catalogo();
        $consulta = "INSERT INTO $this->tabla2 (id_inventario, id_detalle_ot, entrada_salida, existencia_inventario, UsuarioCreacion, FechaCreacion,
        UsuarioUltimaModificacion, FechaUltimaModificacion, Pantalla) VALUES($this->id_inventario, $this->id_detalle_ot, $this->entrada_salida, 
        $this->existencia_inventario, '$this->UsuarioCreacion', NOW(), '$this->UsuarioUltimaModificacion', NOW(),'$this->Pantalla')";
        $result = $catalogo->obtenerLista($consulta);
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function editRegistroMovimientoAlmacen(){
        $catalogo = new Catalogo();
        $where = "id_detalle_ot = $this->id_detalle_ot";
        $consulta = "UPDATE $this->tabla2 SET id_inventario = $this->id_inventario, entrada_salida = $this->entrada_salida, existencia_inventario = $this->existencia_inventario, 
        UsuarioUltimaModificacion = '$this->UsuarioUltimaModificacion', FechaUltimaModificacion = NOW(), Pantalla = '$this->Pantalla' WHERE $where";
        $result = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla2, $where);
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function getRegistroMovimientoAlmacen($id){
        $catalogo = new Catalogo();
        $consulta = "SELECT * FROM $this->tabla2 WHERE id_detalle_ot = $id";
        //echo "<br>Esta consulta es de Inventario_almacen.class funcion getRegistroMovimientoAlmacen ".$consulta;
        $result = $catalogo->obtenerLista($consulta);
        while($rs = mysql_fetch_array($result)){
            $this->id_inventario = $rs['id_inventario'];
            $this->id_detalle_ot = $rs['id_detalle_ot'];
            $this->entrada_salida = $rs['entrada_salida'];
            $this->existencia_inventario = $rs['existencia_inventario'];
            //$this->getRegistroInventarioById($rs['id_inventario']);
            return true;
        }
        return false;
    }
    
    public function deleteMovimientoAlmacen(){
        $catalogo = new Catalogo();
        $where = "";
        if($this->id_detalle_ot != ""){
            $where = "id_detalle_ot = $this->id_detalle_ot";
        }else{
            $where = "id_detalle_ot IN ((SELECT id_detalle_ot FROM det_orden_trabajo WHERE folio_orden_trabajo = '$this->id_OT'))";
        }
        $consulta = "DELETE FROM $this->tabla2 WHERE $where";
//        echo "$consulta<br>";
        $result = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla2, $where);
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function deleteOnCascade($arrayIdDetOT){
        $catalogo = new Catalogo();
        $where = "";
        if($arrayIdDetOT != ""){
            $where = "dma.id_detalle_ot IN ($arrayIdDetOT)";
        }else{
            $where = "dot.folio_orden_trabajo = '$this->id_OT'";
        }
        $consulta = "SELECT dma.id_detalle_ot, i.id_inventario, IF(dma.entrada_salida = 1, i.cantidad_existencia - dot.cantidad, i.cantidad_existencia + 
        dot.cantidad) AS nuevoMonto, i.id_articulo, i.id_articulo_sku, i.id_almacen, i.id_area_almacen FROM det_movimiento_almacen dma INNER JOIN 
        det_orden_trabajo dot ON dot.id_detalle_ot = dma.id_detalle_ot INNER JOIN det_inventario i ON i.id_inventario = dma.id_inventario WHERE $where";
//        echo $consulta;
        $result = $catalogo->obtenerLista($consulta);
        $return = true;
        
        while($rs = mysql_fetch_array($result)){            
            $this->id_inventario = $rs['id_inventario'];
            $this->id_detalle_ot = $rs['id_detalle_ot'];
            $this->cantidad_existencia = $rs['nuevoMonto'];
            $this->id_articulo = $rs['id_articulo'];
            $this->id_articulo_sku = $rs['id_articulo_sku'];
            $this->id_almacen = $rs['id_almacen'];
            $this->id_area_almacen = $rs['id_area_almacen'];
            //echo "$this->id_inventario: $this->id_detalle_ot<br>";
            //Primero editamos la cantidad del inventario
            if($this->editRegistroInventario()){
                $resultado = $this->deleteMovimientoAlmacen();
                if($return && $resultado === false){
                    $return = false;
                }
            }
        }
        return $return;
    }
    
    public function deleteMovimientoAlmacenByIdInventario(){
        $catalogo = new Catalogo();
        $where = "id_inventario = $this->id_inventario";
        $consulta = "DELETE FROM $this->tabla2 WHERE $where";
        $result = $catalogo->ejecutaConsultaActualizacion($consulta, $this->tabla2, $where);
        if($result == 1){
            return true;
        }
        return false;
    }

    function getId_inventario() {
        return $this->id_inventario;
    }

    function getId_articulo() {
        return $this->id_articulo;
    }

    function getId_articulo_sku() {
        return $this->id_articulo_sku;
    }

    function getId_almacen() {
        return $this->id_almacen;
    }

    function getId_area_almacen() {
        return $this->id_area_almacen;
    }

    function getCantidad_existencia() {
        return $this->cantidad_existencia;
    }

    function getUsuarioCreacion() {
        return $this->UsuarioCreacion;
    }

    function getFechaCreacion() {
        return $this->FechaCreacion;
    }

    function getUsuarioUltimaModificacion() {
        return $this->UsuarioUltimaModificacion;
    }

    function getId_detalle_ot() {
        return $this->id_detalle_ot;
    }

    function getEntrada_salida() {
        return $this->entrada_salida;
    }

    function getExistencia_inventario() {
        return $this->existencia_inventario;
    }

    function setId_inventario($id_inventario) {
        $this->id_inventario = $id_inventario;
    }

    function setId_articulo($id_articulo) {
        $this->id_articulo = $id_articulo;
    }

    function setId_articulo_sku($id_articulo_sku) {
        $this->id_articulo_sku = $id_articulo_sku;
    }

    function setId_almacen($id_almacen) {
        $this->id_almacen = $id_almacen;
    }

    function setId_area_almacen($id_area_almacen) {
        $this->id_area_almacen = $id_area_almacen;
    }

    function setCantidad_existencia($cantidad_existencia) {
        $this->cantidad_existencia = $cantidad_existencia;
    }

    function setUsuarioCreacion($UsuarioCreacion) {
        $this->UsuarioCreacion = $UsuarioCreacion;
    }

    function setFechaCreacion($FechaCreacion) {
        $this->FechaCreacion = $FechaCreacion;
    }

    function setUsuarioUltimaModificacion($UsuarioUltimaModificacion) {
        $this->UsuarioUltimaModificacion = $UsuarioUltimaModificacion;
    }

    function setId_detalle_ot($id_detalle_ot) {
        $this->id_detalle_ot = $id_detalle_ot;
    }

    function setEntrada_salida($entrada_salida) {
        $this->entrada_salida = $entrada_salida;
    }

    function setExistencia_inventario($existencia_inventario) {
        $this->existencia_inventario = $existencia_inventario;
    }
    
    function getPantalla() {
        return $this->Pantalla;
    }

    function setPantalla($Pantalla) {
        $this->Pantalla = $Pantalla;
    }
    
    function setId_OT($id_OT) {
        $this->id_OT = $id_OT;
    }
    
    
    
    
    function getId_posicion() {
        return $this->id_posicion;
    }

    function setId_posicion($id_posicion) {
        $this->id_posicion = $id_posicion;
    }


    
    
    
    
}
