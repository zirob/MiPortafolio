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
// var_dump($_POST);
//Validacionesi


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
			$sql3="SELECT  * FROM  usuarios WHERE usuario='".$_POST['usuario']."' AND rut_empresa='".$_SESSION['empresa']."'";
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
	else
	{
		$validador=comprobar_email($_POST['email']);
		if($validador!=1)
		{
					$error=1;
					$mensaje=" Error en el formato del email  ";
		}
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


	$nombre_archivo = $_FILES['archivo']['name']; 
	$tipo_archivo = $_FILES['archivo']['type']; 
	$tamano_archivo = $_FILES['archivo']['size'];


	if(!(strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "png")))
	{
		$error=1;
		$mensaje = "La extensión no es correcta";
	}elseif($tamano_archivo > 100000){
		$error=1;
		$mensaje = "Tamaño del archivo no es correcta.";
	}else{
		$destino="firmas/".$_FILES['archivo']['name'];
		$fecha=date("Y-m-d H:i:s");
		$up_firma.= "UPDATE usuarios SET";
		$up_firma.= " nombre_arch_fd='".$_FILES['archivo']['name']."'";
		$up_firma.= ", ruta_arch_fd='".$destino."'";
		$up_firma.= ", ext_arch_fd='".$_FILES['archivo']['type']."'";
		$up_firma.= ", user_insert_fd='".$_SESSION['user']."'";
		$up_firma.= ", fecha_insert_fd='".$fecha."'";
		$up_firma.= " WHERE usuario='".$_GET['user']."' AND rut_empresa='".$_SESSION['empresa']."'";
		mysql_query($up_firma);	
		move_uploaded_file($_FILES['archivo']['tmp_name'],$destino);
		$mensaje = "Archivo subido";

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'usuarios', 'usuario', '3'";
        $sql_even.= ", 'UPDATE: nombre_arch_fd=".$_FILES['archivo']['name'].",ruta_arch_fd=".$destino."";
        $sql_even.= ", user_insert_fd=".$_SESSION['user'].", fecha_insert_fd=".$fecha."'";
        $sql_even.= ", '".$_SERVER['REMOTE_ADDR']."', 'actualizacion de firma', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
	}



}


