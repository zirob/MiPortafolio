<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>

<?php
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT p.*,df.descripcion_familia ,ds.descripcion_subfamilia   FROM productos_new p , familia df, subfamilia ds
WHERE  p.rut_empresa='".$_SESSION['empresa']."' AND  df.id_familia=p.id_familia AND ds.id_subfamilia=p.id_subfamilia  ";
if(!empty($_REQUEST['codbar_productonew']))
$consulta.=" AND p.codbar_productonew like'%".$_REQUEST['codbar_productonew']."%' ";

if(!empty($_REQUEST['descripcion']))
$consulta.=" AND p.descripcion like'%".$_REQUEST['descripcion']."%' ";

if(!empty($_REQUEST['codigo_interno']))
$consulta.=" AND p.codigo_interno like'%".$_REQUEST['codigo_interno']."%' ";


if(!empty($_REQUEST['id_familia']))
$consulta.=" AND df.id_familia like'%".$_REQUEST['id_familia']."%' ";


if(!empty($_REQUEST['descripcion_subfamilia']))
$sql.=" AND ds.descripcion_subfamilia like'%".$_REQUEST['descripcion_subfamilia']."%' ";



if(!empty($_REQUEST['pasillo']))
$consulta.=" AND p.pasillo like'%".$_REQUEST['pasillo']."%' ";

if(!empty($_REQUEST['casillero']))
$consulta.=" AND p.casillero like'%".$_REQUEST['casillero']."%' ";

if(!empty($_REQUEST['observaciones']))
$consulta.=" AND p.observaciones like'%".$_REQUEST['observaciones']."%' ";


$consulta.=" AND 1=1 ";

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

$sql ="SELECT p.*,df.descripcion_familia ,ds.descripcion_subfamilia   FROM productos_new p , familia df, subfamilia ds
WHERE  p.rut_empresa='".$_SESSION['empresa']."' AND  df.id_familia=p.id_familia AND ds.id_subfamilia=p.id_subfamilia ";


//Filtros
if(!empty($_REQUEST['codbar_productonew']))
$sql.=" AND p.codbar_productonew like'%".$_REQUEST['codbar_productonew']."%' ";

if(!empty($_REQUEST['descripcion']))
$sql.=" AND p.descripcion like'%".$_REQUEST['descripcion']."%' ";

if(!empty($_REQUEST['codigo_interno']))
$sql.=" AND p.codigo_interno like'%".$_REQUEST['codigo_interno']."%' ";


if(!empty($_REQUEST['id_familia']))
$sql.=" AND df.id_familia like'%".$_REQUEST['id_familia']."%' ";


if(!empty($_REQUEST['descripcion_subfamilia']))
$sql.=" AND ds.descripcion_subfamilia like'%".$_REQUEST['descripcion_subfamilia']."%' ";




if(!empty($_REQUEST['pasillo']))
$sql.=" AND p.pasillo like'%".$_REQUEST['pasillo']."%' ";

if(!empty($_REQUEST['casillero']))
$sql.=" AND p.casillero like'%".$_REQUEST['casillero']."%' ";

if(!empty($_REQUEST['observaciones']))
$sql.=" AND p.observaciones like'%".$_REQUEST['observaciones']."%' ";


$sql.=" AND 1=1 ";
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
    <form action="?cat=4&sec=4&filtro=1" method="POST">

