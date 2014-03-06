<?php
$msg="";
$error="";
$ex = explode("-",$_GET['id_petroleo']);

$sql ="SELECT s.agno, s.mes, s.dia, p.tipo_producto, de.patente, s.litros, cc.descripcion_cc  FROM salida_petroleo s,productos p,detalles_productos de, centros_costos cc 
WHERE  s.rut_empresa='".$_SESSION['empresa']."' and s.cod_producto = p.cod_producto and s.cod_detalle_producto = de.cod_detalle_producto and cc.Id_cc = s.centro_costo 
and s.dia='".$ex[0]."' and s.mes='".$ex[1]."' and s.agno='".$ex[2]."' ORDER BY s.agno,mes,dia";
$res = mysql_query($sql,$con);
if(isset($error) && !empty($error)){
        ?>
<div id="main-error"><? echo $error;?></div>
<?
    }elseif($msg){
?>
<div id="main-ok"><? echo $msg;?></div>
<?
    }
?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="7">  ..:: Salida de Petroleo : <?=$_GET['id_petroleo'];?> ::..</td>
        <td id="list_link"><a href="?cat=3&sec=7"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Facturas de Petroleo"></a>
                           <a href="?cat=3&sec=12&id_petroleo=<?=$_GET['id_petroleo'];?>"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Salida Petroleo"></a></td></tr>
      
        
    </tr>
    <tr id="titulo_reg">
        <td>#</td>
        <td>Dia</td>
        <td>Mes</td>
        <td>A&ntilde;o</td>
        <td>Producto</td>
        <td>Patente</td>
        <td>Litros</td>
        <td>Centro C.</td>
        <!--<td width="100px">Editar</td> -->
    </tr>    

<?
if(mysql_num_rows($res)!=NULL){
    $i=1;
while($row = mysql_fetch_assoc($res)){
    
    
           switch($row['tipo_producto']){
                    case 1:  $tipo="Maquinarias y Equipos"; break;
                    case 2:  $tipo="Vehiculo Menor"; break;
                    case 3:  $tipo="Herramientas"; break;
                    case 4:  $tipo="Muebles"; break;
                    case 5:  $tipo="Generador"; break;
                    case 6:  $tipo="Plantas"; break;
                    case 7:  $tipo="Equipos de Tunel"; break;
                    case 8:  $tipo="Otros"; break;
                }
?>
    <tr  class="listado_datos">
        <td><?echo $i;$i++;?></td>
        <td><?=$row['dia']; ?></td>
        <td><?=$row['mes']; ?></td>
        <td><?=$row['agno']; ?></td>
        <td><?=$tipo; ?></td>
        <td><?=$row['patente']; ?></td>
        <td><?=$row['litros']; ?></td>
        <td><?=$row['descripcion_cc']; ?></td>
        <? /*<td><a href="?cat=3&sec=12&id_petroleo=<?=$row['dia']."-".$row['mes']."".$row['agno'];?>"><img src="img/edit.png" width="24px" height="24px"></a></td> */ ?>
    </tr>


<?php }


    }else{
  ?>
    <tr  id="mensaje-sin-reg"><td colspan="8">No existe Salida de Petroleo para el Dia a Desplegado</td></tr>
<? }?>
</table>