<?php
//var_dump($_POST);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT * FROM solicitudes_compra WHERE rut_empresa = '".$_SESSION['empresa']."'  ";
if(isset($_REQUEST['id_solicitud_compra']) && $_REQUEST['id_solicitud_compra']!=""){
    $consulta.=" and id_solicitud_compra like '%".$_REQUEST['id_solicitud_compra']."%' ";
}

if(isset($_REQUEST['descripcion']) && $_REQUEST['descripcion']!=""){
    $consulta.=" and descripcion_solicitud like '%".$_REQUEST['descripcion']."%' ";
}

if(isset($_REQUEST['fecha']) && $_REQUEST['fecha']!=""){
    $consulta.=" and fecha_ingreso like '%".$_REQUEST['fecha']."%' ";
}

if(isset($_REQUEST['concepto']) && $_REQUEST['concepto']!=0){
    $consulta.=" and concepto='".$_REQUEST['concepto']."' ";
}

if(isset($_REQUEST['tipo']) && $_REQUEST['tipo']!=0){
    $consulta.=" and tipo_solicitud='".$_REQUEST['tipo']."' ";
}

if(isset($_REQUEST['estado']) && $_REQUEST['estado']!=0){
    $consulta.=" and estado='".$_REQUEST['estado']."'";
}

if(isset($_REQUEST['tipo_solicitud_compra']) && $_REQUEST['tipo_solicitud_compra']!=0){
    $consulta.=" and tipo_solicitud_compra='".$_REQUEST['tipo_solicitud_compra']."'";
}

$consulta.=" ORDER BY fecha_ingreso";
$datos=mysql_query($consulta,$con);

//MIRO CUANTOS DATOS FUERON DEVUELTOS
$num_rows=mysql_num_rows($datos);

//ACA SE DECIDE CUANTOS RESULTADOS MOSTRAR POR PÁGINA , EN EL EJEMPLO PONGO 15
$rows_per_page= 20;

//CALCULO LA ULTIMA PÁGINA
$lastpage= ceil($num_rows / $rows_per_page);

//COMPRUEBO QUE EL VALOR DE LA PÁGINA SEA CORRECTO Y SI ES LA ULTIMA PÁGINA
$page=(int)$page;
if($page > $lastpage){
    $page= $lastpage;
}
if($page < 1){
    $page=1;
}

//CREO LA SENTENCIA LIMIT PARA AÑADIR A LA CONSULTA QUE DEFINITIVA
$limit= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;


//Elimina (Anula) registro
if(isset($_GET['elim']) && $_GET['elim']==1){
    $s = "UPDATE solicitudes_compra SET estado='4' WHERE id_solicitud_compra='".$_GET['id_solic']."' AND rut_empresa='".$_SESSION["empresa"]."' ";
        if(mysql_query($s,$con)){
            $mensaje="La Solicitud de Compra Nº ".$_GET['id_solic']." ha sido Anulada";

            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$_GET['id_solic']."', '3'";
            $sql_even.= ", 'update:estado=4', '".$_SERVER['REMOTE_ADDR']."', 'anula solicitud de compra', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);
        }
}

if(isset($_GET['filtro']) && $_GET['filtro']==1){

    $sql ="SELECT * FROM solicitudes_compra WHERE rut_empresa = '".$_SESSION['empresa']."'  ";
    
    if(isset($_REQUEST['id_solicitud_compra']) && $_REQUEST['id_solicitud_compra']!=""){
        $sql.=" and id_solicitud_compra like '%".$_REQUEST['id_solicitud_compra']."%' ";
    }

    if(isset($_REQUEST['descripcion']) && $_REQUEST['descripcion']!=""){
        $sql.=" and descripcion_solicitud like '%".$_REQUEST['descripcion']."%' ";
    }
    
    if(isset($_REQUEST['fecha']) && $_REQUEST['fecha']!=""){
        $sql.=" and fecha_ingreso like '%".$_REQUEST['fecha']."%' ";
    }
    
    if(isset($_REQUEST['concepto']) && $_REQUEST['concepto']!=0){
        $sql.=" and concepto='".$_REQUEST['concepto']."' ";
    }
    
    if(isset($_REQUEST['tipo']) && $_REQUEST['tipo']!=0){
        $sql.=" and tipo_solicitud='".$_REQUEST['tipo']."' ";
    }
    
    if(isset($_REQUEST['estado']) && $_REQUEST['estado']!=0){
        $sql.=" and estado='".$_REQUEST['estado']."'";
    }

    if(isset($_REQUEST['tipo_solicitud_compra']) && $_REQUEST['tipo_solicitud_compra']!=0){
        $sql.=" and tipo_solicitud_compra='".$_REQUEST['tipo_solicitud_compra']."'";
    }
    
    $sql.=" ORDER BY id_solicitud_compra DESC";
    $sql.= " ".$limit;
    
    
}else{
    $sql = "SELECT * FROM solicitudes_compra WHERE rut_empresa = '".$_SESSION['empresa']."' AND rut_empresa='".$_SESSION["empresa"]."'  ORDER BY id_solicitud_compra DESC";
    $sql.= " ".$limit;
}

