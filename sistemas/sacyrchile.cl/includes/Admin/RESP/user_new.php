<?php
$msg="";
$action=1;

        $usuario ="";
        $empresa="";
        $contrasena="";
        $nombre="";
        $email="";
        $cargo="";
        $depto="";
        $tipo_usuario="";
        $permisos="";
        $pass="";
        $pass2="";
        $bodega="";
        $estado="";

if(isset($_GET['action'])){    
    if($_GET['action']==1 && !isset($_GET['user'])){
        if($_POST['usuario']!= null){
        $sql_ins="INSERT INTO usuarios (usuario ,rut_empresa,contrasena,nombre,email ,cargo ,depto,cod_bodega ,tipo_usuario,permisos,usuario_ingreso,fecha_ingreso, estado_usuario,cambio_password  )
                VALUES ('".$_POST['usuario']."','".$_SESSION["empresa"]."', '123456','".$_POST['nombre']."', '".$_POST['email']."', '".$_POST['cargo']."', '".$_POST['depto']."', '".$_POST['bodega']."', '".$_POST['tipo_usuario']."', 'todos', '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."',1,1)";
      
        $em = verificaremail($_POST['email']);
           if(isset($_POST['nombre']) && $_POST['nombre']!="" && $_POST['nombre']!=NULL){
               if(isset($_POST['email']) && $_POST['email']!="" && $_POST['email']!=NULL && $em!=FALSE){
                    if(isset($_POST['cargo']) && $_POST['cargo']!="" && $_POST['cargo']!=NULL){
                        if(isset($_POST['depto']) && $_POST['depto']!="" && $_POST['depto']!=NULL){
                            if(isset($_POST['bodega']) && $_POST['bodega']!="" && $_POST['bodega']!=NULL){
                                    if(isset($_POST['tipo_usuario']) && $_POST['tipo_usuario']!="" && $_POST['tipo_usuario']!=NULL  && $_POST['tipo_usuario']!=0){
                                        if(mysql_query($sql_ins,$con)){
                                           $msg ="Se ha Agregado correctamente el Usuario";
                                       }else{
                                           $error="Ha ocurrido un error al grabar datos intente mas tarde.";
                                       }
                                     }else{
                                        $error="Debe Ingresar un Tipo de Usuario.";    
                                    } 
                            }else{
                                 $error="Debe asignar una Bodega al usuario.";   
                            } 
                        }else{
                                $error="Debe ingresar el departamento al cual Pertenece el Usuario.";
                        } 
                    }else{
                        $error="Debe ingresar el Cargo del Usuario.";
                    }    
                }else{
                    $error="Debe ingresar el Email del Usuario.";
                }    
           }else{
               $error="debe Ingresar el Nombre del Usuario.";
           }     
        
        }else{
            $error="Debe Ingresar un Login para el Usuario ";
        }
    }else{
        if(isset($_GET['user'])){

        }else{
            $action="1&new=1";
        }
    }

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
       
        if($_POST['usuario']!= null ){
            if($_POST['pass']==$_POST['pass2'] && $_POST['pass']!="" && $_POST['pass2']!="" && $_POST['pass']!=NULL && $_POST['pass2']!=NULL){
        
        $sql_update="UPDATE usuarios SET usuario='".$_POST['usuario']."', contrasena='".$_POST['pass']."', nombre='".$_POST['nombre']."', email='".$_POST['email']."', cargo='".$_POST['cargo']."', depto='".$_POST['depto']."',cod_bodega='".$_POST['bodega']."', tipo_usuario='".$_POST['tipo_usuario']."', estado_usuario='".$_POST['estado']."' WHERE usuario='".$_GET['user']."'";

            if(isset($_POST['nombre']) && $_POST['nombre']!="" && $_POST['nombre']!=NULL){
               if(isset($_POST['email']) && $_POST['email']!="" && $_POST['email']!=NULL){
                    if(isset($_POST['cargo']) && $_POST['cargo']!="" && $_POST['cargo']!=NULL){
                        if(isset($_POST['depto']) && $_POST['depto']!="" && $_POST['depto']!=NULL){
                            if(isset($_POST['bodega']) && $_POST['bodega']!="" && $_POST['bodega']!=NULL){
                                    if(isset($_POST['tipo_usuario']) && $_POST['tipo_usuario']!="" && $_POST['tipo_usuario']!=NULL && $_POST['tipo_usuario']!=0){
                                        if(mysql_query($sql_update,$con)){
                                           $msg ="Se ha Actualizado correctamente el Usuario";
                                       }else{
                                           $error="Ha ocurrido un error al grabar datos intente mas tarde.";
                                       }
                                     }else{
                                        $error="Debe Ingresar un Tipo de Usuario.";    
                                    } 
                            }else{
                                 $error="Debe asignar una Bodega al usuario.";   
                            } 
                        }else{
                                $error="Debe ingresar el departamento al cual Pertenece el Usuario.";
                        } 
                    }else{
                        $error="Debe ingresar el Cargo del Usuario.";
                    }    
                }else{
                    $error="Debe ingresar el Email del Usuario.";
                }    
           }else{
               $error="debe Ingresar el Nombre del Usuario.";
           }     
            }else{
                $error="Los Password ingresado deben Concidir";
            }
        }else{
            $error="Debe Ingresar un Login para el Usuario ";
        }
        
    }


    if($_GET['action']==2 && isset($_GET['user'])){
        $qry_prv = "SELECT * FROM usuarios u inner join bodegas b on u.cod_bodega=b.cod_bodega where u.rut_empresa='".$_SESSION['empresa']."' and usuario='".$_GET['user']."'";

        $sel_prv = mysql_query($qry_prv,$con);


        $row = mysql_fetch_assoc($sel_prv);

        $usuario =$row['usuario'];
        $empresa=$row['rut_empresa'];
        $contrasena=$row['contrasena'];
        $nombre=$row['nombre'];
        $email=$row['email'];
        $cargo=$row['cargo'];
        $depto=$row['depto'];
        $tipo_usuario=$row['tipo_usuario'];
        $permisos=$row['permisos'];
        $bodega = $row['cod_bodega'];
        $pass =$row['contrasena'];
        $pass2 = $pass;
        $estado=$row['estado_usuario'];
        $action="2&new=2&user=".$_GET['user'];
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
<form action="?cat=2&sec=5&action=<?=$action; ?>" method="POST">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
       <td id="titulo_tabla" style="text-align:center;" colspan="3">  <a href="?cat=2&sec=4"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Usuarios"></a></td></tr>
    <tr>
        <td colspan="1"><label>Usuario:(*)</label><br /><input class="fu" type="text" name="usuario" size="20" value="<?=$usuario;?>" <? if(isset($_GET['user'])){ echo "readonly";}?>></td>
         <td colspan="2"><label>Nombre:(*)</label><br /><input class="fu"  type="text" name="nombre" size="50" value="<?=utf8_encode($nombre);?>"></td>
    </tr>
    <tr>
        <td ><label>Email:(*)</label><input class="fo" type="text" size="70" name="email" value="<?=$email;?>" id="email" onchange="valida_email(this.value);"></td>
        <td><label>Cargo:(*)</label><input class="fo" type="text" name="cargo" value="<?=$cargo;?>"></td>
        <td><label>Depto:(*)</label><input class="fo" type="text" name="depto" value="<?=$depto;?>"></td>
    </tr>
    <tr>
        <td ><label>Bodega:(*)</label><br />
            <select name="bodega">
                <option value="" >---</option>
                <? 
                    $sql_b ="SELECT * FROM bodegas WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion_bodega";
                    $r_b = mysql_query($sql_b,$con);
                    if(mysql_num_rows($r_b)!=null){
                        while($ro = mysql_fetch_assoc($r_b)){
                            ?>
                <option value="<?=$ro['cod_bodega'];?>" <?if($ro['cod_bodega']==$bodega){ echo "SELECTED";}?> ><?=$ro['descripcion_bodega'];?></option>
                        <?
                        }
                    }else{
                        ?>
                <option>No Existen Datos</option>
                <?
                    }
                ?>
            </select>
        
        
        </td>
          <?if(!isset($_GET['user'])){ $pass="123456"; } ?>
        <td><?if(isset($_GET['user'])){  ?>
                <label>Password:(*)</label><input class="fo" type="password" size="30" name="pass" value="<?=$pass;?>" <?if(!isset($_GET['user'])){?> readonly<? } ?>><? }else{ echo "<label>Password Inicial:</label> 123456"; }?></td>
        <td><?if(isset($_GET['user'])){  ?><label>Reingrese Password:</label><input class="fo" type="password" size="30" name="pass2" value="<?=$pass;?>"  <?if(!isset($_GET['user'])){?> readonly<? } ?>><? } ?></td>
    </tr>
    <tr>
        <td><label>Tipo de Usuario:(*)</label> <br />
            <select name="tipo_usuario">
                <option value="0"> --- </option>
                <option value="1" <? if($tipo_usuario==1){ echo "SELECTED";}?>> Administrador</option>
                <option value="9" <? if($tipo_usuario==9){ echo "SELECTED";}?>> Adm. Operador</option>
                
            </select>
        
        </td>
        <td colspan="2"><label>Estado del Usuario:</label><br /> <select name="estado" <?if(!isset($_GET['user'])){?> disabled="disabled"<? } ?>><option value="1" <? if($estado==1){echo "SELECTED";}?>>Habilitar</option><option value="2" <? if($estado==2){echo "SELECTED";}?>>Deshabilitar</option></select></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right;"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>