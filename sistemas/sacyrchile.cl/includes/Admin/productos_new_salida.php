<?php
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_REQUEST['page'])){
    $page= $_REQUEST['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT p.*,c.descripcion_cc,b.descripcion_bodega,l.descripcion_lf,pp.descripcion FROM  ((((productos_new_salidas  
  p LEFT JOIN centros_costos c ON p.id_cc=c.id_cc) LEFT JOIN bodegas b ON b.cod_bodega=p.cod_bodega)
   LEFT JOIN lugares_fisicos l ON p.id_lf=l.id_lf) LEFT JOIN productos pp ON p.cod_producto=pp.cod_producto) WHERE 1=1 ";

if(!empty($_REQUEST['fecha_salida']))
{
    $fecha_factura=($_REQUEST['fecha_salida']);
    $consulta.=" AND p.fecha_salida like'%".$fecha_factura."%' ";
}

if(!empty($_REQUEST['cantidad']))
$consulta.=" AND p.cantidad ='".$_REQUEST['cantidad']."' ";


if(!empty($_REQUEST['descripcion_cc']))
$consulta.=" AND c.descripcion_cc like'%".$_REQUEST['descripcion_cc']."%' ";


if(!empty($_REQUEST['descripcion_bodega']))
$consulta.=" AND b.descripcion_bodega like'%".$_REQUEST['descripcion_bodega']."%' ";


if(!empty($_REQUEST['descripcion']))
$consulta.=" AND pp.descripcion like'%".$_REQUEST['descripcion']."%' ";



if(!empty($_REQUEST['descripcion_lf']))
$consulta.=" AND l.descripcion_lf like'%".$_REQUEST['descripcion_lf']."%' ";


if(!empty($_REQUEST['id_ot']))
$consulta.=" AND p.id_ot '".$_REQUEST['id_ot']."' ";

if(!empty($_REQUEST['observaciones']))
$consulta.=" AND p.observaciones like'%".$_REQUEST['observaciones']."%' ";


$consulta.=" AND 1=1 and codbar_productonew=".$_REQUEST['codbar_productonew'];
$consulta.= " AND estado_salida=1";

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


if($_REQUEST['id_salida'])
{

    $fecha=date("Y-m-d H:i:s");
    $sql =" UPDATE productos_new_salidas SET ";
    $sql.=" estado_salida=2,";
    $sql.=" usuario_elimina='".$_SESSION['user']."',";
    $sql.=" fecha_elimina='".$fecha."'";
    $sql.=" WHERE id_salida=".$_REQUEST['id_salida']." AND rut_empresa='".$_SESSION['empresa']."'";
    mysql_query($sql);
    echo "<div style=' width:100%; height:auto; border-top: solid 3px blue;border-bottom: solid 3px blue;color:blue; text-align:center; font-family:tahoma; font-size:18px;'>";
    echo "Eliminación correcta de la salida Nº: ".$_REQUEST['id_salida'];
    echo "</div>";

    $sel_prd2 = "SELECT cantidad, total FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
    $sel_prd2.= "AND rut_empresa='".$_SESSION["empresa"]."'";           
    $res_prd2 = mysql_query($sel_prd2);
    $row_prd2 = mysql_fetch_array($res_prd2);   

    $sel_salida = "SELECT cantidad, total FROM productos_new_salidas WHERE id_salida=".$_REQUEST['id_salida']." AND rut_empresa='".$_SESSION['empresa']."' ";
    $res_salida = mysql_query($sel_salida);
    $row_salida = mysql_fetch_assoc($res_salida);
    
    $cantidad = $row_prd2["cantidad"] + $row_salida["cantidad"];
    $total = $row_prd2["total"] + $row_salida["total"];

    //Actualiza: Cantidad y Total de la tabla Productos_New.  
    $up_prdIn = "UPDATE productos_new SET cantidad='".$cantidad."', total='".$total."' ";
    $up_prdIn.= "WHERE codbar_productonew='".$_GET["codbar_productonew"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
    mysql_query($up_prdIn);

    $sql_even2 = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
    $sql_even2.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
    $sql_even2.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new', '".$_GET["codbar_productonew"]."', '3'";
    $sql_even2.= ", 'UPDATE:cantidad=".$cantidad.", total=".$total."'";
    $sql_even2.= ",'".$_SERVER['REMOTE_ADDR']."', 'Update', '1', '".date('Y-m-d H:i:s')."')";
    mysql_query($sql_even2, $con); 

    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new_salidas', '".$_REQUEST['id_salida']."', '3'";
    $sql_even.= ", 'UPDATE:estado_salida=2,";
    $sql_even.=" usuario_elimina=".$_SESSION['user'].",";
    $sql_even.=" fecha_elimina=".$fecha." ";
    $sql_even.= ",'".$_SERVER['REMOTE_ADDR']."', 'Update', '1', '".date('Y-m-d H:i:s')."')";
    mysql_query($sql_even, $con);  

}

//Consulta que contiene el sql
$msg="";
$error="";

/*
$sql ="SELECT p.*,c.descripcion_cc,b.descripcion_bodega,l.descripcion_lf,pp.descripcion
 FROM productos_new_salidas p ,centros_costos c ,bodegas b,lugares_fisicos l,productos pp
  WHERE 1=1 AND p.id_cc=c.id_cc AND b.cod_bodega=p.cod_bodega AND p.id_lf=l.id_lf AND p.cod_producto=pp.cod_producto ";*/
  
  $sql=" SELECT p.*,c.descripcion_cc,b.descripcion_bodega,l.descripcion_lf,pp.descripcion FROM  ((((productos_new_salidas  
  p LEFT JOIN centros_costos c ON p.id_cc=c.id_cc) LEFT JOIN bodegas b ON b.cod_bodega=p.cod_bodega)
   LEFT JOIN lugares_fisicos l ON p.id_lf=l.id_lf) LEFT JOIN productos pp ON p.cod_producto=pp.cod_producto) WHERE 1=1 ";


