<?php
$msg="";
$error="";

$sql ="SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION['empresa']."' ORDER BY agno,mes,dia";
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
        <td id="titulo_tabla" style="text-align:center;" colspan="8">  </td>
        <td id="list_link"><a href="?cat=3&sec=10"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Factura Petroleo"></a></td></tr>
      
        
    </tr>
    <tr id="titulo_reg">
        <td>#</td>
        <td>Fecha</td>
        <td>Num Factura</td>
        <td>Litros</td>
        <td>Valor Fact.</td>
        <td>Utilizado</td>
        <td>Saldo</td>
        <td width="100px">Ver Salida</td>
        <td width="100px">Editar</td>
    </tr>    

<?
if(mysql_num_rows($res)!=null){
    $i=1;
while($row = mysql_fetch_assoc($res)){
?>
    <tr  class="listado_datos">
        <td><?echo $i;$i++;?></td>
        <td><?=$row['dia']."-".$row['mes']."-".$row['agno']; ?></td>
        <td><?=$row['num_factura']; ?></td>
        <td><?=$row['litros']; ?></td>
        <td><?=$row['valor_factura']; ?></td>
        <td><?=$row['utilizado_litros']; ?></td>
        <td><?=$row['saldo_litros']; ?></td>
        <td><a href="?cat=3&sec=11&id_petroleo=<?=$row['dia']."-".$row['mes']."-".$row['agno'];?>"><img src="img/view.png" width="24px" height="24px" class="toolTIP" title="Ver salidas de petroleo"></a></td>
        <td><a href="?cat=3&sec=10&action=2&id_petroleo=<?=$row['dia']."-".$row['mes']."-".$row['agno'];?>"><img src="img/edit.png" width="24px" height="24px" class="toolTIP" title="Editar factura de Petroleo"></a></td>
    </tr>


<?php }


    }else{
  ?>
    <tr  id="mensaje-sin-reg"><td colspan="9">No existen Facturas de Petroleo para ser Desplegadas</td></tr>
<? }?>
</table>

