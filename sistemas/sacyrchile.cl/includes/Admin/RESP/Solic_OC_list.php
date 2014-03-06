<?php

if(isset($_GET['elim']) && $_GET['elim']==1){
    $s = "UPDATE solicitudes_compra SET estado='4' WHERE id_solicitud_compra='".$_GET['id_solic']."'";
        if(mysql_query($s,$con)){
            $msg="La Solicitud de Compra ha sido Anulada";
        }
}

 $sql ="SELECT * FROM solicitudes_compra WHERE rut_empresa = '".$_SESSION['empresa']."'ORDER BY fecha_ingreso";
 $r = mysql_query($sql,$con);
?>
<?if(isset($msg) && $msg!="" && $msg!=null){ ?>
<div id="main-error"><? echo $msg;?></div>
<? } ?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="7"> </td>
        <td id="list_link"><a href="?cat=2&sec=11"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Solicitud de Compra"></a></td></tr>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td width="50px">ID OT</td>
        <td>Descripcion</td>
        <td width="70px">Fecha Ing.</td>
        <td width="80px">Num. Arch.</td>
        <td width="50px">Estado</td>
        <td width="20px">Editar</td>
        <td width="20px">Eliminar</td>
    </tr>
<?
 if(mysql_num_rows($r)>0){
     $i=1;
 while($row = mysql_fetch_assoc($r)){
 ?>    
    <tr class="listado_datos">
        <td><?=$i,$i++;?></td>
        <td><?=$row['id_ot'];?></td>
        <td><?=substr( $row['descripcion_solicitud'], 0, 20 )."...";?></td>
        <td width="100px"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
        <td>
            <?
                $sql2 = "SELECT count(*) as cant_archivos FROM archivos WHERE id_solicitud='".$row['id_solicitud_compra']."'";
                $r2 = mysql_query($sql2,$con);
                $row2 = mysql_fetch_assoc($r2);
                echo $row2['cant_archivos'];
            ?>
        </td>
        <td><?  
        $estado="";
        switch($row['estado']){
                case 1:
                    $estado ="Abierta";
                    break;
                case 2:
                    $estado ="En espera de informacion";
                    break;
                case 3:
                    $estado ="Autorizada";
                    break;
                case 4:
                    $estado ="Anulada";
                    break;
                case 5:
                    $estado ="Cerrada";
                    break;
            }
        echo $estado;
        ?></td>
        <td style="text-align:center;"><a href="?cat=2&sec=11&action=2&id_solic=<?=$row['id_solicitud_compra'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Solicitud de Compra"></a></td>
        <td style="text-align:center;"><a href="?cat=2&sec=10&elim=1&id_solic=<?=$row['id_solicitud_compra'];?>"><img src="img/delete2.png" width="24px" height="24px" border="0" class="toolTIP" title="Eliminar Solicitud de Compra"></a></td>
    </tr>  
 <?    
 }
 }else{
     ?>
    <tr  id="mensaje-sin-reg">
        <td colspan="8">No existen Solicitudes de Compra a Ser Desplegadas</td>
    </tr>
 <?   
 }
?>
</table>
