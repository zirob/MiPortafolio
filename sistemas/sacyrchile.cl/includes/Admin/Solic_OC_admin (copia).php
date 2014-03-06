<?php
$action=1;
$_SESSION['id_solic']="";
$id_ot = "";
$descripcion= "";
$tipo=3;
$observaciones="";
$estado=6;
$concepto=4;
$archivos="";
$nueva="";
$msg="";
$sql_ins="";



$mostrar=0;

// Validaciones 
if(!empty($_POST['accion'])){
    
    $error=0;
    if(empty($_POST['descripcion_solicitud'])){
        $error=1;
        $mensaje="Debe Ingresar una descripcion para la Solicitud de Compra";
    } 

    if($_POST['tipo_solicitud'] == 3){
        $error=1;
        $mensaje="Debe seleccionar el Tipo de la Solicitud de Compra";
     }   

    if(empty($_POST['concepto'])){
        $error=1;
        $mensaje="Debe seleccionar el Concepto de la Solicitud de Compra";

    }

    if(empty($_POST['estado'])){
        $error=1;
        $mensaje="Debe seleccionar un Estado de Solicitud de Compra valido";
    }    

}   


// Rescata los datos
if(!empty($_GET['id_solic']) and empty($_POST['primera']))
{
    $sql="SELECT  * FROM  solicitudes_compra WHERE id_solicitud_compra='".$_GET['id_solic']."' ";
    $rec=mysql_query($sql);
    $row=mysql_fetch_array($rec);
     $_POST=$row;
     $_POST["centros_costos"]=$row["id_cc"];
     $_POST["id_solicitud_compra"]=$row["id_solicitud_compra"];
     $s=0;


     $sql_soldet = "SELECT  * FROM  detalles_sc WHERE id_solicitud_compra='".$row["id_solicitud_compra"]."' ";
     $rec_soldet=mysql_query($sql_soldet);
     echo "<br>".$sql_soldet."<br>";
     
     while($row_soldet=mysql_fetch_array($rec_soldet)){
        $s++;
        $_POST['cantidad_'.$s] = $row_soldet["cantidad_det_sc"];
        $_POST['unidad_medida_'.$s] = $row_soldet["unidad_det_sc"];
        $_POST['descripcion_'.$s] =  $row_soldet["descripcion_det_sc"];
        $_POST["EstadoItem_".$s] = $row_soldet["estado_det_sc"];

     }

     $count = "SELECT  count(id_det_sc) AS count_det FROM  detalles_sc WHERE id_solicitud_compra='".$row["id_solicitud_compra"]."' ";
     echo "<br>".$count."<br>";

     $res_count=mysql_query($count);
     $row_count=mysql_fetch_array($res_count);
     $_POST['num_prd'] = $row_count["count_det"];
     


}  
var_dump($_POST);


//Subo el archivo
if ($_POST["action"] == "upload") 
{


    $nombre_archivo = $_FILES['archivo']['name']; 
    // echo "<br>nombre_archivo : ".$_FILES['archivo']['name'];
    $tipo_archivo = $_FILES['archivo']['type']; 
    $tamano_archivo = $_FILES['archivo']['size'];

    // Obtiene extension
    $ext = explode(".",$nombre_archivo);
    $nombre = $ext[0];
    $num = count($ext)-1;



    /*if(!(strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "png")))
    {
        $error=1;
        $mensaje = "La extensión no es correcta";
    }elseif($tamano_archivo > 100000){
        $error=1;
        $mensaje = "Tamaño del archivo no es correcta.";
    }else{*/
        $destino="documentos/".$_FILES['archivo']['name'];
        $fecha=date("Y-m-d H:i:s");
        
        $sql.=" UPDATE usuarios SET";
        $sql.=" nombre_arch_fd='".$_FILES['archivo']['name']."'";
        $sql.=",ruta_arch_fd='".$destino."'";
        $sql.=",ext_arch_fd='".$_FILES['archivo']['type']."'";
        $sql.=",user_insert_fd='".$_SESSION['user']."'";
        $sql.=",fecha_insert_fd='".$fecha."'";
        $sql.=" WHERE usuario='".$_GET['user']."'";
        mysql_query( $sql);
        
        if(move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
                $sql_archivo = "INSERT INTO archivos (id_solicitud, rut_empresa, ruta_archivo, nombre_archivo, extension_archivo, usuario_carga, fecha_carga, estado_archivo) "; 
                $sql_archivo.= "VALUES ('".$_GET['id_solic']."', '".$_SESSION['empresa']."', '".$destino."', '".$nombre."','".$extension."','".$_SESSION['user']."', ";
                $sql_archivo.= "'".date('Y-m-d H:i:s')."',0)";
                mysql_query($sql_archivo,$con);

                $mensaje = "Archivo subido";
        }

}



