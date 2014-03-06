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
$consulta = "SELECT s.*, c.descripcion_cc, p.descripcion, lf.descripcion_lf, dp.patente  
FROM productos p 
INNER JOIN ( salida_petroleo s LEFT JOIN lugares_fisicos lf  ON s.id_lugaresfisicos=lf.id_lf) 
ON p.cod_producto=s.cod_producto 
INNER JOIN centros_costos c 
ON c.Id_cc=s.centro_costo 
INNER JOIN detalles_productos dp 
ON s.cod_detalle_producto=dp.cod_detalle_producto 
WHERE s.rut_empresa='".$_SESSION['empresa']."'";

 if(!empty($_REQUEST['dia']))
$consulta.=" AND s.dia like'%".$_REQUEST['dia']."%' ";

if(!empty($_REQUEST['mes']))
$consulta.=" AND s.mes like'%".$_REQUEST['mes']."%' ";

if(!empty($_REQUEST['agno']))
$consulta.=" AND s.agno like'%".$_REQUEST['agno']."%' ";

if(!empty($_REQUEST['tipo_salida']))
$consulta.=" AND s.tipo_salida like'%".$_REQUEST['tipo_salida']."%' ";

/*
if(!empty($_REQUEST['patente']))
$sql.=" AND de.patente like'%".$_REQUEST['patente']."%' ";
*/

if(!empty($_REQUEST['litros']))
$consulta.=" AND s.litros like'%".$_REQUEST['litros']."%' ";

if(!empty($_REQUEST['descripcion_cc']))
$consulta.=" AND c.descripcion_cc like'%".$_REQUEST['descripcion_cc']."%' ";

$consulta.=" AND 1=1  order by s.dia,s.mes,s.agno asc";

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

/*$sql ="SELECT s.*,c.descripcion_cc  FROM salida_petroleo s,centros_costos c
WHERE  s.rut_empresa='".$_SESSION['empresa']."'  and c.Id_cc=s.centro_costo ";*/

$sql = "SELECT s.*, c.descripcion_cc, p.descripcion, lf.descripcion_lf, dp.patente  
FROM productos p 
INNER JOIN ( salida_petroleo s LEFT JOIN lugares_fisicos lf  ON s.id_lugaresfisicos=lf.id_lf) 
ON p.cod_producto=s.cod_producto 
INNER JOIN centros_costos c 
ON c.Id_cc=s.centro_costo 
INNER JOIN detalles_productos dp 
ON s.cod_detalle_producto=dp.cod_detalle_producto 
WHERE s.rut_empresa='".$_SESSION['empresa']."'";

if(empty($_REQUEST['recarga']))
{
  $fecha=date('d-m-Y');
  $dia=substr($fecha,0,2);
  $mes=substr($fecha,3,2);
  $ano=substr($fecha,6,4);
  $_REQUEST['mes']=$mes;
  $_REQUEST['agno']=$ano;;
}

//Filtros
if(!empty($_REQUEST['dia']))
$sql.=" AND s.dia like'%".$_REQUEST['dia']."%' ";

if(!empty($_REQUEST['mes']))
$sql.=" AND s.mes like'%".$_REQUEST['mes']."%' ";

if(!empty($_REQUEST['agno']))
$sql.=" AND s.agno like'%".$_REQUEST['agno']."%' ";


if(!empty($_REQUEST['tipo_salida']))
$sql.=" AND s.tipo_salida like'%".$_REQUEST['tipo_salida']."%' ";


if(!empty($_REQUEST['patente']))
$sql.=" AND dp.patente like'%".$_REQUEST['patente']."%' ";


if(!empty($_REQUEST['litros']))
$sql.=" AND s.litros like'%".$_REQUEST['litros']."%' ";

if(!empty($_REQUEST['descripcion_cc']))
$sql.=" AND c.descripcion_cc like'%".$_REQUEST['descripcion_cc']."%' ";

$sql.=" AND 1=1  order by s.dia,s.mes,s.agno asc";
$sql.= " ".$limit;




//Efectua la Consulta
$res = mysql_query($sql,$con);
$num = mysql_num_rows($res); 
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
    <form action="?cat=4&sec=3&filtro=1" method="POST">
    <input type='hidden' name='recarga' value='1'>
    <table width="95%">
      
    </table>
