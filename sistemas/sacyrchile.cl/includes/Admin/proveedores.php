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
$consulta = "SELECT rut_proveedor, razon_social, domicilio, telefono_1, descripcion_esp , sub_especializacion, e.id_esp ";
$consulta.= "FROM proveedores p inner join especialidades e ";
$consulta.= "WHERE p.id_esp=e.id_esp AND rut_empresa='".$_SESSION['empresa']."'";
if(!empty($_REQUEST['razon_social'])){
        $consulta.= " AND razon_social like '%".$_REQUEST['razon_social']."%'";
    }

    //Filtro de domicilio
    if(!empty($_REQUEST['direccion']) && $_REQUEST['direccion']!=""){
        $consulta.= " AND domicilio like '%".$_REQUEST['direccion']."%'";
    }
    
    //Filtro por telefono 
    if(!empty($_REQUEST['telefono']) && $_REQUEST['telefono']!=""){
        $consulta.= " AND telefono_1 like '%".$_REQUEST['telefono']."%'";
    }

    //Filtro por sub-especilacion
    if(!empty($_REQUEST['sub_especializacion']) && $_REQUEST['sub_especializacion']!=""){
        $consulta.= " AND sub_especializacion like '%".$_REQUEST['sub_especializacion']."%'";
    }
    //Filtro de especialidades
    if(!empty($_REQUEST['especialidades']) && $_REQUEST['especialidades']!=""){
        $consulta.= " AND e.id_esp='".$_REQUEST["especialidades"]."'";
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

// var_dump($_REQUEST);

if(isset($_GET['filtro']) && $_GET['filtro']==1){

    $qry = "SELECT rut_proveedor, razon_social, domicilio, telefono_1, descripcion_esp , sub_especializacion, e.id_esp ";
    $qry.= "FROM proveedores p inner join especialidades e ";
    $qry.= "WHERE p.id_esp=e.id_esp AND rut_empresa='".$_SESSION['empresa']."'";

    if(!empty($_REQUEST['razon_social'])){
        $qry.= " AND razon_social like '%".$_REQUEST['razon_social']."%'";
    }

    //Filtro de domicilio
    if(!empty($_REQUEST['direccion']) && $_REQUEST['direccion']!=""){
        $qry.= " AND domicilio like '%".$_REQUEST['direccion']."%'";
    }
    
    //Filtro por telefono 
    if(!empty($_REQUEST['telefono']) && $_REQUEST['telefono']!=""){
        $qry.= " AND telefono_1 like '%".$_REQUEST['telefono']."%'";
    }

    //Filtro por sub-especilacion
    if(!empty($_REQUEST['sub_especializacion']) && $_REQUEST['sub_especializacion']!=""){
        $qry.= " AND sub_especializacion like '%".$_REQUEST['sub_especializacion']."%'";
    }
    //Filtro de especialidades
    if(!empty($_REQUEST['especialidades']) && $_REQUEST['especialidades']!=""){
        $qry.= " AND e.id_esp='".$_REQUEST["especialidades"]."'";
        $row2["id_esp"] = $_REQUEST["especialidades"];   
        $row["id_esp"] = $_REQUEST["especialidades"];   
    }
    
    $qry.=" ORDER BY razon_social";
    $sqy.= " ".$limit;


}else{
    $qry = "SELECT rut_proveedor, razon_social, domicilio, telefono_1, descripcion_esp , sub_especializacion, e.id_esp ";
    $qry.= "FROM proveedores p inner join especialidades e ";
    $qry.= "WHERE p.id_esp = e.id_esp AND rut_empresa = '".$_SESSION['empresa']."' ";
    $sqy.= " ".$limit;

}

$res = mysql_query($qry,$con);

?>

<!--boton de agregar proveedor-->

<table id="list_registros">
    <!-- Agrear usuarios -->
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="5">  </td>
        <td id="list_link">
            <a href="?cat=2&sec=8&action=1">
                <img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" 
                      title="Agregar Proveedor">
            </a>
        </td>
    </tr>
 </table>   

<!--filtros-->

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
    <form action="?cat=2&sec=7&filtro=1" method="POST">
        <tr id="titulo_reg" style="background-color: #fff;">
            <td width="20px" style="font-family:Tahoma; font-size:12px; text-align:center;">Filtro:</td>
            <td style="text-align:center;"><input type="text" name="razon_social"  value='<?  echo $_REQUEST["razon_social"]?>' class="fo" onFocus='this.value=""'></td>
            <td style="text-align:center;"><input type="text" name="telefono"      value='<?  echo $_REQUEST["telefono"]?>'   class="fo" onFocus='this.value=""' onKeyPress="ValidaSoloNumeros()"></td>
            <td style="text-align:center;"><input type="text" name="direccion"     value='<?  echo $_REQUEST["direccion"]?>'  class="fo" onFocus='this.value=""'></td>
            <td width="120px" style="text-align:center;"><select name="especialidades" style='width: 100px'  class="fo"><!--elige especialidades-->
<?php
                $sql2 = "SELECT id_esp, descripcion_esp FROM especialidades WHERE 1=1 ORDER BY descripcion_esp ";
                $res2 = mysql_query($sql2,$con);
?>
                <option value='0' <? if (isset($_REQUEST["especialidades"]) == 0) echo 'selected'; ?> class="fo">---</option>
<?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                    <option value='<? echo $row2["id_esp"]; ?>' <? if ($row2['id_esp'] == $_REQUEST['especialidades']) echo "selected"; ?> class="fo"><?  echo $row2["descripcion_esp"];?></option>
<?php
                }
