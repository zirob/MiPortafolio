<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>

<?php
// var_dump($_POST);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_REQUEST['page'])){
    $page= $_REQUEST['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT p.*, p.fecha_ingreso AS fecha_ingreso, u.* FROM productos_new_entradas p, usuarios u  WHERE  ";
$consulta.= "u.usuario=p.usuario_ingreso AND p.rut_empresa=u.rut_empresa ";
$consulta.=" AND codbar_productonew=".$_REQUEST['codbar_productonew'];

if(!empty($_REQUEST['fecha_factura']))
{
    $fecha_factura=($_REQUEST['fecha_factura']);
    $consulta.=" AND p.fecha_factura like'%".$fecha_factura."%' ";
}

if(!empty($_REQUEST['factura']))
$consulta.=" AND p.factura like'%".$_REQUEST['factura']."%' ";


if(!empty($_REQUEST['cantidad']))
$consulta.=" AND p.cantidad ='".$_REQUEST['cantidad']."' ";


if(!empty($_REQUEST['precio_pmp']))
$consulta.=" AND p.precio_pmp ='".$_REQUEST['precio_pmp']."' ";

if(!empty($_REQUEST['total']))
$consulta.=" AND p.total ='".$_REQUEST['total']."' ";


if(!empty($_REQUEST['usuario_ingreso']))
$consulta.=" AND u.nombre like'%".$_REQUEST['usuario_ingreso']."%' ";

if(!empty($_REQUEST['fecha_ingreso']))
$consulta.=" AND p.fecha_ingreso like'%".$_REQUEST['fecha_ingreso']."%' ";



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


//Consulta que contiene el sql
$msg="";
$error="";

$sql = "SELECT p.*, p.fecha_ingreso AS fecha_ingreso_prd, u.* FROM productos_new_entradas p, usuarios u  WHERE ";
$sql.= " u.usuario=p.usuario_ingreso AND p.rut_empresa=u.rut_empresa ";
$sql.=" AND codbar_productonew='".$_REQUEST['codbar_productonew']."'";
//Filtros
if(!empty($_REQUEST['fecha_factura']))
{
	$fecha_factura=($_REQUEST['fecha_factura']);
	$sql.=" AND p.fecha_factura like'%".$fecha_factura."%' ";
}

if(!empty($_REQUEST['factura']))
$sql.=" AND p.factura like'%".$_REQUEST['factura']."%' ";


if(!empty($_REQUEST['cantidad']))
$sql.=" AND p.cantidad ='".$_REQUEST['cantidad']."' ";


if(!empty($_REQUEST['precio_pmp']))
$sql.=" AND p.precio_pmp ='".$_REQUEST['precio_pmp']."' ";

if(!empty($_REQUEST['total']))
$sql.=" AND p.total ='".$_REQUEST['total']."' ";


if(!empty($_REQUEST['usuario_ingreso']))
$sql.=" AND u.nombre like'%".$_REQUEST['usuario_ingreso']."%' ";

if(!empty($_REQUEST['fecha_ingreso']))
$sql.=" AND p.fecha_ingreso like'%".$_REQUEST['fecha_ingreso']."%' ";


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
<form action="?cat=3&sec=33&filtro=1&codbar_productonew=<?=$_GET["codbar_productonew"];?>" method="POST">
<table width="95%" border='0'>
    <tr>
        <td id="list_link"  colspan="100%">
            <a href='?cat=3&sec=15'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Productos'></a>
            <a href="?cat=3&sec=35&codbar_productonew=<?=$_GET["codbar_productonew"];?>"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Entradas de Productos"></a>
        </td>
    </tr>
</table>

<table id="list_registros" style=" border-collapse:collapse" border="1" >

    <tr  style='font-family:tahoma;font-size:12px;'>
        <td style="text-align:left; font-weight:bold; ">Filtro:</td>
        <td style="text-align:center">
        <input type="date" name='fecha_factura' value='<? echo $_REQUEST['fecha_factura']?>' class="fo" style="width:100px;">   
        </td>
        <td style="text-align:center">
       <input name='factura' value='<? echo $_REQUEST['factura']?>' class="fo" style="width:90%;" onKeyPress='ValidaSoloNumeros()'>
        </td>
        
         <td style="text-align:center">
		<input name='cantidad' value='<? echo $_REQUEST['cantidad']?>' class="fo" style="width:90%;" onKeyPress='ValidaSoloNumeros()'>
        </td>
        
        <td style="text-align:center">
        <input name='precio_pmp' value='<? echo $_REQUEST['precio_pmp']?>' class="fo" style="width:90%;" onKeyPress='ValidaSoloNumeros()'>
        </td>
        
        <td style="text-align:center">
        <input name='total' value='<? echo $_REQUEST['total']?>' class="fo" style="width:90%;" onKeyPress='ValidaSoloNumeros()'>
        </td>
        
        <td style="text-align:center;" width="150px">
        <input name='usuario_ingreso' value='<? echo $_REQUEST['usuario_ingreso']?>' class="fo" style="width:90%;" >
        </td>
        
        <td style="text-align:center">
        <input type="date" name='fecha_ingreso' value='<? echo $_REQUEST['fecha_ingreso']?>' class="fo" style="width:90%;">
        </td>
    	
        <td style="text-align:right" colspan="4"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td style="text-align:center; ">#</td>
        <td style="text-align:center; ">Fecha Factura</td>
        <td style="text-align:center">Factura</td>
        <td style="text-align:center">Cantidad</td>
        <td style="text-align:center">Valor Unitario</td>
        <td style="text-align:center">Total</td>
        <td style="text-align:center">Usuario Ingreso</td>
        <td style="text-align:center">Fecha Ingreso</td>
        <td style="text-align:center"></td>
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
    echo "    <td style='text-align:center'>".date("d-m-Y",strtotime($row['fecha_factura']))."</td>";
    echo "    <td style='text-align:center'>".$row['factura']."</td>";
    echo "    <td style='text-align:center'>".$row['cantidad']."</td>";
	echo "    <td style='text-align:right'>".$row['precio_pmp']."</td>";
    echo "    <td style='text-align:right' >".$row['total']."</td>";
    echo "    <td style='text-align:center'>".$row['nombre']."</td>";
	echo "    <td style='text-align:center'>".date("d-m-Y",strtotime($row['fecha_ingreso_prd']))."</td>";

	echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=35&action=2&id_entrada=".$row['id_entrada']."&codbar_productonew=".$_GET['codbar_productonew']."' >
					    <img src='img/edit.png' width='24px' height='24px' border='0' class='toolTIP' title='Editar Entrada'>
					</a>
				</td>";
    echo "</tr>";
    }
}else{
 
 ?>
<tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="100%">No existen Entradas de Productos a Ser Desplegadas</td>
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
                <li><a href="?&cat=3&sec=33&page=<? echo $i;?>&filtro=1&fecha_factura=<?=$_REQUEST["fecha_factura"];?>&factura=<?=$_REQUEST["factura"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&precio_pmp=<?=$_REQUEST["precio_pmp"];?>&total=<?=$_REQUEST["total"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=3&sec=33&page=<? echo $nextpage;?>&filtro=1&fecha_factura=<?=$_REQUEST["fecha_factura"];?>&factura=<?=$_REQUEST["factura"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&precio_pmp=<?=$_REQUEST["precio_pmp"];?>&total=<?=$_REQUEST["total"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=3&sec=33&page=<? echo $prevpage;?>&filtro=1&fecha_factura=<?=$_REQUEST["fecha_factura"];?>&factura=<?=$_REQUEST["factura"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&precio_pmp=<?=$_REQUEST["precio_pmp"];?>&total=<?=$_REQUEST["total"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=3&sec=33&page=<? echo $i;?>&filtro=1&fecha_factura=<?=$_REQUEST["fecha_factura"];?>&factura=<?=$_REQUEST["factura"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&precio_pmp=<?=$_REQUEST["precio_pmp"];?>&total=<?=$_REQUEST["total"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=3&sec=33&page=<? echo $nextpage;?>&filtro=1&fecha_factura=<?=$_REQUEST["fecha_factura"];?>&factura=<?=$_REQUEST["factura"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&precio_pmp=<?=$_REQUEST["precio_pmp"];?>&total=<?=$_REQUEST["total"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>">Next &raquo;</a></li><?
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