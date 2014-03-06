<script type="text/javascript">
function ValidaSoloNumeros() {
   if ((event.keyCode < 48) || (event.keyCode > 57)) 
      event.returnValue = false;
}
</script>
   <style>
    .foo
    {
    	border:1px solid #09F;
    	background-color:#FFFFFF;
    	color:#000066;
    	font-size:11px;
    	font-family:Tahoma, Geneva, sans-serif;
    	text-align:center;
    }
</style>

<?


 $mostrar=0;

if(!empty($_POST['accion'])){

    	$error=0;
    	
    	if(empty($_POST['fecha'])){
    		
    		$error=1;
    		$mensaje="Debe ingresar Fecha de entrada de Petroleo";

    	}else{

    		$fecha = explode('-', $_POST["fecha"]);
    		$_POST["anho"] = $fecha[0];
    		$_POST["mes"] = $fecha[1];
    		$_POST["dia"] = $fecha[2];

    		if($_GET["action"]==1){

    			$find = "SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION["empresa"]."' AND dia='".$_POST["dia"]."' AND mes='".$_POST["mes"]."' AND agno='".$_POST["anho"]."'  ";
    			$res_find = mysql_query($find);
    			$num = mysql_num_rows($res_find);

    			if($num>0){
    				$error=1;
    				$mensaje=" Ya existe una entrada de Petroleo para ese dia ";
    			}
    		}
    	}
    	if(empty($_POST['valor_ief'])){
    		$error=1;
    		$mensaje="Debe ingresar Valor de Impto. Especifico Unitario";
    	}
}

// Rescata los datos 
if(!empty($_GET['id_petroleo']) and empty($_POST['primera'])){

    	$fecha_edit = explode('-', $_GET["id_petroleo"]);
    	$dia= $fecha_edit[0]; 
    	$mes = $fecha_edit[1];
    	$anho  = $fecha_edit[2];

		//fecha con formato del $_POST
    	$fecha_edit = $anho."-".$mes."-".$dia;

    	$sql_dat = "SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION["empresa"]."' AND dia='".$dia."' AND mes='".$mes."' AND agno='".$anho."'";
    	$res_dat = mysql_query($sql_dat,$con);
    	$row_dat = mysql_fetch_array($res_dat);
    	

    	$_POST=$row_dat;
    	$_POST["fecha"] = $fecha_edit;
    	$_POST["anho"] = $row_dat["agno"];
    	$_POST["mes"] = $row_dat["mes"];
    	$_POST["dia"] = $row_dat["dia"];
    	$_POST["num_factura"] = $row_dat["num_factura"];
    	$_POST["valor_factura"] = $row_dat["valor_factura"];
    	$_POST["litros"] = $row_dat["litros"];
    	$_POST["valor_iev"] = number_format($row_dat["valor_IEV"], 4, ',', '.');
    	$_POST["valor_ief"] = number_format($row_dat["valor_IEF"], 4, ',', '.');
    	$_POST["total_ief"] = $row_dat["total_IEF"];

}


//Cosulta si el Entrada existe
if(empty($_POST['bandera_guardar']))
{
    	$sql = "SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION["empresa"]."' AND dia='".$dia."' AND mes='".$mes."' AND agno='".$anho."'";
    	$res = mysql_query($sql,$con);
    	$row = mysql_fetch_array($res);
    // $row=mysql_fetch_array($rec);
}


