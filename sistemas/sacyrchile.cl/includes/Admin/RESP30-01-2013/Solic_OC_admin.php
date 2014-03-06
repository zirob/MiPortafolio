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
if(isset($_GET['action'])){    
    if($_GET['action']==1 && !isset($_GET['user'])){
        
        
        
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
    }

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
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

    
}else{
    $action="1";
}



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
?>
<form action="?cat=2&sec=11&action=<?=$action; ?>" method="POST">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
      <td id="titulo_tabla" style="text-align:center;" colspan="3"> <a href="?cat=2&sec=10"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Solicitudes de Compra"></</a></td></tr>
    <tr>
        <td colspan="3"><label>ID OT</label><br />
            <select name="id_ot"  <? if($estado==3){ echo "Disabled"; }?>>
                <option value="0" <?if($id_ot==0){ echo "SELECTED";}?> >---</option>
                <? 
                    $qryOT = "SELECT * FROM cabeceras_ot WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY id_ot";
                    $resOT = mysql_query($qryOT,$con);
                    while($OT = mysql_fetch_assoc($resOT)){
                        if($id_ot==$OT['id_ot']){ $selected ="SELECTED";}else{$selected="";}
                        echo "<option value='".$OT['id_ot']."' ".$selected.">".$OT['id_ot']."</option>";
                    }
               ?>
            </select>
        
        
        </td>
    </tr>
    <tr>
        <td colspan="3"><label>Descripcion:(*)</label><br /><textarea  <? if($estado==3){ echo "readonly"; }?> cols="110" rows="2" name="descripcion_solicitud"><?=$descripcion;?></textarea></td>
    </tr>
    <tr>
        <td><label>Tipo:(*)</label> <br/>
            <select name="tipo_solicitud"  <? if($estado==3){ echo "Disabled"; }?>>
                <option value="3" >---</option>
                <option value="1" <?if($tipo==1){ echo "SELECTED";}?>>Nacional</option>
                <option value="0" <?if($tipo==0){ echo "SELECTED";}?>>Internacional</option>
            </select>
        </td>
        <td><label>Concepto:(*)</label><br/>
            <select name="concepto" <? if($estado==3){ echo "Disabled"; }?> >
                 <option value="4" >---</option>
                <option value="1" <?if($concepto==1){ echo "SELECTED";}?>>Compra</option>
                <option value="2" <?if($concepto==2){ echo "SELECTED";}?>>Mantenci&oacute;n</option>
                <option value="3" <?if($concepto==3){ echo "SELECTED";}?>>Reparaci&oacute;n</option>
            </select>
        </td>
        <td>
            <label>Estado:(*)</label><br/>
            <?if(!isset($_GET['id_solic'])){?> <input class="fo" readonly="readonly" name="estado" value="ABIERTA"><?}else{
                
                    if($estado==3){?> <input class="fo" readonly="readonly" name="estado" value="AUTORIZADA"><?}else{
                        
                ?>
                <select name="estado">
                <option value="6" >---</option>
                <option value="1" <?if($estado==1){ echo "SELECTED";}?>>Abierta</option>
                <option value="2" <?if($estado==2){ echo "SELECTED";}?>>En espera de Informaci&oacute;n</option>
                <option value="3" <?if($estado==3){ echo "SELECTED";}?>>Autorizada</option>
                <option value="5" <?if($estado==5){ echo "SELECTED";}?>>Cerrada</option>
                <option value="4" <?if($estado==4){ echo "SELECTED";}?>>Anulada</option>
            </select>
            <?} } ?>
        
        </td>
    </tr>
    <tr>
        <td colspan="3"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones"  <? if($estado==3){ echo "readonly"; }?>><?=$observaciones;?></textarea></td>
    </tr>
     <? if($nueva==1 || (isset($_GET['id_solic'])&& $_GET['id_solic']!=null)){?>
    <tr>
        <td colspan="3">
            <div id="archivos_cargados">
                    <table border="1">
                        <tr><td>Archivo</td><td width="50px">ver</td><td>Aprobado</td></tr>
                        <? 
                            $qr_a ="SELECT * FROM archivos WHERE id_solicitud='".$id."'";
                            $r_a = mysql_query($qr_a,$con);
                            if(mysql_num_rows($r_a)!=NULL){
                                $i=1;
                                while($r = mysql_fetch_assoc($r_a)){
                                    ?>
                                 <tr><td><?=$r['nombre_archivo'];?></td>
                                     <td><a href="<?=$r['ruta_archivo']."".$r['nombre_archivo'].".".$r['extension_archivo'];?>" target="_blank">ver</a></td>
                                     <td><input type="checkbox" value="<?=$r['id_archivo'];?>" onchange="archivo_seleccionado(this.value);" id="archivo_aprovado_<?=$r['id_archivo'];?>" name="archivo_aprovado" <?if($r['estado_archivo']==1){ echo "checked";}?>  <? if($estado==3){ echo "Disabled"; }?>></td>    
                                 </tr>
                            <?
                                $i++;
                                }
                            }else{
                                ?>
                        <td colspan="3" style="text-align: center;">No existen archivos para ser desplegados</td>   
                        <?
                            }

                        ?>
                    </table>
                </div>

            <div id="archivos"> <label>(*)Formato: jpg,gif,png,pdf,doc,docx,xls,xlsx,txt</label><br />
                <a id="pickfiles" href="#"><img src="img/document_add.png" width="30px" class="toolTIP" title="Agregar Archivos"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a id="uploadfiles" href="#"><img src="img/disk_blue_ok.png" width="30px"  class="toolTIP" title="Subir Archivos"></a> 
                    <div id="filelist"></div> <br /> 

                </div>
                
        </td>
    </tr>
    <?}?>
    <tr>
        <td style="text-align: right;" colspan="3"><input type="submit" value="Grabar"></td>
    </tr>
    
</table>
</form>
