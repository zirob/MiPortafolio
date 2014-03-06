<?php
// var_dump($_POST);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT c.id_ot, c.solicitado, c.asignacion, c.descripcion_ot, c.fecha_ingreso fecha_ingreso_ot, ";
$consulta.= "c.tipo_trabajo, c.concepto_trabajo, c.estado_ot, c.asigna_ot, ";
$consulta.= "cc.descripcion_cc, p.descripcion ";
$consulta.= "FROM cabeceras_ot c inner join centros_costos cc inner join productos p  ";
$consulta.= "WHERE ";
$consulta.= "c.rut_empresa = '".$_SESSION['empresa']."' ";
$consulta.= "AND c.centro_costos=cc.id_cc ";
$consulta.= "AND c.cod_producto=p.cod_producto ";

if(isset($_REQUEST['tipo_trabajo']) && $_REQUEST['tipo_trabajo']!=0){
        $consulta.=" and tipo_trabajo like '%".$_REQUEST['tipo_trabajo']."%' ";
    }

    if(isset($_REQUEST['cod_producto']) && $_REQUEST['cod_producto']!=0){
        $consulta.=" and c.cod_producto = '".$_REQUEST['cod_producto']."' ";
    }
    
    if(isset($_REQUEST['cod_detalle_producto']) && $_REQUEST['cod_detalle_producto']!=""){
        $consulta.=" and dp.cod_detalle_producto like '%".$_REQUEST['cod_detalle_producto']."%' ";
    }
    
    if(isset($_REQUEST['id_lf']) && $_REQUEST['id_lf']!=0){
        $consulta.=" and c.id_lf like '%".$_REQUEST['id_lf']."%' ";
    }
    
    if(isset($_REQUEST['concepto_trabajo']) && $_REQUEST['concepto_trabajo']!=0){
        $consulta.=" and concepto_trabajo='".$_REQUEST['concepto_trabajo']."' ";
    }
    
    if(isset($_REQUEST['centro_costos']) && $_REQUEST['centro_costos']!=0){
        $consulta.=" and centro_costos='".$_REQUEST['centro_costos']."'";
    }

    if(isset($_REQUEST['estado_ot']) && $_REQUEST['estado_ot']!=0){
        $consulta.=" and estado_ot='".$_REQUEST['estado_ot']."'";
    }

   /* if(isset($_REQUEST['cod_producto']) && $_REQUEST['cod_producto']!=0){
        $consulta.=" and p.cod_producto='".$_REQUEST['cod_producto']."'";
    }*/
    
    if(isset($_REQUEST['solicitado']) && $_REQUEST['solicitado']!=""){
        $consulta.=" and c.solicitado like '%".$_REQUEST['solicitado']."%'";
    }

    if(isset($_REQUEST['asignacion']) && $_REQUEST['asignacion']!=""){
        $consulta.=" and c.asignacion like '%".$_REQUEST['asignacion']."%'";
    }

    if(isset($_REQUEST['asigna_ot']) && $_REQUEST['asigna_ot']!=""){
        $consulta.=" and c.asigna_ot like '%".$_REQUEST['asigna_ot']."%'";
    }

    if(isset($_REQUEST['fecha_ingreso_ot']) && $_REQUEST['fecha_ingreso_ot']!=""){
        $consulta.=" and c.fecha_ingreso like '%".$_REQUEST['fecha_ingreso_ot']."%'";
    }