$res = mysql_query($sql,$con);

?>

<?
//Manejo de errores
if($error==0){
    echo "<div style=' width:100%; height:auto; border-top: solid 3px blue;border-bottom: solid 3px blue;color:blue; text-align:center; font-family:tahoma; font-size:18px;'>";
    echo $mensaje;
    echo "</div>";
}else{
    echo "<div style=' width:100%; height:auto; border-top: solid 3px red ;border-bottom: solid 3px red; color:red; text-align:center;font-family:tahoma; font-size:18px;'>";
    echo $mensaje;  
    echo "</div>";
} 
?>

<!-- boton de agregar solicitud OC  -->

<table id="list_registros" border='0'>
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="7"> </td>
        <td id="list_link">
            <a href="?cat=2&sec=11&action=1">
                <img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Solicitud de Compra">
            </a>
        </td>
    </tr>
</table> 

<style>
.fo
{
    border:1px solid #09F;
    background-color:#FFFFFF;
    color:#000066;
    font-size:11px;
    font-family:Tahoma, Geneva, sans-serif;
    width:80%;
    text-align:center;

}

/* -------------------------------------------- */
/* ----------- Pagination: Digg Style --------- */
/* -------------------------------------------- */
ul    { border:0; margin:0; padding:0; }
#pagination-digg li          { border:0; margin:0; padding:0; font-size:11px; list-style:none; /* savers */ float:left; }
#pagination-digg a           { border:solid 1px #9aafe5; margin-right:2px; }
#pagination-digg .previous-off,
#pagination-digg .next-off   { border:solid 1px #DEDEDE; color:#888888; display:block; float:left; font-weight:bold; margin-right:2px; padding:3px 4px; }
#pagination-digg .next a,
#pagination-digg .previous a { font-weight:bold; }
#pagination-digg .active     { background:#2e6ab1; color:#FFFFFF; font-weight:bold; display:block; float:left; padding:4px 6px; /* savers */ margin-right:2px; }
#pagination-digg a:link,
#pagination-digg a:visited   { color:#0e509e; display:block; float:left; padding:3px 6px; text-decoration:none; }
#pagination-digg a:hover     { border:solid 1px #0e509e; }

</style>

