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
$consulta = "SELECT * FROM cabecera_oc WHERE rut_empresa = '".$_SESSION['empresa']."' ";
    

if(isset($_REQUEST['id_oc']) && $_REQUEST['id_oc']!=""){
$consulta.=" and id_oc='".$_REQUEST['id_oc']."'";
}

if(isset($_REQUEST['fecha']) && $_REQUEST['fecha']!=""){
$consulta.=" and fecha_oc='".date("Y-m-d",strtotime($_REQUEST['fecha']))."'";
}

if(isset($_REQUEST['solicitante']) && $_REQUEST['solicitante']!=""){
$consulta.=" and solicitante like '%".$_REQUEST['solicitante']."%'";
}

if(isset($_REQUEST['concepto']) && $_REQUEST['concepto']!=""){
$consulta.=" and concepto=".$_REQUEST['concepto'];
}

if(isset($_REQUEST['centro_costo']) && $_REQUEST['centro_costo']!=""){
$consulta.=" and centro_costos=".$_REQUEST['centro_costo'];
}

if(isset($_REQUEST['estado']) && $_REQUEST['estado']!=""){
$consulta.=" and estado_oc=".$_REQUEST['estado'];
}


if(!empty($_REQUEST['fecha_entrega']))
{
$fecha=$_REQUEST['fecha_entrega'];
$consulta.=" and fecha_entrega='".$fecha."'";
}

$consulta.=" ORDER BY fecha_oc";

/*
if(!empty($_REQUEST['patente']))
$consulta.=" AND de.patente like'%".$_REQUEST['patente']."%' ";
*/

if(!empty($_REQUEST['litros']))
$consulta.=" AND s.litros like'%".$_REQUEST['litros']."%' ";