<table id="list_registros" style=" border-collapse:collapse" border="1" >

    <tr  style='font-family:tahoma;font-size:12px;'>
        <td style="text-align:left; font-weight:bold; ">Filtro:</td>
        <td style="text-align:center"><input name='codbar_productonew' value='<? echo $_REQUEST['codbar_productonew']?>' class="fo" style="width:90px;" onKeyPress='ValidaSoloNumeros()'></td>
        <td style="text-align:center">
        <input name='descripcion' value='<? echo $_REQUEST['descripcion']?>' class="fo" style="width:100px;">   
        </td>
        <td style="text-align:center">
       <input name='codigo_interno' value='<? echo $_REQUEST['codigo_interno']?>' class="fo" style="width:90px;">
        </td>
        
         <td style="text-align:center">
         <select  name="id_familia" class="fo" style="width:80px;">
          <option value=""  class="fo">---</option>
            <?
                $s = "SELECT * FROM familia WHERE 1=1 ORDER BY descripcion_familia";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r))
				{
                    ?>  <option value="<?=$roo['id_familia'];?>"   <? if($_REQUEST['id_familia']==$roo['id_familia']) echo " selected" ;?> class="foo"><?=$roo['descripcion_familia'];?></option> <?    
                }
        
                ?>
         </select>
	
        </td>
        
        <td style="text-align:center"> <input type='text' name='descripcion_subfamilia' class="fo" style="width:90px;" value='<?echo $_REQUEST["descripcion_subfamilia"];?>'><td style="text-align:center">
        <input name='pasillo' value='<? echo $_REQUEST['pasillo']?>' class="fo" style="width:50px;" >
        </td>
        
        <td style="text-align:center">
        <input name='casillero' value='<? echo $_REQUEST['casillero']?>' class="fo" style="width:50px;">
        </td>
        
        <td style="text-align:center">
        <input name='observaciones' value='<? echo $_REQUEST['observaciones']?>' class="fo" style="width:150px;">
        </td>
    	
        <td style="text-align:right" colspan="4"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td style="text-align:center; ">#</td>
        <td style="text-align:center; ">Codigo de<br /> Barras</td>
        <td style="text-align:center">Descripcion</td>
        <td style="text-align:center">Codigo Interno</td>
        <td style="text-align:center">Familia</td>
        <td style="text-align:center">SubFamilia</td>
        <td style="text-align:center">Pasillo</td>
        <td style="text-align:center">Casillero</td>
        <td style="text-align:center">Observacion</td>
        <td style="text-align:center">Cantidad</td>
        <td style="text-align:center">PMP</td>
        <td style="text-align:center">Total</td>
        <td style="text-align:center"></td>
        <!--<td width="100px">Editar</td> -->
    </tr>    

<?
if(mysql_num_rows($res)!= NULL){
	//Desempilamos los datos
	$i=1;
	while($row=mysql_fetch_array($res))
	{
    echo "<tr   style='font-family:tahoma;font-size:12px;'>";
    echo "    <td style='text-align:center'>".$i++."</td>";
    echo "    <td style='text-align:center'>".$row['codbar_productonew']."</td>";
    echo "    <td style='text-align:left'>".$row['descripcion']."</td>";
    echo "    <td style='text-align:center'>".$row['codigo_interno']."</td>";
	echo "    <td style='text-align:center'>".$row['descripcion_familia']."</td>";
    echo "    <td style='text-align:center' >".$row['descripcion_subfamilia']."</td>";
    echo "    <td style='text-align:center'>".$row['pasillo']."</td>";
	echo "    <td style='text-align:center'>".$row['casillero']."</td>";
    echo "    <td style='text-align:left' >".$row['observaciones']."</td>";
    echo "    <td style='text-align:right' >".$row['cantidad']."</td>";
    echo "    <td style='text-align:right' >".$row['precio_pmp']."</td>";
    echo "    <td style='text-align:right' >".$row['total']."</td>";
    echo "</tr>";
	}
 ?>


</table>
</form>
<?
}else{
     ?>
    <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="100%">No existen Productos a Ser Desplegados</td>
    </tr>
 <?   
 }
 ?>

<form action="includes/Admin/productos_excel.php" method="POST">     
            <input type="hidden" name="sql" id="sql" hidden="hidden" value="<?  echo $sql; ?>">

            <table align="center">
            <tr><td>
           <input type="submit" value="Exportar a Excel"></td></tr>
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
                <li><a href="?&cat=4&sec=4&page=<? echo $i;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones=<?=$_REQUEST["observaciones"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=4&sec=4&page=<? echo $nextpage;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones=<?=$_REQUEST["observaciones"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=4&sec=4&page=<? echo $prevpage;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones=<?=$_REQUEST["observaciones"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=4&sec=4&page=<? echo $i;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones=<?=$_REQUEST["observaciones"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=4&sec=4&page=<? echo $nextpage;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones=<?=$_REQUEST["observaciones"];?>">Next &raquo;</a></li><?
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