<?php
if(isset($_GET['filtro']) && $_GET['filtro']==1){
    $sql = "SELECT * FROM cabecera_oc WHERE rut_empresa = '".$_SESSION['empresa']."'";
    
    if(isset($_POST['fecha']) && $_POST['fecha']!=""){
        $sql.=" and fecha_oc='".date("Y-m-d",strtotime($_POST['fecha']))."'";
    }
    
    if(isset($_POST['solicitante']) && $_POST['solicitante']!=""){
        $sql.=" and solicitante like '%".$_POST['solicitante']."%'";
    }
    
    if(isset($_POST['concepto']) && $_POST['concepto']!=""){
        $sql.=" and concepto=".$_POST['concepto'];
    }
    
    if(isset($_POST['centro_costo']) && $_POST['centro_costo']!=""){
        $sql.=" and centro_costos=".$_POST['centro_costo'];
    }
    
    if(isset($_POST['estado']) && $_POST['estado']!=""){
        $sql.=" and estado_oc=".$_POST['estado'];
    }
    
    $sql.=" ORDER BY fecha_oc";
}else{
    $sql = "SELECT * FROM cabecera_oc WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY fecha_oc";
}
$res = mysql_query($sql,$con);

?>
<table id="list_registros">
    
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="9"> </td>
        <td id="list_link" ><a href="?cat=2&sec=16"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Orden de Compra"></</a></td></tr>
    <tr id="titulo_reg" style="background-color: #fff;">
        <form action="?cat=2&sec=15&filtro=1" method="POST">
        <td ><label>Filtro:</label></td>
        <td ><input type="text" size="10" name="fecha" class="fu"></td>
        <td ><input type="text" size="15" name="solicitante" class="fu"></td>
        <td><select name="concepto"><option value="">---</option><option value="1">Compra</option><option value="2">Mantenci&oacute;n</option><option value="3">Reparaci&oacute;n</option></select></td>
        <td><select name="centro_costo">
                <option value="">---</option>
            <?
                $s = "SELECT * FROM centros_costos ORDER BY descripcion_cc";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r)){
                    ?>  <option value="<?=$roo['Id_cc'];?>"><?=$roo['descripcion_cc'];?></option> <?    
                }
        
                ?>
            </select>    
        </td>
         <td></td>
         <td><select name="estado" style="width:100px;" class="fu">
                 <option value="">---</option>
                 <option value="1">Abierta</option>
                 <option value="2">Pendiente</option>
                 <option value="3">Cerrada</option>
                 <option value="4">Aprobada</option>
             </select></td>
           <td></td>
        <td colspan="2"><input type="submit" value="Filtrar"></td>
        </form>
    </tr>
    <tr id="titulo_reg">
        <td width="20px">#</td>
        <td width="120px">Fecha OC</td>
        <td>Solicitante</td>
        <td>Concepto</td>
        <td>Centro Costos</td>
        <td>Total</td>
        <td>Estado</td>
        <td width="100px">Fecha Entrega</td>
        <td width="20px">Ver</td>
        <td width="20px">Editar</td>
    </tr>
<?
 if(mysql_num_rows($res)!= NULL){
     $i=1;
 while($row = mysql_fetch_assoc($res)){
 
     
     switch($row['estado_oc']){
         case 1:
             $estado = "Abierto";
             break;
         case 2:
             $estado = "Pendiente";
             break;
         case 3: 
             $estado = "Cerrada";
             break;
         case 4: 
             $estado = "Aprobado";
             break;
     }
     
     switch($row['concepto']){
         case 1:
             $concepto = "Compra";
             break;
         case 2: 
             $concepto = "Mantenci&oacute;n";
             break;
         case 3:
             $concepto = "Reparaci&oacute;n";
             break;
     }
     
     
     
     ?>    
    <tr class="listado_datos">
        <td><?=$i;$i++;?></td>
        <td><?=date("d-m-Y",strtotime($row['fecha_oc']));?></td>
        <td><?=$row['solicitante'];?></td>
        <td><?=$concepto;?></td>
        <td><? 
        
                $s="SELECT * FROM centros_costos WHERE Id_cc=".$row['centro_costos'];
                $r = mysql_query($s,$con);
                $ro = mysql_fetch_assoc($r);
                
                echo $ro['descripcion_cc'];
                
        
        
        
        ?></td>
        <td><?=$row['total'];?></td>
        <td><?=$estado;?></td>
        <td><?=date("d-m-Y",strtotime($row['fecha_entrega']));?></td>
        <td><a href="?cat=2&sec=19&id_oc=<?=$row['id_oc'];?>"><img src="img/view.png" width="24px" height="24px" border="0" class="toolTIP" title="Ver Orden de Compra"></a></td>
        <td><a href="?cat=2&sec=16&id_oc=<?=$row['id_oc'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Orden de Compra"></a></td>
 
    </tr>  
 <?    
 }
 }else{
     ?>
    <tr id="mensaje-sin-reg">
        <td colspan="9">No existen Ordenes de Compra a ser Desplegadas</td>
    </tr>
 <?   
 }
?>
</table>