<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>
<?php



// Validaciones
if(!empty($_POST['accion'])){

    $error=0;
    if(empty($_POST['codigo_interno']) && empty($_POST['patente'])){
        $error=1;
        $mensaje="Debe ingresar el Codigo Interno o Patente del Detalle del Activo";
    }
   /* if(empty($_POST['patente'])){
        $error=1;
        $mensaje="Debe ingresar la Patente del Detalle del Activo";
    }*/
    if(empty($_POST['especifico'])){
        $error=1;
        $mensaje="Debe ingresar el Especifico del Detalle del Activo";
    }
    if(empty($_POST['estado_producto'])){
        $error=1;
        $mensaje="Debe ingresar el Estado del Detalle del Activo";
    }
    if(empty($_POST['valor_unitario'])){
        $error=1;
        $mensaje="Debe ingresar el Valor Unitario del Detalle del Activo";
    }
    if(empty($_POST['producto_arrendado'])){
        $error=1;
        $mensaje="Debe ingresar el Producto Arrendado del Detalle del Activo";
    }
}



// Rescata los datos
if(!empty($_GET['id_det']) and empty($_POST['primera']))
{
    $sql_res = "SELECT * FROM detalles_productos ";
    $sql_res.= "WHERE rut_empresa='".$_SESSION['empresa']."' AND cod_producto='".$_GET["id_prod"]."' AND cod_detalle_producto='".$_GET["id_det"]."'";
    $res_res = mysql_query($sql_res,$con);
    $row_res = mysql_fetch_array($res_res);

    $_POST=$row_res;
    $_POST["potencia_kva"]=$row_res["potencia_KVA"];

    if(($row_res["fecha_factura"])!=0){
          $_POST["fecha_factura"] = date('Y-m-d', strtotime($row_res["fecha_factura"]));
    }else{
        $_POST["fecha_guia_despacho"] = 0;
    }
    
    if(($row_res["fecha_guia_despacho"])!=0){
          $_POST["fecha_guia_despacho"] = date('Y-m-d', strtotime($row_res["fecha_guia_despacho"]));
    }else{
        $_POST["fecha_guia_despacho"] = 0;
    }

    if(($row_res["fecha_asignacion"])!=0){
          $_POST["fecha_asignacion"] = date('d-m-Y', strtotime($row_res["fecha_asignacion"]));
    }else{
        $_POST["fecha_asignacion"] = 0;
    }

    if(($row_res["fecha_cambio_bodega"])!=0){
          $_POST["fecha_cambio_bodega"] = date('d-m-Y', strtotime($row_res["fecha_cambio_bodega"]));
    }
}
 

