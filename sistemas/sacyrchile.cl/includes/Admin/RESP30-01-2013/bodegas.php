<?php
if(isset($_GET['filtro']) && $_GET['filtro']==1){
     $sql ="SELECT * FROM bodegas WHERE rut_empresa = '".$_SESSION['empresa']."'";
    if(!empty($_POST['descripcion_bodega']) && $_POST['descripcion_bodega']!=""){
        $sql .= " and descripcion_bodega like '%".$_POST['descripcion_bodega']."%'";
    }
            
    $sql.=" ORDER BY descripcion_bodega";
}else{
    $sql = "SELECT * FROM bodegas WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion_bodega";
}   

 $r = mysql_query($sql,$con);
?>
<table id="list_registros">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="3">  </td>
        <td id="list_link" ><a href="?cat=3&sec=2"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Bodega"></a></td>
    
    </tr>
     <form action="?cat=3&sec=1&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px">Filtro:</td>
        <td width="70px"><input type="text" name="descripcion_bodega" class="fu" size="60"></td>
        <td ></td>
      
        <td><input type="Submit" value="Filtrar"></td>
    </tr>
    </form>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td>Descripci&oacute;n</td>
        <td>Fecha Ingreso</td>
        <td width="40px;">Editar</td>
    </tr>
<?
 if(mysql_num_rows($r)!= null){
     $i=1;
 while($row = mysql_fetch_assoc($r)){
 ?>    
    <tr class="listado_datos">
        <td><?=$i;$i++;?></td>
        <td><?=utf8_decode(substr( $row['descripcion_bodega'], 0, 100 ));?></td>
        <td width="100px"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
        <td  style="text-align:center;"><a href='?cat=3&sec=2&action=2&cod=<?=$row['cod_bodega']; ?>'><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Bodega"></a></td>
 
    </tr>  
 <?    
 }
 }else{
     ?>
    <tr id="mensaje-sin-reg">
        <td colspan="4">No existen Bodegas a Ser Desplegadas</td>
    </tr>
 <?   
 }
?>
</table>