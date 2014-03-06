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
$consulta = "SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION['empresa']."' ";
 if(isset($_REQUEST['mes']) && $_REQUEST['mes']!="" )
{
    if($_REQUEST['mes']==13)  $MesEsp = $_REQUEST['mes'] - 5;//Agosto
    if($_REQUEST['mes']==14) $MesEsp = $_REQUEST['mes'] - 5;//Septiembre
    if(!empty($MesEsp)){
        $consulta.=" and mes like '%".$MesEsp."%'"; 
    }else{
        $consulta.=" and mes like'%".$_REQUEST['mes']."%'"; 
    }
}else{
   $consulta.=" and mes like '%".date('m')."%'";
}

if(isset($_REQUEST['anio']) && $_REQUEST['anio']!=""){
   $consulta.=" and agno like'%".$_REQUEST['anio']."%'"; 
}else{
   $consulta.=" and agno='".date('Y')."'";
}

$consulta.="  ORDER BY agno,mes,dia";


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


if(empty($_REQUEST["filtrar"])){
    $_REQUEST['anio'] = date("Y");
    $_REQUEST['mes'] = date("m");
}

$sql ="SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION['empresa']."' ";

if(isset($_REQUEST['mes']) && $_REQUEST['mes']!="" )
{
    if($_REQUEST['mes']==13)  $MesEsp = $_REQUEST['mes'] - 5;//Agosto
    if($_REQUEST['mes']==14) $MesEsp = $_REQUEST['mes'] - 5;//Septiembre
    if(!empty($MesEsp)){
        $sql.=" and mes like '%".$MesEsp."%'"; 
    }else{
        $sql.=" and mes like'%".$_REQUEST['mes']."%'"; 
    }
}else{
   $sql.=" and mes like '%".date('m')."%'";
}

if(isset($_REQUEST['anio']) && $_REQUEST['anio']!=""){
   $sql.=" and agno like'%".$_REQUEST['anio']."%'"; 
}else{
   $sql.=" and agno='".date('Y')."'";
}

$sql.="  ORDER BY agno,mes,dia";
$sql.= " ".$limit;

// var_dump($_REQUEST);



if(isset($error) && !empty($error)){
    ?>
    <div id="main-error"><? echo $error;?></div>
    <?
}elseif($msg){
    ?>
    <div id="main-ok"><? echo $msg;?></div>
    <?
}
// echo "<br>mes =".$_REQUEST['mes'];
?>


</style>

<table id="list_registros" >
  
</table>

