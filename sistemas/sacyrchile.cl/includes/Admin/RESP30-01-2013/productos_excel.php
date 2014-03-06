<? include '../Conexion.php';
$con = Conexion();

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Productos.xls");
header("Pragma: no-cache");
header("Expires: 0");


 $sql = "SELECT p.cod_producto, d.cod_detalle_producto, c.descripcion_cc,b.descripcion_bodega, p.tipo_producto, 
        d.patente, d.producto_arrendado, p.activo_fijo, p.critico, d.especifico,d.estado_producto, p.descripcion,
        d.valor_unitario FROM productos p INNER JOIN detalles_productos d on p.cod_producto = d.cod_producto 
        inner join centros_costos c on d.centro_costo = c.Id_cc inner join bodegas b on d.asignado_a_bodega = b.cod_bodega 
        WHERE p.rut_empresa='".$_POST['rut_empresa']."'";
    
        if(isset($_POST['bodega']) && $_POST['bodega']!="" && $_POST['bodega']!=0){
            $sql.=" and b.cod_bodega=".$_POST['bodega'];
        }
        if(isset($_POST['tipo']) && $_POST['tipo']!="" && $_POST['tipo']!=0){
            $sql.=" and p.tipo_producto=".$_POST['tipo'];
        }    
        if(isset($_POST['cod_barra']) && $_POST['cod_barra']!=""){
            $sql.=" and p.cod_producto=".$_POST['cod_barra'];
        }
        if(isset($_POST['nfactura']) && $_POST['nfactura']!=""){
            $sql.=" and d.num_factura=".$_POST['nfactura'];
        }
        if(isset($_POST['noc']) && $_POST['noc']!=""){
            $sql.=" and d.id_oc=".$_POST['noc'];
        }
        if(isset($_POST['especifico']) && $_POST['especifico']!="" && $_POST['especifico']!=0){
            $sql.=" and d.especifico=".$_POST['especifico'];
        }
        if(isset($_POST['activofijo']) && $_POST['activofijo']!="" && $_POST['activofijo']!=0){
            $sql.=" and p.activo_fijo=".$_POST['activofijo'];
        }
        if(isset($_POST['critico']) && $_POST['critico']!="" && $_POST['critico']!=0){
            $sql.=" and p.critico=".$_POST['critico'];
        }
        if(isset($_POST['arrendado']) && $_POST['arrendado']!="" && $_POST['arrendado']!=0){
            $sql.=" and d.producto_arrendado in (0,".$_POST['arrendado'].")";
        }
        if(isset($_POST['centro_costo']) && $_POST['centro_costo']!=""){
            $sql.=" and centro_costo=".$_POST['centro_costo'];
        }
        if(isset($_POST['patente']) && $_POST['patente']!=""){
            $sql.=" and d.patente like '%".$_POST['patente']."%'";
        }
        
         if(isset($_POST['estado']) && $_POST['estado']!="" && $_POST['estado']!=0){
            $sql.=" and d.estado_producto='".$_POST['estado']."'";
        }
        
        if(isset($_POST['desde']) && $_POST['desde']!="" && $_POST['desde']==""){
              $d = explode("-",$_POST['desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
            $sql .=" and p.fecha_ingreso BETWEEN '".$desde."' and '".date('Y-m-d H:i:s')."'";
          }
          if(isset($_POST['hasta']) && $_POST['hasta']!="" && $_POST['hasta']==""){
              $x = explode("-",$_POST['hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
            $sql .=" and p.fecha_ingreso BETWEEN '2010-01-01 00:00:00' and '".$hasta."'";
          }
          
          if(isset($_POST['desde']) && isset($_POST['hasta']) && $_POST['desde']!="" && $_POST['hasta']!=""){
              $d = explode("-",$_POST['desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
              
              $x = explode("-",$_POST['hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
              $sql .=" and p.fecha_ingreso BETWEEN '".$desde."' and '".$hasta."'";
          }   

?>
<table style="width: 1000px;margin-top:20px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td ><label>Código Barras</label></td>
       <td ><label>Id. Producto</label></td>
       <td ><label>Centro Costos</label></td>
       <td ><label>Bodega</label></td>
       <td ><label>Tipo Producto</label></td>
       <td ><label>Patente</label></td>
       <td ><label>Arrendado</label></td>
       <td ><label>Activo Fijo</label></td>
       <td ><label>Critico</label></td>
       <td ><label>Especifico</label></td>
       <td ><label>Estado</label></td>
       <td ><label>Descripcion</label></td>
       <td ><label>Valor</label></td>
    </tr>
    <? $res = mysql_query($sql,$con);
 
        if(mysql_num_rows($res)!=NULL){
        
        while($row = mysql_fetch_assoc($res)){
            
            switch($row['tipo_producto']){
                    case 1:  $tipo="Maquinarias y Equipos"; break;
                    case 2:  $tipo="Vehiculo Menor"; break;
                    case 3:  $tipo="Herramientas"; break;
                    case 4:  $tipo="Muebles"; break;
                    case 5:  $tipo="Generador"; break;
                    case 6:  $tipo="Plantas"; break;
                    case 7:  $tipo="Equipos de Tunel"; break;
                    case 8:  $tipo="Otros"; break;
                }
                
                
                switch($row['producto_arrendado']){
                    case 0:  $arrendado="No"; break;
                    case 1:  $arrendado="Si"; break;
                    case 2:  $arrendado="No"; break;
                    }
                switch($row['activo_fijo']){
                    case 1:  $activo_fijo="Si"; break;
                    case 2:  $activo_fijo="No"; break;
                    }
                switch($row['critico']){
                    case 1:  $critico="Si"; break;
                    case 2:  $critico="No"; break;
                    }
                switch($row['especifico']){
                    case 1:  $especifico="Si"; break;
                    case 2:  $especifico="No"; break;
                    }    
                switch($row['estado_producto']){
                    case 1:  $estado="Activo"; break;
                    case 2:  $estado="Inactivo"; break;
                    }        
                
        ?>
    <tr class="listado_datos">
        <td><?=$row['cod_producto'];?></td>
        <td><?=$row['cod_detalle_producto'];?></td>
        <td><?=$row['descripcion_cc'];?></td>
        <td><?=$row['descripcion_bodega'];?></td>
        <td><?=$tipo;?></td>
        <td><?=$row['patente'];?></td>
        <td><?=$arrendado;?></td>
        <td><?=$activo_fijo;?></td>
        <td><?=$critico;?></td>
        <td><?=$especifico;?></td>
        <td><?=$estado;?></td>
        <td><?=$row['descripcion'];?></td>
        <td><?=$row['valor_unitario'];?></td>
    </tr>
    <? } ?>
 <? }else{ ?>
     <tr  id="mensaje-sin-reg"><td colspan="13">No existen Registros con las caracteristicas seleccionadas</td></tr>
     <? } ?>
     
</table> 