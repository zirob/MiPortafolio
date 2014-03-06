<?php

$sql = "SELECT * FROM cabecera_oc WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY fecha_oc";
$res = mysql_query($sql,$con);

?>
<table id="list_registros">
    
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="9"> </td>
        <td id="list_link" ><a href="?cat=2&sec=16"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Orden de Compra"></</a></td></tr>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td width="100px">Fecha OC</td>
        <td>solicitante</td>
        <td>Concepto</td>
        <td>Centro Costos</td>
        <td>Total</td>
        <td>Estado</td>
        <td width="100px">Fecha Entrega</td>
        <td width="20px">Ver</td>
        <td width="20px">Editar</td>
    </tr>
<?
 if(mysql_num_rows($res)!= NULL){
     $i=1;
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
     
     
     
     ?>    
    <tr class="listado_datos">
        <td><?=$i;$i++;?></td>
        <td><?=date("d-m-Y",strtotime($row['fecha_oc']));?></td>
        <td><?=$row['solicitante'];?></td>
        <td><?=$concepto;?></td>
        <td><?=$row['centro_costos'];?></td>
        <td><?=$row['total'];?></td>
        <td><?=$estado;?></td>
        <td><?=date("d-m-Y",strtotime($row['fecha_entrega']));?></td>
        <td><a href="?cat=2&sec=19&id_oc=<?=$row['id_oc'];?>"><img src="img/view.png" width="24px" height="24px" border="0" class="toolTIP" title="Ver Orden de Compra"></a></td>
        <td><a href="?cat=2&sec=16&id_oc=<?=$row['id_oc'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Orden de Compra"></a></td>
 
    </tr>  
 <?    
 }
 }else{
     ?>
    <tr id="mensaje-sin-reg">
        <td colspan="9">No existen Ordenes de Compra a ser Desplegadas</td>
    </tr>
 <?   
 }
?>
</table>