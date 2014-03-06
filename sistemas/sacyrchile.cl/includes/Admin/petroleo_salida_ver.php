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
$error=0;
//Validaciones
if(!empty($_POST['procesar']))
{
	if(empty($_POST['tipo_salida']))
	{
			$error=1;
			$mensaje=" Debe ingresar el tipo de salida  ";
	}
	
	if(empty($_POST['fecha_salida']))
	{
			$error=1;
			$mensaje=" Debe ingresar la fecha de salida  ";
	}
	
	
	if(empty($_POST['centro_costo']))
	{
			$error=1;
			$mensaje=" Debe ingresar el centro de costo  ";
	}
	
	if(empty($_POST['litros']))
	{
			$error=1;
			$mensaje=" Debe ingresar los litros  ";
	}
	/*
	if(empty($_POST['persona_autoriza']))
	{
			$error=1;
			$mensaje=" Debe ingresar la persona que autoriza  ";
	}*/
	
	if(empty($_POST['persona_retira']))
	{
			$error=1;
			$mensaje=" Debe ingresar la persona que retira  ";
	}

	if(($_POST['tipo_salida'])==1)
	{
		if(empty($_POST['cod_producto']))
		{
			$error=1;
			$mensaje=" Debe ingresar el producto  ";
		}
		else
		{
			if(empty($_POST['cod_detalle_producto']))
			{
				$error=1;
				$mensaje=" Debe ingresar el detalle del producto  ";
			}
		}
	}


	if(($_POST['tipo_salida'])==2)
	{
		if(empty($_POST['id_lugaresfisicos']))
		{
			$error=1;
			$mensaje=" Debe ingresar el lugar fisico  ";
		}
	}
	
	
	if($error==0)
	{
		if(!empty($_GET['id_salida_petroleo']))
		{
			$sql= " UPDATE salida_petroleo SET ";
			$sql.=" dia='".substr($_POST['fecha_salida'],8,2)."',";
			$sql.=" mes='".substr($_POST['fecha_salida'],5,2)."',";
			$sql.=" agno='".substr($_POST['fecha_salida'],0,4)."',";
			$sql.=" cod_producto='".$_POST['cod_producto']."',";
			$sql.=" cod_detalle_producto='".$_POST['cod_detalle_producto']."',";
			$sql.=" litros='".$_POST['litros']."',";
			$sql.=" centro_costo='".$_POST['centro_costo']."',";
			$sql.=" persona_autoriza='".$_SESSION['user']."',";
			$sql.=" persona_retira='".$_POST['persona_retira']."',";
			$sql.=" tipo_salida='".$_POST['tipo_salida']."',";
			$sql.=" kilometro='".$_POST['kilometro']."',";
			$sql.=" horometro='".$_POST['horometro']."',";
			$sql.=" id_lugaresfisicos='".$_POST['id_lugaresfisicos']."',";
			$sql.=" observacion='".$_POST['observacion']."'";	
			$sql.=" WHERE id_salida_petroleo=".$_GET['id_salida_petroleo']." AND rut_empresa='".$_SESSION['empresa']."'";
			mysql_query($sql);
			$mensaje=" Actualización Correcta ";
			$mostrar=1;
		}
		else
		{
			$sql =" INSERT INTO salida_petroleo (rut_empresa,dia,mes,agno,cod_producto,cod_detalle_producto,litros,
				   centro_costo,persona_autoriza,persona_retira,tipo_salida,kilometro,horometro,id_lugaresfisicos,observacion,usuario_ingreso,fecha_ingreso)
				   VALUES ( ";
			$sql.= "'".$_SESSION['empresa']."',";
			$sql.= "'".substr($_POST['fecha_salida'],8,2)."',";
			$sql.= "'".substr($_POST['fecha_salida'],5,2)."',";
			$sql.= "'".substr($_POST['fecha_salida'],0,4)."',";
			$sql.= "'".$_POST['cod_producto']."',";
			$sql.= "'".$_POST['cod_detalle_producto']."',";
			$sql.= "'".$_POST['litros']."',";
			$sql.= "'".$_POST['centro_costo']."',";
			$sql.= "'".$_SESSION['user']."',";
			$sql.= "'".$_POST['persona_retira']."',";
			$sql.= "'".$_POST['tipo_salida']."',";
			$sql.= "'".$_POST['kilometro']."',";
			$sql.= "'".$_POST['horometro']."',";
			$sql.= "'".$_POST['id_lugaresfisicos']."',";
			$sql.= "'".$_POST['observacion']."',";
			$sql.= "'".$_SESSION['user']."',";
			$sql.= "'".date('Y-m-d')."'";
		    $sql.= " )";
			mysql_query($sql);
			$mensaje=" Inserción Correcta ";
			$mostrar=1;
		}

	}

}

