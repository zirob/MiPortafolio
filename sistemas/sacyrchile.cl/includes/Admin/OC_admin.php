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
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>
<?php
	// var_dump($_POST);
	//Selecciono el tipo de cargo que tiene
	$sql_cargo = "SELECT jefatura, Backup_Jefatura FROM usuarios ";
	$sql_cargo.= "WHERE usuario='".$_SESSION['user']."' AND  rut_empresa='".$_SESSION['empresa']."'";
	$rec_cargo=mysql_query($sql_cargo);
	$row_cargo=mysql_fetch_array($rec_cargo);
	
	//Cargo 1
	$c1=$row_cargo[0];
	
	//cargo 
	$c2=$row_cargo[1];
	// echo $c1."-".$c2;
	//edita la accion
	$action="2&id_oc=".$_GET['id_oc'];
	$mostrar=0;
	$error=0;
	
	
	/*if($_SESSION['user']!=$_POST['usuario_ingreso'])
	{
		$mensaje="Modificaciones permitidas a esta OC solo por el usuario:";
		$no_edit = 1;
		$error=1;
	}else{*/
	if(empty($_POST['id_solicitud_compra']) and !empty($_POST['procesar']))
	{
		$mensaje=" Debe ingresar una solicitud ";
		$error=1;
	}

	if(empty($_POST['moneda']) and !empty($_POST['procesar']))
	{
		$mensaje=" Debe ingresar la moneda ";
		$error=1;
	}

	if(empty($_POST['id_esp']) and !empty($_POST['procesar']))
	{
		$mensaje=" Debe ingresar la especificación ";
		$error=1;
	}


	if(empty($_POST['proveedor']) and !empty($_POST['procesar']))
	{
		$mensaje=" Debe ingresar el proveedor ";
		$error=1;
	}

	if($_POST['total_doc']>20000000){
		if(empty($_POST['vb_gerente_general']) and $_POST["estado_oc"]==4 )
		{
			$mensaje=" Debe dar Visto Bueno el de mayor rango antes de Aprobar ";
			$error=1;
			$_POST["error"]=1;
		}
	}else{
		if(empty($_POST['vb_jefe_grupo_obras']) and $_POST["estado_oc"]==4 )
		{
			$mensaje=" Debe dar Visto Bueno el de mayor rango antes de Aprobar ";
			$error=1;
			$_POST["error"]=1;
		}
	}

	

	if(($_POST['procesar']))
	{
		if($_POST['cantidad']==0)
		{
			$mensaje=" Debe ingresar al menos un detalle de producto ";
			$error=1;
		}
		else
		{
			for($i=1;$i<=$_POST['cantidad'];$i++)
			{
				if($_POST['visible'.$i]=='')
				{

						//Buscamos los detalles



						if(empty($_POST['precio'.$i]))  
						{
								$mensaje=" Ingresar Precio item Nº".$i." del detalle de Productos. ";
								$error=1;
						}
						if(empty($_POST['descripcion'.$i]))  
						{
								$mensaje=" Ingresar Descripcion del item Nº".$i." del detalle de Productos ";
								$error=1;
						}
						if(empty($_POST['unidad'.$i]))  
						{
								$mensaje=" Ingresar Unidad del item Nº".$i." del detalle de Productos ";
								$error=1;
						}
						if(empty($_POST['cantidad'.$i]))  
						{
								$mensaje=" Ingresar Cantidad del item Nº".$i." del detalle de Productos ";
								$error=1;
						}

					// break;
				}
			}
		}

	}
	// }

	//Datos de la cabecera
    $sql_emp = "SELECT * FROM empresa WHERE rut_empresa='".$_SESSION['empresa']."'";
    $res_emp = mysql_query($sql_emp,$con);
    $row_emp = mysql_fetch_assoc($res_emp);
	
	//Trae los datos del la solicitud
	if((($_POST['id_solicitud_compra'])and(empty($_POST['procesar']))) || (!empty($_POST["procesar"])))
	{
		//echo "traer datos solicitud";
		$sql_sol="select * from solicitudes_compra WHERE id_solicitud_compra=".$_POST['id_solicitud_compra']." AND rut_empresa='".$_SESSION['empresa']."'";
		$rec_sol=mysql_query($sql_sol);	
		$row_sol=mysql_fetch_array($rec_sol);

		$num=mysql_num_rows($rec_sol);

		if(empty($_GET['id_oc']))
		{
			 if($row_sol['estado']!=3)
			 {
			 	$error=1;
			 	$mensaje=" Orden de compra seleccionada se encuentra cerrada ";
			 	$num=0;
			 }
		}



		if($num==0)
		{
			$_POST['id_solicitud_compra']="";
			$_POST['solicitante']="";
			$_POST['descripcion_solicitud']="";	
			$_POST['concepto']="";
			$_POST['centro_costos']="";
			$mensaje=" Debe ingresar una solicitud  valida y en estado autorizada ";
			$error=1;
		}
		else
		{

			$_POST['solicitante']=$row_sol['solicitante'];
			$_POST['descripcion_solicitud']=$row_sol['descripcion_solicitud'];	
			$_POST['concepto']=$row_sol['concepto'];
			$_POST['centro_costos']=$row_sol['id_cc'];
		}
	
	}
	
	//Calcula la cantidad de  OC
	if(empty($_GET['id_oc']))
	{
		$sql="SELECT MAX(id_oc) AS id_oc FROM cabecera_oc WHERE rut_empresa='".$_SESSION['empresa']."' ";
		$rec_num=mysql_query($sql);
		$row_oc = mysql_fetch_assoc($rec_num);
		$row_oc["id_oc"] = $row_oc["id_oc"] + 1; 
		
		@$row_num=mysql_fetch_array($rec_num);
		$row_num[0]=$row_num[0]+1;
		$_POST['forma_de_pago']=0;
	}
	
	//Indicadores
	$i=1;
	$j=0;
	$var=0;
	
	//Agregar una linea
	if(!empty($_POST['agregar']))
	{
		$_POST['cantidad']++;
	}

	//Eliminar una fila
	if(!empty($_POST['eliminar']))
	{
		$_POST['visible'.$_POST['eliminar']]=1;
		//calcula el subtotal
		$_POST['subtotal']=$_POST['subtotal']-$_POST['total'.$_POST['eliminar']];
		//calcula el neto
		$_POST['valor_neto']=$_POST['subtotal']-$_POST['descuento'];
		//calcula el iva
		$_POST['iva']=round($_POST['valor_neto']*($_POST['valor_iva']/100));
		//calcula el total
		$_POST['total_doc']=round($_POST['valor_neto']*(($_POST['valor_iva']/100))+1);
	}
	
	//Si esta no vacio traigo los datos para editar de la cebecera
	if(!empty($_GET['id_oc']) and empty($_POST['primera']))
	{
		 $sql = "SELECT * FROM cabecera_oc WHERE id_oc=".$_GET['id_oc']." AND rut_empresa='".$_SESSION['empresa']."'";
         $res = mysql_query($sql);
         $row = mysql_fetch_assoc($res);
		 $_POST=$row;
		 $_POST['total_doc']=$row['total'];
		 $_POST['proveedor']=$row['rut_proveedor'];
		 $_POST['fecha_entrega']=substr($_POST['fecha_entrega'],0,10);	
		 
		 $sql_prv=" SELECT id_esp FROM proveedores where rut_proveedor='".$_POST['proveedor']."'";
		 $rec_prv=mysql_query($sql_prv);
		 $row_prv=mysql_fetch_array($rec_prv);
		 $_POST['id_esp']=$row_prv[0];
		 	
	}
	
	//Si esta no vacio traigo los datos para editar del detalle
	if(!empty($_GET['id_oc']) and empty($_POST['primera']) and ($error==0))
	{
		 $p=1;
		 $sql_detalle = "SELECT * FROM detalle_oc  WHERE id_oc=".$_GET['id_oc']." AND estado_detalle_oc=0  AND rut_empresa='".$_SESSION['empresa']."'";
         $res_detalle = mysql_query($sql_detalle);
		 $_POST['cantidad']=mysql_num_rows($res_detalle);
		 while($row_detalle = mysql_fetch_assoc($res_detalle))
		 {
			 $_POST['cantidad'.$p]= $row_detalle['cantidad'];
			 $_POST['unidad'.$p]= $row_detalle['unidad'];
			 $_POST['descripcion'.$p]= $row_detalle['descripcion'];
			 $_POST['precio'.$p]= $row_detalle['precio'];
			 $_POST['precio'.$p]=str_replace(".","",$_POST['precio'.$p]);
			 $_POST['total'.$p]= $row_detalle['total'];
			 $_POST['total'.$p]=str_replace(".","",$_POST['total'.$p]);
			 $_POST['id_det_oc'.$p]= $row_detalle['id_det_oc'];	 
			 $_POST['visible'.$p]= '';
			 $p++;
		 }
	}
	
	//Si hace POST el boton y si esta vacio el GET_['id_oc'] actualizo  si no guardo;	
	if(!empty($_GET['id_oc']) and !empty($_POST['procesar']) and ($error==0))
	{
		$sql = " UPDATE  cabecera_oc SET valor_iva='".$_POST['valor_iva']."', ";	
		$sql.= " id_solicitud_compra='".$_POST['id_solicitud_compra']."',";	
		$sql.= " rut_empresa='".$_SESSION['empresa']."',";	
		$sql.= " fecha_oc='".$_POST['fecha_oc']."',";
		//$sql.= " solicitante='".$_POST['solicitante']."',";	
		// $sql.= " concepto='".$_POST['concepto']."',";	
		//$sql.= " centro_costos='".$_POST['centro_costos']."',";
		$sql.= " atte_oc='".$_POST['atte_oc']."',";
		$sql.= " moneda='".$_POST['moneda']."',";
		$sql.= " subtotal='".$_POST['subtotal']."',";	
		$sql.= " descuento='".$_POST['descuento']."',";
		$sql.= " iva='".$_POST['iva']."',";	
		$sql.= " valor_neto='".$_POST['valor_neto']."',";	
		$sql.= " total	='".$_POST['total_doc']."',";
		$sql.= " forma_de_pago	='".$_POST['forma_de_pago']."',";
		$sql.= " fecha_entrega	='".$_POST['fecha_entrega']."',";
		$sql.= " sometido_plan_calidad	='".$_POST['sometido_plan_calidad']."',";
		$sql.= " adj_propuesta_economica	='".$_POST['adj_propuesta_economica']."',";
		$sql.= " adj_esp_tecnica	='".$_POST['adj_esp_tecnica']."',";	

		$sel_vb2 = "SELECT * FROM cabecera_oc WHERE id_oc=".$_GET['id_oc']." AND rut_empresa='".$_SESSION['empresa']."'";
        $res_vb2 = mysql_query($sel_vb2);
        $row_vb2 = mysql_fetch_assoc($res_vb2);
		
		//Valida el Visto Bueno del primero
		if($c1==1 || $c2==1)
		{
			if(empty($_POST['vb_depto_compras'] ))
			{
				$sql.="vb_depto_compras='',";
				$sql.="nombre_vb_depto_compras='',";
				$sql.="fecha_vb_depto_compras='".date('Y-m-d h:i:s')."',";
			}
			else
			{
				if($row_vb2["vb_depto_compras"]==0){
					$var++;
					$sql.="vb_depto_compras='".$_POST['vb_depto_compras']."',";
					$sql.="nombre_vb_depto_compras='".$_SESSION['user']."',";
					$sql.="fecha_vb_depto_compras='".date('Y-m-d h:i:s')."',";	
				}
			}
		}
		
		//Valida el Visto Bueno del segundo
		if($c1==2 || $c2==2)
		{
			if(empty($_POST['vb_jefe_compras'] ))
			{
				$sql.="vb_jefe_compras='',";
				$sql.="nombre_vb_jefe_compras='',";
				$sql.="fecha_vb_jefe_compras='".date('Y-m-d h:i:s')."',";
			}
			else
			{
				if($row_vb2["vb_jefe_compras"]==0){
					$var++;
					$sql.="vb_jefe_compras='".$_POST['vb_jefe_compras']          ."',";
					$sql.="nombre_vb_jefe_compras='".$_SESSION['user']."',";
					$sql.="fecha_vb_jefe_compras='".date('Y-m-d h:i:s')."',";	
				}
			}
		}
		
		//Valida el Visto Bueno del tercero
		if($c1==3 || $c2==3)
		{
			if(empty($_POST['vb_jefe_adm'] ))
			{
				$sql.="vb_jefe_adm='',";
				$sql.="nombre_vb_jefe_adm='',";
				$sql.="fecha_vb_jefe_adm='".date('Y-m-d h:i:s')."',";
			}
			else
			{
				if($row_vb2["vb_jefe_adm"]==0){
					$var++;
					$sql.="vb_jefe_adm='".$_POST['vb_jefe_adm']          ."',";
					$sql.="nombre_vb_jefe_adm='".$_SESSION['user']."',";
					$sql.="fecha_vb_jefe_adm='".date('Y-m-d h:i:s')."',";	
				}
			}
		}

		//Valida el Visto Bueno del cuarto
		if($c1==4 || $c2==4)
		{
			if(empty($_POST['vb_jefe_parque_maquinaria'] ))
			{
				$sql.="vb_jefe_parque_maquinaria='',";
				$sql.="nombre_vb_jefe_pm='',";
				$sql.="fecha_vb_jefe_pm='".date('Y-m-d h:i:s')."',";
			}
			else
			{
				if($row_vb2["vb_jefe_parque_maquinaria"]==0){
					$var++;
					$sql.="vb_jefe_parque_maquinaria='".$_POST['vb_jefe_parque_maquinaria']          ."',";
					$sql.="nombre_vb_jefe_pm='".$_SESSION['user']."',";
					$sql.="fecha_vb_jefe_pm='".date('Y-m-d h:i:s')."',";	
				}
			}
		}
		//Valida el Visto Bueno del quinto
		if($c1==5 || $c2==5)
		{
			if(empty($_POST['vb_jefe_grupo_obras'] ))
			{
				$sql.="vb_jefe_grupo_obras='',";
				$sql.="nombre_vb_grupo_obras='',";
				$sql.="fecha_vb_grupo_obras='".date('Y-m-d h:i:s')."',";
			}
			else
			{
				if($row_vb2["vb_jefe_grupo_obras"]==0){
					$var++;
					$sql.="vb_jefe_grupo_obras='".$_POST['vb_jefe_grupo_obras']          ."',";
					$sql.="nombre_vb_grupo_obras='".$_SESSION['user']."',";
					$sql.="fecha_vb_grupo_obras='".date('Y-m-d h:i:s')."',";	
				}
			}
		}
		//Valida el Visto Bueno del Sexto
		if($c1==6 || $c2==6)
		{
			if(empty($_POST['vb_gerente_general'] ))
			{
				$sql.="vb_gerente_general='',";
				$sql.="nombre_vb_gerente_general='',";
				$sql.="fecha_vb_gerente_general='".date('Y-m-d h:i:s')."',";
			}
			else
			{
				if($row_vb2["vb_jefe_grupo_obras"]==0){
					$var++;
					$sql.="vb_gerente_general='".$_POST['vb_gerente_general']          ."',";
					$sql.="nombre_vb_gerente_general='".$_SESSION['user']."',";
					$sql.="fecha_vb_gerente_general='".date('Y-m-d h:i:s')."',";
					// $_POST['estado_oc']=4;		
				}
			}
		}


		$sql.="factura='".$_POST['factura']."',";
		$sql.="observaciones='".$_POST['observaciones']."',";
		$sql.="observaciones1='".$_POST['observaciones1']."',";
		$sql.="observaciones2='".$_POST['observaciones2']."',";
		$sql.="estado_oc='".$_POST['estado_oc']."',";
		$sql.="rut_proveedor='".$_POST['proveedor']."',";
		$sql.="usuario_ingreso='".$_SESSION['user']."',";
		$sql.="fecha_ingreso='".date('Y-m-d h:i:s')."'";
		$sql.= " WHERE id_oc='".$_GET['id_oc']."'  AND rut_empresa='".$_SESSION['empresa']."'";
		
		
		$consulta=mysql_query($sql);
		if($consulta)
			$mensaje=" Actualización Correcta";
			$mostrar=1;

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'cabecera_oc', '".$_GET['id_oc']."', '3'";
        $sql_even.= ", 'UPDATE:";
        $sql_even.= "valor_iva=".$_POST['valor_iva'].", id_solicitud_compra=".$_POST['id_solicitud_compra'].", rut_empresa=".$_SESSION['empresa']."";
        $sql_even.= ", fecha_oc=".$_POST['fecha_oc'].", atte_oc=".$_POST['atte_oc'].", moneda=".$_POST['moneda'].", subtotal=".$_POST['subtotal']."";
        $sql_even.= ", descuento=".$_POST['descuento'].", iva=".$_POST['iva'].", valor_neto=".$_POST['valor_neto'].", total	=".$_POST['total_doc']."";
        $sql_even.= ", forma_de_pago=".$_POST['forma_de_pago'].", fecha_entrega	=".$_POST['fecha_entrega'].", sometido_plan_calidad=".$_POST['sometido_plan_calidad']."";
        $sql_even.= ", adj_propuesta_economica	=".$_POST['adj_propuesta_economica'].", adj_esp_tecnica	=".$_POST['adj_esp_tecnica'].", vb_depto_compras=".$_POST['vb_depto_compras']."";
        $sql_even.= ", nombre_vb_depto_compras=".$_SESSION['user'].", fecha_vb_depto_compras=".date('Y-m-d h:i:s').", vb_jefe_compras=".$_POST['vb_jefe_compras']."";
        $sql_even.= ", nombre_vb_jefe_compras=".$_SESSION['user'].", fecha_vb_jefe_compras=".date('Y-m-d h:i:s').", vb_jefe_adm=".$_POST['vb_jefe_adm']."";
        $sql_even.= ", nombre_vb_jefe_adm=".$_SESSION['user'].", fecha_vb_jefe_adm=".date('Y-m-d h:i:s').", vb_jefe_parque_maquinaria=".$_POST['vb_jefe_parque_maquinaria']."";
        $sql_even.= ", nombre_vb_jefe_pm=".$_SESSION['user'].", fecha_vb_jefe_pm=".date('Y-m-d h:i:s').", vb_jefe_grupo_obras=".$_POST['vb_jefe_grupo_obras']."";
        $sql_even.= ", nombre_vb_grupo_obras=".$_SESSION['user'].", fecha_vb_grupo_obras=".date('Y-m-d h:i:s').", vb_gerente_general=".$_POST['vb_gerente_general']."";
        $sql_even.= ", nombre_vb_gerente_general=".$_SESSION['user'].", fecha_vb_gerente_general=".date('Y-m-d h:i:s').", factura=".$_POST['factura']."";
        $sql_even.= ", observaciones=".$_POST['observaciones'].", estado_oc=".$_POST['estado_oc'].", rut_proveedor=".$_POST['proveedor'].", usuario_ingreso=".$_SESSION['user']."";
        $sql_even.= ", fecha_ingreso=".date('Y-m-d h:i:s')."'";
        $sql_evne.= ", '".$_SERVER['REMOTE_ADDR']."', 'actualizacion de cabecera de orden compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
		
		// Si todos los VB estan Chequeados entonces estado: Aprobado 
		$sel_vb = "SELECT vb_depto_compras, vb_jefe_compras, vb_jefe_adm, vb_jefe_parque_maquinaria, ";
		$sel_vb.= "vb_jefe_grupo_obras, vb_gerente_general FROM cabecera_oc WHERE id_oc='".$_GET["id_oc"]."'";
		$sel_vb.= " AND rut_empresa='".$_SESSION['empresa']."' ";
		$res_vb=mysql_query($sel_vb);
        $row_vb=mysql_fetch_array($res_vb);
		if($_POST['total_doc'] > 50000 && $_POST['total_doc']<=20000000){
			if($row_vb['vb_depto_compras']==1 && $row_vb['vb_jefe_compras']==1 && $row_vb['vb_jefe_adm']==1 &&
				   $row_vb['vb_jefe_parque_maquinaria']==1 && $row_vb['vb_jefe_grupo_obras']==1 ){
					
					
				   			$up_estado = "UPDATE cabecera_oc SET estado_oc=4 WHERE id_oc='".$_GET["id_oc"]."' AND rut_empresa='".$_SESSION['empresa']."'";
							mysql_query($up_estado);
			}
		}
			/*if($_POST['total_doc'] > 20000000){
				if($row_vb['vb_depto_compras']==1 && $row_vb['vb_jefe_compras']==1 && $row_vb['vb_jefe_adm']==1 &&
				   $row_vb['vb_jefe_parque_maquinaria']==1 && $row_vb['vb_jefe_grupo_obras']==1 && $row_vb['vb_gerente_general']==1){
				   			$up_estado = "UPDATE cabecera_oc SET estado_oc=4 WHERE id_oc='".$_GET["id_oc"]."' AND rut_empresa='".$_SESSION['empresa']."'";
							mysql_query($up_estado);
				}
			}*/

				

		
		//Agrego el nuevo detalle
		$g=1;
		while($g<=$_POST['cantidad'])
		{
			$sql_buscar="select * from detalle_oc where id_det_oc='".$_POST['id_det_oc'.$g]."'  AND rut_empresa='".$_SESSION['empresa']."'";
			$rec_buscar=mysql_query($sql_buscar);
			$num_buscar=mysql_num_rows($rec_buscar);
			if(($num_buscar>0)&&(empty($_POST['visible'.$g])))
			{
				$sql_detalle="  UPDATE detalle_oc SET";
				$sql_detalle.=" id_oc='".$_POST['id_oc']."',";
				//$sql_detalle.=" id_solicitud_compra='".$_POST['id_solicitud_compra']."',";
				$sql_detalle.=" rut_empresa='".$_SESSION['empresa']."',";
				$sql_detalle.=" cantidad='".$_POST['cantidad'.$g]."',";
				$sql_detalle.=" unidad='".$_POST['unidad'.$g]."',";
				$sql_detalle.=" descripcion='".$_POST['descripcion'.$g]."',";
				$sql_detalle.=" precio='".$_POST['precio'.$g]."',";
				$sql_detalle.=" total='".$_POST['total'.$g]."',";
				$sql_detalle.=" usuario_ingreso='".$_SESSION['user']."',";
				$sql_detalle.=" fecha_ingreso='".date('Y-m-d h:i:s')."',";
				$sql_detalle.=" estado_detalle_oc='0',";
				$sql_detalle.=" usuario_elimina='',";
				$sql_detalle.=" fecha_elimina=''";	
				$sql_detalle.=" where id_det_oc='".$_POST['id_det_oc'.$g]."'  AND rut_empresa='".$_SESSION['empresa']."'";		
				
				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
		        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
		        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_oc', '".$_POST['id_det_oc'.$g]."', '3'";
		        $sql_even.= ", 'UPDATE:";
		        $sql_even.= "id_oc=".$_POST['id_oc'].", rut_empresa=".$_SESSION['empresa'].", cantidad=".$_POST['cantidad'.$g]."";
		        $sql_even.= ", unidad=".$_POST['unidad'.$g].", descripcion=".$_POST['descripcion'.$g].", precio=".$_POST['precio'.$g].", total=".$_POST['total'.$g]."";
		        $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d h:i:s').", estado_detalle_oc=0'";
		        $sql_even.= ", '".$_SERVER['REMOTE_ADDR']."', 'actualizacion detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
		        mysql_query($sql_even, $con);
				
			}
			
			if(($num_buscar>0)&&(!empty($_POST['visible'.$g])))
			{
				$sql_detalle=" UPDATE detalle_oc SET";
				$sql_detalle.=" id_oc='".$_POST['id_oc']."',";
				$sql_detalle.=" id_solicitud_compra='".$_POST['id_solicitud_compra']."',";
				$sql_detalle.=" rut_empresa='".$_SESSION['empresa']."',";
				$sql_detalle.=" cantidad='".$_POST['cantidad'.$g]."',";
				$sql_detalle.=" unidad='".$_POST['unidad'.$g]."',";
				$sql_detalle.=" descripcion='".$_POST['descripcion'.$g]."',";
				$sql_detalle.=" precio='".$_POST['precio'.$g]."',";
				$sql_detalle.=" total='".$_POST['total'.$g]."',";
				$sql_detalle.=" usuario_ingreso='".$_SESSION['user']."',";
				$sql_detalle.=" fecha_ingreso='".date('Y-m-d h:i:s')."',";
				$sql_detalle.=" estado_detalle_oc='1',";
				$sql_detalle.=" usuario_elimina='".$_SESSION['user']."',";
				$sql_detalle.=" fecha_elimina='".date('Y-m-d h:i:s')."'";	
				$sql_detalle.=" where id_det_oc='".$_POST['id_det_oc'.$g]."' AND rut_empresa='".$_SESSION['empresa']."'";	
				
				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
		        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
		        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_oc', '".$_POST['id_det_oc'.$g]."', '3'";
		        $sql_even.= ", 'UPDATE:";
		        $sql_even.= "id_oc=".$_POST['id_oc'].", id_solicitud_compra=".$_POST['id_solicitud_compra'].", rut_empresa=".$_SESSION['empresa'].", cantidad=".$_POST['cantidad'.$g]."";
		        $sql_even.= ", unidad=".$_POST['unidad'.$g].", descripcion=".$_POST['descripcion'.$g].", precio=".$_POST['precio'.$g].", total=".$_POST['total'.$g]."";
		        $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d h:i:s').", estado_detalle_oc=1'";
		        $sql_even.= "'".$_SERVER['REMOTE_ADDR']."', 'actualizacion detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
		        mysql_query($sql_even, $con);
			}

			if((empty($_POST['visible'.$g]))and ($num_buscar==0))
			{
					$sql_detalle =" INSERT INTO detalle_oc(id_oc,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total,usuario_ingreso,fecha_ingreso,estado_detalle_oc,usuario_elimina,fecha_elimina) ";
					$sql_detalle.=" VALUES (";
					$sql_detalle.="'".$_GET['id_oc']."',";
					$sql_detalle.="'".$_POST['id_solicitud_compra']."',";
					$sql_detalle.="'".$_SESSION['empresa'] ."',";
					$sql_detalle.="'".$_POST['cantidad'.$g]."',";
					$sql_detalle.="'".$_POST['unidad'.$g]."',";
					$sql_detalle.="'".$_POST['descripcion'.$g]."',";
					$sql_detalle.="'".$_POST['precio'.$g]."',";
					$sql_detalle.="'".$_POST['total'.$g]."',";
					$sql_detalle.="'".$_SESSION['user']."',";
					$sql_detalle.="'".date('Y-m-d h:i:s')."',";
					$sql_detalle.="'0',";
					$sql_detalle.="'',";
					$sql_detalle.="''";
					$sql_detalle.=" )";
					

			}
			mysql_query($sql_detalle);
			$g++;

			$consulta = "SELECT MAX(id_oc) as id_oc FROM detalle_oc WHERE rut_empresa='".$_SESSION["empresa"]."'";
            $resultado=mysql_query($consulta);
            $fila=mysql_fetch_array($resultado);
			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_oc', '".$_POST['id_det_oc'.$g]."', '2'";
	        $sql_even.= ", 'INSERT:";
	        $sql_even.= "id_oc=".$_POST['id_oc'].", rut_empresa=".$_SESSION['empresa'].", cantidad=".$_POST['cantidad'.$g]."";
	        $sql_even.= ", unidad=".$_POST['unidad'.$g].", descripcion=".$_POST['descripcion'.$g].", precio=".$_POST['precio'.$g].", total=".$_POST['total'.$g]."";
	        $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d h:i:s').", estado_detalle_oc=0'";
	        $sql_even.= "'".$_SERVER['REMOTE_ADDR']."', 'INSERCION detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
	        mysql_query($sql_even, $con);
		}
		$mostrar=1;
		
	}
	
	if(empty($_GET['id_oc']) and !empty($_POST['procesar']) and ($error==0))
	{
	
		//Guardo en la cabecera
		$sql= "INSERT INTO cabecera_oc(";
		$sql.= " valor_iva, id_solicitud_compra, rut_empresa, fecha_oc";
		$sql.= ", solicitante, concepto, centro_costos";
		$sql.= ", atte_oc, moneda, subtotal";
		$sql.= ", descuento, valor_neto, iva,total";
		$sql.= ", forma_de_pago, fecha_entrega, sometido_plan_calidad";
	 	$sql.= ", adj_esp_tecnica, adj_propuesta_economica";
	    $sql.= ", vb_depto_compras, nombre_vb_depto_compras, fecha_vb_depto_compras";
	    $sql.= ", vb_jefe_compras, nombre_vb_jefe_compras, fecha_vb_jefe_compras";
	    $sql.= ", vb_jefe_adm, nombre_vb_jefe_adm, fecha_vb_jefe_adm";
	    $sql.= ", vb_jefe_parque_maquinaria, nombre_vb_jefe_pm, fecha_vb_jefe_pm";
	    $sql.= ", vb_jefe_grupo_obras, nombre_vb_grupo_obras, fecha_vb_grupo_obras";
	    $sql.= ", vb_gerente_general, nombre_vb_gerente_general, fecha_vb_gerente_general";
	    $sql.= ", observaciones, observaciones1, observaciones2, estado_oc, rut_proveedor";
	    $sql.= ", usuario_ingreso, fecha_ingreso, factura";
		$sql.= ") VALUES(";
		$sql.= " '".$_POST["valor_iva"]."', '".$_POST['id_solicitud_compra']."', '".$_SESSION['empresa']."', '".date('Y-m-d h:i:s')."'";
		$sql.= ", '".$_POST['solicitante']."', '".$_POST['concepto']."', '".$_POST['centro_costos']."'";
		$sql.= ", '".$_POST['atte_oc']."', '".$_POST['moneda']."', '".$_POST['subtotal']."'";
		$sql.= ", '".$_POST['descuento']."', '".$_POST['valor_neto']."', '".$_POST['iva']."', '".$_POST['total_doc']."'";
		$sql.= ", '".$_POST['forma_de_pago']."', '".$_POST['fecha_entrega']."', '".$_POST['sometido_plan_calidad']."'";	
		$sql.= ", '".$_POST['adj_esp_tecnica']."', '".$_POST['adj_propuesta_economica']."'";	



		//Valida el Visto Bueno del primero
		if($c1==1 || $c2==1)
		{
			if(empty($_POST['vb_depto_compras'] ))
			{
				$sql.=", '', '', '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$sql.=", '".$_POST['vb_depto_compras']."', '".$_SESSION['user']."', '".date('Y-m-d h:i:s')."'";
			}
		}else{
			$sql.=", '', '', ''";
		}
		
		//Valida el Visto Bueno del segundo
		if($c1==2 || $c2==2)
		{
			if(empty($_POST['vb_jefe_compras'] ))
			{
				$sql.=", '', '', '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$sql.=", '".$_POST['vb_jefe_compras']."', '".$_SESSION['user']."', '".date('Y-m-d h:i:s')."'";
			}
		}else{
			$sql.=", '', '', ''";
		}
		
		//Valida el Visto Bueno del tercero
		if($c1==3 || $c2==3)
		{
			if(empty($_POST['vb_jefe_adm'] ))
			{
				$sql.=", '', '', '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$sql.=", '".$_POST['vb_jefe_adm']."', '".$_SESSION['user']."', '".date('Y-m-d h:i:s')."'";
			}
		}else{
			$sql.=", '', '', ''";
		}
			
		//Valida el Visto Bueno del cuarto
		if($c1==4 || $c2==4)
		{
			if(empty($_POST['vb_jefe_parque_maquinaria'] ))
			{
				$sql.=", '', '', '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$sql.=", '".$_POST['vb_jefe_adm']."', '".$_SESSION['user']."', '".date('Y-m-d h:i:s')."'";
			}
		}else{
			$sql.=", '', '', ''";
		}

		//Valida el Visto Bueno del quinto
		if($c1==5 || $c2==5)
		{
			if(empty($_POST['vb_jefe_grupo_obras'] ))
			{
				$sql.=", '', '', '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$sql.=", '".$_POST['vb_jefe_grupo_obras']."', '".$_SESSION['user']."', '".date('Y-m-d h:i:s')."'";
			}
		}else{
			$sql.=", '', '', ''";
		}

		//Valida el Visto Bueno del Sexto
		if($c1==6 || $c2==6)
		{
			if(empty($_POST['vb_gerente_general'] ))
			{
				$sql.=", '', '', '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$sql.=", '".$_POST['vb_gerente_general']."', '".$_SESSION['user']."', '".date('Y-m-d h:i:s')."'";
				// $_POST['estado_oc']=4;	
			}
		}else{
			$sql.=", '', '', ''";
		}
		
		$sql.=", '".$_POST['observaciones']."', '".$_POST['observaciones1']."', '".$_POST['observaciones2']."', '1', '".$_POST['proveedor']."'";		
		$sql.=", '".$_SESSION['user']."', '". date('Y-m-d h:i:s')."', '".$_POST['factura']."'";	
		$sql.=")";
		
		$consulta1=mysql_query($sql);
			
		// Si Total < 50000 entonces estado: Aprobado y V.B. todos Chequeados ////////////////////////////////////
																												//
		$consulta = "SELECT MAX(id_oc) as id_oc FROM cabecera_oc WHERE rut_empresa='".$_SESSION["empresa"]."'";	//
        $resultado=mysql_query($consulta);																		//
        $fila=mysql_fetch_array($resultado);																	//
																												//
		if($_POST["total_doc"]<=50000){																			//
			$up_oc = "UPDATE cabecera_oc SET estado_oc=4, vb_depto_compras=1";									//
			$up_oc.= ", fecha_vb_depto_compras='".date('Y-m-d h:i:s')."', vb_jefe_compras=1";					//
			$up_oc.= ", fecha_vb_jefe_compras='".date('Y-m-d h:i:s')."', vb_jefe_adm=1";						//
			$up_oc.= ", fecha_vb_jefe_adm='".date('Y-m-d h:i:s')."', vb_jefe_parque_maquinaria=1";				//
			$up_oc.= ", fecha_vb_jefe_pm='".date('Y-m-d h:i:s')."', vb_jefe_grupo_obras=1";						//
			$up_oc.= ", fecha_vb_grupo_obras='".date('Y-m-d h:i:s')."' "; 										//
			$up_oc.= "WHERE id_oc='".$fila["id_oc"]."' AND rut_empresa='".$_SESSION["empresa"]."'";				//
			mysql_query($up_oc);																				//
		}																										//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'cabecera_oc', '".$_GET['id_oc']."', '2'";
        $sql_even.= ", 'INSERT:";
        $sql_even.= "valor_iva=".$_POST['valor_iva'].", id_solicitud_compra=".$_POST['id_solicitud_compra'].", rut_empresa=".$_SESSION['empresa']."";
        $sql_even.= ", fecha_oc=".$_POST['fecha_oc'].", atte_oc=".$_POST['atte_oc'].", moneda=".$_POST['moneda'].", subtotal=".$_POST['subtotal']."";
        $sql_even.= ", descuento=".$_POST['descuento'].", iva=".$_POST['iva'].", valor_neto=".$_POST['valor_neto'].", total	=".$_POST['total_doc']."";
        $sql_even.= ", forma_de_pago=".$_POST['forma_de_pago'].", fecha_entrega	=".$_POST['fecha_entrega'].", sometido_plan_calidad=".$_POST['sometido_plan_calidad']."";
        $sql_even.= ", adj_propuesta_economica	=".$_POST['adj_propuesta_economica'].", adj_esp_tecnica	=".$_POST['adj_esp_tecnica'].", vb_depto_compras=".$_POST['vb_depto_compras']."";
        $sql_even.= ", nombre_vb_depto_compras=".$_SESSION['user'].", fecha_vb_depto_compras=".date('Y-m-d h:i:s').", vb_jefe_compras=".$_POST['vb_jefe_compras']."";
        $sql_even.= ", nombre_vb_jefe_compras=".$_SESSION['user'].", fecha_vb_jefe_compras=".date('Y-m-d h:i:s').", vb_jefe_adm=".$_POST['vb_jefe_adm']."";
        $sql_even.= ", nombre_vb_jefe_adm=".$_SESSION['user'].", fecha_vb_jefe_adm=".date('Y-m-d h:i:s').", vb_jefe_parque_maquinaria=".$_POST['vb_jefe_parque_maquinaria']."";
        $sql_even.= ", nombre_vb_jefe_pm=".$_SESSION['user'].", fecha_vb_jefe_pm=".date('Y-m-d h:i:s').", vb_jefe_grupo_obras=".$_POST['vb_jefe_grupo_obras']."";
        $sql_even.= ", nombre_vb_grupo_obras=".$_SESSION['user'].", fecha_vb_grupo_obras=".date('Y-m-d h:i:s').", vb_gerente_general=".$_POST['vb_gerente_general']."";
        $sql_even.= ", nombre_vb_gerente_general=".$_SESSION['user'].", fecha_vb_gerente_general=".date('Y-m-d h:i:s').", factura=".$_POST['factura']."";
        $sql_even.= ", observaciones=".$_POST['observaciones'].", estado_oc=".$_POST['estado_oc'].", rut_proveedor=".$_POST['proveedor'].", usuario_ingreso=".$_SESSION['user']."";
        $sql_even.= ", fecha_ingreso=".date('Y-m-d h:i:s')."'";
        $sql_even.= "'".$_SERVER['REMOTE_ADDR']."', 'Insercion cabecera de orden compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);

		// Update a estado de la solicitud de Compra asocisada ///////////////////////////////	
		if(!empty($_POST["id_solicitud_compra"])){											//																	
			$up_sol_OC = "UPDATE solicitudes_compra SET estado=5 ";							//
			$up_sol_OC.= "WHERE  id_solicitud_compra='".$_POST["id_solicitud_compra"]."'";	//
			mysql_query($up_sol_OC); 														//
		}																					//
		//////////////////////////////////////////////////////////////////////////////////////

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$_POST["id_solicitud_compra"]."', '3'";
        $sql_even.= ", 'UPDATE:estado=5', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion estado de solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);

		
		//Guardo el detalle de la OC
        $consulta7 = "SELECT MAX(id_oc) as id_oc FROM cabecera_oc WHERE rut_empresa='".$_SESSION["empresa"]."'";
        $resultado7=mysql_query($consulta7);
        $id_oc=mysql_fetch_array($resultado7);

		$g=1;
		while($g<=$_POST['cantidad'])
		{
			if(empty($_POST['visible'.$g]))
			{
				$sql_detalle =" INSERT INTO detalle_oc(";
				$sql_detalle.= "id_oc, id_solicitud_compra, rut_empresa";
				$sql_detalle.= ", cantidad, unidad, descripcion";
				$sql_detalle.= ", precio,total,usuario_ingreso";
				$sql_detalle.= ", fecha_ingreso";
				$sql_detalle.=") VALUES (";
				$sql_detalle.="'".$id_oc["id_oc"]."', '".$_POST['id_solicitud_compra']."', '".$_SESSION['empresa'] ."'";
				$sql_detalle.=", '".$_POST['cantidad'.$g]."', '".$_POST['unidad'.$g]."', '".$_POST['descripcion'.$g]."'";
				$sql_detalle.=", '".$_POST['precio'.$g]."', '".$_POST['total'.$g]."', '".$_SESSION['user']."'";
				$sql_detalle.=", '".date('Y-m-d h:i:s')."'";
				$sql_detalle.=" )";
				$consulta2 = mysql_query($sql_detalle);

				$consulta = "SELECT MAX(id_oc) as id_oc FROM detalle_oc WHERE rut_empresa='".$_SESSION["empresa"]."'";
	            $resultado=mysql_query($consulta);
	            $fila=mysql_fetch_array($resultado);
				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
		        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
		        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_oc', '".$_POST['id_det_oc'.$g]."', '2'";
		        $sql_even.= ", 'INSERT:";
		        $sql_even.= "id_oc=".$_POST['id_oc'].", rut_empresa=".$_SESSION['empresa'].", cantidad=".$_POST['cantidad'.$g]."";
		        $sql_even.= ", unidad=".$_POST['unidad'.$g].", descripcion=".$_POST['descripcion'.$g].", precio=".$_POST['precio'.$g].", total=".$_POST['total'.$g]."";
		        $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d h:i:s').", estado_detalle_oc=0'";
		        $sql_even.= ", ".$_SERVER['REMOTE_ADDR']."', 'insercion detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
		        mysql_query($sql_even, $con);

			}
			$g++;
		}

		if($consulta1  && $consulta2){
			$mensaje=" Inserción Correcta";
			$mostrar=1;
		}
	}




	// Update a estado de la solicitud de Compra asocisada ///////////////////////////	
	/*if(!empty($_POST["id_solicitud_compra"]))
	{
			$up_sol_OC = "UPDATE  solicitudes_compra SET estado=5 ";						//
			$up_sol_OC.= "WHERE  id_solicitud_compra='".$_POST["id_solicitud_compra"]."'";
			mysql_query($up_sol_OC);
		
	}														*///
	/////////////////////////////////////////////////////////////////////////////////

	
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

	//Trae los datos del la solicitud
	if($_POST['id_solicitud_compra'])
	{
		//echo "traer datos solicitud";
		$sql_sol="select * from solicitudes_compra WHERE id_solicitud_compra=".$_POST['id_solicitud_compra']." AND rut_empresa='".$_SESSION['empresa']."'";
		$rec_sol=mysql_query($sql_sol);	
		$row_sol=mysql_fetch_array($rec_sol);
		$_POST['solicitante']=$row_sol['solicitante'];
		$_POST['descripcion_solicitud']=$row_sol['descripcion_solicitud'];	
		$_POST['concepto']=$row_sol['concepto'];
		$_POST['centro_costos']=$row_sol['id_cc'];	
	}
