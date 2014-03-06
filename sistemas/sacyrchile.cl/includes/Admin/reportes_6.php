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
$consulta = "SELECT cod_producto, rut_empresa, descripcion, usuario_ingreso, fecha_ingreso, f.id_familia, f.descripcion_familia, s.id_subfamilia, s.descripcion_subfamilia   ";
$consulta.= "FROM productos p inner join familia f inner join subfamilia s ";
$consulta.= "WHERE f.id_familia=p.id_familia AND  s.id_subfamilia=p.id_subfamilia AND rut_empresa = '".$_SESSION['empresa']."' ";

if(!empty($_REQUEST['codigo_producto']) && $_REQUEST['codigo_producto']!=""){
    $consulta .= " and cod_producto like '%".$_REQUEST['codigo_producto']."%'";
}
if(!empty($_REQUEST['descripcion']) && $_REQUEST['descripcion']!=""){
    $consulta .= " and descripcion like '%".$_REQUEST['descripcion']."%'";
}

if(!empty($_REQUEST['familia']) && $_REQUEST['familia']!=""){
    $consulta .= " and p.id_familia='".$_REQUEST['familia']."'";
 }

 if(!empty($_REQUEST['subfamilia']) && $_REQUEST['subfamilia']!=""){
    $consulta .= " and s.id_subfamilia='".$_REQUEST['subfamilia']."'";
 }

$consulta.=" ORDER BY descripcion";


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


// $qry="";
if(isset($_GET['filtro']) && $_GET['filtro']==1){

    /*$qry = "SELECT  cod_producto, rut_empresa, descripcion, usuario_ingreso, fecha_ingreso, f.id_familia, f.descripcion_familia, s.id_subfamilia, s.descripcion_subfamilia ";
    $qry.= "FROM productos p inner join familia f inner join subfamilia s ";
    $qry.= "WHERE f.id_familia=p.id_familia AND  s.id_subfamilia=p.id_subfamilia AND rut_empresa = '".$_SESSION['empresa']."' ";*/

    $qry = "SELECT dp.cod_producto, p.descripcion, dp.patente, dp.codigo_interno, dp.estado_producto , f.id_familia, f.descripcion_familia,sf.id_subfamilia , sf.descripcion_subfamilia ";
    $qry.= "FROM productos p, detalles_productos dp, familia f, subfamilia sf ";
    $qry.= "WHERE p.cod_producto=dp.cod_producto ";
    $qry.= "AND f.id_familia=p.id_familia "; 
    $qry.= "AND sf.id_subfamilia=p.id_subfamilia ";
    $qry.= "AND p.rut_empresa = '".$_SESSION['empresa']."'";

    if(!empty($_REQUEST['codigo_producto']) && $_REQUEST['codigo_producto']!=""){
        $qry .= " and p.cod_producto like '%".$_REQUEST['codigo_producto']."%'";
    }
    if(!empty($_REQUEST['descripcion']) && $_REQUEST['descripcion']!=""){
        $qry .= " and p.descripcion like '%".$_REQUEST['descripcion']."%'";
    }
    if(!empty($_REQUEST['codigo_interno']) && $_REQUEST['codigo_interno']!=""){
        $qry .= " and dp.codigo_interno like '%".$_REQUEST['codigo_interno']."%'";
    }
    if(!empty($_REQUEST['patente']) && $_REQUEST['patente']!=""){
        $qry .= " and patente like '%".$_REQUEST['patente']."%'";
    }
    if(!empty($_REQUEST['estado']) && $_REQUEST['estado']!=0){
        $qry .= " and estado_producto = ".$_REQUEST['estado']."";
    }
    if(!empty($_REQUEST['familia']) && $_REQUEST['familia']!=""){
        $qry .= " and f.id_familia='".$_REQUEST['familia']."'";
        $row["id_familia"] = $_REQUEST["familia"]; 
     }
     if(!empty($_REQUEST['subfamilia']) && $_REQUEST['subfamilia']!=""){
        $qry .= " and sf.descripcion_subfamilia like'%".$_REQUEST['subfamilia']."%'";
        //$row["id_subfamilia"] = $_REQUEST["subfamilia"]; 
     }
    
    $qry.=" ORDER BY descripcion";
    $sqy.= " ".$limit;

}else{
    /*$qry = "SELECT cod_producto, rut_empresa, descripcion, usuario_ingreso, fecha_ingreso, f.id_familia, f.descripcion_familia, s.id_subfamilia, s.descripcion_subfamilia   ";
    $qry.= "FROM productos p inner join familia f inner join subfamilia s ";
    $qry.= "WHERE f.id_familia=p.id_familia AND  s.id_subfamilia=p.id_subfamilia AND rut_empresa = '".$_SESSION['empresa']."' ";
    $sqy.= " ".$limit;*/

    $qry = "SELECT dp.cod_producto, p.descripcion, dp.patente, dp.codigo_interno, dp.estado_producto ,f.descripcion_familia, sf.descripcion_subfamilia ";
    $qry.= "FROM productos p, detalles_productos dp, familia f, subfamilia sf ";
    $qry.= "WHERE p.cod_producto=dp.cod_producto ";
    $qry.= "AND f.id_familia=p.id_familia "; 
    $qry.= "AND sf.id_subfamilia=p.id_subfamilia ";
    $qry.= "AND p.rut_empresa = '".$_SESSION['empresa']."'";
}   
    // echo "<br>".$qry."<br>";
$res = mysql_query($qry,$con);
?>



