<?
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT pr.*, pn.*, pn.id_familia, f.descripcion_familia, pn.id_subfamilia, s.descripcion_subfamilia ";
$consulta.= "FROM preinventario_resultado pr inner join productos_new pn inner join familia f inner join subfamilia s ";
$consulta.= "WHERE pr.codbar_producto=pn.codbar_productonew AND pr.rut_empresa='".$_SESSION['empresa']."' AND pr.id_pi='".$_GET['id_pi']."' ";
$consulta.= "AND f.id_familia=pn.id_familia AND  s.id_subfamilia=pn.id_subfamilia ";
if(!empty($_POST['codigobarras'])){
  $consulta.=" and codbar_producto like '%".$_POST['codigobarras']."%' ";
}
if(!empty($_POST['familia']) && $_POST['familia']!=""){
  $consulta.= " and pn.id_familia='".$_POST['familia']."'";
}
if(!empty($_POST['subfamilia']) && $_POST['subfamilia']!=""){
  $consulta.= " and pn.id_subfamilia='".$_POST['subfamilia']."'";
}
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

	$sel = "SELECT pr.*, pn.*, pn.id_familia, f.descripcion_familia, pn.id_subfamilia, s.descripcion_subfamilia ";
	$sel.= "FROM preinventario_resultado pr inner join productos_new pn inner join familia f inner join subfamilia s ";
	$sel.= "WHERE pr.codbar_producto=pn.codbar_productonew AND pr.rut_empresa='".$_SESSION['empresa']."' AND pr.id_pi='".$_GET['id_pi']."' ";
	$sel.= "AND f.id_familia=pn.id_familia AND  s.id_subfamilia=pn.id_subfamilia ";

    if(!empty($_POST['codigobarras'])){
        $sel.=" and codbar_producto like '%".$_POST['codigobarras']."%' ";
    }
    if(!empty($_POST['familia']) && $_POST['familia']!=""){
        $sel.= " and pn.id_familia='".$_POST['familia']."'";
        $row["id_familia"] = $_POST["familia"]; 
    }
    if(!empty($_POST['subfamilia']) && $_POST['subfamilia']!=""){
        $sel.= " and pn.id_subfamilia='".$_POST['subfamilia']."'";
        $row["id_subfamilia"] = $_POST["subfamilia"]; 
    }

    // $sel.=" ORDER BY pr.id_pi";
    $sel.= " ".$limit;


}else{
	
	$sel = "SELECT pr.*, pn.*, pn.id_familia, f.descripcion_familia, pn.id_subfamilia, s.descripcion_subfamilia ";
	$sel.= "FROM preinventario_resultado pr inner join productos_new pn inner join familia f inner join subfamilia s ";
	$sel.= "WHERE pr.codbar_producto=pn.codbar_productonew AND pr.rut_empresa='".$_SESSION['empresa']."' AND pr.id_pi='".$_GET['id_pi']."' ";
	$sel.= "AND f.id_familia=pn.id_familia AND  s.id_subfamilia=pn.id_subfamilia ";
    $sel.= " ".$limit;

}


$res = mysql_query($sel,$con);
?>

<table border='0' style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4">
   

    <tr>
        <td  style="text-align:right;" >
        	 <form action="includes/Admin/preinventario_resultado_excel.php?id_pi=<?=$_GET['id_pi'];?>" method="POST">     
		      		<input type='text' name='empresa' id='empresa' hidden="hidden" value='<?=$_SESSION["empresa"];?>'>
		            <input type="text" name="codigobarras" id="codigobarras" hidden="hidden" value="<? if(isset($_POST['codigobarras'])){ echo $_POST['codigobarras'];}?>">
		            <input type="text" name="familia" id="familia" hidden="hidden" value="<? if(isset($_POST['familia'])){ echo $_POST['familia'];}?>" >
		            <input type="text" name="SubFamilia" id="SubFamilia" hidden="hidden" value="<? if(isset($_POST['SubFamilia'])){ echo $_POST['SubFamilia'];}?>" >
		     		<input type="image" alt="submit" src="img/excel2007.png" width="36px" height="36px" class="toolTIP" title="Informe Excel de Resultado Pre-Inventarios"></input>
            <a href="?cat=3&sec=37"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Pre-Inventarios"></a>
       		</form> 
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