<style>
.fo
{
  border:1px solid #09F;
  background-color:#FFFFFF;
  color:#000066;
  font-size:11px;
  font-family:Tahoma, Geneva, sans-serif;
  width:40%;
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
<br>
<table id="list_registros" border="1" style="border-collapse:collapse;" >
    <form action="?cat=4&sec=5" method="POST">
        <tr id="titulo_reg" style="background-color: #fff;">
            <td colspan="2" style="font-family:Tahoma; font-size:12px;text-align:center;"><label>Filtro:</label></td>
            <td colspan="2" style="font-family:Tahoma; font-size:12px;text-align:center;"><label>Mes:&nbsp;&nbsp;</label>
                <!-- <input type="text" size="2" name="mes" class="fo"> -->
                <select name="mes" <? if($estado==3){ echo "Disabled"; }?> class='fo' >
                    <option value="0" <? if($_REQUEST['mes']==0) echo " selected "; ?> >---</option>  
                    <option value="01" <? if($_REQUEST['mes']==01){ echo " selected ";} ?> >Enero</option>
                    <option value="02" <? if($_REQUEST['mes']==02){ echo " selected ";} ?> >Febrero</option>
                    <option value="03" <? if($_REQUEST['mes']==03){ echo " selected ";} ?> >Marzo</option>
                    <option value="04" <? if($_REQUEST['mes']==04){ echo " selected ";} ?> >Abril</option>
                    <option value="05" <? if($_REQUEST['mes']==05){ echo " selected ";} ?> >Mayo</option>
                    <option value="06" <? if($_REQUEST['mes']==06){ echo " selected ";} ?> >Junio</option>
                    <option value="07" <? if($_REQUEST['mes']==07){ echo " selected ";} ?> >Julio</option>

                    <option value="13" <?  if($_REQUEST['mes'] == 13){ echo " selected ";} ?> >Agosto</option>
                    <option value="14" <? if($_REQUEST['mes']==14){ echo " selected ";} ?> >Septiembre</option>

                    <option value="10" <? if($_REQUEST['mes']==10){ echo " selected ";} ?> >Octubre</option>
                    <option value="11" <? if($_REQUEST['mes']==11){ echo " selected ";} ?> >Noviembre</option>
                    <option value="12" <? if($_REQUEST['mes']==12){ echo " selected ";} ?> >Diciembre</option>
                </select>
            </td>

            <td colspan="2" style="font-family:Tahoma; font-size:12px;text-align:center;"><label>A&ntilde;o:&nbsp;&nbsp;</label>
                <input type="text" size="2" name="anio" value='<?=$_REQUEST["anio"];?>' class="fo" onKeyPress="ValidaSoloNumeros()">
            </td>
            <td></td>
            <td colspan="2" style="font-family:Tahoma; font-size:12px; text-align:right;">
                <input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"><input type='hidden' name='filtrar' value='filtrar' >
            </td>

        </tr>
    </form>

    <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td  style="text-align:center;">#</td>
        <td  style="text-align:center;">Dia</td>
        <td  style="text-align:center;">N° Factura</td>
        <td  style="text-align:center;">Litros</td>
        <td  style="text-align:center;">Valor IEF.</td>
        <td  style="text-align:center;">Valor IEV.</td>
        <td  style="text-align:center;">Total IEF.</td>
        <td width="60px"  style="text-align:center;"></td>
        <td width="60px"  style="text-align:center;"></td>
    </tr>    

    <?
	$res = mysql_query($sql,$con);
    if(mysql_num_rows($res)!=null){
        $i=1;
        while($row = mysql_fetch_assoc($res)){
            ?>
            <tr  style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
                <td style="text-align: center;"><?echo $i;$i++;?></td>
                <td style="text-align: center;"><?=$row['dia'];?></td>
                <td style="text-align: center;"><?=$row['num_factura']; ?></td>
                <td style="text-align: center;"><?=number_format($row['litros'],0,'','.'); ?></td>
                <td style="text-align: right;"><?=number_format($row['valor_IEF'],0,'','.'); ?></td>
                <td style="text-align: right;"><?=number_format($row['valor_IEV'],0,'','.'); ?></td>
                <td style="text-align: right;"><?=number_format($row['total_IEF'],0,'','.'); ?></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
            </tr>


            <?php 
        }

    }else{
        ?>
        <tr  id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; ; font-size:12px;">
            <td colspan="9">No existen Facturas de Petroleo para ser Desplegadas</td>
        </tr>
        <? 
    }
    ?>
</table>
<form action="includes/Admin/petroleo_entrada_excel.php" method="POST">     
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
                <li><a href="?&cat=4&sec=5&page=<? echo $i;?>&filtro=1&mes=<?=$_REQUEST["mes"];?>&anio=<?=$_REQUEST["anio"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=4&sec=5&page=<? echo $nextpage;?>&filtro=1&mes=<?=$_REQUEST["mes"];?>&anio=<?=$_REQUEST["anio"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=4&sec=5&page=<? echo $prevpage;?>&filtro=1&mes=<?=$_REQUEST["mes"];?>&anio=<?=$_REQUEST["anio"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=4&sec=5&page=<? echo $i;?>&filtro=1&mes=<?=$_REQUEST["mes"];?>&anio=<?=$_REQUEST["anio"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=4&sec=5&page=<? echo $nextpage;?>&filtro=1&mes=<?=$_REQUEST["mes"];?>&anio=<?=$_REQUEST["anio"];?>">Next &raquo;</a></li><?
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