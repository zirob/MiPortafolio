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
// Rescata los datos /////
// if(!empty($_GET['id_petroleo']) and empty($_POST['primera'])){

		
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
    	$_POST["fecha"] = date('d-m-Y', strtotime($_POST["fecha"]));
    	$_POST["anho"] = $row_dat["agno"];
    	$_POST["mes"] = $row_dat["mes"];
    	$_POST["dia"] = $row_dat["dia"];
    	$_POST["num_factura"] = $row_dat["num_factura"];
    	$_POST["valor_factura"] = $row_dat["valor_factura"];
    	$_POST["litros"] = $row_dat["litros"];
    	$_POST["valor_iev"] = $row_dat["valor_IEV"];
    	$_POST["valor_ief"] = $row_dat["valor_IEF"];
    	$_POST["total_ief"] = $row_dat["total_IEF"];

		////////////////////////////////////////////////////////////
    	///////////////////////// Calculos ///////////////////////// 
    	
    	// Destinación
    		// 1=Activo especifico=1 (recuperables)
    		// 2=Lugares Fisicos ó Activo=1 y especidico=2 (no recuperables)
    	$LitrosRec = 0;
    	$LitrosNoRec = 0;

    	$sql = "SELECT dt.*, sp.* ";
    	$sql.= "FROM detalles_productos dt, salida_petroleo sp ";
    	$sql.= "WHERE dt.cod_detalle_producto = sp.cod_detalle_producto AND dt.especifico='1' AND sp.tipo_salida='1' AND sp.dia='".$_POST["dia"]."' ";
    	$sql.= "AND sp.mes='".$_POST["mes"]."' AND sp.agno='".$_POST["anho"]."' ";
		$res = mysql_query($sql,$con);
    	while($row_ps = mysql_fetch_array($res)){

    			$LitrosRec = $LitrosRec + $row_ps["litros"];
    	}

    	$up_sumsp = "SELECT SUM(litros) AS sum_salida FROM salida_petroleo WHERE tipo_salida='2' AND  dia='".$row_dat["dia"]."' AND mes='".$row_dat["mes"]."' AND agno='".$row_dat["agno"]."'";
    	$res = mysql_query($up_sumsp,$con);
    	$rowpet_in = mysql_fetch_array($res);

    	$sql = "SELECT dt.*, sp.* ";
    	$sql.= "FROM detalles_productos dt, salida_petroleo sp ";
    	$sql.= "WHERE dt.cod_detalle_producto = sp.cod_detalle_producto AND dt.especifico='2' AND sp.tipo_salida='1' AND sp.dia='".$_POST["dia"]."' ";
    	$sql.= "AND sp.mes='".$_POST["mes"]."' AND sp.agno='".$_POST["anho"]."' ";
		$res = mysql_query($sql,$con);
    	while($row_ps = mysql_fetch_array($res)){
    			$LitrosNoRec = $LitrosNoRec + $row_ps["litros"];
    	}
    	$LitrosNoRec = $LitrosNoRec + $rowpet_in["sum_salida"];


    	$Tot_IERec = $LitrosRec * $row_dat["valor_IEF"];
    	$Tot_IENoRec = $LitrosNoRec * $row_dat["valor_IEF"];

    	// Total utilizado
    	$Tot_LitUti = $LitrosRec + $LitrosNoRec;
    	$Tot_Uti = $Tot_LitUti * $row_dat["valor_IEF"];

    	///////////// Saldo no Utilizado ///////////////
    	$sum_petin = 0;
    	$fecha_invertida = date('d-m-Y', strtotime($_POST["fecha"]));
    	$fecha_Factura = conv_ts($fecha_invertida);

    	// Suma de litros no utilizados
    	$sql = "SELECT * FROM petroleo WHERE  1=1 ORDER BY mes ASC";
    	$res = mysql_query($sql,$con);
    	while($row_in = mysql_fetch_array($res)){
    		
    		$fec_PetEntrada = $row_in["dia"]."-".$row_in["mes"]."-".$row_in["agno"]; 
    		$fec_PetEntrada_tiemstamp = conv_ts($fec_PetEntrada);

    		if($fec_PetEntrada_tiemstamp <= $fecha_Factura){
    			$sum_petin = $sum_petin + $row_in["litros"];
    		}

    		
    	}
    	
    	// Suma de litros  utilizados
    	$sql = "SELECT * FROM salida_petroleo WHERE  1=1 ORDER BY mes ASC";
    	$res = mysql_query($sql,$con);
    	while($row_out = mysql_fetch_array($res)){
    		
    		$fec_PetSalida = $row_out["dia"]."-".$row_out["mes"]."-".$row_out["agno"]; 
    		$fec_PetSalida_tiemstamp = conv_ts($fec_PetSalida);

			if($fec_PetSalida_tiemstamp <= $fecha_Factura){
    			$sum_petout = $sum_petout + $row_out["litros"];
    		}
    	}
    	$rowpet_out = mysql_fetch_array($res);

    	// saldo
    	$saldo_pet = $sum_petin - $sum_petout;
    	if($saldo_pet<0) $saldo_pet=0;
    	$valsaldo_pet = $saldo_pet * $row_dat["valor_IEF"];
    	// $tsfec_diant = conv_ts($_GET["id_petroleo"]);
    	
    	
    	