?>

<table style="width:1000px;" cellpadding="3" cellspacing="4">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="2"> <a href="?cat=2&sec=15"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Ordenes de Compra"></a></td></tr>
    
    <tr>
</table>
<?
if($mostrar==0)
{
	
?>
<form action="?cat=2&sec=16&action=<?=$action?>" method="POST" name='f1' id="f1">
<input  type='hidden' name='primera' value='1'/>
<!-- <input  type='hidden' name='usuario_ingreso' value='<?/*=$row['usuario_ingreso'];*/?>'/> -->
<div style="width:100%; height:auto; ">
<table style="width:100%;" cellpadding="3" cellspacing="4" border="0">
            <tr id="cabecera_1" style="height: 100px;">
                <td colspan="2" width="100px" style="text-align: left;"><img src="img/sacyr.png" border="0" width="150px"></td>
                <td colspan="2"></td>
                <td colspan="2" width="100px">
                    <table width="100%" style="text-align:center;">
                        <tr><td><?=$row_emp['direccion'];?></td></tr>
                        <tr><td><?="Comuna ".$row_emp['comuna']."   ".$row_emp['ciudad'];?></td></tr>
                        <tr><td><?=$row_emp['telefono_1'];?></td></tr>
                        <tr><td><?=$row_emp['fax'];?></td></tr>
                        <tr>
                        <td style=" font-weight:bold; font-size:28px;">Número: <?
                            if(empty($_GET['id_oc']))
							echo $row_oc["id_oc"];
							else
							echo "<input type='text' name='id_oc' value='".$_POST['id_oc']."' readonly style='width:30px; border:0;font-weight:bold; font-size:28px;background-color:transparent'   >";?>
                            <td>
                        </tr>
                        </table>
</table>
</div>
<div>
    <table style="margin-right: 0px;float:right;" border="0" width="100%">
              <tr><td style="text-align: right;"><b>Solicitud Compra:</b><label style='color:red' >(*)</label> </td>
            <td >
             <input  name="id_solicitud_compra" onKeyPress='ValidaSoloNumeros()'  value="<? echo $_POST['id_solicitud_compra']; ?>"   style=" width:120px;" onchange="submit()"  <? if(!empty($_GET['id_oc'])) echo "readonly"; ?> class="fo">                          
                    <?
		if(!empty($_POST['solicitante']))
		{
			echo "<input type='text' readonly name='descripcion_solicitud' value='".$_POST['descripcion_solicitud']."'  class='fo' style='width:300px'></td>";
		}
        ?> 
            </td>
            	
        </tr>
        <tr><td style="text-align: right;" width="150px"><b>Solicitante:</b><label style='color:red' >(*)</label> </td><td><input readonly type="text" size="50" name="solicitante" value="<?=$_POST['solicitante'];?>" style=" width:300px;"  class="fo" >
   
        </tr>
        <tr><td style="text-align: right;" ><b>Concepto:</b><label style='color:red' >(*)</label> </td><td>
        <select name="concepto" style=" width:100px;" class="fo" disabled="disabled">
        <option value=""> --- </option>
        <option value="1" <? if($_POST['concepto']==1){ echo "SELECTED" ;}?>>Mantenci&oacute;n</option>
        <option value="2" <? if($_POST['concepto']==2){ echo "SELECTED" ;}?>>Reparaci&oacute;n</option>
        <option value="3" <? if($_POST['concepto']==3){ echo "SELECTED" ;}?>>Servicios</option>
        <option value="4" <? if($_POST['concepto']==4){ echo "SELECTED" ;}?>>Activos</option> 
        <option value="5" <? if($_POST['concepto']==5){ echo "SELECTED" ;}?>>Repuestos</option>
        <option value="6" <? if($_POST['concepto']==6){ echo "SELECTED" ;}?>>Fabricacion</option>
        <option value="7" <? if($_POST['concepto']==7){ echo "SELECTED" ;}?>>Rectificación</option> 
        </select>

    </td>
        </tr>
        <tr><td style="text-align: right;"><b>Centro Costo:</b><label style='color:red' >(*)</label>  </td>
        <td>
        <select name="centro_costos"  style=" width:200px;" class="fo" disabled="disabled">
                <option value=""> --- </option>
                <?
                    $cc ="SELECT * FROM centros_costos where rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                    $rc = mysql_query($cc,$con);
                     while($ce = mysql_fetch_assoc($rc)){
                     ?>
                    <option value="<?=$ce['Id_cc'];?>" <? if($_POST['centro_costos']==$ce['Id_cc']){ echo "SELECTED" ;}?>><?=$ce['descripcion_cc'];?></option>
                     <?   
                    }
                ?>
        </select> 
        <input type='hidden' name='centro_costos' value='<?=$_POST["centro_costos"];?>'>
        </td>
        </tr>
        
        <tr>
        	<td style="text-align:right; font-weight:bold;">Fecha:</td>
        	<td><?
				if(empty($_GET['id_oc']))
				echo date('d-m-Y');
				else
				echo "<input type='text' name='fecha_oc' value='".$_POST['fecha_oc']."' readonly class='fo' style='width:120px'>";
				?>
			</td>
        </tr>
        <tr>
        	<td  style="text-align: right;"><b>Comparativo General:</b></td>	
            <td id="link-solic-oc">
                <? 
                    if(!empty($_POST['id_solicitud_compra'])){
                        $sql_cg = "SELECT * FROM archivos WHERE estado_archivo=1 and id_solicitud=".$_POST['id_solicitud_compra'];
                        $rew_cg = mysql_query($sql_cg,$con);
                        $r = mysql_fetch_assoc($rew_cg);
                       	echo "<a href='".$r["ruta_archivo"]."' target='_blank'>".$r["nombre_archivo"]."</a> <input type='text' name='archivo' value='".$r['id_archivo']."' hidden='hidden'>";


                    }
                ?>
                
                
            </td>
        </tr>

        <!-- <tr>
        	<td  style="text-align: right;"><b>Comparativo General:</b></td>	
        	<td><a href="<?/*=$r['ruta_archivo'];*/?>" target="_blank" style='text-decoration:none; font-size:13px;'>ver</a></td>
        </tr> -->
         <tr>
				<td  style="text-align: right;"><b>Moneda:</b><label style='color:red'>(*)</label></td>
				<td><input type="text" name="moneda" value="<?=$_POST['moneda'];?>" class='fo' style='width:100px'></td>
		</tr>

    	<tr><td  style="text-align: right;"><b>Especialización:</b><label style='color:red'>(*)</label></td>
        	<td align="left" style="text-align:left"><select name="id_esp"  style=" width:200px;" class="fo" onchange='submit()'>
                <option value=""> --- </option>
                <?
                    $cc ="SELECT * FROM especialidades where 1=1  ORDER BY descripcion_esp";
                    $rc = mysql_query($cc,$con);
                     while($ce = mysql_fetch_assoc($rc)){
                     ?>
                    <option value="<?=$ce['id_esp'];?>" <? if($_POST['id_esp']==$ce['id_esp']){ echo "SELECTED" ;}?>><?=$ce['descripcion_esp'];?></option>
                     <?   
                    }
                ?>
        </select> 
</td>
    </tr>
        <tr>
            <td width="145px;"id="proveedor_selec" style=" text-align:right;"><b>Proveedor:</b><label style='color:red'>(*)</label></td>
            <td>
               <select name="proveedor"  class='fo' style='width:200px'  onchange="submit()"  >
                    <option value=""> --- </option>
                    <? $sql = "SELECT p.* FROM proveedores p,especialidades e
							   WHERE rut_empresa = '".$_SESSION['empresa']."' and e.id_esp=p.id_esp and  e.id_esp = '".$_POST['id_esp']."'
							    ";
							   
					       $res = mysql_query($sql,$con);
                       while($row = mysql_fetch_assoc($res))
					   {
							echo "<option value=".$row['rut_proveedor'].""; if($row['rut_proveedor']==$_POST['proveedor']) echo " selected "; echo">".$row['razon_social']."</option>";   
					   }
					?>
               </select>
               
         </td>
      </tr>
      </table>
      <table>
         </tr>
               <? if($_POST['proveedor']!=""){
                        $sql ="SELECT * FROM proveedores WHERE rut_proveedor='".$_POST['proveedor']."'";
                        $res = mysql_query($sql,$con);
                        $row = mysql_fetch_assoc($res);
					    $html="";
						echo "<table border='0' width='100%'>";
                        $html.='<tr>';
                        $html.='<td width="150px" style="text-align: right;"><b>Razón Social:</b></td>';
                        $html.="<td width='150px' style='text-align: left;' ><input type='text'2 value='".$row['razon_social']."' style='width:Auto;width:250px;' class='fo' readonly></td>";
         
                        $html.='<td width="150px" style="text-align: right;"><b>Rut:</b></td>';
                        $html.="<td width='150px' style='text-align: left;'><input type='text' value='".$row['rut_proveedor']."' style='width:Auto;width:150px;' readonly class='fo' ></td>";
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td width="150px" style="text-align: right;"><b>Domicilio:</b></td>';
                        $html.="<td style='text-align: left;' width='200'><input type='text' value='".$row['domicilio']."' style='width:Auto;width:250px;' readonly class='fo'></td>";
        
                        $html.='<td style="text-align: right;"><b>Comuna:</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['comuna']."' style='width:Auto;width:150px;' readonly class='fo'></td>";
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td style="text-align: right;"><b>Ciudad:</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['ciudad']."' style='width:Auto;' readonly class='fo'></td>";
                       
                        $html.='<td style="text-align: right;"><b>Celular:</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['celular']."' style='width:Auto' readonly class='fo'></td>";
                   
                        $html.='<td style="text-align: right;"><b>Telefono:</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['telefono_1']."' style='width:Auto' readonly class='fo'></td>";
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td style="text-align: right;"><b>ATT (SR/A):</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['contacto']."' style='width:Auto' readonly class='fo'></td>";
                     
                        $html.='<td style="text-align: right;"><b>Email:</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['mail']."' style='width:Auto;width:140px;' readonly class='fo'></td>";
                     
                        $html.='<td style="text-align: right;"><b>Fax:</b></td>';
                        $html.="<td style='text-align: left;'><input type='text' value='".$row['fax']."' style='width:Auto' readonly class='fo'></td>";
                        $html.='</tr>';
                        $html.='</table>';

                        echo $html;
               
               } ?>	
		</td>
        </tr>
    </table>
</div>

<table style="width:90%; border-collapse:collapse;" id="detalle-prov" class="detalle-oc"  cellpadding="3" cellspacing="4" align="center">
      <tr>
	      <td colspan="6" style="text-align:right;">
		      	<? if($_POST['estado_oc']!=4)
		      	{
		      	?>
		      	<button type="submit" name="agregar" value='1' ><img src="img/add1.png" width="20" height="20" /></button>
		     	<?
		     	}
		     	?>
	      </td>
      </tr>
      <tr>
            
            <td style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px; text-align:center" width="10%"><b>Cantidad</b></td>
            <td style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px; text-align:center" width="30%"><b>Unidad</b></td>
            <td style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px; text-align:center" width="30%"><b>Descripci&oacute;n</b></td>
            <td style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px; text-align:center" width="10%"><b>Precio</b></td>
            <td style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px; text-align:center" width="10%"><b>Total</b></td>
            <td style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px; text-align:center" width="10%"><b>Eliminar</b></td>
      </tr>
      <input  type='hidden' name="cantidad" value='<? echo $_POST['cantidad'];?>'/>
        
        <style>
		.detalle
		{
			text-align:center; 
			background-color:#EBEBEB;
			border:1px solid #666;
		}
		</style>
        <?
        	
        	// BLOQUEAR EN ESTADO: APROBADA
        	if($_POST["estado_oc"]==4) $readonly = 'readonly';	
        	
			while(($_POST['cantidad']>=$i))
			{
 				
				echo "<tr>";
				if(empty($_POST['visible'.$i]))
				{
				echo "<td class='detalle' width='80px'><input type='text' name='cantidad".$i."'    value='".$_POST['cantidad'.$i]."' onKeyPress='ValidaSoloNumeros()' onchange='calcular".$i."()'  style='width:100px; text-align:center; border:#09F solid 1px;' ".$readonly."></td>";
				echo "<td class='detalle'><input type='text' name='unidad".$i."'      value='".$_POST['unidad'.$i]."'  style='border:#09F solid 1px; ' ";if($_POST['estado_oc']==4)echo " readonly ";  echo" ></td>";
				echo "<td class='detalle'><input type='text' name='descripcion".$i."' value='".$_POST['descripcion'.$i]."'  style='border:#09F solid 1px;width:350px;'"; if($_POST['estado_oc']==4)echo " readonly "; echo" ></td>";
				echo "<td class='detalle'><input type='text' name='precio".$i."'      value='".$_POST['precio'.$i]."' onchange='calcular".$i."()' style='width:100px; text-align:right;border:#09F solid 1px;' onKeyPress='ValidaSoloNumeros()' "; if($_POST['estado_oc']==4)echo " readonly "; echo "></td>";
				echo "<td class='detalle'><input type='text' name='total".$i."'       value='".$_POST['total'.$i]."' readonly  style='width:100px; text-align:right;border:#09F solid 1px;' ></td>";
				
					if($_POST['estado_oc']!=4)
      				{
              			echo "<td class='detalle'><button name='eliminar' value='".$i."' ><img src='img/borrar.png' width='16' height='16' /> </button></td>";
					}
					else
					{
						echo "<td class='detalle'></td>";
					}
				}
				else
				{
				echo "<input type='hidden' name='total".$i."'       value='".$_POST['total'.$i]."' readonly  style='width:100px; text-align:right'>";
				}
				echo "<input type='hidden' name='visible".$i."' value='".$_POST['visible'.$i]."' />";
				echo "<input type='hidden' name='id_det_oc".$i."' value='".$_POST['id_det_oc'.$i]."' />";
				echo "</tr>";
				$i++;
		    }
			
			
		?>
</table>

<table width="210px" border="0" align="right" style="border-collapse:collapse; margin-right:51px;">
    <tr>
    <td ></td>
    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0); font-weight:bold;"  width="100px">SubTotal:</td>
    <td  style="text-align:center;border:1px solid rgb(0,0,0);" width="100px;"><input type="text" name="subtotal" value='<? echo $_POST['subtotal'];?>' style="width:80px; text-align:right" readonly  class="fo"/></td>					<tr>
    <tr>
    <td></td>
    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Descuento:</td>
    <td  style="text-align:center;border:1px solid rgb(0,0,0);" ><input type="text" onchange="calcular1()"  onKeyPress='ValidaSoloNumeros()' name="descuento" value='<? echo $_POST['descuento'];?>' style="width:80px; text-align:right" class="fo" /></td>					<tr>
    <tr>
    <td></td>
    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Neto:</td>
    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="valor_neto" value='<? echo $_POST['valor_neto'];?>' style="width:80px; text-align:right"  readonly class="fo" /></td>					<tr>
    <tr>
    <td></td>
    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">IVA:<input onKeyPress='ValidaSoloNumeros()' onchange="calcular1()" type="text" name='valor_iva' style="width:20px;" value='<? echo $_POST['valor_iva'] ?>'>%</td>
    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="iva" value='<? echo $_POST['iva'];?>'  style="width:80px; text-align:right"  readonly  class="fo"/></td>					<tr>
    <tr>
    <td></td>
    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Total:</td>
    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="total_doc" value='<? echo $_POST['total_doc'];?>' style="width:80px; text-align:right"  readonly class="fo" /></td>					<tr>
		
