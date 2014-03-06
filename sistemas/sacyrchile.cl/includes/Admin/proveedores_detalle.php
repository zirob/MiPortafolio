<?php
/*  $sql = "SELECT a.*,e.*, e.Id_Esp ";
    $sql.= "FROM PROVEEDORES p inner join ESPECIALIDADES e ";
    $sql.= "WHERE p.Id_Esp=e.Id_Esp AND Rut_Proveedor='".$_GET['id_prov']."'";*/


     $sql="SELECT  * FROM  proveedores WHERE rut_proveedor='".$_GET['id_prov']."' ";
    $rec=mysql_query($sql);
    $row=mysql_fetch_array($rec);
    $_POST=$row;
    $_POST["rut"] = $row["rut_proveedor"];
    $_POST['id_prov']=$row['rut_proveedor'];
    $_POST["especialidades"] = $row["id_esp"];
    $_POST["sub_especializacion"] = $row["Sub_Especializacion"];
    $_POST["razon_social"]=$row['razon_social'];
    $_POST["domicilio"]=$row['domicilio'];
    $_POST["comuna"]=$row['comuna'];
    $_POST["ciudad"]=$row['ciudad'];
    $_POST["telefono_1"]=$row['telefono_1'];
    $_POST["telefono_2"]=$row['telefono_2'];
    $_POST["fax"]=$row['fax'];
    $_POST["celular"]=$row['celular'];
    $_POST["mail"]=$row['mail'];
    $_POST["observaciones"] = trim($row["observaciones"]);
    $_POST["contact_contacto"]= $row['contacto'];
    $_POST["contact_telefono"]= $row['fono_contacto'];
    $_POST["contact_email"]= $row['email_contacto'];
    $_POST["sucursal_domicilio"]= $row['domicilio_sucursal'];
    $_POST["sucursal_comuna"]= $row['comuna_sucursal'];
    $_POST["sucursal_ciudad"]= $row['ciudad_sucursal'];
    $_POST["sucursal_telefono"]= $row['telefono_sucursal'];
    $_POST["sucursal_fax"]= $row['fax_sucursal'];
    $_POST["sucursal_contacto"]= $row['contacto_sucursal'];
    $_POST["sucursal_fono"]= $row['fono_contacto_sucursal'];
    $_POST["sucursal_email"]= $row['email_contacto_sucursal'];
?>


    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr>
        <!-- Boton para volver a pantalla principal -->
        <td id="titulo_tabla" style="" colspan=6"> 
            <a href="?cat=2&sec=7">
                <img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;"  class="toolTIP" title="Volver al Listado de Proveedores">
            </a>
        </td>
    </tr>
    
    <!-- Input de Rut, razon social y especializacion -->
    
    <tr>
        <td >
            <label>Rut: (11111111-1)</label>
            <input class="fo" type="text" id="rut" name="rut" size="20" value="<?=$_POST["rut"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"
            <? 
            if(!empty($row["rut_proveedor"])){
                 echo "readonly";
             }
             ?> >
        </td> 
        <td colspan="4">
            <label>Razon Social:</label>
            <input  class="fo" type="text" size="81" name="razon_social" value="<?=$_POST["razon_social"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly> 
        </td>   
        <td >
<?php     
                $sql_esp = "SELECT id_esp, descripcion_esp FROM especialidades WHERE id_esp='".$_POST["especialidades"]."'";
                 $rec_esp=mysql_query($sql_esp);
                  $row_esp=mysql_fetch_array($rec_esp);
