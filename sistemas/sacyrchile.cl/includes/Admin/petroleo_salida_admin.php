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
			$mensaje=" Debe Seleccionar Activo  ";
		}
		else
		{
			if(empty($_POST['cod_detalle_producto']))
			{
				$error=1;
				$mensaje=" Debe seleccionar la Patente del Activo  ";
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
			$sql_usu=" select usuario from usuarios where usuario='".$_SESSION['user']."' AND rut_empresa='".$_SESSION["empresa"]."' ";
			$rec_usu=mysql_query($sql_usu);
			$row_usu=mysql_fetch_array($rec_usu);
			$_POST['persona_autoriza']=$row_usu[0];

			$sql= " UPDATE salida_petroleo SET ";
			$sql.=" dia='".substr($_POST['fecha_salida'],8,2)."',";
			$sql.=" mes='".substr($_POST['fecha_salida'],5,2)."',";
			$sql.=" agno='".substr($_POST['fecha_salida'],0,4)."',";
			$sql.=" cod_producto='".$_POST['cod_producto']."',";
			$sql.=" cod_detalle_producto='".$_POST['cod_detalle_producto']."',";
			$sql.=" litros='".$_POST['litros']."',";
			$sql.=" centro_costo='".$_POST['centro_costo']."',";
			$sql.=" persona_autoriza='".$_POST['persona_autoriza']."',";
			$sql.=" persona_retira='".$_POST['persona_retira']."',";
			$sql.=" tipo_salida='".$_POST['tipo_salida']."',";
			$sql.=" kilometro='".$_POST['kilometro']."',";
			$sql.=" horometro='".$_POST['horometro']."',";
			$sql.=" id_lugaresfisicos='".$_POST['id_lugaresfisicos']."',";
			$sql.=" observacion='".$_POST['observacion']."',";	
			$sql.=" observacion1='".$_POST['observacion1']."',";	
			$sql.=" observacion2='".$_POST['observacion2']."'";	
			$sql.=" WHERE id_salida_petroleo=".$_GET['id_salida_petroleo']." AND rut_empresa='".$_SESSION['empresa']."'";
			$consulta = mysql_query($sql);
			if($consulta)
				$mensaje=" Actualización Correcta ";
				$mostrar=1;

			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'salida_petroleo', '".$_GET['id_salida_petroleo']."', '3'";
            $sql_even.= ", 'UPDATE:";
            $sql_even.=" dia=".substr($_POST['fecha_salida'],8,2).",";
			$sql_even.=" mes=".substr($_POST['fecha_salida'],5,2).",";
			$sql_even.=" agno=".substr($_POST['fecha_salida'],0,4).",";
			$sql_even.=" cod_producto=".$_POST['cod_producto'].",";
			$sql_even.=" cod_detalle_producto=".$_POST['cod_detalle_producto'].",";
			$sql_even.=" litros=".$_POST['litros'].",";
			$sql_even.=" centro_costo=".$_POST['centro_costo'].",";
			$sql_even.=" persona_autoriza=".$_POST['persona_autoriza'].",";
			$sql_even.=" persona_retira=".$_POST['persona_retira'].",";
			$sql_even.=" tipo_salida=".$_POST['tipo_salida'].",";
			$sql_even.=" kilometro=".$_POST['kilometro'].",";
			$sql_even.=" horometro=".$_POST['horometro'].",";
			$sql_even.=" id_lugaresfisicos=".$_POST['id_lugaresfisicos'].",";
			$sql_even.=" observacion=".$_POST['observacion']."";	
            $sql_even.= "','".$_SERVER['REMOTE_ADDR']."', 'Update', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);  
		}
		else
		{
			$sql_usu=" select usuario from usuarios where usuario='".$_SESSION['user']."' AND rut_empresa='".$_SESSION['empresa']."'";
			$rec_usu=mysql_query($sql_usu);
			$row_usu=mysql_fetch_array($rec_usu);
			$_POST['persona_autoriza']=$row_usu[0];

			$sql =" INSERT INTO salida_petroleo (rut_empresa,dia,mes,agno,cod_producto,cod_detalle_producto,litros,
				   centro_costo,persona_autoriza,persona_retira,tipo_salida,kilometro,horometro,id_lugaresfisicos,observacion,observacion1,observacion2,usuario_ingreso,fecha_ingreso)
				   VALUES ( ";
			$sql.= "'".$_SESSION['empresa']."',";
			$sql.= "'".substr($_POST['fecha_salida'],8,2)."',";
			$sql.= "'".substr($_POST['fecha_salida'],5,2)."',";
			$sql.= "'".substr($_POST['fecha_salida'],0,4)."',";
			$sql.= "'".$_POST['cod_producto']."',";
			$sql.= "'".$_POST['cod_detalle_producto']."',";
			$sql.= "'".$_POST['litros']."',";
			$sql.= "'".$_POST['centro_costo']."',";
			$sql.= "'".$_POST['persona_autoriza']."',";
			$sql.= "'".$_POST['persona_retira']."',";
			$sql.= "'".$_POST['tipo_salida']."',";
			$sql.= "'".$_POST['kilometro']."',";
			$sql.= "'".$_POST['horometro']."',";
			$sql.= "'".$_POST['id_lugaresfisicos']."',";
			$sql.= "'".$_POST['observacion']."',";
			$sql.= "'".$_POST['observacion1']."',";
			$sql.= "'".$_POST['observacion2']."',";
			$sql.= "'".$_SESSION['user']."',";
			$sql.= "'".date('Y-m-d H:i:s')."'";
		    $sql.= " )";
			$consulta = mysql_query($sql);
			if($consulta){
				$mensaje=" Inserción Correcta ";
				$mostrar=1;
			}else{
				$mensaje = " Inserción bloqueada. Debe generar una Entrada de Petroleo en el mes correpondiente. ";
				$mostrar = 1;
				$error = 1;
			}

			$consulta = "SELECT MAX(id_salida_petroleo) as id_salida_petroleo FROM salida_petroleo WHERE rut_empresa='".$_SESSION["empresa"]."'";
            $resultado=mysql_query($consulta);
            $fila=mysql_fetch_array($resultado);
			$sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'salida_petroleo', '".$fila['id_salida_petroleo']."', '2'";
            $sql_even.= ", 'INSERT: empresa=".$_SESSION["empresa"].",";
            $sql_even.=" dia=".substr($_POST['fecha_salida'],8,2).",";
			$sql_even.=" mes=".substr($_POST['fecha_salida'],5,2).",";
			$sql_even.=" agno=".substr($_POST['fecha_salida'],0,4).",";
			$sql_even.=" cod_producto=".$_POST['cod_producto'].",";
			$sql_even.=" cod_detalle_producto=".$_POST['cod_detalle_producto'].",";
			$sql_even.=" litros=".$_POST['litros'].",";
			$sql_even.=" centro_costo=".$_POST['centro_costo'].",";
			$sql_even.=" persona_autoriza=".$_POST['persona_autoriza'].",";
			$sql_even.=" persona_retira=".$_POST['persona_retira'].",";
			$sql_even.=" tipo_salida=".$_POST['tipo_salida'].",";
			$sql_even.=" kilometro=".$_POST['kilometro'].",";
			$sql_even.=" horometro=".$_POST['horometro'].",";
			$sql_even.=" id_lugaresfisicos=".$_POST['id_lugaresfisicos'].",";
			$sql_even.=" observacion=".$_POST['observacion']."";	
            $sql_even.= "','".$_SERVER['REMOTE_ADDR']."', 'Insert', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con); 
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
		$sql="SELECT max(id_salida_petroleo) FROM salida_petroleo WHERE rut_empresa='".$_SESSION['empresa']."' ";
		$rec_num=mysql_query($sql);
		$row_num=mysql_fetch_array($rec_num);
		$row_num[0]=$row_num[0]+1;

		$sql_usu=" select nombre from usuarios where usuario='".$_SESSION['user']."' AND rut_empresa='".$_SESSION['empresa']."' ";
		$rec_usu=mysql_query($sql_usu);
		$row_usu=mysql_fetch_array($rec_usu);
		$_POST['persona_autoriza']=$row_usu[0];
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
	/*
	if($mensaje!='')
	{
	    if($_GET['id_salida_petroleo'])
	    {
	    	$id_salida_petroleo=$_GET['id_salida_petroleo'];
	    }

	    if($_GET['id_salida_petroleo'])
	    {
	    	$id_salida_petroleo=$_GET['id_salida_petroleo'];
	    }
		echo "<form method='POST' action='?cat=3&sec=16&id_salida_petroleo='".$id_salida_petroleo." >";
		echo "<br><button type='submit' >Imprimir</button>";
		echo "</form>";
	}
	*/
	echo "</div>";
}
else
{
	echo "<div style=' width:100%; height:auto; border-top: solid 3px red ;border-bottom: solid 3px red; color:red; text-align:center;font-family:tahoma; font-size:18px;'>";
	echo $mensaje;
	echo "</div>";
}
?>
<form action="?cat=3&sec=12&id_salida_petroleo=<? echo $_GET['id_salida_petroleo']; ?>" method="POST">
<input  type="hidden" name="primera" value="1"/>

<table style="width:900px;" id="detalle-prov"  cellpadding="3" cellspacing="4" border="0">

<tr>
<td align="right" colspan="100%">
<a href='?cat=3&sec=14'><img src='img/view_previous.png' width='36px' height='36px' border='0' style='float:right;' class='toolTIP' title='Volver al Listado Sailda de Petroleo'></a>
</td>
</tr>
<?
if($mostrar==0)
{
?>
<tr>
<td colspan="100%" align="right" style="font-size:24px; font-family:Tahoma, Geneva, sans-serif;text-align:center">
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
<tr height="30px">
</tr>
    <tr>
        <td colspan="2" id="detalle_prod">

        </td>
    
    </tr>
    <tr>
        <td><label>Litros:</label><label style="color:red">(*)</label><br/><input size="10"  onKeyPress='ValidaSoloNumeros()' class="foo" type="text" name="litros" value="<?=$_POST['litros'];?>"></td>
        <td><label>Centro Costo:</label><label style="color:red">(*)</label>
        <select name="centro_costo"  class="foo">
                <option value=""  class="foo">---</option>
            <?
                $s = "SELECT * FROM centros_costos WHERE  rut_empresa='".$_SESSION['empresa']."' ORDER BY descripcion_cc";
                $r = mysql_query($s,$con);
                
                while($roo = mysql_fetch_assoc($r)){
                    ?>  <option value="<?=$roo['Id_cc'];?>"   <? if($_POST['centro_costo']==$roo['Id_cc']) echo " selected" ;?> class="foo"><?=$roo['descripcion_cc'];?></option> <?    
                }
        
                ?>
            </select>
        </td>
            <td><label>Fecha Salida:</label><label style="color:red;">(*)</label><br/>
            <input  type="date" name="fecha_salida" value="<? echo $_POST['fecha_salida']; ?>" class="foo">
        </td>
    </tr>
    <tr>
      
        
    </tr>
    <tr>
        
        <td><label>Retirado por:</label><label style="color:red;">(*)</label><br/><input type="text" class="foo" name="persona_retira" value="<?=$_POST['persona_retira'];?>"></td>
        <?
        if(empty($_GET['id_salida_petroleo']))
		{			
        	echo "<td><label>Autorizado por:</label><br/><input type='text'  readonly class='foo' name='persona_autoriza' value='".$_POST['persona_autoriza']."'></td>";
    	}
    	else
    	{
    		$sql_usu=" select nombre from usuarios where usuario='".$_POST['persona_autoriza']."' AND rut_empresa='".$_SESSION["empresa"]."' ";
			$rec_usu=mysql_query($sql_usu);
			$row_usu=mysql_fetch_array($rec_usu);
			$_POST['persona_autoriza']=$row_usu[0];

    		echo "<td><label>Autorizado por:</label><br/><input type='text'  readonly class='foo' name='persona_autoriza' value='".$_POST['persona_autoriza']."'></td>";	
    	}
    	?>
    </tr>
     <tr>
        <td><label>Tipo Salida </label><br/>
    	Activo          <input  type="radio" name="tipo_salida"   value="1" onchange="submit()" <? if($_POST['tipo_salida']==1) echo " checked "; ?>/>  
        Lugares Fisicos <input  type="radio" name="tipo_salida"   value="2" onchange="submit()" <? if($_POST['tipo_salida']==2) echo " checked "; ?>/> 
        <td>
        
        <td></td>
    </tr>
    <?
	if($_POST['tipo_salida']==1)
	{
        $s = "SELECT * FROM productos p INNER JOIN detalles_productos d ON p.cod_producto = d.cod_producto WHERE p.rut_empresa = '".$_SESSION['empresa']."'  GROUP BY descripcion";
        $res = mysql_query($s,$con);
		echo "<tr>";
		echo "<td>";
        echo "<label>Activo:</label><label style='color:red'></label><br/>
            <select name='cod_producto' class='foo' style='width:150px' onchange='submit()'>
                <option value=''> --- </option>";
              

                    if(mysql_num_rows($res)!=NULL)
					{
                        while($r = mysql_fetch_assoc($res))
						{
                            
                            echo "<option value='".$r['cod_producto']."'";
							if($_POST['cod_producto']==$r['cod_producto'])
							echo " selected ";
							echo ">".$r['descripcion']."</option>";
                        }
                    }
               
        echo "</select>";
		echo "</td>";
		echo "<td>";
		if(!empty($_POST['cod_producto']))
		{
			$sql3=" SELECT * FROM detalles_productos where cod_producto='".$_POST['cod_producto']."'   AND rut_empresa='".$_SESSION['empresa']."'";
			$res3 = mysql_query($sql3,$con);
			$num=mysql_num_rows($res3);
			echo "<label>Patente :</label><label style='color:red'></label><br/>";
			if($num>0)
			{
            echo "<select name='cod_detalle_producto' class='foo' style='width:150px' '>
                <option value=''> --- </option>";

             
                    if(mysql_num_rows($res)!=NULL)
					{
                        while($r3 = mysql_fetch_assoc($res3))
						{
                            if($r3['patente']!='')
                            {
	                            echo "<option value='".$r3['cod_detalle_producto']."'";
								if($_POST['cod_detalle_producto']==$r3['cod_detalle_producto'])
								echo " selected ";
								echo ">".$r3['patente']."</option>";
							}
							else
							{
							    echo "<option value='".$r3['cod_detalle_producto']."'";
								if($_POST['cod_detalle_producto']==$r3['cod_detalle_producto'])
								echo " selected ";
								echo ">".$r3['codigo_interno']."</option>";
							}
                        }
                    }
               
        	echo "</select>";
        	}



			
		}
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><label>Kilometro</label><br><input type='text' name='kilometro' value='".$_POST['kilometro']."'  class='foo' >";
		echo "</td>";
		echo "<td><label>Horometro </label><br><input type='text' name='horometro' value='".$_POST['horometro']."'  class='foo' >";
		echo "</td>";
		echo "</tr>";
	}
	
	if($_POST['tipo_salida']==2)
	{
		echo "<tr>";
		echo "<td><label>Lugares Fisicos</label><br>";
		echo "<select name='id_lugaresfisicos' class='foo' >";
		echo "<option value='0'></option>";
		$sql1="SELECT * from lugares_fisicos  WHERE  rut_empresa='".$_SESSION['empresa']."'";
		$rec1=mysql_query($sql1);
		while($row1=mysql_fetch_array($rec1))
		{
			echo "<option value='".$row1['id_lf']."' ";
			if($row1['id_lf']==$_POST['id_lugaresfisicos'])
			{
				echo " selected ";
			}
			echo">".$row1['descripcion_lf']."</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "<tr>";
	}
	?>
    <tr>
        <td colspan="3"><label>Observacion:</label><br /><input  type='text' name="observacion"  style=" border:solid 1px #06F; color:#000000;  width:800px;" value='<?=$_POST['observacion'];?>' /></td>
   </tr>
    <tr>
        <td colspan="3"><label>Observacion 1:</label><br /><input  type='text' name="observacion1"  style=" border:solid 1px #06F; color:#000000;  width:800px;" value='<?=$_POST['observacion1'];?>' /></td>
   </tr>
    <tr>
        <td colspan="3"><label>Observacion 2:</label><br /><input  type='text' name="observacion2"  style=" border:solid 1px #06F; color:#000000;  width:800px;" value='<?=$_POST['observacion2'];?>' /></td>
   </tr>   
   <tr>
       <td colspan="100%" align="center"><input type="submit" name="procesar" value="Procesar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
   </tr>
<tr>
				<td colspan="100%" style='text-align:center;tfont-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
					(*) Campos de Ingreso Obligatorio.
				</td>
                </tr>
   <?
	}
   ?>
      </table>
</form>