<script>
function validar(codbar_productonew,rut)
{	
	result=$("#resultado");
	result.html("cargando...")
	$.get("buscador.php",{ codbar_productonew:codbar_productonew ,rut:rut})
	.success(function(data){ result.html(data)})
	.error(function(a,e){ result.html(e)});
}

</script>
<?

// validaciones
$mostrar=0;
if(!empty($_POST['accion'])){

    $error=0;
    if(empty($_POST['fecha_pi'])){
    	$error=1;
        $mensaje="Debes ingresar Fecha del Pre-Inventario";
	}
    if($_GET["action"]==2){
    	if(empty($_POST['estado_pi'])){
        	$error=1;
            $mensaje="Debes ingresar Estado del Pre-Inventario";
    	}
    }
    if(empty($_POST['descripcion_pi'])){
    	$error=1;
        $mensaje="Debes ingresar Descripcion del Pre-Inventario";
	}
}	

// Rescata los datos 
if(!empty($_GET['id_pi']) and empty($_POST['primera']))
{
    $sql = "SELECT * FROM preinventario WHERE id_pi='".$_GET["id_pi"]."' AND rut_empresa='".$_SESSION['empresa']."'";
    $rec = mysql_query($sql);
    $row1 = mysql_fetch_array($rec);
    $_POST = $row1;
    if(($row1["fecha_pi"])!=0){
          $_POST["fecha_pi"] = date('Y-m-d', strtotime($row1["fecha_pi"]));
    }else{
        $_POST["fecha_pi"] = 0;
    }
}

if($_GET["action"]==1){
    $_POST["estado_pi"] = 1;
    $disabled = "disabled='true'"; 
}

//Discrimina si guarda o edita
if(!empty($_POST['accion'])){

        if($error==0){

            if($_POST['accion']=="guardar"){


    			$sql_ins = "INSERT INTO preinventario (rut_empresa, fecha_pi, descripcion_pi, estado_pi, usuario_ingreso, fecha_ingreso) ";
    			$sql_ins.= " VALUES ('".$_SESSION["empresa"]."', '".$_POST["fecha_pi"]."', '".$_POST["descripcion_pi"]."', '1' ";
    			$sql_ins.= ", '".$_SESSION["user"]."' , '".date('Y-m-d H:i:s')."' )";	
				$consulta = mysql_query($sql_ins,$con);
			    if($consulta)
			    	$mensaje=" Ingreso de Pre-Inventario Exitoso ";
			    	$mostrar=1;

                $consulta = "SELECT MAX(id_pi) as id_pi FROM preinventario WHERE rut_empresa='".$_SESSION["empresa"]."'";
                $resultado=mysql_query($consulta);
                $fila=mysql_fetch_assoc($resultado);
                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'preinventario', '".$fila["id_pi"]."', '2'";
                $sql_even.= ", 'INSERT: rut_empresa=".$_SESSION["empresa"].", fecha_pi=".$_POST["fecha_pi"].", descripcion_pi=".$_POST["descripcion_pi"]."";
                $sql_even.= ", estado_pi=".$_POST["estado_pi"].", usuario_ingreso=".$_SESSION["user"].", fecha_ingreso=".date('Y-m-d H:i:s')."', ";
                $sql_even.= "'".$_SERVER['REMOTE_ADDR']."', 'Insert en preinventario', '1', '".date('Y-m-d H:i:s')."') ";
                mysql_query($sql_even, $con);  

    		}else{
                $sql_up = "UPDATE preinventario SET ";
                $sql_up.= "fecha_pi='".$_POST["fecha_pi"]."',  descripcion_pi='".$_POST["descripcion_pi"]."', estado_pi='".$_POST["estado_pi"]."' ";
                $sql_up.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND id_pi='".$_GET["id_pi"]."'";
                $consulta=mysql_query($sql_up, $con);
                if($consulta)
                    $mensaje=" Actualización de Pre-Inventario Exitoso ";
                    $mostrar=1;


                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'preinventario', '".$_GET["id_pi"]."', '3'";
                $sql_even.= ", 'UPDATE: fecha_pi=".$_POST["fecha_pi"].",  descripcion_pi=".$_POST["descripcion_pi"].", estado_pi=".$_POST["estado_pi"]."', '".$_SERVER['REMOTE_ADDR']."', 'Update en preinventario', '1', '".date('Y-m-d H:i:s')."') ";
                mysql_query($sql_even, $con); 
    		}
    	}
}	


//Manejo de errores
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


<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">   <a href="?cat=3&sec=37"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Pre-Inventario"></a></td></tr>
    
    <tr>
</table>

    <style>
    a:hover{
        text-decoration:none;
    }

    .fo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:12px;
        font-family:Tahoma, Geneva, sans-serif;
        width:80%;
        text-align:center;
    }
</style>   

<?php
    if($mostrar==0){
?>

<form action="?cat=3&sec=38&action=<?=$_GET["action"]; ?>&id_pi=<?=$_GET["id_pi"];?>" method="POST" id="f1" name="f1">
	<div id='resultado' style="display:none">
    </div>
	<table border="0" style="width:80%;border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif; ">
     		<!-- <tr>
            <td>
            <label>Codigo:</label><label style="color:red;">(*)</label><br>
			<input type="text" name="codbar_productonew" value='<? /*echo $_POST['codbar_productonew']; */?>' onchange="validar(document.f1.codbar_productonew.value,'<? /*echo $_SESSION["empresa"];*/?>')"  />
            </td>    
            </tr> -->
            <tr> 
				<td>
       				<label>Fecha:</label><label style="color:red;">(*)</label><br>
					<input type="date" name="fecha_pi" value='<? echo $_POST['fecha_pi']; ?>'  <?/*if($_GET["action"]==2) echo 'disabled="true"';*/?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" />
				</td>
	 			<td style="font-family:Tahoma; font-size:12px;text-align:left;"><label>Estado:</label><label style="color:red;">(*)</label><br>

                    <select name="estado_pi"  class='fo' style="width:150px;" <?=$disabled;?> >
                        <option value="0" <? if($_POST['estado_pi']==0) echo " selected "; ?> >---</option>  
                        <option value="1" <? if($_POST['estado_pi']==1){ echo " selected ";} ?> >Abierto</option>
                        <option value="2" <? if($_POST['estado_pi']==2){ echo " selected ";} ?> >En Proceso</option>
                        <option value="3" <? if($_POST['estado_pi']==3){ echo " selected ";} ?> >Finalizado</option>
                    </select>
	            </td>
        </tr>
        <tr>
       			<td colspan="2"><label>Descripcion:</label><label style="color:red;">(*)</label><br />
	       		<textarea cols="110" rows="2" name="descripcion_pi" style="width:800px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["descripcion_pi"];?></textarea>
       	</tr>
       	<tr>
	        <td  colspan="3" style="text-align: right;">
	            <div style="width:100%; height:auto; text-align:right;">
	                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
	                        <?

	                        if($_GET["action"]==1)
	                        {
	                            echo  " value='guardar' >Guardar</button>";
	                        }
	                        if($_GET["action"]==2)
	                        {
	                            echo " value='editar' >Actualizar</button>";
	                        }
	                        ?>

	                    </div>
	                    <input  type="hidden" name="primera" value="1"/>


	        </td>
    	</tr>
    	<tr>
                <td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                  (*) Campos de Ingreso Obligatorio.
            </td>
    </tr>
 	</table>
 </form>

<?php
  // var_dump($_POST);
}
?>