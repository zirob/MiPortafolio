<?php
include '../Conexion.php';
$con = Conexion();

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=OC.xls");
header("Pragma: no-cache");
header("Expires: 0");


 $sql = "SELECT * FROM cabecera_oc WHERE rut_empresa='".$_POST['rut_empresa']."' and estado_oc=".$_POST['estado'];
            
        
           
            
        
          
            
          if($_POST['id_oc']!=""){
            $sql .=" and id_oc='".$_POST['id_oc']."'"; 
          }
          if($_POST['id_solic']!=""){
            $sql .=" and id_solicitud_compra='".$_POST['id_solic']."'";
          }
          if($_POST['centro_costo']!="" && $_POST['centro_costo']!=0){
            $sql .=" and centro_costos='".$_POST['centro_costo']."'";
          }
          if($_POST['rut_proveedor']!="" && count($_POST['rut_proveedor'])>5){
              $rut = str_replace(".","",$_POST['rut_proveedor']);
            $sql .=" and rut_proveedor='".$rut."'";
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
       <td ><label>Id O.C.</label></td>
       <td ><label>Id Sol. Compra</label></td>
       <td ><label>Fecha O.C</label></td>
       <td ><label>Estado O.C</label></td>
       <td ><label>Centro Costos</label></td>
       <td ><label>Proveedor</label></td>
       <td ><label>Descripcion Compra</label></td>
       <td ><label>Total O.C</label></td>
       <td ><label>Fecha Entrega</label></td>
   </tr>
   <? 
        if(mysql_num_rows($res)!= NULL){
            
            while($row = mysql_fetch_assoc($res)){
                
                switch($row['estado_oc']){
         case 1:
             $estado = "Abierto";
             break;
         case 2: 
             $estado = "Aprovado";
             break;
         case 3:
             $estado = "Pendiente";
             break;
         case 4: 
             $estado = "Cerrada";
             break;
     }
     
     switch($row['concepto']){
         case 1:
             $concepto = "Compra";
             break;
         case 2: 
             $concepto = "Mantenci&oacute;n";
             break;
         case 3:
             $concepto = "Reparaci&oacute;n";
             break;
     }
     
     $cc = "SELECT * FROM centros_costos WHERE Id_cc=".$row['centro_costos'];
                $sc =mysql_query($cc,$con);
                $rc = mysql_fetch_assoc($sc);
     
     
     ?>    
    <tr class="listado_datos">
        <td><?=$row['id_oc'];?></td>
        <td><?=$row['id_solicitud_compra'];?></td>
        <td><?=date("d-m-Y",strtotime($row['fecha_oc']));?></td>
        <td><?=$estado;?></td>
        <td><?=$rc['descripcion_cc'];?></td>
        <td><?=$row['rut_proveedor'];?></td>
        <td><?=$row['observaciones'];?></td>
        <td><?=$row['total'];?></td>
       
        <td><?=date("d-m-Y",strtotime($row['fecha_entrega']));?></td>
    </tr>

<?          
            }
            
     }else{ ?>
   <tr id="mensaje-sin-reg">
       <td colspan="9">No existen Registros con los Datos Seleccionados</td>
   </tr>
   <? 
     
   }     


?>
</table>