// save or edit Date
if(!empty($_POST['accion'])){

    if($error==0){

        if($_POST['accion']=="guardar"){
            
                $sql3 = "SELECT id_cc, descripcion_cc FROM centros_costos ";
                $sql3.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND id_cc='".$_POST["centro_costo"]."' ORDER BY descripcion_cc";
                $res3 = mysql_query($sql3,$con);
                $rw3 = mysql_fetch_assoc($res3);

                $sql4 = "SELECT cod_bodega, descripcion_bodega FROM bodegas ";
                $sql4.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND cod_bodega='".$_POST["asignado_a_bodega"]."'";
                $res4 = mysql_query($sql4,$con);
                $rw4 = mysql_fetch_assoc($res4);

                $sql = "INSERT INTO detalles_productos (cod_producto, rut_empresa, codigo_interno, especifico, ";
                $sql.= " patente, agno, color, marca, modelo ";
                $sql.= ", potencia_kva, horas_mensuales, consumo_nominal ";
                $sql.= ", consumo_mensual, peso_bruto, referencia_sacyr ";
                $sql.= ", vin_chasis, motor, centro_costo ";
                $sql.= ", asignado_a_bodega, num_factura ";
                $sql.= ", fecha_factura, num_guia_despacho, fecha_guia_despacho ";
                $sql.= ", valor_unitario, observaciones, estado_producto "; 
                $sql.= ", producto_arrendado, empresa_arriendo, id_oc ";
                $sql.= ", usuario_ingreso, fecha_ingreso, kilometro, horometro) ";
                // $sql.= ", descripcion_cc, descripcion_bodega) ";

                $sql.= " VALUES ('".$_GET['id_prod']."','".$_SESSION['empresa']."','".$_POST["codigo_interno"]."','".$_POST["especifico"]."', ";
                $sql.= "'".$_POST["patente"]."','".$_POST["agno"]."','".$_POST["color"]."','".$_POST["marca"]."','".$_POST["modelo"]."' ";
                $sql.= ", '".$_POST["potencia_kva"]."', '".$_POST["horas_mensuales"]."', '".$_POST["consumo_nominal"]."' ";
                $sql.= ", '".$_POST["consumo_mensual"]."','".$_POST["peso_bruto"]."', '".$_POST["referencia_sacyr"]."' ";
                $sql.= ", '".$_POST["vin_chasis"]."', '".$_POST["motor"]."', '".$_POST["centro_costo"]."' ";
                $sql.= ", '".$_POST["asignado_a_bodega"]."', '".$_POST["num_factura"]."' ";
                $sql.= ", '".$_POST["fecha_factura"]."','".$_POST["num_guia_despacho"]."','".$_POST["fecha_guia_despacho"]."' ";
                $sql.= ", '".$_POST["valor_unitario"]."','".$_POST["observaciones"]."','".$_POST["estado_producto"]."' ";
                $sql.= ", '".$_POST["producto_arrendado"]."','".$_POST["empresa_arriendo"]."','".$_POST["id_oc"]."' ";
                $sql.= ", '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."', '".$_POST["kilometro"]."', '".$_POST["horometro"]."') ";
                // $sql.= ", '".$rw3["descripcion_cc"]."', '".$rw4["descripcion_bodega"]."')"; 
                $consulta=mysql_query($sql,$con);
                if($consulta)   
                    $mensaje=" Ingreso Detalle de Activo Satisfactorio ";
                    $mostrar=1;

                $consulta = "SELECT MAX(cod_detalle_producto) as cod_detalle_producto FROM detalles_productos WHERE rut_empresa='".$_SESSION["empresa"]."'";
                $resultado=mysql_query($consulta);
                $fila=mysql_fetch_array($resultado);
                
                //Guardar Asignacion a Bodega
                if($_POST["asignado_a_bodega"]!=0){    
                    $sql5 = "UPDATE detalles_productos SET fecha_asignacion='".date('Y-m-d H:i:s')."' ";
                    $sql5.= "WHERE cod_detalle_producto='".$fila["cod_detalle_producto"]."'";
                    $consulta5=mysql_query($sql5,$con);
                }

                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_productos', '".$fila["cod_detalle_producto"]."', '2'";
                $sql_even.= ", 'INSERT: cod_producto=".$_GET['id_prod'].", rut_empresa=".$_SESSION['empresa'].", codigo_interno=".$_POST["codigo_interno"].", especifico=".$_POST["especifico"].", ";
                $sql_even.= " patente=".$_POST["patente"].", agno=".$_POST["agno"].", color=".$_POST["color"].", marca=".$_POST["marca"].", modelo=".$_POST["modelo"]." ";
                $sql_even.= ", potencia_kva=".$_POST["potencia_kva"].", horas_mensuales=".$_POST["horas_mensuales"].", consumo_nominal=".$_POST["consumo_nominal"]." ";
                $sql_even.= ", consumo_mensual=".$_POST["consumo_mensual"].", peso_bruto=".$_POST["peso_bruto"].", referencia_sacyr=".$_POST["referencia_sacyr"]." ";
                $sql_even.= ", vin_chasis=".$_POST["vin_chasis"].", motor=".$_POST["motor"].", centro_costo=".$_POST["centro_costo"]." ";
                $sql_even.= ", asignado_a_bodega=".$_POST["asignado_a_bodega"].", num_factura=".$_POST["num_factura"]." ";
                $sql_even.= ", fecha_factura=".$_POST["fecha_factura"].", num_guia_despacho=".$_POST["num_guia_despacho"].", fecha_guia_despacho=".$_POST["fecha_guia_despacho"]." ";
                $sql_even.= ", valor_unitario=".$_POST["valor_unitario"].", observaciones=".$_POST["observaciones"].", estado_producto=".$_POST["estado_producto"]." "; 
                $sql_even.= ", producto_arrendado=".$_POST["producto_arrendado"].", empresa_arriendo=".$_POST["empresa_arriendo"].", id_oc=".$_POST["id_oc"]." ";
                $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d H:i:s').", kilometro=".$_POST["kilometro"].", horometro=".$_POST["horometro"]."', '".$_SERVER['REMOTE_ADDR']."', 'Insert de detalle activos', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);    

        }else{

                $sql3 = "SELECT id_cc, descripcion_cc FROM centros_costos ";
                $sql3.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND id_cc='".$_POST["centro_costo"]."' ORDER BY descripcion_cc";
                $res3 = mysql_query($sql3,$con);
                $rw3 = mysql_fetch_assoc($res3);

                 //Guardar Asignacion a Bodega
                $sql6 = "SELECT asignado_a_bodega FROM detalles_productos WHERE rut_empresa='".$_SESSION["empresa"]."' AND cod_detalle_producto='".$_GET["id_det"]."' AND asignado_a_bodega!='0'";
                $res6=mysql_query($sql6,$con);
                $num6=mysql_num_rows($res6);

                if($_POST["asignado_a_bodega"]!=0 && $num6==0){    
                    $sql7 = "UPDATE detalles_productos SET fecha_asignacion='".date('Y-m-d H:i:s')."' ";
                    $sql7.= "WHERE cod_detalle_producto='".$_GET["id_det"]."'";
                    $consulta7=mysql_query($sql7,$con);

                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_productos', '".$_GET["id_det"]."', '3'";
                $sql_even.= ", 'UPDATE: ";
                $sql_even.= "fecha_asignacion=".date('Y-m-d H:i:s')."', '".$_SERVER['REMOTE_ADDR']."', 'Insert de detalle activos', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);    

                }

                $sql4 = "SELECT cod_bodega, descripcion_bodega FROM bodegas ";
                $sql4.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND cod_bodega='".$_POST["asignado_a_bodega"]."'";
                $res4 = mysql_query($sql4,$con);
                $rw4 = mysql_fetch_assoc($res4);

                $up = "UPDATE detalles_productos SET codigo_interno='".$_POST["codigo_interno"]."', ";
                $up.= "especifico='".$_POST["especifico"]."' ";
                $up.= ",patente='".$_POST["patente"]."', agno='".$_POST["agno"]."', color='".$_POST["color"]."', marca='".$_POST["marca"]."', modelo='".$_POST["modelo"]."' ";
                $up.= ",potencia_KVA='".$_POST["potencia_kva"]."', horas_mensuales='".$_POST["horas_mensuales"]."', consumo_nominal='".$_POST["consumo_nominal"]."' ";
                $up.= ",consumo_mensual='".$_POST["consumo_mensual"]."', peso_bruto='".$_POST["peso_bruto"]."', referencia_sacyr='".$_POST["referencia_sacyr"]."' ";
                $up.= ",vin_chasis='".$_POST["vin_chasis"]."', motor='".$_POST["motor"]."', centro_costo='".$_POST["centro_costo"]."' ";
                $up.= ",asignado_a_bodega='".$_POST["asignado_a_bodega"]."', num_factura='".$_POST["num_factura"]."' ";
                $up.= ",fecha_factura='".$_POST["fecha_factura"]."', num_guia_despacho='".$_POST["num_guia_despacho"]."', fecha_guia_despacho='".$_POST["fecha_guia_despacho"]."' ";
                $up.= ",valor_unitario='".$_POST["valor_unitario"]."', observaciones='".$_POST["observaciones"]."', estado_producto='".$_POST["estado_producto"]."' ";
                $up.= ",producto_arrendado='".$_POST["producto_arrendado"]."', empresa_arriendo='".$_POST["empresa_arriendo"]."', id_oc='".$_POST["id_oc"]."'";
                $up.= ",kilometro='".$_POST["kilometro"]."', horometro='".$_POST["horometro"]."' ";
                $up.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND cod_detalle_producto='".$_GET["id_det"]."' AND cod_producto='".$_GET["id_prod"]."' ";
                $consulta=mysql_query($up, $con);
                if($consulta)
                    $mensaje=" Actualización Detalle de Activo Satisfactorio ";
                    $mostrar=1;

                if($_POST["asignado_a_bodega_anterior"]!=$_POST["asignado_a_bodega"]){    
                    $sql5 = "UPDATE detalles_productos SET cod_bodega_anterior='".$_POST["asignado_a_bodega_anterior"]."', fecha_cambio_bodega='".date('Y-m-d H:i:s')."' ";
                    $sql5.= "WHERE cod_detalle_producto='".$_GET["id_det"]."'";
                    $consulta5=mysql_query($sql5,$con);
                }

                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_productos', '".$_GET["id_det"]."', '3'";
                $sql_even.= ", 'UPDATE:codigo_interno=".$_POST["codigo_interno"].", ";
                $sql_even.= "especifico=".$_POST["especifico"]." ";
                $sql_even.= ",patente=".$_POST["patente"].", agno=".$_POST["agno"].", color=".$_POST["color"].", marca=".$_POST["marca"].", modelo=".$_POST["modelo"]." ";
                $sql_even.= ",potencia_KVA=".$_POST["potencia_kva"].", horas_mensuales=".$_POST["horas_mensuales"].", consumo_nominal=".$_POST["consumo_nominal"]." ";
                $sql_even.= ",consumo_mensual=".$_POST["consumo_mensual"].", peso_bruto=".$_POST["peso_bruto"].", referencia_sacyr=".$_POST["referencia_sacyr"]." ";
                $sql_even.= ",vin_chasis=".$_POST["vin_chasis"].", motor=".$_POST["motor"].", centro_costo=".$_POST["centro_costo"]." ";
                $sql_even.= ",asignado_a_bodega=".$_POST["asignado_a_bodega"].", num_factura=".$_POST["num_factura"]." ";
                $sql_even.= ",fecha_factura=".$_POST["fecha_factura"].", num_guia_despacho=".$_POST["num_guia_despacho"].", fecha_guia_despacho=".$_POST["fecha_guia_despacho"]." ";
                $sql_even.= ",valor_unitario=".$_POST["valor_unitario"].", observaciones=".$_POST["observaciones"].", estado_producto=".$_POST["estado_producto"]." ";
                $sql_even.= ",producto_arrendado=".$_POST["producto_arrendado"].", empresa_arriendo=".$_POST["empresa_arriendo"].", id_oc=".$_POST["id_oc"]."";
                $sql_even.= ",kiometro=".$_POST["kilometro"].", horometro=".$_POST["horometro"]."', '".$_SERVER['REMOTE_ADDR']."', 'Update de detalle activos', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);      

        }

        if(!empty($_POST["asignado_a_bodega"])){
            $up_bod = "UPDATE detalles_productos SET estado_producto='2' ";
            $up_bod.= "WHERE rut_empresa='".$_SESSION["empresa"]."' AND cod_detalle_producto='".$_GET["id_det"]."' AND cod_producto='".$_GET["id_prod"]."'";
            mysql_query($up_bod, $con);

            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_productos', '".$_GET["id_det"]."', '3'";
            $sql_even.= ", 'UPDATE:estado_producto=2', '".$_SERVER['REMOTE_ADDR']."', 'Update de detalle activos', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);

        }

    }
}

