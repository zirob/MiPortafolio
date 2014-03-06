	  <script>
  function calcular()
  {
	  c=0;
	  a=document.f1.cantidad.value;
	  b=document.f1.precio_pmp.value;
	  c=a*b;
	  document.f1.total.value=c;
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
		width:80%;
		text-align:center;
	}
	
	</style>

<?php
// var_dump($_POST);	
$error=0;
	
//Validaciones
if(!empty($_POST['accion']))
{

 	$query1 = "SELECT cantidad FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
	$query1.= "AND rut_empresa='".$_SESSION["empresa"]."'";
	$res_query1 = mysql_query($query1);
	$row_query1 = mysql_fetch_array($res_query1);
	if($_POST['cantidad'] > $row_query1["cantidad"]){
		$error=1;
		$mensaje=" La cantidad ingresada supera a las existencias. ";
	}

	if(empty($_POST['fecha_salida'])){
		$error=1;
		$mensaje=" Debe Ingresar la Fecha de Salida ";
	}
	if(empty($_POST['cantidad']) ){
			$error=1;
			$mensaje=" Debe Ingresar la cantidad ";
	}
	if(empty($_POST['tipo_salida1'])){
			$error=1;
			$mensaje=" Debe Seleccionar el Destino del prodcuto: 'Bodega' ó 'Centro Costo' ";
	}else{
		if($_POST["tipo_salida1"]==1){
			if($_POST['cod_bodega']==0){
				$error=1;
				$mensaje=" Debe Seleccionar una Bodega ";	
			}
		}else{
			if($_POST['id_cc']==0){
				$error=1;
				$mensaje=" Debe Seleccionar un Centro de Costo ";	
			}
		}
	}

	if(empty($_POST['tipo_salida2'])){
			$error=1;
			$mensaje=" Debe Seleccionar el Tipo de Producto: 'Activo', 'Lugar Fisico' u 'Orden' ";
	}else{
		if($_POST["tipo_salida2"]==1){
			if($_POST['cod_producto']==0){
				$error=1;
				$mensaje=" Debe Seleccionar un Activo ";	
			}else{
				if($_POST['cod_detalle_producto']==0){
					$error=1;
					$mensaje=" Debe Seleccionar una Patente ";
				}
			}
		}
		if($_POST["tipo_salida2"]==2){
			if($_POST['id_lf']==0){
				$error=1;
				$mensaje=" Debe Seleccionar un Lugar Fisico ";	
				
			}
		}
		if($_POST["tipo_salida2"]==3){
			if($_POST['id_ot']==0){
				$error=1;
				$mensaje=" Debe Seleccionar un Orden de Trabajo ";	
				
			}
		}
	}

	if($error==0)
	{
		
			if(!empty($_GET['id_salida']) )
			{
				
				$sql= " UPDATE productos_new_salidas SET  ";
				// $sql.=" codbar_productonew='".$_POST['codbar_productonew']."' ,";	
				$sql.=" rut_empresa='".$_SESSION['empresa']."' ,";
				$sql.=" fecha_salida='".$_POST['fecha_salida']."' ,";
				$sql.=" cantidad	='".$_POST['cantidad']."' ,";
				$sql.=" precio_pmp='".$_POST['precio_pmp']."' ,";	
				$sql.=" total='".$_POST['total']."' ,";	
				$sql.=" persona_solicita='".$_SESSION['user']."' ,";	
				$sql.=" cod_bodega='".$_POST['cod_bodega']."', ";	
				$sql.=" id_cc='".$_POST['id_cc']."', ";	
				$sql.=" id_lf='".$_POST['id_lf']."', ";	
				$sql.=" id_ot='".$_POST['id_ot']."' ,";	
				$sql.=" cod_detalle_producto='".$_POST['cod_detalle_producto']."', ";	
				$sql.=" cod_producto='".$_POST['cod_producto']."', ";	
				$sql.=" observaciones='".$_POST['observaciones']."', ";	
				$sql.=" observaciones1='".$_POST['observaciones1']."', ";	
				$sql.=" observaciones2='".$_POST['observaciones2']."' ";	
				$sql.=" WHERE id_salida=".$_GET['id_salida']." AND rut_empresa='".$_SESSION['empresa']."'";
				$consulta = mysql_query($sql);
				if($consulta)
					$mensaje = " Actualización Correcta ";
					$mostrar = 1;

				// OBTENER CANTIDAD Y TOTAL DE TABLA PRODUCTOS_NEW
	            $sel_prd2 = "SELECT cantidad, total FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
				$sel_prd2.= "AND rut_empresa='".$_SESSION["empresa"]."'";
				$res_prd2 = mysql_query($sel_prd2);
				$row_prd2 = mysql_fetch_array($res_prd2);

				// RESTAR ENTRE LA SALIDA Y LO GUARDADO EN TABLA PRODUCTOS_NEW
				$cantidad_Edit = $_POST["cantidad_anterior"] - $_POST["cantidad"];
				$cantidad = $row_prd2["cantidad"] + $cantidad_Edit;

				$total_Edit = $_POST["total_anterior"] - $_POST["total"];
				$total = $row_prd2["total"] + $total_Edit;

				//Actualiza: Cantidad y Total de la tabla Productos_New.  
				$up_prdIn = "UPDATE productos_new SET cantidad='".$cantidad."', total='".$total."' ";
				$up_prdIn.= "WHERE codbar_productonew='".$_GET["codbar_productonew"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
				$consulta2 = mysql_query($up_prdIn);

				if($consulta && $consulta2)
				$mensaje = " Actualización Correcta ";
				$mostrar = 1;

				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new_salidas', '".$_GET['id_salida']."', '3'";
	            $sql_even.= ", 'UPDATE:codbar_productonew=".$_POST['codbar_productonew']."' ,";	
				$sql_even.=" rut_empresa=".$_SESSION['empresa']." ,";
				$sql_even.=" fecha_salida=".$_POST['fecha_salida']." ,";
				$sql_even.=" cantidad	=".$_POST['cantidad']." ,";
				$sql_even.=" precio_pmp=".$_POST['precio_pmp']." ,";	
				$sql_even.=" total=".$_POST['total']." ,";	
				$sql_even.=" persona_solicita=".$_SESSION['user']." ,";	
				$sql_even.=" cod_bodega=".$_POST['cod_bodega'].", ";	
				$sql_even.=" id_cc=".$_POST['id_cc'].", ";	
				$sql_even.=" id_lf=".$_POST['id_lf'].", ";	
				$sql_even.=" id_ot=".$_POST['id_ot']." ,";	
				$sql_even.=" cod_detalle_producto=".$_POST['cod_detalle_producto'].", ";	
				$sql_even.=" cod_producto=".$_POST['cod_producto'].", ";	
				$sql_even.=" observaciones=".$_POST['observaciones']."', '".$_SERVER['REMOTE_ADDR']."', 'Update de entrada productos', '1', '".date('Y-m-d H:i:s')."')";
	            mysql_query($sql_even, $con); 
				
			}
			else
			{

				$fecha=date("Y-m-d H:i:s");
				$in_prd=" INSERT INTO productos_new_salidas (codbar_productonew,rut_empresa,fecha_salida,cantidad
				,precio_pmp,total,persona_solicita,cod_bodega,id_cc,id_lf,id_ot,cod_detalle_producto,cod_producto,usuario_ingreso,fecha_ingreso,estado_salida,observaciones, observaciones1, observaciones2) VALUES";
				$in_prd.="  ( ";
				$in_prd.=" '".$_GET['codbar_productonew']."', ";
				$in_prd.=" '".$_SESSION['empresa']."', ";
				$in_prd.=" '".$_POST['fecha_salida']."', ";
				$in_prd.=" '".$_POST['cantidad']."', ";
				$in_prd.=" '".$_POST['precio_pmp']."', ";
				$in_prd.=" '".$_POST['total']."', ";
				$in_prd.=" '".$_SESSION['user']."', ";
				$in_prd.=" '".$_POST['cod_bodega']."', ";
				$in_prd.=" '".$_POST['id_cc']."', ";
				$in_prd.=" '".$_POST['id_lf']."', ";
				$in_prd.=" '".$_POST['id_ot']."', ";
				$in_prd.=" '".$_POST['cod_detalle_producto']."', ";
				$in_prd.=" '".$_POST['cod_producto']."', ";
				$in_prd.=" '".$_SESSION['user']."', ";
				$in_prd.=" '".$fecha."', ";
				$in_prd.=" '1', ";
				$in_prd.=" '".$_POST['observaciones']."', ";
				$in_prd.=" '".$_POST['observaciones1']."', ";
				$in_prd.=" '".$_POST['observaciones2']."' ) ";
				$consulta = mysql_query($in_prd); 
				

				$consulta = "SELECT MAX(id_salida) as id_salida FROM productos_new_salidas WHERE rut_empresa='".$_SESSION["empresa"]."'";
	            $resultado=mysql_query($consulta);
	            $fila=mysql_fetch_array($resultado);
				$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new_salidas', '".$fila['id_salida']."', '2'";
	            $sql_even.= ", 'INSERT:codbar_productonew=".$_POST['codbar_productonew'].",rut_empresa=".$_SESSION['empresa'].",fecha_salida=".$_POST['fecha_salida'].",cantidad=".$_POST['cantidad']."";
	            $sql_even.= ",precio_pmp=".$_POST['precio_pmp'].",total=".$_POST['total'].",persona_solicita=".$_SESSION['user'].",cod_bodega=".$_POST['cod_bodega'].",id_cc=".$_POST['id_cc']."";
	            $sql_even.= ",id_lf=".$_POST['id_lf'].",id_ot=".$_POST['id_ot'].",cod_detalle_producto=".$_POST['cod_detalle_producto'].",cod_producto=".$_POST['cod_producto'].",usuario_ingreso=".$_SESSION['user']."";
	            $sql_even.= ",fecha_ingreso=".$fecha.",estado_salida=1,observaciones=".$_POST['observaciones']."', '".$_SERVER['REMOTE_ADDR']."', 'Insert de entrada productos', '1', '".date('Y-m-d H:i:s')."')";
	            mysql_query($sql_even, $con); 
	            // OBTENER CANTIDAD Y TOTAL DE TABLA PRODUCTOS_NEW
	            $sel_prd2 = "SELECT cantidad, total FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
				$sel_prd2.= "AND rut_empresa='".$_SESSION["empresa"]."'";
				$res_prd2 = mysql_query($sel_prd2);
				$row_prd2 = mysql_fetch_array($res_prd2);
				// RESTAR ENTRE LA SALIDA Y LO GUARDADO EN TABLA PRODUCTOS_NEW
				$cantidad = $row_prd2["cantidad"] - $_POST["cantidad"];
				$total = $row_prd2["total"] - $_POST["total"];

				//Actualiza: Cantidad y Total de la tabla Productos_New.  
				$up_prdIn = "UPDATE productos_new SET cantidad='".$cantidad."', total='".$total."' ";
				$up_prdIn.= "WHERE codbar_productonew='".$_GET["codbar_productonew"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
				$consulta2 = mysql_query($up_prdIn);

				if($consulta && $consulta2)
				$mensaje = " Inserción Correcta ";
				$mostrar = 1;
			}
	}
	
}

// OBTENNER EL PRECIO PMP
if(!empty($_REQUEST['codbar_productonew'])){

	$sel_prd = "SELECT precio_pmp FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
	$sel_prd.= "AND rut_empresa='".$_SESSION["empresa"]."'";
	$res_prd = mysql_query($sel_prd);
	$row_prd = mysql_fetch_array($res_prd);
	$_POST['precio_pmp'] = $row_prd['precio_pmp'];
}

//Rescato los Datos
if(!empty($_GET['id_salida']) and (empty($_POST['primera']))){

	$sql="SELECT  * FROM  productos_new_salidas WHERE id_salida='".$_GET['id_salida']."' and  rut_empresa='".$_SESSION['empresa']."'";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$_POST=$row;
	$_POST['fecha_salida']=substr($_POST['fecha_salida'],0,10);	
	
	if(!empty($_POST['cod_bodega']))
	$_POST['tipo_salida1']=1;
	
	if(!empty($_POST['id_cc']))
	$_POST['tipo_salida1']=2;
	
	if(!empty($_POST['cod_producto']))
	$_POST['tipo_salida2']=1;
	
	if(!empty($_POST['id_lf']))
	$_POST['tipo_salida2']=2;
	
	if(!empty($_POST['id_ot']))
	$_POST['tipo_salida2']=3;
} 


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

<form name='f1' id='f1' action="?cat=3&sec=36&id_salida=<? echo $_GET['id_salida']; ?>&codbar_productonew=<?=$_GET["codbar_productonew"];?>" method="POST">
<input type="hidden" name="primera" value="1"/>

<table style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4" border="0">

	<tr>
		<td align="right" colspan="100%">
			<a href='?cat=3&sec=34&codbar_productonew=<?=$_GET["codbar_productonew"];?>'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Salidas de Productos'></a>
		</td>
	</tr>

<?
if($mostrar==0){
?>

	<tr height="10px">
	</tr>
	 <!-- <tr>
	        <td><label>Producto</label><label style="color:red">(*)</label><br />
	        <select name="codbar_productonew"  class="foo" onchange="submit()" 
	        >
	                <option value=""  class="foo">---</option>
	            <?/*
	                $s = "SELECT * FROM productos_new WHERE 1=1 ";
	                $r = mysql_query($s,$con);
	                
	                while($roo = mysql_fetch_assoc($r)){
	                    ?>  <option value="<?=$roo['codbar_productonew'];?>"   <? if($_POST['codbar_productonew']==$roo['codbar_productonew']) echo " selected" ;?> class="foo"><?=$roo['descripcion'];?></option> <?    
	                }
	        
	                */?>
	            </select>
	        </td>
	 </tr> -->
	 <tr>
	        <td><label>Fecha Salida:</label><label style='color:red'>(*)</label><br/>
	        	<input size="10" class="foo" type="date" name="fecha_salida" value="<?=$_POST['fecha_salida'];?>">
	        </td>
	</tr>
	<tr>
	        <td><label>Cantidad:</label><label style='color:red'>(*)</label><br/>
	        	<input size="10" onchange="calcular()" class="foo" type="text" name="cantidad" value="<?=$_POST['cantidad'];?>">
	        	<input type='hidden' name='cantidad_anterior' value='<?=$_POST['cantidad'];?>'>
	        </td> 
	        <td><label>Valor Unitario:</label><br/>
	        	<input onchange="calcular()" readonly size="10" class="foo" type="text" name="precio_pmp" value="<?=$_POST['precio_pmp'];?>">
	        </td>
	        <td><label>Total:</label><br/>
	        	<input size="10" readonly="readonly" class="foo" type="text" name="total" value="<?=$_POST['total'];?>">
	        	<input type='hidden' name='total_anterior' value='<?=$_POST['total'];?>'>
	        </td>
	</tr>
	<tr>
	    	<td>
	    		<label >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp ASIGNAR</label><br> 
		        <label>Bodega</label>          
		        <input  type="radio" name="tipo_salida1"   value="1" onchange="submit()" <? if($_POST['tipo_salida1']==1) echo " checked "; ?>/>  
	    	    <label>Centro Costo</label>
	    	    <input  type="radio" name="tipo_salida1"   value="2" onchange="submit()" <? if($_POST['tipo_salida1']==2) echo " checked "; ?>/> 
	        	<label style='color:red;text-align:right;'>&nbsp&nbsp&nbsp&nbsp(*)</label>
	        </td>
	     	<td>
	     	<?
			  if(!empty($_POST['tipo_salida1']) AND ($_POST['tipo_salida1']==1)){

				 $sql = " SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION['empresa']."'";
				 $rec = mysql_query($sql);
				 
				 echo "<select name='cod_bodega'  class='foo' >";
				 echo "<option value='0'>-- Seleccione Bodega --</option>";
				 while($row=mysql_fetch_array($rec))
				 {
					 echo "<option value='".$row['cod_bodega']."' ";
					 if($_POST['cod_bodega']==$row['cod_bodega'])
					 {
					 	echo " selected ";
					 }
					 echo " class='foo' >";
					 echo $row['descripcion_bodega']."</option>";

				 }
				 echo "</select>"; 
				 echo "<label style='color:red;text-align:right;'>&nbsp(*)</label>";
			  }
		  
			  if(!empty($_POST['tipo_salida1'])and ($_POST['tipo_salida1']==2)){

				 $sql=" SELECT * FROM centros_costos WHERE rut_empresa='".$_SESSION['empresa']."'";
				 $rec=mysql_query($sql);
				 echo "<select name='id_cc'  class='foo' onchange='submit()'>";
				 echo "<option value='0'>-- Seleccione Centro Costo --</option>";
				 while($row=mysql_fetch_array($rec))
				 {
					 echo "<option value='".$row['Id_cc']."' ";
					 if($_POST['id_cc']==$row['Id_cc'])
					 {
					 	echo " selected ";
					 }
					 echo " class='foo' >";
					 echo $row['descripcion_cc']."</option>";

				 }
				 echo "</select>"; 
				 echo "<label style='color:red;text-align:right;'>&nbsp(*)</label>";
			  }
	        ?>
	     	</td>
	</tr>
	<tr>
	    	<td>
	    		<label >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp DESTINAR</label><br> 
		        <label>Activo</label>          
		        <input  type="radio" name="tipo_salida2"   value="1" onchange="submit()" <? if($_POST['tipo_salida2']==1) echo " checked "; ?>/>  
		        <label>Lugar Fisico</label>    
		        <input  type="radio" name="tipo_salida2"   value="2" onchange="submit()" <? if($_POST['tipo_salida2']==2) echo " checked "; ?>/> 
		        <label>O.T.</label>          
		        <input  type="radio" name="tipo_salida2"   value="3" onchange="submit()" <? if($_POST['tipo_salida2']==3) echo " checked "; ?>/> 
	        	<label style='color:red;text-align:right;'>&nbsp&nbsp(*)</label>
	        </td>
	     	<td>
	     	<?
			  if(!empty($_POST['tipo_salida2'])and ($_POST['tipo_salida2']==1)){

				 $sql=" SELECT * FROM productos WHERE rut_empresa='".$_SESSION['empresa']."'";
				 $rec=mysql_query($sql);
				 echo "<select name='cod_producto'  class='foo' onchange='submit()'>";
				 echo "<option value='0'>-- Seleccione Activo --</option>";
				 
				 while($row=mysql_fetch_array($rec)){

					 echo "<option value='".$row['cod_producto']."' ";
					 
					 if($_POST['cod_producto']==$row['cod_producto']){
					 	echo " selected ";
					 }
					 echo " class='foo' >";
					 echo $row['descripcion']."</option>";
				 }
				 echo "</select>"; 
	        	
				 echo "<label style='color:red;text-align:right;'>&nbsp(*)</label>";
			  }
			  
			  if(!empty($_POST['tipo_salida2'])and ($_POST['tipo_salida2']==2))
			  {
				 $sql=" SELECT * FROM lugares_fisicos WHERE rut_empresa='".$_SESSION['empresa']."'";
				 $rec=mysql_query($sql);
				 echo "<select name='id_lf'  class='foo' >";
				 echo "<option value='0'>-- Seleccione Lugar Fisico --</option>";
				 while($row=mysql_fetch_array($rec))
				 {
					 echo "<option value='".$row['id_lf']."' ";
					 if($_POST['id_lf']==$row['id_lf'])
					 {
					 	echo " selected ";
					 }
					 echo " class='foo' >";
					 echo $row['descripcion_lf']."</option>";

				 }
				 echo "</select>"; 
				echo "<label style='color:red;text-align:right;'>&nbsp(*)</label>";
			  }
			  
			  if(!empty($_POST['tipo_salida2'])and ($_POST['tipo_salida2']==3))
			  {
				 $sql=" SELECT * FROM cabeceras_ot WHERE rut_empresa='".$_SESSION['empresa']."'";
				 $rec=mysql_query($sql);
				 echo "<select name='id_ot'  class='foo' >";
				 echo "<option value='0'>-- Seleccione Orden Trabajo --</option>";
				 while($row=mysql_fetch_array($rec))
				 {
					 echo "<option value='".$row['id_ot']."' ";
					 if($_POST['id_ot']==$row['id_ot'])
					 {
					 	echo " selected ";
					 }
					 echo " class='foo' >";
					 echo $row['descripcion_ot']."</option>";
				 }
				 echo "</select>"; 
				 echo "<label style='color:red;text-align:right;'>&nbsp(*)</label>";

			  }
	        ?>
		     </td>
		     <td>
		     <?
		     	if(!empty($_POST['cod_producto'])&&($_POST['tipo_salida2'])==1){
					
						 $sql=" SELECT * FROM detalles_productos WHERE cod_producto='".$_POST['cod_producto']."' AND rut_empresa='".$_SESSION['empresa']."'";
						 $rec=mysql_query($sql);
						 echo "<select name='cod_detalle_producto'  class='foo' >";
						 echo "<option value='0'>-- Seleccione Patente --</option>";
						 while($row=mysql_fetch_array($rec)){

							 echo "<option value='".$row['cod_detalle_producto']."' ";
							 if($_POST['cod_detalle_producto']==$row['cod_detalle_producto']){
								echo " selected ";
							 }
							 echo " class='foo' >";
							 // echo $row['patente']."</option>";
							 if($row["patente"]!=''){
							 	echo $row["patente"];
							 }else{
							 	echo $row["codigo_interno"];
							 }
						 }
						 echo "</select>";
				 		echo "<label style='color:red;text-align:right;'>&nbsp(*)</label>";

				}
			?>
	     	</td>
	</tr>
	<tr>
	        <td colspan="100%"><label>Observaciones:</label><br/>
	        	<input size="20"  class="foo" style="text-align:left;"  type="text" name="observaciones" value="<?=$_POST['observaciones'];?>"style=' width:400px;'>
	        </td> 
	</tr>
	<tr>
	        <td colspan="100%"><label>Observaciones 1:</label><br/>
	        	<input size="20"  class="foo" style="text-align:left;"  type="text" name="observaciones1" value="<?=$_POST['observaciones1'];?>"style=' width:400px;'>
	        </td> 
	</tr>
	<tr>
	        <td colspan="100%"><label>Observaciones 2:</label><br/>
	        	<input size="20"  class="foo" style="text-align:left;"  type="text" name="observaciones2" value="<?=$_POST['observaciones2'];?>"style=' width:400px;'>
	        </td> 
	</tr>	
	<tr>
	       <td style="text-align: right;"  colspan="100%">
	       		<input name="accion" type="submit" value="Grabar"  style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;">
	       	</td>
	</tr>   	
	<tr>
			<td colspan="100%" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
					(*) Campos de Ingreso Obligatorio.
			</td>
	</tr>


<?
	}
?>
</table>
</form>