
<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>

<?php
// var_dump($_SESSION);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT p.*,df.descripcion_familia ,ds.descripcion_subfamilia   FROM productos_new p , familia df, subfamilia ds
WHERE  p.rut_empresa='".$_SESSION['empresa']."' AND  df.id_familia=p.id_familia AND ds.id_subfamilia=p.id_subfamilia ";
if(!empty($_REQUEST['codbar_productonew']))
$consulta.=" AND p.codbar_productonew like'%".$_REQUEST['codbar_productonew']."%' ";

if(!empty($_REQUEST['descripcion']))
$consulta.=" AND p.descripcion like'%".$_REQUEST['descripcion']."%' ";

if(!empty($_REQUEST['codigo_interno']))
$consulta.=" AND p.codigo_interno like'%".$_REQUEST['codigo_interno']."%' ";


if(!empty($_REQUEST['id_familia']))
$consulta.=" AND df.id_familia like'%".$_REQUEST['id_familia']."%' ";


if(!empty($_REQUEST['descripcion_subfamilia']))
$consulta.=" AND ds.descripcion_subfamilia like'%".$_REQUEST['descripcion_subfamilia']."%' ";


if(!empty($_REQUEST['pasillo']))
$consulta.=" AND p.pasillo like'%".$_REQUEST['pasillo']."%' ";

if(!empty($_REQUEST['casillero']))
$consulta.=" AND p.casillero like'%".$_REQUEST['casillero']."%' ";

if(!empty($_REQUEST['observaciones']))
$consulta.=" AND p.observaciones like'%".$_REQUEST['observaciones']."%' ";
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
$sql.=" AND df.id_familia ='".$_REQUEST['id_familia']."' ";


if(!empty($_REQUEST['descripcion_subfamilia']))
$sql.=" AND ds.descripcion_subfamilia like '%".$_REQUEST['descripcion_subfamilia']."%' ";


if(!empty($_REQUEST['pasillo']))
$sql.=" AND p.pasillo ='".$_REQUEST['pasillo']."' ";

if(!empty($_REQUEST['casillero']))
$sql.=" AND p.casillero ='".$_REQUEST['casillero']."' ";

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
<br/><br/>
<form action="?cat=3&sec=15&filtro=1" method="POST">
<table width="95%">
    <tr>
        <td id="list_link"  colspan="100%"><a href="?cat=3&sec=31&action=1"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Producto"></a></td>
    </tr>