if(!empty($_REQUEST['Id_cc']))
$consulta.=" AND c.Id_cc like'%".$_REQUEST['Id_cc']."%' ";
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
    $sql = "SELECT * FROM cabecera_oc WHERE rut_empresa = '".$_SESSION['empresa']."' ";
    
	
    if(isset($_REQUEST['id_oc']) && $_REQUEST['id_oc']!=""){
        $sql.=" and id_oc='".$_REQUEST['id_oc']."'";
    }

    if(isset($_REQUEST['fecha']) && $_REQUEST['fecha']!=""){
        $sql.=" and fecha_oc like '%".$_REQUEST['fecha']."%'";
    }
    
    if(isset($_REQUEST['solicitante']) && $_REQUEST['solicitante']!=""){
        $sql.=" and solicitante like '%".$_REQUEST['solicitante']."%'";
    }
    
    if(isset($_REQUEST['concepto']) && $_REQUEST['concepto']!=""){
        $sql.=" and concepto=".$_REQUEST['concepto'];
    }
    
    if(isset($_REQUEST['centro_costo']) && $_REQUEST['centro_costo']!=""){
        $sql.=" and centro_costos=".$_REQUEST['centro_costo'];
    }
    
    if(isset($_REQUEST['estado']) && $_REQUEST['estado']!=""){
        $sql.=" and estado_oc=".$_REQUEST['estado'];
    }
	
	
	if(!empty($_REQUEST['fecha_entrega']))
	{
		$fecha=$_REQUEST['fecha_entrega'];
		$sql.=" and fecha_entrega like '%".$fecha."%'";
	}
    
    $sql.=" ORDER BY fecha_oc";
    $sql.= " ".$limit;

}else{
    $sql = "SELECT * FROM cabecera_oc WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY fecha_oc";
    $sql.= " ".$limit;

}
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
<table id="list_registros" er="0" style="border-collapse:collapse; margin-top:10px;">

    <tr id="titulo_reg" style="background-color: #fff;">
        <form action="?cat=4&sec=1&filtro=1" method="POST">
        <td  style="font-family:Tahoma; font-size:12px;"><label>Filtro:</label></td>
        <td  style="text-align:center;"><input type="text" size="11" name="id_oc" class="fo" value='<?=$_REQUEST['id_oc'];?>' onKeyPress='ValidaSoloNumeros()'></td>
        <td  style="text-align:center;"><input type="date" size="10" name="fecha" class="fo" value='<?=$_REQUEST['fecha'];?>'></td>
        <td  style="text-align:center;"><input type="text" size="15" name="solicitante" class="fo" value='<?=$_REQUEST['solicitante'];?>'></td>
        <td  style="text-align:center;">
        <select name="concepto" class="fo">
        <option value=""   class="fo">---</option>
        <option value="1"  class="fo"<? if($_REQUEST['concepto']==1) echo " selected" ;?>>Compra</option>
        <option value="2"  class="fo"<? if($_REQUEST['concepto']==2) echo " selected" ;?>>Mantenci&oacute;n</option>
        <option value="3"  class="fo"<? if($_REQUEST['concepto']==3) echo " selected" ;?>>Reparaci&oacute;n</option>
        <option value="4"  class="fo"<? if($_REQUEST['concepto']==4) echo " selected" ;?>>Servicios</option>
        <option value="5"  class="fo"<? if($_REQUEST['concepto']==5) echo " selected" ;?>>Activos</option>
        <option value="6"  class="fo"<? if($_REQUEST['concepto']==6) echo " selected" ;?>>Repuestos</option>
        <option value="7"  class="fo"<? if($_REQUEST['concepto']==7) echo " selected" ;?>>Fabricacion</option>
        </select>
        </td>
        <td  style="text-align:center;"><select name="centro_costo"  class="fo">
                <option value=""  class="fo">---</option>
            <?
                $s = "SELECT * FROM centros_costos ORDER BY descripcion_cc";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r)){
                    ?>  <option value="<?=$roo['Id_cc'];?>"   <? if($_REQUEST['centro_costo']==$roo['Id_cc']) echo " selected" ;?> class="fo"><?=$roo['descripcion_cc'];?></option> <?    
                }
        
                ?>
            </select>    
        </td>
         <td></td>
         <td  style="text-align:center;"><select name="estado" style="width:100px;" class="fo">
                 <option value="">---</option>
                 <option value="1"  class="fo"<? if($_REQUEST['estado']==1) echo " selected" ;?>>Abierta</option>
                 <option value="2"  class="fo"<? if($_REQUEST['estado']==2) echo " selected" ;?>>Pendiente</option>
                 <option value="4"  class="fo"<? if($_REQUEST['estado']==4) echo " selected" ;?>>Cerrada</option>
                 <option value="3"  class="fo"<? if($_REQUEST['estado']==3) echo " selected" ;?>>Aprobada</option>
             </select></td>
           <td style="text-align:center;"><input  type="date" class="fo" name="fecha_entrega" value="<? echo $_REQUEST['fecha_entrega'];?>"/></td>
        <td colspan="2" style="text-align:right"><input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
        </form>
    </tr>
    <tr id="titulo_reg"  style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td width="20px" style="text-align:center;">#</td>
        <td width="20px" style="text-align:center;">ID OC</td>
        <td width="120px"style="text-align:center;">Fecha OC</td>
        <td style="text-align:center;">Solicitante</td>
        <td style="text-align:center;">Concepto</td>
        <td style="text-align:center;">Centro Costos</td>
        <td style="text-align:center;">Total</td>
        <td style="text-align:center;">Estado</td>
        <td width="100px" style="text-align:center;">Fecha Entrega</td>
        <td width="20px" style="text-align:center;"></td>
        <td width="20px" style="text-align:center;"></td>
    </tr>