?>
            <label>Especialización:</label>
            <input  class="fo" type="text" size="20"  name="especialidades" value="<?=$row_esp["descripcion_esp"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly> 
        </td>
    </tr>

    <!-- Input sub-Especialización, Direccion y comuna -->

    <tr>
        <td colspan="2">
            <label>Sub-Especialización:</label>
            <input class="fo" type="text" size="70" name="sub_especializacion" value="<?=$_POST["sub_especializacion"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
        <td colspan="3">
            <label>Direccion:</label>
            <input class="fo" type="text" size="70" name="domicilio" value="<?=$_POST["domicilio"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
        <td>
            <label>Comuna:</label>
            <input type="text" class="fo" name="comuna" value="<?=$_POST["comuna"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
    </tr>

    <!-- Input Telefono 1, Telefono 2, Fax, Celular-->

    <tr>
        <td colspan="2">
            <label>Ciudad:</label>
            <input type="text" class="fo" name="ciudad" value="<?=$_POST["ciudad"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
        <td >
            <label>Teléfono 1:</label>
            <input class="fo" class="nume" type="text" size="20" name="telefono_1" value="<?=$_POST["telefono_1"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
        <td>
            <label>Teléfono 2:</label>
            <input class="fo" class="nume" type="text" size="20" name="telefono_2" value="<?=$_POST["telefono_2"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
        <td>
            <label>Fax:</label>
            <input class="fo" type="text" class="nume" name="fax" value="<?=$_POST["fax"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
        <td>
            <label>Celular:</label>
            <input class="fo" type="text" class="nume" name="celular" value="<?=$_POST["celular"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
    </tr>

    <!-- Input Email -->

    <tr>
        <td colspan="2">
            <label>Email:</label>
            <input class="fo" type="text" size="50"  name="email" value="<?=$_POST["mail"];?>" id="email" onchange="valida_email(this.value);" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
        </td>
    </tr>

    <!-- Input Observacion -->

    <tr>
        <td colspan="6">
            <label>Observación:</label>
            <br/>
            <textarea cols="100" rows="4" name="observaciones" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly><?=$_POST["observaciones"];?></textarea>
        </td>
    </tr>

    <!-- Datos de Contacto del Proveedor -->

    <tr>
        <td colspan="6">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                
                <!-- Titulo Contacto -->

                <tr>
                    <td id="titulo_tabla" style="text-align:center;" colspan="3">  ..:: Contacto Proveedor ::..</td>
                </tr>

                <!-- Input de Contacto, Telefono, Email -->

                <tr>
                    <td width="300px">
                        <label>Contacto:</label>    
                        <input class="fo" name="contact_contacto" type="text" value="<?=$_POST["contact_contacto"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td width="300px">
                        <label>Télefono:</label>
                        <input class="fo" name="contact_telefono" type="text" value="<?=$_POST["contact_telefono"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td width="300px">
                        <label>Email:</label>
                        <input class="fo" name="contact_email" type="text" value="<?=$_POST["contact_email"];?>" onchange="valida_email(this.value);" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly></td>
                </tr>
            </table>   
        </td>
    </tr> 

    <!-- Datos de Sucursal del Proveedor -->

    <tr>
        <td colspan="6">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
               
                <!-- Titulo Sucursal -->

                <tr>
                   <td id="titulo_tabla" style="text-align:center;" colspan="3">  ..:: Sucursal Proveedor ::..</td>
                </tr>

                <!-- Input de Domicilio, Comuna, Ciudad -->

                <tr>
                    <td width="300px">
                        <label>Domicilio:</label>
                        <input class="fo" name="sucursal_domicilio" type="text" value="<?=$_POST["sucursal_domicilio"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td width="300px">
                        <label>Comuna:</label>
                        <input class="fo" name="sucursal_comuna" type="text" value="<?=$_POST["sucursal_comuna"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td width="300px">
                        <label>Ciudad:</label>
                        <input class="fo" name="sucursal_ciudad" type="text" value="<?=$_POST["sucursal_ciudad"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                </tr>

                <!-- Input de Telefono, fax y contacto de sucursal -->

                <tr>
                    <td>
                        <label>Teléfono:</label>
                        <input class="fo" name="sucursal_telefono" type="text" value="<?=$_POST["sucursal_telefono"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td>
                        <label>Fax:</label>
                        <input class="fo" name="sucursal_fax" type="text" value="<?=$_POST["sucursal_fax"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td>
                        <label>Contacto:</label>
                        <input class="fo" name="sucursal_contacto" type="text" value="<?=$_POST["sucursal_contacto"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                </tr>

                <!-- Input Email y fono de contacto sucursal -->

                <tr>
                    <td colspan="2">
                        <label>Email Contacto:</label>
                        <input class="fo" name="sucursal_email" type="text" value="<?=$_POST["sucursal_email"];?>" onchange="valida_email(this.value);" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
                    <td>
                        <label>Teléfono Contacto:</label>
                        <input class="fo" name="sucursal_fono" type="text" value="<?=$_POST["sucursal_fono"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" readonly>
                    </td>
               </tr>
               
            </table>
        </td>
    </tr> 
    
</table>
