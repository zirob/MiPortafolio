<?php
$error="";
$msg="";
$asigMult="";
$cant="";
if(isset($_GET['elim']) && $_GET['elim']==1){
    $sql ="UPDATE detalles_productos SET estado_producto=2 WHERE cod_detalle_producto=".$_GET['id_det'];
    if(mysql_query($sql,$con)){
        $msg="Se ha eliminado correctamente el Item";
    }else{
        $error="Ha ocurrido un error al aliminar el Item";
    }
    
}

if(isset($_GET['asignar']) && $_GET['asignar']==1){
    
    $sql ="SELECT * FROM detalle_productos WHERE rut_empresa='".$_SESSION['empresa']."' and estado_producto=1 and cod_producto='".$_GET['id_prod']."' ORDER BY cod_detalle_producto";
    $res = mysql_query($sql,$con);
    
    $cant = count($_POST['nueva_bodega']);
    $nueva = $_POST['nueva_bodega'];
    $i=1;
    while($row = mysql_fetch_assoc($res)){
        if($i<=$cant){
            $s = "UPDATE detalle_productos SET asignado_a_bodega='".$nueva[$i]."' fecha_cambio_bodega='".date('Y-m-d H:m:s')."', cod_bodega_anterior='".$row['asignado_a_bodega']."'  WHERE cod_detalle_producto='".$row['cod_detalle_producto']."'";
            mysql_query($s,$con);
            $i++;
        }else{
            exit;
        }
        
    }
    if($i==$cant){
        $msg="Se reasignaron bodegas a los detalles de productos correctamente";
    }else{
        $error="No se pudieron reasignar bodegas a los productos";
    }
    
}



if(isset($_GET['id_prod'])){
    $sel2 = "SELECT * FROM detalles_productos WHERE rut_empresa = '".$_SESSION['empresa']."' and estado_producto=1 and cod_producto=".$_GET['id_prod']." ORDER BY cod_detalle_producto LIMIT 1";
    $res2 = mysql_query($sel2,$con);
    $rw = mysql_fetch_assoc($res2);
    
    if($rw['patente']=="" && empty($rw['patente'])){
        $asigMult = 1;
    }else{
        $asigMult = 2;
    }
    
        if($_GET['filtro']==1){
            if($_POST['arrendado']!="" && $_POST['arrendado']==1){
               $sel = "SELECT * FROM detalles_productos WHERE rut_empresa = '".$_SESSION['empresa']."' and estado_producto=1 and cod_producto=".$_GET['id_prod']." and producto_arrendado=1 ORDER BY cod_detalle_producto";
            }elseif($_POST['arrendado']!="" && ($_POST['arrendado']==2 || $_POST['arrendado']==0)){
                $sel = "SELECT * FROM detalles_productos WHERE rut_empresa = '".$_SESSION['empresa']."' and estado_producto=1 and cod_producto=".$_GET['id_prod']." and producto_arrendado in(0,2) ORDER BY cod_detalle_producto";
            }elseif ($_POST['arrendado']=="") {
                $sel = "SELECT * FROM detalles_productos WHERE rut_empresa = '".$_SESSION['empresa']."' and estado_producto=1 and cod_producto=".$_GET['id_prod']." ORDER BY cod_detalle_producto";
            }
            
        
        }else{
            $sel = "SELECT * FROM detalles_productos WHERE rut_empresa = '".$_SESSION['empresa']."' and estado_producto=1 and cod_producto=".$_GET['id_prod']." ORDER BY cod_detalle_producto";
        }
    $res = mysql_query($sel,$con);
    $cant = mysql_num_rows($res);
}
?>
<?
    if(isset($error) && !empty($error)){
        ?>
<script>
          alert(<?php echo $error;?>);
</script>
<?
    }elseif($msg){
?>
<script>
          alert(<?php echo $msg;?>);
</script>

<?
    }
?>
<table id="list_registros">
    <tr>
    <form action="?cat=3&sec=5&filtro=1&id_prod=<?=$_GET['id_prod'];?>" method="POST">
        <td id="titulo_tabla" style="text-align:center;" colspan="7"> Filtro:  Arrendado: <select name="arrendado"><option value="">---</option><option value="1">Si</option><option value="2">No</option></select>
            <input type="submit" value="Filtrar">
        </td>
        
    </form>   
        <td id="list_link">
            
            <a href="?cat=3&sec=3"><img src="img/view_previous.png" width="30px" height="30px" border="0"></a><a alt="volver" href="?cat=3&sec=6&id_prod=<?=$_GET['id_prod'];?>"><img src="img/add1.png" width="30px" height="30px" border="0" class="toolTIP" title="Agregar Detalle de Producto"></a>
            <? if($asigMult==1){?><a href="javascript:reasignar_items(<?=$cant;?>,'<?=$_SESSION['empresa'];?>');"><img src="img/document_exchange.png"  width="30px" height="30px" border="0" alt="Reasignar Bodega" style="float:left;" class="toolTIP" title="Reasignar Bodega a los Productos"></a><? } ?>
        </td></tr>
    
    </tr>
    <tr id="titulo_reg">
        <td>#</td>
        <td>Codigo</td>
        <td>descripcion</td>
        <td>Especifico</td>
        <td>Bodega</td>
        <td>Arrendado</td>
        <td width="100px">Editar</td>
        <td width="100px">Eliminar</td>
    </tr>    
    <form action="?cat=3&sec=5&asignar=1&id_prod=<?=$_GET['id_prod'];?>" method="POST">
<?
if(mysql_num_rows($res)!=NULL){
    $i=1;
while($row = mysql_fetch_assoc($res)){
?>
    <tr  class="listado_datos">
        <td><?echo $i;$x=$i;$i++;?></td>
        <td><?=$row['cod_producto']; ?></td>
        <td><?=$row['observaciones']; ?></td>
        <td><? if($row['especifico']==1){ echo "Si";}else{ echo "No"; } ?></td>
        <td id="asignar_bodega_<?=$x;?>"><? 
                    $s = "SELECT * FROM bodegas WHERE cod_bodega=".$row['asignado_a_bodega'];
                    $r = mysql_query($s,$con);
                    $rw = mysql_fetch_assoc($r);
                    echo $rw['descripcion_bodega'];
                    ?></td>
        <td><? if($row['producto_arrendado']==1){ echo "Si";}else{ echo "No"; } ?></td>
        <td><a href="?cat=3&sec=9&action=2&id_prod=<?=$row['cod_producto'];?>&id_det=<?=$row['cod_detalle_producto'];?>"><img class="toolTIP" title="Editar Detalle de Producto" src="img/editar.png" width="24px" height="24px"></a></td>
        <td><a href="?cat=3&sec=5&elim=1&id_prod=<?=$_GET['id_prod']?>&id_det=<?=$row['cod_detalle_producto'];?>"><img src="img/eliminar.png" width="24px" height="24px" class="toolTIP" title="Eliminar Detalle de Producto"></a></td>
    </tr>


<?php } ?>
    </form>

<?    }else{   ?>
    <tr  id="mensaje-sin-reg"><td colspan="8">No existen Productos para ser Desplegados</td></tr>
<? }?>
</table>