$consulta.=" ORDER BY id_ot DESC ";
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

    $sql = "SELECT c.id_ot, c.solicitado, c.asignacion, c.descripcion_ot, c.fecha_ingreso fecha_ingreso_ot, ";
    $sql.= "c.tipo_trabajo, c.concepto_trabajo, c.estado_ot, c.asigna_ot, ";
    $sql.= "cc.descripcion_cc, p.descripcion ";
    $sql.= "FROM cabeceras_ot c inner join centros_costos cc inner join productos p  ";
    $sql.= "WHERE ";
    $sql.= "c.rut_empresa = '".$_SESSION['empresa']."' ";
    $sql.= "AND c.centro_costos=cc.id_cc ";
    $sql.= "AND c.cod_producto=p.cod_producto ";
    // $sql.= "AND c.cod_detalle_producto=dp.cod_detalle_producto ";   
    // $sql.= "AND c.id_lf=lf.id_lf ";

    if(isset($_REQUEST['tipo_trabajo']) && $_REQUEST['tipo_trabajo']!=0){
        $sql.=" and tipo_trabajo like '%".$_REQUEST['tipo_trabajo']."%' ";
    }

    if(isset($_REQUEST['cod_producto']) && $_REQUEST['cod_producto']!=0){
        $sql.=" and c.cod_producto = '".$_REQUEST['cod_producto']."' ";
    }
    
    if(isset($_REQUEST['cod_detalle_producto']) && $_REQUEST['cod_detalle_producto']!=""){
        $sql.=" and dp.cod_detalle_producto like '%".$_REQUEST['cod_detalle_producto']."%' ";
    }
    
    if(isset($_REQUEST['id_lf']) && $_REQUEST['id_lf']!=0){
        $sql.=" and c.id_lf like '%".$_REQUEST['id_lf']."%' ";
    }
    
    if(isset($_REQUEST['concepto_trabajo']) && $_REQUEST['concepto_trabajo']!=0){
        $sql.=" and concepto_trabajo='".$_REQUEST['concepto_trabajo']."' ";
    }
    
    if(isset($_REQUEST['centro_costos']) && $_REQUEST['centro_costos']!=0){
        $sql.=" and centro_costos='".$_REQUEST['centro_costos']."'";
    }

    if(isset($_REQUEST['estado_ot']) && $_REQUEST['estado_ot']!=0){
        $sql.=" and estado_ot='".$_REQUEST['estado_ot']."'";
    }

   /* if(isset($_REQUEST['cod_producto']) && $_REQUEST['cod_producto']!=0){
        $sql.=" and p.cod_producto='".$_REQUEST['cod_producto']."'";
    }*/
    
    if(isset($_REQUEST['solicitado']) && $_REQUEST['solicitado']!=""){
        $sql.=" and c.solicitado like '%".$_REQUEST['solicitado']."%'";
    }

    if(isset($_REQUEST['asignacion']) && $_REQUEST['asignacion']!=""){
        $sql.=" and c.asignacion like '%".$_REQUEST['asignacion']."%'";
    }

    if(isset($_REQUEST['asigna_ot']) && $_REQUEST['asigna_ot']!=0){
        $sql.=" and c.asigna_ot like '%".$_REQUEST['asigna_ot']."%'";
    }
    if(isset($_REQUEST['fecha_recep_taller']) && $_REQUEST['fecha_recep_taller']!=""){
        $sql.=" and c.fecha_ingreso like '%".$_REQUEST['fecha_recep_taller']."%'";
    }

    $sql.=" ORDER BY id_ot DESC ";    
    $sql.= " ".$limit;
    
    
}else{
    // original
    $sql = "SELECT c.id_ot, c.solicitado, c.asignacion, c.descripcion_ot, c.fecha_ingreso fecha_ingreso_ot, ";
    $sql.= "c.tipo_trabajo, c.concepto_trabajo, c.estado_ot, c.asigna_ot, ";
    $sql.= "cc.descripcion_cc, p.descripcion ";
    $sql.= "FROM cabeceras_ot c inner join centros_costos cc inner join productos p  ";
    $sql.= "WHERE ";
    $sql.= "c.rut_empresa = '".$_SESSION['empresa']."' ";
    $sql.= "AND c.centro_costos=cc.id_cc ";
    $sql.= "AND c.cod_producto=p.cod_producto ";
    $sql.=" ORDER BY id_ot DESC ";
    $sql.= " ".$limit;

 // echo "<br>".$sql;
    /*$sql = "SELECT c.id_ot, c.fecha_ingreso fecha_ingreso_ot, p.descripcion descripcion_prd, c.tipo_trabajo, c.concepto_trabajo, c.estado_ot, ";
    $sql.= " lf.id_lf, lf.descripcion_lf, cc.id_cc, cc.descripcion_cc ";
    $sql.= "FROM cabeceras_ot c inner join productos p inner join lugares_fisicos lf inner join centros_costos cc WHERE ";
    $sql.= "c.rut_empresa = '".$_SESSION['empresa']."' AND ";
    $sql.= "(c.cod_producto=p.cod_producto OR c.id_lf=lf.id_lf) ";
    $sql.= "AND c.centro_costos=cc.id_cc ";*/

    // $sql = "SELECT c.fecha_ingreso fecha_ingreso_ot, p.descripcion descripcion_prd, c.tipo_trabajo, c.concepto_trabajo, c.estado_ot, ";
    // $sql.= " lf.descripcion_lf, cc.descripcion_cc ";
    // $sql.= " FROM  cabeceras_ot c ";
    // $sql.= " INNER JOIN productos p  ON  c.cod_producto=p.cod_producto";
    // $sql.= " (INNER JOIN lugares_fisicos lf ON  c.id_lf=lf.id_lf";
    // $sql.= " OR ";
    // $sql.= " INNER JOIN centros_costos cc ON c.centro_costos=cc.id_cc)";
    // $sql.= " WHERE c.rut_empresa = '".$_SESSION['empresa']."'";
    // echo "<br>".$sql;
}

