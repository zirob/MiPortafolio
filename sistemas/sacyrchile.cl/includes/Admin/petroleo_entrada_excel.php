<?php
include '../Conexion.php';
$con = Conexion();

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Entrada_Petroleo.xls");
header("Pragma: no-cache");
header("Expires: 0");

            
          if($_POST['estado']!=""){
                $sql = "SELECT * FROM cabecera_oc WHERE rut_empresa='".$_POST['rut_empresa']."' and estado_oc=".$_POST['estado'];
            }else{
                $sql = "SELECT * FROM cabecera_oc WHERE estado_oc in (1,2,3,4,5) and rut_empresa='".$_POST['rut_empresa']."'";
            }
        
          
            
          if($_POST['id_oc']!=""){
            $sql .=" and id_oc='".$_POST['id_oc']."'"; 
          }
          if($_POST['id_solic']!=""){
            $sql .=" and id_solicitud_compra='".$_POST['id_solic']."'";
          }
          if($_POST['centro_costo']!="" && $_POST['centro_costo']!=0){
            $sql .=" and centro_costos='".$_POST['centro_costo']."'";
          }
          if($_POST['rut_proveedor']!="" && !empty($_POST['rut_proveedor']) && $_POST['rut_proveedor']!=NULL && $_POST['rut_proveedor']!=" "){
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
          
		    	$a=" \ ";
	$a=trim($a);
    $sql=$_POST['sql'];
	$sql=str_replace($a,"",$sql);
	$i=0;
    $res = mysql_query($sql,$con);
 ?>         
          
          
          <table style="margin-top:20px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td ><label>Dia</label></td>
       <td ><label>Mes</label></td>
       <td ><label>Agno</label></td>
       <td ><label>Empresa</label></td>
       <td ><label>Numero Factura</label></td>
       <td ><label>Litros</label></td>
       <td ><label>Valor_factura</label></td>
       <td ><label>Valor_IEF</label></td>
       <td ><label>Valor_IEV</label></td>
       <td ><label>total_IEF</label></td>
       <td ><label>Litros Utilizados</label></td>
       <td ><label>Utilizado_total_IE</label></td>
       <td ><label>Destinacion_PP_litros</label></td>
       <td ><label>Destinacion_PP_IE_Recuperable</label></td>
       <td ><label>Destinacion_VT_litros</label></td>
       <td ><label>destinacion_VT_IE_no_Recuperable</label></td>
       <td ><label>saldo_litros</label></td>
       <td ><label>saldo_impto_especifico</label></td>
       
   </tr>
   <? 
        $num=mysql_num_rows($res);
        if($num>0)
         {   
            while($row = mysql_fetch_array($res))
			{
				
               ?>
                <tr class="listado_datos">
                <td><?=$row[0];?></td>
                <td><?=$row[1];?></td>
                <td><?=$row[2];?></td>
                <td><?=$row[3];?></td>
                <td><?=$row[4];?></td>
                <td><?=$row[5];?></td>
                <td><?=$row[6];?></td>
                <td><?=number_format($row[7],0,"",""); ?></td>
                <td><?=number_format($row[8],0,"",""); ?></td>
                <td><?=$row[9];?></td>
                <td><?=$row[10];?></td>
                <td><?=$row[11];?></td>
                <td><?=$row[12];?></td>
                <td><?=$row[13];?></td>
                <td><?=$row[14];?></td>
                <td><?=$row[15];?></td>
                <td><?=$row[16];?></td>
                <td><?=$row[17];?></td>
            
                
        

        </tr>

<?          
            }
     }       
     else
	 { ?>
   <tr id="mensaje-sin-reg">
       <td colspan="9">No existen Registros con los Datos Seleccionados</td>
   </tr>
   <? 
     
   }     


?>
</table>