<form action="?cat=3&sec=42&filtro=1&id_pi=<?=$_GET['id_pi'];?>" method="POST">
	<table id="list_registros" border="1" style="border-collapse:collapse;"  border'1'>
    	<tr  id="titulo_reg" style="background-color: #fff;">
    		<td width="20px" style="font-family:Tahoma; font-size:12px; text-align:center;">Filtro:</td>
        	<td width="120px" style="text-align:center;"><input type="text"   name="codigobarras" value='<?=$_POST["codigobarras"];?>' style="width:80%; text-align=left; font-size:12px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onFocus='this.value=""'></td>
         	<td width="120px"style="text-align:center;"><select name="familia"   class="fo">
<?                
                $sql2 = "SELECT * FROM familia WHERE 1=1 ORDER BY id_familia ";
                $res2 = mysql_query($sql2,$con);
?>
                <option value='0' <? if (isset($_POST["familia"]) == 0) echo 'selected'; ?> class="fo">---</option>
<?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                   <option value='<? echo $row2["id_familia"]; ?>' <? if ($row2['id_familia'] == $_POST['familia']) echo "selected"; ?> class="fo"><?  echo $row2["descripcion_familia"];?></option>
<?php
                }
?>
            </select>
        </td>
        <td width="120px" style="text-align:center;"><select name="subfamilia"   class="fo">
<?                
                $sql3 = "SELECT DISTINCT id_subfamilia, descripcion_subfamilia FROM subfamilia WHERE 1 =1 ORDER BY id_subfamilia ";
                $res3 = mysql_query($sql3,$con);
?>
                <option value='0' <? if (isset($_POST["subfamilia"]) == 0) echo 'selected'; ?> class="fo">---</option>
<?php              
                while($row3 = mysql_fetch_assoc($res3)){
?>
                   <option value='<? echo $row3["id_subfamilia"]; ?>' <? if ($row3['id_subfamilia'] == $_POST['subfamilia']) echo "selected"; ?> class="fo"><?  echo $row3["descripcion_subfamilia"];?></option>
<?php
                }
?>
			 </select>
        </td>
        <td colspan="5" style="text-align:right;"><input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
	</tr>
	<tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
	    <td style="text-align: center;" >#</td>
	    <td style="text-align: center;" >Codigo de Barra</td>
	    <td style="text-align: center;" >Familia</td>
	    <td style="text-align: center;" >SubFamilia</td>
	    <td style="text-align: center;" >Descripción</td>
	    <td style="text-align: center;" >Cantidad Sistema</td>
	    <td style="text-align: center;" >Cantidad Ingresada</td>
	    <!-- <td style="width:5%;text-align: center;" >Excel</td> -->
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
                <td style="text-align: center;"><?=$row["codbar_producto"];?></td>
                <td style="text-align: center;"><?=$row["descripcion_familia"];?></td>
                <td style="text-align: center;"><?=$row["descripcion_subfamilia"];?></td>
                <td style="text-align: center;"><?=$row["descripcion"];?></td>
                <td style="text-align: center;"><?=$row["cantidad_sistema"];?></td>
                <td style="text-align: center;"><?=$row["cantidad_ingresada"];?></td>
  
            	<!-- <td style="text-align: center;"><a href="?cat=3&sec=39&id_pi=<?/*=$row['id_pi'];*/?>&codbar=<?/*=$row['id_det_pi'];*/?>&eliminar=1"><img src="img/document_add.png" width="24px" height="24px" class="toolTIP" title="Ingresar Detalle de Pre-Inventario"></a></td> -->
            </tr>
<?
        }
    }else{
?>
    	<tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
    		<td colspan="10">No existen Activos y/o Productos a ser desplegados.</td>
		</tr>
<?
	}
?>
</table>
</form>
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
            <li><a href="?&cat=3&sec=42&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $i;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=3&sec=42&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $nextpage;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=3&sec=42&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $prevpage;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=3&sec=42&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $i;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=3&sec=42&id_pi=<?=$_GET['id_pi'];?>&page=<? echo $nextpage;?>&filtro=1&codigobarras=<?=$_REQUEST["codigobarras"];?>&familia=<?=$_REQUEST["familia"];?>&subfamilia=<?=$_REQUEST["subfamilia"];?>">Next &raquo;</a></li><?
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