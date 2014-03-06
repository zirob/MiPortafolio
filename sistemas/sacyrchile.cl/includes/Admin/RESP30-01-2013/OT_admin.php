<?php
$action=1;
$msg="";
$error="";
$id_ot="";
$fecha="";
$horas="";
$estado_trabajo="";
$descripcion="";
$observaciones_ot="";
$descripcion_ot="";
$centro_costo = "";
$tipo_trabajo="";
$concepto="";
$cod_producto="";
$observaciones="";

if(isset($_GET['action'])){    
    if($_GET['action']==1){
        
            $sql_ins="INSERT INTO cabeceras_ot (centro_costos,rut_empresa ,cod_producto,cod_detalle_producto ,tipo_trabajo,concepto_trabajo,descripcion_ot,estado_ot,observaciones,usuario_ingreso,fecha_ingreso)
                    VALUES ('".$_POST['centro_costo']."','".$_SESSION['empresa']."', ".$_POST['cod_producto'].", '".$_POST['detalle_prod']."','".$_POST['tipo_trabajo']."','".$_POST['concepto']."', '".$_POST['descripcion_ot']."','".$_POST['estado']."','".$_POST['observaciones_ot']."' ,'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."')";

            
            
         if($_POST['tipo_trabajo']!="" && !empty($_POST['tipo_trabajo'])){
            if($_POST['estado']!="" && !empty($_POST['estado'])){
                if($_POST['cod_producto']!="" && !empty($_POST['cod_producto'])){
                    if($_POST['detalle_prod']!="" && !empty($_POST['detalle_prod'])){
                               if(mysql_query($sql_ins,$con)){
                                    $msg ="Se Agrego correctamente La Orden de Trabajo";
                               }else{
                                     $error="Ha ocurrido un error al grabar datos intente mas tarde";
                               }
                    }else{
                         $error="Debe Seleccionar un Detalle de Producto(*)";
                    }    
                }else{
                    $error="Debe Seleccionar un Producto(*)";
                } 
            }else{
                $error="Debe Seleccionar El estado de la Orden de trabajo(*)";
            }
         }else{
            $error="Debe ingresar Un Tipo de Trabajo (*)";
         }
         
    
            
            
             if( mysql_query($sql_ins,$con)){
                 $rs = mysql_query("SELECT @@identity AS id");
                 if ($row = mysql_fetch_row($rs)) {
                    $id = trim($row[0]);
                    $operador = $_POST['operador'];
                    $fecha=$_POST['fecha'];
                    $horas=$_POST['horas'];
                    $estado_trabajo=$_POST['estado_trabajo'];
                    $descripcion=$_POST['det_descripcion'];
                    $observaciones=$_POST['observaciones'];
                    $centro_costo = $_POST['centro_costo'];
                    
                    
                        if($fecha!="" && !empty($fecha)){
                           
                                if($estado_trabajo!="" && !empty($estado_trabajo)){
                                     if($descripcion!="" && !empty($descripcion)){
                                            $num = count($operador);
                                            for($i=0;$i<$num;$i++){
                                                $sql = "INSERT INTO detalle_ot (id_ot,cod_producto,cod_detalle_producto,rut_empresa,descripcion_trabajo,fecha_trabajo,operador_a_cargo,horas_hombre,estado_trabajo,observaciones,usuario_actualiza,fecha_actualiza)
                                                        VALUES ('".$id."', '".$_POST['cod_producto']."', '".$_POST['detalle_prod']."','".$_SESSION['empresa']."','".$descripcion[$i]."','".$fecha[$i]."','".$operador[$i]."','".$horas[$i]."','".$estado_trabajo[$i]."','".$observaciones[$i]."','".$_SESSION['user']."','".date('Y-m-d H:i:s')."')";
                                                if(mysql_query($sql,$con)){
                                                    $msg ="Se ha Agregado correctamente la Orden de Trabajo";
                                                }else{
                                                    $error="Debe ingresar los datos solicitados (*)";
                                                }

                                            }
                                            
                                      }else{
                                         $error="Debe Ingresar Descripción para el trabajo realizado";
                                      }       
                                }else{
                                     $error="Debe Seleccionar un Estado del Trabajo Realizado(*)";
                                }    
                          
                        }else{
                            $error="Debe Ingresar Fecha del trabajo Realizado(*)";
                        }
                    
                 }       
             }      
                    
            
    }else{
        if(isset($_GET['id_cc'])){

        }else{
            $action="1&new=1";
        }
    }

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        $sql_update="UPDATE cabecera_ot SET descripcion_ot='".$_POST['descripcion']."', observaciones='".$_POST['observaciones_ot']."' WHERE id_ot='".$_GET['id_ot']."'";
        $rev = "SELECT * FROM cabecera_ot WHERE id_ot='".$_POST['id_ot']."'";
        $res_rev =mysql_query($rev,$con);
        
        
        
            if($_POST['codigo']!="" && $_POST['descripcion']!=""){
                if(mysql_query($sql_update,$con)){
                    
                    
                   
                    $id = $_GET['id_ot'];
                    $operador = $_POST['operador'];
                    $fecha=$_POST['fecha'];
                    $horas=$_POST['horas'];
                    $estado_trabajo=$_POST['estado_trabajo'];
                    $descripcion=$_POST['det_descripcion'];
                    $observaciones=$_POST['observaciones'];
                    
                    mysql_query("DELETE FROM detalle_ot WHERE id_ot=".$id,$con);
                    
                    $num = count($operador);
                        for($i=0;$i<$num;$i++){
                            $sql = "INSERT INTO detalle_ot values(id_ot,cod_producto,cod_detalle_producto,rut_empresa,descripcion_trabajo,fecha_trabajo,operador_a_cargo,horas_hombre,estado_trabajo,observaciones,usuario_actualiza,fecha_actualiza)
                                    VALUES ('".$id."', ".$_POST['cod_producto'].", '".$_POST['detalle_prod']."','".$_SESSION['empresa']."','".$descripcion[$i]."','".$fecha[$i]."','".$operador[$i]."','".$horas."','".$estado_trabajo."','".$observaciones."','".$_SESSION['user']."','".date('Y-m-d H:i:s')."')";
                            if(mysql_query($sql,$con)){
                                $msg ="Se ha Agregado correctamente la Orden de Trabajo";
                            }else{
                                $error="Debe ingresar los datos solicitados (*)";
                            }

                        }
                   
                    
                $msg ="Se han actualizado correctamente los datos";
                }else{
                    
                }
            }else{
                $error="Debe Ingresar los datos ingresados (*)";
            }
    }


    if($_GET['action']==2 && isset($_GET['id_ot'])){
        $qry_prv = "SELECT * FROM cabeceras_ot WHERE id_ot =".$_GET['id_ot'];

        $sel_prv = mysql_query($qry_prv,$con);

        $row = mysql_fetch_assoc($sel_prv);
        
        
        $estado_trabajo=$row['estado_ot'];
        $descripcion_ot=$row['descripcion_ot'];
        $observaciones_ot=$row['observaciones'];
        $centro_costo = $row['centro_costos'];
        $tipo_trabajo = $row['tipo_trabajo'];
        $concepto= $row['concepto_trabajo'];
        $cod_producto = $row['cod_producto'];
        $cod_detalle_producto = $row['cod_detalle_producto'];
        
        
        $action="2&new=2&id_cc=".$_GET['id_cc'];
    }
}


		  