<table id="list_registros" border="1" style="border-collapse:collapse;" >

 <form action="?cat=2&sec=10&filtro=1" method="POST">
    <tr  id="titulo_reg" style="background-color: #fff;">
        <td width="90px" style="font-family:Tahoma; font-size:12px; text-align:center;"><input type="text"   name="id_solicitud_compra"   value='<?=$_REQUEST["id_solicitud_compra"];?>'  class="fo" onFocus='this.value=""'></td>
        <td width="120px" style="text-align:center;"><input type="text"   name="descripcion"   value='<?=$_REQUEST["descripcion"];?>'  class="fo" onFocus='this.value=""'></td>
        <!-- <td style="text-align:center;"><input type="text"   name="fecha"         value='<?/*=$_POST["fecha"];*/?>'        class="fo" onFocus='this.value=""'></td> -->
        <td style="text-align:center;"><input type="date"   name="fecha" value='<?=$_REQUEST["fecha"];?>' class="fo" onFocus='this.value=""'></td>
        <td style="text-align:center;">
            <select name="concepto" style="width:100px;"  class="fo">
                <option value="0" <? if($_REQUEST['concepto']==0) echo " selected "; ?> >---</option>
                <option value="1" <? if($_REQUEST['concepto']==1) echo " selected "; ?> >MANTENCION</option>
                <option value="2" <? if($_REQUEST['concepto']==2) echo " selected "; ?> >REPARACION</option>
                <option value="3" <? if($_REQUEST['concepto']==3) echo " selected "; ?> >SERVICIOS</option>
                <option value="4" <? if($_REQUEST['concepto']==4) echo " selected "; ?> >ACTIVOS</option>
                <option value="5" <? if($_REQUEST['concepto']==5) echo " selected "; ?> >REPUESTOS</option>
                <option value="6" <? if($_REQUEST['concepto']==6) echo " selected "; ?> >FABRICACION</option>
                <option value="7" <? if($_REQUEST['concepto']==7) echo " selected "; ?> >RECTIFICACIÓN</option>
            </select>
        </td>
        <td style="text-align:center;">
            <select name="tipo" style="width:100px;"  class="fo">
                <option value="0" <? if($_REQUEST['tipo']==0) echo " selected "; ?> >---</option>
                <option value="1" <? if($_REQUEST['tipo']==1) echo " selected "; ?> >Nacional</option>
                <option value="2" <? if($_REQUEST['tipo']==2) echo " selected "; ?> >Internacional</option>
            </select>
        </td>
        <td style="text-align:center;">
            <select name="estado" style="width:100px;" class="fo">
                <option value="0" <? if($_REQUEST['estado']==0) echo " selected "; ?> >---</option>
                <option value="1" <? if($_REQUEST['estado']==1) echo " selected "; ?> >Abierta</option>
                <option value="2" <? if($_REQUEST['estado']==2) echo " selected "; ?> >En Espera de Informaci&oacute;n</option>
                <option value="3" <? if($_REQUEST['estado']==3) echo " selected "; ?> >Autorizada</option>
                <option value="4" <? if($_REQUEST['estado']==4) echo " selected "; ?> >Anulada</option>
                <option value="5" <? if($_REQUEST['estado']==5) echo " selected "; ?> >Cerrada</option>
            </select>
        </td>

        <!-- Nuevos Input Select -->

        <td style="text-align:center;">
            <select name="tipo_solicitud_compra" style="width:100px;"  class="fo">
                <option value="0" <? if($_REQUEST['tipo_solicitud_compra']==0) echo " selected "; ?> >---</option>
                <option value="1" <? if($_REQUEST['tipo_solicitud_compra']==1) echo " selected "; ?> >Cotización</option>
                <option value="2" <? if($_REQUEST['tipo_solicitud_compra']==2) echo " selected "; ?> >Compra</option>
                <option value="3" <? if($_REQUEST['tipo_solicitud_compra']==3) echo " selected "; ?> >Solicitud</option>
                <option value="4" <? if($_REQUEST['tipo_solicitud_compra']==4) echo " selected "; ?> >regularización</option>
            </select>
        </td>
 <!--       <td style="text-align:center;">
            <select name="material" style="width:120px;"  class="fo">
                <option value="0" <? /*if($_POST['material']==0) echo " selected "; */?> >---</option>
                <option value="1" <? /*if($_POST['material']==1) echo " selected "; */?> >Electrico</option>
                <option value="2" <? /*if($_POST['material']==2) echo " selected "; */?> >Mecanico</option>
                <option value="3" <? /*if($_POST['material']==3) echo " selected "; */?> >Filtros</option>
                <option value="4" <? /*if($_POST['material']==4) echo " selected "; */?> >Lubricantes</option>
                <option value="5" <? /*if($_POST['material']==5) echo " selected "; */?> >Ferreteria</option>
                <option value="6" <? /*if($_POST['material']==6) echo " selected "; */?> >Otros</option>
            </select>
        </td>
        <td style="text-align:center;">
            <select name="prioridad" style="width:120px;"  class="fo">
                <option value="0" <? /*if($_POST['prioridad']==0) echo " selected "; */?> >---</option>
                <option value="1" <? /*if($_POST['prioridad']==1) echo " selected "; */?> >Urgente</option>
                <option value="2" <? /*if($_POST['prioridad']==2) echo " selected "; */?> >Alta</option>
                <option value="3" <? /*if($_POST['prioridad']==3) echo " selected "; */?> >Normal</option>
            </select>
        </td>
        <td style="text-align:center;">
            <select name="origen" style="width:120px;"  class="fo">
                <option value="0" <? /*if($_POST['origen']==0) echo " selected "; */?> >---</option>
                <option value="1" <? /*if($_POST['origen']==1) echo " selected "; */?> >Pta. Chancado</option>
                <option value="2" <? /*if($_POST['origen']==2) echo " selected "; */?> >Pta. Hormigón</option>
                <option value="3" <? /*if($_POST['origen']==3) echo " selected "; */?> >Pta. Asfalto</option>
                <option value="4" <? /*if($_POST['origen']==4) echo " selected "; */?> >Pta. Seleccionadora</option>
                <option value="5" <? /*if($_POST['origen']==5) echo " selected "; */?> >Pta. Dosificadora</option>
                <option value="6" <? /*if($_POST['origen']==6) echo " selected "; */?> >Taller Obra</option>
                <option value="7" <? /*if($_POST['origen']==7) echo " selected "; */?> >Otro</option>
            </select>
        </td> -->
        
        <td colspan="2" style="text-align:center;"><input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
    </tr>