</table>
<table id="list_registros" style=" border-collapse:collapse" border="1" >

    <tr style='font-family:tahoma;font-size:12px;'>
        <td style="text-align:left; font-weight:bold; ">Filtro:</td>
        <td style="text-align:center;width:85px;">
            <input name='codbar_productonew' value='<? echo $_REQUEST['codbar_productonew']?>' class="fo" style="width:90%;" onKeyPress='ValidaSoloNumeros()'></td>
        <td style="text-align:center;width:162px;">
            <input name='descripcion' value='<? echo $_REQUEST['descripcion']?>' class="fo" style="width:90%;">   
        </td>
        <td style="text-align:center;width:77px;">
            <input name='codigo_interno' value='<? echo $_REQUEST['codigo_interno']?>' class="fo" style="width:90%;">
        </td>
        
        <td style="text-align:center;width:75px;">
    		<select name='id_familia' class="fo" style="width:90%;" >
            <option value=''>--- </option>
            <?
            $sqlfam=" select * from familia   order by  descripcion_familia ";
            $recfam=mysql_query($sqlfam);
            while($rowfam=mysql_fetch_array($recfam)){

                echo "<option value='".$rowfam['id_familia']."'";
                if($rowfam['id_familia']==$_REQUEST['id_familia']){

                    echo " selected ";
                }
                echo">".$rowfam['descripcion_familia'];
                echo "</option>";
            }
            
            ?>
            </select> 
        </td>
        
        <td style="text-align:center;width:100px;">
           <input type='text' name='descripcion_subfamilia' value='<?=$_REQUEST["descripcion_subfamilia"];?>' class='fo'> 
        <!-- <select name='id_subfamilia' class="fo" style="width:90%;">
        <option value=''>---- </option>
        <?/*
        $sqlfam = " SELECT * FROM subfamilia WHERE id_familia='".$_REQUEST['id_familia']."' order by  descripcion_subfamilia ";
        $recfam=mysql_query($sqlfam);
        while($rowfam=mysql_fetch_array($recfam))
        {
            echo "<option value='".$rowfam['id_subfamilia']."'";
            if($rowfam['id_subfamilia']==$_REQUEST['id_subfamilia'])
            {
                echo " selected ";
            }
            echo">".$rowfam['descripcion_subfamilia'];
            echo "</option>";
        }*/
        ?>
        </select>  -->
        </td>
        
        <td style="text-align:center;width:55px;">
        <input name='pasillo' value='<? echo $_REQUEST['pasillo']?>' class="fo" style="width:90%;" >
        </td>
        
        <td style="text-align:center;width:52px;">
        <input name='casillero' value='<? echo $_REQUEST['casillero']?>' class="fo" style="width:90%;" >
        </td>
        
        <td style="text-align:center;width:160px;">
        <input name='observaciones' value='<? echo $_REQUEST['observaciones']?>' class="fo" style="width:90%;">
        </td>
    	
        <td style="text-align:right" colspan="4"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td style="text-align:center; ">#</td>
        <td style="text-align:center; ">Codigo de<br /> Barras</td>
        <td style="text-align:center">Descripción</td>
        <td style="text-align:center">Código Interno</td>
        <td style="text-align:center">Familia</td>
        <td style="text-align:center">SubFamilia</td>
        <td style="text-align:center">Pasillo</td>
        <td style="text-align:center">Casillero</td>
        <td style="text-align:center">Observación</td>
        <td style="text-align:center">Editar</td>
        <td style="text-align:center">Ver</td>
        <td style="text-align:center">Entradas</td>
        <td style="text-align:center">Salidas</td>
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
    echo "    <td style='text-align:center'>".$row['codbar_productonew']."</td>";
    echo "    <td style='text-align:left'>".$row['descripcion']."</td>";
    echo "    <td style='text-align:center'>".$row['codigo_interno']."</td>";
	echo "    <td style='text-align:center'>".$row['descripcion_familia']."</td>";
    echo "    <td style='text-align:center' >".$row['descripcion_subfamilia']."</td>";
    echo "    <td style='text-align:center'>".$row['pasillo']."</td>";
	echo "    <td style='text-align:center'>".$row['casillero']."</td>";
    echo "    <td style='text-align:left' >".$row['observaciones']."</td>";
	echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=31&action=2&codbar_productonew=".$row['codbar_productonew']."' >
					    <img src='img/edit.png' width='24px' height='24px' border='0' class='toolTIP' title='Editar Producto'>
					</a>
				</td>";
	echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=32&action=2&codbar_productonew=".$row['codbar_productonew']."' >
					    <img src='img/view.png' width='24px' height='24px' border='0' class='toolTIP' title='Ver Producto'>
					</a>
				</td>";
	echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=33&codbar_productonew=".$row['codbar_productonew']."' >
					    <img src='img/document_add.png' width='24px' height='24px' border='0' class='toolTIP' title='Entrada Producto'>
					</a>
				</td>";
	echo "    <td style='text-align: center;'>
				    <a href='?cat=3&sec=34&codbar_productonew=".$row['codbar_productonew']."' >
					    <img src='img/disk_blue_ok.png' width='24px' height='24px' border='0' class='toolTIP' title='Salida Producto'>
					</a>
				</td>";
    echo "</tr>";
	}
}else{
 ?>
<tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="100%">No existen Productos a Ser Desplegadas</td>
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
                <li><a href="?&cat=3&sec=15&page=<? echo $i;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones<?=$_REQUEST["observaciones"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=3&sec=15&page=<? echo $nextpage;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones<?=$_REQUEST["observaciones"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=3&sec=15&page=<? echo $prevpage;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones<?=$_REQUEST["observaciones"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=3&sec=15&page=<? echo $i;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&descripcion_subfamilia=<?=$_REQUEST["descripcion_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones<?=$_REQUEST["observaciones"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=3&sec=15&page=<? echo $nextpage;?>&filtro=1&codbar_productonew=<?=$_REQUEST["codbar_productonew"];?>&codigo_interno=<?=$_REQUEST["codigo_interno"];?>&id_familia=<?=$_REQUEST["id_familia"];?>&id_subfamilia=<?=$_REQUEST["id_subfamilia"];?>&pasillo=<?=$_REQUEST["pasillo"];?>&casillero=<?=$_REQUEST["casillero"];?>&observaciones<?=$_REQUEST["observaciones"];?>">Next &raquo;</a></li><?
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