    <style>
	.fo
	{
		border:1px solid #09F;
		background-color:#FFFFFF;
		color:#000066;
		font-size:10px;
		font-family:Tahoma, Geneva, sans-serif;
		width:80%;
		text-align:left;
	}
	
	td
	{
		font-family:Tahoma, Geneva, sans-serif;
		font-size:10px;
	}
	
	</style>


<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>
<script type="text/javascript">
function imprSelec(muestra)
{
	$("#boton_im").css("display" ,'none');
	$("#volver").css("display" ,'none');
	var ficha=document.getElementById(muestra);var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);ventimp.document.close();ventimp.print();ventimp.close();}
</script>
<?php
	
	//edita la accion
	$action="2&id_oc=".$_GET['id_oc'];
	$mostrar=0;
	$error=0;
	
	if(empty($_POST['id_solicitud_compra']) and !empty($_POST['procesar']))
	{
		$mensaje=" Debe ingresar una solicitud ";
		$error=1;
	}
	


	//Datos de la cabecera
    $sql_emp = "SELECT * FROM empresa WHERE rut_empresa='".$_SESSION['empresa']."'";
    $res_emp = mysql_query($sql_emp,$con);
    $row_emp = mysql_fetch_assoc($res_emp);
	
	//Trae los datos del la solicitud
	if($_POST['id_solicitud_compra']) 
	{
		//echo "traer datos solicitud";
		$sql_sol="select * from solicitudes_compra WHERE id_solicitud_compra=".$_POST['id_solicitud_compra']." AND rut_empresa='".$_SESSION['empresa']."'";
		$rec_sol=mysql_query($sql_sol);	
		$row_sol=mysql_fetch_array($rec_sol);
		$num=mysql_num_rows($rec_sol);
		$_POST['concepto']=$row_sol['concepto'];
		$_POST['solicitante']=$row_sol['solicitante'];
		$_POST['descripcion_solicitud']=$row_sol['descripcion_solicitud'];	
		$_POST['centro_costos']=$row_sol['id_cc'];
		if($num==0)
		{
			$_POST['id_solicitud_compra']="";
			$mensaje=" Debe ingresar una solicitud  valida";
			$error=1;
		}
	}

	
	//Calcila la cantidad de  OC
	if(empty($_GET['id_oc']))
	{
		$sql="SELECT COUNT(*) FROM cabecera_oc WHERE rut_empresa='".$_SESSION['empresa']."' ";
		$rec_num=mysql_query($sql);
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
		 $_POST['concepto']=$row['concepto'];
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
	
	//Si hace POST el boton y si esta vacio el GET_['id_oc'] guardo si no actualizo;	
	if(!empty($_GET['id_oc']) and !empty($_POST['procesar']) and ($error==0))
	{
		$sql = " UPDATE  cabecera_oc SET ";	
		$sql.= " id_solicitud_compra='".$_POST['id_solicitud_compra']."',";	
		$sql.= " rut_empresa='".$_SESSION['empresa']."',";	
		$sql.= " fecha_oc='".$_POST['fecha_oc']."',";
		$sql.= " solicitante='".$_POST['solicitante']."',";	
		$sql.= " concepto='".$_POST['concepto']."',";	
		$sql.= " centro_costos='".$_POST['centro_costos']."',";
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
		//Valida el Visto Bueno del primero
		if(empty($_POST['vb_depto_compras'] ))
		{
			$sql.="vb_depto_compras='',";
			$sql.="nombre_vb_depto_compras='',";
			$sql.="fecha_vb_depto_compras='".date('Y-m-d')."',";
		}
		else
		{
			$var++;
			$sql.="vb_depto_compras='".$_POST['vb_depto_compras']."',";
			$sql.="nombre_vb_depto_compras='vb_Depto_Compras',";
			$sql.="fecha_vb_depto_compras='".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del segundo
		if(empty($_POST['vb_jefe_compras'] ))
		{
			$sql.="vb_jefe_compras='',";
			$sql.="nombre_vb_jefe_compras='',";
			$sql.="fecha_vb_jefe_compras='".date('Y-m-d')."',";
		}
		else
		{
			$var++;
			$sql.="vb_jefe_compras='".$_POST['vb_jefe_compras']          ."',";
			$sql.="nombre_vb_jefe_compras='vb_Jefe_de_Compras',";
			$sql.="fecha_vb_jefe_compras='".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del tercero
		if(empty($_POST['vb_jefe_adm'] ))
		{
			$sql.="vb_jefe_adm='',";
			$sql.="nombre_vb_jefe_adm='',";
			$sql.="fecha_vb_jefe_adm='".date('Y-m-d')."',";
		}
		else
		{
			$var++;
			$sql.="vb_jefe_adm='".$_POST['vb_jefe_adm']          ."',";
			$sql.="nombre_vb_jefe_adm='vb_Jefe_de_Administracion',";
			$sql.="fecha_vb_jefe_adm='".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del cuarto
		if(empty($_POST['vb_jefe_parque_maquinaria'] ))
		{
			$sql.="vb_jefe_parque_maquinaria='',";
			$sql.="nombre_vb_jefe_pm='',";
			$sql.="fecha_vb_jefe_pm='".date('Y-m-d')."',";
		}
		else
		{
			$var++;
			$sql.="vb_jefe_parque_maquinaria='".$_POST['vb_jefe_adm']          ."',";
			$sql.="nombre_vb_jefe_pm='vb_Jefe_Parque_Maquinaria',";
			$sql.="fecha_vb_jefe_pm='".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del quinto
		if(empty($_POST['vb_jefe_grupo_obras'] ))
		{
			$sql.="vb_jefe_grupo_obras='',";
			$sql.="nombre_vb_grupo_obras='',";
			$sql.="fecha_vb_grupo_obras='".date('Y-m-d')."',";
		}
		else
		{
			$var++;
			$sql.="vb_jefe_grupo_obras='".$_POST['vb_jefe_grupo_obras']          ."',";
			$sql.="nombre_vb_grupo_obras='vb_Jefe_Grupo_Obras',";
			$sql.="fecha_vb_grupo_obras='".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del Sexto
		if(empty($_POST['vb_gerente_general'] ))
		{
			$sql.="vb_gerente_general='',";
			$sql.="nombre_vb_gerente_general='',";
			$sql.="fecha_vb_gerente_general='".date('Y-m-d')."',";
		}
		else
		{
			$var++;
			$sql.="vb_gerente_general='".$_POST['vb_gerente_general']          ."',";
			$sql.="nombre_vb_gerente_general='vb_Gerente_General',";
			$sql.="fecha_vb_gerente_general='".date('Y-m-d')."',";	
		}
		$sql.="factura='".$_POST['factura']."',";
		$sql.="observaciones='".$_POST['observaciones']."',";
		$sql.="observaciones1='".$_POST['observaciones1']."',";
		$sql.="observaciones2='".$_POST['observaciones2']."',";
		$sql.="estado_oc='".$_POST['estado_oc']."',";
		$sql.="rut_proveedor='".$_POST['proveedor']."',";
		$sql.="usuario_ingreso='".$_SESSION['user']."',";
		$sql.="fecha_ingreso='".date('Y-m-d')."'";
		$sql.= " WHERE id_oc='".$_GET['id_oc']."'  AND rut_empresa='".$_SESSION['empresa']."'";
		
		if($_POST['estado_oc']!=4)
		{
			mysql_query($sql);
			$mensaje=" Actualización Correcta";

			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'cabecera_oc', '".$_GET['id_oc']."', '3'";
	        $sql_even.= ", 'update:todos los campos', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion de cabecera de orden compra', '1', '".date('Y-m-d H:i:s')."')";
	        mysql_query($sql_even, $con);
			
		}
		else
		{
			if(($var==6))
			{
				mysql_query($sql);
				$mensaje=" Actualización Correcta";
				
			}
			else
			{
				if(($var<6)and ($_POST['total_doc']>=20000000))
				{
					$mensaje=" No posee todos los vistos buenos para aprobar";
					$error++;
				}
				else
				{
					if(($var<5)and ($_POST['total_doc']>=50000))
					{
						$mensaje=" No posee todos los vistos buenos para aprobar";
						$error++;
					}
					else
					{

								mysql_query($sql);
								$mensaje=" Actualización Correcta";

					}
				}
			}

		}

		
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
		        $sql_even.= ", 'update:todos los campos', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion detalle de orden compra', '1', '".date('Y-m-d h:i:s')."')";
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
				$sql_detalle.=" fecha_ingreso='".date('Y-m-d')."',";
				$sql_detalle.=" estado_detalle_oc='1',";
				$sql_detalle.=" usuario_elimina='".$_SESSION['user']."',";
				$sql_detalle.=" fecha_elimina='".date('Y-m-d')."'";	
				$sql_detalle.=" where id_det_oc='".$_POST['id_det_oc'.$g]."' AND rut_empresa='".$_SESSION['empresa']."'";	
				
				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
		        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
		        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_oc', '".$_POST['id_det_oc'.$g]."', '3'";
		        $sql_even.= ", 'update:rut_empresa,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total,usuario_ingreso,
		        fecha_ingreso,estado_detalle_oc,usuario_elimina,fecha_elimina', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
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
					$sql_detalle.="'".date('Y-m-d')."',";
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
	        $sql_even.= ", 'INSERT:rut_empresa,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total,usuario_ingreso,
	        fecha_ingreso,estado_detalle_oc,usuario_elimina,fecha_elimina', '".$_SERVER['REMOTE_ADDR']."', 'INSERCION detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
	        mysql_query($sql_even, $con);
		}
		$mostrar=1;
		
	}
	
	if(empty($_GET['id_oc']) and !empty($_POST['procesar']) and ($error==0))
	{
		//Guardo en la cabecera
		$sql= " INSERT  INTO cabecera_oc(id_solicitud_compra,rut_empresa,fecha_oc,solicitante,concepto,
		centro_costos,atte_oc,moneda,subtotal,descuento,valor_neto,iva,total,forma_de_pago,fecha_entrega,sometido_plan_calidad
	    ,adj_esp_tecnica,adj_propuesta_economica,vb_depto_compras,nombre_vb_depto_compras,		
		fecha_vb_depto_compras,vb_jefe_compras,nombre_vb_jefe_compras,fecha_vb_jefe_compras
		,vb_jefe_adm,nombre_vb_jefe_adm,fecha_vb_jefe_adm
		,vb_jefe_parque_maquinaria,nombre_vb_jefe_pm,fecha_vb_jefe_pm
		,vb_jefe_grupo_obras,nombre_vb_grupo_obras,fecha_vb_grupo_obras
		,vb_gerente_general,nombre_vb_gerente_general,fecha_vb_gerente_general
		,observaciones,observaciones1,observaciones2,estado_oc,rut_proveedor,usuario_ingreso,fecha_ingreso,factura)";
		$sql.=" VALUES( ";
		$sql.="'".$_POST['id_solicitud_compra']."',";
		$sql.="'".$_SESSION['empresa']     ."',";
		$sql.="'". date('Y-m-d H:i:s')           ."',";
		$sql.="'".$_POST['solicitante']        ."',";
		$sql.="'".$_POST['concepto']           ."',";
		$sql.="'".$_POST['centro_costos']           ."',";
		$sql.="'".$_POST['atte_oc']            ."',";
		$sql.="'".$_POST['moneda']             ."',";
		$sql.="'".$_POST['subtotal']           ."',";
		$sql.="'".$_POST['descuento']          ."',";
		$sql.="'".$_POST['valor_neto']         ."',";
		$sql.="'".$_POST['iva']                ."',";
		$sql.="'".$_POST['total_doc']          ."',";
		$sql.="'".$_POST['forma_de_pago']          ."',";	
		$sql.="'".$_POST['fecha_entrega']          ."',";	
		$sql.="'".$_POST['sometido_plan_calidad']          ."',";	
		$sql.="'".$_POST['adj_esp_tecnica']          ."',";	
		$sql.="'".$_POST['adj_propuesta_economica']          ."',";
		
		//Valida el Visto Bueno del primero
		if(empty($_POST['vb_depto_compras'] ))
		{
			$sql.="'',";
			$sql.="'',";
			$sql.="'".date('Y-m-d')."',";
		}
		else
		{
			$sql.="'".$_POST['vb_depto_compras']          ."',";
			$sql.="'vb_Depto_Compras',";
			$sql.="'".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del segundo
		if(empty($_POST['vb_jefe_compras'] ))
		{
			$sql.="'',";
			$sql.="'',";
			$sql.="'".date('Y-m-d')."',";
		}
		else
		{
			$sql.="'".$_POST['vb_jefe_compras']          ."',";
			$sql.="'vb_Jefe_de_Compras',";
			$sql.="'".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del tercero
		if(empty($_POST['vb_jefe_adm'] ))
		{
			$sql.="'',";
			$sql.="'',";
			$sql.="'".date('Y-m-d')."',";
		}
		else
		{
			$sql.="'".$_POST['vb_jefe_adm']          ."',";
			$sql.="'vb_Jefe_de_Administracion',";
			$sql.="'".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del cuarto
		if(empty($_POST['vb_jefe_parque_maquinaria'] ))
		{
			$sql.="'',";
			$sql.="'',";
			$sql.="'".date('Y-m-d')."',";
		}
		else
		{
			$sql.="'".$_POST['vb_jefe_adm']          ."',";
			$sql.="'vb_Jefe_Parque_Maquinaria',";
			$sql.="'".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del quinto
		if(empty($_POST['vb_jefe_grupo_obras'] ))
		{
			$sql.="'',";
			$sql.="'',";
			$sql.="'".date('Y-m-d')."',";
		}
		else
		{
			$sql.="'".$_POST['vb_jefe_grupo_obras']          ."',";
			$sql.="'vb_Jefe_Grupo_Obras',";
			$sql.="'".date('Y-m-d')."',";	
		}
		
		//Valida el Visto Bueno del Sexto
		if(empty($_POST['vb_gerente_general'] ))
		{
			$sql.="'',";
			$sql.="'',";
			$sql.="'".date('Y-m-d')."',";
		}
		else
		{
			$sql.="'".$_POST['vb_gerente_general']          ."',";
			$sql.="'vb_Gerente_General',";
			$sql.="'".date('Y-m-d')."',";	
		}
		
		
		$sql.="'".$_POST['observaciones']          ."',";		
		$sql.="'".$_POST['observaciones1']          ."',";		
		$sql.="'".$_POST['observaciones2']          ."',";		
		$sql.="'".$_POST['estado_oc']          ."',";	
		$sql.="'".$_POST['proveedor']          ."',";	
		$sql.="'".$_SESSION['user']          ."',";	
		$sql.="'". date('Y-m-d')           ."',";
		$sql.="'".$_POST['factura']          ."'";	
		$sql.=" ) ";
		
		$mensaje=" Inserción Correcta";
		mysql_query($sql);
		
		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'cabecera_oc', '".$_GET['id_oc']."', '2'";
        $sql_even.= ", 'INSERT:todos los campos', '".$_SERVER['REMOTE_ADDR']."', 'insercion cabecera de orden compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);

		// Update a estado de la solicitud de Compra asocisada ///////////////////////////	
		$up_sol_OC = "UPDATE solicitudes_compras SET estado=5 ";						//
		$up_sol_OC.= "WHERE  id_solicitud_compra='".$_POST["id_solicitud_compra"]."'";	//
		mysql_query($up_sol_OC);														//
		//////////////////////////////////////////////////////////////////////////////////

		$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$_POST["id_solicitud_compra"]."', '3'";
        $sql_even.= ", 'INSERT:estado', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion estado de solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);

		
		//Guardo el detalle de la OC
		$g=1;
		while($g<=$_POST['cantidad'])
		{
			if(empty($_POST['visible'.$g]))
			{
				$sql_detalle =" INSERT INTO detalle_oc(id_oc,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total,usuario_ingreso,fecha_ingreso) ";
				$sql_detalle.=" VALUES (";
				$sql_detalle.="'".$row_num[0]."',";
				$sql_detalle.="'".$_POST['id_solicitud_compra']."',";
				$sql_detalle.="'".$_SESSION['empresa'] ."',";
				$sql_detalle.="'".$_POST['cantidad'.$g]."',";
				$sql_detalle.="'".$_POST['unidad'.$g]."',";
				$sql_detalle.="'".$_POST['descripcion'.$g]."',";
				$sql_detalle.="'".$_POST['precio'.$g]."',";
				$sql_detalle.="'".$_POST['total'.$g]."',";
				$sql_detalle.="'".$_SESSION['user']."',";
				$sql_detalle.="'".date('Y-m-d')."'";
				$sql_detalle.=" )";
				mysql_query($sql_detalle);

				$consulta = "SELECT MAX(id_oc) as id_oc FROM detalle_oc WHERE rut_empresa='".$_SESSION["empresa"]."'";
	            $resultado=mysql_query($consulta);
	            $fila=mysql_fetch_array($resultado);
				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
		        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
		        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_oc', '".$_POST['id_det_oc'.$g]."', '2'";
		        $sql_even.= ", 'INSERT:rut_empresa,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total,usuario_ingreso,
		        fecha_ingreso,estado_detalle_oc,usuario_elimina,fecha_elimina', '".$_SERVER['REMOTE_ADDR']."', 'insercion detalle de orden compra', '1', '".date('Y-m-d H:i:s')."')";
		        mysql_query($sql_even, $con);
			}
			$g++;
		}
		$mostrar=1;
	}
	
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

<table style="width:1000px;" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2"> <a id='volver' href="?cat=2&sec=15"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Ordenes de Compra"></a></td></tr>
    
    <tr>
</table>
<?
if($mostrar==0)
{
	
?>
<form action="?cat=2&sec=16&action=<?=$action?>" method="POST" name='f1' id="f1" >
<input  type='hidden' name='primera' value='1'/>
<div style="width:100%; height:auto; ">
	<table style="width:100%;border-collapse:collapse;" cellpadding="3" cellspacing="4" border="0">
	     <tr id="cabecera_1" style="height: 50px;">
	          <td colspan="2" width="100px" style="text-align: left;"><img src="img/sacyr.png" border="0" width="130px"></td>
	          <td colspan="2" style=" font-weight:bold; font-size:24px;text-align:center;"><label>ORDEN DE COMPRA</label></td>
	          <td colspan="2" width="100px">
		      		<table width="100%" style="text-align:center;">
		                <tr>
		                	<td><?=$row_emp['direccion'];?></td>
		                </tr>
		                <tr>
		                	<td><?="Comuna ".$row_emp['comuna']."   ".$row_emp['ciudad'];?></td>
		                </tr>
		                <tr>
		                	<td><?=$row_emp['telefono_1'];?></td>
		                </tr>
		                <tr>
		                	<td><?=$row_emp['fax'];?></td>
		                </tr>
		                <tr>
		                	<td style=" font-weight:bold; font-size:20px;">Número: <?
			                    if(empty($_GET['id_oc']))
								echo $row_num[0];
								else
								echo "<input type='text' name='id_oc' value='".$_POST['id_oc']."' readonly style='width:30px; border:0;font-weight:bold; font-size:20px;background-color:transparent'   >";?>
		                    </td>
		            	</tr>
		            </table>
		    </tr>        
	</table>
</div>

<div>
<table style="margin-right: 0px;float:right;" border="1" width="100%" >
    	<tr>
    		<td style="text-align: right;font-size: 10px;border: 0px solid;" width="150px"><b>Solicitud Compra:</b></td>
            <td style="text-align: left;font-size: 10px;border: 0px solid;"><? echo $_POST['id_solicitud_compra']; ?></td>

        	<td style="text-align: right;font-size: 10px;border: 0px solid;" width="150px"><b>Solicitante:</b></td>
        	<td style="text-align: left;font-size: 10px;border: 0px solid;"><?=$_POST['solicitante'];?></td>
        </tr>
        <tr>
        	<td style="text-align: right;font-size: 10px;border: 0px solid;" ><b>Concepto:</b> </td>
        	<td style="text-align: left;font-size: 10px;border: 0px solid;"><?if($_POST['concepto']==1) echo "Mantenci&oacute;n";
        		if($_POST['concepto']==2) echo "Reparaci&oacute;n";
                if($_POST['concepto']==3) echo "Servicios";
                if($_POST['concepto']==4) echo "Activos";
                if($_POST['concepto']==5) echo "Repuestos";
                if($_POST['concepto']==6) echo "Fabricacion";
                if($_POST['concepto']==7) echo "Rectificación";?>
        	</td>
        	<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Centro Costo:</b></td>
        	<td style="text-align: left;font-size: 10px;border: 0px solid;">
<?
                    $cc ="SELECT * FROM centros_costos where rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                    $rc = mysql_query($cc,$con);
                    while($ce = mysql_fetch_assoc($rc)){
						 if($_POST['centro_costos']==$ce['Id_cc']) echo $ce["descripcion_cc"];
					}	 
?>
        	</td>
        </tr>
        <tr>
        	<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Fecha:</b></td>
        	<td style="text-align: left;font-size: 10px;border: 0px solid;">
<?
				if(empty($_GET['id_oc']))
					echo date('d-m-Y');
				else
					echo date('d-m-Y', strtotime($_POST['fecha_oc']));
?>
			</td>
			<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Moneda:</b></td>
			<td style="text-align: left;font-size: 10px;border: 0px solid;"><?=$_POST['moneda'];?></td>
        </tr>
        <tr>
        	<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Especialización:</b></td>
        	<td style="text-align: left;font-size: 10px;border: 0px solid;">
<?
                        $cc ="SELECT * FROM especialidades where 1=1  ORDER BY descripcion_esp";
                        $rc = mysql_query($cc,$con);
                         while($ce = mysql_fetch_assoc($rc)){
                             if($_POST['id_esp']==$ce['id_esp']) echo $ce['descripcion_esp'];
                        }
?>
			</td>
            <td width="145px;"id="proveedor_selec" style="text-align: right;font-size: 10px;border: 0px solid;"><b>Proveedor:</b></td>
            <td style="text-align: left;font-size: 10px;border: 0px solid;">
                    <? 
                    $sql = "SELECT p.* FROM proveedores p,especialidades e WHERE rut_empresa='".$_SESSION['empresa']."' ";
                    $sql.= " AND e.id_esp=p.id_esp and  e.id_esp = '".$_POST['id_esp']."' ";
					$res = mysql_query($sql,$con);
                    while($row = mysql_fetch_assoc($res)){
							if($row['rut_proveedor']==$_POST['proveedor']) echo $row['razon_social'];
					}
					?>
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
						echo "<table border='1' width='100%'>";
                        $html.='<tr>';
                        $html.='<td width="150px" style="text-align: right;font-size: 10px;border: 0px solid;"><b>Razón Social:</b></td>';
                        $html.="<td width='150px' style='text-align: left;font-size: 10px;border: 0px solid;' >".$row['razon_social']."</td>";
         
                        $html.='<td width="150px" style="text-align: right;font-size: 10px;border: 0px solid;"></td>';
                        $html.="<td width='150px' style='text-align: left;font-size: 10px;border: 0px solid;'></td>";
                        $html.='<td width="150px" style="text-align: right;font-size: 10px;border: 0px solid;"><b>Rut:</b></td>';
                        $html.="<td width='150px' style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['rut_proveedor']."</td>";
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td width="150px" style="text-align: right;font-size: 10px;border: 0px solid;"><b>Domicilio:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;' width='200'>".$row['domicilio']."</td>";
        
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'></td>";
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Comuna:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['comuna']."</td>";
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Ciudad:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['ciudad']."</td>";
                       
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Celular:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['celular']."</td>";
                   
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Telefono:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['telefono_1']."</td>";
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>ATT (SR/A):</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['contacto']."</td>";
                     
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Email:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['mail']."</td>";
                     
                        $html.='<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Fax:</b></td>';
                        $html.="<td style='text-align: left;font-size: 10px;border: 0px solid;'>".$row['fax']."</td>";
                        $html.='</tr>';
                        $html.='</table>';

                        echo $html;
               
               } ?>	
		</td>
        </tr>
</table>
</div>

<table style="width:90%; border-collapse:collapse;" id="detalle-prov" class="detalle-oc"  cellpadding="3" cellspacing="4" align="center">
	  <tr height="10px">
		  <td colspan="6" style="text-align:right;">
		  
		  </td>
	  </tr>
	  <tr>
		    <td style="background-color:rgb(0,0,255); color:black; font-family:Tahoma; font-size:10px; text-align:center;border: 1px solid;" ><b>Cantidad</b></td>
		    <td style="background-color:rgb(0,0,255); color:black; font-family:Tahoma; font-size:10px; text-align:center;border: 1px solid;" ><b>Unidad</b></td>
		    <td style="background-color:rgb(0,0,255); color:black; font-family:Tahoma; font-size:10px; text-align:center;border: 1px solid;" ><b>Descripci&oacute;n</b></td>
		    <td style="background-color:rgb(0,0,255); color:black; font-family:Tahoma; font-size:10px; text-align:center;border: 1px solid;" ><b>Precio</b></td>
		    <td style="background-color:rgb(0,0,255); color:black; font-family:Tahoma; font-size:10px; text-align:center;border: 1px solid;" ><b>Total</b></td>

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
				while(($_POST['cantidad']>=$i))
				{
						
					echo "<tr>";
					if(empty($_POST['visible'.$i]))
					{
						echo "<td class='detalle' style='text-align: center;font-size: 10px;border: 1px solid;' style='width:10%;'>".$_POST['cantidad'.$i]."</td>";
						echo "<td class='detalle' style='text-align: center;font-size: 10px;border: 1px solid;' style='width:10%;' style='width:10%;'>".$_POST['unidad'.$i]."</td>";
						echo "<td class='detalle' style='text-align: left;font-size: 10px;border: 1px solid;' style='width:10%;' style='text-align:left;'>".$_POST['descripcion'.$i]."</td>";
						echo "<td class='detalle' style='text-align: right;font-size: 10px;border: 1px solid;' style='width:10%;' style='text-align:right;width:12%;'>".$_POST['precio'.$i]."</td>";
						echo "<td class='detalle' style='text-align: right;font-size: 10px;border: 1px solid;' style='width:10%;' style='text-align:right;width:12%;'>".$_POST['total'.$i]."</td>";
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
<table width="175px" border="0" align="right" style="border-collapse:collapse; margin-right:35px;">
	<tr>
		<td ></td>
		<td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0); font-weight:bold; font-size: 10px;"  width="100px">SubTotal:</td>
		<td  style="text-align:center;border:1px solid rgb(0,0,0);font-size: 10px;" width="100px;"><? echo $_POST['subtotal'];?></td>
	</tr>
	<tr>
		<td></td>
		<td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;font-size: 10px;" width="">Descuento:</td>
		<td  style="text-align:center;border:1px solid rgb(0,0,0);font-size: 10px;" ><? echo $_POST['descuento'];?></td>
	</tr>
	<tr>
		<td></td>
		<td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;font-size: 10px;" width="">Neto:</td>
		<td  style="text-align:center;border:1px solid rgb(0,0,0);font-size: 10px;"><? echo $_POST['valor_neto'];?></td>
	</tr>
	<tr>
	<td></td>
		<td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;font-size: 10px;" width="">IVA: <?if(empty($_POST["valor_iva"])){ echo "0%";}else{ echo $_POST["valor_iva"]."%";}?></td>
		<td  style="text-align:center;border:1px solid rgb(0,0,0);font-size: 10px;"><? echo $_POST['iva'];?></td>
	</tr>
	<tr>
	<td></td>
		<td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;font-size: 10px;" width="">Total:</td>
		<td  style="text-align:center;border:1px solid rgb(0,0,0);font-size: 10px;"><? echo $_POST['total_doc'];?></td>
	</tr>
</table>

<table width="100%" style="margin-top:25px;" border="0">
	<tr>
     	<td style="text-align: right;font-size: 10px;border: 0px solid;"  width="25%"><b>Sometido al Plan de Calidad</b></td>
     	<td style="text-align: left;font-size: 10px;border: 0px solid;"  width="25%">
     		<input disabled="disabled" type="checkbox" name="sometido_plan_calidad"   <? if($_POST['sometido_plan_calidad']==1){echo "checked"; }?> value='1'>
     	</td>
 		<td style="text-align: right;font-size: 10px;border: 0px solid;" width="15%"><b>Forma Pago:</b></td>
 		<td style="text-align: left;font-size: 10px;border: 0px solid;" width="35%">
 			<?
		        if($_POST['forma_de_pago']==0) echo "Seleccione Forma de Pago";
		        if($_POST['forma_de_pago']==2) echo "Al Contado";
		        if($_POST['forma_de_pago']==3) echo "30 Dias";
		        if($_POST['forma_de_pago']==4) echo "45 Dias";
		        if($_POST['forma_de_pago']==5) echo "50% Inicio 50% contra entrega";
		     ?>
		</td>
 	</tr>
 	<tr>
     	<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Adjunta Especificación Técnica</b></td>
     	<td style="text-align: left;font-size: 10px;border: 0px solid;">
     		<input  disabled="disabled" type="checkbox" name="adj_esp_tecnica" <? if($_POST['adj_esp_tecnica']==1){echo "checked"; }?>  value='1'>
     	</td>
		<td style="text-align: right;font-size: 10px;border: 0px solid;" ><b>Fecha Entrega: </b></td>
		<td style="text-align: left;font-size: 10px;border: 0px solid;"><? echo date('d-m-Y', strtotime($_POST['fecha_entrega']));?></td>
 	</tr>
  	<tr>
     	<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Adjunta Propuesta Económica</b></td>
     	<td style="text-align: left;font-size: 10px;border: 0px solid;">
     		<input  disabled="disabled" type="checkbox" name="adj_propuesta_economica" <? if($_POST['adj_propuesta_economica']==1){echo "checked";}?> value='1'>
     	</td>
		<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Estado Orden de Compra</b></td>
	    <td style="text-align: left;font-size: 10px;border: 0px solid;">
	    	<?
	            if($_POST['estado_oc']==1) echo "Abierta";
	            if($_POST['estado_oc']==2) echo "Pendiente";
	            if($_POST['estado_oc']==3) echo "Cerrada";
	            if($_POST['estado_oc']==4) echo "Aprobada";
	            if($_POST['estado_oc']==5) echo "Rechazada";
	        ?>
	    </td>
	</tr>
	<tr height="30px;"  style="text-align:right">
		<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Observaciones:</b></td>
		<td style="text-align: left;font-size: 10px;border: 0px solid;"><? echo $_POST['observaciones'];?></td>
		<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Observaciones 1:</b></td>
		<td style="text-align: left;font-size: 10px;border: 0px solid;"><? echo $_POST['observaciones1'];?></td>
	
	</tr>
	<tr height="10px;"  style="text-align:right">
		<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Observaciones 2:</b></td>
		<td style="text-align: left;font-size: 10px;border: 0px solid;"><? echo $_POST['observaciones2'];?></td>
		<td style="text-align: right;font-size: 10px;border: 0px solid;"><b>Factura:</b></td>
		<td style="text-align: left;font-size: 10px;border: 0px solid;"><? echo $_POST['factura'];?></td>
	</tr>
	<tr height="10px;">
	</tr>
	<tr>
	    <td colspan="100%" style="text-align: center;font-size: 11px;border: 1px solid;">
	        <p>De conformidad a lo prescrito en la Ley Nº 19.983 del Año 2007, que REGULA LA TRANSFERENCIA Y OTORGA MERITO EJECUTIVO A COPIA DE LA</p>
	        <p>FACTURA, las partes acuerdan que el plazo indicado en el Número 3 del Artículo 2º de dicha Ley, para reclamar el contenido de las facturas presentadas</p>
	        <p>por el Proveedor, Subcontratista, Contratista o como se le tenga denomidado en el Contrato o en la <b>Órden de Compra, será de 30 DÍAS.</b></p>
	    </td>
	</tr>
	 <tr>
			<td colspan="100%" width="100%" style="font-size: 10px;padding-top:12px; padding-bottom: 12px;border: 1px solid; text-align:center;">
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
            <td style="height: 150px; width: 15%;border: 1px solid; text-align:center;font-size: 11px;">
            	<b>VºBº Depto Compras</b><br/>
                <input type="checkbox" name="vb_depto_compras" <? if($_POST['vb_depto_compras']==1){ echo "checked";} if($c1!=1 && $c2!=1) echo " disabled ";?> value="1" ><br>
               	
               	<? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_depto_compras"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				if(($c1==1 || $c2==1) || $row_vb["vb_depto_compras"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:14px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}		
               	}
               	if($row_vb["vb_depto_compras"]==1){
				?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Nombre: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$row_usu1["nombre"];?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_depto_compras"];?></label>
               	<?}?>

            </td>
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center;font-size: 11px;">
                <b>VºBº Jefe Compras</b><br/>
                <input type="checkbox" name="vb_jefe_compras" <? if($_POST['vb_jefe_compras']==1){ echo "checked";}  if($c1!=2 && $c2!=2) echo " disabled ";?> value="1" ><br>
            	
                <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_jefe_compras"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				if(($c1==2 || $c2==2) || $row_vb["vb_jefe_compras"]==1){

					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:14px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}
               	}
               	if($row_vb["vb_jefe_compras"]==1){
				?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Nombre: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$row_usu1["nombre"];?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_jefe_compras"];?></label>
               	<?}?>

            </td>
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center;font-size: 11px;font-size: 11px;">
                <b>VºBº Jefe Administracion</b><br/>
                <input type="checkbox" name="vb_jefe_adm" <? if($_POST['vb_jefe_adm']==1){ echo "checked";} if($c1!=3 && $c2!=3) echo " disabled ";?> value="1"><br>
            	
            	 <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_jefe_adm"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				if(($c1==3 || $c2==3) || $row_vb["vb_jefe_adm"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:14px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}
               	}
               	if($row_vb["nombre_vb_jefe_adm"]==1){
				?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Nombre: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$row_usu1["nombre"];?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_jefe_adm"];?></label>
               	<?}?>

            </td>    
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center;font-size: 11px;">
                <b>VºBº Jefe Parque Maquinarias </b><br/>
                <input type="checkbox" name="vb_jefe_parque_maquinaria" <? if($_POST['vb_jefe_parque_maquinaria']==1){ echo "checked";} if($c1!=4 && $c2!=4) echo " disabled ";?> value="1"><br>
            
                 <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_jefe_pm"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				if(($c1==4 || $c2==4) || $row_vb["vb_jefe_parque_maquinaria"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:14px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}
               	}
               	if($row_vb["vb_jefe_parque_maquinaria"]==1){
				?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Nombre: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$row_usu1["nombre"];?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_jefe_pm"];?></label>
               	<?}?>

            </td>
            <td style="height: 100px; width:15%;border: 1px solid; text-align:center;font-size: 11px;">
                <b>VºBº Jefe Grupo de Obras</b><br/>
                <input type="checkbox" name="vb_jefe_grupo_obras" <? if($_POST['vb_jefe_grupo_obras']==1){ echo "checked";} if($c1!=5 && $c2!=5) echo " disabled ";?> value="1"><br>
            
                 <? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_grupo_obras"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				if(($c1==5 || $c2==5) || $row_vb["vb_jefe_grupo_obras"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:14px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}
               	}
               	if($row_vb["vb_jefe_grupo_obras"]==1){
				?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Nombre: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$row_usu1["nombre"];?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_grupo_obras"];?></label>
               	<?}?>

            </td>
<? 
if($_POST['total_doc']>=20000000){
?>
            <td style="height: 100px; width: 15%;border: 1px solid; text-align:center;font-size: 11px;">
                <b>VB Gerente General </b><br/>
                <input type="checkbox" name="vb_gerente_general" <? if($_POST['vb_gerente_general']==1){ echo "checked";} if($c1!=6 && $c2!=6) echo " disabled ";?> value="1"><br>
            	
            	<? 
               	$sql_usu1 = "SELECT nombre, nombre_arch_fd FROM usuarios WHERE usuario='".$row_vb["nombre_vb_gerente_general"]."' AND rut_empresa='".$_SESSION['empresa']."' ";
               	$res_usu1 = mysql_query($sql_usu1);
				$row_usu1 = mysql_fetch_assoc($res_usu1);
				if(($c1==6 || $c2==6) || $row_vb["vb_gerente_general"]==1){
					if($row_usu1["nombre_arch_fd"]!=NULL){
					?>
	                	<img src='firmas/<?=$row_usu1['nombre_arch_fd']?>' width='100px' height='60px'><br>
	               	<?
	               	}else{
	               	?>
	               		<label style="color:black; font-family:Tahoma, Geneva, sans-serif; font-size:14px;"><b>AUTORIZADO</b></label><br>
	               	<?	
	               	}
               	}
               	if($row_vb["vb_gerente_general"]==1){
				?>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Nombre: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$row_usu1["nombre"];?></label><br>
	                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$row_vb["fecha_vb_gerente_general"];?></label>
               	<?}?>

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
      <table align="center"><tr><td>
      
       <input type="button" id="boton_im" onclick="javascript:imprSelec('f1')" value="Imprimir" /></td></tr></table>
</form>    
<?
 }
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
//echo " document.f1.submit()
//";

echo "}
 ";
echo "</script>
 ";
 $k++;
 }
 

?>






