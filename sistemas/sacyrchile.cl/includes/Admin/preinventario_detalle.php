<script>
function ValidaSoloNumeros() {
   if ((event.keyCode < 48) || (event.keyCode > 57)) 
      event.returnValue = false;
}
</script>

<?
// var_dump($_REQUEST);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT pd.*, u.nombre ";
$consulta.= "FROM preinventario_detalle pd , usuarios u ";
$consulta.= "WHERE pd.rut_empresa='".$_SESSION['empresa']."' AND pd.id_pi='".$_GET['id_pi']."' AND u.usuario=pd.usuario_ingreso AND estado_det_pi!=2 AND pd.rut_empresa=u.rut_empresa ";
if(isset($_REQUEST['codigobarras'])){
    $consulta.=" and codigobarras like '%".$_REQUEST['codigobarras']."%' ";
}
if(isset($_REQUEST['cantidad'])){
    $consulta.=" and cantidad like '%".$_REQUEST['cantidad']."%' ";
}
/*if(isset($_REQUEST['codigo_det_ip'])){
    $consulta.=" and codigo_det_ip like '%".$_REQUEST['codigo_det_ip']."%' ";
}*/
if(isset($_REQUEST['usuario_ingreso'])){
    $consulta.=" and nombre like '%".$_REQUEST['usuario_ingreso']."%' ";
}
if(isset($_REQUEST['fecha_ingreso'])){
    $consulta.=" and pd.fecha_ingreso like '%".$_REQUEST['fecha_ingreso']."%' ";
}
/*if(isset($_REQUEST['estado_det_pi']) && $_REQUEST["estado_det_pi"]!=0){
    $consulta.=" and estado_det_pi =".$_REQUEST['estado_det_pi']." ";
}*/
$consulta.=" ORDER BY codigobarras";
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

if($_GET["eliminar"]==1){
    $up = "UPDATE preinventario_detalle SET ";
    $up.= "estado_det_pi='2', usuario_elimina='".$_SESSION["user"]."', fecha_elimina='".date('Y-m-d H:i:s')."'  ";
    $up.= "WHERE id_det_pi='".$_GET["codbar"]."' AND rut_empresa='".$_SESSION["empresa"]."' ";
    mysql_query($up);

    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'preinventario_detalle', '".$_GET["codbar"]."', '3'";
    $sql_even.= ", 'UPDATE: estado_det_pi=2', '".$_SERVER['REMOTE_ADDR']."', 'Update en preinventario', '1', '".date('Y-m-d H:i:s')."') ";
    mysql_query($sql_even, $con); 
}

if(isset($_GET['filtro']) && $_GET['filtro']==1){
	$sel = "SELECT pd.*, u.nombre ";
    $sel.= "FROM preinventario_detalle pd , usuarios u ";
    $sel.= "WHERE pd.rut_empresa='".$_SESSION['empresa']."' AND pd.id_pi='".$_GET['id_pi']."' AND u.usuario=pd.usuario_ingreso AND estado_det_pi!=2 ";
    $sel.= "AND estado_det_pi!='2' AND pd.rut_empresa=u.rut_empresa ";

    if(isset($_REQUEST['codigobarras'])){
        $sel.=" and codigobarras like '%".$_REQUEST['codigobarras']."%' ";
    }
    if(isset($_REQUEST['cantidad'])){
        $sel.=" and cantidad like '%".$_REQUEST['cantidad']."%' ";
    }
    /*if(isset($_REQUEST['codigo_det_ip'])){
        $sel.=" and codigo_det_ip like '%".$_REQUEST['codigo_det_ip']."%' ";
    }*/
    if(isset($_REQUEST['usuario_ingreso'])){
        $sel.=" and nombre like '%".$_REQUEST['usuario_ingreso']."%' ";
    }
    if(isset($_REQUEST['fecha_ingreso'])){
        $sel.=" and pd.fecha_ingreso like '%".$_REQUEST['fecha_ingreso']."%' ";
    }
    /*if(isset($_REQUEST['estado_det_pi']) && $_REQUEST["estado_det_pi"]!=0){
        $sel.=" and estado_det_pi =".$_REQUEST['estado_det_pi']." ";
    }*/
    $sel.=" ORDER BY codigobarras";
    $sel.= " ".$limit;

}else{
    $sel = "SELECT pd.*, u.nombre ";
    $sel.= "FROM preinventario_detalle pd , usuarios u ";
    $sel.= "WHERE pd.rut_empresa='".$_SESSION['empresa']."' AND pd.id_pi='".$_GET['id_pi']."' AND u.usuario=pd.usuario_ingreso AND estado_det_pi!=2 AND pd.rut_empresa=u.rut_empresa";
    // $sel.= "AND estado_det_pi!='2' ";
    $sel.= " ".$limit;

}	

$res = mysql_query($sel,$con);

