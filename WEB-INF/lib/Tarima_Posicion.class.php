<?php
include_once("Catalogo.class.php");

class Tarima_Posicion {

    private $id_tarima_posicio;
    private $numero_tarima;
    private $posicion;
    private $reubicacion;
    private $id_usuario;
    
    private $UsuarioCreacion;
    private $FechaCreacion;
    private $UsuarioUltimaModificacion;
    private $FechaUltimaModificacion;
    private $Pantalla;

    public function getRegistroById($id) {
        $consulta = "SELECT * FROM det_tarima_posicion WHERE id_tarima_posicion = " . $id . "";
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        while ($rs = mysql_fetch_array($query)) {
            $this->numero_tarima = $rs['numero_tarima'];
            $this->posicion = $rs['numero_posicion'];
        }
        return $query;
    }

    public function getRegistroById_tarima($id) {
        if($id != ""){
            $consulta = "SELECT id_tarima_posicion FROM det_tarima_posicion WHERE id_tarima = " . $id . "";
            $catalogo = new Catalogo();
            $query = $catalogo->obtenerLista($consulta);            
            while ($rs = mysql_fetch_array($query)) {
                $this->id_tarima_posicio = $rs['id_tarima_posicion'];
                return true;
            }
        }
        return false;
    }

    public function get_tarimas_articulos($codigo_art, $id_cliente, $id_almacen, $caducidad, $id_area_almacen) {
        $ordenar = "";
        if ($caducidad == "0" || $caducidad == "") {
            $ordenar = "ORDER BY ta.numero_tarima ASC";
        } else {
            $ordenar = "ORDER BY dr.fecha_caducidad ASC";
        }
        $consulta = "SELECT dr.id_detrecepcion,ta.id_tarima,dta.id_det_tarima,ta.numero_tarima,dot.id_detalle_ot,dta.cantidad_cajas -
            IF(ISNULL((SELECT SUM(pk.cantidad_articulos) FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)),0,(SELECT SUM(pk.cantidad_articulos) 
            FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)) AS piezas_tarima FROM cat_tarimas_almacen ta INNER JOIN det_tarimas_almacen dta ON ta.id_tarima=dta.id_tarima 
        INNER JOIN det_orden_trabajo dot ON dta.id_det_ot=dot.id_detalle_ot INNER JOIN cat_orden_trabajo ot ON dot.folio_orden_trabajo=ot.folio_ordentrab 
        INNER JOIN det_recepcion dr ON dot.id_det_recepcion=dr.id_detrecepcion INNER JOIN cat_articulos ar ON dr.id_articulo=ar.id_articulo 
        LEFT JOIN cat_articulo_sku cas ON (cas.IdArticuloSKU = dr.id_articulo_sku AND cas.IdArticulo = ar.id_articulo)
        WHERE (cas.SKU = '$codigo_art' OR ar.articulo_codigo='$codigo_art') AND ot.id_cliente='$id_cliente' AND ot.id_almacen='$id_almacen' AND ot.id_area_almacen = '$id_area_almacen' HAVING piezas_tarima>0 $ordenar";
        //echo "Estasson las tarimas de donde se toma el producto ".$consulta;
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        return $query;
    }
    
    public function get_tarimas_articulos_antes_OT($codigo_art, $id_cliente, $id_almacen, $caducidad, $id_area_almacen, $OT){
        $ordenar = "";
        if ($caducidad == "0" || $caducidad == "") {
            $ordenar = "ORDER BY ta.numero_tarima ASC";
        } else {
            $ordenar = "ORDER BY dr.fecha_caducidad ASC";
        }
        $consulta = "SELECT dr.id_detrecepcion,ta.id_tarima,dta.id_det_tarima,ta.numero_tarima,dot.id_detalle_ot,dta.cantidad_cajas -
        IF(ISNULL((SELECT SUM(pk.cantidad_articulos) FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima AND pk.id_ot != $OT)),0,(SELECT SUM(pk.cantidad_articulos) 
        FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)) AS piezas_tarima FROM cat_tarimas_almacen ta INNER JOIN det_tarimas_almacen dta ON ta.id_tarima=dta.id_tarima 
        INNER JOIN det_orden_trabajo dot ON dta.id_det_ot=dot.id_detalle_ot INNER JOIN cat_orden_trabajo ot ON dot.folio_orden_trabajo=ot.folio_ordentrab 
        INNER JOIN det_recepcion dr ON dot.id_det_recepcion=dr.id_detrecepcion INNER JOIN cat_articulos ar ON dr.id_articulo=ar.id_articulo 
        LEFT JOIN cat_articulo_sku cas ON (cas.IdArticuloSKU = dr.id_articulo_sku AND cas.IdArticulo = ar.id_articulo)
        WHERE (cas.SKU = '$codigo_art' OR ar.articulo_codigo='$codigo_art') AND ot.id_cliente='$id_cliente' AND ot.id_almacen='$id_almacen' AND ot.id_area_almacen = '$id_area_almacen' HAVING piezas_tarima>0 $ordenar";
        // echo $consulta;
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        return $query;
    }

