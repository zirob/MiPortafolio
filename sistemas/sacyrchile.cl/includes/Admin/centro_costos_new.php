<?php
//Validaciones
$mostrar=0;
if(!empty($_POST['procesar']))
{
	$error=0;
	if(empty($_POST['codigo_cc']))
	{
		$error=1;
		$mensaje=" Debe ingresar el codigo del centro costo ";
	}
	else
	{
		if(empty($_GET['id_cc']))
		{
			$sql="SELECT  * FROM  centros_costos WHERE codigo_cc='".$_POST['codigo_cc']."' and  rut_empresa='".$_SESSION['empresa']."'";
			$rec=mysql_query($sql);
			$row=mysql_fetch_array($rec);
			$num=mysql_num_rows($rec);
			if($num>0)
			{
						$error=1;
						$mensaje=" Ya existe  el codigo para otro centro de costo ";
			}
		}
	}
	
	if(empty($_POST['descripcion_cc']))
	{
		$error=1;
		$mensaje=" Debe ingresar la descripcion del centro de costo  ";
	}	
}

if(!empty($_POST['procesar']) and ($error==0))
{

	if(!empty($_GET['id_cc']))
	{
		$fecha=date("Y-m-d H:i:s");
		$sql.="  UPDATE centros_costos SET";
		$sql.="  rut_empresa='".$_SESSION['empresa']."'";
		$sql.=", codigo_cc='".$_POST['codigo_cc']."'";
		$sql.=", descripcion_cc='".$_POST['descripcion_cc']."'";
		//$sql.=", usuario_ingreso='".$_SESSION['user']."'";
		//$sql.=", fecha_ingreso='".$fecha."'";
		$sql.="  WHERE id_cc='".$_GET['id_cc']."' and  rut_empresa='".$_SESSION['empresa']."' ";
		mysql_query($sql);
		$mensaje=" Actualizacion Exitosa ";
		$mostrar=1;

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'centros_costos', '".$_GET['id_cc']."', '3'";
        $sql_even.= ", 'UPDATE:rut_empresa=".$_SESSION['empresa'].",codigo_cc=".$_POST['codigo_cc'].",descripcion_cc=".$_POST['descripcion_cc']."', '".$_SERVER['REMOTE_ADDR']."', 'Actualizacion de centro de costos', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);

	}
	else
	{

		$fecha=date("Y-m-d H:i:s");
		$sql ="  INSERT INTO centros_costos (rut_empresa,codigo_cc,descripcion_cc,usuario_ingreso,fecha_ingreso)";
		$sql.="  VALUES('".$_SESSION['empresa']."'";
		$sql.=" ,'".$_POST['codigo_cc']."'";
		$sql.=" ,'".$_POST['descripcion_cc']."'";
		$sql.=" ,'".$_SESSION['user']."'";
		$sql.=" ,'".$fecha."')";
		mysql_query($sql);
		$mensaje=" Ingreso Correcto ";
		$mostrar=1;

		$sel = "SELECT MAX(id_cc) as id_cc FROM centros_costos WHERE rut_empresa='".$_SESSION["empresa"]."'";
        $rec=mysql_query($sel);
		$row_cc=mysql_fetch_array($rec);

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'centros_costos', '".$row_cc['id_cc']."', '2'";
        $sql_even.= ", 'INSERT: usuario=".$_SESSION['user'].", rut_empresa=".$_SESSION['empresa'].",codigo_cc=".$_POST['codigo_cc'].",descripcion_cc=".$_POST['descripcion_cc']."', '".$_SERVER['REMOTE_ADDR']."', 'Insercion de centro de costos', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
	}
}
   
//Rescata los datos
if(!empty($_GET['id_cc']) and empty($_POST['primera']))
{
	$sql="SELECT  * FROM  centros_costos WHERE Id_cc='".$_GET['id_cc']."' ";
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
<form action="?cat=2&sec=13&id_cc=<? echo $_GET['id_cc']; ?>" method="POST">
     <input  type="hidden" name="primera" value='1'/>
     <?
     	if(!empty($_POST['primera']))
		{
			echo"<input  type='hidden' name='id_cc' value='".$_POST['id_cc']."' />";
		}
		else
		{
			echo"<input  type='hidden' name='id_cc' value='".$_GET['id_cc']."' />";
		}
	 ?>
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;"> </td>
        <td id="list_link" ><a href="?cat=2&sec=12"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al listado de Centros de Costos"></</a></td></tr>
   
    <tr>
        <td  colspan="2"><label>C&oacute;digo:</label><label style="color:#FF0000;">(*)</label><br /><input class="fu nume" type="text" name="codigo_cc" size="20" value="<? echo $_POST['codigo_cc'] ?>"  style="background-color:rgb(255,255,255); color:#000000; border:1px solid #00F"
        <?
		if(!empty($_GET['id_cc']))
		echo " readonly ";
        ?>
        ></td>
    </tr>
    <tr>
        <td  colspan="2"><label>Descripcion:</label><label style="color:#FF0000;color:red; " >(*)</label><br /><textarea cols="110" rows="2" name="descripcion_cc" style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F"><? echo $_POST['descripcion_cc']?></textarea></td>
    </tr>
    <tr>
        <td  colspan="2" style="text-align: right;"><input name="procesar" type="submit" value="procesar"  style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr>
    <td align="center" colspan="100%;" style="color:red; font-weight:bold;">
    	(*) Campos de Ingreso Obligatorio.
    </td>
    </tr>
</table>
</form>
<? } 
else
{?>
<table width="100%">
<tr> <td id="list_link"  align="right";><a href="?cat=2&sec=12"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al listado de Centros de Costos"></</a></td></tr>
</table>
<? }?>
