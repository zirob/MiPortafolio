<script>
function validar(id_pi,rut,user)
{   
    res=confirm("¿Esta seguro de Generar un nuevo Resultado?");
    if(res==true){
        result=$("#resultado");
        result.html("cargando...")
        $.get("buscador3.php",{id_pi:id_pi,rut:rut,user:user})
        .success(function(data){ result.html(data)})
        .error(function(a,e){ result.html(e)});
    }
}
</script>

<?

//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT p.*, u.nombre FROM preinventario p, usuarios u ";
$consulta.= "WHERE p.rut_empresa = '".$_SESSION['empresa']."' AND u.usuario=p.usuario_ingreso  ";

if(isset($_REQUEST['fecha_pi']) && $_REQUEST['fecha_pi']!=""){
    $consulta.=" and fecha_pi like '%".$_REQUEST['fecha_pi']."%' ";
}
if(isset($_REQUEST['descripcion_pi']) && $_REQUEST['descripcion_pi']!=""){
    $consulta.=" and descripcion_pi like '%".$_REQUEST['descripcion_pi']."%' ";
}
if(isset($_REQUEST['estado_pi']) && $_REQUEST['estado_pi']!="0"){
    $consulta.=" and estado_pi ='".$_REQUEST['estado_pi']."' ";
}
if(isset($_REQUEST['usuario_ingreso']) && $_REQUEST['usuario_ingreso']!=""){
    $consulta.=" and nombre like '%".$_REQUEST['usuario_ingreso']."%' ";
}
if(isset($_REQUEST['fecha_ingreso']) && $_REQUEST['fecha_ingreso']!=""){
    $consulta.=" and p.fecha_ingreso like '%".$_REQUEST['fecha_ingreso']."%' ";
}

$consulta.=" ORDER BY id_pi";
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
    $sql = "SELECT p.*, u.nombre FROM preinventario p, usuarios u ";
    $sql.= "WHERE p.rut_empresa = '".$_SESSION['empresa']."' AND u.usuario=p.usuario_ingreso  ";
    
    if(isset($_REQUEST['fecha_pi']) && $_REQUEST['fecha_pi']!=""){
        $sql.=" and fecha_pi like '%".$_REQUEST['fecha_pi']."%' ";
    }
    if(isset($_REQUEST['descripcion_pi']) && $_REQUEST['descripcion_pi']!=""){
        $sql.=" and descripcion_pi like '%".$_REQUEST['descripcion_pi']."%' ";
    }
    if(isset($_REQUEST['estado_pi']) && $_REQUEST['estado_pi']!="0"){
        $sql.=" and estado_pi ='".$_REQUEST['estado_pi']."' ";
    }
    if(isset($_REQUEST['usuario_ingreso']) && $_REQUEST['usuario_ingreso']!=""){
        $sql.=" and nombre like '%".$_REQUEST['usuario_ingreso']."%' ";
    }
    if(isset($_REQUEST['fecha_ingreso']) && $_REQUEST['fecha_ingreso']!=""){
        $sql.=" and p.fecha_ingreso like '%".$_REQUEST['fecha_ingreso']."%' ";
    }

    $sql.=" ORDER BY id_pi";
    $sql.= " ".$limit;

}else{
    $sql = "SELECT p.*, u.nombre FROM preinventario p, usuarios u ";
    $sql.= "WHERE p.rut_empresa = '".$_SESSION['empresa']."' AND u.usuario=p.usuario_ingreso AND p.rut_empresa=u.rut_empresa ";
    $sql.= " ".$limit;

}

$res = mysql_query($sql,$con);
?>

<table id="list_registros" border='0'>
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="7"> </td>
        <td id="list_link">
            <a href="?cat=3&sec=38&action=1">
                <img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Pre-Inventario">
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