//Discrimina si guarda o edita
if(!empty($_POST['accion'])){

    	if($error==0){
    		$_POST["valor_iev"] = str_replace(",", ".", $_POST["valor_iev"]);
    		$_POST["valor_ief"] = str_replace(",", ".", $_POST["valor_ief"]);
    		$total_ief = $_POST["valor_ief"]*$_POST["litros"];
    		$_POST["total_ief"] = round($total_ief);

    		if($_POST['accion']=="guardar"){
    			$sql_ins = "INSERT INTO petroleo (dia, mes, agno, rut_empresa, num_factura, valor_factura, litros, valor_ief, valor_iev, total_ief, usuario_ingreso, fecha_ingreso) ";
    			$sql_ins.= "VALUE ('".$_POST["dia"]."', '".$_POST["mes"]."', '".$_POST["anho"]."', '".$_SESSION["empresa"]."', '".$_POST["num_factura"]."', ";
    			$sql_ins.= "'".$_POST["valor_factura"]."', '".$_POST["litros"]."', '".$_POST["valor_ief"]."', '".$_POST["valor_iev"]."', '".$_POST["total_ief"]."', '".$_SESSION['user']."' , '".date('Y-m-d H:i:s')."')";
			    $consulta = mysql_query($sql_ins,$con);
			    if($consulta)
			    	$mensaje=" Entrada de Petroleo Ingresada Satisfactoriamente ";
			    	$mostrar=1;


			    $consulta = "SELECT MAX(fecha_ingreso) as fecha_ingreso FROM petroleo WHERE rut_empresa='".$_SESSION["empresa"]."'";
                $resultado=mysql_query($consulta);
                $fila=mysql_fetch_array($resultado);
                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'petroleo', '".$fila["fecha_ingreso"]."', '2'";
                $sql_even.= ", 'INSERT:dia=".$_POST["dia"].", mes=".$_POST["mes"].", agno=".$_POST["anho"].", rut_empresa=".$_SESSION["empresa"].", num_factura=".$_POST["num_factura"].", valor_factura=".$_POST["valor_factura"].", litros=".$_POST["litros"].", valor_ief=".$_POST["valor_ief"].",";
                $sql_even.= " valor_iev=".$_POST["valor_iev"].", total_ief=".$_POST["total_ief"].", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d H:i:s')."', '".$_SERVER['REMOTE_ADDR']."', 'Insert', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);  

			}else{ 

				$sql_ins = "UPDATE petroleo SET num_factura='".$_POST["num_factura"]."', valor_factura='".$_POST["valor_factura"]."', litros='".$_POST["litros"]."', valor_ief='".$_POST["valor_ief"]."', total_ief='".$_POST["total_ief"]."' ";
				$sql_ins.= ", valor_iev='".$_POST["valor_iev"]."', total_ief='".$_POST["total_ief"]."' ";
				$sql_ins.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND dia='".$_POST["dia"]."' AND mes='".$_POST["mes"]."' AND agno='".$_POST["anho"]."'";
				$consulta=mysql_query($sql_ins);
				if($consulta)
					$mensaje="Entrada de Petroleo Actualizada Satisfactoriamente ";
					$mostrar=1;


				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'petroleo', '".$fila["fecha_ingreso"]."', '3'";
                $sql_even.= ", 'UPDATE:num_factura=".$_POST["num_factura"].", valor_factura=".$_POST["valor_factura"].", litros=".$_POST["litros"].", valor_ief=".$_POST["valor_ief"].", total_ief=".$_POST["total_ief"]." ";
				$sql_even.= ", valor_iev=".$_POST["valor_iev"].", total_ief=".$_POST["total_ief"]." ";
                $sql_even.= "','".$_SERVER['REMOTE_ADDR']."', 'Update', '1', '".date('Y-m-d H:i:s')."')";
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
?>

<?
// if($mostrar==0)
// {


?> 
<style>
    .fu
    {
        background-color:#FFFFFF;
        color:rgb(0,0,0);
    }

    .fo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:11px;
        font-family:Tahoma, Geneva, sans-serif;
        width:80%;
        text-align:center;
    }
</style>

	
<table  id="detalle-prov"  cellpadding="5" cellspacing="6" border="0" width="80%" align="center" >
		<tr>
			<td style="text-align:center;" colspan="3">  <a href="?cat=3&sec=13"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Facturas de Petroleo"></</a></td>
		</tr>
	</table>
	
<?php
    if($mostrar==0){
?>

<form action="?cat=3&sec=10&action=<?=$_GET["action"]; ?>" method="POST" >
	<table border="0" style="width:80%;border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif; ">
		<tr>
			<td  style="text-align:right"><label>Fecha Fac. Petroleo:</label><label style="color:red;">(*)</label></td>
			<td colspan="5"><input type="date" name="fecha" value='<? echo $_POST['fecha']; ?>'  <?if($_GET["action"]==2) echo 'disabled="true"';?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" /></td>
<?
				if ($_GET["action"]==2) echo "<input  type='hidden' name='fecha' value='".$_POST["fecha"]."'/>";
?>				
				<input  type="hidden" name="anho" value="<?=$_POST["anho"]?>"/>
				<input  type="hidden" name="mes" value="<?=$_POST["mes"]?>"/>
				<input  type="hidden" name="dia" value="<?=$_POST["dia"]?>"/>
		</tr>
		<tr>
				<td style="text-align:right"><label>Num. Factura:</label></td><td><input  class="foo" type="text" name="num_factura" value="<?=$_POST['num_factura'];?>"  onKeyPress="ValidaSoloNumeros()"></td>

				<td style="text-align:right"><label>Valor Factura:</label></td><td><input class="foo" type="text" name="valor_factura" value="<?=$_POST['valor_factura'];?>" onKeyPress="ValidaSoloNumeros()"></td>

				<td  style="text-align:right"><label>Litros:</label></td><td><input    class="foo" type="text" onFocus="sum(); this.value=''" onKeyUp="sum()" name="litros" value="<?=$_POST['litros'];?>" onKeyPress="ValidaSoloNumeros()" ></td>
		</tr>
		<tr>
				<td style="text-align:right"><label>Valor IEV:   </label></td><td><input class="foo"  type="text"  name="valor_iev"  value='<? echo $_POST['valor_iev']; ?>'  /></td>

				<td style="text-align:right"><label>Valor IEF:   </label><label style="color:red;">(*)</label></td><td><input class="foo"  type="text"  onFocus="sum(); this.value=''" onKeyUp="sum()" name="valor_ief"  value='<? echo $_POST['valor_ief']; ?>' > </td>

				<!-- <td style="text-align:right"><label>Total IEF:</label></td><td><input class="foo"  type="text"  onFocus="sum()" onKeyUp="sum()" name="total_ief"  value='<? echo $_POST['total_ief'];?>' readonly></td> -->
				<td style="text-align:right"><label>Total IEF:</label></td><td><input class="foo"  type="text"  name="total_ief"  value='<? echo $_POST['total_ief'];?>' readonly></td>
		</tr>
	</table>
	<br>
	<table border="0"  width="80%" align="center" >
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
<?
		// total_ief();
?>
</form>

	<?php
// var_dump($_POST);
}

	function total_ief($dec = 2) {
		$ceros = "";
		for ($d = 1; $d <= $dec; $d++) $ceros .= "0";
			$pot = "1".$ceros;
		$pot = round($pot, 0);

		echo "\n\n <script language='javascript' type='text/javascript'> \n\n";


		echo "function sum(input) { \n";

		echo "var ief = 0; \n";
		echo "var lit = 0; \n";

		echo "\n ief = eval(document.forms[0].valor_ief.value); \n";
		echo "\n lit = eval(document.forms[0].litros.value); \n";

		// echo "if (isNaN(ief))  document.forms[0].valor_ief.value = Math.round(0); \n";
		echo "if (eval(ief) < eval(0))  document.forms[0].valor_ief.value = Math.round(0); \n";

		// echo "if (isNaN(lit)) document.forms[0].litros.value = Math.round(0); \n";
		echo "if (eval(lit) < eval(0)) document.forms[0].litros.value = Math.round(0); \n";
   // echo "\n alert('entre'); \n";


		echo "\n total = eval(eval(lit)*eval(ief)); \n";
		echo "if (isNaN(total)) document.forms[0].total_ief.value = Math.round(0); \n";
		echo "if (eval(total) < eval(0)) document.forms[0].total_ief.value = Math.round(0); \n";

		echo "\n document.forms[0].total_ief.value = Math.round(total*".$pot.")/".$pot."; \n";

		echo "} \n\n";

		echo "\n</script> \n";
	}

	// var_dump($_POST); 
	?>