?>
                </select>
            </td>
            <td style="text-align:center;"><input type="text" name="sub_especializacion" value='<?  echo $_REQUEST["sub_especializacion"]?>' class="fo"  onFocus='this.value=""'></td>
            <td colspan="2" style="text-align:center;"><input type="Submit"  value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"></td>
        </tr>
    </form>

    <!--Nombre de las columnas-->

    <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
        <td  width="30px;"  style="text-align: center;" >#</td>
        <td  width="150px;" style="text-align: center;" >Razon Social</td>
        <td  width="120px;" style="text-align: center;" >Teléfono</td>
        <td  width="150px;" style="text-align: center;" >Domicilio</td>
        <td  width="120px;" style="text-align: center;" >Especialización</td>
        <td  width="150px;" style="text-align: center;" >Sub-Especialización</td>
        <td  width="45px;"  style="text-align: center;" >Ver</td>
        <td  width="45px;"  style="text-align: center;" >Editar</td> 
    </tr>    

    <!--Detalle-->

<?php
$num = mysql_num_rows($res);
    if(mysql_num_rows($res)!=null){
        $i=1;
        while($row = mysql_fetch_assoc($res)){
?>
            <style>
            #row:hover
            {
                background-color:#FF000;
            }
            </style>

            <tr style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
                <td style="text-align: center;"><?echo $i;$i++;?></td>
                <td style="text-align: left;"><?=$row['razon_social']; ?></td>
                <td style="text-align: center;"><?=$row['telefono_1'];?></td>
                <td style="text-align: left;"><?=$row['domicilio'];?></td>
                <td style="text-align: center;"><?=utf8_decode(substr($row["descripcion_esp"], 0, 100 ));?></td>
                <td style="text-align: center;"><?=$row['sub_especializacion'];?></td>
                <td style="text-align: center;"><a href="?cat=2&sec=9&id_prov=<?=$row['rut_proveedor'];?>"><img src="img/view.png" width="24px" height="24px" border="0" class="toolTIP" title="Ver Datos del Proveedor"></a></td>
                <td style="text-align: center;"><a href="?cat=2&sec=8&action=2&id_prov=<?=$row['rut_proveedor'];?>"><img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Proveedor"></a></td>
            </tr>
<?php 
        }
    }else{
?>
            <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
                <td colspan="8">No existen Proveedores para ser Desplegados</td>
            </tr>
<?php
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
            <li><a href="?&cat=2&sec=7&page=<? echo $i;?>&filtro=1&direccion=<?=$_REQUEST["direccion"];?>&telefono=<?=$_REQUEST["telefono"];?>&sub_especializacion=<?=$_REQUEST["sub_especializacion"];?>&especialidades=<?=$_REQUEST["especialidades"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=2&sec=7&page=<? echo $nextpage;?>&filtro=1&direccion=<?=$_REQUEST["direccion"];?>&telefono=<?=$_REQUEST["telefono"];?>&sub_especializacion=<?=$_REQUEST["sub_especializacion"];?>&especialidades=<?=$_REQUEST["especialidades"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=2&sec=7&page=<? echo $prevpage;?>&filtro=1&direccion=<?=$_REQUEST["direccion"];?>&telefono=<?=$_REQUEST["telefono"];?>&sub_especializacion=<?=$_REQUEST["sub_especializacion"];?>&especialidades=<?=$_REQUEST["especialidades"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=2&sec=7&page=<? echo $i;?>&filtro=1&direccion=<?=$_REQUEST["direccion"];?>&telefono=<?=$_REQUEST["telefono"];?>&sub_especializacion=<?=$_REQUEST["sub_especializacion"];?>&especialidades=<?=$_REQUEST["especialidades"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=2&sec=7&page=<? echo $nextpage;?>&filtro=1&direccion=<?=$_REQUEST["direccion"];?>&telefono=<?=$_REQUEST["telefono"];?>&sub_especializacion=<?=$_REQUEST["sub_especializacion"];?>&especialidades=<?=$_REQUEST["especialidades"];?>">Next &raquo;</a></li><?
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