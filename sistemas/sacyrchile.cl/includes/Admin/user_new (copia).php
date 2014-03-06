<style>
#mostrar
{
	display:none;
}

a:hover
{
	cursor:pointer;
}
</style>
<script>
function mostrar()
{
	$("#mostrar").css("display","block");
}

function ocultar()
{
	$("#mostrar").css("display","none");
}
</script>
<?
// var_dump($_POST);
//Validaciones
$mostrar=0;
  

if(!empty($_POST['accion']))
{
	$error=0;

	if(empty($_POST['usuario']))
	{
		$error=1;
		$mensaje=" Debe ingresar el usuario  ";
	}
	else
	{
		if(!empty($_POST['bandera_guardar']))
		{
			$sql3="SELECT  * FROM  usuarios WHERE usuario='".$_POST['usuario']."' ";
			$rec3=mysql_query($sql3);
			$row3=mysql_fetch_array($rec3);
			$num=mysql_num_rows($rec3);
			if($num>0)
			{
						$error=1;
						$mensaje=" Ya existe  el usuario  ingresado ";
			}
		}
	}
	
	if(empty($_POST['nombre']))
	{
		$error=1;
		$mensaje=" Debe ingresar el nombre  ";
	}
	
	if(empty($_POST['email']))
	{
		$error=1;
		$mensaje=" Debe ingresar el email  ";
	}
	
	if(empty($_POST['cargo']))
	{
		$error=1;
		$mensaje=" Debe ingresar el cargo  ";
	}
	
	if(empty($_POST['depto']))
	{
		$error=1;
		$mensaje=" Debe ingresar el depto  ";
	}
	
	if(empty($_POST['bodega']))
	{
		$error=1;
		$mensaje=" Debe ingresar la bodega  ";
	}
	
	if(empty($_POST['tipo_usuario']))
	{
		$error=1;
		$mensaje=" Debe ingresar el tipo de usuario  ";
	}
}
	
	//Subo el archivo
	if ($_POST["action"] == "upload") 
	{
		$destino="firmas/".$_FILES['archivo']['name'];
		$fecha=strftime("%d-%m-%Y",time());
		$sql.=" UPDATE usuarios SET";
		$sql.=" nombre_arch_fd='".$_FILES['archivo']['name']."'";
		$sql.=",ruta_arch_fd='".$destino."'";
		$sql.=",ext_arch_fd='".$_FILES['archivo']['type']."'";
		$sql.=",user_insert_fd='".$_SESSION['user']."'";
		$sql.=",fecha_insert_fd='".$fecha."'";
		$sql.=" WHERE usuario='".$_GET['user']."'";
		mysql_query( $sql);
		
        if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino))
		{
            $mensaje = "Archivo subido";
        }
		else
		{
			 $error=1;
            $mensaje = "Error al subir el archivo";
        }
		
	}


//Rescata los datos
if(!empty($_GET['user']) and empty($_POST['primera']))
{
	$sql="SELECT  * FROM  usuarios WHERE usuario='".$_GET['user']."' ";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$_POST=$row;
	$_POST['bodega']=$row['cod_bodega'];
	$_POST['estado']=$row['estado_usuario'];
	$_POST['contrasena']=$row['key_password'];
	$_POST['archivo_firma']=$row['nombre_arch_fd'];
	
	
}


//Cosulta si el usuario existe
if(empty($_POST['bandera_guardar']))
{
$sql="SELECT  * FROM  usuarios WHERE usuario='".$_POST['usuario']."' ";
$rec=mysql_query($sql);
$row=mysql_fetch_array($rec);
}