$sql_ex = "SELECT descripcion FROM productos ";
$sql_ex.= "WHERE rut_empresa='".$_SESSION['empresa']."' AND cod_producto='".$_GET["id_prod"]."' ";
$res_ex = mysql_query($sql_ex,$con);
$row_ex = mysql_fetch_array($res_ex);

$numdet_ex = 0;
$sqldet_ex = "SELECT cod_producto FROM detalles_productos ";
$sqldet_ex.= "WHERE rut_empresa='".$_SESSION['empresa']."' AND cod_producto='".$_GET["id_prod"]."' ";
$resdet_ex = mysql_query($sqldet_ex,$con);
$numdet_ex = mysql_num_rows($resdet_ex);
$numdet_ex++;

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

 <style>
    .foo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:11px;
        font-family:Tahoma, Geneva, sans-serif;
        text-align:center;
    }

    </style>

<!-- producto detalle new 2 -->

<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4" >
    <tr>
        <!-- Boton para volver a pantalla principal -->
        <td id="list_link" style="" colspan=6" > 
<?
        if($mostrar==1 && $_GET["action"]==1){
?>
            <a  href="?cat=3&sec=6&id_prod=<?=$_GET['id_prod'];?>&action=1">
                    <img src="img/add1.png" width="30px" height="30px" border="0" class="toolTIP" title="Agregar Nuevo Detalle de Activo">
            </a>
<?
        }
