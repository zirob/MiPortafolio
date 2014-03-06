<?php
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT * FROM lugares_fisicos WHERE rut_empresa = '".$_SESSION['empresa']."' ";
    if(!empty($_REQUEST['descripcion_lf']) && $_REQUEST['descripcion_lf']!=""){

        $consulta.= " and descripcion_lf like '%".$_REQUEST['descripcion_lf']."%'";
    }

    if(!empty($_REQUEST['observacion_lf']) && $_REQUEST['observacion_lf']!=""){

        $consulta.= " and observacion_lf like '%".$_REQUEST['observacion_lf']."%'";
    }


    if(!empty($_REQUEST['centros_costos']) && $_REQUEST['centros_costos']!=""){

        $consulta.= " and id_cc like '%".$_REQUEST['centros_costos']."%'";

    }
    $consulta.=" ORDER BY descripcion_lf";

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

    $sql ="SELECT * FROM lugares_fisicos WHERE rut_empresa = '".$_SESSION['empresa']."'";
    
    if(!empty($_REQUEST['descripcion_lf']) && $_REQUEST['descripcion_lf']!=""){

        $sql .= " and descripcion_lf like '%".$_REQUEST['descripcion_lf']."%'";
    }

    if(!empty($_REQUEST['observacion_lf']) && $_REQUEST['observacion_lf']!=""){

        $sql .= " and observacion_lf like '%".$_REQUEST['observacion_lf']."%'";

    }

    if(!empty($_REQUEST['centros_costos']) && $_REQUEST['centros_costos']!=""){

        $sql .= " and id_cc like '%".$_REQUEST['centros_costos']."%'";
        $row2["Id_Esp"] = $_REQUEST["centros_costos"];   
        $row["Id_Esp"] = $_REQUEST["centros_costos"]; 

    }
    $sql.=" ORDER BY descripcion_lf";
    $sql.= " ".$limit;

}else{

    $sql = "SELECT * FROM lugares_fisicos WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion_lf";
    $sql.= " ".$limit;

}   

$res = mysql_query($sql,$con);

?>


<table id="list_registros" border="0">
    <!-- ingreso de lugar fisico -->
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="3"> </td>
        <td id="list_link" ><a href="?cat=2&sec=22">
            <img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Lugar Fisico"></a>
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
    <form action="?cat=2&sec=21&filtro=1" method="POST">
        <tr id="titulo_reg" style="background-color: #fff;">
            <td width="20px" style="font-family:Tahoma; font-size:12px; text-align:center;" >Filtro:</td>
            <!-- Descripcion -->
            <td style="text-align:center;"><input type="text" name="descripcion_lf" name="descripcion_lf" value='<?  echo $_REQUEST["descripcion_lf"]?>' class="fo" onFocus='this.value=""'></td>
            <!-- observacion -->
            <td style="text-align:center;"><input type="text" name="observacion_lf" name="observacion_lf" value='<?  echo $_REQUEST["observacion_lf"]?>' class="fo" onFocus='this.value=""'></td>
            <!-- Para centro de costo -->
            <td style="text-align:center;"></td>

            <!-- boton filtrar -->
            <td style="text-align:center;"><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
        </tr>
    </form>

    <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td width="30px;" style="text-align: center;">#</td>
        <td width="350px;" style="text-align: center;" >Descripci&oacute;n</td>
        <td width="350px;" style="text-align: center;" >Observaci&oacute;n</td>
        <!-- <td width="100px;" style="text-align: center;" >Centro de Costos</td> -->
        <td width="100px;"  style="text-align: center;" >Fecha Ingreso</td>
        <td width="45px;"  style="text-align: center;" >Editar</td>
    </tr>

    <?php
    if(mysql_num_rows($res)!= null){

        $i=1;
        while($row = mysql_fetch_assoc($res)){
            ?>    
            <tr style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
                <td style="text-align: center;"><?=$i;$i++;?></td>
                <td style="text-align: center;"><?=utf8_decode(substr( $row['descripcion_lf'], 0, 100 ));?></td>
                <td style="text-align: center;"><?=utf8_decode(substr( $row['observacion_lf'], 0, 100 ));?></td>
                <!-- Aqui va centro de costos -->
                <td style="text-align: center;"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
                <td style="text-align: center;"><a href='?cat=2&sec=22&action=2&id_lf=<?=$row['id_lf']; ?>'>
                    <img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Lugar Fisico"></a>
                </td>
            </tr>  
            <?    
        }
    }else{
        ?>
        <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; ; font-size:12px;">
            <td colspan="5">No existen Lugares Fisicos a Ser Desplegadas</td>
        </tr>
        <?}?>

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
            <li><a href="?&cat=2&sec=21&page=<? echo $i;?>&filtro=1&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&observacion_lf=<?=$_REQUEST["observacion_lf"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=2&sec=21&page=<? echo $nextpage;?>&filtro=1&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&observacion_lf=<?=$_REQUEST["observacion_lf"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=2&sec=21&page=<? echo $prevpage;?>&filtro=1&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&observacion_lf=<?=$_REQUEST["observacion_lf"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=2&sec=21&page=<? echo $i;?>&filtro=1&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&observacion_lf=<?=$_REQUEST["observacion_lf"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=2&sec=21&page=<? echo $nextpage;?>&filtro=1&descripcion_lf=<?=$_REQUEST["descripcion_lf"];?>&observacion_lf=<?=$_REQUEST["observacion_lf"];?>">Next &raquo;</a></li><?
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