//Discrimina si guarda o edita
if(!empty($_POST['accion']))
{
	if($error==0)
	{
		if(empty($row['usuario']))
		{
			//Transformaciones previas
			$fecha=strftime("%Y-%m-%d",time());
			
	
			//////////////////////////
			$_POST['contrasena']=123456;
			$_POST['key_password']=123456;
			//EMCRIPTA LA CONTRASENA
			$_POST['contrasena']=md5($_POST['contrasena']);
			$_POST['usuario']=strtolower($_POST['usuario']);
			$sql=" INSERT INTO  usuarios (usuario,rut_empresa  ,contrasena  ,nombre  ,email  , cargo  ,depto  ,cod_bodega  ,tipo_usuario , 	estado_usuario,  Jefatura, Backup_Jefatura, usuario_ingreso, fecha_ingreso, cambio_password, key_password ,fecha_insert_fd)";
			$sql.=" VALUES('".$_POST['usuario']."','".$_SESSION['empresa']."','".$_POST['contrasena']."','".$_POST['nombre']."','".$_POST['email']."','".$_POST['cargo']."','".$_POST['depto']."','".$_POST['bodega']."'
			,'".$_POST['tipo_usuario']."' , '1','".$_POST['Jefatura']."','".$_POST['Backup_Jefatura']."','".$_SESSION['user']."','".$fecha."','".$_POST['cambio_password']."','".$_POST['key_password']."','".$fecha."')";
			$consulta=mysql_query($sql);
			if($consulta)
			$mensaje=" Ingreso Correcto ";
			$mostrar=1;
					
		}
		else
		{
			//Asignaciones y validaciones previas
			if($_POST['cambio_password']==1)
			$_POST['contrasena']=123456;
			
			$_POST['key_password']=$_POST['contrasena'];
			$_POST['contrasena']=md5($_POST['contrasena']);
			$fecha=strftime("%Y-%m-%d",time());
			
			
			$sql =" UPDATE usuarios SET ";
			$sql.="  contrasena='".$_POST['contrasena']."' ";
			$sql.=", nombre ='".$_POST['nombre']."' ";
			$sql.=", email ='".$_POST['email']."' ";
			$sql.=", cargo ='".$_POST['cargo']."' ";
			$sql.=", depto ='".$_POST['depto']."' ";
			$sql.=", cod_bodega ='".$_POST['bodega']."' ";
			$sql.=", tipo_usuario ='".$_POST['tipo_usuario']."' ";
			$sql.=", estado_usuario ='".$_POST['estado']."' ";
			$sql.=", Jefatura ='".$_POST['Jefatura']."' ";
			$sql.=", Backup_Jefatura ='".$_POST['Backup_Jefatura']."' ";
			$sql.=", usuario_ingreso ='".$_SESSION['user']."' ";
			$sql.=", fecha_ingreso ='".$fecha."'";
			$sql.=", cambio_password ='".$_POST['cambio_password']."'";
			$sql.=", key_password ='".$_POST['key_password']."'";
			$sql.="  WHERE usuario='".$_POST['usuario']."'";
			$consulta=mysql_query($sql);
			if($consulta)
			$mensaje=" Actualizacion Correcta ";
			$mostrar=1;
		}
	}
}
?>


    <?
	//Manejo de errores
	
    if($error==0)
	{
    echo "<div style=' width:100%; height:auto; border-top: solid 3px blue;border-bottom: solid 3px blue;color:blue; text-align:center; font-family:tahoma; font-size:18px;'>";
    echo $mensaje;
	echo "</div>";
	}
	else
	{
	echo "<div style=' width:100%; height:auto; border-top: solid 3px red ;border-bottom: solid 3px red; color:red; text-align:center;font-family:tahoma; font-size:18px;'>";
    echo $mensaje;
	echo "</div>";
	}
	?>
   



