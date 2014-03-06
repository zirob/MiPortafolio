<?php
if(isset($_GET['id_prov'])){
$rut_prov = $_GET['id_prov'];
}
if(isset($rut_prov) && $rut_prov!=null && !empty($rut_prov)){
$sql = "SELECT * FROM proveedores WHERE rut_proveedor='".$rut_prov."'";
$res = mysql_query($sql,$con);

$row = mysql_fetch_assoc($res);
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
?>

  <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="" colspan=6"> <a href="?cat=2&sec=7"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Proveedores"></a></td></tr>
    </tr>
    <tr>
        <td colspan="2"><label>Rut:(*) (11111111-1)</label><input class="fo" type="text" id="rut" name="rut" readonly size="20" value="<?=$rut_proveedor;?>"></td>
        <td colspan="4"><label>Razon Social:(*)</label><input class="fo" type="text" size="81" name="razon_social"  readonly value="<?=$razon_social;?>"></td>
    </tr>
    <tr>
        <td colspan="4"><label>Direccion:(*)</label><input class="fo" type="text" size="70" name="domicilio" value="<?=$domicilio;?>" readonly></td>
        <td><label>Comuna:(*)</label><input type="text" class="fo" name="comuna" value="<?=$comuna;?>" readonly></td>
        <td><label>Ciudad:(*)</label><input type="text" class="fo" name="ciudad" value="<?=$ciudad;?>" readonly></td>
    </tr>
    <tr>
        <td colspan="2"><label>Telefono 1:(*)</label><input class="fo" class="nume" type="text" name="telefono_1" value="<?=$telefono_1;?>" readonly></td>
        <td colspan="2"><label>Telefono 2:</label><input class="fo" class="nume" type="text" name="telefono_2" value="<?=$telefono_2;?>" readonly></td>
        <td><label>Fax:</label><input class="fo" type="text" class="nume" name="fax" value="<?=$fax;?>" readonly></td>
        <td><label>Celular:</label><input class="fo" type="text" class="nume" name="celular" value="<?=$celular;?>" readonly></td>
    </tr>
    <tr>
        <td colspan="6"><label>Email:</label><input class="fo" type="text" size="80" name="email" readonly value="<?=$mail;?>" id="email" onchange="valida_email(this.value);"></td>
    </tr>
    <tr>
        <td colspan="6"><label>Observacion:</label><br /><textarea cols="100" rows="4" readonly name="observaciones"><?=$observaciones;?></textarea></td>
   </tr>
    <tr><td colspan="6">
             <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <tr >
                    <td id="titulo_tabla" style="text-align:center;" colspan="3">  ..:: Contacto Proveedor ::..</td>
                </tr>
                <tr>
                    <td width="300px"><label>Contacto:</label><input class="fo" name="contact_contacto"  readonly type="text" value="<?=$contact_contacto;?>"></td>
                    <td width="300px"><label>Telefono:</label><input class="fo" name="contact_telefono" readonly type="text" value="<?=$contact_telefono;?>"></td>
                    <td width="300px"><label>Email:</label><input class="fo" name="contact_email" type="text" readonly value="<?=$contact_email;?>" onchange="valida_email(this.value);"></td>
                </tr>
             </table>   
     </td></tr> 
   <tr><td colspan="6">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
               <tr >
                   <td id="titulo_tabla" style="text-align:center;" colspan="3">  ..:: Sucursal Proveedor ::..</td>
               </tr>
                <tr>
                   <td width="300px"><label>Domicilio:</label><input class="fo" name="sucursal_domicilio" readonly type="text" value="<?=$sucursal_domicilio;?>"></td>
                   <td width="300px"><label>Comuna:</label><input class="fo" name="sucursal_comuna" readonly type="text" value="<?=$sucursal_comuna;?>"></td>
                   <td width="300px"><label>Ciudad:</label><input class="fo" name="sucursal_ciudad" readonly type="text" value="<?=$sucursal_ciudad;?>"></td>
               </tr>
               <tr>
                   <td><label>Telefono:</label><input class="fo" name="sucursal_telefono" type="text" readonly value="<?=$sucursal_telefono;?>"></td>
                   <td><label>Fax:</label><input class="fo" name="sucursal_fax" type="text" readonly value="<?=$sucursal_fax;?>"></td>
                   <td><label>Contacto:</label><input class="fo" name="sucursal_contacto" type="text" readonly value="<?=$sucursal_contacto;?>"></td>
               </tr>
               <tr>
                   <td colspan="2"><label>Email Contacto:</label><input class="fo" name="sucursal_email" readonly type="text" value="<?=$sucursal_email;?>" onchange="valida_email(this.value);"></td>
                   <td><label>Telefono Contacto:</label><input class="fo" name="sucursal_fono" type="text" readonly value="<?=$sucursal_fono;?>"></td>
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
<?
}else{
 ?>
<div id="main-error">Debe Seleccionar un Proveedor para ver informacion</div>
<?
}
?>