// if(isset($_GET['action'])){   
    if(!empty($_POST['accion'])){
        
        if($error==0){


            // INSERTO DATOS 
            if($_POST['accion']=="guardar"){
                               
                $sql_ins = "INSERT INTO solicitudes_compra (";
                $sql_ins.= " rut_empresa, id_ot, tipo_solicitud_compra, descripcion_solicitud, ";
                $sql_ins.= " tipo_solicitud, estado, concepto, observaciones, ";
                $sql_ins.= " usuario_ingreso, fecha_ingreso, material, prioridad, origen, ";
                $sql_ins.= " id_cc, cod_producto, cod_detalle_producto, otro_destino) ";
                $sql_ins.= "VALUES (";
                $sql_ins.= "'".$_SESSION['empresa']."','".$_POST['id_ot']."', '".$_POST['tipo_solicitud_compra']."','".$_POST['descripcion_solicitud']."', ";
                $sql_ins.= "'".$_POST['tipo_solicitud']."','1','".$_POST['concepto']."', '".$_POST['observaciones']."', ";
                $sql_ins.= "'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."', '".$_POST['material']."', '".$_POST['prioridad']."', '".$_POST['origen']."', "; 
                $sql_ins.= "'".$_POST['centros_costos']."', '".$_POST['cod_producto']."', '".$_POST['cod_detalle_producto']."', '".$_POST['otro_destino']."') ";
                $consulta=mysql_query($sql_ins,$con);
                echo "<br>".$sql_ins;
               
                
                
                // Obtener numero de solicitud  //////////////////////////////////////////////////////////////
                $sql3 = "SELECT MAX(id_solicitud_compra) AS id_solicitud_compra FROM solicitudes_compra";   //
                $res3 = mysql_query($sql3);                                                                 //
                $row3 = mysql_fetch_assoc($res3);                                                           //
                $sol_ultnum = $row3["id_solicitud_compra"];                                               //
                //////////////////////////////////////////////////////////////////////////////////////////////



                for($x=1; $x<=$_POST["num_prd"]; $x++){
                    // echo "<br>entreamos_".$x;

                    $sql_det = "INSERT INTO detalles_sc (";
                    $sql_det.= "id_solicitud_compra, rut_empresa, cantidad_det_sc, unidad_det_sc, descripcion_det_sc, ";
                    $sql_det.= "estado_det_sc, usuario_ingreso, fecha_ingreso) ";
                    $sql_det.= "VALUES (";
                    $sql_det.= "'".$sol_ultnum."' ,'".$_SESSION['empresa']."','".$_POST['cantidad_'.$x]."', '".$_POST['unidad_medida_'.$x]."','".$_POST['descripcion_'.$x]."', ";
                    $sql_det.= "'".$_POST["EstadoItem_".$x]."' ,'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."' ) ";
                    $consulta2=mysql_query($sql_det,$con);
                 
                    echo "<br>".$sql_det."<br>";

                }
                    
                if($consulta && $consulta2){
                        $mensaje="Solicitud de Compra ha Sido Guardada Correctamente";
                        $mostrar=1;
                }
            
            //  ACTUALIZO DATOS
            }else{

                $sql_update = "UPDATE solicitudes_compra SET id_ot='".$_POST['id_ot']."', tipo_solicitud_compra='".$_POST['tipo_solicitud_compra']."', ";
                $sql_update.= "tipo_solicitud='".$_POST['tipo_solicitud']."', estado='".$_POST["estado"]."', concepto='".$_POST['concepto']."', observaciones='".$_POST['observaciones']."', ";
                $sql_update.= "material='".$_POST['material']."', prioridad='".$_POST['prioridad']."', origen='".$_POST['origen']."',";
                $sql_update.= "id_cc='".$_POST['centros_costos']."', cod_producto='".$_POST['cod_producto']."', cod_detalle_producto='".$_POST['cod_detalle_producto']."', otro_destino='".$_POST['otro_destino']."' ";
                $sql_update.= "WHERE id_ot='".$_POST['id_ot']."'";
                $consulta=mysql_query($sql_update);echo "<br>".$sql_update;
                


                $up_bor = "DELETE FROM detalles_sc WHERE id_solicitud_compra='".$_POST["id_solicitud_compra"]."'";
                $consulta=mysql_query($up_bor,$con);
                echo "<br>".$up_bor."<br>";
echo "<br>".$_POST["num_prd"]."<br>";
                for($x=1; $x<=$_POST["num_prd"]; $x++){
                   
                    echo "<br>entreamos_".$x."<br>";

                    if($_POST["EstadoItem_".$x] == 1){
                        $sql_det = "INSERT INTO detalles_sc (";
                        $sql_det.= "id_solicitud_compra, rut_empresa, cantidad_det_sc, unidad_det_sc, descripcion_det_sc, ";
                        $sql_det.= "estado_det_sc, usuario_ingreso, fecha_ingreso) ";
                        $sql_det.= "VALUES (";
                        $sql_det.= "'".$_POST["id_solicitud_compra"]."' ,'".$_SESSION['empresa']."','".$_POST['cantidad_'.$x]."', '".$_POST['unidad_medida_'.$x]."','".$_POST['descripcion_'.$x]."', ";
                        $sql_det.= "'".$_POST["EstadoItem_".$x]."' ,'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."' ) ";
                        $consulta2=mysql_query($sql_det,$con);
                     
                        // $sql_det = "UPDATE detalles_sc SET ";
                        // $sql_det.= "id_solicitud_compra='".$_POST["id_solicitud_compra"]."', rut_empresa='".$_SESSION['empresa']."', cantidad_det_sc='".$_POST['cantidad_'.$x]."', unidad_det_sc='".$_POST['unidad_medida_'.$x]."', descripcion_det_sc='".$_POST['descripcion_'.$x]."', ";
                        // $sql_det.= "estado_det_sc='".$_POST["EstadoItem_".$x]."', usuario_ingreso='".$_SESSION['user']."', fecha_ingreso='".date('Y-m-d H:i:s')."' ";
                        // $sql_det.= "WHERE id_solicitud_compra='".$_POST["id_solicitud_compra"]."'";
                        // $consulta2=mysql_query($sql_det,$con);

                        echo "<br>".$sql_det;
                    }

                }
                    
                if($consulta && $consulta2){
                       $mensaje="Solicitud de Compra ha sido Actualizada Correctamente";
                       $mostrar=1;
                }


            }

            // Aprueba archivo
                if(isset($_POST['archivo_aprovado']) && $_POST['archivo_aprovado']!=""){
                    echo "<br>estados actualizados";
                    $s2 = "UPDATE archivos SET estado_archivo=0 WHERE id_solicitud=".$_POST['id_solicitud_compra'];
                    mysql_query($s2,$con);echo "<br>".$s2;

                    $s = "UPDATE archivos SET estado_archivo=1 WHERE id_archivo=".$_POST['archivo_aprovado'];
                    mysql_query($s,$con);

                     $mensaje="estado de archivosa ha sido Actualizada Correctamente";
                     $mostrar=1;
                }
        }
    }            
    /*if($_GET['action']==1 && !isset($_GET['user'])){
        
        
        
        $sql_ins="INSERT INTO solicitudes_compra (rut_empresa, id_ot,descripcion_solicitud,tipo_solicitud,estado,concepto,observaciones,usuario_ingreso,fecha_ingreso)
                VALUES ('".$_SESSION['empresa']."','".$_POST['id_ot']."','".$_POST['descripcion_solicitud']."','".$_POST['tipo_solicitud']."','".$_POST['estado']."','".$_POST['concepto']."', '".$_POST['observaciones']."', '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."')";
                
        
        if(isset($_POST['descripcion_solicitud']) && !empty($_POST['descripcion_solicitud']) && $_POST['descripcion_solicitud']!=""){
                if(isset($_POST['tipo_solicitud']) && !empty($_POST['tipo_solicitud']) && $_POST['tipo_solicitud']!=""){
                    if(isset($_POST['concepto']) && !empty($_POST['concepto']) && $_POST['concepto']!=""){
                        if(isset($_POST['estado']) && !empty($_POST['estado']) && $_POST['estado']!=""){
                                 if(mysql_query($sql_ins,$con)){
                                            $rs = mysql_query("SELECT @@identity AS id");

                                            if ($row = mysql_fetch_row($rs)) {
                                               $id = trim($row[0]);


                                            $qry_prv = "SELECT * FROM solicitudes_compra WHERE id_solicitud_compra ='".$id."'";
                                            $sel_prv = mysql_query($qry_prv,$con);


                                           $row = mysql_fetch_assoc($sel_prv);

                                           $id_ot = $row['id_ot'];
                                           $descripcion= $row['descripcion_solicitud'];
                                           $tipo=$row['tipo_solicitud'];
                                           $observaciones=$row['observaciones'];
                                           $concepto = $row['concepto'];
                                           $estado = $row['estado'];
                                           $action="2&new=2&id_solic=".$id;
                                            $nueva =1;
                                           } 

                                           $msg ="Se ha Agregado correctamente la Solicitud de Compra";
                                       }else{
                                           $error="Ha ocurrido un error al grabar datos intente mas tarde";
                                       }
                    }else{
                        $error="Debe seleccionar un Estado de Solicitud de Compra valido";
                    }
                }else{
                    $error="Debe seleccionar el Concepto de la Solicitud de Compra";
                }
            }else{
                $error="Debe seleccionar el Tipo de la Solicitud de Compra";
            }
        }else{
            $error="Debe Ingresar una descripcion para la Solicitud de Compra";
        }   
        
        
        
    }else{
        if(isset($_GET['id_solic'])){

        }else{
            $action="1&new=1";
        }
    }*/

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