// }
?>
<form action="?cat=3&sec=10&action=<?=$action; ?>" method="POST">
	<table  id="detalle-prov"  cellpadding="5" cellspacing="6" border="0" width="80%" align="center">
		<tr>
			<td style="text-align:center;" colspan="3">  <a href="?cat=3&sec=13"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Facturas de Petroleo"></</a></td>
		</tr>
	</table>
	
	<table>

	</table>
	<table border="1"  width="80%" align="center" style="width:80%;border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;">
		<tr>
			<td  style="text-align:right"><label>Fecha Fac.:</label><label style="color:red;">(*)</label></td>
			<td  style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><? echo $_POST['fecha']; ?></td>
<?
			if(empty($_GET['id_petroleo'])){
				$fecha = explode('-', $_POST["fecha"]);
				$_POST["anho"] = $fecha[0];
				$_POST["mes"] = $fecha[1];
				$_POST["dia"] = $fecha[2];
			}else{
?>				
				<input type='hidden' name='fecha' value='<?=$_POST["fecha"];?>'>
<?
			}
?>

			<input  type="hidden" name="anho" value="<?=$_POST["anho"]?>"/>
			<input  type="hidden" name="mes" value="<?=$_POST["mes"]?>"/>
			<input  type="hidden" name="dia" value="<?=$_POST["dia"]?>"/>
		</tr>
		<tr>
			<td style="text-align:right"><label>Num. Factura: </label></td>
			<td  style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><? echo $_POST['num_factura'];?></td>

			<td style="text-align:right"><label>Valor Factura:</label></td>
			<td style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?echo "$".number_format($_POST['valor_factura'], 0, ',', '.');?></td>

			<td  style="text-align:right"><label>Litros:</label></td>
			<td style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?=number_format($_POST['litros'], 0, ',', '.');?></td>
		</tr>
		<tr>
			<td style="text-align:right"><label>Valor IEV:</label></td>
			<td style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?echo "$".number_format($_POST['valor_iev'], 4, ',', '.'); ?></td>

			<td style="text-align:right"><label>Valor IEF:</label><label style="color:red;">(*)</label></td>
			<td style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?echo "$".number_format($_POST['valor_ief'], 4, ',', '.'); ?></td>

			<td style="text-align:right"><label>Total IEF:</label></td>
			<td style='font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?echo "$".number_format($_POST['total_ief'], 0, ',', '.');?></td>
		</tr>
	</table>
	
	<br>
	
	<!-- Total utilizado -->
	<table  id="detalle-prov"  border="1" align="center" style="width:45%;border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;">
		<tr>
			<td colspan="4" style="text-align:center;background-color:rgb(0,0,255); color:rgb(255,255,255);"><label>Total Utilizado</label></td>

		</tr>
		<tr>
			<td style="text-align:right"><label>Litros:</label></td>
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;background-color: #fff;'><?=number_format($Tot_LitUti, 0, ',', '.');?></td>
			<td style="text-align:right"><label>Total IE:</label></td>
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;background-color: #fff;'><?echo "$".number_format($Tot_Uti, 0, ',', '.');?></td>
		</tr>
	</table>

	<br>
	
	<!-- Destinación -->
	<table  id="detalle-prov" border="1"  align="center" style="width:80%; border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;">
		<tr>
			<td  colspan='8' style="text-align:center;background-color:rgb(0,0,255); color:rgb(255,255,255);"><label>Destinación</label></td>

		</tr>
		<tr>
			<td colspan='4' style="text-align:center"><label>Procesos Productivos</label></td>
			<td colspan='4' style="text-align:center"><label>Vehiculos Transporte y/o ops. no afectas IVA</label></td>
		</tr>
		<tr>
			<td style='text-align:right; font-family:tahoma;font-size:12px;font-weight:bold;'>Litros:</td>				
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;background-color: #fff;'><?=number_format($LitrosRec, 0, ',', '.');?></td>
			<td style='text-align:right; font-family:tahoma;font-size:12px;font-weight:bold;'>IE. Recuperable:</td>		
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;background-color: #fff;'><?echo "$".number_format($Tot_IERec, 0, ',', '.');?></td>
			<td style='text-align:right; font-family:tahoma;font-size:12px;font-weight:bold;'>Litros:</td>				
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;background-color: #fff;'><?=number_format($LitrosNoRec, 0, ',', '.');?></td>
			<td style='text-align:right; font-family:tahoma;font-size:12px;font-weight:bold;'>IE. NO Recuperable:</td>	
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;background-color: #fff;'><?echo "$".number_format($Tot_IENoRec, 0, ',', '.');?></td>
		</tr>
	</table>
	<br>

	<table  id="detalle-prov"  border="1" align="center" style="width:45%;border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;">
		<tr>
			<td colspan="4" style="text-align:center;background-color:rgb(0,0,255); color:rgb(255,255,255);"><label>Saldo no Utilizado</label></td>

		</tr>
		<tr>
			<td style='text-align:right; font-family:tahoma;font-size:12px;font-weight:bold;'><label>Litros:</label></td>
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?=number_format($saldo_pet, 0, ',', '.');?></td>
			<td style='text-align:right; font-family:tahoma;font-size:12px;font-weight:bold;'><label>Impuesto Espec:</label></td>
			<td style='text-align:left;font-family:tahoma;font-size:12px;font-weight:normal;background-color: #fff;'><?echo "$".number_format($valsaldo_pet, 0, ',', '.');?></td>
		</tr>
	</table>
