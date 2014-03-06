<?php



//Validaciones
$mostrar=0;
if(!empty($_POST['accion']))
{
	$error=0;	
	$_POST['descripcion_bodega'] = trim($_POST['descripcion_bodega']);
	if(empty($_POST['descripcion_bodega']) /*OR $_POST['descripcion_bodega']=' '*/)
	{
		$error=1;
		$mensaje=" Debe ingresar la descripcion de la bodega   ";
	}	
}

if(!empty($_POST['accion']) and ($error==0))
{
	if(!empty($_POST['cod']))
	{
		$_POST['descripcion_bodega']=trim($_POST['descripcion_bodega']);
		$fecha=date("Y-m-d H:i:s");
		$update.=" UPDATE bodegas SET";
		$update.="  rut_empresa='".$_SESSION['empresa']."' ";
		$update.=", descripcion_bodega='".$_POST['descripcion_bodega']."' ";
		$update.=", usuario_ingreso='".$_SESSION['user']."' ";
		// $update.=", fecha_ingreso='".$fecha."'";
		//vcalidacion
		if(empty($_POST['primera']))
		$update.=" WHERE cod_bodega='".$_SESSION['cod']."'";
		else
		$update.=" WHERE cod_bodega='".$_POST['cod']."'";
		mysql_query($update);
		$mensaje=" Actualización Exitosa ";
		$mostrar=1;

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'bodegas', '".$_POST['cod']."', '3'";
        $sql_even.= ", 'UPDATE:rut_empresa=".$_SESSION['empresa'].",descripcion_bodega=".$_POST['descripcion_bodega'].",usuario_ingreso=".$_SESSION['user']."', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion a bodega', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
	}
	else
	{
		$_POST['descripcion_bodega']=trim($_POST['descripcion_bodega']);
		$fecha=date("Y-m-d H:i:s");
		$insert.="  INSERT INTO bodegas (rut_empresa,descripcion_bodega,usuario_ingreso,fecha_ingreso)";
		$insert.="  VALUES('".$_SESSION['empresa']."'";
		$insert.=" ,'".$_POST['descripcion_bodega']."'";
		$insert.=" ,'".$_SESSION['user']."'";
		$insert.=" ,'".$fecha."')";
		$consulta = mysql_query($insert); 
		if($consulta)
			$mensaje=" Ingreso Exitoso ";
			$mostrar=1;

		$consulta = "SELECT MAX(cod_bodega) as cod_bodega FROM bodegas WHERE rut_empresa='".$_SESSION["empresa"]."'";
        $resultado=mysql_query($consulta);
		$fila=mysql_fetch_array($resultado);

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'bodegas', '".$fila["cod_bodega"]."', '2'";
        $sql_even.= ", 'insert:rut_empresa=".$_SESSION['empresa'].",descripcion_bodega=".$_POST['descripcion_bodega'].",";
        $sql_even.= "usuario_ingreso=".$_SESSION['user'].",fecha_ingreso=".$fecha."', '".$_SERVER['REMOTE_ADDR']."', 'insercion a bodega', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
	}
}
   
//Rescata los datos
if(!empty($_GET['cod']) and empty($_POST['primera']))
{
	$sql="SELECT  * FROM  bodegas WHERE cod_bodega='".$_GET['cod']."' ";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$_POST=$row;	
}

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
<?
if($mostrar==0)
{
?>
<form action="?cat=3&sec=2&action=<?=$action; ?>" method="POST">
     <input  type="hidden" name="primera" value='1'/>
     <?
     	if(!empty($_POST['primera']))
		{
			echo"<input  type='hidden' name='cod' value='".$_POST['cod']."' />";
		}
		else
		{
			echo"<input  type='hidden' name='cod' value='".$_GET['cod']."' />";
		}
	 ?>
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr>
      <td id="titulo_tabla" style="text-align:center;"> 
      		<a href="?cat=3&sec=1"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Bodegas"></a>
      </td>
  	</tr>
    <tr>
        <td colspan="2"><label>Descripción:</label><label style="color:red;">(*)</label><br />
        	<textarea cols="110" rows="2" name="descripcion_bodega" style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F"><?=$_POST['descripcion_bodega'];?></textarea>
        </td>
    </tr>
    <tr>
       <td style="text-align: right;"  colspan="2"><input name="accion" type="submit" value="Grabar"  style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
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
	 echo "<a href='?cat=3&sec=1'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Bodegas'></a>	";
}
 ?>