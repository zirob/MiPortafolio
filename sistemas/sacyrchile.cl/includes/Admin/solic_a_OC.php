<?php

$id_solic = $_GET['id_solic'];

$solic = "SELECT * FROM solicitudes_compra WHERE id_solicitud_compra=".$id_solic;
$res = mysql_query($solic,$con);
if(mysql_num_rows($res)!= null){
    $row = mysql_fetch_assoc($res);
    
    $id_ot =$row['id_ot'];
    $descripcion=$row['descripcion_solicitud'];
    $observaciones=$row['observaciones'];
    
    switch($row['tipo_solicitud']){
        case 0:
            $tipo ="Internacional";
            break;
        case 1:
            $tipo ="Nacional";
            break;
    }
    
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
    switch($row['concepto']){
        case 1:
            $concepto ="Compra";
            break;
        case 2:
            $concepto ="Mantencion";
            break;
        case 3:
            $concepto ="Reparacion";
            break;
    }
    
     
}

$archivos = "SELECT * FROM archivos WHERE id_solicitud=".$id_solic;
$resARC = mysql_query($archivos,$con);

?>
<form action="?cat=2&sec=16&id_solic=<?=$_GET['id_solic'];?>" method="POST">
 <table style="width:900px;" id="detalle-cc" border="1" cellpadding="3" cellspacing="2">
     <tr>
         <td colspan="3">ID OT: <input type="text" readonly="readonly" name="id_ot" value="<?=$id_ot;?>"></td>
     </tr>
     <tr>
         <td colspan="3">Descripcion:<br /> <p><?=$descripcion;?></p></td>
     </tr>
     <tr>
         <td colspan="3">Observaciones:<br /> <p><?=$observaciones;?></p></td>
     </tr>
     <tr>
         <td>Tipo: <input type="text" readonly="readonly" value="<?=$tipo;?>" name="tipo"></td>
         <td>Estado: <input type="text" readonly="readonly" value="<?=$estado;?>" name="estado"></td>
         <td>Concepto: <input type="text" readonly="readonly" value="<?=$concepto;?>" name="concepto"></td>
     </tr>
     <tr>
         <td colspan="3">Cotizacion Aprovada <br />
             <? if(mysql_num_rows($resARC)!= NULL){
                    while($rew = mysql_fetch_assoc($resARC)){
                        ?>
             <input type="radio" name="archivo" value="<?=$rew['id_archivo']?>"> <a href="../../documentos/<?=$rew['nombre_archivo'].".".$rew['extension_archivo'];?>"><?=$rew['nombre_archivo'];?></a>
                        <?
                    }
                    
                }
?>
         </td>
     </tr>
     <tr>
         <td colspan="3"><input type="submit" value="Convertir en OC"></td>
     </tr>
 </table>
</form>