</form>

<!--Nombre de Columnas-->

<tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
    <td  style="text-align: center;" >#</td>
    <td  style="text-align: center;" >Descripción</td>
    <td  style="text-align: center;" >Fecha Ing.</td>
    <td  style="text-align: center;" >Concepto</td>
    <td  style="text-align: center;" >Tipo</td>
    <td  style="text-align: center;" >Estado</td>
    <td  style="text-align: center;" >Tipo Solicitud Compra</td>
    <!-- <td style="text-align: center;" >MATERIAL</td>
    <td style="text-align: center;" >PRIORIDAD</td>
    <td style="text-align: center;" >ORIGEN</td> -->
    <td     style="text-align: center;" >Editar</td>
    <td     style="text-align: center;" >Anular</td>
</tr>

<?
if(mysql_num_rows($res)>0){

    $i=1;
    while($row = mysql_fetch_assoc($res)){
        ?>    

        <style>
        #row:hover{
            background-color:#FF000;
        }
        </style>

        <tr style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
            <td style="text-align: center;"><?=$row["id_solicitud_compra"];?></td>
            <td style="text-align: left;"><?=substr($row['descripcion_solicitud'], 0, 20 )."...";?></td>
            <td style="text-align: center;" width="100px"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
            <td style="text-align: center;">
                <?   
                if($row['concepto']==1)
                    echo "MANTENCION";
                if($row['concepto']==2)
                    echo "REPARACION";
                if($row['concepto']==3)
                    echo "SERVICIOS";
                if($row['concepto']==4)
                    echo "ACTIVOS";
                if($row['concepto']==5)
                    echo "REPUESTOS";
                if($row['concepto']==6)
                    echo "FABRICACION";
                if($row['concepto']==7)
                    echo "RECTIFICACIÓN";
                ?>
            </td>
            <td style="text-align: center;">
                <?
                if($row['tipo_solicitud']==1)
                    echo "Nacional";
                if($row['tipo_solicitud']==2)
                    echo "Internacional";
                ?>
            </td>
            <td style="text-align: center;">
                <?  
                if($row['estado']==1)
                    echo "Abierta";
                if($row['estado']==2)
                    echo "En espera de informaci&oacute;n";
                if($row['estado']==3)
                    echo "Autorizada";
                if($row['estado']==4)
                    echo "Anulada";
                if($row['estado']==5)
                    echo "Cerrada";
                ?>
            </td>
            <td style="text-align: center;">
                <?  
                if($row['tipo_solicitud_compra']==1)
                    echo "Cotizaci&oacute;n";
                if($row['tipo_solicitud_compra']==2)
                    echo "Compra";
                if($row['tipo_solicitud_compra']==3)
                    echo "Solicitud";
                if($row['tipo_solicitud_compra']==4)
                    echo "Regulaci&oacute;n";
                ?>
            </td>
           <!--  <td>
            </td>      
            <td>
            </td> 
            <td>
            </td> -->
            <td style="text-align:center;"><a href="?cat=2&sec=11&action=2&id_solic=<?=$row['id_solicitud_compra'];?>&action=2"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Solicitud de Compra"></a></td>
            <td style="text-align:center;"><a href="?cat=2&sec=10&elim=1&id_solic=<?=$row['id_solicitud_compra'];?>"><img src="img/delete2.png" width="24px" height="24px" border="0" class="toolTIP" title="Eliminar Solicitud de Compra"></a></td>
        </tr>  
        <?    
    }
}else{
    ?>
    <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="9">No existen Solicitudes de Compra a Ser Desplegadas</td>
    </tr>
    <?   
}
?>
</table>
<br>
<table width="900px" align="center" border="0" >
    <tr>
        <td>
            <?

//UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACIÓN SIEMPRE Y CUANDO HAYA MÁS DE UNA PÁGINA
            if($num_rows != 0){
             $nextpage= $page +1;
             $prevpage= $page -1;

             ?><ul id="pagination-digg"><?
//SI ES LA PRIMERA PÁGINA DESHABILITO EL BOTON DE PREVIOUS, MUESTRO EL 1 COMO ACTIVO Y MUESTRO EL RESTO DE PÁGINAS
             if ($page == 1) {
                ?>
                <li class="previous-off">&laquo; Previous</li>
                <li class="active">1</li> <?
                for($i= $page+1; $i<= $lastpage ; $i++){?>
                <li><a href="?&cat=2&sec=10&page=<? echo $i;?>&filtro=1&id_solicitud_compra=<?=$_REQUEST["id_solicitud_compra"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&fecha=<?=$_REQUEST["fecha"];?>&concepto=<?=$_REQUEST['concepto'];?>&tipo=<?=$_REQUEST['tipo'];?>&estado=<?=$_REQUEST['estado'];?>&tipo_solicitud de _compra=<?=$_REQUEST['tipo_solicitud_compra'];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=2&sec=10&page=<? echo $nextpage;?>&filtro=1&id_solicitud_compra=<?=$_REQUEST["id_solicitud_compra"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&fecha=<?=$_REQUEST["fecha"];?>&concepto=<?=$_REQUEST['concepto'];?>&tipo=<?=$_REQUEST['tipo'];?>&estado=<?=$_REQUEST['estado'];?>&tipo_solicitud de _compra=<?=$_REQUEST['tipo_solicitud_compra'];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=2&sec=10&page=<? echo $prevpage;?>&filtro=1&id_solicitud_compra=<?=$_REQUEST["id_solicitud_compra"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&fecha=<?=$_REQUEST["fecha"];?>&concepto=<?=$_REQUEST['concepto'];?>&tipo=<?=$_REQUEST['tipo'];?>&estado=<?=$_REQUEST['estado'];?>&tipo_solicitud de _compra=<?=$_REQUEST['tipo_solicitud_compra'];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=2&sec=10&page=<? echo $i;?>&filtro=1&id_solicitud_compra=<?=$_REQUEST["id_solicitud_compra"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&fecha=<?=$_REQUEST["fecha"];?>&concepto=<?=$_REQUEST['concepto'];?>&tipo=<?=$_REQUEST['tipo'];?>&estado=<?=$_REQUEST['estado'];?>&tipo_solicitud de _compra=<?=$_REQUEST['tipo_solicitud_compra'];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=2&sec=10&page=<? echo $nextpage;?>&filtro=1&id_solicitud_compra=<?=$_REQUEST["id_solicitud_compra"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&fecha=<?=$_REQUEST["fecha"];?>&concepto=<?=$_REQUEST['concepto'];?>&tipo=<?=$_REQUEST['tipo'];?>&estado=<?=$_REQUEST['estado'];?>&tipo_solicitud de _compra=<?=$_REQUEST['tipo_solicitud_compra'];?>">Next &raquo;</a></li><?
        }else{
            ?> <li class="next-off">Next &raquo;</li><?
        }
    }   
    ?></ul></div><?
}
?>
</td>
</tr>

</table>


<!-- <br>
<table id="list_registros" border="1">
<tr>
    <td>
        
        <ul id="pagination-digg">
            <li class="previous-off">«Previous</li>
            <li class="active">1</li>
            <li><a href="?cat=2&sec=10&page=2">2</a></li>
            <li><a href="?cat=2&sec=10&page=3">3</a></li>
            <li><a href="?cat=2&sec=10&page=4">4</a></li>
            <li><a href="?cat=2&sec=10&page=5">5</a></li>
            <li><a href="?cat=2&sec=10&page=6">6</a></li>
            <li><a href="?cat=2&sec=10&page=7">7</a></li>
            <li class="next"><a href="?page=8">Next »</a></li>
        </ul>
    </td>
</tr>

</table> -->