<table id="list_registros" style=" border-collapse:collapse" border="1" >

    <tr  style='font-family:tahoma;font-size:12px;'>
        <td style="text-align:left; font-weight:bold; ">Filtro:</td>
        <td style="text-align:center"><input name='dia' value='<? echo $_REQUEST['dia']?>' class="fo" style="width:50px;" onKeyPress="ValidaSoloNumeros()"></td>
        <td style="text-align:center">
        <select name='mes' class="fo">
        <option value='0'></option>
        <option value='1' <? if($_REQUEST['mes']==1)  echo " selected" ;?> >Enero</option>
        <option value='2' <? if($_REQUEST['mes']==2)  echo " selected" ;?> >Febrero</option>
        <option value='3' <? if($_REQUEST['mes']==3)  echo " selected" ;?> >Marzo</option>
        <option value='4' <? if($_REQUEST['mes']==4)  echo " selected" ;?> >Abril</option>
        <option value='5' <? if($_REQUEST['mes']==5)  echo " selected" ;?> >Mayo</option>
        <option value='6' <? if($_REQUEST['mes']==6)  echo " selected" ;?> >Junio</option>
        <option value='7' <? if($_REQUEST['mes']==7)  echo " selected" ;?> >Julio</option>
        <option value='8' <? if($_REQUEST['mes']==8)  echo " selected" ;?> >Agosto</option>
        <option value='9' <? if($_REQUEST['mes']==9)  echo " selected" ;?> >Septiembre</option>
        <option value='10' <? if($_REQUEST['mes']==10)  echo " selected" ;?> >Octubre</option>
        <option value='11' <? if($_REQUEST['mes']==11)  echo " selected" ;?> >Noviembre</option>
        <option value='12' <? if($_REQUEST['mes']==12)  echo " selected" ;?> >Diciembre</option>
        </select>                
        </td>
        <td style="text-align:center">  
        <input name='agno'  value='<? echo $_REQUEST['agno'] ?>'  class='fo' onKeyPress="ValidaSoloNumeros()"/>
        </td>
         <td style="text-align:center">
        <select name='tipo_salida' class="fo">
        <option value='0'></option>
        <option value='1' <? if($_REQUEST['tipo_salida']==1)  echo " selected" ;?> >Activo</option>
        <option value='2' <? if($_REQUEST['tipo_salida']==2)  echo " selected" ;?> >Lugares Fisicos</option>
        </td>
        <td style="text-align:center"><input name='litros' value='<? echo $_REQUEST['litros']?>' class="fo" style="width:150px;" onKeyPress="ValidaSoloNumeros()"> 
        </td>
        <td style="text-align:center">
                  <select name="descripcion_cc"  class="fo">
                <option value=""  class="fo">---</option>
            <?
                $s = "SELECT * FROM centros_costos ORDER BY descripcion_cc";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r)){
                    ?>  <option value="<?=$roo['descripcion_cc'];?>"   <? if($_REQUEST['descripcion_cc']==$roo['descripcion_cc']) echo " selected" ;?> class="fo"><?=$roo['descripcion_cc'];?></option> <?    
                }
        
                ?>
            </select>
        </td>
         <td style="text-align:center"><input name='patente' value='<? echo $_REQUEST['patente']?>' class="fo" style="width:90px;"></td>
    	 <td style="text-align:center"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td style="text-align:center; ">#</td>
        <td style="text-align:center">Dia</td>
        <td style="text-align:center">Mes</td>
        <td style="text-align:center">A&ntilde;o</td>
        <td style="text-align:center">Tipo Salida</td>
        <td style="text-align:center">Litros</td>
        <td style="text-align:center">Centro C.</td>
        <td style="text-align:center">Patente</td>
       <td></td>
        <!--<td width="100px">Editar</td> -->
    </tr>    

<?
/*
if(mysql_num_rows($res)!=NULL){
    $i=1;
while($row = mysql_fetch_assoc($res)){
    
    
           switch($row['tipo_producto']){
                    case 1:  $tipo="Maquinarias y Equipos"; break;
                    case 2:  $tipo="Vehiculo Menor"; break;
                    case 3:  $tipo="Herramientas"; break;
                    case 4:  $tipo="Muebles"; break;
                    case 5:  $tipo="Generador"; break;
                    case 6:  $tipo="Plantas"; break;
                    case 7:  $tipo="Equipos de Tunel"; break;
                    case 8:  $tipo="Otros"; break;
               }*/
?>
<?
	//Desempilamos los datos
	$i=1;
    if($num>0)
    {
    	while($row=mysql_fetch_array($res))
    	{
    	if($row['tipo_salida']==1) $row['tipo_salida']="Activo";
    	if($row['tipo_salida']==2) $row['tipo_salida']="Lugares Fisicos";
        echo "<tr   style='font-family:tahoma;font-size:12px;'>";
        echo "    <td style='text-align:center'>".$i++."</td>";
        echo "    <td style='text-align:center'>".$row['dia']."</td>";
        echo "    <td style='text-align:center'>".$row['mes']."</td>";
        echo "    <td style='text-align:center'>".$row['agno']."</td>";
    	echo "    <td style='text-align:center'>".$row['tipo_salida']."</td>";
        echo "    <td style='text-align:center' >".$row['litros']."</td>";
        echo "    <td style='text-align:center'>".$row['descripcion_cc']."</td>"; 
        echo "    <td style='text-align:center'>".$row['patente']."</td>"; 
    	echo "    <td style='text-align: center;'>
    				    
    				</td>";
        echo "</tr>";
    	}
    }
    else
    {
        echo "<tr id='mensaje-sin-reg' style='color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; font-size:12px;'>";
        echo "<td colspan='100%'>No existen Salidas de Petroleo a Ser Desplegadas</td>";
        echo "</ tr>";
    }
 ?>


</table>
</form>
<form action="includes/Admin/petroleo_excel.php" method="POST">     
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
                <li><a href="?&cat=4&sec=3&page=<? echo $i;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=4&sec=3&page=<? echo $nextpage;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=4&sec=3&page=<? echo $prevpage;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=4&sec=3&page=<? echo $i;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=4&sec=3&page=<? echo $nextpage;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&descripcion_cc=<?=$_REQUEST["descripcion_cc"];?>">Next &raquo;</a></li><?
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