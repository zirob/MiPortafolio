<?php
 $sql ="SELECT * FROM cabeceras_ot WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY estado_ot";
 $r = mysql_query($sql,$con);
?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="6"> </td>
        <td id="list_link" ><a href="?cat=2&sec=18"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Orden de Trabajo"></a></td></tr>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td width="150px">Tipo trabajo</td>
        <td>Concepto</td>
        <td>Centro Costo</td>
        <td width="150px">Estado</td>
        <td width="100px">Fecha Ing.</td>
        
        <td width="20px">Editar</td>
    </tr>
<?
 if(mysql_num_rows($r)!= null){
     $i=1;
 while($row = mysql_fetch_assoc($r)){
     
     $s="SELECT descripcion_cc FROM centros_costos WHERE Id_cc=".$row['centro_costos'];
     $r2 = mysql_query($s,$con);
        $r2r = mysql_fetch_assoc($r2);
        $centro_costo = $r2r['descripcion_cc'];
     
     switch($row['concepto_trabajo']){
         case 1:
             $concepto="Compras";
             break;
         case 2:
             $concepto="Mantencion";
             break;
         case 3:
             $concepto="Reparacion";
             break;
         case 4:
             $concepto="Certificacion Equipo";
             break;
     }
     
     
     switch($row['estado_ot']){
         case 1:
             $estado="Abierta";
             break;
         case 2:
             $estado="En Proceso";
             break;
         case 3:
             $estado="Finalizada";
             break;
         case 4:
             $estado="Cerrada";
             break;
     }
     
 ?>    
    <tr class="listado_datos">
        <td><?=$i;$i++;?></td>
        <td><?=$row['tipo_trabajo'];?></td>
        <td><?=$concepto;?></td>
        <td><?=$centro_costo;?></td>
        <td><?=$estado;?></td>
        <td><?=date("d-m-Y",strtotime($row['fecha_ingreso'])); ?></td>
        <td style="text-align:center;"><a href="?cat=2&sec=18&action=2&id_ot=<?=$row['id_ot'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Orden de Trabajo"></a></td>
 
    </tr>  
 <?    
 }
 }else{
     ?>
    <tr id="mensaje-sin-reg">
        <td colspan="7">No existen Ordenes de Trabajo a Ser Desplegadas</td>
    </tr>
 <?   
 }
?>
</table>
