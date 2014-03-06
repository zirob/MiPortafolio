<?
if(!empty($_POST['accion'])){

	$error=0;
    if(empty($_POST['cantidad'])){
    		$error=1;
    		$mensaje="Debe ingresar Cantidad  del Detalle";
    }
    if(empty($_POST['estado_det_pi'])){
    		$error=1;
    		$mensaje="Debe ingresar Valor el Estado del Detalleº";
    }
}

// recarga datos
if(empty($_POST['primera'])){
	$sql = "SELECT * FROM preinventario_detalle WHERE id_det_pi='".$_GET["codbar"]."'";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$_POST = $row;
}


//Discrimina si guarda o edita
if(!empty($_POST['accion'])){

    	if($error==0){
 			$up = "UPDATE preinventario_detalle SET ";
 			$up.= "cantidad='".$_POST["cantidad"]."', estado_det_pi='".$_POST["estado_det_pi"]."' ";
 			$up.= "WHERE id_det_pi='".$_GET["codbar"]."' ";
 			$consulta=mysql_query($up);
					if($consulta)
						$mensaje="Detalle de Preinventario Actualizado Satisfactoriamente ";
						$mostrar=1;
    	}

    	$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'preinventario_detalle', '".$_GET["codbar"]."', '3'";
        $sql_even.= ", 'UPDATE: cantidad=".$_POST["cantidad"].", estado_det_pi=".$_POST["estado_det_pi"]."', usuario_ingreso=".$_SESSION["user"].", fecha_ingreso=".date('Y-m-d H:i:s')."', ";
        $sql_even.= "'".$_SERVER['REMOTE_ADDR']."', 'Update en preinventario_detalle', '1', '".date('Y-m-d H:i:s')."') ";
        mysql_query($sql_even, $con);  
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
?>


<table id="list_registros" border='0'>
	<tr>
		<td id="titulo_tabla" style="text-align:center;" colspan="7"> </td>
		<td id="list_link">
			<a href="?cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>"><img src="img/view_previous.png" width="30px" height="30px" border="0" class="toolTIP" title="Volver a Pre-Inventario Detalles"></a>
		</td>
	</tr>
</table> 


<style>
a:hover{
	text-decoration:none;
}

.fo
{
	border:1px solid #09F;
	background-color:#FFFFFF;
	color:#000066;
	font-size:12px;
	font-family:Tahoma, Geneva, sans-serif;
	width:80%;
	text-align:center;
}
</style> 
<?php
    if($mostrar==0){
?>
<form action="?cat=3&sec=41&action=<?=$_GET["action"]; ?>&id_pi=<?=$_GET["id_pi"];?>&codbar=<?=$_GET['codbar']?>" method="POST" >

	<table style="width:80%;border-collapse:collapse; font-size:12px; text-align:left;" style="font-family:Tahoma, Geneva, sans-serif;" align='center'  border="0" cellpadding="3" cellspacing="3" >
		<tr>
			<td style="font-family:Tahoma, Geneva, sans-serif;"><label><b>Codigo de Barra:</label><label style="color:red;">(*)</label></b><br><input  class="fo" type="text" name="codigobarras" value="<?=$_POST['codigobarras'];?>"  onKeyPress="ValidaSoloNumeros() " readonly></td>

			<td style="font-family:Tahoma, Geneva, sans-serif;"><label><b>Cantidad:</label><label style="color:red;">(*)</label></b><br><input class="fo" type="text" name="cantidad" value="<?=$_POST['cantidad'];?>" onKeyPress="ValidaSoloNumeros()"></td>

			<td  style="font-family:Tahoma, Geneva, sans-serif;"><label><b>Estado:</label><label style="color:red;">(*)</label></b><br>
					<select name="estado_det_pi"  class='fo' style="width:80%;">
		                <option value="0" <? if($_POST['estado_det_pi']==0) echo " selected "; ?> >---</option>  
		                <option value="1" <? if($_POST['estado_det_pi']==1){ echo " selected ";} ?> >Habilitado</option>
		                <option value="2" <? if($_POST['estado_det_pi']==2){ echo " selected ";} ?> >Deshabilitado</option>
             		</select>
			</td>
		</tr>
		<tr>
			<td></td>
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


	</table>	
</form>
<?}	?>