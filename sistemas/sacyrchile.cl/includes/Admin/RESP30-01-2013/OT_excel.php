<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=OT.xls");
header("Pragma: no-cache");
header("Expires: 0");



$sql ="SELECT c.id_ot, c.fecha_ingreso, c.estado_ot, cc.descripcion_cc, pp.tipo_producto, p.patente, c.descripcion_ot, SUM( d.horas_hombre ) AS horas_hombre
                    FROM detalles_productos p, cabeceras_ot c, detalle_ot d,centros_costos cc, bodegas b, productos pp WHERE c.rut_empresa='".$_POST['rut_empresa']."' ";
           
        if($_POST['bodega']==0){
            
            $sql.="";
        }else{
            $sql.=" and b.cod_bodega=".$_POST['bodega'];
        }
          
        
         if($_POST['estado']!=""){
            $sql .=" and c.estado_ot='".$_POST['estado']."'"; 
          }
            
          if($_POST['id_ot']!=""){
            $sql .=" and c.id_ot='".$_POST['id_ot']."'"; 
          }
          if($_POST['centro_costo']!=""){
            $sql .=" and c.centro_costos='".$_POST['centro_costo']."'";
          }
          if($_POST['patente']!=""){
            $sql .=" and p.patente='".$_POST['patente']."'";
          }
          if($_POST['cod_producto']!=""){
            $sql .=" and pp.cod_producto='".$_POST['cod_producto']."'";
          }
          if($_POST['fecha_desde']!="" && $_POST['fecha_hasta']==""){
              $d = explode("-",$_POST['fecha_desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
            $sql .=" and fecha_oc BETWEEN '".$desde."' and '".date('Y-m-d H:i:s')."'";
          }
          if($_POST['fecha_hasta']!="" && $_POST['fecha_desde']==""){
              $x = explode("-",$_POST['fecha_hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
            $sql .=" and fecha_oc BETWEEN '2010-01-01 00:00:00' and '".$hasta."'";
          }
          
          if($_POST['fecha_desde']!="" && $_POST['fecha_hasta']!=""){
              $d = explode("-",$_POST['fecha_desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
              
              $x = explode("-",$_POST['fecha_hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
              $sql .=" and fecha_oc BETWEEN '".$desde."' and '".$hasta."'";
              
              
          }
          
          $res = mysql_query($sql,$con);



?>
<table style="margin-top:20px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td style="width: 50px;"><label>Id O.T.</label></td>
       <td style="width: 100px;"><label>Fecha O.T</label></td>
       <td style="width: 100px;"><label>Estado O.T</label></td>
       <td style="width: 100px;"><label>Centro Costos</label></td>
       <td style="width: 100px;"><label>Producto</label></td>
       <td style="width: 100px;"><label>Patente</label></td>
       <td style="width: 150px;"><label>Descripcion OT</label></td>
       <td style="width: 100px;"><label>Horas Hombre</label></td>
    </tr>
   <? 
        if(mysql_num_rows($res)!= NULL && mysql_num_rows($res)>1){
            while($row = mysql_fetch_assoc($res)){
                
                 switch($row['tipo_producto']){
                    case 1:
                        $tipo="Maquinarias y Equipos"; break;
                    case 2:
                        $tipo="Vehiculo Menor"; break;
                    case 3:
                        $tipo="Herramientas"; break;
                    case 4:
                        $tipo="Muebles"; break;
                    case 5:
                        $tipo="Generador"; break;
                    case 6:
                        $tipo="Plantas"; break;
                    case 7:
                        $tipo="Equipos de Tunel"; break;
                    case 8:
                        $tipo="Otros"; break;
                }
                
                
                ?>

   <tr class="listado_datos">
       <td ><?=$row['id_ot'];?></td>
       <td ><?=$d[2]."-".$d[1]."-".$d[0];?></td>
       <td ><?=$row['estado_ot'];?></td>
       <td ><?=$row['descripcion_cc'];?></td>
       <td ><?=$tipo;?></td>
       <td ><?=$row['patente'];?></td>
       <td ><?=$row['descripcion_ot'];?></td>
       <td ><?=$row['horas_hombre'];?></td>
       
   </tr>

   <?
           }    
   
             
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="9">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }    ?>
   
            
</table>