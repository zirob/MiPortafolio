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
		width:80%;
		text-align:center;
	}

	</style>

<?php
// var_dump($_SESSION);
// var_dump($_POST);
$error=0;
//Validaciones
if(empty($_GET['codbar_productonew']))
{	
	if(!empty($_POST['activo_fijo']))
		$codigo = '1';
	else
		$codigo = '2';


	if(!empty($_POST['critico']))
		$codigo.='1';
	else
		$codigo.='2';

	//Codigo de familia
	if(strlen($_POST['id_familia']) == 1){

		$if_familia = $_POST['id_familia'];
		$codigo.= '0'.$if_familia;
	}else{
		$codigo.=$_POST['id_familia'];
	}

	//Codigo de Subfamilia
	if(strlen($_POST['id_subfamilia']) == 1){

		$if_familia = $_POST['id_subfamilia'];
		$codigo.= '0'.$if_familia;
	}else{
		$codigo.=$_POST['id_subfamilia'];
	}
	
	// $codigo.=$_POST['id_subfamilia'];
	
	//codigo nuevo	
	$sql = "SELECT * FROM  productos_new WHERE id_subfamilia='".$_POST['id_subfamilia']."' AND";
	$sql.= " rut_empresa='".$_SESSION['empresa']."'";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$num=mysql_num_rows($rec);
	$num=$num+1;
	
	$largo=strlen($num);
	$largo=7-$largo;
	
	for($i=0;$i<$largo;$i++)
	$codigo.=0;
	$codigo.=$num;
	$_POST['codbar_productonew'] = $codigo;
}