<?
 if(mysql_num_rows($res)!= NULL){
     $i=1;
 while($row = mysql_fetch_assoc($res)){
 
     
     switch($row['estado_oc']){
         case 1:
             $estado = "Abierto";
             break;
         case 2:
             $estado = "Pendiente";
             break;
         case 3: 
             $estado = "Cerrada";
             break;
         case 4: 
             $estado = "Aprobada";
             break;
     }
     
     switch($row['concepto']){
         case 1:
             $concepto = "Compra";
             break;
         case 2: 
             $concepto = "Mantenci&oacute;n";
             break;
         case 3:
             $concepto = "Reparaci&oacute;n";
             break;
     }
     
     
     
     ?>    
    <tr class="listado_datos" style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243); border-collapse:collapse;">
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=$i;$i++;?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=$row['id_oc'];?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=date("d-m-Y",strtotime($row['fecha_oc']));?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=$row['solicitante'];?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=$concepto;?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><? 
        
                $s="SELECT * FROM centros_costos WHERE Id_cc=".$row['centro_costos'];
                $r = mysql_query($s,$con);
                $ro = mysql_fetch_assoc($r);
                
                echo $ro['descripcion_cc'];
                
        
        
        
        ?></td>
        <td style="border:1px solid #666;text-align:right; background-color:#F4F4F4;"><?=$row['total'];?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=$estado;?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"><?=date("d-m-Y",strtotime($row['fecha_entrega']));?></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"></td>
        <td style="border:1px solid #666;text-align:center; background-color:#F4F4F4;"></td>
 
    </tr>  
 <?    
 }
 }else{
     ?>
    <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="100%">No existen OC a Ser Desplegadas</td>
    </tr>
 <?   
 }
?>
<form action="includes/Admin/OC_excel.php" method="POST">     
            <input type="hidden" name="sql" id="sql" hidden="hidden" value="<?  echo $sql; ?>">

            <table align="center">
            <tr><td>
           <input type="submit" value="Exportar a Excel"></td></tr>
            </table>
             </form>
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
                <li><a href="?&cat=4&sec=14&page=<? echo $i;?>&filtro=1&id_oc=<?=$_REQUEST["id_oc"];?>&fecha=<?=$_REQUEST["fecha"];?>&solicitante=<?=$_REQUEST["solicitante"];?>&concepto=<?=$_REQUEST["concepto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&estado=<?=$_REQUEST["estado"];?>&fecha_entrega=<?=$_REQUEST["fecha_entrega"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=4&sec=14&page=<? echo $nextpage;?>&filtro=1&id_oc=<?=$_REQUEST["id_oc"];?>&fecha=<?=$_REQUEST["fecha"];?>&solicitante=<?=$_REQUEST["solicitante"];?>&concepto=<?=$_REQUEST["concepto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&estado=<?=$_REQUEST["estado"];?>&fecha_entrega=<?=$_REQUEST["fecha_entrega"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=4&sec=14&page=<? echo $prevpage;?>&filtro=1&id_oc=<?=$_REQUEST["id_oc"];?>&fecha=<?=$_REQUEST["fecha"];?>&solicitante=<?=$_REQUEST["solicitante"];?>&concepto=<?=$_REQUEST["concepto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&estado=<?=$_REQUEST["estado"];?>&fecha_entrega=<?=$_REQUEST["fecha_entrega"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=4&sec=14&page=<? echo $i;?>&filtro=1&id_oc=<?=$_REQUEST["id_oc"];?>&fecha=<?=$_REQUEST["fecha"];?>&solicitante=<?=$_REQUEST["solicitante"];?>&concepto=<?=$_REQUEST["concepto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&estado=<?=$_REQUEST["estado"];?>&fecha_entrega=<?=$_REQUEST["fecha_entrega"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=4&sec=14&page=<? echo $nextpage;?>&filtro=1&id_oc=<?=$_REQUEST["id_oc"];?>&fecha=<?=$_REQUEST["fecha"];?>&solicitante=<?=$_REQUEST["solicitante"];?>&concepto=<?=$_REQUEST["concepto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&estado=<?=$_REQUEST["estado"];?>&fecha_entrega=<?=$_REQUEST["fecha_entrega"];?>">Next &raquo;</a></li><?
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