<style>
.fu
{
	background-color:#FFFFFF;
	color:rgb(0,0,0);
}
</style>
<?
if($mostrar==0)
{
?>
<form  action="?cat=2&sec=5&action=2&user=admin" method="POST" >
<div style="width:100%; height:auto; text-align:center;">	       <a href="?cat=2&sec=4"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Usuarios"></a></div>
    <table style="width:80%;" id="detalle-prov" cellpadding="3" cellspacing="4" border="0" style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;">
	
    <tr >
    
       <td id="titulo_tabla" style="text-align:center; " colspan="3">     

	   </td>
	</tr>
   
	
    <tr>
        <td colspan="1">
		    <label>Usuario:</label><label style="color:red">(*)</label><br />
		    <input  type="text" name="usuario" size="20" value="<? echo $_POST['usuario'];?>"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"
            <?
				if(!empty($row['usuario']))
				{
					echo "readonly";
				}
			?>
            >
		</td>
        <td colspan="2">
		    <label >Nombre:</label><label style="color:red">(*)</label><br />
			<input class="fu"  type="text" name="nombre" size="50" value="<? echo $_POST['nombre'] ?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
	    </td>
    </tr>
	
    <tr>
        <td >
		    <label>Email:</label><label style="color:red">(*)</label>
			<input class="fu" type="text" size="70" name="email" value="<? echo $_POST['email'] ?>" id="email"  style="background-color: rgb(255,255,255); border:1px solid #06F ; color:#000000;">
	    </td>
        <td>
		    <label>Cargo:</label><label style="color:red">(*)</label>
			<input class="fu" type="text" name="cargo" value="<? echo $_POST['cargo'] ?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
		</td>
    </tr>
	<tr>
            <td>
		    <label>Depto:</label><label style="color:red">(*)</label><br />
			<input class="fu" type="text" name="depto" value="<? echo $_POST['depto'] ?>" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">
		</td>
        <td>	
        	<?
			if(!empty($_GET['user']))
			{
        	 echo "<label >Reestablecer Password:</label><input  type='checkbox' name='cambio_password' value='1'"; 
            
			if(($_POST['cambio_password'])==1)
			echo " checked ";
            
            echo"/>";
			}?>
            <br />
        </td>
    </tr>
    <tr>
        <td >
		    <label>Bodega:</label><label style="color:red">(*)</label><br />
                <select name="bodega" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">
                    <option value=""  style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">---</option>
                    <? 
                        $sql_b ="SELECT * FROM bodegas WHERE rut_empresa = '".$_SESSION['empresa']."' ORDER BY descripcion_bodega";
                        $r_b = mysql_query($sql_b,$con);
                        if(mysql_num_rows($r_b)!=null)
						{
                            while($ro = mysql_fetch_assoc($r_b))
							{
                    ?>
                    <option value="<?=$ro['cod_bodega'];?>" 
					<? if($ro['cod_bodega']==$_POST['bodega'])
					{ 
						echo "SELECTED";
					} ?> 
                    style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;" ><?=$ro['descripcion_bodega'];?>
					</option>
                    <?
                            }
                        }
						else{
                            ?>
                <option style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">No Existen Datos</option>
                <?
                    }
                ?>
            </select>             
        </td>
		
        <td>
        	<?
        	  if(!empty($_GET['user']))
			  {
		    	
			    echo "<input type='hidden'  name='contrasena' value='".$_POST['contrasena']."' >";
			  }
			?>
		</td>
    </tr>
	
    <tr>
        <td>
		    <label>Tipo de Usuario:</label><label style="color:red">(*)</label> <br />
            <select name="tipo_usuario" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">
                <option value="0"> --- </option>
                <option value="1" <? if($_POST['tipo_usuario']==1){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Administrador</option>
                <option value="2" <? if($_POST['tipo_usuario']==2){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Operador</option>
                <option value="3" <? if($_POST['tipo_usuario']==3){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Administrador de Bodegas</option>
                <option value="4" <? if($_POST['tipo_usuario']==4){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Bodeguero</option>
                <option value="5" <? if($_POST['tipo_usuario']==5){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Auditor</option>
                <option value="6" <? if($_POST['tipo_usuario']==6){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Conductor</option>
                <option value="7" <? if($_POST['tipo_usuario']==7){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefatura</option>
                        
            </select>        
        </td>
        <td colspan="2">
        <?
		 
			if(!empty($_GET['user']))
			{
		    echo"<label>Estado del Usuario:</label><br /> ";
			echo"<select name='estado' style='background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;'>";
			echo "<option value='1' ";
		    if($_POST['estado']==1)
			{
				echo "SELECTED ";
			}?> 
            <?
            echo "style='background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;'>Habilitar</option>";
			echo "<option value='2'";
			if($_POST['estado']==2)
			{
				echo "SELECTED ";
			}
			echo "style='background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;'>Deshabilitar</option>";
			}?> 
            
			</select>
      		</td>
    </tr>
    
	<tr>
        <td>
		    <label>Jefatura:</label> <br />
            <select name="Jefatura" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">
                <option value="0"> --- </option>
                <option value="1" <? if($_POST['Jefatura'] == 1){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Dpto. Compras</option>
				<option value="2" <? if($_POST['Jefatura'] == 2){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Compras</option>
				<option value="3" <? if($_POST['Jefatura'] == 3){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Parque Maquinarias</option>
				<option value="4" <? if($_POST['Jefatura'] == 4){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Grupo Obras</option>
                <option value="5" <? if($_POST['Jefatura'] == 5){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Gerente General</option>   
                <option value="6" <? if($_POST['Jefatura'] == 6){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Administración</option>                
           
            </select>        
        </td>
        <td>
		    <label>Backup Jefatura:</label> <br />
		    <select name="Backup_Jefatura" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">
                <option value="0" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> --- </option>
                <option value="1" <? if($_POST['Backup_Jefatura'] == 1){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Dpto. Compras</option>
				<option value="2" <? if($_POST['Backup_Jefatura'] == 2){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Compras</option>
				<option value="3" <? if($_POST['Backup_Jefatura'] == 3){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Parque Maquinarias</option>
				<option value="4" <? if($_POST['Backup_Jefatura'] == 4){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Grupo Obras</option>
                <option value="5" <? if($_POST['Backup_Jefatura'] == 5){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Gerente General</option>                
           		<option value="6" <? if($_POST['Backup_Jefatura'] == 6){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Administración</option>
            </select>    
		</td>
		 <td colspan="2">
		</td>
		
    </tr>
    <tr>
    <td><b>Firma Digital</b> </td>
    </tr>
    <tr>
    <td>
    <? if(!empty($_POST['archivo_firma']))
	  {
		  echo "<input  type='text' name='archivo_firma' value='".$_POST['archivo_firma']."' readonly /><a onclick='mostrar()'>Mostrar</a> <a onclick='ocultar()'>Ocultar</a>";
		  echo "<br><br>";
		  echo "<div id='mostrar'>";
		  echo "<img src='firmas/".$_POST['archivo_firma']."' width='200px' height='200px'>";
		  echo "</div>";
	  }
	   else
	   echo "<label style='font-family:Tahoma;font-size:12px'>Usuario no tiene firma digital. Subir archivo en Editar Usuario.</label>";
    ?>
	</td>
    </tr>
	<tr>
        <td colspan="3" style="text-align: right;"></td>
    </tr>
     
</table>
<div style="width:90%; height:auto; text-align:right;"><button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
        <?
			if(empty($row['usuario']))
			{
				echo  " value='guardar' >Guardar</button><input name='bandera_guardar' type='hidden' value='1'>";
			}
			else
			{
				echo " value='editar' >Actualizar</button>";
			}
        ?>
        
         </div>
<input  type="hidden" name="primera" value="1"/>
</form>

<?
//validar solo update
?>
<form   action="?cat=2&sec=5&action=2&user=admin"  method="post" enctype="multipart/form-data" style=" text-align:center; background-color:#EEE">
  Adjuntar Firma Electronica:<input name="archivo" type="file" size="35" style=" background-color:#fff" />
  <input name="enviar" type="submit" value="Subir Archivo"  /><br>

  <input name="action" type="hidden" value="upload" />    
<br>
<table align='center' border='0'>
	<tr>
			<td colspan="2" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
				(*) Campos de Ingreso Obligatorio.
			</td>
	</tr>
</table>
</form>
<? }
else
{
	echo "<div style='width:100%; height:auto; text-align:center;'>	       <a href='?cat=2&sec=4'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Usuarios'></a></div>";
}?>