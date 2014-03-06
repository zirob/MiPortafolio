<?php

//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT dp.*  ";
$consulta.= "FROM detalles_productos dp  ";
$consulta.= "WHERE dp.rut_empresa='".$_SESSION['empresa']."' AND dp.cod_producto='".$_GET['id_prod']."' ";
$consulta.= "AND estado_producto!='3' ";

    if(isset($_REQUEST['patente'])){
        $consulta.=" and patente like '%".$_REQUEST['patente']."%' ";
    }
    if(isset($_REQUEST['especifico']) && $_REQUEST['especifico']!=0){
        $consulta.=" and especifico like '%".$_REQUEST['especifico']."%' ";
    }
    if(isset($_REQUEST['asignado_a_bodega'])){
        $consulta.=" and descripcion_bodega like '%".trim($_REQUEST['asignado_a_bodega'])."%' ";
    }
    if(isset($_REQUEST['estado_producto']) && $_REQUEST['estado_producto']!=0){
        $consulta.=" and estado_producto = ".$_REQUEST['estado_producto']."";
    }
    if(isset($_REQUEST['centro_costo']) ){       
        $consulta.=" and descripcion_cc like '%".$_REQUEST['centro_costo']."%' ";
    }
    if(isset($_REQUEST['arrendado']) && $_REQUEST['arrendado']!=0){
        $consulta.=" and producto_arrendado like '%".$_REQUEST['arrendado']."%' ";
    }

    $consulta.=" ORDER BY cod_producto";
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

if(isset($_GET['elim']) && $_GET['elim']==1){
    $sql ="UPDATE detalles_productos SET estado_producto=3 WHERE cod_detalle_producto=".$_GET['id_det'];
    mysql_query($sql,$con);

    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_productos', '".$_GET['id_det']."', '3'";
    $sql_even.= ", 'UPDATE:estado_producto=3', '".$_SERVER['REMOTE_ADDR']."', 'Update de estado de detalle activos', '1', '".date('Y-m-d H:i:s')."')";
    mysql_query($sql_even, $con);

}

if(isset($_GET['filtro']) && $_GET['filtro']==1){

    // $sel = "SELECT dp.*, b.cod_bodega, b.descripcion_bodega, cc.Id_cc, cc.descripcion_cc ";
    // $sel.= "FROM detalles_productos dp INNER JOIN bodegas b INNER JOIN centros_costos cc ";
    // $sel.= "WHERE dp.rut_empresa='".$_SESSION['empresa']."' AND dp.cod_producto='".$_GET['id_prod']."' ";
    // $sel.= "AND estado_producto!='3' AND dp.asignado_a_bodega=b.cod_bodega AND dp.centro_costo=cc.Id_cc ";

    $sel = "SELECT dp.*  ";
    $sel.= "FROM detalles_productos dp  ";
    $sel.= "WHERE dp.rut_empresa='".$_SESSION['empresa']."' AND dp.cod_producto='".$_GET['id_prod']."' ";
    $sel.= "AND estado_producto!='3' ";
    

 /*   if(isset($_REQUEST['cod_producto'])){
        $sel.=" and cod_producto like '%".$_REQUEST['cod_producto']."%' ";
    }*/
    if(isset($_REQUEST['identificador'])){
        $sel.=" and (patente like '%".$_REQUEST['identificador']."%' OR codigo_interno like '%".$_REQUEST["identificador"]."%') ";
    }
    if(isset($_REQUEST['especifico']) && $_REQUEST['especifico']!=0){
        $sel.=" and especifico like '%".$_REQUEST['especifico']."%' ";
    }
    if(isset($_REQUEST['asignado_a_bodega'])){
        $sel.=" and descripcion_bodega like '%".trim($_REQUEST['asignado_a_bodega'])."%' ";
    }
    if(isset($_REQUEST['estado_producto']) && $_REQUEST['estado_producto']!=0){
        $sel.=" and estado_producto = ".$_REQUEST['estado_producto']."";
    }
    if(isset($_REQUEST['centro_costo']) ){       
        $sel.=" and descripcion_cc like '%".$_REQUEST['centro_costo']."%' ";
    }
    if(isset($_REQUEST['arrendado']) && $_REQUEST['arrendado']!=0){
        $sel.=" and producto_arrendado like '%".$_REQUEST['arrendado']."%' ";
    }

    $sel.=" ORDER BY cod_producto";
    $sel.= " ".$limit;


}else{
       
    // $sel = "SELECT dp.*, b.cod_bodega, b.descripcion_bodega, cc.Id_cc, cc.descripcion_cc ";
    // $sel.= "FROM detalles_productos dp INNER JOIN bodegas b INNER JOIN centros_costos cc ";
    // $sel.= "WHERE dp.rut_empresa='".$_SESSION['empresa']."' AND dp.cod_producto='".$_GET['id_prod']."' ";
    // $sel.= "AND estado_producto!='3' AND dp.asignado_a_bodega=b.cod_bodega AND dp.centro_costo=cc.Id_cc ";
    $sel = "SELECT dp.* ";
    $sel.= "FROM detalles_productos dp  ";
    $sel.= "WHERE dp.rut_empresa='".$_SESSION['empresa']."' AND dp.cod_producto='".$_GET['id_prod']."' ";
    $sel.= "AND estado_producto!='3' ";
    $sel.= " ".$limit;

}