//Filtros
if(!empty($_REQUEST['fecha_salida']))
{
	$fecha_factura=($_REQUEST['fecha_salida']);
	$sql.=" AND p.fecha_salida like'%".$fecha_factura."%' ";
}

if(!empty($_REQUEST['cantidad']))
$sql.=" AND p.cantidad ='".$_REQUEST['cantidad']."' ";


if(!empty($_REQUEST['descripcion_cc']))
$sql.=" AND c.descripcion_cc like'%".$_REQUEST['descripcion_cc']."%' ";


if(!empty($_REQUEST['descripcion_bodega']))
$sql.=" AND b.descripcion_bodega like'%".$_REQUEST['descripcion_bodega']."%' ";


if(!empty($_REQUEST['descripcion']))
$sql.=" AND pp.descripcion like'%".$_REQUEST['descripcion']."%' ";



if(!empty($_REQUEST['descripcion_lf']))
$sql.=" AND l.descripcion_lf like'%".$_REQUEST['descripcion_lf']."%' ";


if(!empty($_REQUEST['id_ot']))
$sql.=" AND p.id_ot ='".$_REQUEST['id_ot']."' ";

if(!empty($_REQUEST['observaciones']))
$sql.=" AND p.observaciones like'%".$_REQUEST['observaciones']."%' ";


$sql.=" AND 1=1 and codbar_productonew=".$_REQUEST['codbar_productonew'];
$sql.= " AND estado_salida=1";
$sql.= " ".$limit;




