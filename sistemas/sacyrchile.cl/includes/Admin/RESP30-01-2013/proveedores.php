<?php

if(isset($_GET['filtro']) && $_GET['filtro']==1){
    $qry = "SELECT * FROM proveedores WHERE rut_empresa = '".$_SESSION['empresa']."'";
         if(!empty($_POST['razon_social']) && $_POST['razon_social']!=""){
        $qry .= " and razon_social like '%".$_POST['razon_social']."%'";
    }
    if(!empty($_POST['direccion']) && $_POST['direccion']!=""){
        $qry .= " and domicilio like '%".$_POST['direccion']."%'";
    }
    
    if(!empty($_POST['telefono']) && $_POST['telefono']!=""){
        $qry .= " and telefono_1 like '%".$_POST['telefono']."%'";
    }
       
    $qry.=" ORDER BY razon_social";
}else{
    $qry = "SELECT * FROM proveedores WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY Razon_social";
}



$res = mysql_query($qry,$con);
?>
<table id="list_registros">
 
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="5">  </td>
        <td id="list_link"><a href="?cat=2&sec=8"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Proveedor"></a></td>
    </tr>
 
    <form action="?cat=2&sec=7&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px">Filtro:</td>
        <td><input type="text" name="razon_social" class="fo"></td>
        <td><input type="text" name="direccion" class="fo"></td>
         <td><input type="text" name="telefono" class="fo"></td>
         <td colspan="2"><input type="Submit" value="Filtrar"></td>
    </tr>
    </form>
    <tr id="titulo_reg">
        <td  width="30px;">#</td>
        <td>Razon Social</td>
        <td  width="250px;">Domicilio</td>
        <td  width="140px;">Telefono_1</td>
        <td  width="50px;">Ver</td>
        <td  width="50px;">Editar</td> 
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
        <td><?=$row['telefono_1']; ?></td>
        <td style="text-align: center;"><a href="?cat=2&sec=9&id_prov=<?=$row['rut_proveedor'];?>"><img src="img/view.png" width="24px" height="24px" border="0" class="toolTIP" title="Ver Datos del Proveedor"></a></td>
        <td style="text-align: center;"><a href="?cat=2&sec=8&action=2&id_prov=<?=$row['rut_proveedor'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Proveedor"></a></td>
    </tr>


<?php }


    }else{
  ?>
    <tr  id="mensaje-sin-reg"><td colspan="6">No existen Proveedores para ser Desplegados</td></tr>
<? }?>
</table>