    public function get_inners_tarimas($codigo_art, $id_cliente, $id_almacen, $id_area_almacen) {
        $consulta = "SELECT (dta.cantidad_cajas - IF(ISNULL((SELECT SUM(pk.cantidad_articulos) FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)),
        0,(SELECT SUM(pk.cantidad_articulos) FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)))/ar.inner_pack AS cantidad_inner FROM 
        cat_tarimas_almacen ta INNER JOIN det_tarimas_almacen dta ON ta.id_tarima=dta.id_tarima INNER JOIN det_orden_trabajo dot ON dta.id_det_ot = 
        dot.id_detalle_ot INNER JOIN cat_orden_trabajo ot ON dot.folio_orden_trabajo=ot.folio_ordentrab INNER JOIN det_recepcion dr ON dot.id_det_recepcion=
        dr.id_detrecepcion INNER JOIN cat_articulos ar ON dr.id_articulo=ar.id_articulo LEFT JOIN cat_articulo_sku cas ON (cas.IdArticuloSKU = dr.id_articulo_sku
        AND cas.IdArticulo = ar.id_articulo) WHERE (cas.SKU = '$codigo_art' OR ar.articulo_codigo='$codigo_art') AND ot.id_cliente='$id_cliente' AND 
        ot.id_almacen='$id_almacen' AND ot.id_area_almacen = '$id_area_almacen' HAVING cantidad_inner>0";
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        $total = 0;
        while ($rs = mysql_fetch_array($query)) {
            $total = $total + $rs['cantidad_inner'];
        }
        return $total;
    }

    public function get_tarimas_articulos_import($codigo_art, $id_cliente, $id_almacen) {
        $consulta = "SELECT dr.id_detrecepcion,ta.id_tarima,dta.id_det_tarima,ta.numero_tarima,dot.id_detalle_ot,dta.cantidad_cajas -
            IF(ISNULL((SELECT SUM(pk.cantidad_articulos) FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)),0,(SELECT SUM(pk.cantidad_articulos) 
            FROM det_picking pk WHERE pk.id_det_tarima=dta.id_det_tarima)) AS piezas_tarima FROM cat_tarimas_almacen ta INNER JOIN det_tarimas_almacen dta ON ta.id_tarima=dta.id_tarima 
        INNER JOIN det_orden_trabajo dot ON dta.id_det_ot=dot.id_detalle_ot INNER JOIN cat_orden_trabajo ot ON dot.folio_orden_trabajo=ot.folio_ordentrab 
        INNER JOIN det_recepcion dr ON dot.id_det_recepcion=dr.id_detrecepcion INNER JOIN cat_articulos ar ON dr.id_articulo=ar.id_articulo 
        WHERE ar.articulo_codigo='$codigo_art' AND ot.id_cliente='$id_cliente' AND ot.id_almacen='$id_almacen' HAVING piezas_tarima>0 ORDER BY ta.id_tarima  ASC";
        //  echo $consulta;
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        return $query;
    }

    public function newRegistro() {
        $consulta = "INSERT INTO det_tarima_posicion(id_tarima, id_posicion, fecha_posicion, reubicacion, UsuarioCreacion, FechaCreacion, 
        UsuarioUltimaModificacion, FechaUltimaModificacion, Pantalla, id_usuario) VALUES($this->numero_tarima, $this->posicion, now(), $this->reubicacion, 
        '$this->UsuarioCreacion', NOW(), '$this->UsuarioUltimaModificacion', NOW(), '$this->Pantalla', $this->id_usuario);";
        $catalogo = new Catalogo();
        //echo $consulta;
        $this->id_tarima_posicio = $catalogo->insertarRegistro($consulta);
        if ($this->id_tarima_posicio != null && $this->id_tarima_posicio != 0) {
            return true;
        }        
        return false;
    }

    public function editRegistro() {
        $where = "id_tarima_posicion = " . $this->id_tarima_posicio;
        $consulta = "UPDATE det_tarima_posicion SET id_posicion = $this->posicion, fecha_posicion = now(), UsuarioUltimaModificacion = 
        '$this->UsuarioUltimaModificacion', FechaUltimaModificacion = NOW(), Pantalla = '$this->Pantalla', id_usuario = $this->id_usuario WHERE $where";
        $catalogo = new Catalogo();
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, "det_tarima_posicion", $where);
        if ($query == 1) {
            return true;
        }        
        return false;
    }

    public function deleteRegistro() {
        $where = "id_tarima_posicion = " . $this->id_tarima_posicio;
        $consulta = "DELETE FROM det_tarima_posicion WHERE $where";
        $catalogo = new Catalogo();
        $query = $catalogo->ejecutaConsultaActualizacion($consulta, "det_tarima_posicion", $where);
        if ($query == 1) {
            return true;
        }        
        return false;
    }
    
    public function deleteRegistroByIdOTTarima($idOT){
        $catalogo = new Catalogo();
        $where = "id_tarima IN (SELECT id_tarima FROM cat_tarimas_almacen WHERE id_ot = $idOT)";
        $consulta = "DELETE FROM det_tarima_posicion WHERE $where";
        //echo $consulta;
        $result = $catalogo->ejecutaConsultaActualizacion($consulta, "det_tarima_posicion", $where);
        if($result == 1){
            return true;
        }
        return false;
    }

    public function cantidad_articulo($array, $id_cliente) {
        $array_datos = array();
        $x = 0;
        foreach ($array AS $valor) {
            $bol = 0;
            if ($valor[0] == "D") {
                if (empty($array_datos)) {
                    $array_datos[0][0] = $valor[4];
                    $array_datos[0][1] = number_format($valor[5]);
                    $x++;
                } else {
                    for ($i = 0; $i < count($array_datos); $i++) {
                        if ($valor[4] == $array_datos[$i][0]) {
                            $bol = 1;
                            $cantidad_pzas = $array_datos[$i][1] + number_format($valor[5]);
                            $array_datos[$i][1] = $cantidad_pzas;
                            break;
                        } else {
                            $bol = 0;
                        }
                    }
                    if ($bol == 0) {
                        $array_datos[$x][0] = $valor[4];
                        $array_datos[$x][1] = number_format($valor[5]);
                        $x++;
                    }
                }
            }
        }

        return $this->get_inventario_sku($array_datos, $id_cliente);
    }

    public function get_inventario_sku($array_datos, $id_cliente) {
        $array_sku_sin_inv = array();
        //$array_result = [];
        $array_result = $this->inventario_skus($array_datos, $id_cliente);
        /*foreach ($array_datos AS $valor) {
            if ($this->inventario_sku($valor[0], $id_cliente) < $valor[1]) {
                array_push($array_sku_sin_inv, $valor[0]);
            }
        }*/
        //echo("$array_result[0], $array_result[1], $array_result[2]");
        //$i = 0;
        //echo("<br>SKU | query | arreglo_datos <br>");
        if(!empty($array_result)){
            foreach ($array_datos AS $valor) {
                //if($array_result[$i] < $valor[1]){
                //    array_push($array_sku_sin_inv, $valor[0]);
                //    echo($valor[0]. "-" . $valor[1] . " - " .$array_result[$i] . "<br>");
                //}
                //echo("<br>");
                //echo($valor[0] . " | " . $array_result[$valor[0]] . " | " . $valor[1]);
                if($array_result[$valor[0]] < $valor[1]){
                    array_push($array_sku_sin_inv, $valor[0]);
                }
                //$i++;
            } 
        }
        //echo("<br>");
        return $array_sku_sin_inv;
    }
    
    public function inventario_skus($array_datos, $cliente){
        $array = "";
        $array_result = array();
        //echo("Elementos en el arreglo: ");
        foreach($array_datos AS $valor){
            $array .= "'$valor[0]',";
        }
        $array_sku= trim($array, ",");
        $consulta = "SELECT ar.articulo_codigo AS sku,
        SUM((dta.cantidad_cajas-(SELECT IF(ISNULL(SUM(IF(ISNULL(dpk.cantidad_articulos),0,dpk.cantidad_articulos))),0,SUM(IF(ISNULL(dpk.cantidad_articulos),0,dpk.cantidad_articulos))) AS surtida
        FROM det_picking dpk WHERE dpk.id_det_tarima=dta.id_det_tarima ))) AS piezas
        FROM cat_tarimas_almacen cta INNER JOIN det_tarimas_almacen dta ON cta.id_tarima=dta.id_tarima INNER JOIN det_orden_trabajo dot ON dot.id_detalle_ot=dta.id_det_ot
        INNER JOIN det_recepcion dr ON dot.id_det_recepcion=dr.id_detrecepcion INNER JOIN cat_articulos ar ON ar.id_articulo=dr.id_articulo INNER JOIN cat_orden_trabajo ot ON dot.folio_orden_trabajo=ot.folio_ordentrab
        INNER JOIN cat_almacen al ON ot.id_almacen=al.id_almacen INNER JOIN cat_clientes cl ON ot.id_cliente=cl.id_cliente INNER JOIN cat_recepcion r ON dr.id_folio_recepcion=r.id_folio_recepcion
        LEFT JOIN cat_orden_compra oc ON r.orden_compra=oc.id_orden_compra  WHERE ot.id_cliente IN ($cliente) AND ar.articulo_codigo IN ($array_sku) GROUP BY ar.articulo_codigo";
        //echo("<br>Consulta: " . $consulta);
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        while($rs = mysql_fetch_array($query)){
            $array_result[$rs['sku']]= $rs['piezas']; 
        }
        //echo("<br>");
        print_r($array_result);
        return $array_result;        
    }

    public function inventario_sku($sku, $cliente) {
        $inv_pzs = 0;
        $consulta = "SELECT ar.articulo_codigo AS sku,
                SUM((dta.cantidad_cajas-(SELECT IF(ISNULL(SUM(IF(ISNULL(dpk.cantidad_articulos),0,dpk.cantidad_articulos))),0,SUM(IF(ISNULL(dpk.cantidad_articulos),0,dpk.cantidad_articulos))) AS surtida
                FROM det_picking dpk WHERE dpk.id_det_tarima=dta.id_det_tarima ))) AS piezas
               	FROM cat_tarimas_almacen cta INNER JOIN det_tarimas_almacen dta ON cta.id_tarima=dta.id_tarima INNER JOIN det_orden_trabajo dot ON dot.id_detalle_ot=dta.id_det_ot
                INNER JOIN det_recepcion dr ON dot.id_det_recepcion=dr.id_detrecepcion INNER JOIN cat_articulos ar ON ar.id_articulo=dr.id_articulo INNER JOIN cat_orden_trabajo ot ON dot.folio_orden_trabajo=ot.folio_ordentrab
                INNER JOIN cat_almacen al ON ot.id_almacen=al.id_almacen INNER JOIN cat_clientes cl ON ot.id_cliente=cl.id_cliente INNER JOIN cat_recepcion r ON dr.id_folio_recepcion=r.id_folio_recepcion
                LEFT JOIN cat_orden_compra oc ON r.orden_compra=oc.id_orden_compra  WHERE ot.id_cliente IN ($cliente) AND ar.articulo_codigo='$sku' GROUP BY ar.articulo_codigo HAVING piezas>0  ORDER BY ot.folio_ordentrab ASC";
        $catalogo = new Catalogo();
        $query = $catalogo->obtenerLista($consulta);        
        while ($rs = mysql_fetch_array($query)) {
            $inv_pzs = $rs['piezas'];
        }
        if ($inv_pzs == "") {
            $inv_pzs = 0;
        }
        return $inv_pzs;
    }
    
    function getRegistroByIdPosicion($IdPosicionAlmacen){
        $catalogo = new Catalogo();
        $consulta = "SELECT * FROM det_tarima_posicion WHERE id_posicion = $IdPosicionAlmacen";
        $result = $catalogo->obtenerLista($consulta);
        while($rs = mysql_fetch_array($result)){
            $this->id_tarima_posicio = $rs['id_tarima_posicion'];
            $this->numero_tarima = $rs['id_tarima'];
            $this->posicion = $rs['id_posicion'];
            $this->reubicacion = $rs['reubicacion'];
            return true;
        }
        return false;
    }

    function getId_tarima_posicio() {
        return $this->id_tarima_posicio;
    }

    function getNumero_tarima() {
        return $this->numero_tarima;
    }

    function getPosicion() {
        return $this->posicion;
    }

    function getReubicacion() {
        return $this->reubicacion;
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

    function getFechaUltimaModificacion() {
        return $this->FechaUltimaModificacion;
    }

    function getPantalla() {
        return $this->Pantalla;
    }

    function setId_tarima_posicio($id_tarima_posicio) {
        $this->id_tarima_posicio = $id_tarima_posicio;
    }

    function setNumero_tarima($numero_tarima) {
        $this->numero_tarima = $numero_tarima;
    }

    function setPosicion($posicion) {
        $this->posicion = $posicion;
    }

    function setReubicacion($reubicacion) {
        $this->reubicacion = $reubicacion;
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

    function setFechaUltimaModificacion($FechaUltimaModificacion) {
        $this->FechaUltimaModificacion = $FechaUltimaModificacion;
    }

    function setPantalla($Pantalla) {
        $this->Pantalla = $Pantalla;
    }

    function getId_usuario() {
        return $this->id_usuario;
    }

    function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }
}
