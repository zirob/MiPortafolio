<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>

<?php
// var_dump($_SESSION);
///////////////////////////////////////     Valida Email    //////////////////////////////////////////////////////////////
function comprobar_email($email){ 
    $mail_correcto = 0; 
    //compruebo unas cosas primeras 
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
     if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
             //miro si tiene caracter . 
         if (substr_count($email,".")>= 1){ 
                 //obtengo la terminacion del dominio 
             $term_dom = substr(strrchr ($email, '.'),1); 
                 //compruebo que la terminación del dominio sea correcta 
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
                 //compruebo que lo de antes del dominio sea correcto 
                 $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
                 $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
                 if ($caracter_ult != "@" && $caracter_ult != "."){ 
                     $mail_correcto = 1; 
                 } 
             } 
         } 
     } 
 } 
 if ($mail_correcto) 
     return 1; 
 else 
     return 0; 
} 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




// Validaciones
$mostrar=0;
if(!empty($_POST['accion'])){

    $error=0;
    if(empty($_POST['rut'])){
        $error=1;
        $mensaje="Debe ingresar Rut del Proveedor (*)";
    }
    // if(isset($_POST['rut']) && $_POST['accion']!='editar'){
    if(!empty($_POST['bandera_guardar'])){
        $rut = str_replace(".", "", $_POST["rut"]);
        $sql_rut = "SELECT rut_proveedor FROM proveedores WHERE rut_proveedor='".$rut."' AND rut_empresa='".$_SESSION['empresa']."'";
        $res_rut = mysql_query($sql_rut);
        if(mysql_num_rows($res_rut))
            $error=1;
        $mensaje="Rut repetido, Ingrese nuevo RUT del Proveedor";
        $row["rut_proveedor"] = $_POST["rut"];
    }

    if(empty($_POST['razon_social'])){
        $error=1;
        $mensaje="Debe ingresar Razon Social del Proveedor (*)";
    }

    if(empty($_POST['domicilio'])){
        $error=1;
        $mensaje="Debe ingresar Domicilio del Proveedor (*)";
    }

    if($_POST['especialidades']==0){
        $error=1;
        $mensaje = "Debe seleccionar Especialización del Proveedor (*)";
    } 

    if(empty($_POST['sub_especializacion'])){
        $error=1;
        $mensaje = "Debe ingresar Sub-Especialización del Proveedor (*)";
    }  

    if(empty($_POST['comuna'])){
        $error=1;
        $mensaje="Debe ingresar Comuna del Proveedor (*)";
    }

    if(empty($_POST['ciudad'])){
        $error=1;
        $mensaje="Debe ingresar Ciudad del Proveedor (*)";
    }

    if(empty($_POST['telefono_1'])){
        $error=1;
        $mensaje="Debe ingresar Telefono 1 del Proveedor (*)";
    }  
    
    if(!empty($_POST['email'])){
        $validador=comprobar_email($_POST['email']);
        if($validador!=1)
        {
            $error=1;
            $mensaje=" Error en el formato del Email  ";
        }
    }
    
    if(!empty($_POST['contact_email'])){
        $validador=comprobar_email($_POST['contact_email']);
        if($validador!=1)
        {
            $error=1;
            $mensaje=" Error en el formato del Email de Contacto  ";
        }
    }

    if(!empty($_POST['sucursal_email'])){
        $validador=comprobar_email($_POST['sucursal_email']);
        if($validador!=1)
        {
            $error=1;
            $mensaje=" Error en el formato del Email de Sucursal  ";
        }
    }
}

// Rescata los datos
if(!empty($_GET['id_prov']) and empty($_POST['primera']))
{
    $sql="SELECT  * FROM  proveedores WHERE rut_proveedor='".$_GET['id_prov']."' AND rut_empresa='".$_SESSION['empresa']."'";
    $rec=mysql_query($sql);
    $row=mysql_fetch_array($rec);
    $_POST=$row;
    $_POST["rut"] = $row["rut_proveedor"];
    $_POST['id_prov']=$row['rut_proveedor'];
    $_POST["especialidades"] = $row["id_esp"];
    $_POST["sub_especializacion"] = $row["Sub_Especializacion"];//con mayusculas
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
    $_POST["observaciones1"] = trim($row["observaciones1"]);
    $_POST["observaciones2"] = trim($row["observaciones2"]);
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
}