</table>

<table width="100%" style="margin-top:25px;" border="0">
 <tr>
 <td  style="text-align: right;" width="250px"><b>Forma Pago:</b></td><td width="850px">
             
                        <select name="forma_de_pago"  style=" color:#000; background-color:#FFFFFF; width:200px"class="fo" >
                                    <option value="0" <? if($_POST['forma_de_pago']==0){ echo "SELECTED";}?>>Seleccione Forma de Pago</option>
                                    <option value="2" <? if($_POST['forma_de_pago']==2){ echo "SELECTED";}?>>Al Contado</option>
                                    <option value="3" <? if($_POST['forma_de_pago']==3){ echo "SELECTED";}?>>30 Dias</option>
                                    <option value="4" <? if($_POST['forma_de_pago']==4){ echo "SELECTED";}?>>45 Dias</option>
                                    <option value="5" <? if($_POST['forma_de_pago']==5){ echo "SELECTED";}?>>50% Inicio 50% contra entrega</option>
    </select>
 </tr>
 <tr>
 <td  style="text-align: right;" ><b>Fecha Entrega: </b></td>
 <td ><input type="date" name="fecha_entrega" value='<? echo $_POST['fecha_entrega'];?>' class="fo"  style="width:120px;"></td>
 </tr>
 <tr>
     <td style="text-align: right;"><b>Sometido al Plan de Calidad</b></td><td><input type="checkbox" name="sometido_plan_calidad"   <? if($_POST['sometido_plan_calidad']==1){echo "checked"; }?> value='1'></td>
 </tr>
 <tr>
     <td style="text-align: right;"><b>Adjunta Especificación Técnica</b></td><td><input type="checkbox" name="adj_esp_tecnica" <? if($_POST['adj_esp_tecnica']==1){echo "checked"; }?>  value='1'></td>
                                        
  </tr>
  <tr>
     <td style="text-align: right;"><b>Adjunta Propuesta Económica</b></td><td><input type="checkbox" name="adj_propuesta_economica" <? if($_POST['adj_propuesta_economica']==1){echo "checked";}?> value='1'></td>
