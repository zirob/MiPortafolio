<?php
if(isset($_GET['filtro']) && $_GET['filtro']==1){
 $sql ="SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'";
         if(!empty($_POST['cod_cc']) && $_POST['cod_cc']!=""){
        $sql .= " and codigo_cc=".$_POST['cod_cc'];
    }
    if(!empty($_POST['descripcion_cc']) && $_POST['descripcion_cc']!=""){
        $sql .= " and descripcion_cc like '%".$_POST['descripcion_cc']."%'";
    }
         
    $sql.=" ORDER BY descripcion_cc";
}else{
    $sql ="SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion_cc";
}
 
 
 
?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="4"></td>
        <td id="list_link" ><a href="?cat=2&sec=13"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Centros de Costo"></a></td></tr>
    <form action="?cat=2&sec=12&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px">Filtro:</td>
        <td width="70px"><input type="text" name="cod_cc" class="fo"></td>
        <td colspan="2"><input type="text" name="descripcion_cc" class="fu" size="60"></td>
         <td><input type="Submit" value="Filtrar"></td>
    </tr>
    </form>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td width="70px">Cod. CC</td>
        <td>Descripcion</td>
        <td width="100px">Fecha Ing.</td>
        
        <td width="20px">Editar</td>
    </tr>
<?
if($r = mysql_query($sql,$con)){
 if(mysql_num_rows($r)!= null){
     $i=1;
 while($row = mysql_fetch_assoc($r)){
 ?>    
    <tr class="listado_datos">
        <td><?=$i;$i++;?></td>
        <td><?=$row['codigo_cc'];?></td>
        <td><?=utf8_decode(substr( $row['descripcion_cc'], 0, 100 ));?></td>
        <td width="100px"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
        
        <td style="text-align:center;"><a href="?cat=2&sec=13&action=2&id_cc=<?=$row['Id_cc'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Centro de Costo"></a></td>
 
    </tr>  
 <?    
 }
 }
 }else{
     ?>
    <tr id="mensaje-sin-reg">
        <td colspan="6">No existen Centros de Costo a ser Desplegados</td>
    </tr>
 <?   
 }
?>
</table>