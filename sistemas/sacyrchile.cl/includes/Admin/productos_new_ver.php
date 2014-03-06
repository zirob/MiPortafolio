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
$error=0;
//Validaciones
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
				$sql="SELECT  * FROM  productos_new WHERE codbar_productonew='".$_POST['codbar_productonew']."' and  rut_empresa='".$_SESSION['empresa']."'";
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
	
	
	
	
	if($error==0)
	{
		if(!empty($_GET['codbar_productonew']))
		{
			$sql.="  UPDATE productos_new SET";
			$sql.="  rut_empresa='".$_SESSION['empresa']."'";
			$sql.=", descripcion='".$_POST['descripcion']."'";
			$sql.=", codigo_interno='".$_POST['codigo_interno']."'";
			$sql.=", id_familia='".$_POST['id_familia']."'";
			$sql.=", id_subfamilia='".$_POST['id_subfamilia']."'";
			$sql.=", activo_fijo='".$_POST['activo_fijo']."'";
			$sql.=", critico='".$_POST['critico']."'";
			$sql.=", stock_critico='".$_POST['stock_critico']."'";
			$sql.=", pasillo='".$_POST['pasillo']."'";
			$sql.=", casillero='".$_POST['casillero']."'";
			$sql.=", cantidad='".$_POST['cantidad']."'";
			$sql.=", precio_pmp='".$_POST['precio_pmp']."'";
			$sql.=", total='".$_POST['total']."'";
			$sql.=", observaciones='".$_POST['observaciones']."'";	
			$sql.="  WHERE codbar_productonew='".$_GET['codbar_productonew']."' and  rut_empresa='".$_SESSION['empresa']."' ";
			mysql_query($sql);
			$mensaje=" Actualización Correcta ";
			$mostrar=1;
		}
		else
		{
			$fecha=date("Y-m-d H:i:s");
			$mensaje=" Inserción Correcta ";
			$sql=" INSERT INTO productos_new (codbar_productonew,rut_empresa,descripcion,codigo_interno
					,id_subfamilia,id_familia,activo_fijo,critico,stock_critico,pasillo,casillero,cantidad,precio_pmp,
					total,observaciones,usuario_ingreso,fecha_ingreso)
					VALUES ( ";
			$sql.=" '".$_POST['codbar_productonew']."',";
			$sql.=" '".$_SESSION['empresa']."',";
			$sql.=" '".$_POST['descripcion']."',";
			$sql.=" '".$_POST['codigo_interno']."',";
			$sql.=" '".$_POST['id_subfamilia']."',";
			$sql.=" '".$_POST['id_familia']."',";
			$sql.=" '".$_POST['activo_fijo']."',";
			$sql.=" '".$_POST['critico']."',";
			$sql.=" '".$_POST['stock_critico']."',";
			$sql.=" '".$_POST['pasillo']."',";
			$sql.=" '".$_POST['casillero']."',";
			$sql.=" '0',";
			$sql.=" '0',";
			$sql.=" '0',";
			$sql.=" '".$_POST['observaciones']."',";
			$sql.=" '".$_SESSION['user']."',";
			$sql.=" '".$fecha."'";
			$sql.=" )";
			$mostrar=1;

		}

	}
	
	
}

