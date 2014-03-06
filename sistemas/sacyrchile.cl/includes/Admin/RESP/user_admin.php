<?php
if(isset($_GET['filtro']) && $_GET['filtro']==1){
    $qry = "SELECT usuario, nombre, cargo, depto, fecha_ingreso FROM usuarios u inner join empresa e on u.rut_empresa = e.rut_empresa WHERE u.rut_empresa = '".$_SESSION['empresa']."'";
    if(!empty($_POST['usuario']) && $_POST['usuario']!=""){
        $qry .= " and usuario like '%".$_POST['usuario']."%'";
    }
    if(!empty($_POST['nombre']) && $_POST['nombre']!=""){
        $qry .= " and nombre like '%".$_POST['nombre']."%'";
    }
    
    if(!empty($_POST['cargo']) && $_POST['cargo']!=""){
        $qry .= " and cargo like '%".$_POST['cargo']."%'";
    }
    if(!empty($_POST['departamento']) && $_POST['departamento']!=""){
        $qry .= " and depto like '%".$_POST['departamento']."%'";
    }
       
    $qry.=" ORDER BY usuario";
}else{
    $qry = "SELECT usuario, nombre, cargo, depto, fecha_ingreso FROM usuarios u inner join empresa e on u.rut_empresa = e.rut_empresa WHERE u.rut_empresa = '".$_SESSION['empresa']."' ORDER BY usuario";
}



$res = mysql_query($qry,$con);
?>
<table id="list_registros">
    <tr>
       <td id="titulo_tabla" style="text-align:center;" colspan="6">  </td>
        <td id="list_link" ><a href="?cat=2&sec=5"><img src="img/user1_add.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Usuario"></</a></td></tr>
    </tr>
    <form action="?cat=2&sec=4&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px">Filtro:</td>
        <td><input type="text" name="usuario" class="fo"></td>
        <td><input type="text" name="nombre" class="fo"></td>
         <td><input type="text" name="cargo" class="fo"></td>
         <td><input type="text" name="departamento" class="fo"></td>
         <td colspan="2"><input type="Submit" value="Filtrar"></td>
    </tr>
    </form>
    <tr id="titulo_reg">
        <td   width="30px;">#</td>
        <td>Usuario</td>
        <td>Nombre</td>
        <td width="150px;">cargo</td>
        <td  width="150px;">Departamento</td>
        <td width="100px;">Fec. Ingreso</td>
        <td  width="50px;">Editar</td>
    </tr>    
    
<?

if(mysql_num_rows($res)!=null){
    $i=1;
    while($row = mysql_fetch_assoc($res)){
?>
    <tr class="listado_datos">
        <td><?echo $i; $i++;?></td>
        <td><?=utf8_encode($row['usuario']); ?></td>
        <td><?=utf8_encode($row['nombre']); ?></td>
        <td><?=$row['cargo']; ?></td>
        <td><?=$row['depto']; ?></td>
        <td width="100px"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
        <td style="text-align: center;"><a href="?cat=2&sec=5&action=2&user=<?=$row['usuario'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Usuario"></a></td>
    </tr>


<?php }


    }else{
  ?>
    <tr  id="mensaje-sin-reg"><td colspan="7">No existen Usuarios para ser Desplegados</td></tr>
<? }?>
</table>

    