if(empty($_GET['id_salida_petroleo']) and (empty($_POST['primera'])))
{
	$_POST['fecha_salida']=date('Y-m-d');

}

//Rescato los Datos
if(!empty($_GET['id_salida_petroleo']) and (empty($_POST['primera'])))
{
	$sql=" SELECT * FROM salida_petroleo WHERE  id_salida_petroleo=".$_GET['id_salida_petroleo']." AND rut_empresa='".$_SESSION['empresa']."' ";
	$rec=mysql_query($sql);
	$row=mysql_fetch_array($rec);
	$_POST=$row;	
	$_POST['fecha_salida']=$row['agno']."-".$row['mes']."-".$row['dia'];

} 

//Calcila la cantidad de  salidas
if(empty($_GET['id_salida_petroleo']))
{
		$sql="SELECT COUNT(*) FROM salida_petroleo WHERE rut_empresa='".$_SESSION['empresa']."' ";
		$rec_num=mysql_query($sql);
		$row_num=mysql_fetch_array($rec_num);
		$row_num[0]=$row_num[0]+1;
}

//Activo por primera ves el check
if(empty($_GET['id_salida_petroleo']) and (empty($_POST['primera'])))
{
	$_POST['tipo_salida']=1;
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
<form action="?cat=3&sec=12&id_salida_petroleo=<? echo $_GET['id_salida_petroleo']; ?>" method="POST" name="formulario" id="formulario">
<input  type="hidden" name="primera" value="1"/>

<table style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4" border="0">

<tr>
<td align="right" colspan="100%">
<a href='?cat=3&sec=14'><img id="volver" src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado Sailda de Petroleo'></a>
</td>
</tr>
<?
if($mostrar==0)
{
?>
<tr>
		<td width="20%">
			<img src="img/sacyr.png" border="0" width="150px">
		</td>
		<td colspan="2" style="font-size:24px; font-family:Tahoma, Geneva, sans-serif;text-align:left">
			Salida de petróleo Nº:
<?
			if(empty($_GET['id_salida_petroleo']))
			{
				echo $row_num[0];
			}
			else
			{
				echo $_GET['id_salida_petroleo'];
			}
?>

		</td>
</tr>
<tr height="15">
</tr>
    <!-- <tr>
        <td colspan="3" id="detalle_prod"></td>
    </tr> -->
    <tr>
        <td>
        	<label><b>Litros: </b></label><?=$_POST['litros'];?>
        </td>
        <td>
        	<label><b>Centro Costo: </b></label>
<?
	                $s = "SELECT * FROM centros_costos WHERE  rut_empresa='".$_SESSION['empresa']."' ORDER BY descripcion_cc";
	                $r = mysql_query($s,$con);
	                
	                while($roo = mysql_fetch_assoc($r)){
	                   if($_POST['centro_costo']==$roo['Id_cc']) echo $roo['descripcion_cc'];
	                }
?>
	        </select>
	    </td>
            <td><label><b>Fecha Salida: </b></label>
            <? echo date('d-m-Y', strtotime($_POST['fecha_salida'])); ?>
        </td>
    </tr>
    <tr>
      
        
    </tr>
    <tr>
        
        <td><label><b>Retirado por: </b></label><?=$_POST['persona_retira'];?></td>
<?
        	$sql_usu = " SELECT nombre FROM usuarios WHERE usuario='".$_POST['persona_autoriza']."' ";
        	$sql_usu.= "AND rut_empresa='".$_SESSION["empresa"]."' ";
			$rec_usu=mysql_query($sql_usu);
			$row_usu=mysql_fetch_array($rec_usu);
			$_POST['persona_autoriza']=$row_usu[0];

        	echo "<td><label><b>Autorizado por: </b></label>".$_POST['persona_autoriza']."</td>";	
?>
        <td><label><b>Tipo Salida: </b></label>
<?
	    	if($_POST['tipo_salida']==1) echo "Activo";
		    if($_POST['tipo_salida']==2) echo "Lugares Fisicos";
?> 
        </td>
    </tr>
    <?
	if($_POST['tipo_salida']==1)
	{
		echo "<tr>";
		echo "<td>";
        echo "<label><b>Activo:</b></label>";

        $s = "SELECT * FROM productos p  WHERE ";
        $s.= "cod_producto='".$_POST['cod_producto']."' and p.rut_empresa = '".$_SESSION['empresa']."'  GROUP BY descripcion";
        $res = mysql_query($s,$con);

        if(mysql_num_rows($res)!=NULL){
            while($r = mysql_fetch_assoc($res)){
				if($_POST['cod_producto']==$r['cod_producto']) echo $r['descripcion'];
            }
        }

		echo "</td>";
		echo "<td>";
		if(!empty($_POST['cod_producto']))
		{
			$sql3=" SELECT * FROM detalles_productos where cod_producto='".$_POST['cod_producto']."'  AND rut_empresa='".$_SESSION['empresa']."'";
			$res3 = mysql_query($sql3,$con);
			$num=mysql_num_rows($res3);
			echo "<label><b>Patente: </b></label>";
			if($num>0)
			{
                    if(mysql_num_rows($res)!=NULL)
					{
                        while($r3 = mysql_fetch_assoc($res3))
						{
                            
							if($_POST['cod_detalle_producto']==$r3['cod_detalle_producto']){
								if(!empty($r3['patente'])){
									echo $r3['patente'];	
								}else{
									echo $r3["codigo_interno"];
								}
							} 
                        }
                    }
               
        	}
        	else
        	{
        		echo $r3['codigo_interno'];
        	}



			
		}
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><label><b>Kilometro: </b></label>".$_POST['kilometro']."";
		echo "</td>";
		echo "<td><label><b>Horometro: </b></label>".$_POST['horometro']."";
		echo "</td>";
		echo "</tr>";
	}
	
	if($_POST['tipo_salida']==2)
	{
		echo "<tr>";
		echo "<td><label><b>Lugares Fisicos: </b></label>";
		$sql1="SELECT * from lugares_fisicos  WHERE  rut_empresa='".$_SESSION['empresa']."'";
		$rec1=mysql_query($sql1);
		while($row1=mysql_fetch_array($rec1))
		{
			if($row1['id_lf']==$_POST['id_lugaresfisicos'])
			{
				echo "".$row1['descripcion_lf']."";
			}
		}
		echo "</td>";
		echo "<tr>";
	}
	?>
    <tr>
        <td colspan="3" height="50" ><label><b>Observacion: </b></label><?=$_POST['observacion'];?></td>
   	</tr>
                <tr height="100px">
				<td colspan="100%" valign="bottom" style='text-align:center;tfont-family:tahoma;;color:#000;font-size:15px;font-weight:bold;' >
					_________________________________
				</td>
               <tr>
                <td colspan="100%" style='text-align:center;tfont-family:tahoma;;color:#000;font-size:15px;font-weight:bold;' >
                <? echo $_POST['persona_retira'];?>
                </td>
                </tr>
                <tr>
                </tr>
   <?
	}
   ?>
      </table>
      <table align="center"><tr><td>
      
       <input type="button" id="boton_im" onclick="javascript:imprSelec('formulario')" value="Imprimir" /></td></tr></table>
</form>