//Efectua la Consulta
$res = mysql_query($sql,$con);
?>
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
<br /><br />
<form action="?cat=3&sec=34&filtro=1&codbar_productonew=<?=$_GET["codbar_productonew"];?>" method="POST">
    <table width="95%">
        <tr>
            <td id="list_link"  colspan="100%">
                <a href='?cat=3&sec=15'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Productos'></a>
                <a href="?cat=3&sec=36&codbar_productonew=<?=$_GET['codbar_productonew'];?>"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Salida"></a>
            </td>
        </tr>
    </table>

    <table id="list_registros" style=" border-collapse:collapse" border="1" >
        <tr style='font-family:tahoma;font-size:12px;'>
            <td style="text-align:left; font-weight:bold; ">Filtro:</td>
            <td style="text-align:center" >
                <input type="date" name='fecha_salida' value='<? echo $_REQUEST['fecha_salida']?>' class="fo" style="width:100px;">   
            </td>
            <td style="text-align:center">
                <input name='cantidad' value='<? echo $_REQUEST['cantidad']?>' class="fo" style="width:90%;">
            </td>
            <td style="text-align:center">
		        <!-- <input name='descripcion_cc' value='<?/* echo $_REQUEST['descripcion_cc']*/?>' class="fo" style="width:90%;"> -->
                <select name="descripcion_cc"  class="fo" style="width:90%;">
                <option value="0"  class="fo">---</option>
            <?
                $s = "SELECT * FROM centros_costos WHERE  rut_empresa='".$_SESSION['empresa']."' ORDER BY descripcion_cc";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_array($r)){
            ?>  <option value="<?=$roo['descripcion_cc'];?>"   <? if($_REQUEST['descripcion_cc']==$roo['descripcion_cc']) echo " selected" ;?> class="fo"><?=$roo['descripcion_cc'];?></option> <?    
                }
        
            ?>
            </select>

            </td>
            <td style="text-align:center" width="150px">
                <!-- <input name='descripcion_bodega' value='<? /*echo $_REQUEST['descripcion_bodega']*/?>' class="fo" style="width:90%;"> -->
                <select name="descripcion_bodega"  class="fo" style="width:90%;">
                <option value="0"  class="fo">---</option>
            <?
                $sel2 = "SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION['empresa']."' ORDER BY descripcion_bodega";
                $res2 = mysql_query($sel2,$con);
                
                while($row2 = mysql_fetch_array($res2)){
            ?>  <option value="<?=$row2['descripcion_bodega'];?>"   <? if($_REQUEST['descripcion_bodega']==$row2['descripcion_bodega']) echo " selected" ;?> class="fo"><?=$row2['descripcion_bodega'];?></option> <?    
                }
        
            ?>
            </select>
            </td>
        
        <td style="text-align:center">
        <!-- <input name='descripcion' value='<? /*echo $_REQUEST['descripcion']*/?>' class="fo" style="width:90%;"> -->
             <select name="descripcion"  class="fo" style="width:90%;">
                <option value="0"  class="fo">---</option>
            <?
                $sel3 = "SELECT * FROM productos WHERE rut_empresa='".$_SESSION['empresa']."' ORDER BY descripcion";
                $res3 = mysql_query($sel3,$con);
                
                while($row3 = mysql_fetch_array($res3)){
            ?>  <option value="<?=$row3['descripcion'];?>"   <? if($_REQUEST['descripcion']==$row3['descripcion']) echo " selected" ;?> class="fo"><?=$row3['descripcion'];?></option> <?    
                }
        
            ?>
            </select>
        </td>
        
        <td style="text-align:center">
        <!-- <input name='descripcion_lf' value='<? /*echo $_REQUEST['descripcion_lf']*/?>' class="fo" style="width:90%;"> -->
            <select name="descripcion_lf"  class="fo" style="width:90%;">
                <option value="0"  class="fo">---</option>
            <?
                $sel4 = "SELECT * FROM lugares_fisicos WHERE rut_empresa='".$_SESSION['empresa']."' ORDER BY descripcion_lf";
                $res4 = mysql_query($sel4,$con);
                
                while($row4 = mysql_fetch_array($res4)){
            ?>  <option value="<?=$row4['descripcion_lf'];?>"   <? if($_REQUEST['descripcion_lf']==$row4['descripcion_lf']) echo " selected" ;?> class="fo"><?=$row4['descripcion_lf'];?></option> <?    
                }
        
            ?>
            </select>
        </td>
        
        <td style="text-align:center">
        <input type="text" name='id_ot' value='<? echo $_REQUEST['id_ot']?>' class="fo" style="width:90%;">
        </td>
        <td style="text-align:center;" width="150px">
        <input type="text" name='observaciones' value='<? echo $_REQUEST['observaciones']?>' class="fo" style="width:90%;">
        </td>
    	
        <td style="text-align:right" colspan="4"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td colspan='3' ></td>    
        <td style="text-align:center;" colspan='2'>Asignación</td>    
        <td style="text-align:center;" colspan='3'>Destinación</td>    
        <td style="text-align:center;" colspan='3'></td>    
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td style="text-align:center;" >#</td>
        <td style="text-align:center;">Fecha Salida</td>
        <td style="text-align:center" >Cantidad</td>
        <td style="text-align:center">Centro Costos</td>

        <td style="text-align:center">Bodegas</td>
        <td style="text-align:center">Activo</td>
        <td style="text-align:center">Lugar Físico</td>
        <td style="text-align:center" >Nº O.T.</td>
        <td style="text-align:center">Observaciones</td>
        <td style="text-align:center">Editar</td>
        <td style="text-align:center">Eliminar</td>
       <!-- <td style="text-align:center" colspan="2">Editar</td>
        <!--<td width="100px">Editar</td> -->
    </tr>    