?>
            <a href="?cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>">
                <img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;"  class="toolTIP" title="Volver al Listado de Detalle de Activos">
            </a>
        </td>
    </tr>
</table>

<?php
    if($mostrar==0){
?>

<form action="?cat=3&sec=6&id_prod=<?=$_GET['id_prod'];?>&id_det=<?=$_GET['id_det'];?>&action=<?=$_GET["action"]; ?>&num_lin=<?=$_GET["num_lin"];?>" method="POST">

    <table id="detalle-prov"  style="margin-top:15px; border:3px #FFF solid;width:900px;"  cellpadding="3" cellspacing="4" border="0">
        <tr>
           <td colspan="3" id="titulo_tabla" style="text-align:center;"><?if($_GET["action"]==1){?> Detalle Nº <?=$numdet_ex;?> del Activo <?=$row_ex["descripcion"];?> <?}else{?>  Detalle Nº <?=$_GET["num_lin"];?> del Activo <?=$row_ex["descripcion"];?> <?}?> </td>
        </tr>
        
        <tr>
            <td colspan="2" style="text-align: center;"><label>Identificador del Activo</label><label style="color:red;">(*)</label></td>
            <td></td>
        </tr>        
    
        <tr>
            <td><label>Codigo Interno:</label><br>
                <input style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'  type="text" name="codigo_interno" value="<?=$_POST["codigo_interno"]; ?>" <?/* if($_GET['action']==2) echo 'readonly';*/?> >
            </td>
            <td><label>Patente:</label><br>
                <input type="text"  name="patente" value="<?=$_POST["patente"];?>" style="border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%">
            </td>
            <td><label>Referencia Sacyr:</label><br>
                <input type="text" name="referencia_sacyr" value="<?=$_POST["referencia_sacyr"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td> 
        </tr>
        <tr>
            <td><label>Estado:</label><label style="color:red;">(*)</label><br/>
                <select name="estado_producto" class="foo">
                    <option value="0" <? if($_POST['estado_producto']==0) echo " selected "; ?>>---</option>
                    <option value="1" <? if($_POST['estado_producto']==1) echo " selected "; ?>>Disponible</option>
                    <option value="2" <? if($_POST['estado_producto']==2) echo " selected "; ?>>Asignado</option>
                    <option value="3" <? if($_POST['estado_producto']==3) echo " selected "; ?>>Dado de Baja</option>
                    <option value="4" <? if($_POST['estado_producto']==4) echo " selected "; ?>>En Mantenci&oacute;n</option>
                    <option value="5" <? if($_POST['estado_producto']==5) echo " selected "; ?>>En Reparaci&oacute;n</option>
                </select>
            </td>
            <td><label>Valor Unitario:</label><label style="color:red;">(*)</label>
                <input   type="text" name="valor_unitario"  value="<?=$_POST["valor_unitario"];?>" onKeyPress='ValidaSoloNumeros()' style="border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%">
            </td>
            <td><label>Especifico:</label><label style="color:red;">(*)</label><br/>
                <select name="especifico" class="foo" style="width:50px">
                    <option value="0" <? if($_POST['especifico']==0) echo " selected "; ?>>---</option>
                    <option value="1" <? if($_POST['especifico']==1) echo " selected "; ?>>Si</option>
                    <option value="2" <? if($_POST['especifico']==2) echo " selected "; ?>>No</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Producto Arrendado:</label><label style="color:red;">(*)</label><br/>
                <select name="producto_arrendado" class="foo">
                    <option value="0" <? if($_POST['producto_arrendado']==0) echo " selected "; ?>>---</option>
                    <option value="1" <? if($_POST['producto_arrendado']==1) echo " selected "; ?>>Arrendado</option>
                    <option value="2" <? if($_POST['producto_arrendado']==2) echo " selected "; ?>>NO Arrendado</option>
                </select>
            </td>
            <td colspan="2"><label>Empresa Arriendo:</label><br>
                <input type="text" name="empresa_arriendo" value="<?=$_POST["empresa_arriendo"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:535px'>
            </td>
        </tr>
        <tr>
            <td colspan="3" id="titulo_tabla" style="text-align:center;" >..:: Especificaciones ::..</td>
        </tr>
        <tr>
            <td><label>Año:</label><br/>
                <input type="text"  size="4" name="agno" value="<?=$_POST["agno"];?>" onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:40%'>
            </td>
            <td><label>Color:</label><br>
                <input type="text" name="color" value="<?=$_POST["color"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
        </tr>
        <tr>
            <td><label>Marca:</label><br>
                <input type="text" name="marca" value="<?=$_POST["marca"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Modelo:</label><br>
                <input type="text" name="modelo" value="<?=$_POST["modelo"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>VIN:</label><br>
                <input type="text" name="vin_chasis" value="<?=$_POST["vin_chasis"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
        </tr>
        <tr>
            <td colspan="2"><label>Motor:</label><br>
                <input type="text"  name="motor" value="<?=$_POST["motor"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Peso Bruto:</label><br>
                <input type="text" name="peso_bruto" value="<?=$_POST["peso_bruto"];?>"  style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
        </tr>
        <tr>
            <td>
                <label>Potencia KVA:</label><br>
                <input type="text" name="potencia_kva" value="<?=$_POST["potencia_kva"];?>" style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td>
                <label>Kilometro:</label><br>
                <input type="text" name="kilometro" value="<?=$_POST["kilometro"];?>"  style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td>
                <label>horometro:</label><br>
                <input type="text" name="horometro" value="<?=$_POST["horometro"];?>"  style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
        </tr>
        <tr>
            <td colspan="3" id="titulo_tabla" style="text-align:center;" >..:: Consumo ::..</td>
        </tr>
        <tr>
            <td><label>Horas Mensuales:</label><br>
                <input type="text" name="horas_mensuales" value="<?=$_POST["horas_mensuales"];?>" onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Consumo Nominal:</label><br>
                <input type="text" name="consumo_nominal" value="<?=$_POST["consumo_nominal"];?>" onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Consumo Mensual:</label><br>
                <input type="text" name="consumo_mensual" value="<?=$_POST["consumo_mensual"];?>" onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
        </tr>
        <tr>
            <td colspan="3" id="titulo_tabla" style="text-align:center;" >..:: Asignación ::..</td>
        </tr>

        <tr>
            <td><label>Centro Costo:</label><br/>
                <select name="centro_costo" class="foo">
                    <?
                    $sql1 = "SELECT id_cc, descripcion_cc FROM centros_costos WHERE rut_empresa='".$_SESSION["empresa"]."' ORDER BY descripcion_cc";
                    $res1 = mysql_query($sql1,$con);
                    ?>
                        <option value='0' <? if (isset($_POST["centro_costo"]) == 0) echo 'selected'; ?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option> 
                    <?
                    while($rw1 = mysql_fetch_assoc($res1)){
                        ?>
                        <option value='<? echo $rw1["id_cc"];?>' <? if ($rw1["id_cc"] == $_POST['centro_costo']) echo "selected"; ?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><? echo $rw1["descripcion_cc"];?></option>
                        <?
                    }
                    ?>
                </select>
               
            </td>

            
            <td><label>Asignar a Bodega:</label>
                <select name="asignado_a_bodega" class="foo">
                    <?            
                    $sql2 = "SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION["empresa"]."' ORDER BY descripcion_bodega";
                    $res2 = mysql_query($sql2,$con);
                    ?>
                    <option value='0' <? if (isset($_POST["asignado_a_bodega"]) == '0') echo 'selected'; ?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option> 
                    <?
                    while($rw2 = mysql_fetch_assoc($res2)){
                        ?>
                        <option value='<? echo $rw2["cod_bodega"]; ?>' <? if ($rw2["cod_bodega"] == $_POST['asignado_a_bodega']) echo "selected"; ?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $rw2["descripcion_bodega"];?></option>
                        <?
                    }
                    ?>                 
                </select>
                <input type=hidden name='asignado_a_bodega_anterior' value='<?=$_POST["asignado_a_bodega"];?>' />
            </td>
            
            <?if($_POST["asignado_a_bodega"]!=0){?>
                <td><label>Fecha de Asignación:</label><br>
                       <span style='font-size:12px;font-family:Tahoma, Geneva, sans-serif;'> <? echo $_POST['fecha_asignacion']; ?></span>
                </td>
            <?}?>

        </tr>    

            <?
                if(!empty($_POST["cod_bodega_anterior"])){
                    $sql3 = "SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION["empresa"]."' AND cod_bodega='".$_POST["cod_bodega_anterior"]."'";
                    $res3 = mysql_query($sql3,$con);
                    $row3 = mysql_fetch_assoc($res3);
            ?>
        <tr>
                <td><label>Bodega Anterior:</label><br>
                       <span style='font-size:12px;font-family:Tahoma, Geneva, sans-serif;'> <? echo $row3['descripcion_bodega']; ?></span>
                </td>
                <td><label>Fecha de Cambio Bodega:</label><br>
                       <span style='font-size:12px;font-family:Tahoma, Geneva, sans-serif;'> <? echo $_POST['fecha_cambio_bodega']; ?></span>
                </td>
        </tr>   
            <?}?>
        <tr>
            <td colspan="3" id="titulo_tabla" style="text-align:center;" >..:: Comercial ::..</td>
        </tr>
        <tr>
            <td><label>Nº Orden de Compra:</label><br>
                <input type="text"  name="id_oc" value="<?=$_POST["id_oc"];?>"  onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Numero Factura:</label><br>
                <input  type="text"  name="num_factura" value="<?=$_POST["num_factura"];?>" onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Fecha Factura:</label><br>
                <input type="date" name="fecha_factura" value='<? echo $_POST['fecha_factura']; ?>'  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" />
            </td>
        </tr>
        <tr>
            <td><label>Numero Guia Despacho:</label>
                <input type="text"  name="num_guia_despacho" value="<?=$_POST["num_guia_despacho"];?>" onKeyPress='ValidaSoloNumeros()' style='border:1px solid #09F;background-color:#FFFFFF;font-size:11px;font-family:Tahoma, Geneva, sans-serif;width:70%'>
            </td>
            <td><label>Fecha Guia Despacho:</label>
                <br>
                <input type="date" name="fecha_guia_despacho" value='<? echo $_POST['fecha_guia_despacho']; ?>'  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" />
            </td>
        </tr>
        <tr>
            <td colspan="3"><label>Observaciones:</label><br>
                <textarea name="observaciones" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones"];?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <div style="width:100%; height:auto; text-align:right;">
                    <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                    <?

                    if($_GET["action"]==1)
                    {
                        echo  " value='guardar' >Guardar</button><input name='bandera_guardar' type='hidden' value='1'>";
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
}
  //var_dump($_POST);
?>


<?
function conv_ts($fecha_dd_mm_aaaa) {
    if (empty($fecha_dd_mm_aaaa)) $fecha_dd_mm_aaaa = time();
    list($d, $m, $a) = explode("-",$fecha_dd_mm_aaaa);
    return mktime(0, 0, 0, $m, $d, $a);
}


?>