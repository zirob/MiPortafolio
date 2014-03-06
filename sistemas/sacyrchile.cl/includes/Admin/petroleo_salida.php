<?php
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT s.*,c.descripcion_cc  FROM salida_petroleo s,centros_costos c
WHERE  s.rut_empresa='".$_SESSION['empresa']."'  and c.Id_cc=s.centro_costo ";
if(empty($_REQUEST['filtro']))
{
  $fecha=date('d-m-Y');
  $dia=substr($fecha,0,2);
  $mes=substr($fecha,3,2);
  $ano=substr($fecha,6,4);
  $_REQUEST['mes']=$mes;
  $_REQUEST['agno']=$ano;;
}

if(!empty($_REQUEST['dia'])){

    $DIA = $_REQUEST["dia"];
    $n_dig = strlen($_REQUEST["dia"]);
    if($n_dig == 1) $DIA = "0".$DIA;

    $consulta.=" AND s.dia ='".$DIA."' ";

}    

if(!empty($_REQUEST['mes']))
    $consulta.=" AND s.mes like'%".$_REQUEST['mes']."%' ";

if(!empty($_REQUEST['agno']))
    $consulta.=" AND s.agno like'%".$_REQUEST['agno']."%' ";

if(!empty($_REQUEST['tipo_salida']))
    $consulta.=" AND s.tipo_salida like'%".$_REQUEST['tipo_salida']."%' ";

if(!empty($_REQUEST['litros']))
    $consulta.=" AND s.litros like'%".$_REQUEST['litros']."%' ";

if(!empty($_REQUEST['Id_cc']))
    $consulta.=" AND c.Id_cc like'%".$_REQUEST['Id_cc']."%' ";

$consulta.=" AND 1=1  order by s.Id_salida_petroleo DESC";

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

$sql ="SELECT s.*,c.descripcion_cc  FROM salida_petroleo s,centros_costos c
WHERE  s.rut_empresa='".$_SESSION['empresa']."'  and c.Id_cc=s.centro_costo ";

if(empty($_REQUEST['filtro']))
{
  $fecha=date('d-m-Y');
  $dia=substr($fecha,0,2);
  $mes=substr($fecha,3,2);
  $ano=substr($fecha,6,4);
  $_REQUEST['mes']=$mes;
  $_REQUEST['agno']=$ano;
}

//Filtros
if(!empty($_REQUEST['dia'])){

    $DIA = $_REQUEST["dia"];
    $n_dig = strlen($_REQUEST["dia"]);
    if($n_dig == 1) $DIA = "0".$DIA;

    $sql.=" AND s.dia ='".$DIA."' ";

}

if(!empty($_REQUEST['mes']))
$sql.=" AND s.mes like'%".$_REQUEST['mes']."%' ";

if(!empty($_REQUEST['agno']))
$sql.=" AND s.agno like'%".$_REQUEST['agno']."%' ";


if(!empty($_REQUEST['tipo_salida']))
$sql.=" AND s.tipo_salida like'%".$_REQUEST['tipo_salida']."%' ";

if(!empty($_REQUEST['litros']))
$sql.=" AND s.litros like'%".$_REQUEST['litros']."%' ";


if(!empty($_REQUEST['Id_cc']))
$sql.=" AND c.Id_cc like'%".$_REQUEST['Id_cc']."%' ";

$sql.=" AND 1=1  order by s.Id_salida_petroleo DESC";
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
    <form action="?cat=3&sec=14&filtro=1" method="POST">
    <input type='hidden' name='recarga' value='1'>
    <table width="95%">
        <tr>
        <td id="list_link"  colspan="100%"><a href="?cat=3&sec=12"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Salida de Petroleo"></a></td>
    
    </tr>
    </table>
