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

<?
$mostrar=0;


if(!empty($_POST['procesar']))
{
	$_POST['permisos']="";
	for($i=1;$i<=20;$i++)
	{
		$_POST['permisos'].=$_POST[$i];
	}
	$sql=" UPDATE usuarios SET permisos='".$_POST['permisos']."' WHERE  usuario='".$_GET['user']."'";
	mysql_query($sql);
	$mostrar=1;
	$mensaje=" Actualización Correcta de Permisos ";

	
	$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'usuarios', '".$_POST["rut"]."', '3'";
    $sql_even.= ", 'UPDATE: permisos=".$_POST['permisos']."'";
    $sql_even.= ", '".$_SERVER['REMOTE_ADDR']."', 'actualización proveedores', '1', '".date('Y-m-d H:i:s')."') ";
    mysql_query($sql_even, $con);
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
	
	//Calculo de permisos iniciales
	for($i=1;$i<=20;$i++)
	{
		$_POST[$i]=substr($_POST['permisos'],$i-1,1);
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

#detalle-prov tr td 
{
font-family:Tahoma; font-size:12px;
}
</style>
<?
if($mostrar==0)
{
	?>
	<form  action="?cat=2&sec=99&action=2&user=<? echo $_GET['user'];  ?>"  method="POST" >
		<input type='hidden' name='primera' value='1'>
		<div style="width:100%; height:auto; text-align:center;">	       <a href="?cat=2&sec=4"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Usuarios"></a></div>
		<table  id="detalle-prov" cellpadding="3" cellspacing="4" border="1" style="margin:0 auto; margin-top:30px; font-family:Tahoma, Geneva, sans-serif; border-colapse:colapse;width:60%">

			<tr>
				<td colspan="1">
					<label>Usuario:</label>
					<input  type="text" name="usuario" size="20" value="<? echo $_POST['usuario'];?>"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"
					<?
					if(!empty($row['usuario']))
					{
						echo "readonly";
					}
					?>
					
				</td>
			</tr>
	
			<tr style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
				<td  align='center'> Modulo</td>
				<td align='center'> Bloqueado</td>
				<td align='center'> Acceso</td>

			</tr>
			<tr><td>Usuarios:</td>
				<td align='center'><input type='radio' name='1' value='0' <? if($_POST['1']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='1' value='1' <? if($_POST['1']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Centros Costo:</td>
				<td align='center'><input type='radio' name='2' value='0' <? if($_POST['2']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='2' value='1' <? if($_POST['2']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>bodega:</td>
				<td align='center'><input type='radio' name='3' value='0' <? if($_POST['3']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='3' value='1' <? if($_POST['3']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Lugares Fisicos :</td>
				<td align='center'><input type='radio' name='4' value='0' <? if($_POST['4']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='4' value='1' <? if($_POST['4']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Plantas :</td>
				<td align='center'><input type='radio' name='20' value='0' <? if($_POST['20']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='20' value='1' <? if($_POST['20']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Solictud de Compra:</td>
				<td align='center'><input type='radio' name='5' value='0' <? if($_POST['5']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='5' value='1' <? if($_POST['5']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Ordenes de Compra:</td>
				<td align='center'><input type='radio' name='6' value='0' <? if($_POST['6']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='6' value='1' <? if($_POST['6']==1) echo "checked" ;?>></td>
			</tr>
			
			<tr><td>Proveedores:</td>
				<td align='center'><input type='radio' name='7' value='0' <? if($_POST['7']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='7' value='1' <? if($_POST['7']==1) echo "checked" ;?>></td>
			</tr>

			<tr><td>Petróleo Entrada:</td>
				<td align='center'><input type='radio' name='8' value='0' <? if($_POST['8']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='8' value='1' <? if($_POST['8']==1) echo "checked" ;?>></td>
			</tr>

			<tr><td>Petróleo Salida:</td>
				<td align='center'><input type='radio' name='9' value='0' <? if($_POST['9']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='9' value='1' <? if($_POST['9']==1) echo "checked" ;?>></td>
			</tr>

			<tr><td>Activos:</td>
				<td align='center'><input type='radio' name='10' value='0' <? if($_POST['10']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='10' value='1' <? if($_POST['10']==1) echo "checked" ;?>></td>
			</tr>

			<tr><td>Productos:</td>
				<td align='center'><input type='radio' name='11' value='0' <? if($_POST['11']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='11' value='1' <? if($_POST['11']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Pre-Inventario:</td>
				<td align='center'><input type='radio' name='12' value='0' <? if($_POST['12']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='12' value='1' <? if($_POST['12']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Ordenes de Trabajo:</td>
				<td align='center'><input type='radio' name='13' value='0' <? if($_POST['13']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='13' value='1' <? if($_POST['13']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Reporte Orden de Compra:</td>
				<td align='center'><input type='radio' name='14' value='0' <? if($_POST['14']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='14' value='1' <? if($_POST['14']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Reporte Orden de trabajo:</td>
				<td align='center'><input type='radio' name='15' value='0' <? if($_POST['15']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='15' value='1' <? if($_POST['15']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Reporte Entrada Petróleo:</td>
				<td align='center'><input type='radio' name='16' value='0' <? if($_POST['16']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='16' value='1' <? if($_POST['16']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Reporte Salida Petróleo:</td>
				<td align='center'><input type='radio' name='17' value='0' <? if($_POST['17']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='17' value='1' <? if($_POST['17']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Reporte de Productos:</td>
				<td align='center'><input type='radio' name='18' value='0' <? if($_POST['18']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='18' value='1' <? if($_POST['18']==1) echo "checked" ;?>></td>
			</tr>
			<tr><td>Reporte de Activos:</td>
				<td align='center'><input type='radio' name='19' value='0' <? if($_POST['19']==0) echo "checked" ;?>></td>
				<td align='center'><input type='radio' name='19' value='1' <? if($_POST['19']==1) echo "checked" ;?>></td>
			</tr>
			<tr>
				<td colspan='100%' align='center'>
				<button    name='procesar' value='1' type="submit" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;">Procesar</button>
		
				</td>
			</tr>

		</table>   
		<!-- </div> -->
	</form>
	<?
}
else
{
	echo "<div style='width:100%; height:auto; text-align:center;'>	       <a href='?cat=2&sec=4'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Usuarios'></a></div>";
}
?>