//Rescato los Datos
if(!empty($_GET['codbar_productonew']) and (empty($_POST['primera'])))
{
	$sql="SELECT  * FROM  productos_new WHERE codbar_productonew='".$_GET['codbar_productonew']."' and  rut_empresa='".$_SESSION['empresa']."'";
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
<form action="?cat=3&sec=31&codbar_productonew=<? echo $_GET['codbar_productonew']; ?>" method="POST">
<input  type="hidden" name="primera" value="1"/>

<table style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4" border="0">

<tr>
<td align="right" colspan="100%">
<a href='?cat=3&sec=15'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Usuarios'></a>
</td>
</tr>
<?
if($mostrar==0)
{
?>

<tr height="30px">
</tr>
    <tr>
        <td colspan="2" id="detalle_prod">

        </td>
    
    </tr>
    <tr>
        <td><label>Codigo de Barra:</label><br/><input size="10" class="foo" type="text" name="codbar_productonew" value="<?=$_POST['codbar_productonew'];?>"
        <?
			if(!empty($_GET['codbar_productonew']))
			{
				echo " readonly ";
			}
        ?> 
        /></td>
        <td><label>Descripcion:</label><br/><input readonly size="10" class="foo" type="text" name="descripcion" value="<?=$_POST['descripcion'];?>" style="text-align:left;"></td>
        <td><label>Codigo Interno:</label><br/><input  readonly size="10" class="foo" type="text" name="codigo_interno" value="<?=$_POST['codigo_interno'];?>"></td>
    <tr>
        <td><label>Familia</label><br />
        <select name="id_familia"  class="foo" onchange="submit()" disabled="disabled">
                <option value=""  class="foo">---</option>
            <?
                $s = "SELECT * FROM familia WHERE 1=1 ORDER BY descripcion_familia";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r)){
                    ?>  <option value="<?=$roo['id_familia'];?>"   <? if($_POST['id_familia']==$roo['id_familia']) echo " selected" ;?> class="foo"><?=$roo['descripcion_familia'];?></option> <?    
                }
        
                ?>
            </select>
        </td>
        <?
			if(!empty($_POST['id_familia']))
			{
		?>
        	 <td><label>Sub Familia</label><br />
        	 <select name="id_subfamilia"  class="foo" onchange="submit()" disabled="disabled">
             <option value=""  class="foo">---</option>
            <?
                $s = "SELECT * FROM subfamilia WHERE id_familia='".$_POST['id_familia']."' ORDER BY descripcion_subfamilia";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r)){
                    ?>  <option value="<?=$roo['id_subfamilia'];?>"   <? if($_POST['id_subfamilia']==$roo['id_subfamilia']) echo " selected" ;?> class="foo"><?=$roo['descripcion_subfamilia'];?></option> <?    
                }
        
             ?>
            </select>
            </td>
		<?		
			}
		?>
    </tr>
     <tr>
        <td><label>Activo Fijo:</label><br/><input readonly  size="10" class="foo" type="text" name="activo_fijo" value="<?if($_POST['activo_fijo']==0){echo 'NO';}else{echo 'SI';}?>"></td>
        <td><label>Critico:</label><br/><input readonly size="10" class="foo" type="text" name="critico" value="<?if($_POST['critico']==0){echo 'NO';}else{echo 'SI';}?>"></td>
        <td><label>Stock Critico:</label><br/><input readonly  size="10" class="foo" type="text" name="stock_critico" value="<?=$_POST['stock_critico'];?>"></td>
    </tr>
    <tr>
        <td><label>Pasillo:</label><br/><input size="10" readonly class="foo" type="text" name="pasillo" value="<?=$_POST['pasillo'];?>"></td>
        <td><label>Casillero:</label><br/><input size="10" readonly class="foo" type="text" name="casillero" value="<?=$_POST['casillero'];?>"></td>
    </tr>
    <?
	if(!empty($_GET['codbar_productonew']))
	{
    ?>
    <tr>
        <td><label>Cantidad:</label><br/><input readonly size="10" class="foo" type="text" name="cantidad" value="<?=$_POST['cantidad'];?>"></td>
        <td><label>PMP:</label><br/><input readonly  size="10" class="foo" type="text" name="precio_pmp" value="<?=$_POST['precio_pmp'];?>"></td>
        <td><label>Precio Total:</label><br/><input readonly  size="10" class="foo" type="text" name="total" value="<?=$_POST['total'];?>"></td>
    </tr>
    <? 
	} 
	?>
    <tr>
    
        <td><label>Observaciones:</label><br/><input readonly size="10" class="foo" type="text" name="observaciones" value="<?=$_POST['observaciones'];?>" style="text-align:left;"></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left;">
            <div id="barcode" ></div>
            <a href="javascript:imprSelec('barcode')">
                
                <?if($_GET["action"]==2){ ?><img src="img/printer_ok.png" width="36px" height="36px" border="0"  class="toolTIP" title="Imprimir Codigo de Barra"><?}?>
            </a>
        </td>
	</tr>
</table>

<?
	}
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