?>
<table id="list_registros" border='0'>
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="7"> </td>
        <td id="list_link">
            <a href="?cat=3&sec=37"><img src="img/view_previous.png" width="30px" height="30px" border="0" class="toolTIP" title="Volver a Pre-Inventarios"></a>
            <a href="?cat=3&sec=40&action=1&id_pi=<?=$_GET['id_pi'];?>">
                <img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Detalle de Pre-Inventario">
            </a>
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
   <form action="?cat=3&sec=39&filtro=1&id_pi=<?=$_GET['id_pi'];?>" method="POST">
    <tr  id="titulo_reg" style="background-color: #fff;">
    	<td width="20px" style="font-family:Tahoma; font-size:12px; text-align:center;">Filtro:</td>
        <td width="120px" style="text-align:center;"><input type="text"   name="codigobarras" value='<?=$_REQUEST["codigobarras"];?>' onKeyPress='ValidaSoloNumeros()' style="width:80%; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onFocus='this.value=""'></td>
        <td width="120px" style="text-align:center;"><input type="text"  name="cantidad" value="<?=$_REQUEST["cantidad"];?>" onKeyPress='ValidaSoloNumeros()' style="width:80%; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onFocus='this.value=""'></td>         
        <!-- <td width="120px" style="text-align:center;">
            <select name="estado_det_pi"  class='fo' style="width:80%;">
                <option value="0" <?/* if($_REQUEST['estado_det_pi']==0) echo " selected "; */?> >---</option>  
                <option value="1" <?/* if($_REQUEST['estado_det_pi']==1){ echo " selected ";} */?> >Habilitado</option>
                <option value="2" <?/* if($_REQUEST['estado_det_pi']==2){ echo " selected ";} */?> >Deshabilitado</option>
             </select>
        </td> -->
        <td width="120px" style="text-align:center;"><input type="text"  name="usuario_ingreso" value="<?=$_REQUEST["usuario_ingreso"];?>" style="width:80%; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onFocus='this.value=""'></td>
        <td width="120px" style="text-align:center;"><input type="date"   name="fecha_ingreso" value='<?=$_REQUEST["fecha_ingreso"];?>' class="fo" onFocus='this.value=""'></td>
        <td colspan="4" style="text-align:right;"><input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
	</tr>
	</form>
	<tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
	    <td style="text-align: center;" >#</td>
	    <td style="text-align: center;" >Codigo de Barra</td>
	    <td style="text-align: center;" >Cantidad</td>
	    <!-- <td style="text-align: center;" >Estado</td> -->
	    <td style="text-align: center;" >Usuario Ingreso</td>
	    <td style="text-align: center;" >Fecha Ingreso</td>
	    <td style="width:5%;text-align: center;" >Editar</td>
	    <td style="width:5%;text-align: center;" >Eliminar</td>
	 </tr>

<?
	if(mysql_num_rows($res)>0){

    	$i=1;
    	while($row = mysql_fetch_assoc($res)){
?>    

        	<style>
        	#row:hover{
        	    background-color:#FF000;
        	}
        	</style>

        	<tr style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
            	<td style="text-align: center;"><?=$i;$i++;?></td>
            	<td style="text-align: center;"><?=$row["codigobarras"];?></td>
                <td style="text-align: center;"><?=$row["cantidad"];?></td>
                <!-- <td style="text-align: center;"> -->
<?
                    /*if($row["estado_det_pi"]==1)
                    echo "Habilitado";
                    if($row["estado_det_pi"]==2)
                    echo "Deshabilitado";   */

?>
                <!-- </td> -->
                <td style="text-align: center;"><?=$row["nombre"];?></td>
                <td style="text-align: center;"><?=date("d-m-Y",strtotime($row["fecha_ingreso"]));?></td>
            	<td style="text-align: center;"><a href="?cat=3&sec=41&action=2&id_pi=<?=$row['id_pi'];?>&codbar=<?=$row['id_det_pi']?>"><img src="img/edit.png" width="24px" height="24px" class="toolTIP" title="Editar Pre-Inventario"></a></td>
            	<td style="text-align: center;"><a href="?cat=3&sec=39&id_pi=<?=$row['id_pi'];?>&codbar=<?=$row['id_det_pi']?>&eliminar=1"><img src="img/eliminar.png" width="24px" height="24px" class="toolTIP" title="Ingresar Detalle de Pre-Inventario"></a></td>
            </tr>
<?
        }
    }else{
?>
    	<tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
    		<td colspan="10">No existen Productos en Pre-Inventarios</td>
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
            <li><a href="?&cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $i;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&codigo_det_ip=<?=$_REQUEST["codigo_det_ip"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&estado_det_pi=<?=$_REQUEST["estado_det_pi"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $nextpage;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&codigo_det_ip=<?=$_REQUEST["codigo_det_ip"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&estado_det_pi=<?=$_REQUEST["estado_det_pi"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $prevpage;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&codigo_det_ip=<?=$_REQUEST["codigo_det_ip"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&estado_det_pi=<?=$_REQUEST["estado_det_pi"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $i;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&codigo_det_ip=<?=$_REQUEST["codigo_det_ip"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&estado_det_pi=<?=$_REQUEST["estado_det_pi"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $nextpage;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&cantidad=<?=$_REQUEST["cantidad"];?>&codigo_det_ip=<?=$_REQUEST["codigo_det_ip"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>&estado_det_pi=<?=$_REQUEST["estado_det_pi"];?>">Next &raquo;</a></li><?
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