/////////////////////////////// SCRIPT ANTIGUO /////////////////////////////////////////////////////////////////////////////////////////

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){echo "<br>entree!!";
        $sql_update="UPDATE solicitudes_compra SET id_ot='".$_POST['id_ot']."', descripcion_solicitud='".$_POST['descripcion_solicitud']."', observaciones='".$_POST['observaciones']."' ,tipo_solicitud = '".$_POST['tipo_solicitud']."',  concepto='".$_POST['concepto']."', estado='".$_POST['estado']."' WHERE id_solicitud_compra='".$_GET['id_solic']."'";
        if(mysql_query($sql_update,$con)){

            if(isset($_POST['archivo_aprovado']) && $_POST['archivo_aprovado']!=""){
                $s = "UPDATE archivos SET estado_archivo=1 WHERE id_archivo=".$_POST['archivo_aprovado'];
                mysql_query($s,$con);
            }
            if(isset($_POST['archivos'])){
                $archivos = $_POST['archivos'];
                $num = count($archivos);

                for($i=0;$i<$num;$i++){

                    $ext = explode(".",$archivos[$i]);
                    $sqlarchivo="INSERT INTO archivos (id_solicitud,rut_empresa,ruta_archivo,nombre_archivo,extension_archivo,usuario_carga,fecha_carga,estado_archivo) VALUES
                    ('".$_GET['id_solic']."','".$_SESSION['empresa']."','../../documentos/','".$ext[0]."','".$ext[1]."','".$_SESSION['user']."','".date('Y-m-d H:i:s')."',0)";
                    $paro = mysql_query($sqlarchivo,$con);
                }    
            }
            if($_POST['estado']==3){
                $msg="Solicitud de Compra ha Sido Autorizada Correctamente";
            }else{
                $msg ="Se han actualizado correctamente los datos";
            }
        }else{
            $msg="Ha ocurrido un error al actualizar intente mas tarde";
        }
    }


    if($_GET['action']==2 && isset($_GET['id_solic'])){

        $qry_prv = "SELECT * FROM solicitudes_compra WHERE id_solicitud_compra ='".$_GET['id_solic']."'";
        $_SESSION['id_solic']=$_GET['id_solic'];
        $sel_prv = mysql_query($qry_prv,$con);


        $row = mysql_fetch_assoc($sel_prv);
        $id = $_GET['id_solic'];
        $id_ot = $row['id_ot'];
        $descripcion= $row['descripcion_solicitud'];
        $tipo=$row['tipo_solicitud'];
        $observaciones=$row['observaciones'];
        $concepto = $row['concepto'];
        $estado = $row['estado'];
        $action="2&new=2&id_solic=".$_GET['id_solic'];
    }

    
