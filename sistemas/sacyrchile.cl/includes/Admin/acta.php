<style>
.fo
{
	border:1px solid #09F;
	background-color:#FFFFFF;
	color:#000066;
	font-size:11px;
	font-family:Tahoma, Geneva, sans-serif;
	width:80%;
	text-align:left;
}

td
{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
}

</style>
<script type="text/javascript">
function imprSelec(muestra)
{
    $("#boton_im").css("display" ,'none');
    $("#volver").css("display" ,'none');
    var ficha=document.getElementById(muestra);var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);ventimp.document.close();ventimp.print();ventimp.close();
}
</script>
<?
$sql = "SELECT * FROM cabeceras_ot WHERE id_ot=".$_GET["id_ot"]."";
$res = mysql_query($sql,$con);
$row = mysql_fetch_assoc($res);

$sql2 = "SELECT * FROM detalles_productos WHERE cod_detalle_producto='".$row["cod_detalle_producto"]."'";
$res2 = mysql_query($sql2,$con);
$row2 = mysql_fetch_assoc($res2);
?>
<table style="width:1000px;" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2"> <a id='volver' href="?cat=2&sec=17"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Ordenes de Compra"></a></td></tr>
    
    <tr>
</table>
<form action="?cat=2&sec=16&action=<?=$action?>" method="POST" name='f1' id="f1" >
	<input  type='hidden' name='primera' value='1'/>
	<div style="width:100%; height:auto; ">
		<table align="center" style="width:900px;" cellpadding="3" cellspacing="4" border="0">
			<tr id="cabecera_1" style="height: 100px;">
				<td  width="150px" style="text-align: left;"><img src="img/sacyr.png" border="0" width="150px"></td>
				<td  colspan="2" width="300px" style="text-align: center;">
					<span style=" font-weight:normal; font-size:14px;">SACYR CHILE S.A. Parque Maquinarias</span><br>
					<span style=" font-weight:bold; font-size:19px;">Acta de Recepción / Entrega de Vehículos Menores</span>
				</td>
				
				<td style=" font-weight:bold; font-size:28px;text-align:center;"><label>OT</label><br>Nº <?=$_GET['id_ot'];?></td>
			</tr>
		</table>


		<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
			<tr id="cabecera_1" valign="top"  style="height: 10px;">
				<td  width="15%" style="text-align: center;">
					<span><b>DATOS DEL VEHICULO</b></span>
				</td>
			</tr>
		</table>
		<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
			<tr id="cabecera_1" valign="top"  style="height: 60px;">
				<td  width="15%" style="text-align: left;">
					<label>Patente: </label><br><br>
					<label>&nbsp&nbsp&nbsp<?=$row2["patente"];?></label>

				</td>
				<td  width="28%" style="text-align: left;">
					<label>Marca:</label><br><br>
					<label>&nbsp&nbsp&nbsp<?=$row2["marca"];?></label>

				</td>
				<td  width="27%" style="text-align: left;">
					<label>Modelo:</label><br><br>
					<label>&nbsp&nbsp&nbsp<?=$row2["modelo"];?></label>

				</td>
				<td  width="15%" style="text-align: left;">
					<label>Kilometro:</label><br><br>
					<label>&nbsp&nbsp&nbsp<?=$row2["kilometro"];?></label>

				</td>
				<td  width="15%" style="text-align: left;">
					<label>Codificación Interna:</label><br><br>
					<label>&nbsp&nbsp&nbsp<?=$row2["codigo_interno"];?></label>

				</td>
			</table>


			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="top"  style="height: 10px;">
					<td  width="15%" style="text-align: center;">
						<span><b>DATOS PERSONAL</b></span>
					</td>
				</tr>
			</table>
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
				<tr id="cabecera_1" valign="top"  style="height: 60px;">
					<td  width="33%" style="text-align: left;">
						<label>Area:</label>
					</td>
					<td  width="33%" style="text-align: left;">
						<label>Nombre:</label>
					</td>
					<td  width="33%" style="text-align: left;">
						<label>Teléfono:</label>
					</td>
				</tr>
			</table>


			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="top"  style="height: 10px;">
					<td  width="15%" style="text-align: center;">
						<span><b>MOTIVO INGRESO A TALLER</b></span>
					</td>
				</tr>
			</table>
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
				<tr id="cabecera_1" valign="center"  style="height: 30px;">
					<td  width="33%" style="text-align: left;">
						<label>Mantención &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
						</label><span style="font-weight:bold; font-size:21px;">O</span>&nbsp&nbsp<label>De</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					</label>Km&nbsp/&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label>Hrs.&nbsp&nbsp&nbsp&nbsp</label> 
					&nbsp&nbsp&nbsp&nbsp<label>Averia</label>&nbsp&nbsp&nbsp&nbsp<span style="font-weight:bold; font-size:21px;">O</span>
				</td>
					<!-- <td  width="33%" style="text-align: left;">
						<label>Nombre:</label>
					</td>
					<td  width="33%" style="text-align: left;">
						<label>Teléfono:</label>
					</td> -->
				</tr>
			</table>
			
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="top"  style="height: 10px;">
					<td  width="15%" style="text-align: center;">
						<span><b>ESTADO VEHICULO</b></span>
					</td>
				</tr>
			</table>
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
				<tr id="cabecera_1" valign="center"  style="height: 30px;">
						<td  width="33%" style="text-align: left;">
							<img src="img/check_auto.jpg" border="0" height="120px" width="100%">
						</td>
				</tr>
			</table>

			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="top"  style="height: 10px;">
					<td  width="15%" style="text-align: center;">
						<span><b>EQUIPAMIENTO VEHICULO (B Bueno - M Malo - F Faltante - R Roto)</b></span>
					</td>
				</tr>
			</table>

			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
				<tr id="cabecera_1" valign="top"  style="height: 60px;">
					<td  width="33%" style="text-align: left;">
						<label>Radio&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Panel Radio&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Antena&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Emblemas&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Logo Emp.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Tabla Comp.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Tag&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Ruedas Rep.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label><br><br>
						<label>Parlantes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Gata&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Triangulos&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Botiquín&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Llave Rueda&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Tapa Rueda&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Encendedor&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Sombrillas&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label><br><br>
						<label>Espejos&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Extintor&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
						<label>Documentos&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
					</td>
				</tr>
			</table>



			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="top"  style="height: 10px;">
					<td  width="15%" style="text-align: center;">
						<span><b>OBSERVACIONES RECEPCION</b></span>
					</td>
				</tr>
			</table>
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
				<tr id="cabecera_1" valign="center"  style="height: 25px;">
					<td  width="33%" style="text-align: left;">
					</td>
				</tr>
				<tr id="cabecera_1" valign="center"  style="height: 25px;">
					<td  width="33%" style="text-align: left;">
					</td>
				</tr>
				<tr id="cabecera_1" valign="center"  style="height: 25px;">
					<td  width="33%" style="text-align: left;">
					</td>
				</tr>
				<tr id="cabecera_1" valign="center"  style="height: 25px;">
					<td  width="33%" style="text-align: left;">
					</td>
				</tr>
				<tr id="cabecera_1" valign="center"  style="height: 25px;">
					<td  width="33%" style="text-align: left;">
					</td>
				</tr>
			</table>


			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="top"  style="height: 10px;">
					<td  width="15%" style="text-align: center;">
						<span><b>TRABAJO REALIZADO</b></span>
					</td>
				</tr>
			</table>
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
				<tr id="cabecera_1" valign="center"  style="height: 25px;">
						<td  width="33%" style="text-align: center;">
							<label>MANTENCIÓN</label>
						</td>
						<td  width="33%" style="text-align: center;">
							<label>REPARACIÓN</label>
						</td>
				</tr>
				<tr id="cabecera_1" valign="top"  style="height: 250px;">
						<td  width="33%" style="text-align: left;">
						
							 <table align="center" style="width:100%;border-collapse:collapse;" cellpadding="2" cellspacing="4" border="1">
								<tr>
									<td width="25%"><label>Aceite Mot.</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Aceite Direcc</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Aceite Caja.</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Aceite</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Filtro Aire</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Filtro Aceite</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Filtro Comb.</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Filtro Comb. 2</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Agua Radia</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Agua Dep. Limp.</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Trapos</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>_____________</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td colspan="4" style="text-align:center;"><label>Chequeo Básico Vehiculo (B Bueno - M Malo)</label></td>
								</tr>	
								<tr>
									<td width="25%"><label>Frenos Delant.</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Surco Neumáticos</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Frenos Traser.</label></td>
											<td width="25%"><label>:</label></td>
									<td width="25%"><label>Presión de Aire</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Correa Direc.</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Luces Delanteras</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>Luces de Freno</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Luces Traseras</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td width="25%"><label>_____________</label></td>
									<td width="25%"><label>:</label></td>
									<td width="25%"><label>Luces Emergencia</label></td>
									<td width="25%"><label>:</label></td>
								</tr>
								<tr>
									<td colspan="4" style="text-align:left;"><label>Estado General Interior del vehiculo:</label></td>
								</tr>
								<tr>
									<td colspan="4" style="height:20px;text-align:left;"><label></label></td>
								</tr>
								<tr>
									<td colspan="4" style="height:20px;text-align:left;"><label></label></td>
								</tr>
								<tr>
									<td colspan="4" style="height:20px;text-align:left;"><label></label></td>
								</tr>
							</table> 

					</td>
					<td  width="33%" style="text-align: left;">
						
						<table align="center" style="border-collapse:collapse;" cellpadding="2" cellspacing="4" border="1">
							<tr id="cabecera_1" valign="top"  style="height: 60px;">
									<td  width="33%" style="text-align: left;">
										<label>Tipo de Falla:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Mayor&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Menor&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Siniestro&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label><br><br>
										<label>Falla:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Recurrente&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Desgaste&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Prematura&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label><br><br>
										<label>Tipo:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Electrica&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Mecanica&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
										<label>Estructural&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
									</td>
							</tr>
							<tr id="cabecera_1" valign="down"  style="height: 32px;">
									<td  width="33%" style="text-align: left;">
										<label>Obs:</label>
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
							<tr id="cabecera_1" valign="top"  style="height: 20px;">
									<td  width="33%" style="text-align: left;">
									</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
							<tr id="cabecera_1" valign="top"  style="height: 10px;">
								<td  width="15%" style="text-align: center;">
									<span><b>OBSERVACIONES ENTREGA</b></span>
								</td>
							</tr>
			</table>
			<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="1">
					<tr id="cabecera_1" valign="center"  style="height: 25px;">
						<td  width="33%" style="text-align: left;">
						</td>
					</tr>
					<tr id="cabecera_1" valign="center"  style="height: 25px;">
						<td  width="33%" style="text-align: left;">
						</td>
					</tr>
					<tr id="cabecera_1" valign="center"  style="height: 25px;">
						<td  width="33%" style="text-align: left;">
						</td>
					</tr>
					<tr id="cabecera_1" valign="center"  style="height: 25px;">
						<td  width="33%" style="text-align: left;">
						</td>
					</tr>
					<tr id="cabecera_1" valign="center"  style="height: 25px;">
						<td  width="33%" style="text-align: left;">
						</td>
					</tr>
		</table>
		
		<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				 <tr id="cabecera_1" valign="center"  style="height: 30px;">
					<td  width="50%" style="text-align: center;">
						<label>Taller( Curacavi_________ &nbsp&nbsp/&nbspObra&nbsp____________________________ ) </label>
					</td>
					<td  width="50%" style="text-align: center;">
						<label>Conductor</label>
					</td>
				</tr>
				<tr id="cabecera_1" valign="top" >
					<td  width="50%" style="text-align: left;">
			
						 <table align="center" style="width:100%;border-collapse:collapse;" cellpadding="2" cellspacing="4" border="1">
							<tr style="height: 60px;" valign="top">
								<td width="25%"><label>Nombre Recepción</label></td>
								<td width="25%"><label>Nmbre Entrega</label></td>
							</tr>
							<tr style="height: 20px;" valign="top">
								<td width="25%"><label>Fecha&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbspHora&nbsp&nbsp&nbsp&nbsp&nbsp:</label></td>
								<td width="25%"><label>Fecha&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbspHora&nbsp&nbsp&nbsp&nbsp&nbsp:</label></td>
							</tr>
							<tr style="height: 60px;" valign="top">
								<td width="25%"><label></label></td>
								<td width="25%"><label></label></td>
							</tr>
							<tr style="height: 20px;text-align:center;" valign="top">
								<td width="25%"><label>Firma</label></td>
								<td width="25%"><label>Firma</label></td>
							</tr>
						</table>
					</td>
					<td  width="50%" style="text-align: left;">
			
						<table align="center" style="width:100%;border-collapse:collapse;" cellpadding="2" cellspacing="4" border="1">
							<tr style="height: 60px;" valign="top">
								<td width="25%"><label>Nombre Recepción</label></td>
								<td width="25%"><label>Nmbre Entrega</label></td>
							</tr>
							<tr style="height: 20px;" valign="top">
								<td width="25%"><label>Fecha&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbspHora&nbsp&nbsp&nbsp&nbsp&nbsp:</label></td>
								<td width="25%"><label>Fecha&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbspHora&nbsp&nbsp&nbsp&nbsp&nbsp:</label></td>
							</tr>
							<tr style="height: 60px;" valign="top">
								<td width="25%"><label></label></td>
								<td width="25%"><label></label></td>
							</tr>
							<tr style="height: 20px;text-align:center;" valign="top">
								<td width="25%"><label>Firma</label></td>
								<td width="25%"><label>Firma</label></td>
							</tr>
						</table>	
					</td>
				</tr>	
		</table>
		<table align="center" style="width:100%;border-collapse:collapse;width:900px;" cellpadding="2" cellspacing="4" border="0">
				<tr id="cabecera_1" valign="center"  style="height: 30px;">
					<td  width="50%" style="text-align: left;">
						<span>El taller no se responsabiliza de enseres personales, se deben de retirar al momento de entregar el vehiculo</span>
					</td>
				</tr>
		</table>

		<!-- imprimir -->
		
		<table align="center">
		    <tr>
		        <td>
		            <input type="button" id="boton_im" onclick="javascript:imprSelec('f1')" value="Imprimir" />
		        </td>
		    </tr>
		</table>
</form>