<table id="list_registros" style=" border-collapse:collapse" border="1" >

    <tr  style='font-family:tahoma;font-size:12px;'>
        <td style="text-align:left; font-weight:bold; ">Filtro:</td>
        <td style="text-align:center"><input name='dia' value='<? echo $_REQUEST['dia']?>' class="fo" style="width:50px;"></td>
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
        <input name='agno'  value='<? echo $_REQUEST['agno'] ?>'  class='fo'/>
        </td>
         <td style="text-align:center">
        <select name='tipo_salida' class="fo">
        <option value='0'></option>
        <option value='1' <? if($_REQUEST['tipo_salida']==1)  echo " selected" ;?> >Activo</option>
        <option value='2' <? if($_REQUEST['tipo_salida']==2)  echo " selected" ;?> >Lugares Fisicos</option>
        </select>
        </td>
        
        <td style="text-align:center"><input name='litros' value='<? echo $_REQUEST['litros']?>' class="fo" style="width:150px;">
        </td>
        <td style="text-align:center">
                <select name="Id_cc"  class="fo">
                <option value="0"  class="fo">---</option>
            <?
                $s = "SELECT * FROM centros_costos ORDER BY descripcion_cc";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_array($r)){
                    ?>  <option value="<?=$roo['Id_cc'];?>"   <? if($_REQUEST['Id_cc']==$roo['Id_cc']) echo " selected" ;?> class="fo"><?=$roo['descripcion_cc'];?></option> <?    
                }
        
                ?>
            </select>
        </td>
    	 <td style="text-align:center" colspan="2"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
   	
    </tr>
    <tr  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td style="text-align:center; ">Numero</td>
        <td style="text-align:center">Dia</td>
        <td style="text-align:center">Mes</td>
        <td style="text-align:center">A&ntilde;o</td>
        <td style="text-align:center">Tipo Salida</td>
        <td style="text-align:center">Litros</td>
        <td style="text-align:center">Centro C.</td>
        <td style="text-align:center">Editar</td>
        <td style="text-align:center">Imprimir</td>
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
    	if($row['tipo_salida']==1) $row['tipo_salida']="Acitvo";
    	if($row['tipo_salida']==2) $row['tipo_salida']="Lugares Fisicos";
        echo "<tr   style='font-family:tahoma;font-size:12px;'>";
        echo "    <td style='text-align:center'>".$row['id_salida_petroleo']."</td>";
        echo "    <td style='text-align:center'>".$row['dia']."</td>";
        echo "    <td style='text-align:center'>".$row['mes']."</td>";
        echo "    <td style='text-align:center'>".$row['agno']."</td>";
    	echo "    <td style='text-align:center'>".$row['tipo_salida']."</td>";
        echo "    <td style='text-align:center' >".$row['litros']."</td>";
        echo "    <td style='text-align:center'>".$row['descripcion_cc']."</td>"; 
    	echo "    <td style='text-align: center;'>
    				    <a href='?cat=3&sec=12&action=2&id_salida_petroleo=".$row['id_salida_petroleo']."' >
    					    <img src='img/edit.png' width='24px' height='24px' border='0' class='toolTIP' title='Editar Salida de Petroleo'>
    					</a>
    				</td>";
		 	echo "    <td style='text-align: center;'>
    				    <a href='?cat=3&sec=16&action=2&id_salida_petroleo=".$row['id_salida_petroleo']."' >
    					    <img src='img/printer_ok.png' width='24px' height='24px' border='0' class='toolTIP' title='Imprimir Salida de Petroleo'>
    					</a>
    				</td>";
        echo "</tr>";
    	}
    }
    else
    {
        echo "<tr id='mensaje-sin-reg' style='color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; font-size:12px;'>";
        echo "<td colspan='100%'>No existen Salidas de Petróleo para ser Desplegadas</td>";
        echo "</ tr>";
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
                <li><a href="?&cat=3&sec=14&page=<? echo $i;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&Id_cc=<?=$_REQUEST["Id_cc"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=3&sec=14&page=<? echo $nextpage;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&Id_cc=<?=$_REQUEST["Id_cc"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=3&sec=14&page=<? echo $prevpage;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&Id_cc=<?=$_REQUEST["Id_cc"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=3&sec=14&page=<? echo $i;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&Id_cc=<?=$_REQUEST["Id_cc"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=3&sec=14&page=<? echo $nextpage;?>&filtro=1&dia=<?=$_REQUEST["dia"];?>&mes=<?=$_REQUEST["mes"];?>&agno=<?=$_REQUEST["agno"];?>&tipo_salida=<?=$_REQUEST["tipo_salida"];?>&litros=<?=$_REQUEST["litros"];?>&Id_cc=<?=$_REQUEST["Id_cc"];?>">Next &raquo;</a></li><?
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