if(!empty($_POST['procesar']))
{
	if(empty($_POST['codbar_productonew']))
	{
			$error=1;
			$mensaje=" Debe ingresar el codigo de barra  ";
	}
	else
	{
		if(empty($_GET['codbar_productonew']))
		{
				$sql = "SELECT  * FROM  productos_new WHERE codbar_productonew='".$_POST['codbar_productonew']."' ";
				$sql.= "AND  rut_empresa='".$_SESSION['empresa']."'";
				$rec=mysql_query($sql);
				$row=mysql_fetch_array($rec);
				$num=mysql_num_rows($rec);
				if($num>0)
				{
					$error=1;
					$mensaje=" El codigo de barra ya existe ";
				}
		}
	}

	if(empty($_POST['descripcion']))
	{
			$error=1;
			$mensaje=" Debe ingresar la descripcion  ";
	}
	
	if(empty($_POST['id_subfamilia'])and (empty($_GET['codbar_productonew'])))
	{
			$error=1;
			$mensaje=" Debe ingresar la subfamilia  ";
	}

	if(empty($_POST['id_familia'])and (empty($_GET['codbar_productonew'])))
	{
			$error=1;
			$mensaje=" Debe ingresar la familia  ";
	}


	/*if((empty($_POST['activo_fijo']))and (empty($_GET['codbar_productonew'])))
	{
			$error=1;
			$mensaje=" Debe ingresar el si es activo fijo  ";
	}*/


	if(!empty($_POST['critico']))
	{
			if(empty($_POST['stock_critico']))
			{
				$error=1;
				$mensaje=" Debe ingresar el stock critico  ";
			}
	}
	
	
	if(empty($_POST['pasillo']))
	{
			$error=1;
			$mensaje=" Debe ingresar el pasillo  ";
	}

	if(empty($_POST['casillero']))
	{
			$error=1;
			$mensaje=" Debe ingresar el casillero  ";
	}

	
	if($error==0){

		if(!empty($_GET['codbar_productonew']) and !empty($_POST['procesar'])){

			$up_prd.="  UPDATE productos_new SET";
			$up_prd.="  rut_empresa='".$_SESSION['empresa']."'";
			$up_prd.=", descripcion='".$_POST['descripcion']."'";
			$up_prd.=", codigo_interno='".$_POST['codigo_interno']."'";
			$up_prd.=", stock_critico='".$_POST['stock_critico']."'";
			$up_prd.=", pasillo='".$_POST['pasillo']."'";
			$up_prd.=", casillero='".$_POST['casillero']."'";
			$up_prd.=", cantidad='".$_POST['cantidad']."'";
			$up_prd.=", precio_pmp='".$_POST['precio_pmp']."'";
			$up_prd.=", total='".$_POST['total']."'";
			$up_prd.=", observaciones='".$_POST['observaciones']."'";	
			$up_prd.=", observaciones1='".$_POST['observaciones1']."'";	
			$up_prd.=", observaciones2='".$_POST['observaciones2']."'";	
			$up_prd.="  WHERE codbar_productonew='".$_GET['codbar_productonew']."' and  rut_empresa='".$_SESSION['empresa']."' ";
			$consulta = mysql_query($up_prd);
			if($consulta)
				$mensaje=" Actualización Correcta ";
				$mostrar=1;

			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new', '".$_GET['codbar_productonew']."', '3'";
            $sql_even.= ", 'UPDATE:rut_empresa=".$_SESSION['empresa']."";
			$sql_even.=", descripcion=".$_POST['descripcion']."";
			$sql_even.=", codigo_interno=".$_POST['codigo_interno']."";
			$sql_even.=", stock_critico=".$_POST['stock_critico']."";
			$sql_even.=", pasillo=".$_POST['pasillo']."";
			$sql_even.=", casillero=".$_POST['casillero']."";
			$sql_even.=", cantidad=".$_POST['cantidad']."";
			$sql_even.=", precio_pmp=".$_POST['precio_pmp']."";
			$sql_even.=", total=".$_POST['total']."";
			$sql_even.=", observaciones=".$_POST['observaciones']." ";
            $sql_even.= "','".$_SERVER['REMOTE_ADDR']."', 'Update', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con); 
		}
		else
		{

			$fecha=date("Y-m-d H:i:s");
			$sql=" INSERT INTO productos_new (codbar_productonew,rut_empresa,descripcion,codigo_interno,id_familia,id_subfamilia,
					activo_fijo,critico,stock_critico,pasillo,casillero,cantidad,precio_pmp,
					total,observaciones, observaciones1, observaciones2, usuario_ingreso,fecha_ingreso)
					VALUES ( ";
			$sql.=" '".$_POST['codbar_productonew']."',";
			$sql.=" '".$_SESSION['empresa']."',";
			$sql.=" '".$_POST['descripcion']."',";
			$sql.=" '".$_POST['codigo_interno']."',";
			
			$sql.=" '".$_POST['id_familia']."',";
			$sql.=" '".$_POST['id_subfamilia']."',";
			$sql.=" '".$_POST['activo_fijo']."',";
			$sql.=" '".$_POST['critico']."',";
			
			$sql.=" '".$_POST['stock_critico']."',";
			$sql.=" '".$_POST['pasillo']."',";
			$sql.=" '".$_POST['casillero']."',";
			$sql.=" '0',";
			$sql.=" '0',";
			$sql.=" '0',";
			$sql.=" '".$_POST['observaciones']."',";
			$sql.=" '".$_POST['observaciones1']."',";
			$sql.=" '".$_POST['observaciones2']."',";
			$sql.=" '".$_SESSION['user']."',";
			$sql.=" '".$fecha."'";
			$sql.=" )";
			
			$consulta = mysql_query($sql);
			if($consulta)
				$mensaje=" Inserción Correcta ";
				$mostrar=1;


			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos_new', '".$_POST['codbar_productonew']."', '3'";
            $sql_even.= ", 'INSERT:rut_empresa=".$_SESSION['empresa']."";
			$sql_even.=", descripcion=".$_POST['descripcion']."";
			$sql_even.=", codigo_interno=".$_POST['codigo_interno']."";
			$sql_even.=", stock_critico=".$_POST['stock_critico']."";
			$sql_even.=", pasillo=".$_POST['pasillo']."";
			$sql_even.=", casillero=".$_POST['casillero']."";
			$sql_even.=", cantidad=0";
			$sql_even.=", precio_pmp=0";
			$sql_even.=", total=0";
			$sql_even.=", observaciones=".$_POST['observaciones']." ";
            $sql_even.= "','".$_SERVER['REMOTE_ADDR']."', 'Insert', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);
            
		}

	}
	
	
}