$res = mysql_query($sql,$con);

?>

<?
// VISUALIZACION DE ERRORES 
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


<style>
   .foo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:11px;
        font-family:Tahoma, Geneva, sans-serif;
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
    <form action="?cat=4&sec=2&filtro=1" method="POST">
        <tr  id="titulo_reg" style="background-color: #fff;">
            
            <td width="3%" style="font-family:Tahoma; font-size:12px; text-align:center;">
                Filtro:
                <!-- <input type="text"   name="id_ot"   value='<?/*=$_REQUEST["id_ot"];*/?>'  class="foo" style="width:95%;" onFocus='this.value=""'> -->
            </td>
            <td width="8%" style="text-align:center;">
                  <select name="tipo_trabajo" class="foo" style="width:95%;">
                      <option value="0"> --- </option>
                      <option value="1" <?if($_REQUEST["tipo_trabajo"]==1){ echo "SELECTED";}?>> Urgente </option>
                      <option value="2" <?if($_REQUEST["tipo_trabajo"]==2){ echo "SELECTED";}?>> Normal </option>
                  </select>
            </td>
            <td width="8%" style="text-align:center;">
                  <select name="asigna_ot" class="foo" style="width:95%;">
                      <option value="0"> --- </option>
                      <option value="1" <?if($_REQUEST["asigna_ot"]==1){ echo "SELECTED";}?>> Activo </option>
                      <option value="2" <?if($_REQUEST["asigna_ot"]==2){ echo "SELECTED";}?>> Lugares Fisicos </option>
                  </select>
            </td>
            <td width="11%" style="text-align:center;">
                  <select name="concepto_trabajo" class="foo" style="width:95%;">
                        <option value="0"> --- </option>
                        <option value="1" <?if($_REQUEST["concepto_trabajo"]==1){ echo "SELECTED";}?>> MANTENCION </option>
                        <option value="2" <?if($_REQUEST["concepto_trabajo"]==2){ echo "SELECTED";}?>> REPARACION </option>
                  </select>
            </td>
            <td width="25%" style="text-align:center;">
                <input type="text"  class="foo" style="width:95%;" name="descripcion_ot" value='<?=$_REQUEST["descripcion_ot"]?>'>
            </td>
            <td width="12%" style="text-align:center;">
                  <select name="centro_costos" class="foo" style="width:95%;">
    <? 
                        $s = "SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                        $res4 = mysql_query($s,$con);
    ?>
                            <option value='0' <? if (isset($_REQUEST['centro_costos']) == 0) echo 'selected'; ?> class="foo"> ---</option>
    <?
                        while($r = mysql_fetch_assoc($res4)){
    ?>
                            <option value="<?=$r['Id_cc'];?>" <? if($_REQUEST["centro_costos"]==$r['Id_cc']){echo "SELECTED";}?>><?=$r['descripcion_cc'];?></option>
    <?  
                        }
    ?>
                  </select>
            </td>
            <td width="12%" style="text-align:center;">
                  <select name="estado_ot" class="foo" style="width:95%">
                        <option value="0"> --- </option>
                        <option value="1" <?if($_REQUEST["estado_ot"]==1){ echo "SELECTED";}?>> Pendiente </option>
                        <option value="2" <?if($_REQUEST["estado_ot"]==2){ echo "SELECTED";}?>> En Proceso </option>
                        <option value="3" <?if($_REQUEST["estado_ot"]==3){ echo "SELECTED";}?>> Finalizado </option>
                        <option value="4" <?if($_REQUEST["estado_ot"]==4){ echo "SELECTED";}?>> En Espera de Materiales </option>
                        <option value="5" <?if($_REQUEST["estado_ot"]==5){ echo "SELECTED";}?>> Trabajo Suspendido </option>
                  </select>
            </td>

            <td width="12%" style="text-align:center;">
                <select name="cod_producto" class="foo" style="width:95%;">