</form>

<?
// Actualiza datos de petroleo
$up_date = "UPDATE petroleo SET  utilizado_litros='".$Tot_LitUti."', utilizado_total_ie='".$Tot_Uti."', destinacion_pp_litros='".$LitrosRec."', destinacion_pp_ie_recuperable='".$Tot_IERec."', destinacion_vt_litros='".$LitrosNoRec."'";
$up_date.= ", destinacion_vt_ie_no_Recuperable='".$Tot_IENoRec."', saldo_litros='".$saldo_pet."', saldo_impto_especifico='".$valsaldo_pet."' ";
$up_date.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND dia='".$_POST["dia"]."' AND mes='".$_POST["mes"]."' AND agno='".$_POST["anho"]."'";
$consulta = mysql_query($up_date,$con);

$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
$sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
$sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'petroleo', '".$_POST["dia"]."-".$_POST["mes"]."-".$_POST["anho"]."', '3'";
$sql_even.= ", 'UPDATE:";
$sql_even.= "utilizado_litros=".$Tot_LitUti."', utilizado_total_ie=".$Tot_Uti.", destinacion_pp_litros=".$LitrosRec.", destinacion_pp_ie_recuperable=".$Tot_IERec.", destinacion_vt_litros=".$LitrosNoRec."";
$sql_even.= ", destinacion_vt_ie_no_Recuperable=".$Tot_IENoRec.", saldo_litros=".$saldo_pet.", saldo_impto_especifico=".$valsaldo_pet." ";
$sql_even.= "', '".$_SERVER['REMOTE_ADDR']."', 'Update', '1', '".date('Y-m-d H:i:s')."')";
mysql_query($sql_even, $con);  


function conv_ts($fecha_dd_mm_aaaa) {
	if (empty($fecha_dd_mm_aaaa)) $fecha_dd_mm_aaaa = time();
	list($d, $m, $a) = explode("-",$fecha_dd_mm_aaaa);
	return mktime(0, 0, 0, $m, $d, $a);
}
?>

	