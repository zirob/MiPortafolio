<script>
   
</script>    
<?php
$msg="";
$action=1;

$rut_proveedor ="";
    $razon_social="";
    $domicilio="";
    $comuna="";
    $ciudad="";
    $telefono_1="";
    $telefono_2="";
    $fax="";
    $celular="";
    $mail="";
    $observaciones="";
    $contact_contacto= "";
    $contact_telefono= "";
    $contact_email= "";
    $sucursal_domicilio= "";
    $sucursal_comuna= "";
    $sucursal_ciudad= "";
    $sucursal_telefono= "";
    $sucursal_fax= "";
    $sucursal_contacto= "";
    $sucursal_fono= "";
    $sucursal_email= "";
    
if(isset($_GET['action'])){    
    if($_GET['action']==1 && !isset($_GET['id_prov'])){
        $sql_ins="INSERT INTO proveedores (rut_proveedor,rut_empresa ,razon_social ,domicilio ,comuna ,ciudad ,telefono_1 ,telefono_2 ,fax,
                celular ,mail  ,contacto,fono_contacto,email_contacto,domicilio_sucursal,comuna_sucursal,ciudad_sucursal,
                telefono_sucursal,fax_sucursal,contacto_sucursal,fono_contacto_sucursal,email_contacto_sucursal,observaciones,fecha_ingreso ,usuario_ingreso)
                VALUES ('".str_replace(".","",$_POST['rut'])."','".$_SESSION['empresa']."', '".$_POST['razon_social']."', '".$_POST['domicilio']."', '".$_POST['comuna']."', '".$_POST['ciudad']."', '".$_POST['telefono_1']."', '".$_POST['telefono_2']."', '".$_POST['fax']."', '".$_POST['celular']."', 
                    '".$_POST['email']."','".$_POST['contact_contacto']."', '".$_POST['contact_telefono']."', '".$_POST['contact_email']."', '".$_POST['sucursal_domicilio']."', '".$_POST['sucursal_comuna']."',
                        '".$_POST['sucursal_ciudad']."', '".$_POST['sucursal_telefono']."', '".$_POST['sucursal_fax']."', '".$_POST['sucursal_contacto']."', '".$_POST['sucursal_fono']."', '".$_POST['sucursal_email']."','".$_POST['observaciones']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['user']."')";

        
        if($_POST['rut']!="" && !empty($_POST['rut'])){
            if($_POST['razon_social']!="" && !empty($_POST['razon_social'])){
                if($_POST['domicilio']!="" && !empty($_POST['domicilio'])){
                    if($_POST['comuna']!="" && !empty($_POST['comuna'])){
                            if($_POST['ciudad']!="" && !empty($_POST['ciudad'])){
                                   if($_POST['telefono_1']!="" && !empty($_POST['telefono_1'])){
                                           if(mysql_query($sql_ins,$con)){
                                               $msg ="Se ha Agregado correctamente el proveedor";
                                           }else{
                                               $error="Ha ocurrido un error al grabar datos intente mas tarde";
                                           }
                                    }else{
                                        $error="Debe ingresar Telefono_1 del proveedor (*)";
                                    } 
                            }else{
                                $error="Debe ingresar Ciudad del proveedor (*)";
                            }
                    }else{
                        $error="Debe ingresar Comuna del proveedor (*)";
                    }
                }else{
                    $error="Debe ingresar Domicilio del proveedor (*)";
                }
            }else{
                $error="Debe ingresar Razon Social del proveedor (*)";
            }
        }else{
            $error="Debe ingresar Rut del proveedor (*)";
        }
        
     
    }else{
        if(isset($_GET['id_prov'])){

        }else{
            $action="1&new=1";
        }
    }

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        $sql_update="UPDATE proveedores SET rut_proveedor='".$_POST['rut']."' ,razon_social='".$_POST['razon_social']."' ,domicilio='".$_POST['domicilio']."' ,
            comuna='".$_POST['comuna']."' ,ciudad='".$_POST['ciudad']."' ,telefono_1='".$_POST['telefono_1']."' ,telefono_2='".$_POST['telefono_2']."' ,fax='".$_POST['fax']."',
            celular='".$_POST['celular']."' ,mail='".$_POST['email']."',observaciones='".$_POST['observaciones']."',contacto='".$_POST['contact_contacto']."',fono_contacto='".$_POST['contact_telefono']."',
            email_contacto='".$_POST['contact_email']."',domicilio_sucursal='".$_POST['sucursal_domicilio']."',comuna_sucursal='".$_POST['sucursal_comuna']."',
            ciudad_sucursal='".$_POST['sucursal_ciudad']."',telefono_sucursal='".$_POST['sucursal_telefono']."',fax_sucursal='".$_POST['sucursal_fax']."',
            contacto_sucursal='".$_POST['sucursal_contacto']."',fono_contacto_sucursal='".$_POST['sucursal_fono']."',email_contacto_sucursal='".$_POST['sucursal_email']."'
            WHERE rut_proveedor = '".$_GET['id_prov']."'";

        
        
        if($_POST['rut']!="" && !empty($_POST['rut'])){
            if($_POST['razon_social']!="" && !empty($_POST['razon_social'])){
                if($_POST['domicilio']!="" && !empty($_POST['domicilio'])){
                    if($_POST['comuna']!="" && !empty($_POST['comuna'])){
                            if($_POST['ciudad']!="" && !empty($_POST['ciudad'])){
                                   if($_POST['telefono_1']!="" && !empty($_POST['telefono_1'])){
                                           if(mysql_query($sql_update,$con)){
                                               $msg ="Se Actualizo correctamente el proveedor";
                                           }else{
                                               $error="Ha ocurrido un error al grabar datos intente mas tarde";
                                           }
                                    }else{
                                        $error="Debe ingresar Telefono_1 del proveedor (*)";
                                    } 
                            }else{
                                $error="Debe ingresar Ciudad del proveedor (*)";
                            }
                    }else{
                        $error="Debe ingresar Comuna del proveedor (*)";
                    }
                }else{
                    $error="Debe ingresar Domicilio del proveedor (*)";
                }
            }else{
                $error="Debe ingresar Razon Social del proveedor (*)";
            }
        }else{
            $error="Debe ingresar Rut del proveedor (*)";
        }
    }


    if($_GET['action']==2 && isset($_GET['id_prov'])){
        
        if(isset($_POST['rut'])){
            $qry_prv = "SELECT * FROM proveedores WHERE rut_proveedor ='".$_POST['rut']."'";
        }else{
            $qry_prv = "SELECT * FROM proveedores WHERE rut_proveedor ='".$_GET['id_prov']."'";
        }
        $sel_prv = mysql_query($qry_prv,$con);


        $row = mysql_fetch_assoc($sel_prv);

        $rut_proveedor =$row['rut_proveedor'];
        $razon_social=$row['razon_social'];
        $domicilio=$row['domicilio'];
        $comuna=$row['comuna'];
        $ciudad=$row['ciudad'];
        $telefono_1=$row['telefono_1'];
        $telefono_2=$row['telefono_2'];
        $fax=$row['fax'];
        $celular=$row['celular'];
        $mail=$row['mail'];
        $observaciones=$row['observaciones'];
        $contact_contacto= $row['contacto'];
        $contact_telefono= $row['fono_contacto'];
        $contact_email= $row['email_contacto'];
        $sucursal_domicilio= $row['domicilio_sucursal'];
        $sucursal_comuna= $row['comuna_sucursal'];
        $sucursal_ciudad= $row['ciudad_sucursal'];
        $sucursal_telefono= $row['telefono_sucursal'];
        $sucursal_fax= $row['fax_sucursal'];
        $sucursal_contacto= $row['contacto_sucursal'];
        $sucursal_fono= $row['fono_contacto_sucursal'];
        $sucursal_email= $row['email_contacto_sucursal'];
         
        if(isset($_POST['rut'])){
            $action="2&new=2&id_prov=".$_POST['rut'];
        }else{
            $action="2&new=2&id_prov=".$_GET['id_prov'];
        }
        
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
<form action="?cat=2&sec=8&action=<?=$action; ?>" method="POST">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="" colspan=6"> <a href="?cat=2&sec=7"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;"  class="toolTIP" title="Volver al Listado de Proveedores"></a></td></tr>
    </tr>
    <tr>
        <td colspan="2"><label>Rut:(*) (11111111-1)</label><input class="fo" type="text" id="rut" name="rut" size="20" value="<?=$rut_proveedor;?>" <? if($rut_proveedor!=""){ echo "readonly";} ?>></td>
        <td colspan="4"><label>Razon Social:(*)</label><input class="fo" type="text" size="81" name="razon_social" value="<?=$razon_social;?>"></td>
    </tr>
    <tr>
        <td colspan="4"><label>Direccion:(*)</label><input class="fo" type="text" size="70" name="domicilio" value="<?=$domicilio;?>"></td>
        <td><label>Comuna:(*)</label><input type="text" class="fo" name="comuna" value="<?=$comuna;?>"></td>
        <td><label>Ciudad:(*)</label><input type="text" class="fo" name="ciudad" value="<?=$ciudad;?>"></td>
    </tr>
    <tr>
        <td colspan="2"><label>Telefono 1:(*)</label><input class="fo" class="nume" type="text" name="telefono_1" value="<?=$telefono_1;?>"></td>
        <td colspan="2"><label>Telefono 2:</label><input class="fo" class="nume" type="text" name="telefono_2" value="<?=$telefono_2;?>"></td>
        <td><label>Fax:</label><input class="fo" type="text" class="nume" name="fax" value="<?=$fax;?>"></td>
        <td><label>Celular:</label><input class="fo" type="text" class="nume" name="celular" value="<?=$celular;?>"></td>
    </tr>
    <tr>
        <td colspan="6"><label>Email:</label><input class="fo" type="text" size="80" name="email" value="<?=$mail;?>" id="email" onchange="valida_email(this.value);"></td>
    </tr>
    <tr>
        <td colspan="6"><label>Observacion:</label><br /><textarea cols="100" rows="4" name="observaciones"><?=$observaciones;?></textarea></td>
   </tr>
    <tr><td colspan="6">
             <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <tr >
                    <td id="titulo_tabla" style="text-align:center;" colspan="3">  ..:: Contacto Proveedor ::..</td>
                </tr>
                <tr>
                    <td width="300px"><label>Contacto:</label><input class="fo" name="contact_contacto" type="text" value="<?=$contact_contacto;?>"></td>
                    <td width="300px"><label>Telefono:</label><input class="fo" name="contact_telefono" type="text" value="<?=$contact_telefono;?>"></td>
                    <td width="300px"><label>Email:</label><input class="fo" name="contact_email" type="text" value="<?=$contact_email;?>" onchange="valida_email(this.value);"></td>
                </tr>
             </table>   
     </td></tr> 
   <tr><td colspan="6">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
               <tr >
                   <td id="titulo_tabla" style="text-align:center;" colspan="3">  ..:: Sucursal Proveedor ::..</td>
               </tr>
                <tr>
                   <td width="300px"><label>Domicilio:</label><input class="fo" name="sucursal_domicilio" type="text" value="<?=$sucursal_domicilio;?>"></td>
                   <td width="300px"><label>Comuna:</label><input class="fo" name="sucursal_comuna" type="text" value="<?=$sucursal_comuna;?>"></td>
                   <td width="300px"><label>Ciudad:</label><input class="fo" name="sucursal_ciudad" type="text" value="<?=$sucursal_ciudad;?>"></td>
               </tr>
               <tr>
                   <td><label>Telefono:</label><input class="fo" name="sucursal_telefono" type="text" value="<?=$sucursal_telefono;?>"></td>
                   <td><label>Fax:</label><input class="fo" name="sucursal_fax" type="text" value="<?=$sucursal_fax;?>"></td>
                   <td><label>Contacto:</label><input class="fo" name="sucursal_contacto" type="text" value="<?=$sucursal_contacto;?>"></td>
               </tr>
               <tr>
                   <td colspan="2"><label>Email Contacto:</label><input class="fo" name="sucursal_email" type="text" value="<?=$sucursal_email;?>" onchange="valida_email(this.value);"></td>
                   <td><label>Telefono Contacto:</label><input class="fo" name="sucursal_fono" type="text" value="<?=$sucursal_fono;?>"></td>
               </tr>
            </table>
   </td></tr> 
   <tr><td colspan="6">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">     
                <tr>
                    <td colspan="6" style="text-align: right;"><input type="submit" value="Grabar"></td>
                </tr>
            </table>
   </td></tr>     
   </table>
</form>