//Rescata los datos
if(!empty($_GET['user']) and empty($_POST['primera']))
{
	$sql="SELECT  * FROM  usuarios WHERE usuario='".$_GET['user']."' and rut_empresa='".$_SESSION['empresa']."'";
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
	$sql="SELECT  * FROM  usuarios WHERE usuario='".$_POST['usuario']."' AND rut_empresa='".$_SESSION['empresa']."' ";
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
			$fecha=date("Y-m-d H:i:s");
			

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
				$mensaje=" Ingreso Correcto-Clave de Acceso:123456";
			$mostrar=1;

			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'usuarios', '', '2'";
	        $sql_even.= ", 'INSERT: usuario=".$_POST['usuario'].",rut_empresa=".$_SESSION['empresa'].",contrasena=".$_POST['contrasena']."";
	        $sql_even.= ",nombre=".$_POST['nombre']." ,email=".$_POST['email']." , cargo=".$_POST['cargo']."  ,depto=".$_POST['depto']." ";
	        $sql_even.= ",cod_bodega=".$_POST['bodega']." ,tipo_usuario=".$_POST['tipo_usuario']." , estado_usuario=1,  Jefatura=".$_POST['Jefatura']."";
	        $sql_even.= ", Backup_Jefatura=".$_POST['Backup_Jefatura'].", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".$fecha.", cambio_password=".$_POST['cambio_password'].", key_password=".$_POST['key_password']." ,fecha_insert_fd=".$fecha."', '".$_SERVER['REMOTE_ADDR']."', 'insert de datos', '1', '".date('Y-m-d H:i:s')."')";
	        mysql_query($sql_even, $con);

		}
		else
		{
				//Asignaciones y validaciones previas
				if($_POST['cambio_password']==1)
					$_POST['contrasena']=123456;

				$_POST['key_password']=$_POST['contrasena'];
				$_POST['contrasena']=md5($_POST['contrasena']);
				$fecha=date("Y-m-d H:i:s");


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
				$sql.=", cambio_password ='".$_POST['cambio_password']."'";
				$sql.=", key_password ='".$_POST['key_password']."'";
				$sql.="  WHERE usuario='".$_POST['usuario']."' AND rut_empresa='".$_SESSION['empresa']."'";
				$consulta=mysql_query($sql);
				if($consulta)
					$mensaje=" Actualizacion Correcta ";
				$mostrar=1;


				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
		        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
		        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'usuarios', '', '3'";
		        $sql_even.= ", 'UPDATE: contrasena=".$_POST['contrasena']." ,nombre=".$_POST['nombre']." ,email=".$_POST['email']." ";
		        $sql_even.= ", cargo=".$_POST['cargo']." ,depto=".$_POST['depto']." ,cod_bodega=".$_POST['bodega']." ,tipo_usuario=".$_POST['tipo_usuario']."";
		        $sql_even.= ",	estado_usuario=".$_POST['estado'].",  Jefatura=".$_POST['Jefatura']." , Backup_Jefatura=".$_POST['Backup_Jefatura']."";
		        $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", cambio_password=".$_POST['cambio_password'].", key_password=".$_POST['key_password']."'";
		        $sql_even.= ", '".$_SERVER['REMOTE_ADDR']."', 'update de datos', '1', '".date('Y-m-d H:i:s')."')";
		        mysql_query($sql_even, $con);
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
	<form  action="?cat=2&sec=5&action=2&user=<? echo $_GET['user'];  ?>"  method="POST" >
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
					<option value="3" <? if($_POST['Jefatura'] == 3){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Administración</option>                
					<option value="4" <? if($_POST['Jefatura'] == 4){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Parque Maquinarias</option>
					<option value="5" <? if($_POST['Jefatura'] == 5){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Grupo Obras</option>
					<option value="6" <? if($_POST['Jefatura'] == 6){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Gerente General</option>   

				</select>        
			</td>
			<td>
				<!-- Backup_Jefatura -->
				<label>Backup Jefatura:</label> <br />
				<select name="Backup_Jefatura" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;">
					<option value="0" style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> --- </option>
					<option value="1" <? if($_POST['Backup_Jefatura'] == 1){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Dpto. Compras</option>
					<option value="2" <? if($_POST['Backup_Jefatura'] == 2){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Compras</option>
					<option value="3" <? if($_POST['Backup_Jefatura'] == 3){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Administración</option>                
					<option value="4" <? if($_POST['Backup_Jefatura'] == 4){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Parque Maquinarias</option>
					<option value="5" <? if($_POST['Backup_Jefatura'] == 5){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Grupo Obras</option>
					<option value="6" <? if($_POST['Backup_Jefatura'] == 6){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Gerente General</option>   
				</select>    
			</td>
			<td colspan="2">
			</td>

		</tr>
		<tr>
			<td>	
				<label>Firma Digital</label>
			</td>
		</tr>
		<tr>
			<td>
				<? if(!empty($_POST['archivo_firma']))
				{
					echo "<input  type='text' name='archivo_firma' value='".$_POST['archivo_firma']."' readonly /><input type='button' onclick='mostrar()' value='Mostrar' title='Mostrar Firma' /> <input type='button' onclick='ocultar()' value='Ocultar' title='Ocultar Firma' />";
					echo "<br><br>";
					echo "<div id='mostrar'>";
					echo "<img src='firmas/".$_POST['archivo_firma']."' width='200px' height='200px'>";
					echo "</div>";
				}
				else{
					if(isset($_GET['user'])){
						echo "<label style='font-family:tahoma;font-size:12px;font-weight:normal;'>Usuario sin firma.</label>";					
					}else{
						echo "<label style='font-family:tahoma;font-size:12px;font-weight:normal;'>Usuario sin firma. Subir firma en la opcion Editar Usuario.</label>";
					}
				}
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
if(!empty($_GET['user'])){
	?>
	<form   action="?cat=2&sec=5&action=2&user=<? echo $_GET['user']; ?>"  method="post" enctype="multipart/form-data" style=" text-align:center; background-color:#EEE">
		<label style='font-family:tahoma;font-size:12px;font-weight:normal;'>Adjuntar Firma Electronica:</label><input name="archivo" type="file" size="35" style=" background-color:#fff" />
		<input name="enviar" type="submit" value="Subir Archivo"  /><br>

		<input name="action" type="hidden" value="upload" /> 

		<div>
		<table border='0' align='center' width='25%' style='font-family:tahoma;font-weight:light;text-align:Center;font-size:11px'><tr><td><li>Se permiten archivos .gif, .jpg y .png<br><li>se permiten archivos de 100 Kb máximo.</td></tr></table>
		<table align='center' border='0'>
			<br>
			<tr>

			</tr>
		</table>   
		</div
	></form>
	<?
}
?>
<table border='0' align='left' width='100%' style='font-family:tahoma;font-weight:light;text-align:left;font-size:11px'><tr>
				<td colspan="100%" style='text-align:center;tfont-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
					(*) Campos de Ingreso Obligatorio.
				</td>
                </tr>
                </table>
<? }
else
{
	echo "<div style='width:100%; height:auto; text-align:center;'>	       <a href='?cat=2&sec=4'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Usuarios'></a></div>";
}?>
