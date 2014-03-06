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

	
	
	
	
	if($error==0)
	{
		if(!empty($_GET['codbar_productonew']))
		{
			
			$mensaje=" Actualización Correcta ";
			$mostrar=1;
		}
		else
		{
			$fecha=date("Y-m-d H:i:s");
			
			$mensaje=" Inserciòn Correcta ";
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
<a href='?cat=3&sec=3'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado de Usuarios'></a>
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
        <td><label>Codigo de Barra:</label><label style="color:red">(*)</label><br/><input size="10" class="foo" type="text" name="codbar_productonew" value="<?=$_POST['codbar_productonew'];?>"
        <?
			if(!empty($_GET['codbar_productonew']))
			{
				echo " readonly ";
			}
        ?> 
        /></td>
        <td><label>Descripcion:</label><br/><input size="10" class="foo" type="text" name="descripcion" value="<?=$_POST['descripcion'];?>" style="text-align:left;"></td>
        <td><label>Codigo Interno:</label><br/><input size="10" class="foo" type="text" name="codigo_interno" value="<?=$_POST['codigo_interno'];?>"></td>
    <tr>
        <td><label>Familia</label><br />
        <select name="id_familia"  class="foo" onchange="submit()" 
        <?
			if(!empty($_GET['codbar_productonew']))
			{
				echo " disabled='disabled' ";
			}
        ?>
        >
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
        	 <select name="id_subfamilia"  class="foo" onchange="submit()"
             <?
			 if(!empty($_GET['codbar_productonew']))
			{
				echo " disabled='disabled' ";
			}
			 ?>
             >
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
        <td><label>Activo Fijo:</label><br/><input size="10" class="foo" type="text" name="activo_fijo" value="<?=$_POST['activo_fijo'];?>"
         <?
			 if(!empty($_GET['codbar_productonew']))
			{
				echo " disabled='disabled' ";
			}
		?>
        ></td>
        <td><label>Critico:</label><br/><input size="10" class="foo" type="text" name="critico" value="<?=$_POST['critico'];?>"
         <?
			 if(!empty($_GET['codbar_productonew']))
			{
				echo " disabled='disabled' ";
			}
	    ?>
        ></td>
        <td><label>Stock Critico:</label><br/><input size="10" class="foo" type="text" name="stock_critico" value="<?=$_POST['stock_critico'];?>"></td>
    </tr>
    <tr>
        <td><label>Pasillo:</label><br/><input size="10" class="foo" type="text" name="pasillo" value="<?=$_POST['pasillo'];?>"></td>
        <td><label>Casillero:</label><br/><input size="10" class="foo" type="text" name="casillero" value="<?=$_POST['casillero'];?>"></td>
    </tr>
    <?
	if(!empty($_GET['codbar_productonew']))
	{
    ?>
    <tr>
        <td><label>Cantidad:</label><br/><input size="10" class="foo" type="text" name="cantidad" value="<?=$_POST['cantidad'];?>"></td>
        <td><label>Precio PM:</label><br/><input size="10" class="foo" type="text" name="precio_pmp" value="<?=$_POST['precio_pmp'];?>"></td>
        <td><label>Precio Total:</label><br/><input size="10" class="foo" type="text" name="total" value="<?=$_POST['total'];?>"></td>
    </tr>
    <? 
	} 
	?>
    <tr>
    
        <td><label>Observaciones:</label><br/><input size="10" class="foo" type="text" name="observaciones" value="<?=$_POST['observaciones'];?>" style="text-align:left;"></td>
    </tr>
    <tr>
        <td  colspan="100%" style="text-align: right;"><input name="procesar" type="submit" value="Procesar"  style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
    <tr>
    <td align="center" colspan="100%;" style="color:red; font-weight:bold;">
    	(*) Campos de Ingreso Obligatorio.
    </td>
    </tr>
</table>

<?
	}
?>
</table>
</form>