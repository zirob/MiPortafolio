<?php

$ex = explode("-",$_GET['id_petroleo']);

$sql = "SELECT s.cod_producto,s.num_doc,d.patente,s.litros,c.descripcion_cc, s.persona_autoriza, s.persona_retira, d.especifico,s.observacion  
    FROM salida_petroleo s inner join detalles_productos d on s.cod_detalle_producto = d.cod_detalle_producto 
    inner join centros_costos c on s.centro_costo= c.Id_cc  WHERE s.dia='".$ex[0]."' and s.mes='".$ex[1]."' and s.agno='".$ex[2]."'";
$res = mysql_query($sql,$con);

?>
<table style="margin-top:20px; width:1000px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
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
    while($row = mysql_fetch_assco($res)){
   ?>

       <td><?=$row['cod_producto'];?></td>
       <td><?=$row['patente'];?></td>
       <td><?=$row['num_doc'];?></td>
       <td><?=$row['litros']?></td>
       <td><?=$row['descripcion_cc']?></td>
       <td><?if($row['especifico']==1){ echo "Si";}else{ echo "No";}?></td>
       <td><?=$row['persona_autoriza'];?></td>
       <td><?=$row['persona_retira'];?></td>
       <td><?=$row['observacion'];?></td>
  
   
    <? }?>
       
       <form action="includes/Admin/petroleo_salida_excel.php" method="POST">    
           <tr><td colspan="9">
                   <input type="text" name="dia" id="dia" hidden="hidden" value="<? if(isset($ex[0])){ echo $ex[0];}?>">
            <input type="text" name="mes" id="mes" hidden="hidden" value="<? if(isset($ex[1])){ echo $ex[1];}?>" >
            <input type="text" name="anio" id="anio" hidden="hidden" value="<? if(isset($ex[2])){ echo $ex[2];}?>" >
                   <input type="submit" value="Exportar a Excel">
               </td></tr>
       </form>   
   <? }else{ ?>
    
       
        <tr  id="mensaje-sin-reg"><td colspan="9">No existen Registros con las caracteristicas seleccionadas</td></tr>
       
   <? } ?>