?>
<?
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
<form action="?cat=2&sec=18&action=<?=$action; ?>" method="POST">
    <table style="width:900px; background-color: #BBB;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">  </td>
        <td id="list_link" style="text-align:right;" ><a href="?cat=2&sec=17"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al Listado de Ordenes de Trabajo"></</a></td></tr>
    <tr>
        <td ><label>Tipo Trabajo:(*)</label><input type="text" name="tipo_trabajo" size="20" value="<?=$tipo_trabajo;?>"></td>
        <td ><label>Concepto:</label>
            <select name="concepto">
                <option> --- </option>
                <option value="4" <?if($concepto==4){ echo "SELECTED";}?>> Certificacion de Equipo </option>
                <option value="2" <?if($concepto==2){ echo "SELECTED";}?>> Mantencion </option>
                <option value="3" <?if($concepto==3){ echo "SELECTED";}?>> Reparacion </option>
            </select>
        </td>
        <td ><label>Estado:(*)</label>
            <select name="estado">
                <option> --- </option>
                <option value="1" <?if($estado_trabajo==1){ echo "SELECTED";}?>> Abierta </option>
                <option value="2" <?if($estado_trabajo==2){ echo "SELECTED";}?>> En Proceso </option>
                <option value="3" <?if($estado_trabajo==3){ echo "SELECTED";}?>> Finalizada </option>
                <option value="4" <?if($estado_trabajo==4){ echo "SELECTED";}?>> Cerrada </option>
            </select>    
        </td>
    </tr>
    <tr>
        <td colspan="2" id="detalle_prod">
            <label>Producto:(*)</label><select name="cod_producto"  onchange="busca_prod_detalle(this.value,'<?=$_SESSION['empresa'];?>')">
                <option value=""> --- </option>
                <? 
                    $s = "SELECT * FROM productos p INNER JOIN detalles_productos d ON p.cod_producto = d.cod_producto WHERE d.patente <> '' and p.rut_empresa = '".$_SESSION['empresa']."'  ORDER BY p.descripcion";
                    $res = mysql_query($s,$con);
                    if(mysql_num_rows($res)!=NULL){
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                <option value="<?=$r['cod_producto'];?>" <?if($cod_producto==$r['cod_producto']){ echo "SELECTED";}?>><?=$r['descripcion'];?></option>
                <?
                        }
                    }
                ?>
            </select>
            
            <? if(isset($cod_producto) && $cod_producto!="" && $cod_producto>0){
                
                $sql = "SELECT patente, cod_detalle_producto FROM detalles_productos WHERE rut_empresa ='".$_SESSION['empresa']."' and patente IS NOT NULL and patente <> '' and cod_producto=".$cod_producto;
                $rew = mysql_query($sql,$con);  
                
                $html="";
                $html.="&nbsp;&nbsp;&nbsp;<div id='producto_detalle'><label>Detalle:(*)</label><select name='detalle_prod'>";
                $html.="<option> --- </option>";


                while($r = mysql_fetch_assoc($rew)){
                    if($r['cod_detalle_producto']==$cod_detalle_producto){
                        $selected="SELECTED";
                    }else{
                        $selected="";
                    }
                    $html.="<option value='".$r['cod_detalle_producto']."' ".$selected.">".$r['patente']."</option>";
                }

                $html.="</select></div>";
                echo $html;
            }?>
        </td>
        <td>
            <label>Centros de Costo:(*)</label>
            <select name="centro_costo">
                <option value="0">---</option>
                <? 
                    $s = "SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                    $res = mysql_query($s,$con);
                    if(mysql_num_rows($res)!=NULL){
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                <option value="<?=$r['Id_cc'];?>" <? if($centro_costo==$r['Id_cc']){echo "SELECTED";}?>><?=$r['descripcion_cc'];?></option>
                <?      }
                    }       ?>
            </select>
        </td>
      
    </tr>
    <tr>
        <td colspan="3"><label>Descripci&oacute;n:</label><br /><textarea cols="110" rows="2" name="descripcion_ot"><?=$descripcion_ot;?></textarea></td>
    </tr>
    <tr>
        <td colspan="3"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones_ot"><?=$observaciones_ot;?></textarea></td>
    </tr>
    <tr><td id="titulo_tabla" colspan="3"  style="text-align:center;"> ..:: Detalle Orden de Trabajo ::..
        <a href="javascript:nuevo_detOT(1)" id="agregar" style="text-align: right; float:right;"><img alt="Nueva Orden de Trabajo" src="img/add.png" border="0"></a></td>
    </tr>
    <tr >
        <td colspan="3" id="detalles-ot">
        <?
            if($id_ot!="" && !empty($id_ot)){
            $sql="SELECT * FROM detalle_ot WHERE id_ot=".$id_ot;
            $rs = mysql_query($sql,$con);

            if(mysql_num_rows($rs)!=NULL){
                while($rww = mysql_fetch_assoc($rs)){
           ?> 
                    <table id="detalle_ot_1" style="width:900px;" id="detalle-cc" border="1" cellpadding="3" cellspacing="2">
              <tr>
                <td colspan="3"><label>Operador:(*)</label>
                    <select name="operador[]">
                        <? 
                            $s = "SELECT * FROM usuarios WHERE rut_empresa = '".$_SESSION['empresa']."' and tipo_usuario=9 ORDER BY nombre";
                            $r = mysql_query($s,$con);
                            if(mysql_num_rows($r)!= null){
                                while($rw = mysql_fetch_assoc($r)){
                                    if($rww['operador']==$rw['usuario']){
                                        $selected="SELECTED";
                                    }else{
                                        $selected="";
                                    }
                                    echo "<option value='".$rw['usuario']."' ".$selected.">".$rw['nombre']."</option>";
                                }
                            }else{
                                echo "<option value='0'> ---- </option>";
                            }
                       ?>
                        
                    </select></td>
            </tr>
            <tr>
                <td colspan="3"><label>Descripcion:(*)</label><br /><textarea cols="110" rows="2" name="det_descripcion[]"><?=$rww['descripcion_trabajo'];?></textarea></td>
            </tr>
            <tr>
                <td ><label>Fecha:(*)</label><input type="text" name="fecha[]" size="20" value="<?=date('d-m-Y',$rww['fecha_trabajo']);?>"></td>
                <td ><label>Horas:(*)</label><input type="text" class="nume" name="horas[]" size="20" value="<?=$rww['horas_hombre'];?>"></td>
                <td ><label>Estado del Trabajo:(*)</label><input type="text" name="estado_trabajo[]" size="20" value="<?=$rww['estado_trabajo'];?>"></td>
            </tr>
            <tr>
                <td colspan="3"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones[]"><?=$rww['observaciones'];?></textarea></td>
            </tr>
            
        </table>
                    
           <?         
                }
            }
            }else{
        ?>
         <table id="detalle_ot_1" style="width:900px;" id="detalle-cc" border="1" cellpadding="3" cellspacing="2">
           
             <tr>
                <td colspan="3"><label>Operador:</label>
                    <select name="operador[]">
                        <? 
                            $s = "SELECT * FROM usuarios WHERE rut_empresa = '".$_SESSION['empresa']."' and tipo_usuario=9 ORDER BY nombre";
                            $r = mysql_query($s,$con);
                            if(mysql_num_rows($r)!= null){
                                while($rw = mysql_fetch_assoc($r)){
                                    echo "<option value='".$rw['usuario']."'>".$rw['nombre']."</option>";
                                }
                            }else{
                                echo "<option value='0'> ---- </option>";
                            }
                       ?>
                        
                    </select></td>
            </tr>
            <tr>
                <td colspan="3"><label>Descripcion:(*)</label><br /><textarea cols="110" rows="2" name="det_descripcion[]"><?=$descripcion;?></textarea></td>
            </tr>
            <tr>
                <td ><label>Fecha:(DD-MM-YYYY)(*)</label><input type="text" name="fecha[]" size="20" value="<?=$fecha;?>"></td>
                <td ><label>Horas:</label><input type="text" class="nume" name="horas[]" size="20" value="<?=$horas;?>"></td>
                <td ><label>Estado del Trabajo:(*)</label><input type="text" name="estado_trabajo[]" size="20" value="<?=$estado_trabajo;?>"></td>
            </tr>
            <tr>
                <td colspan="3"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones[]"><?=$observaciones;?></textarea></td>
            </tr>
            
        </table>
        <? } ?>
        </td>    
    </tr>
    <tr>
                <td colspan="3" style="text-align: right;"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>