//Cosulta si el PROVEEDOR existe
if(empty($_POST['bandera_guardar']))
{
    $sql="SELECT  * FROM  proveedores WHERE rut_proveedor='".$_POST['rut']."' AND rut_empresa='".$_SESSION['empresa']."'";
    $rec=mysql_query($sql);
    $row=mysql_fetch_array($rec);
}

//Discrimina si guarda o edita
if(!empty($_POST['accion'])){

    if($error==0){

        if($_POST['accion']=="guardar"){


                $sql_ins="INSERT INTO proveedores (rut_proveedor,rut_empresa ,razon_social ,domicilio ,comuna ,ciudad ,telefono_1 ,telefono_2 ,fax,";
                $sql_ins.="celular ,mail ,contacto,fono_contacto,email_contacto,domicilio_sucursal,comuna_sucursal,ciudad_sucursal,";
                $sql_ins.="telefono_sucursal,fax_sucursal,contacto_sucursal,fono_contacto_sucursal,email_contacto_sucursal,observaciones,observaciones1,observaciones2,fecha_ingreso ,usuario_ingreso, id_esp, sub_especializacion)";
                $sql_ins.="VALUES ('".str_replace(".","",$_POST['rut'])."','".$_SESSION['empresa']."', '".$_POST['razon_social']."', '".$_POST['domicilio']."', '".$_POST['comuna']."', '".$_POST['ciudad']."', '".$_POST['telefono_1']."', '".$_POST['telefono_2']."', '".$_POST['fax']."', '".$_POST['celular']."', ";
                $sql_ins.="'".$_POST['email']."','".$_POST['contact_contacto']."', '".$_POST['contact_telefono']."', '".$_POST['contact_email']."', '".$_POST['sucursal_domicilio']."', '".$_POST['sucursal_comuna']."',";
                $sql_ins.="'".$_POST['sucursal_ciudad']."', '".$_POST['sucursal_telefono']."', '".$_POST['sucursal_fax']."', '".$_POST['sucursal_contacto']."', '".$_POST['sucursal_fono']."', '".$_POST['sucursal_email']."','".$_POST['observaciones']."','".$_POST['observaciones1']."','".$_POST['observaciones2']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['user']."', ";
                $sql_ins.="'".$_POST["especialidades"]."', '".$_POST["sub_especializacion"]."' )";
                $consulta=mysql_query($sql_ins,$con);
                if($consulta)
                    $mensaje=" Ingreso Correcto ";
                    $mostrar=1;

                    

                $consulta = "SELECT MAX(rut_proveedor) as rut_proveedor FROM proveedores WHERE rut_empresa='".$_SESSION["empresa"]."'";
                $resultado=mysql_query($consulta);
                $fila=mysql_fetch_array($resultado);
                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'proveedores', '".$fila["rut_proveedor"]."', '2'";
                $sql_even.= ", 'INSERT:rut_proveedor=".str_replace(".","",$_POST['rut']).",rut_empresa=".$_SESSION['empresa']." ,razon_social=".$_POST['razon_social']." ,domicilio=".$_POST['domicilio']." ,comuna=".$_POST['comuna']." ,ciudad=".$_POST['ciudad']." ,telefono_1=".$_POST['telefono_1']." ,telefono_2=".$_POST['telefono_2']." ,fax=".$_POST['fax'].",";
                $sql_even.="celular=".$_POST['celular']." ,mail=".$_POST['email']." ,contacto=".$_POST['contact_contacto'].",fono_contacto=".$_POST['contact_telefono'].",email_contacto=".$_POST['contact_email'].",domicilio_sucursal=".$_POST['sucursal_domicilio'].",comuna_sucursal=".$_POST['sucursal_comuna'].",ciudad_sucursal=".$_POST['sucursal_ciudad'].",";
                $sql_even.="telefono_sucursal=".$_POST['sucursal_telefono'].",fax_sucursal=".$_POST['sucursal_fax'].",contacto_sucursal=".$_POST['sucursal_contacto'].",fono_contacto_sucursal=".$_POST['sucursal_fono'].",email_contacto_sucursal=".$_POST['sucursal_email'].",observaciones=".$_POST['observaciones'].",fecha_ingreso=".date('Y-m-d H:i:s')." ,usuario_ingreso=".$_SESSION['user'].", id_esp=".$_POST["especialidades"].", sub_especializacion=".$_POST["sub_especializacion"]."";
                $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."', 'insersion proveedores', '1', '".date('Y-m-d H:i:s')."') ";
                mysql_query($sql_even, $con);   

        }else{ 
                $sql_update = "UPDATE proveedores SET rut_proveedor='".$_POST['rut']."' ,razon_social='".$_POST['razon_social']."' ,domicilio='".$_POST['domicilio']."', ";
                $sql_update.= "comuna='".$_POST['comuna']."' ,ciudad='".$_POST['ciudad']."' ,telefono_1='".$_POST['telefono_1']."' ,telefono_2='".$_POST['telefono_2']."' ,fax='".$_POST['fax']."', ";
                $sql_update.= "celular='".$_POST['celular']."' ,mail='".$_POST['email']."',observaciones='".$_POST['observaciones']."',observaciones1='".$_POST['observaciones1']."',observaciones2='".$_POST['observaciones2']."',contacto='".$_POST['contact_contacto']."',fono_contacto='".$_POST['contact_telefono']."', ";
                $sql_update.= "email_contacto='".$_POST['contact_email']."',domicilio_sucursal='".$_POST['sucursal_domicilio']."',comuna_sucursal='".$_POST['sucursal_comuna']."', ";
                $sql_update.= "ciudad_sucursal='".$_POST['sucursal_ciudad']."',telefono_sucursal='".$_POST['sucursal_telefono']."',fax_sucursal='".$_POST['sucursal_fax']."', ";
                $sql_update.= "contacto_sucursal='".$_POST['sucursal_contacto']."',fono_contacto_sucursal='".$_POST['sucursal_fono']."',email_contacto_sucursal='".$_POST['sucursal_email']."', ";
                $sql_update.= "id_esp='".$_POST["especialidades"]."', sub_especializacion='".$_POST["sub_especializacion"]."' ";
                $sql_update.= "WHERE rut_proveedor='".$_POST["rut"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
                $consulta=mysql_query($sql_update);
                if($consulta)
                    $mensaje=" Actualizacion Correcta ";
                $mostrar=1;


                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'proveedores', '".$_POST["rut"]."', '3'";
                $sql_even.= ", 'UPDATE: ";
                $sql_even.= "rut_proveedor=".$_POST['rut']." ,razon_social=".$_POST['razon_social']." ,domicilio=".$_POST['domicilio'].", ";
                $sql_even.= "comuna=".$_POST['comuna']." ,ciudad=".$_POST['ciudad']." ,telefono_1=".$_POST['telefono_1']." ,telefono_2=".$_POST['telefono_2']." ,fax=".$_POST['fax'].", ";
                $sql_even.= "celular=".$_POST['celular']." ,mail=".$_POST['email'].",observaciones=".$_POST['observaciones'].",contacto=".$_POST['contact_contacto'].",fono_contacto=".$_POST['contact_telefono'].", ";
                $sql_even.= "email_contacto=".$_POST['contact_email'].",domicilio_sucursal=".$_POST['sucursal_domicilio'].",comuna_sucursal=".$_POST['sucursal_comuna'].", ";
                $sql_even.= "ciudad_sucursal=".$_POST['sucursal_ciudad'].",telefono_sucursal=".$_POST['sucursal_telefono'].",fax_sucursal=".$_POST['sucursal_fax'].", ";
                $sql_even.= "contacto_sucursal=".$_POST['sucursal_contacto'].",fono_contacto_sucursal=".$_POST['sucursal_fono'].",email_contacto_sucursal=".$_POST['sucursal_email'].", ";
                $sql_even.= "id_esp=".$_POST["especialidades"].", sub_especializacion=".$_POST["sub_especializacion"]." ";
                $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion proveedores', '1', '".date('Y-m-d H:i:s')."') ";
                mysql_query($sql_even, $con);
        }
    }
}  


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

//Manejo de errores

    /*if($_GET['action']==2 && isset($_GET['id_prov'])){
        
        if(isset($_POST['rut'])){
            $qry_prv = "SELECT * FROM proveedores WHERE rut_proveedor ='".$_POST['rut']."'";
        }else{
            $qry_prv = "SELECT * FROM proveedores WHERE rut_proveedor ='".$_GET['id_prov']."'";
        }
        $sel_prv = mysql_query($qry_prv,$con);

        $row = mysql_fetch_assoc($sel_prv);

        $_POST["rut_proveedor"] =$row['rut_proveedor']; 
        $_POST["razon_social"]=$row['razon_social'];
        $_POST["domicilio"]=$row['domicilio'];
        $_POST["comuna"]=$row['comuna'];
        $_POST["ciudad"]=$row['ciudad'];
        $_POST["telefono_1"]=$row['telefono_1'];
        $_POST["telefono_2"]=$row['telefono_2'];
        $_POST["fax"]=$row['fax'];
        $_POST["celular"]=$row['celular'];
        $_POST["mail"]=$row['mail'];
        $_POST["observaciones"]=$row['observaciones'];
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
        $_POST["sub_especializacion"] = $row["sub_especializacion"];
        
        if(empty($_POST["primera"])){
            $_POST["especialidades"] = $row["id_esp"];
        }else{

        }
        if(isset($_POST['rut'])){
            $action="2&new=2&id_prov=".$_POST['rut'];
        }else{
            $action="2&new=2&id_prov=".$_GET['id_prov'];
        }
        
    }*/

    ?>

    <style>
    .fu
    {
        background-color:#FFFFFF;
        color:rgb(0,0,0);
    }
    </style>


    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
        <tr>
            <!-- Boton para volver a pantalla principal -->
            <td id="titulo_tabla" style="" colspan=6"> 
                <a href="?cat=2&sec=7">
                    <img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;"  class="toolTIP" title="Volver al Listado de Proveedores">
                </a>
            </td>
        </tr>
    </table>
<?php
    if($mostrar==0){
?>
        <form action="?cat=2&sec=8&action=<?=$_GET["action"]; ?>" method="POST">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <!-- Input de Rut, razon social y especializacion -->

                <tr>
                    <td >
                        <label>Rut: (11111111-1)</label><label style="color:red">(*) </label>
                        <input class="fo" type="text" id="rut" name="rut" size="20" value="<?=$_POST["rut"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" <? if($_GET["action"]==2) echo "readonly";?> >
                    </td> 
                    <td colspan="4">
                        <label>Razon Social:</label><label style="color:red">(*)</label>
                        <input class="fo" type="text" size="81" name="razon_social" value="<?=$_POST["razon_social"];?> "style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"> 
                    </td>   
                    <td >
                        <label>Especialización:</label><label style="color:red">(*)</label>
                            <select name="especialidades" style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;'><!--elige especialidades-->
                                <?php
                                $sql2 = "SELECT id_esp, descripcion_esp FROM especialidades WHERE 1=1 ORDER BY descripcion_esp ";
                                $res2 = mysql_query($sql2,$con);
                                ?>
                                <option value='0' <? if (isset($_POST["especialidades"]) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
                                <?php              
                                while($row2 = mysql_fetch_assoc($res2)){
                                    ?>
                                    <option value='<? echo $row2["id_esp"]; ?>' <? if ($row2['id_esp'] == $_POST['especialidades']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $row2["descripcion_esp"];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                    </td>
                </tr>

                <!-- Input sub-Especialización, Direccion y comuna -->

                <tr>
                    <td colspan="2">
                        <label>Sub-Especialización:</label><label style="color:red">(*)</label>
                        <input class="fo" type="text" size="70" name="sub_especializacion" value="<?=$_POST["sub_especializacion"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                    </td>
                    <td colspan="3">
                        <label>Dirección:</label><label style="color:red">(*)</label>
                        <input class="fo" type="text" size="70" name="domicilio" value="<?=$_POST["domicilio"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                    </td>
                    <td>
                        <label>Comuna:</label><label style="color:red">(*)</label>
                        <input type="text" class="fo" name="comuna" value="<?=$_POST["comuna"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                    </td>
            </tr>

            <!-- Input Telefono 1, Telefono 2, Fax, Celular-->

            <tr>
                <td colspan="2">
                    <label>Ciudad:</label><label style="color:red">(*)</label>
                    <input type="text" class="fo" name="ciudad" value="<?=$_POST["ciudad"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                </td>
                <td >
                    <label>Teléfono 1:</label><label style="color:red">(*)</label>
                    <input class="fo" class="nume" type="text" size="20" name="telefono_1" value="<?=$_POST["telefono_1"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                </td>
                <td>
                    <label>Teléfono 2:</label>
                    <input class="fo" class="nume" type="text" size="20" name="telefono_2" value="<?=$_POST["telefono_2"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                </td>
                <td>
                    <label>Fax:</label>
                    <input class="fo" type="text" class="nume" name="fax" value="<?=$_POST["fax"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                </td>
                <td>
                    <label>Celular:</label>
                    <input class="fo" type="text" class="nume" name="celular" value="<?=$_POST["celular"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                </td>
            </tr>

            <!-- Input Email -->

            <tr>
                <td colspan="2">
                    <label>Email:</label>
                    <input class="fo" type="text" size="50"  name="email" value="<?=$_POST["email"];?>" id="email" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                </td>
            </tr>

            <!-- Input Observacion -->

            <tr>
                <td colspan="6">
                    <label>Observación:</label>
                    <br/>
                    <textarea cols="100" rows="4" name="observaciones" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones"];?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <label>Observación 1:</label>
                    <br/>
                    <textarea cols="100" rows="4" name="observaciones1" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones1"];?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <label>Observación 2:</label>
                    <br/>
                    <textarea cols="100" rows="4" name="observaciones2" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones2"];?></textarea>
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
                                <input class="fo" name="contact_contacto" type="text" value="<?=$_POST["contact_contacto"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                            </td>
                            <td width="300px">
                                <label>Teléfono:</label>
                                <input class="fo" name="contact_telefono" type="text" value="<?=$_POST["contact_telefono"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()" onKeyPress="ValidaSoloNumeros()">
                            </td>
                            <td width="300px">
                                <label>Email:</label>
                                <input class="fo" name="contact_email" type="text" value="<?=$_POST["contact_email"];?>"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"></td>
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
                                <input class="fo" name="sucursal_domicilio" type="text" value="<?=$_POST["sucursal_domicilio"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                            </td>
                            <td width="300px">
                                <label>Comuna:</label>
                                <input class="fo" name="sucursal_comuna" type="text" value="<?=$_POST["sucursal_comuna"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                            </td>
                            <td width="300px">
                                <label>Ciudad:</label>
                                <input class="fo" name="sucursal_ciudad" type="text" value="<?=$_POST["sucursal_ciudad"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                            </td>
                        </tr>

                        <!-- Input de Telefono, fax y contacto de sucursal -->

                        <tr>
                            <td>
                                <label>Teléfono:</label>
                                <input class="fo" name="sucursal_telefono" type="text" value="<?=$_POST["sucursal_telefono"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                            </td>
                            <td>
                                <label>Fax:</label>
                                <input class="fo" name="sucursal_fax" type="text" value="<?=$_POST["sucursal_fax"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                            </td>
                            <td>
                                <label>Contacto:</label>
                                <input class="fo" name="sucursal_contacto" type="text" value="<?=$_POST["sucursal_contacto"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                            </td>
                        </tr>

                        <!-- Input Email y fono de contacto sucursal -->

                        <tr>
                            <td colspan="2">
                                <label>Email Contacto:</label>
                                <input class="fo" name="sucursal_email" type="text" value="<?=$_POST["sucursal_email"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                            </td>
                            <td>
                                <label>Teléfono Contacto:</label>
                                <input class="fo" name="sucursal_fono" type="text" value="<?=$_POST["sucursal_fono"];?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" onKeyPress="ValidaSoloNumeros()">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr> 
            <tr>
                <td colspan="6">
                    <div style="width:100%; height:auto; text-align:right;">
                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                        <?

                        if($_GET["action"]==1)
                        {
                            echo  " value='guardar' >Guardar</button><input name='bandera_guardar' type='hidden' value='1'>";
                        }
                        if($_GET["action"]==2)
                        {
                            echo " value='editar' >Actualizar</button>";
                        }
                        ?>

                    </div>
                    <input  type="hidden" name="primera" value="1"/>
                </td>
            </tr>
            <tr>
                <td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                  (*) Campos de Ingreso Obligatorio.
              </td>
          </tr>     
      </table>
  </form>

  <?php
  // var_dump($_POST);
}
?>