<form action="?cat=3&sec=37&filtro=1" method="POST" >
    <table id="list_registros" border="1" style="border-collapse:collapse;" >
    
    <div id='resultado' style="display:none">
    </div>
        <tr  id="titulo_reg" style="background-color: #fff;">
           <td  style="font-family:Tahoma; font-size:12px; text-align:center;">Filtro:</td>
           <td  style="text-align:center;"><input type="date"   name="fecha_pi" value='<?=$_REQUEST["fecha_pi"];?>'  class='fo' style="width:80px;font-size:11px;font-family:Tahoma, Geneva, sans-serif;" onFocus='this.value=""'></td>
           <td  style="text-align:center;"><input type="text" name="descripcion_pi" value="<?=$_REQUEST["descripcion_pi"];?>" style="width:270px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"></td>         
           <td  style="text-align:center;">
            <select name="estado_pi"  class='fo' style="width:90%;">
                <option value="0" <? if($_REQUEST['estado_pi']==0) echo " selected "; ?> >---</option>  
                <option value="1" <? if($_REQUEST['estado_pi']==1){ echo " selected ";} ?> >Abierto</option>
                <option value="2" <? if($_REQUEST['estado_pi']==2){ echo " selected ";} ?> >En Proceso</option>
                <option value="3" <? if($_REQUEST['estado_pi']==3){ echo " selected ";} ?> >Finalizado</option>
            </select>
          </td>
          <td  style="text-align:center;"><input type="text"  name="usuario_ingreso" value="<?=$_REQUEST["usuario_ingreso"];?>"   style="width:85px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"></td>
          <td  style="text-align:center;"><input type="date"   name="fecha_ingreso" value='<?=$_REQUEST["fecha_ingreso"];?>' class="fo" style="width:80px;" onFocus='this.value=""'></td>
          <td colspan="4" style="text-align:right;"><input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
        </tr>

        <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
           <td width="20px" style="text-align: center;" >#</td>
           <td width="110px" style="text-align: center;" >Fecha</td>
           <td width="250px" style="text-align: center;" >Descripción</td>
           <td width="80px" style="text-align: center;" >Estado</td>
           <td width="100px" style="text-align: center;" >Usuario Ingreso</td>
           <td width="90px" style="text-align: center;" >Fecha Ingreso</td>
           <td width="20px" style="text-align: center;" >Editar</td>
           <td width="30px" style="text-align: center;" >Ingreso Det.</td>
           <td width="40px" style="text-align: center;" >Generar Resul.</td>
           <td width="50px"style="text-align: center;" >Resul. Pre-Inv.</td>
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
               <td style="text-align: center;"><?=date("d-m-Y",strtotime($row["fecha_pi"]));?></td>
               <td style="text-align: left;"><?=$row["descripcion_pi"];?></td>
               <td style="text-align: center;">
                <?
                if($row["estado_pi"]==1)
                    echo "Abierto";
                if($row["estado_pi"]==2)
                    echo "En Proceso";   
                if($row["estado_pi"]==3)
                    echo "Finalizado";

                ?>
                </td>
                <td style="text-align: center;"><?=$row["nombre"];?></td>
                <td style="text-align: center;"><?=date("d-m-Y",strtotime($row["fecha_ingreso"]));?></td>
                <td style="text-align: center;"><a href="?cat=3&sec=38&action=2&id_pi=<?=$row['id_pi'];?>"><img src="img/edit.png" width="24px" height="24px" class="toolTIP" title="Editar Pre-Inventario"></a></td>
                <td style="text-align: center;"><a href="?cat=3&sec=39&id_pi=<?=$row['id_pi'];?>"><img src="img/document_add.png" width="24px" height="24px" class="toolTIP" title="Ingresar Detalle de Pre-Inventario"></a></td>
                <td style="text-align: center;" ><button type='button' width="24px" height="24px" onclick="validar('<?=$row['id_pi'];?>','<?=$_SESSION['empresa'];?>','<?=$_SESSION['user'];?>')"   class="toolTIP" title="Generar Resultado"><img src='img/generar.png' border='0' width="24px" height="24px" style="padding:0px"></button></td>
                
                <td style="text-align: center;"><a href="?cat=3&sec=42&id_pi=<?=$row['id_pi'];?>"><img src="img/result.jpg" width="24px" height="24px" class="toolTIP" title="Obtener Resultado de Pre-Inventario"></a></td>

          </tr>
          <?
      }
  }else{
?>
        <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
          <td colspan="10">No existen Pre-Inventarios a Ser Desplegadas</td>
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
            <li><a href="?&cat=3&sec=37&page=<? echo $i;?>&filtro=1&fecha_pi=<?=$_REQUEST["fecha_pi"];?>&descripcion_pi=<?=$_REQUEST["descripcion_pi"];?>&estado_pi=<?=$_REQUEST["estado_pi"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=3&sec=37&page=<? echo $nextpage;?>&filtro=1&fecha_pi=<?=$_REQUEST["fecha_pi"];?>&descripcion_pi=<?=$_REQUEST["descripcion_pi"];?>&estado_pi=<?=$_REQUEST["estado_pi"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=3&sec=37&page=<? echo $prevpage;?>&filtro=1&fecha_pi=<?=$_REQUEST["fecha_pi"];?>&descripcion_pi=<?=$_REQUEST["descripcion_pi"];?>&estado_pi=<?=$_REQUEST["estado_pi"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=3&sec=37&page=<? echo $i;?>&filtro=1&fecha_pi=<?=$_REQUEST["fecha_pi"];?>&descripcion_pi=<?=$_REQUEST["descripcion_pi"];?>&estado_pi=<?=$_REQUEST["estado_pi"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=3&sec=37&page=<? echo $nextpage;?>&filtro=1&fecha_pi=<?=$_REQUEST["fecha_pi"];?>&descripcion_pi=<?=$_REQUEST["descripcion_pi"];?>&estado_pi=<?=$_REQUEST["estado_pi"];?>&usuario_ingreso=<?=$_REQUEST["usuario_ingreso"];?>&fecha_ingreso=<?=$_REQUEST["fecha_ingreso"];?>">Next &raquo;</a></li><?
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