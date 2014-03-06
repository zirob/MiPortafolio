<?php

if(isset($_GET['elim']) && $_GET['elim']==1){
    $s = "UPDATE solicitudes_compra SET estado='4' WHERE id_solicitud_compra='".$_GET['id_solic']."'";
        if(mysql_query($s,$con)){
            $msg="La Solicitud de Compra ha sido Anulada";
        }
}

if(isset($_GET['filtro']) && $_GET['filtro']==1){
    $sql ="SELECT * FROM solicitudes_compra WHERE rut_empresa = '".$_SESSION['empresa']."'";
    
    if(isset($_POST['descripcion']) && $_POST['descripcion']!=""){
        $sql.=" and descripcion_solicitud like '%".$_POST['descripcion']."%'";
    }
    
    if(isset($_POST['fecha']) && $_POST['fecha']!=""){
        $sql.=" and fecha_ingreso='".$_POST['fecha']."'";
    }
    
    if(isset($_POST['concepto']) && $_POST['concepto']!=""){
        $sql.=" and concepto='".$_POST['concepto']."'";
    }
    
     if(isset($_POST['tipo']) && $_POST['tipo']!=""){
        $sql.=" and tipo_solicitud='".$_POST['tipo']."'";
    }
    
     if(isset($_POST['estado']) && $_POST['estado']!=""){
        $sql.=" and estado='".$_POST['estado']."'";
    }
    
    $sql.=" ORDER BY fecha_ingreso";
    
    
}else{
    $sql ="SELECT * FROM solicitudes_compra WHERE rut_empresa = '".$_SESSION['empresa']."'ORDER BY fecha_ingreso";
}
 $r = mysql_query($sql,$con);
?>
<?if(isset($msg) && $msg!="" && $msg!=null){ ?>
<div id="main-error"><? echo $msg;?></div>
<? } ?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="7"> </td>
        <td id="list_link"><a href="?cat=2&sec=11"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Solicitud de Compra"></a></td></tr>
   <form action="?cat=2&sec=10&filtro=1" method="POST">
    <tr  id="titulo_reg" style="background-color: #fff;">
        <td>Filtro:</td>
        <td><input type="text" name="descripcion" class="fo"></td>
        <td><input type="text" name="fecha" size="10" class="fu"></td>
        <td><select name="concepto" style="width:150px;" class="fu"><option value="">---</option><option value="1">Compra</option><option value="2">Mantenci&oacute;n</option><option value="3">Reparaci&oacute;n</option></select></td>
        <td><select name="tipo" style="width:150px;" class="fu"><option value="">---</option><option value="1">Nacional</option><option value="0">Internacional</option></select></td>
        <td><select name="estado" style="width:150px;" class="fu">
                <option value="">---</option>
                <option value="1">Abierta</option>
                <option value="2">En Espera de Informaci&oacute;n</option>
                <option value="3">Autorizada</option>
                <option value="4">Anulada</option>
                <option value="5">Cerrada</option>
            </select>
        </td>
        <td colspan="2"><input type="submit" value="Filtrar"></td>
    </tr>
   </form>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td>Descripcion</td>
        <td width="70px">Fecha Ing.</td>
        <td>Concepto</td>
        <td>Tipo</td>
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
        <td><?=$i;$i++;?></td>
        
        <td><?=substr( $row['descripcion_solicitud'], 0, 20 )."...";?></td>
        <td width="100px"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
        <td>
            <?   
            switch($row['concepto']){
            case 1:
                $concepto="Compra";
                break;
            case 2:
                $concepto="Mantenci&oacute;n";
                break;
            case 3:
                $concepto="Reparaci&oacute;n";
                break;
            case 4:
                $concepto="Certificaci&oacute;n Equipo";
                break;
        }
        
         echo $concepto;
       
         
        switch($row['tipo_solicitud']){
            case 1:
                $tipo="Nacional";
                break;
            case 0:
                $tipo="Internacional";
                break;
        }
         ?>
        </td>
        <td><? echo $tipo;?></td>
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