//Rescato los Datos
if(!empty($_GET['codbar_productonew']) and (empty($_POST['primera']))){ 
	$sql = "SELECT * FROM  productos_new WHERE codbar_productonew='".$_GET['codbar_productonew']."' ";
	$sql.= "AND  rut_empresa='".$_SESSION['empresa']."'";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$_POST=$row;	
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



<form action="?cat=3&sec=31&action=<?=$_GET["action"];?>&codbar_productonew=<? echo $_GET['codbar_productonew']; ?>" method="POST">
<input  type="hidden" name="primera" value="1"/>

<table style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4" border="0">

<tr>
<td align="right" colspan="100%">
<a href='?cat=3&sec=15'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Productos'></a>
</td>
</tr>

<?
if($mostrar==0){
?>

<tr height="30px">
</tr>
<tr>
        <td>
        	<label>Codigo de Barra:</label><label style="color:red">(*)</label><br/>
        	<input readonly size="10" class="foo" type="text" name="codbar_productonew" value="<?=$_POST['codbar_productonew'];?>"
        <?
			if(!empty($_GET['codbar_productonew'])){
				echo " readonly ";
			}
        ?> 
        /></td>
        <td><label>Descripción:</label><label style="color:red">(*)</label><br/>
        	<input size="10" class="foo" type="text" name="descripcion" value="<?=$_POST['descripcion'];?>" style="text-align:left;"></td>
        <td><label>Codigo Interno:</label><br/>
        	<input size="10" class="foo" type="text" name="codigo_interno" value="<?=$_POST['codigo_interno'];?>"></td>
</tr>        	
<tr>
    <td>
        <label>Familia</label><label style="color:red">(*)</label><br/>
		<?

		if(empty($_GET['codbar_productonew'])){
		?>
		        <select name="id_familia"  class="foo" onchange="submit()" >
		            <option value=""  class="foo">---</option>
		<?
		                $s = "SELECT * FROM familia WHERE 1=1 ORDER BY descripcion_familia";
		                $r = mysql_query($s,$con);
		                while($roo = mysql_fetch_assoc($r)){
		?>  
		                    <option value="<?=$roo['id_familia'];?>"   <? if($_POST['id_familia']==$roo['id_familia']) echo " selected" ;?> class="foo"><?=$roo['descripcion_familia'];?></option> <?    
		                }
		?>
		       </select>
		<?
		}else{
	   	?>
	   					<input type="hidden" name="id_familia" value="<?=$_POST["id_familia"];?>">
	   	<?
					   $sql1=" SELECT descripcion_familia from familia WHERE id_familia=".$_POST['id_familia'];
					   $rec1=mysql_query($sql1);
					   $row1=mysql_fetch_array($rec1);
	   				   echo "<input type='text  name='nombre'  readonly  class='foo' value='".$row1['descripcion_familia']."'>";
		}
		?> 
	</td>

	<td><label>Sub Familia</label><label style="color:red">(*)</label><br />
	<?
	if(empty($_GET['codbar_productonew']))
	{
		if(!empty($_POST['id_familia']))
		{
	?>
		 <select name="id_subfamilia"  class="foo" onchange="submit()">
	     <option value=""  class="foo">---</option>
	<?
	        $s = "SELECT * FROM subfamilia WHERE id_familia='".$_POST['id_familia']."' ORDER BY descripcion_subfamilia";
	        $r = mysql_query($s,$con);
	        
	        while($roo = mysql_fetch_assoc($r)){
	?>  
				<option value="<?=$roo['id_subfamilia'];?>"   <? if($_POST['id_subfamilia']==$roo['id_subfamilia']) 
				echo " selected" ;?> class="foo"><?=$roo['descripcion_subfamilia'];?></option> 
				
	<?    
			}
	     	echo "  </select>";
		}
	}else{

	   	?>
	   	<input type="hidden" name="id_subfamilia" value="<?=$_POST["id_subfamilia"];?>">
	   	<?

		$sql1=" SELECT descripcion_subfamilia from subfamilia WHERE  id_subfamilia=".$_POST['id_subfamilia'];
		$rec1=mysql_query($sql1);
		$row1=mysql_fetch_array($rec1);
	   	echo "<input type='text  name='descripcion_subfamilia'  readonly  class='foo' value='".$row1['descripcion_subfamilia']."'>";
	}
	?>

	</td>
</tr>

<tr>
    	<td>
	     	<label>Activo Fijo :</label><!-- <label style="color:red">(*)</label> -->
	     	<input  type="checkbox" name="activo_fijo" value="1" onchange="submit()" 
	        <?
				if(!empty($_POST['activo_fijo']))
				echo " checked ";
				
				 if(!empty($_GET['codbar_productonew']))
				 echo " disabled ";
			?>
		        />
		        <?
		        if($_GET["action"]==2) echo "<input type='hidden' name='activo_fijo' value='".$_POST["activo_fijo"]."'>";
		        ?>
		</td>
	        
		<td>
        <?
        /*
		if(!empty($_POST['validador2']))
		{
			
		?>

        <label>Activo Fijo:</label><br/><input size="10" class="foo" type="text" name="activo_fijo" value="<?=$_POST['activo_fijo'];?>"
         <?
			 if(!empty($_GET['codbar_productonew']))
			{
				echo " disabled='disabled' ";
			}
		?>
        <?
       
		}
        ?>
        </td>
        <td><label>Critico:</label><label style="color:red">(*)</label><br/><input size="10" class="foo" type="text" name="critico" value="<?=$_POST['critico'];?>"
         <?
			 if(!empty($_GET['codbar_productonew']))
			{
				echo " disabled='disabled' ";
			}
			*/
	    ?>
        </td>
</tr>
<tr>
        <td>
        	<label>Critico:</label><!-- <label style="color:red">(*)</label> -->
        	<input  type="checkbox" name="critico" value="1" onchange="submit()" 
	        <?
			if(!empty($_POST['critico']))
				echo " checked ";
				
				if(!empty($_GET['codbar_productonew']))
					echo " disabled ";
				?>
		        />

		        <?
		        if($_GET["action"]==2) echo "<input type='hidden' name='critico' value='".$_POST["critico"]."'>";
		        ?>
		</td>
        <?
		if(!empty($_POST['critico'])){
		?>
			<td>
				<label>Stock Critico:</label><label style="color:red">(*)</label><br/>
				<input size="10" class="foo" type="text" name="stock_critico" onKeyPress='ValidaSoloNumeros()' value="<?=$_POST['stock_critico'];?>"
		        <?
				if(!empty($_GET['codbar_productonew'])){
					//echo " readonly ";
				}
				?>
		        />
		    </td>
    	<?
		}
		?>
</tr>
<tr>
        <td><label>Pasillo:</label><label style="color:red">(*)</label><br/><input size="10" class="foo" type="text" name="pasillo"  value="<?=$_POST['pasillo'];?>"></td>
        <td><label>Casillero:</label><label style="color:red">(*)</label><br/><input size="10" class="foo" type="text" name="casillero"  value="<?=$_POST['casillero'];?>"></td>
</tr>
    <?
	if(!empty($_GET['codbar_productonew'])){
    ?>
<tr>
        <td><label>Cantidad:</label><br/><input size="10" class="foo" type="text" name="cantidad" value="<?=$_POST['cantidad'];?>"></td>
        <td><label>PMP:</label><br/><input size="10" class="foo" type="text" name="precio_pmp" value="<?=$_POST['precio_pmp'];?>" <?if($_SESSION['user']!='admin') echo "readonly";?>></td>
        <td><label>Precio Total:</label><br/><input size="10" class="foo" type="text" name="total" value="<?=$_POST['total'];?>"></td>
</tr>
    <?}?>
<tr>
        <td><label>Observaciones:</label><br/>
        	<input size="10" class="foo" type="text" name="observaciones" value="<?=$_POST['observaciones'];?>" style="text-align:left;">
        </td>

        <td><label>Observaciones 1:</label><br/>
        	<input size="10" class="foo" type="text" name="observaciones1" value="<?=$_POST['observaciones1'];?>" style="text-align:left;">
        </td>

        <td><label>Observaciones 2:</label><br/>
        	<input size="10" class="foo" type="text" name="observaciones2" value="<?=$_POST['observaciones2'];?>" style="text-align:left;">
        </td>
</tr>
<tr>
        <td colspan="3" style="text-align: left;">
            <div id="barcode" ><?echo "hola mundo";?></div>
            <a href="javascript:imprSelec('barcode')">
                <?if($_GET["action"]==2){ ?><img src="img/printer_ok.png" width="36px" height="36px" border="0"  class="toolTIP" title="Imprimir Codigo de Barra"><?}?>
            </a>
        </td>
</tr>
<tr>
        <td  colspan="100%" style="text-align: right;">
        	<input name="procesar" type="submit" value="Procesar"  style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;">

        </td>
    </tr>
    <tr>
    <td align="center" colspan="100%;" style="color:red; font-weight:bold;">
    	(*) Campos de Ingreso Obligatorio.
    </td>
    </tr>
</table>

<?
	}
	// var_dump($_POST);
?>
</table>
</form>

<?
if($_POST["codbar_productonew"]!=""){
?>
    <script type="text/javascript">
        $(document).ready(
            function(){
                $('#barcode').barcode("<?=$_POST["codbar_productonew"];?>", "code128");
            }
        );
    </script> 

    <script type="text/javascript">
        function imprSelec(barcode)
        {
            var ficha=document.getElementById(barcode);
            var ventimp=window.open(' ','popimpr');
ventimp.document.write("<table border=0><tr><td width=\"5\" height=\"90\"></td><td align=\"center\">"+ficha.innerHTML+"</td><td <td width=\"30\"></td><td>"+ficha.innerHTML+"</td><td width=\"0\"></td></tr></table>");
	    
ventimp.document.write("<table border=0><tr><td width=\"5\" height=\"130\"></td><td align=\"center\">"+ficha.innerHTML+"</td><td <td width=\"30\"></td><td>"+ficha.innerHTML+"</td><td width=\"0\"></td></tr></table>");
            ventimp.document.close();
            ventimp.print();
            ventimp.close();}
    </script>
<?
}