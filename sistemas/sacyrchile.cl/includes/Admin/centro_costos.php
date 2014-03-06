<?php
//var_dump($_REQUEST);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'";
if(!empty($_REQUEST['cod_cc']) && $_REQUEST['cod_cc']!=""){
    $consulta.= " and codigo_cc like '%".$_REQUEST['cod_cc']."%'";
}
if(!empty($_REQUEST['descripcion_cc']) && $_REQUEST['descripcion_cc']!=""){
    $consulta.= " and descripcion_cc like '%".$_REQUEST['descripcion_cc']."%'";
}
$consulta.=" ORDER BY descripcion_cc";
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



if(isset($_GET['filtro']) && $_GET['filtro']==1){
    $sql ="SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'";

    if(!empty($_REQUEST['cod_cc']) && $_REQUEST['cod_cc']!=""){
        $sql .= " and codigo_cc like '%".$_REQUEST['cod_cc']."%'";
    }

    if(!empty($_REQUEST['descripcion_cc']) && $_REQUEST['descripcion_cc']!=""){
        $sql .= " and descripcion_cc like '%".$_REQUEST['descripcion_cc']."%'";
    }
         
    $sql.=" ORDER BY descripcion_cc";
    $sql.= " ".$limit;

}else{
    $sql ="SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion_cc";
    $sql.= " ".$limit;
}
 
 
 
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
    
<table id="list_registros" border="0" style="border-collapse:collapse; margin-top:10px;">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="4"></td>
        <td id="list_link" ><a href="?cat=2&sec=13"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Centros de Costo"></a></td></tr>
    	<form action="?cat=2&sec=12&filtro=1" method="POST">
   		<tr id="titulo_reg" style="background-color: #fff;">
            <td width="20px" width="20px" style="font-family:Tahoma; font-size:12px;">Filtro:</td>
            <td width="100px" style="text-align:center;"><input type="text" name="cod_cc"         class="fo" value="<? echo $_REQUEST['cod_cc'];?>" onFocus='this.value=""'></td>
            <td width="550px" style="text-align:center;"><input type="text" name="descripcion_cc" class="fo" value="<? echo $_REQUEST['descripcion_cc'];?>" style="width:95%" onFocus='this.value=""'></td>
            <td width="100px"></td>
            <td style="text-align:right;"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
        </tr>
        </form>
        <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
            <td width="" style="text-align:center;">#</td>
            <td width="" style="text-align:center;">Cod. CC</td>
            <td style="text-align:center;">Descripcion</td>
            <td width="" style="text-align:center;">Fecha Ing.</td>
        
            <td width="20px" style="text-align:center;">Editar</td>
        </tr>
<?
if($r = mysql_query($sql,$con)){
 if(mysql_num_rows($r)!= null){
     $i=1;
 while($row = mysql_fetch_assoc($r)){
 ?>    
    <tr  style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243); border-collapse:collapse;" id="row">
        <td style="border:1px solid #666;text-align:center;"><?=$i;$i++;?></td>
        <td style="border:1px solid #666;text-align:center;"><?=$row['codigo_cc'];?></td>
        <td style="border:1px solid #666;text-align:left;"><?=utf8_decode(substr( $row['descripcion_cc'], 0, 100 ));?></td>
        <td style="border:1px solid #666;text-align:center;"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
        
        <td style="text-align:center; border:1px solid #666"><a href="?cat=2&sec=13&action=2&id_cc=<?=$row['Id_cc'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Centro de Costo"></a></td>
 
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
<br>
<table width="900px" align="center" border="0" >
<tr>
    <td>
<?

  //UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACIÓN SIEMPRE Y CUANDO HAYA MÁS DE UNA PÁGINA
  //echo "<br>page = ".$page;
  //echo "<br>num_rows = ".$num_rows;
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
              <li><a href="?&cat=2&sec=12&page=<? echo $i;?>&filtro=1&cod_cc=<?=$_REQUEST['cod_cc'];?>&descripcion_cc=<?=$_REQUEST['descripcion_cc'];?>"><? echo $i;?></a></li>
   <? } 

   //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
      if($lastpage >$page ){?>       
        <li class="next"><a href="?&cat=2&sec=12&page=<? echo $nextpage;?>&filtro=1&cod_cc=<?=$_REQUEST['cod_cc'];?>&descripcion_cc=<?=$_REQUEST['descripcion_cc'];?>" >Next &raquo;</a></li><?
      }else{?>
        <li class="next-off">Next &raquo;</li>
  <?  }
  } else {
      //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
      ?>
        <li class="previous"><a href="?&cat=2&sec=12&page=<? echo $prevpage;?>&filtro=1&cod_cc=<?=$_REQUEST['cod_cc'];?>&descripcion_cc=<?=$_REQUEST['descripcion_cc'];?>"  >&laquo; Previous</a></li><?
        for($i= 1; $i<= $lastpage ; $i++){
              //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
              if($page == $i){
          ?>  <li class="active"><? echo $i;?></li><?
              }else{
          ?>  <li><a href="?&cat=2&sec=12&page=<? echo $i;?>&filtro=1&cod_cc=<?=$_REQUEST['cod_cc'];?>&descripcion_cc=<?=$_REQUEST['descripcion_cc'];?>" ><? echo $i;?></a></li><?
              }
        }
           //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
        if($lastpage >$page ){    ?> 
        <li class="next"><a href="?&cat=2&sec=12&page=<? echo $nextpage;?>&filtro=1&cod_cc=<?=$_REQUEST['cod_cc'];?>&descripcion_cc=<?=$_REQUEST['descripcion_cc'];?>">Next &raquo;</a></li><?
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