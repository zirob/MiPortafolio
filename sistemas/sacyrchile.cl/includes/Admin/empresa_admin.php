<?php

$qry = "SELECT * FROM empresa ORDER BY Razon_social";
$res = mysql_query($qry,$con);
?>
<table id="list_registros">
    <tr>
        <td colspan="7"  id="list_link"><a href="?cat=1&sec=2"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Nueva Empresa"></a></td>
    </tr>
    <tr id="titulo_reg">
        <td>#</td>
        <td>Razon Social</td>
        <td>Domicilio</td>
        <td>Comuna</td>
        <td>Telefono_1</td>
        <td>Ver</td>
        <td>Editar</td>
    </tr>    

<?
if(mysql_num_rows($res)!=null){
    $i=1;
while($row = mysql_fetch_assoc($res)){
?>
    <tr  class="listado_datos">
        <td><?echo $i;$i++;?></td>
        <td><?=$row['razon_social']; ?></td>
        <td><?=$row['domicilio']; ?></td>
        <td><?=$row['comuna']; ?></td>
        <td><?=$row['telefono_1']; ?></td>
        <td><a href="?cat=1&sec=3&id_prov=<?=$row['rut_proveedor'];?>">ver</a></td>
        <td><a href="?cat=1&sec=2&action=2&id_prov=<?=$row['rut_proveedor'];?>" class="toolTIP" title="Editar Empresa">editar</a></td>
    </tr>


<?php }


    }else{
  ?>
    <tr  id="mensaje-sin-reg"><td colspan="7">No existen Proveedores para ser Desplegados</td></tr>
<? }?>
</table>