// }else{
//     $action="1";
// }


//Mensajes

if(isset($error) && !empty($error)){
    ?>

    <div id="main-error">
        <?php echo $error;?>
    </div>

    <?
}elseif($msg){
    ?>
    <div id="main-ok">
        <?php echo $msg;?>
    </div>
    <?
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>


<style>
.fu
{
    background-color:#FFFFFF;
    color:rgb(0,0,0);
}
</style>

<?php
// if($mostrar==0){
?>

<form action="?cat=2&sec=11&action=<?=$action; ?>" method="POST"  enctype="multipart/form-data">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
        <tr>
            <td id="titulo_tabla" style="text-align:center;" colspan="3"> <a href="?cat=2&sec=10"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Solicitudes de Compra"></</a></td></tr>
        </tr>
    </table>

<?

// Obtener numero de solicitud  //////////////////////////////////////////////////////////////
$sql3 = "SELECT MAX(id_solicitud_compra) AS id_solicitud_compra FROM solicitudes_compra";   //
$res3 = mysql_query($sql3);                                                                 //
$row3 = mysql_fetch_assoc($res3);                                                           //
$sol_num = $row3["id_solicitud_compra"]+1;                                                  //
//////////////////////////////////////////////////////////////////////////////////////////////

?>

<table style="width:900px;" id="detalle-prov" border="1" cellpadding="3" cellspacing="4" >
   <tr>
    <td colspan="3" style="text-align:right; font-size:15pt; font-family:Tahoma, Geneva, sans-serif;"> Nº <?echo $sol_num;?> </td>
</tr>
<tr>
    <td><label>ID OT</label><br />
        <select name="id_ot"  <? if($estado == 3) echo "Disabled"; ?> >
            <option value="0" <? if($id_ot == 0) echo "SELECTED"; ?> >---</option>
            <? 
            $qryOT = "SELECT * FROM cabeceras_ot WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY id_ot";
            $resOT = mysql_query($qryOT,$con);
            while($OT = mysql_fetch_assoc($resOT)){
                if($id_ot==$OT['id_ot']){
                    $selected ="SELECTED";
                }else{
                    $selected="";
                }
                            // echo "<option value='".$OT['id_ot']."' ".$selected.">".$OT['id_ot']."</option>";
                ?>
                <option value='<? echo $OT["id_ot"]; ?>' <? if ($OT["id_ot"] == $_POST['id_ot']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $OT['id_ot'];?></option>
                <?
            }
            ?>
        </select>
    </td>
    <td>
        <label>Tipo Solicitud Compra:</label><br/>
        <select name="tipo_solicitud_compra" style="width:150px;"  class="fo">
            <option value="0" <? if($_POST['tipo_solicitud_compra']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['tipo_solicitud_compra']==1) echo " selected "; ?> >Cotización</option>
            <option value="2" <? if($_POST['tipo_solicitud_compra']==2) echo " selected "; ?> >Compra</option>
            <option value="3" <? if($_POST['tipo_solicitud_compra']==3) echo " selected "; ?> >Solicitud</option>
            <option value="4" <? if($_POST['tipo_solicitud_compra']==4) echo " selected "; ?> >regularizacion</option>
        </select>
    </td> 
    <td>
        <label>Material:</label><br/>
        <select name="material" style="width:120px;"  class="fo">
            <option value="0" <? if($_POST['material']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['material']==1) echo " selected "; ?> >Electrico</option>
            <option value="2" <? if($_POST['material']==2) echo " selected "; ?> >Mecanico</option>
            <option value="3" <? if($_POST['material']==3) echo " selected "; ?> >Filtros</option>
            <option value="4" <? if($_POST['material']==4) echo " selected "; ?> >Lubricantes</option>
            <option value="5" <? if($_POST['material']==5) echo " selected "; ?> >Ferreteria</option>
            <option value="6" <? if($_POST['material']==6) echo " selected "; ?> >Otros</option>
        </select>
    </td>   
</tr>
<tr>
    <td>
        <label>Prioridad:</label><br/>
        <select name="prioridad" style="width:120px;"  class="fo">
            <option value="0" <? if($_POST['prioridad']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['prioridad']==1) echo " selected "; ?> >Urgente</option>
            <option value="2" <? if($_POST['prioridad']==2) echo " selected "; ?> >Alta</option>
            <option value="3" <? if($_POST['prioridad']==3) echo " selected "; ?> >Normal</option>
        </select>
    </td>
    <td>
        <label>Origen:</label><br/>
        <select name="origen" style="width:120px;"  class="fo">
            <option value="0" <? if($_POST['origen']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['origen']==1) echo " selected "; ?> >Pta. Chancado</option>
            <option value="2" <? if($_POST['origen']==2) echo " selected "; ?> >Pta. Hormigón</option>
            <option value="3" <? if($_POST['origen']==3) echo " selected "; ?> >Pta. Asfalto</option>
            <option value="4" <? if($_POST['origen']==4) echo " selected "; ?> >Pta. Seleccionadora</option>
            <option value="5" <? if($_POST['origen']==5) echo " selected "; ?> >Pta. Dosificadora</option>
            <option value="6" <? if($_POST['origen']==6) echo " selected "; ?> >Taller Obra</option>
            <option value="7" <? if($_POST['origen']==7) echo " selected "; ?> >Otro</option>
        </select>
    </td>

<!-- 6) nuevo campo centro de costos en tabla solicitudes_compra Id_cc = FK con la 
    tabla Centros_Costos (mostrar las descripciones) -->

    <td>
        <label>Centro de Costos:</label><br/>
        <select name="centros_costos" style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;'>
            <?php
            $sql2 = "SELECT id_cc, descripcion_cc FROM centros_costos WHERE 1=1 ORDER BY descripcion_cc ";
            $res2 = mysql_query($sql2,$con);
            ?>
            <option value='0' <? if (isset($_POST['centros_costos']) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
            <?php              
            while($row2 = mysql_fetch_assoc($res2)){
                ?>
                <option value='<? echo $row2["id_cc"]; ?>' <? if ($row2['id_cc'] == $_POST['centros_costos']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $row2["descripcion_cc"];?></option>
                <?php
            }
            ?>
        </select>
    </td>
</tr>
<tr>
    <td>

        <label>Activo Destino:</label><br/>
        <select name="cod_producto" style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;' onChange='submit()'>
            <?php
            $sql2 = "SELECT cod_producto, descripcion FROM productos WHERE 1=1 ORDER BY descripcion ";
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
            <option value='999' <? if($_POST['cod_producto'] == 999) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">Otro</option>
        </select>
    </td>
    <?
    if( $_POST["cod_producto"]!=0){
     $disabled = ''; 
 }else{
     $disabled = 'disabled="true"'; 
 }
 ?>
 <td>
    <?
    if($_POST["cod_producto"] != '999'){
        ?>
        <label>Patente Destino:</label><br/>
        <select name="cod_detalle_producto" style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;' <?echo $disabled;?> >
            <?php
            $sql2 = "SELECT cod_detalle_producto, codigo_interno, patente FROM detalles_productos WHERE 1=1 ORDER BY codigo_interno ";
            $res2 = mysql_query($sql2,$con);
            ?>
            <option value='0' <? if (isset($_POST['cod_detalle_producto']) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
            <?php              
            while($row2 = mysql_fetch_assoc($res2)){
                ?>
                <option value='<? echo $row2["cod_detalle_producto"]; ?>' <? if ($row2['cod_detalle_producto'] == $_POST['cod_detalle_producto']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  if(!empty($row2["patente"])){echo $row2["patente"];}else{$row2["codigo_interno"];} ;?></option>
                <?php
            }
            ?>

        </select>
        <input  type="hidden" name="otro_destino" value="" />
        <?
    }else{
        ?>                    
        <label>Otro Destino:</label><br/>
        <input type="text" class="fo" name="otro_destino" value="<?=$_POST["otro_destino"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">         
        <!-- <input  type="hidden" name="cod_detalle_producto" value="0" /> -->
        
        <?
    }
    ?>                    
</td>
<td>

</td>    
</tr>
<tr>
    <td colspan="3">
        <label>Descripcion:</label><label style="color:red">(*)</label>
        <br />
        <textarea  <? if($estado==3){ echo "readonly"; }?> cols="110" rows="2" name="descripcion_solicitud"><?=$_POST["descripcion_solicitud"];?></textarea>
    </td>
</tr>
<tr>
    <td><label>Tipo:</label><label style="color:red">(*)</label> <br/>
        <select name="tipo_solicitud"  <? if($estado==3){ echo "Disabled"; }?>>
            <option value="0" >---</option>
            <option value="1" <? if($_POST["tipo_solicitud"]==1){ echo "selected";} ?> >Nacional</option>
            <option value="2" <? if($_POST["tipo_solicitud"]==2){ echo "selected";} ?> >Internacional</option>
        </select>
    </td>
    <td><label>Concepto:</label><label style="color:red">(*)</label><br/>
        <select name="concepto" <? if($estado==3){ echo "Disabled"; }?> >
            <option value="4" >---</option>
                    <!-- <option value="1" <?/*if($concepto==1){ echo "SELECTED";}*/?>>Compra</option>
                    <option value="2" <?/*if($concepto==2){ echo "SELECTED";}*/?>>Mantenci&oacute;n</option>
                    <option value="3" <?/*if($concepto==3){ echo "SELECTED";}*/?>>Reparaci&oacute;n</option> -->

                    <option value="0" <? if($_POST['concepto']==0) echo " selected "; ?> >---</option>  
                    <option value="1" <? if($_POST['concepto']==1) echo " selected "; ?> >MANTENCION</option>
                    <option value="2" <? if($_POST['concepto']==2) echo " selected "; ?> >REPARACION</option>
                    <option value="3" <? if($_POST['concepto']==3) echo " selected "; ?> >SERVICIOS</option>
                    <option value="4" <? if($_POST['concepto']==4) echo " selected "; ?> >ACTIVOS</option>
                    <option value="5" <? if($_POST['concepto']==5) echo " selected "; ?> >REPUESTOS</option>
                    <option value="6" <? if($_POST['concepto']==6) echo " selected "; ?> >FABRICACION</option>
                    <option value="6" <? if($_POST['concepto']==7) echo " selected "; ?> >RECTIFICACIÓN</option>

                </select>
     </td>
     <td>
                <label>Estado:</label><label style="color:red">(*)</label><br/>
                <?
                if(!isset($_GET['id_solic'])){
                    ?> 
                    <!-- <input class="fo" readonly="readonly" name="estado" value="ABIERTA"> -->
                    <input class="fo" readonly="readonly" name="estado" value="ABIERTA">
                    <?
                }else{

                    if($estado==3){
                        ?>
                        <input class="fo" readonly="readonly" name="estado" value="AUTORIZADA">
                        <?
                    }else{

                        ?>
                        <select name="estado">
                            <option value="6" >---</option>
                            <option value="1" <?if($estado==1){ echo "SELECTED";}?>>Abierta</option>
                            <option value="2" <?if($estado==2){ echo "SELECTED";}?>>En espera de Informaci&oacute;n</option>
                            <option value="3" <?if($estado==3){ echo "SELECTED";}?>>Autorizada</option>
                            <option value="5" <?if($estado==5){ echo "SELECTED";}?>>Cerrada</option>
                            <option value="4" <?if($estado==4){ echo "SELECTED";}?>>Anulada</option>
                        </select>
                        <?
                    }
                } 
                ?>
        </td>
</tr>
<tr>
            <td colspan="3"><label>Observaciones:</label>
                <br/>
                <textarea cols="110" rows="2" name="observaciones"  <? if($estado==3){ echo "readonly"; }?>><?=$_POST["observaciones"];?></textarea>
            </td>
</tr>

        <!--////////////////////// DETALLE /////////////////////////////////////////////////////////////////////-->
</table><br>
<table style="width:900px;" id="detalle-prov" border="1" cellpadding="3" cellspacing="4" >
<tr>
              <td colspan='5' style="text-align:right;">
                    <!-- <button type='submit' name='Agregar' value='Agregar' class='boton'><img src='".$Base."iconos/add.png' border='0'> Agregar</button></td>"; -->
                    <button type="submit" name="Agregar" value='Agregar' ><img src="img/add1.png" width="20" height="20" /></button>
              </td>
</tr>
<tr>
                    
                <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" width="30%"><b>Descripci&oacute;n</b></td>
                <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" width="50px"><b>Unidad</b></td>
                <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" ><b>Cantidad</b></td>
                <!-- <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" ><b>Precio</b></td> -->
                <!-- <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" width="50px"><b>Total</b></td> -->
                <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" ><b>Eliminar</b></td>
             <input  type='hidden' name="cantidad" value='<? echo $_POST['cantidad'];?>'/>
</tr>
                
        <style>
                .detalle
                {
                    text-align:center; 
                    background-color:#EBEBEB;
                    border:1px solid #666;
                }
        </style>
<?

if (empty($_POST['num_prd'])) {
        $_POST['num_prd'] = 1;
        $i = $_POST['num_prd'];
        $_POST["EstadoItem_".$i] = 1;

}

for ($f = 1; $f <= $_POST["num_prd"]; $f++) {
        if (!empty($_POST["Eliminar_".$f])) {
            $_POST["EstadoItem_".$f] = 0;
            $_POST["subtotal"] = $_POST["subtotal"] - $_POST["total_".$i];
        }
}

if (!empty($_POST['Agregar'])) {
       $_POST['num_prd']++;
       $i = $_POST['num_prd'];
       
       $_POST["EstadoItem_".$i] = 1;
}
                    $j=0;
                    $_POST["subtotal"]=0;
                    for ($i = 1; $i <= $_POST["num_prd"]; $i++) 
                    // while($_POST["num_prd"] >= $i)
                    {
                           echo "<br>i : ".$i;
                           echo "<br>num_prd : ".$_POST["num_prd"];
                           echo "<br>EstadoItem_".$i." : ".$_POST["EstadoItem_".$i];
                        

                        if ($_POST["EstadoItem_".$i] == 1) {
                        
                            $j++;
                            echo "<tr>";
                            echo "<td class='detalle'><input type='text' name='descripcion_".$i."' value='".$_POST['descripcion_'.$i]."' ></td>";
                            echo "<td class='detalle'><input type='text' name='unidad_medida_".$i."'      value='".$_POST['unidad_medida_'.$i]."' onchange='calcular".$i."()' style='width:100px; text-align:right'></td>";
                            echo "<td class='detalle' width='80px'><input type='text' name='cantidad_".$i."'    value='".$_POST['cantidad_'.$i]."'   style='width:100px; text-align:center' onFocus='mult_".$i."()' onKeyUp='mult_".$i."()'></td>";
                            // echo "<td class='detalle'><input type='text' name='unidad_".$i."'      value='".$_POST['unidad_'.$i]."' onFocus='mult_".$i."()' onKeyUp='mult_".$i."()'></td>";
                            // echo "<td class='detalle'><input type='text' name='total_".$i."'       value='".$_POST['total_'.$i]."' readonly  style='width:100px; text-align:right' ></td>";
                            echo "<td class='detalle'><button name='Eliminar' value='".$i."' ><img src='img/borrar.png' width='16' height='16' /> </button></td>";
                            
                            echo "<input type='hidden' name='LineaVisible_".$i."' value='".$j."'>";
                            echo "<input type='hidden' name='EstadoItem_".$i."' value='".$_POST["EstadoItem_".$i]."'>";
                            echo "</tr>";
                                
                        }
                        else
                        {
                            echo "<input type='hidden' name='EstadoItem_".$i."' value='0'>";
                            echo "<input type='hidden' name='total_".$i."'  value='0'>";
                            echo "<input type='hidden' name='unidad_".$i."'  value='0'>";
                            echo "<input type='hidden' name='cantiddad_".$i."'  value='0'>";
                        }
                        
                        $_POST["subtotal"] += $_POST["total_".$i];                       
                        
                    }

                    $_POST["valor_neto"] = $_POST["subtotal"] - $_POST["descuento"];
                    $_POST["iva"] = ($_POST["valor_neto"] * 0.19);
                    $_POST["total_doc"] = ($_POST["valor_neto"] + $_POST["iva"]);



?>
                    <input type='hidden' name='num_prd' value='<?=$_POST["num_prd"];?>'>

                <!-- <tr>
                    <td  colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0); font-weight:bold;"  width="147px">SubTotal:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);" width="137px;"><input type="text" name="subtotal" value='<?/* echo $_POST['subtotal'];*/?>' style="width:80px; text-align:right" readonly /></td>                   
                    <td ></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Descuento:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);" ><input type="text" onFocus="total_neto()" onKeyUp="total_neto()" name="descuento" value='<?/* echo $_POST['descuento'];*/?>' style="width:80px; text-align:right"  /></td>                    
                    <td></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Neto:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="valor_neto" value='<?/* echo $_POST['valor_neto'];*/?>' style="width:80px; text-align:right"  readonly /></td>                 
                    <td></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">IVA:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="iva" value='<?/* echo $_POST['iva'];*/?>' style="width:80px; text-align:right"  readonly /></td>                  
                    <td></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Total:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="total_doc" value='<?/* echo $_POST['total_doc'];*/?>' style="width:80px; text-align:right"  readonly /></td>                   
                    <td></td>
                </tr> -->
                

                    
       
</table>

<br>
<table style="width:900px;" id="detalle-prov" border="1" cellpadding="3" cellspacing="4" >

<?
        // CUANDO ESTAMOS EN EDITAR

        if($nueva==1 || (isset($_GET['id_solic'])&& $_GET['id_solic']!=null)){
?>
    
                <tr>
                    <td>Archivo</td>
                    <td>ver</td>
                    <td>Comparativo General</td>
                </tr>

                
<? 

                            $qr_a ="SELECT * FROM archivos WHERE id_solicitud='".$id."'";
                            $r_a = mysql_query($qr_a,$con);
                            if(mysql_num_rows($r_a)!=NULL){
                                $i=1;
                                while($r = mysql_fetch_assoc($r_a)){
?>
                                    <tr>    
                                        <td><?=$r['nombre_archivo'];?></td>
                                        <td><a href="<?=$r['ruta_archivo'];?>" target="_blank">ver</a></td>
                                        <td><input type="checkbox" value="<?=$r['id_archivo'];?>" onchange="archivo_seleccionado(this.value);" id="archivo_aprovado_<?=$r['id_archivo'];?>" name="archivo_aprovado" <?if($r['estado_archivo']==1){ echo "checked";}?>  <? if($estado==3){ echo "Disabled"; }?>></td>    
                                    </tr>
<?
                                    $i++;
                                }
                            }else{
?>
                                <tr>
                                    <td colspan="3" style="text-align: center;">No existen archivos para ser desplegados</td>   
                                </tr>
<?
                            }

?>
        <tr>
            <td colspan='3'>
               <div align='center'>
                        <!-- <form   action="?cat=2&sec=5&action=2&user=<? echo $_GET['user']; ?>"  method="post" enctype="multipart/form-data" style=" text-align:center; background-color:#EEE"> -->
                        <label style='font-family:tahoma;font-size:12px;font-weight:normal;'>Agregar Archivos</label><br><input name="archivo" type="file" size="35" style=" background-color:#fff" />
                        <input name="enviar" type="submit" value="Subir Archivo"  /><br>

                        <input name="action" type="hidden" value="upload" /> 
                </div>
            </td>
        </tr>
<?
            $sql_com = "SELECT * FROM archivos WHERE id_solicitud='".$id."' AND estado_archivo=1";
            $res_com = mysql_query($sql_com,$con);

            if(mysql_num_rows($res_com)==NULL){
                    $disabled = "disabled='true'";
            }
?>
        <tr>
            <td  style="height: 100px; border: 1px solid; text-align:center" >
                        <b>VºBº Solicitud Compra </b><br/><input type="checkbox" name="vb_solic_compras" <? if($_POST['vb_solic_compras']==1){ echo "checked";}?> value="1" <?=$disabled?> >
            </td>
        </tr>
             
<?
        }
?>
        <tr>
            <td style="text-align: right;" colspan="3">
                <!-- <input type="submit" value="Grabar"> -->
                <div style="width:100%; height:auto; text-align:right;">
                    <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                    <?
                            if(empty($row['id_ot']))
                            {
                            echo  " value='guardar' >Guardar";
                            }
                            else
                            {
                                echo " value='editar' >Actualizar";
                            }
                            ?>
                    </button>
                </div>
                <input  type="hidden" name="primera" value="1"/>
                <input  type="hidden" name="id_solicitud_compra" value="<?=$row["id_solicitud_compra"]?>"/>
            </td>
        </tr>
    </table>
<?
multiplicador_nitrh($_POST["num_prd"]);
?>

</form>

<?php 



function multiplicador_nitrh($num, $dec = 0) {
    $ceros = "";
    for ($d = 1; $d <= $dec; $d++) $ceros .= "0";
    $pot = "1".$ceros;
    $pot = round($pot, 0);

    echo "\n\n <script language='javascript' type='text/javascript'> \n\n";
   
    echo "function total_neto(input) { \n";
    echo "var sub = 0; \n";
    echo "var desc = 0; \n";
    echo "\n desc = eval(document.forms[0].descuento.value); \n";
    echo "if (isNaN(desc)) document.forms[0].descuento.value = Math.round(0); \n";

    for ($i = 1; $i <= $num; $i++) {
        echo "sum = eval(document.forms[0].total_".$i.".value); \n";
        echo "if (isNaN(sum)) sum = 0; \n";
        echo "sub = eval(eval(sub) + eval(sum)); \n";
    }
    echo "\n net = eval(eval(sub) - eval(desc)); \n";
    echo "\n document.forms[0].subtotal.value = Math.round(sub*".$pot.")/".$pot."; \n";
    echo "if (desc > sub) desc = 0; \n";
    
    echo "\n document.forms[0].valor_neto.value = Math.round(net*".$pot.")/".$pot."; \n";

    echo "\n document.forms[0].iva.value = Math.round(net*0.19*".$pot.")/".$pot."; \n";
    echo "\n document.forms[0].total_doc.value = Math.round(net*1.19*".$pot.")/".$pot."; \n";
    // echo "\n document.forms[0].ret.value = Math.round(neto*0.10*".$pot.")/".$pot."; \n";
    // echo "\n document.forms[0].hon.value = Math.round(neto*0.90*".$pot.")/".$pot."; \n";
    // echo "\n document.forms[0].tot2.value = Math.round(neto*".$pot.")/".$pot."; \n";
    echo "} \n\n";
    

    /*echo "function desc_sub(input) { \n";
    echo "var desc = 0; \n";
    echo "var sub = 0; \n";
    echo "\n desc = eval(document.forms[0].descuento.value); \n";
    echo "if (isNaN(desc)) desc = 0; \n";
    for ($i = 1; $i <= $num; $i++) {
        echo "sum = eval(document.forms[0].total_".$i.".value); \n";
        echo "if (isNaN(sum)) sum = 0; \n";
        echo "sub = eval(eval(sub) + eval(sum)); \n";
    }
    echo "\n net = eval(eval(sub) - eval(desc)); \n";
    // echo "\n alert(desc); \n";

    echo "\n document.forms[0].valor_neto.value = Math.round(net*".$pot.")/".$pot."; \n";
    echo "} \n\n";
*/

    for ($i = 1; $i <= $num; $i++) {
        echo "function mult_".$i."(input) { \n";
        // echo "  canm = document.forms[0].cant_max_".$i.".value*10000; \n";
        echo "  cant = document.forms[0].cantidad_".$i.".value*10000; \n";
        echo "  unit = document.forms[0].unidad_".$i.".value*100; \n";
    // echo "\n alert(unit)\n";
       /* echo "  if (eval(cant) > eval(canm)) { \n";
        echo "      document.forms[0].cantidad_".$i.".value = ''; \n";
        echo "      cant = 0; \n";
        echo "      alert('Valor excede máximo permitido.'); \n";
        echo "  } \n";*/
        echo "  if (isNaN(cant)) document.forms[0].cantidad_".$i.".value = ''; \n";
        echo "  if (isNaN(unit)) document.forms[0].unidad_".$i.".value = ''; \n";
        echo "  if (cant < 0) document.forms[0].cantidad_".$i.".value = ''; \n";
        echo "  if (unit < 0) document.forms[0].unidad_".$i.".value = ''; \n";
        echo "  subt = (cant*unit)/1000000; \n";
        echo "  document.forms[0].total_".$i.".value = Math.round(subt*".$pot.")/".$pot."; \n";
        echo "  total_neto(); \n";
        echo "} \n \n";
    };
    echo "\n</script> \n";
}

 /*$k=1;
 while(($_POST['cantidad']>=$k))
 {
 echo "<script>
 ";
 echo "function calcular".$k."()
 ";
 echo "{
 ";
 echo "document.f1.total".$k.".value=document.f1.cantidad".$k.".value*document.f1.precio".$k.".value;
 ";
 echo " subtotal=0;
 ";
echo "  total=0;
";
echo "  iva=0;
";
for($i=1;$i<=$_POST['cantidad'];$i++)
{
    //Valida si esta visible
    echo " if(document.f1.visible".$i.".value!=1)
    ";
    echo " {
    ";
        //Si esta visible y esta vacio le pone 0
        echo " if(document.f1.total".$i.".value=='')
        ";
        echo " {
        ";
        echo " document.f1.total".$i.".value=0
        ";
        echo " }
        ";
    echo " subtotal=subtotal+parseInt(document.f1.total".$i.".value);
    ";
    echo " }
    ";
}
echo " (document.f1.subtotal.value=subtotal)
";
echo "  document.f1.valor_neto.value=document.f1.subtotal.value-document.f1.descuento.value;
";
echo "  iva=(document.f1.valor_neto.value)*0.19;
";
echo "  iva=iva.toFixed(0);
";
echo "  document.f1.iva.value=iva;
";
echo "  total=(document.f1.valor_neto.value)*1.19;
";
echo "  total=total.toFixed(0);
";
echo "      document.f1.total_doc.value=total;
";

echo "}
 ";
echo "</script>
 ";
 $k++;
 }*/


/*}*/ 

?>