<?
if(mysql_num_rows($res)>0){
	//Desempilamos los datos
	$i=1;
	while($row=mysql_fetch_array($res))
	{
    echo "<tr   style='font-family:tahoma;font-size:12px;'>";
    echo "    <td style='text-align:center'>".$i++."</td>";
    echo "    <td style='text-align:center'>".date("d-m-Y",strtotime($row['fecha_salida']))."</td>";
    echo "    <td style='text-align:center'>".$row['cantidad']."</td>";
    echo "    <td style='text-align:center'>".$row['descripcion_cc']."</td>";
	echo "    <td style='text-align:left'>".$row['descripcion_bodega']."</td>";
    echo "    <td style='text-align:center' >".$row['descripcion']."</td>";
    echo "    <td style='text-align:center'>".$row['descripcion_lf']."</td>";
	echo "    <td style='text-align:center'>".$row['id_ot']."</td>";
	echo "    <td style='text-align:left'>".$row['observaciones']."</td>";

	echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=36&action=2&id_salida=".$row['id_salida']."&codbar_productonew=".$_GET["codbar_productonew"]."' >
					    <img src='img/edit.png' width='24px' height='24px' border='0' class='toolTIP' title='Editar Salida'>
					</a>
				</td>";
		echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=34&action=2&id_salida=".$row['id_salida']."&codbar_productonew=".$_GET["codbar_productonew"]."' >
					    <img src='img/eliminar.png' width='24px' height='24px' border='0' class='toolTIP' title='Eliminar Salida'>
					</a>
				</td>";
    echo "</tr>";
	}

}else{
 
 ?>
<tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="100%">No existen Salidas de Productos a Ser Desplegadas</td>
    </tr>
<?
  }
?>

</table>
</form>
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
                <li><a href="?&cat=3&sec=34&page=<? echo $i;?>&filtro=1&fecha_salida=<?=$_REQUEST["fecha_salida"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>&descripcion_bodega=<?=$_REQUEST["descripcion_bodega"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&id_ot=<?=$_REQUEST["id_ot"];?>&observaciones=<?=$_REQUEST["observaciones"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=3&sec=34&page=<? echo $nextpage;?>&filtro=1&fecha_salida=<?=$_REQUEST["fecha_salida"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>&descripcion_bodega=<?=$_REQUEST["descripcion_bodega"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&id_ot=<?=$_REQUEST["id_ot"];?>&observaciones=<?=$_REQUEST["observaciones"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=3&sec=34&page=<? echo $prevpage;?>&filtro=1&fecha_salida=<?=$_REQUEST["fecha_salida"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>&descripcion_bodega=<?=$_REQUEST["descripcion_bodega"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&id_ot=<?=$_REQUEST["id_ot"];?>&observaciones=<?=$_REQUEST["observaciones"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=3&sec=34&page=<? echo $i;?>&filtro=1&fecha_salida=<?=$_REQUEST["fecha_salida"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>&descripcion_bodega=<?=$_REQUEST["descripcion_bodega"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&id_ot=<?=$_REQUEST["id_ot"];?>&observaciones=<?=$_REQUEST["observaciones"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=3&sec=34&page=<? echo $nextpage;?>&filtro=1&fecha_salida=<?=$_REQUEST["fecha_salida"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>&descripcion_bodega=<?=$_REQUEST["descripcion_bodega"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&id_ot=<?=$_REQUEST["id_ot"];?>&observaciones=<?=$_REQUEST["observaciones"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>">Next &raquo;</a></li><?
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