<?php
                $sql2 = "SELECT cod_producto, descripcion FROM productos WHERE 1=1 AND rut_empresa='".$_SESSION["empresa"]."' ORDER BY descripcion ";
                $res2 = mysql_query($sql2,$con);
?>
                <option value='0' <? if (isset($_POST['cod_producto']) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
                <?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                    <option value='<? echo $row2["cod_producto"]; ?>' <? if ($row2['cod_producto'] == $_POST['cod_producto']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $row2["descripcion"];?></option>
<?php
                }
                ?>
            </select>
            </td>

            <!-- <td width="12%" style="text-align:center;">
                <input type='text' class="foo" name='cod_detalle_producto' value='<?/*=$_POST["cod_detalle_producto"];*/?>'>
            </td> -->   

           <td width="25%" style="text-align:center;">
                <input type="text"  class="foo" style="width:95%;" name="solicitado" value='<?=$_REQUEST["solicitado"]?>'>
            </td>  
            <td width="25%" style="text-align:center;">
                <input type="text"  class="foo" style="width:95%;" name="asignacion" value='<?=$_REQUEST["asignacion"]?>'>
            </td> 

            <td width="" style="text-align:center;">
                    <input type="date" name="fecha_recep_taller" class="foo" style="width:90px;" value="<?=$_REQUEST['fecha_recep_taller'];?>">
            </td>
            <td colspan="2" style="text-align:center;">
                    <input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;">
            </td>
        </tr>
        <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;text-align: center;">
            <td>#</td>
            <td>Tipo Trabajo</td>
            <td>Asignado a</td>
            <!-- <td>Activo</td> -->
            <!-- <td>Patente</td> -->
            <!-- <td>Lugar Fisico</td> -->
            <td>Concepto</td>
            <td>Descripci&oacute;n</td>
            <td>Centro Costo</td>
            <td>Estado</td>
            <td>Activo</td>
            <!-- <td>Patente</td> -->
            <!-- <td>Lugares Fisicos</td> -->
            <td>Solicitado</td>
            <td>Asignación</td>
            <td>Fecha Ing.</td>
            <td></td>
            <td></td>
        </tr>
        <?
        if(mysql_num_rows($res)!= null){
           $i=1;
           while($row = mysql_fetch_assoc($res)){
?>


              <tr style="text-align:center; font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);">
                    <td><?=$row["id_ot"];?></td>
                    <td><? 
                        if($row['tipo_trabajo']==1)
                            echo "Urgente";
                        if($row['tipo_trabajo']==2)
                            echo "Normal";
                        ?>
                    </td>
                    <td><? 
                        if($row['asigna_ot']==1)
                            echo "Activo";
                        if($row['asigna_ot']==2)
                            echo "Lugares Fisicos";
                        ?>
                    </td>
                    <!-- <td><?/*=$row["descripcion_prd"];*/?></td> -->
                    <!-- <td><?/*=$row['descripcion_lf'];*/?></td> -->
                    <td>
                        <? 
                        if($row['concepto_trabajo']==1)
                            echo "MANTENCION";
                        if($row['concepto_trabajo']==2)
                            echo "REPARACION";
                        ?>
                    </td>
                    <td style="text-align:left;"><?=$row['descripcion_ot'];?></td>
                    <td><?=$row['descripcion_cc'];?></td>
                    <td>
                        <? 
                        if($row['estado_ot']==1)
                            echo "Pendiente";
                        if($row['estado_ot']==2)
                            echo "En Proceso";
                        if($row['estado_ot']==3)
                            echo "Finalizado";
                        if($row['estado_ot']==4)
                            echo "En Espera de Materiales";
                        if($row['estado_ot']==5)
                            echo "Trabajo Suspendido";
                      ?>
                    </td>
                    <td>
                        <?=$row["descripcion"];?>
                    </td>    
                    <!-- <td>
                        <?/*echo $row["patente"];*/?>
                    </td> -->
                   <!--  <td>
                        <?/*echo $row["descripcion_lf"];*/?>
                    </td> -->
                    <td>
                        <?echo $row["solicitado"];?>
                    </td>
                    <td>
                        <?echo $row["asignacion"];?>
                    </td>
                    
                    <td><?=date("d-m-Y",strtotime($row['fecha_ingreso_ot'])); ?></td>
                    <td style="text-align:center;width:08%;" ></td>
                    <td style="text-align:center;width:8%;"></td>

              </tr>  
            <?    
        }
    }else{
       ?>
       <tr id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif;; font-size:12px;">
        <td colspan="13">No existen Ordenes de Trabajo a Ser Desplegadas</td>
    </tr>
    <?   
}
?>
</form>
<form action="includes/Admin/OT_excel.php" method="POST">     
    <input type="hidden" name="sql" id="sql" hidden="hidden" value="<?  echo $sql; ?>">
    <table align="center">
         <tr>
            <td>
                <input type="submit" value="Exportar a Excel">
            </td>
         </tr>
    </table>
</form>

<br>
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
            <li><a href="?&cat=4sec=2&page=<? echo $i;?>&filtro=1&tipo_trabajo=<?=$_REQUEST["tipo_trabajo"];?>&cod_producto=<?=$_REQUEST["cod_producto"];?>&cod_detalle_producto=<?=$_REQUEST["cod_detalle_producto"];?>&id_lf=<?=$_REQUEST["id_lf"];?>&concepto_trabajo=<?=$_REQUEST["concepto_trabajo"];?>&centro_costos=<?=$_REQUEST["centro_costos"];?>&estado_ot=<?=$_REQUEST["estado_ot"];?>"><? echo $i;?></a></li>
 <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
    if($lastpage >$page ){?>       
      <li class="next"><a href="?&cat=4&sec=2&page=<? echo $nextpage;?>&filtro=1&tipo_trabajo=<?=$_REQUEST["tipo_trabajo"];?>&cod_producto=<?=$_REQUEST["cod_producto"];?>&cod_detalle_producto=<?=$_REQUEST["cod_detalle_producto"];?>&id_lf=<?=$_REQUEST["id_lf"];?>&concepto_trabajo=<?=$_REQUEST["concepto_trabajo"];?>&centro_costos=<?=$_REQUEST["centro_costos"];?>&estado_ot=<?=$_REQUEST["estado_ot"];?>" >Next &raquo;</a></li><?
    }else{?>
      <li class="next-off">Next &raquo;</li>
<?  }
} else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
    ?>
      <li class="previous"><a href="?&cat=4&sec=2&page=<? echo $prevpage;?>&filtro=1&tipo_trabajo=<?=$_REQUEST["tipo_trabajo"];?>&cod_producto=<?=$_REQUEST["cod_producto"];?>&cod_detalle_producto=<?=$_REQUEST["cod_detalle_producto"];?>&id_lf=<?=$_REQUEST["id_lf"];?>&concepto_trabajo=<?=$_REQUEST["concepto_trabajo"];?>&centro_costos=<?=$_REQUEST["centro_costos"];?>&estado_ot=<?=$_REQUEST["estado_ot"];?>"  >&laquo; Previous</a></li><?
      for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
            if($page == $i){
        ?>  <li class="active"><? echo $i;?></li><?
            }else{
        ?>  <li><a href="?&cat=4&sec=2&page=<? echo $i;?>&filtro=1&tipo_trabajo=<?=$_REQUEST["tipo_trabajo"];?>&cod_producto=<?=$_REQUEST["cod_producto"];?>&cod_detalle_producto=<?=$_REQUEST["cod_detalle_producto"];?>&id_lf=<?=$_REQUEST["id_lf"];?>&concepto_trabajo=<?=$_REQUEST["concepto_trabajo"];?>&centro_costos=<?=$_REQUEST["centro_costos"];?>&estado_ot=<?=$_REQUEST["estado_ot"];?>" ><? echo $i;?></a></li><?
            }
      }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
      if($lastpage >$page ){    ?> 
      <li class="next"><a href="?&cat=4&sec=2&page=<? echo $nextpage;?>&filtro=1&tipo_trabajo=<?=$_REQUEST["tipo_trabajo"];?>&cod_producto=<?=$_REQUEST["cod_producto"];?>&cod_detalle_producto=<?=$_REQUEST["cod_detalle_producto"];?>&id_lf=<?=$_REQUEST["id_lf"];?>&concepto_trabajo=<?=$_REQUEST["concepto_trabajo"];?>&centro_costos=<?=$_REQUEST["centro_costos"];?>&estado_ot=<?=$_REQUEST["estado_ot"];?>">Next &raquo;</a></li><?
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