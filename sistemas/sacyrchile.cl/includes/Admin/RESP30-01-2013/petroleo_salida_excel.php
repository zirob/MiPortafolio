<?php

include '../Conexion.php';
$con = Conexion();

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Salida_petroleo".$_POST['dia']."-".$_POST['mes']."-".$_POST['anio'].".xls");
header("Pragma: no-cache");
header("Expires: 0");
$ex = explode("-",$_GET['id_petroleo']);

$sql = "SELECT * FROM salida_petroleo s INNER JOIN detalles_productos d ON s.cod_detalle_producto = d.cod_detalle_producto INNER JOIN centros_costos c ON s.centro_costo=c.Id_cc WHERE s.dia='".$_POST['dia']."' and s.mes='".$_POST['mes']."' and s.agno='".$_POST['anio']."'";



//$sql ="SELECT s.agno, s.mes, s.dia, p.tipo_producto, de.patente, s.litros, cc.descripcion_cc, d.cod_producto, s.num_doc, d.especifico, s.persona_autoriza, s.persona_retira  FROM salida_petroleo s,productos p,detalles_productos de, centros_costos cc 
//WHERE  s.rut_empresa='".$_SESSION['empresa']."' and s.cod_producto = p.cod_producto and s.cod_detalle_producto = de.cod_detalle_producto and cc.Id_cc = s.centro_costo 
//and s.dia='".$ex[0]."' and s.mes='".$ex[1]."' and s.agno='".$ex[2]."' ORDER BY s.agno,mes,dia";
$res = mysql_query($sql,$con);

?>
<table style="margin-top:20px; width:1000px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="8">  ..:: Salida de Petroleo : <?=$_POST['dia']."-".$_POST['mes']."-".$_POST['anio'];?> ::..</td>
         <td id="list_link"><a href="?cat=4&sec=3"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al reporte de Petroleo"></a></td></tr>
    </tr>
    <tr id="titulo_reg">
       <td><label>Cod Producto</label></td>
       <td><label>Patente</label></td>
       <td><label>Num. Documento</label></td>
       <td><label>Litros</label></td>
       <td><label>Centro_costo</label></td>
       <td><label>Especifico</label></td>
       <td><label>Persona Autoriza</label></td>
       <td><label>Persona Retira</label></td>
       <td><label>Observacion</label></td>
       
   </tr>
   <?
   if(mysql_num_rows($res)!=NULL){
    while($row = mysql_fetch_assoc($res)){
   ?>
   <tr  class="listado_datos">
       <td><?=$row['cod_producto'];?></td>
       <td><?=$row['patente'];?></td>
       <td><?=$row['num_doc'];?></td>
       <td><?=$row['litros']?></td>
       <td><?=$row['descripcion_cc']?></td>
       <td><?if($row['especifico']==1){ echo "Si";}else{ echo "No";}?></td>
       <td><?=$row['persona_autoriza'];?></td>
       <td><?=$row['persona_retira'];?></td>
       <td><?=$row['observacion'];?></td>
  </tr>
   
    <? }?>
      
   <? }else{ ?>
    
       
        <tr  id="mensaje-sin-reg"><td colspan="9">No existen Registros con las caracteristicas seleccionadas</td></tr>
       
   <? } ?>
</table>