</tr>
<?
/* Si es menor a 50000 queda Aprobada
automaticamente.*/

// if($_POST['total_doc']<=50000) $_POST['estado_oc'] = 4;
?>
<tr height="20px;">
	<td style="text-align: right;"><b>Estado Orden de Compra</b></td>
    <td>
      	<select name="estado_oc" <? if(empty($_GET['id_oc']) || $_POST['total_doc']<=50000 || ($_POST['estado_oc']==4 && $error==0)){ echo "DISABLED";}?>  style=" color:#000; background-color:#FFFFFF; width:100px;" class="fo" >
                <option value="1" <? if($_POST['estado_oc']==1){ echo "SELECTED";}?>>Abierta</option>
                <option value="2" <? if($_POST['estado_oc']==2){ echo "SELECTED";}?>>Pendiente</option>
                <option value="3" <? if($_POST['estado_oc']==3){ echo "SELECTED";}?>>Cerrada</option>
                <?
                // Obtengo jefatura del usuario
                $sel_cargo = "SELECT jefatura, backup_jefatura FROM usuarios WHERE usuario='".$_SESSION["user"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
                $res_cargo = mysql_query($sel_cargo);
                $row_cargo = mysql_fetch_array($res_cargo);
                if((($row_cargo["jefatura"]==5 && $_POST['total_doc']<=20000000) || ($row_cargo["backup_jefatura"]==5 && $_POST['total_doc']<=20000000)) || (($row_cargo["backup_jefatura"]==6) || ($row_cargo["jefatura"]==6))){
                // if(($_POST["vb_jefe_grupo_obras"]==1) || ($_POST["vb_gerente_general"]==1)){
                ?>
                	<option value="4" <? if($_POST['estado_oc']==4){ echo "SELECTED";}?>>Aprobada</option>
                <?}?>
                <option value="5" <? if($_POST['estado_oc']==5){ echo "SELECTED";}?>>Rechazada</option>
    	</select>
    </td>
</tr>

<tr height="20px;"  style="text-align:right"><td><b>Observaciones:</b></td>
<td style="text-align:left"><input type="text" name='observaciones' value='<? echo $_POST['observaciones'];?>' style=" width:300px;" class="fo" /></td></tr>
<tr height="20px;" style="text-align:right"><td><b> Observaciones 1:</b></td>
<td style="text-align:left"><input type="text" name='observaciones1' value='<? echo $_POST['observaciones1'];?>' style=" width:300px;" class="fo" /></td></tr>
<tr height="20px;"  style="text-align:right"><td><b>Observaciones 2:</b></td>
<td style="text-align:left"><input type="text" name='observaciones2' value='<? echo $_POST['observaciones2'];?>' style=" width:300px;" class="fo" /></td></tr>



<tr height="20px;"  style="text-align:right"><td><b>Factura:</b></td>
<td style="text-align:left"><input type="text" name='factura' value='<? echo $_POST['factura'];?>' style=" width:300px;"  class="fo"/></td></tr>
<tr height="20px;">
</tr>
<tr>
                <td colspan="100%" style="text-align: center;font-size: 11px;border: 1px solid;">
                    <p>De conformidad a lo prescrito en la Ley Nº 19.983 del Año 2007, que REGULA LA TRANSFERENCIA Y OTORGA MERITO EJECUTIVO A COPIA DE LA</p>
                    <p>FACTURA, las partes acuerdan que el plazo indicado en el Número 3 del Artículo 2º de dicha Ley, para reclamar el contenido de las facturas presentadas</p>
                    <p>por el Proveedor, Subcontratista, Contratista o como se le tenga denomidado en el Contrato o en la <b>Órden de Compra, será de 30 DÍAS.</b></p>
                </td>
</tr>
 <tr>
 <td colspan="100%" width="100%" style="font-size: 10px;padding-top:15px; padding-bottom: 15px;border: 1px solid; text-align:center;">
                                <b>De acuerdo a lo conversado, rogamos emitan la factura a: SACYR CHILE S.A.<br/>AV VITACURA Nº 2939 OFICINA 1102 Las Condes, Santiago   R.U.T. 96.786.880 - 9<br/>ADJUNTAR COPIA DE ORDEN DE COMPRA A LA FACTURA</b>
  </td>
 </tr>
</table>

<!-- VISTOS BUENOS -->

<?
$sql_vb = "SELECT * FROM cabecera_oc WHERE id_oc=".$_GET['id_oc']." AND rut_empresa='".$_SESSION['empresa']."'";
$res_vb = mysql_query($sql_vb);
$row_vb = mysql_fetch_assoc($res_vb);
?>
<table width="100%">
	<tr>
            <td style="height: 150px; width: 15%;border: 1px solid; text-align:center">
            	<b>VºBº Depto Compras</b><br/>
                <input type="checkbox" name="vb_depto_compras" <? if($_POST['vb_depto_compras']==1){ echo "checked";} if($c1!=1 && $c2!=1) echo " disabled ";?> value="1" ><br>
               	
               	<? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_depto_compras"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				// if(($c1==1 || $c2==1) || $row_vb["vb_depto_compras"]==1){
               	if($row_vb["vb_depto_compras"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:16px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?if(empty($row_usu1["nombre"])){ echo "VºBº Automático"; }else{ echo $row_usu1["nombre"];}?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_depto_compras"];?></label>
               	<?}
				?>

            </td>
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center">
                <b>VºBº Jefe Compras</b><br/>
                <input type="checkbox" name="vb_jefe_compras" <? if($_POST['vb_jefe_compras']==1){ echo "checked";}  if($c1!=2 && $c2!=2) echo " disabled ";?> value="1" ><br>
            	
                <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_jefe_compras"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				// if(($c1==2 || $c2==2) || $row_vb["vb_jefe_compras"]==1){
               	if($row_vb["vb_jefe_compras"]==1){

					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:16px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?if(empty($row_usu1["nombre"])){ echo "VºBº Automático"; }else{ echo $row_usu1["nombre"];}?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_jefe_compras"];?></label>
               	<?}
				?>

            </td>

            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center">
                <b>VºBº Jefe Administracion</b><br/>
                <input type="checkbox" name="vb_jefe_adm" <? if($_POST['vb_jefe_adm']==1){ echo "checked";} if($c1!=3 && $c2!=3) echo " disabled ";?> value="1"><br>
            	
            	 <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_jefe_adm"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				// if(($c1==3 || $c2==3) || $row_vb["vb_jefe_adm"]==1){
               	if($row_vb["vb_jefe_adm"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:16px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?if(empty($row_usu1["nombre"])){ echo "VºBº Automático"; }else{ echo $row_usu1["nombre"];}?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_jefe_adm"];?></label>
               	<?}
				?>

            </td>    
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center">
                <b>VºBº Jefe Parque Maquinarias </b><br/>
                <input type="checkbox" name="vb_jefe_parque_maquinaria" <? if($_POST['vb_jefe_parque_maquinaria']==1){ echo "checked";} if($c1!=4 && $c2!=4) echo " disabled ";?> value="1"><br>
            
                 <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_jefe_pm"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				// if(($c1==4 || $c2==4) || $row_vb["vb_jefe_parque_maquinaria"]==1){
               	if($row_vb["vb_jefe_parque_maquinaria"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:16px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?if(empty($row_usu1["nombre"])){ echo "VºBº Automático"; }else{ echo $row_usu1["nombre"];}?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_jefe_pm"];?></label>
               	<?}
				?>

            </td>
            <td style="height: 100px; width:15%;border: 1px solid; text-align:center">
                <b>VºBº Jefe Grupo de Obras</b><br/>
                <input type="checkbox" name="vb_jefe_grupo_obras" <? if($_POST['vb_jefe_grupo_obras']==1){ echo "checked";} if($c1!=5 && $c2!=5) echo " disabled ";?> value="1"><br>
            
                 <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_grupo_obras"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				// if(($c1==5 || $c2==5) || $row_vb["vb_jefe_grupo_obras"]==1){
               	if($row_vb["vb_jefe_grupo_obras"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:16px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?if(empty($row_usu1["nombre"])){ echo "VºBº Automático"; }else{ echo $row_usu1["nombre"];}?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_grupo_obras"];?></label>
               	<?}
				?>

            </td>
<? 
if($_POST['total_doc']>=20000000){
?>
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center">
                <b>VB Gerente General </b><br/>
                <input type="checkbox" name="vb_gerente_general" <? if($_POST['vb_gerente_general']==1){ echo "checked";} if($c1!=6 && $c2!=6) echo " disabled ";?> value="1"><br>
            	
            	<? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_gerente_general"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				// if(($c1==6 || $c2==6) || $row_vb["vb_gerente_general"]==1){
               	if($row_vb["vb_gerente_general"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:16px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?if(empty($row_usu1["nombre"])){ echo "VºBº Automático"; }else{ echo $row_usu1["nombre"];}?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_gerente_general"];?></label>
               	<?}
				?>

            </td>
<?
}

?>
	</tr>	
</table>

<table width="100%">
<tr>
       <td colspan="6" style="text-align: center;font-size: 11px;border: 1px solid;">
       <p><b>Horario Recepción de Materiales:   Lunes a Jueves de: 8:30 a 12:30 y de 14:30 a 18:00;   Viernes de: 8:30 a 12:00 Hrs.</b></p>
       </td>
</tr>
</table>

<table align="center">
   	<tr>
    	<td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
              (*) Campos de Ingreso Obligatorio.
        </td>
   	</tr>
	<tr>
		<td align="center">
			<?
			// if($_POST["estado_oc"]!=4 || ($_POST['estado_oc']==4 && empty($_POST["error"]))){
			$sql5 = "SELECT estado_oc FROM cabecera_oc WHERE id_oc=".$_GET['id_oc']." AND rut_empresa='".$_SESSION['empresa']."'";
	        $res5 = mysql_query($sql5);
	        @$row5 = mysql_fetch_assoc($res5);
			if($row5['estado_oc']!=4 AND empty($no_edit)){
			?>
			<button name='procesar' value='1' type="submit" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;">Procesar</button>
			<?}?>
		</td>
	</tr>
</table>
</form>    
<?
 }
 // var_dump($_POST);
?>


<?
 $k=1;
 while(($_POST['cantidad']>=$k))
 {
 echo "<script>
 ";
 echo "function calcular".$k."()
 ";
 echo "{
 ";
 echo "document.f1.total".$k.".value=document.f1.cantidad".$k.".value*document.f1.precio".$k.".value;
 ";
 echo " subtotal=0;
 ";
echo "	total=0;
";
echo "	iva=0;
";
for($i=1;$i<=$_POST['cantidad'];$i++)
{
	//Valida si esta visible
	echo " if(document.f1.visible".$i.".value!=1)
	";
	echo " {
	";
		//Si esta visible y esta vacio le pone 0
		echo " if(document.f1.total".$i.".value=='')
		";
		echo " {
		";
		echo " document.f1.total".$i.".value=0
		";
		echo " }
		";
	echo " subtotal=subtotal+parseInt(document.f1.total".$i.".value);
	";
	echo " }
	";
}
echo " (document.f1.subtotal.value=subtotal)
";
echo " 	document.f1.valor_neto.value=document.f1.subtotal.value-document.f1.descuento.value;
";
echo " 	iva=(document.f1.valor_neto.value)*((document.f1.valor_iva.value)/100);
";
echo "  iva=iva.toFixed(0);
";
echo " 	document.f1.iva.value=iva;
";
echo " 	total=(document.f1.valor_neto.value)*(((document.f1.valor_iva.value)/100)+1);
";
echo " 	total=total.toFixed(0);
";
echo "  	document.f1.total_doc.value=total;
";
echo "\n if(total>20000000) \n";
echo "\n { \n";
echo "\n document.f1.submit()\n";
echo "\n } \n";


echo "}
 ";
echo "</script>
 ";
 $k++;
 }
 

?>