<table id="list_registros">
    <tr>
        <td id="titulo_tabla" colspan="7"></td>
        
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
  width:90%;
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
    <form action="?cat=4&sec=6&filtro=1" method="POST">

    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px" style="font-family:Tahoma; font-size:12px;text-align:center;">Filtro:</td>
        <td  width="120px"  style="font-family:Tahoma; font-size:12px;text-align:center;">
            <input type="text"  id="codigo_producto" value='<?=$_REQUEST["codigo_producto"]?>' style='border:1px solid #09F;background-color:#FFFFFF;color:#000066;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:90%;' name="codigo_producto">
        </td>
        <td width="180px" style="text-align:center;"><input class="fo" type="text" id="descripcion" value='<?=$_REQUEST["descripcion"]?>' name="descripcion"></td>
        <td width="100px" style="text-align:center;"><input class="fo" type="text"  name="codigo_interno" value='<?=$_REQUEST["codigo_interno"]?>' onKeyPress='ValidaSoloNumeros()'></td>
        <td width="80px" style="text-align:center;"><input class="fo" type="text"  name="patente" value='<?=$_REQUEST["patente"]?>'></td>
        <td width="100px" style="text-align:center;">
                <select name="estado" class="fo" style="width:80px;">
                    <option value="0"> --- </option>
                    <option value="1" <?if($_POST["estado"]==1){ echo "SELECTED";}?>> Disponible </option>
                    <option value="2" <?if($_POST["estado"]==2){ echo "SELECTED";}?>> Asignado </option>
                    <option value="3" <?if($_POST["estado"]==3){ echo "SELECTED";}?>> Dado de Baja </option>
                    <option value="4" <?if($_POST["estado"]==4){ echo "SELECTED";}?>> En Mantencion </option>
                    <option value="5" <?if($_POST["estado"]==5){ echo "SELECTED";}?>> En Reparacion </option>
                </select>
        </td>
        <td width="120px"style="text-align:center;"><select name="familia"   class="fo">
<?                
                $sql2 = "SELECT * FROM familia WHERE 1=1 ORDER BY id_familia ";
                $res2 = mysql_query($sql2,$con);
?>
                <option value='0' <? if (isset($_REQUEST["familia"]) == 0) echo 'selected'; ?> class="fo">---</option>
<?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                   <option value='<? echo $row2["id_familia"]; ?>' <? if ($row2['id_familia'] == $_REQUEST['familia']) echo "selected"; ?> class="fo"><?  echo $row2["descripcion_familia"];?></option>
<?php
                }
?>
            </select>
        </td>
        <td width="120px" style="text-align:center;"><input class="fo" type="text" name='subfamilia' value='<?=$_REQUEST["subfamilia"]?>' ></td>
        <td colspan="2" style="text-align: center;"><input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
    </tr>    
    </form>
    <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;text-align:center;">
        <td width="20px">#</td>
        <td >Codigo</td>
        <td>Descripcion</td>
        <td>Cod. Interno</td>
        <td>Patente</td>
        <td>Estado</td>
        <td>Familia</td>
        <td>SubFamilia</td>
        <td width="30px"></td>
        <td width="30px"></td>
    </tr>    

<?
    if(mysql_num_rows($res)!=null){
        $i=1;
        while($row = mysql_fetch_assoc($res)){
?>
                <!-- Detalle -->
                <tr style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);">
                    <td><?echo $i;$i++;?></td>
                    <td><?=$row['cod_producto']; ?></td>
                    <td><?=$row['descripcion']; ?></td>
                    <td><?=$row['codigo_interno']; ?></td>
                    <td><?=$row['patente']; ?></td>
                    <td><?
                    switch ($row['estado_producto']) {
                        case '1':
                            echo "Disponible";
                            break;
                        case '2':
                            echo "Asignado";
                            break;
                        case '3':
                            echo "Dado de Baja";
                            break;    
                        case '4':
                            echo "En Mantención";
                            break;    
                        case '5':
                            echo "En Reparación";
                            break;    
                        default:
                            echo "";
                            break;
                    } 
                    ?></td>
                    <td style="text-align: center;"><?=$row["descripcion_familia"]?></td>
                    <td style="text-align: center;"><?=$row["descripcion_subfamilia"]?></td>
                    <td colspan="2"></td>
                </tr>
<?php 
        }

    }else{
?>
         <tr  id="mensaje-sin-reg"  style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; ; font-size:12px;">
            <td colspan="10">No existen Activos para ser Desplegados</td>
        </tr>
<? 
    }
?>
</table>

<form action="includes/Admin/activos_excel.php" method="POST">     
        <input type="hidden" name="sql" id="sql" hidden="hidden" value="<?  echo $qry; ?>">
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
                <li><a href="?&cat=4&sec=6&page=<? echo $i;?>&filtro=1&codigo_producto=<?=$_REQUEST["codigo_producto"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=4&sec=6&page=<? echo $nextpage;?>&filtro=1&codigo_producto=<?=$_REQUEST["codigo_producto"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=4&sec=6&page=<? echo $prevpage;?>&filtro=1&codigo_producto=<?=$_REQUEST["codigo_producto"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=4&sec=6&page=<? echo $i;?>&filtro=1&codigo_producto=<?=$_REQUEST["codigo_producto"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=4&sec=6&page=<? echo $nextpage;?>&filtro=1&codigo_producto=<?=$_REQUEST["codigo_producto"];?>&descripcion=<?=$_REQUEST["descripcion"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>">Next &raquo;</a></li><?
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