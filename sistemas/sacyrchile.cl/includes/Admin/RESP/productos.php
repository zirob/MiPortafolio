<?php
$qry="";
if(isset($_GET['filtro']) && $_GET['filtro']==1){
    $qry = "SELECT * FROM productos WHERE rut_empresa = '".$_SESSION['empresa']."'";
    if(!empty($_POST['codigo_producto']) && $_POST['codigo_producto']!=""){
        $qry .= " and cod_producto='".$_POST['codigo_producto']."'";
    }
    if(!empty($_POST['descripcion']) && $_POST['descripcion']!=""){
        $qry .= " and descripcion like '%".$_POST['descripcion']."%'";
    }
    if(!empty($_POST['pasillo']) && $_POST['pasillo']!=""){
        $qry .= " and pasillo like '%".$_POST['pasillo']."%'";
    }
    if(!empty($_POST['casillero']) && $_POST['casillero']!=""){
        $qry .= " and casillero like '%".$_POST['casillero']."%'";
    }
    if(!empty($_POST['tipo_producto']) && $_POST['tipo_producto']!=0){
        $qry .= " and tipo_producto ='".$_POST['tipo_producto']."'";
    }
     if(!empty($_POST['critico']) && $_POST['critico']!=0){
        $qry .= " and critico ='".$_POST['critico']."'";
    }
     if(!empty($_POST['activo_fijo']) && $_POST['activo_fijo']!=0){
        $qry .= " and activo_fijo ='".$_POST['activo_fijo']."'";
    }
    
    
    $qry.=" ORDER BY descripcion";
}else{
    $qry = "SELECT * FROM productos WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion";
}   
    
$res = mysql_query($qry,$con);
?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" colspan="7"></td>
        
    </tr>
    <tr>
        <td colspan="10"><a href="?cat=3&sec=4"><img src="img/add1.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Agregar Producto"></a></td>
    </tr>
    <form action="?cat=3&sec=3&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px">Filtro:</td>
        <td  width="150px"><input class="fu" type="text" size="12" id="codigo_producto" name="codigo_producto"></td>
        <td><input class="fo" type="text" id="descripcion" name="descripcion"></td>
        <td width="30px"><input class="fu" type="text" size="3" id="pasillo" name="pasillo"></td>
        <td width="30px"><input class="fu" type="text" size="3" id="casillero" name="casillero"></td>
        <td>
            <select name="tipo_producto" class="filtro">
                <option value="0" >---</option>
                <option value="7" >Equipos de Tunel</option>
                <option value="5" >Generador</option>
                <option value="3" >Herramientas</option>
                <option value="1" >Maquinarias y Equipo</option>
                <option value="4" >Muebles</option>
                <option value="6" >Plantas</option>
                <option value="2" >Vehiculo Menor</option>
                <option value="8" >Otros</option>
            </select>
        </td>
        <td width="70px">
             <select name="critico" class="filtro">
                <option value="0">---</option>
                <option value="1">Si</option>
                <option value="2">No</option>
            </select>
        </td>
        <td width="70px">
            <select name="activo_fijo" class="filtro">
                <option value="0">---</option>
                <option value="1">Si</option>
                <option value="2">No</option>
            </select>
        </td>
        <td colspan="2"><input type="submit" value="Filtrar"></td>
    </tr>    
    </form>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td  width="150px">Codigo</td>
        <td>Descripcion</td>
        <td width="30px">Pasillo</td>
        <td width="30px">Casillero</td>
        <td>Tipo</td>
        <td width="70px">Critico</td>
        <td width="70px">Activo Fijo</td>
        <td width="30px">Ver</td>
        <td width="30px">Editar</td>
    </tr>    

<?
if(mysql_num_rows($res)!=null){
    $i=1;
while($row = mysql_fetch_assoc($res)){
    $t = $row['tipo_producto'];
        switch($t){
            case 1:
                $tipo="Maquinaria y Equipos";
                break;
            case 2:
                $tipo="Vehiculo Menor";
                break;
            case 3:
                $tipo="Herramientas";
                break;
            case 4:
                $tipo="Mueble";
                break;
            case 5:
                $tipo="Generador";
                break;
            case 6:
                $tipo="Plantas";
                break;
            case 7:
                $tipo="Equipos de Tunel";
                break;
            case 8:
                $tipo="Otros";
                break;
        }
?>
    <tr  class="listado_datos">
        <td><?echo $i;$i++;?></td>
        <td><?=$row['cod_producto']; ?></td>
        <td><?=$row['descripcion']; ?></td>
        <td><?=$row['pasillo']; ?></td>
        <td><?=$row['casillero']; ?></td>
        <td><?=$tipo; ?></td>
        <td><? if($row['critico']==1){ echo "Si";}else{ echo "No";} ?></td>
        <td><? if($row['activo_fijo']==1){ echo "Si";}else{ echo "No";} ?></td>
        <td style="text-align: center;"><a href="?cat=3&sec=5&id_prod=<?=$row['cod_producto']?>"><img src="img/view.png" width="24px" height="24px" border="0" class="toolTIP" title="Ver Detalle Productos"></a></td>
        <td style="text-align: center;"><a href="?cat=3&sec=4&action=2&id_prod=<?=$row['cod_producto'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Producto"></a></td>
    </tr>


<?php }


    }else{
  ?>
    <tr  id="mensaje-sin-reg"><td colspan="10">No existen Productos para ser Desplegados</td></tr>
<? }?>
</table>