$res = mysql_query($sel,$con);

//Manejo de errores
if($error==0){
        echo "<div style=' width:100%; height:auto; border-top: solid 3px blue;border-bottom: solid 3px blue;color:blue; text-align:center; font-family:tahoma; font-size:18px;'>";
        echo $mensaje;
        echo "</div>";
}else{
        echo "<div style=' width:100%; height:auto; border-top: solid 3px red ;border-bottom: solid 3px red; color:red; text-align:center;font-family:tahoma; font-size:18px;'>";
        echo $mensaje;  
        echo "</div>";
} 

?>


<table id="list_registros" border='0'>
    <tr>
        <td id="list_link" colspan="10">

            <a href="?cat=3&sec=3"><img src="img/view_previous.png" width="30px" height="30px" border="0" class="toolTIP" title="Volver al listado de Activos"></a>
            <a alt="volver" href="?cat=3&sec=6&id_prod=<?=$_GET['id_prod'];?>&action=1">
                <img src="img/add1.png" width="30px" height="30px" border="0" class="toolTIP" title="Agregar Detalle de Activo">
            </a>
            <? 
            if($asigMult==1){
                ?>
                   <!--  <a href="?cat=3&sec=3">
                        <img src="img/document_exchange.png"  width="30px" height="30px" border="0" alt="Reasignar Bodega" style="float:left;" class="toolTIP" title="Volver a Productos">
                    </a> -->
                    <? 
                } 
                ?>
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

  <table id="list_registros" border="1" style="border-collapse:collapse;" >    
    <form action="?cat=3&sec=5&filtro=1&id_prod=<?=$_GET['id_prod'];?>" method="POST">
        <tr id="titulo_reg" style="background-color: #fff;">
            <td width="20px" style="font-family:Tahoma; font-size:12px; text-align:center;">Filtro:</td>
            <td width="40px" style="text-align:center;">
                <!-- <input type="text" style="width:90%;" size="2" name="cod_producto" value='<?=$_REQUEST["cod_producto"];?>' class="fo" > -->
            </td>
            <td width="30px" style="text-align:center;">
                <input type="text" style="width:90%;" size="2" name="identificador" value='<?=$_REQUEST["identificador"];?>' class="fo" >
            </td>
            <td  width="40px" style="text-align:center;">
                <select name="especifico" class='fo'  style="width:90%">
                    <option value="0" <? if($_REQUEST['especifico']==0){ echo " selected ";} ?>>---</option>  
                    <option value="1" <? if($_REQUEST['especifico']==1){ echo " selected ";} ?>>Si</option>  
                    <option value="2" <? if($_REQUEST['especifico']==2){ echo " selected ";} ?>>No</option>  
                </select>
            </td>
            <!-- <td width="100px" style="text-align:center;">
                <input type="text" style="width:90%;" size="2" name="asignado_a_bodega" value='<?/*=$_REQUEST["asignado_a_bodega"];*/?>' class="fo" >
            </td> -->
            <td style="text-align:center;">
                <select name="estado_producto" class="fo" style="width:90%;">
                    <option value="0" <? if($_REQUEST['estado_producto']==0) echo " selected "; ?>>---</option>
                    <option value="1" <? if($_REQUEST['estado_producto']==1) echo " selected "; ?>>Disponible</option>
                    <option value="2" <? if($_REQUEST['estado_producto']==2) echo " selected "; ?>>Asignado</option>
                    <option value="3" <? if($_REQUEST['estado_producto']==3) echo " selected "; ?>>Dado de Baja</option>
                    <option value="4" <? if($_REQUEST['estado_producto']==4) echo " selected "; ?>>En Mantenci&oacute;n</option>
                    <option value="5" <? if($_REQUEST['estado_producto']==5) echo " selected "; ?>>En Reparaci&oacute;n</option>
                </select>
                <!-- <input type="text" style="width:90%;" size="2" name="estado_producto" value='<?=$_REQUEST["estado_producto"];?>' class="fo" > -->
            </td>

            <!-- <td width="100px" style="text-align:center;">
                <input type="text" style="width:90%;" size="2" name="centro_costo" value='<?/*=$_REQUEST["centro_costo"];*/?>' class="fo" >
            </td> -->
            <td id="titulo_tabla"  style="font-family:Tahoma; font-size:12px; text-align:center;" >
                <select name="arrendado" class="fo" style="width:90%" >
                    <option value="0" <? if($_REQUEST['arrendado']==0){ echo " selected ";} ?> >---</option>
                    <option value="1" <? if($_REQUEST['arrendado']==1){ echo " selected ";} ?> >Si</option>
                    <option value="2" <? if($_REQUEST['arrendado']==2){ echo " selected ";} ?> >No</option>
                </select>
            </td>
            <td colspan='2'  style="text-align:right;">    
                <input  type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;">
            </td>
        </tr>
    </form>  

    <!--Nombre de las columnas--> 
    
    <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;text-align: center;">
        <td >#</td>
        <td >Codigo</td>
        <td >Identificador</td>
        <td >Especifico</td>
        <!-- <td >Bodega</td> -->
        <td >Estado</td>
        <!-- <td >Centro Costo</td> -->
        <td >Arrendado</td>
        <td >Editar</td>
        <td >Eliminar</td>
    </tr>    

    <form action="?cat=3&sec=5&asignar=1&id_prod=<?=$_GET['id_prod'];?>" method="POST">

        <!--Detalle-->
        <?

        if(mysql_num_rows($res)!=NULL){

            $i=1;
            while($row = mysql_fetch_assoc($res)){

                switch($row['estado_producto']){
                    case 1:
                    $estado="Disponible";
                    break;
                    case 2:
                    $estado="Asignado";
                    break;
                    case 3:
                    $estado="Dado de Baja";
                    break;
                    case 4:
                    $estado="En Mantenci&oacute;n";
                    break;
                    case 5:
                    $estado="En Reparaci&oacute;n";
                    break;
                }


                ?>
                <tr style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);text-align: center;" >
                    <td><?echo $i;$x=$i;$i++;?></td>
                    <td><?=$row['cod_producto']; ?></td>
                    <td><?if(!empty($row["patente"])){ echo $row['patente'];}else{echo $row["codigo_interno"];}?></td>
                    <td><? if($row['especifico']==1){ echo "Si";}else{ echo "No"; } ?></td>
                    <!-- <td style="text-align:left"><?/*=$row['descripcion_bodega'];*/?></td> -->
                    <td><?if($row["estado_producto"]==1){echo "Disponible";}if($row["estado_producto"]==2){echo "Asignado";}if($row["estado_producto"]==3){echo "Dado de Baja";}if($row["estado_producto"]==4){echo "En Mantención";}if($row["estado_producto"]==5){echo "En Reparación";}?></td>
                    <!-- <td style="text-align:left"><?/*=$row['descripcion_cc'];*/?></td> -->
                    <td><? if($row['producto_arrendado']==1){ echo "Si";}else{ echo "No"; } ?></td>
                    <td><a href="?cat=3&sec=6&action=2&id_prod=<?=$row['cod_producto'];?>&id_det=<?=$row['cod_detalle_producto'];?>&num_lin=<?=$i-1;?>"><img class="toolTIP" title="Editar Detalle de Activo" src="img/editar.png" width="24px" height="24px"></a></td>
                    <td><a href="?cat=3&sec=5&elim=1&id_prod=<?=$_GET['id_prod']?>&id_det=<?=$row['cod_detalle_producto'];?>"><img src="img/eliminar.png" width="24px" height="24px" class="toolTIP" title="Eliminar Detalle de Activo"></a></td>
                </tr>


                <?php
            }
            ?>
        </form>

        <?    
    }else{   
        ?>
        <tr  id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
            <td colspan="11" >No existen Detalle Activos para ser Desplegados</td>
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
            <li><a href="?&cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>&page=<? echo $i;?>&filtro=1&identificador=<?=$_REQUEST["identificador"];?>&especifico=<?=$_REQUEST["especifico"];?>&asignado_a_bodega=<?=$_REQUEST["asignado_a_bodega"];?>&estado_producto=<?=$_REQUEST["estado_producto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&arrendado=<?=$_REQUEST["arrendado"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>&page=<? echo $nextpage;?>&filtro=1&identificador=<?=$_REQUEST["identificador"];?>&especifico=<?=$_REQUEST["especifico"];?>&asignado_a_bodega=<?=$_REQUEST["asignado_a_bodega"];?>&estado_producto=<?=$_REQUEST["estado_producto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&arrendado=<?=$_REQUEST["arrendado"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>&page=<? echo $prevpage;?>&filtro=1&identificador=<?=$_REQUEST["identificador"];?>&especifico=<?=$_REQUEST["especifico"];?>&asignado_a_bodega=<?=$_REQUEST["asignado_a_bodega"];?>&estado_producto=<?=$_REQUEST["estado_producto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&arrendado=<?=$_REQUEST["arrendado"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>&page=<? echo $i;?>&filtro=1&identificador=<?=$_REQUEST["identificador"];?>&especifico=<?=$_REQUEST["especifico"];?>&asignado_a_bodega=<?=$_REQUEST["asignado_a_bodega"];?>&estado_producto=<?=$_REQUEST["estado_producto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&arrendado=<?=$_REQUEST["arrendado"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>&page=<? echo $nextpage;?>&filtro=1&identificador=<?=$_REQUEST["identificador"];?>&especifico=<?=$_REQUEST["especifico"];?>&asignado_a_bodega=<?=$_REQUEST["asignado_a_bodega"];?>&estado_producto=<?=$_REQUEST["estado_producto"];?>&centro_costo=<?=$_REQUEST["centro_costo"];?>&arrendado=<?=$_REQUEST["arrendado"];?>">Next &raquo;</a></li><?
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