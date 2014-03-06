<script type="text/javascript">
	function ValidaSoloNumeros() {
	 if ((event.keyCode < 48) || (event.keyCode > 57)) 
	  event.returnValue = false;
	}
</script>

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
$mostrar=0;
//Validaciones
if(!empty($_POST['accion'])){

	if(empty($_POST['factura']) ){
			$error=1;
			$mensaje=" Debe Ingresar el Numero de Factura ";
	}
	if(empty($_POST['fecha_factura']) ){
			$error=1;
			$mensaje=" Debe Ingresar la Fecha de Factura ";
	}
	if(empty($_POST['cantidad']) ){
			$error=1;
			$mensaje=" Debe Ingresar la cantidad ";
	}
	if(empty($_POST['precio_pmp']) ){
			$error=1;
			$mensaje=" Debe Ingresar el el Precio Unitario ";
	}


	
	if($error==0){
		
		if($_POST['accion']=='editar'){

			// Obtengo de Cantidad_anterior, Precio_PMP_anterior (precio unitario) y el total_anterior 
			$sel_prd = "SELECT * FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
			$sel_prd.= "AND rut_empresa='".$_SESSION["empresa"]."'";
			$res_prd = mysql_query($sel_prd);
			$row_prd = mysql_fetch_array($res_prd);

			
			$sql= " UPDATE productos_new_entradas SET  ";
			$sql.=" factura='".$_POST['factura']."' ,";	
			$sql.=" cantidad='".$_POST["cantidad"]."' ,";
			$sql.=" precio_pmp='".$_POST["precio_pmp"]."' ,";
			$sql.=" total='".$_POST["total"]."' ,";
			$sql.=" fecha_factura='".$_POST['fecha_factura']."' ,";	
			$sql.=" cantidad_anterior='".$row_prd['cantidad']."' ,";	
			$sql.=" precio_pmp_anterior='".$row_prd['precio_pmp']."' ,";	
			$sql.=" total_anterior='".$row_prd['total']."' ";	
			$sql.=" WHERE id_entrada=".$_GET['id_entrada']." AND rut_empresa='".$_SESSION['empresa']."'";
			$consulta = mysql_query($sql);
			
			$cantidad = $_POST["cantidad"] + $row_prd['cantidad'];
			$precio_pmp = ((($_POST["cantidad"]*$_POST["precio_pmp"])+($row_prd['cantidad']*$row_prd['precio_pmp']))/($cantidad));
			// $precio_pmp = ROUND($precio_pmp,3);
			$total = $cantidad*$precio_pmp;
			
			//Actualiza: Cantidad, Precio_PMP y Total de la tabla Productos_New.  
			$up_prdIn = "UPDATE productos_new SET cantidad='".$cantidad."', precio_pmp='".$precio_pmp."', total='".$total."' ";
			$up_prdIn.= "WHERE codbar_productonew='".$_GET["codbar_productonew"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
			$consulta2 = mysql_query($up_prdIn);

			if($consulta && $consulta2)
				$mensaje = " Actualización Correcta ";
				$mostrar = 1;


			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new_entradas', '".$_GET['id_entrada']."', '3'";
	        $sql_even.= ", 'UPDATE:factura=".$_POST['factura'].",";	
			$sql_even.=" cantidad=".$cantidad.",";
			$sql_even.=" precio_pmp=".$precio.",";
			$sql_even.=" total	=".$total.",";
			$sql_even.=" fecha_factura=".$_POST['fecha_factura'].",";	
			$sql_even.=" cantidad_anterior=".$row['cantidad_anterior'].",";	
			$sql_even.=" precio_pmp_anterior=".$row['precio_pmp_anterior'].",";	
			$sql_even.=" total_anterior=".$row['total_anterior'].", '".$_SERVER['REMOTE_ADDR']."', 'Update de entrada productos', '1', '".date('Y-m-d H:i:s')."')";
	        mysql_query($sql_even, $con); 
		
		}else{
			$fecha = date('Y-m-d H:i:s');


			// Obtengo de Cantidad_anterior, Precio_PMP_anterior (precio unitario) y el total_anterior 
			$sel_prd = "SELECT * FROM  productos_new WHERE codbar_productonew='".$_GET["codbar_productonew"]."' ";
			$sel_prd.= "AND rut_empresa='".$_SESSION["empresa"]."'";
			$res_prd = mysql_query($sel_prd);
			$row_prd = mysql_fetch_array($res_prd);
			
			
			$in_prd = " INSERT INTO productos_new_entradas(";
			$in_prd.= "codbar_productonew, rut_empresa, factura, fecha_factura";
			$in_prd.= ", cantidad, precio_pmp, total, cantidad_anterior";
			$in_prd.= ", precio_pmp_anterior, total_anterior, usuario_ingreso, fecha_ingreso";
			$in_prd.= ")VALUES(";
			$in_prd.= " '".$_GET['codbar_productonew']."', '".$_SESSION['empresa']."', '".$_POST['factura']."', '".$_POST['fecha_factura']."'";
			$in_prd.= ", '".$_POST["cantidad"]."', '".$_POST["precio_pmp"]."', '".$_POST["total"]."', '".$row_prd['cantidad']."'";
			$in_prd.= ", '".$row_prd['precio_pmp']."', '".$row_prd['total']."', '".$_SESSION['user']."', '".$fecha."'";
			$in_prd.= " ) ";
			$consulta = mysql_query($in_prd);
			
			$cantidad = $_POST["cantidad"] + $row_prd['cantidad'];
			$precio_pmp = ROUND(((($_POST["cantidad"]*$_POST["precio_pmp"])+($row_prd['cantidad']*$row_prd['precio_pmp']))/($cantidad)),2);
			// $precio_pmp = ROUND($precio_pmp,2);
			$total = ROUND(($cantidad*$precio_pmp),2);
			
			//Actualiza: Cantidad, Precio_PMP y Total de la tabla Productos_New.  
			$up_prdIn = "UPDATE productos_new SET cantidad='".$cantidad."', precio_pmp='".$precio_pmp."', total='".$total."' ";
			$up_prdIn.= "WHERE codbar_productonew='".$_GET["codbar_productonew"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
			$consulta2 = mysql_query($up_prdIn);

			if($consulta && $consulta2)
				$mensaje = " Inserción Correcta ";
				$mostrar = 1;

			$consulta = "SELECT MAX(id_entrada) as id_entrada FROM productos_new_entradas WHERE rut_empresa='".$_SESSION["empresa"]."'";
	        $resultado=mysql_query($consulta);
	        $fila=mysql_fetch_array($resultado);
			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
	        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
	        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new_entradas', '".$fila['id_entrada']."', '2'";
	        $sql_even.= ", 'INSERT:codbar_productonew=".$_POST['codbar_productonew'].",rut_empresa=".$_SESSION['empresa'].",factura=".$_POST['factura'].",fecha_factura=".$_POST['fecha_factura'].",cantidad=".$cantidad."
			,precio_pmp=".$precio.",total=".$total.",cantidad_anterior=".$row1['cantidad'].",precio_pmp_anterior=".$row1['precio_pmp'].",total_anterior=".$row1['total'].",usuario_ingreso=".$_SESSION['user'].",fecha_ingreso=".$fecha."', '".$_SERVER['REMOTE_ADDR']."', 'Insert de entrada productos', '1', '".date('Y-m-d H:i:s')."')";
	        mysql_query($sql_even, $con); 
		}
	}
	
}


//Rescato los Datos
if(!empty($_GET['id_entrada']) and (empty($_POST['primera']))){
	$sql="SELECT  * FROM  productos_new_entradas WHERE id_entrada='".$_GET['id_entrada']."' and  rut_empresa='".$_SESSION['empresa']."'";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$_POST=$row;
	$_POST['fecha_factura']=substr($_POST['fecha_factura'],0,10);	
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


<form name='f1' id='f1' action="?cat=3&sec=35&id_entrada=<? echo $_GET['id_entrada']; ?>&codbar_productonew=<?=$_GET["codbar_productonew"];?>" method="POST">
<input  type="hidden" name="primera" value="1"/>

<table style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4" border="0">
<tr>
	<td align="right" colspan="100%">
		<a href='?cat=3&sec=33&codbar_productonew=<?=$_GET["codbar_productonew"];?>'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Entradas Productos'></a>
	</td>
</tr>

<?
if($mostrar==0)
{
?>

<tr height="10px">
</tr>
<!-- <tr>
    <td><label>Producto</label><label style="color:red">(*)</label><br />
    <select name="codbar_productonew"  class="foo" onchange="submit()" 
    <?/*
			if(!empty($_GET['id_entrrada']))
			{
			 echo " disabled ";
			}*/
    ?>
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
    <td><label>Factura:</label><label style='color:red'>(*)</label><br/>
    	<input size="10" class="foo" type="text" name="factura" value="<?=$_POST['factura'];?>" onKeyPress='ValidaSoloNumeros()'>
    </td>
    <td><label>Fecha Factura:</label><label style='color:red'>(*)</label><br/>
    	<input size="10" class="foo" type="date" name="fecha_factura" value="<?=$_POST['fecha_factura'];?>">
    </td>
</tr>
<tr>
    <td><label>Cantidad:</label><label style='color:red'>(*)</label><br/>
    	<input size="10" onchange="calcular()" class="foo" type="text" name="cantidad" value="<?=$_POST['cantidad'];?>" onKeyPress='ValidaSoloNumeros()'>
    </td> 
   
    <td><label>Valor Unitario:</label><label style='color:red'>(*)</label><br/>
    	<input onchange="calcular()" size="10" class="foo" type="text" name="precio_pmp" value="<?=$_POST['precio_pmp'];?>" onKeyPress='ValidaSoloNumeros()'>
    </td>
    <td><label>Total:</label><br/>
    	<input size="10"   readonly="readonly"  class="foo" type="text" name="total" value="<?=$_POST['total'];?>">
    </td>
</tr>
<tr>
   	<td style="text-align: right;"  colspan="100%">
   		<!-- <input name="accion" type="submit" value="Grabar"  style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"> -->
					<div style="width:100%; height:auto; text-align:right;">
                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                        <?

                        if($_GET["action"]=='')
                        {
                            echo  " value='guardar' >Guardar</button><input name='bandera_guardar' type='hidden' value='1'>";
                        }
                        if($_GET["action"]==2)
                        {
                            echo " value='editar' >Actualizar</button>";
                        }
                        ?>

                    </div>
	</td>
</tr>	
<tr>
	<td colspan="100%" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
		(*) Campos de Ingreso Obligatorio.
	</td>
</tr>
</table>

<?
	}
?>
</table>
</form>