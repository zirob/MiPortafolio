<?php
$action="";
$sql="";
$msg="";
$error="";
if(isset($_GET['action'])){    
    $cod_producto = $_POST['codigo_producto'];
    $cant_item=$_POST['cant_item'];
    $id_prod = $cod_producto;
    
    
    $cod_interno=$_POST['cod_interno'];
    $patente=$_POST['patente'];
    $anio=$_POST['anio'];
    $color=$_POST['color'];
    $marca=$_POST['marca'];
    $modelo=$_POST['modelo'];
    $vin=$_POST['vin'];
    $motor=$_POST['motor'];
    $peso_bruto=$_POST['peso_bruto'];
    $centro_costo=$_POST['centro_costo'];
    $referencia=$_POST['referencia'];
    $estado=$_POST['estado'];
    $bodega=$_POST['bodega'];
    $id_oc=$_POST['id_oc'];
    $n_factura=$_POST['n_factura'];
    $f_factura=$_POST['f_factura'];
    $n_guia=$_POST['n_guia'];
    $f_guia=$_POST['f_guia'];
    $valor_u=$_POST['valor_u'];
    $observaciones=$_POST['observaciones'];
    $emp_arriendo=$_POST['emp_arriendo'];
    $arrendado=$_POST['arrendado'];
    $especifico = $_POST['especifico'];
    $potencia = $_POST['potencia'];
    $horas_mensuales = $_POST['horas_mensuales'];
    $consumo_nominal = $_POST['consumo_nominal'];
    $consumo_mensual = $_POST['consumo_mensual'];
    $asignado = $_POST['bodega'];
    
    if($_GET['action']==1 && isset($_GET['new']) && $_GET['new']==1){
        $x = 0;
        for($i=0;$i<$cant_item;$i++){
                $x++;
                
                
                if(isset($_POST['estado']) && $_POST['estado']!="" && $_POST['estado']!=0){
                    if(isset($_POST['valor_u']) && $_POST['valor_u']!="" && $_POST['valor_u']!=0){
                            if(isset($_POST['arrendado']) && $_POST['arrendado']!="" && $_POST['arrendado']!=0){
                                $sql = "INSERT INTO detalles_productos (cod_producto, rut_empresa, codigo_interno, 
                                        especifico, patente, agno, color, marca, modelo, potencia_KVA, 
                                        horas_mensuales, consumo_nominal, consumo_mensual, peso_bruto,
                                        referencia_sacyr, vin_chasis, motor, centro_costo, asignado_a_bodega,
                                        fecha_asignacion, num_factura, fecha_factura, num_guia_despacho, 
                                        fecha_guia_despacho, valor_unitario, observaciones,estado_producto, 
                                        producto_arrendado,empresa_arriendo, id_oc, usuario_ingreso,fecha_ingreso) 
                                        VALUES ('".$cod_producto."','".$_SESSION['empresa']."','".$cod_interno[$i]."','".$especifico[$i]."',
                                        '".$patente[$i]."','".$anio[$i]."','".$color[$i]."','".$marca[$i]."','".$modelo[$i]."',
                                        '".$potencia[$i]."','".$horas_mensuales[$i]."','".$consumo_nominal[$i]."',
                                        '".$consumo_mensual[$i]."','".$peso_bruto[$i]."','".$referencia[$i]."',
                                        '".$vin[$i]."','".$motor[$i]."','".$centro_costo[$i]."',
                                        '".$asignado[$i]."','".date('Y-m-d H:i:s')."','".$n_factura[$i]."',
                                        '".$f_factura[$i]."','".$n_guia[$i]."','".$f_guia[$i]."',
                                        '".$valor_u[$i]."','".$observaciones[$i]."','".$estado[$i]."',
                                        '".$arrendado[$i]."','".$emp_arriendo[$i]."','".$id_oc[$i]."',
                                        '".$_SESSION['user']."','".date('Y-m-d H:i:s')."')";
            
                                if(mysql_query($sql,$con)){
                                    $msg="Se han agregado correctamente los Items";
                                }else{
                                    $error="Debe Ingresar los datos solicitados";
                                }

                                
                            }else{
                                $error="Debe Seleccionar si el Producto es arrendado";
                            }
                    }else{
                        $error="Debe Ingresar el Valor Unitario";
                    }
                }else{
                    $error="Debe Seleccionar un Estado";
                }
            
            
        }
        
        
         if($x==$cant_item){
                $msg="Se han agregado correctamente los Items";
            }else{
                $error="Debe ingresar los datos correctamente";
            }
        
        
    }else{ 
       $action="1&new=1&id_prod=".$cod_producto;
       
    } 
    
    
    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        $sql_update="";
        if(mysql_query($sql_update,$con)){
            $msg ="Se han actualizado correctamente los datos";
        }else{
            $msg="Ha ocurrido un error al actualizar intente mas tarde";
        }
    }
    
    if($_GET['action']==2 && isset($_GET['id_prod'])){
        
         $action="2&new=2&id_prod=".$_GET['id_prod'];
    }
    
}else{    
$id_prod = $_GET['id_prod'];
$action="1&new=1&id_prod=".$id_prod;
}


    if(isset($error) && !empty($error)){
        ?>
<div id="main-error"><? echo $error;?></div>
<?
    }elseif($msg){
?>
<div id="main-ok"><? echo $msg;?></div>
<?
    }
?>
<form action="?cat=3&sec=6&action=<?=$action; ?>" method="POST" onSubmit="valida_detalle_prod();">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">  <a href="?cat=3&sec=5&id_prod=<?=$id_prod;?>"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado Detalle Productos"></a></td></tr>
    <tr>
        <td colspan="2">
            <label>Cod. Producto</label><input class="fu" type="text" name="codigo_producto" id="codigo_producto" value="<?=$id_prod;?>" size="12">
            &nbsp;<label>Cant. Items</label><input class="fu" type="text" class="nume" name="cant_item" id="cant_item" size="3" value="" onchange="nuevos_items(this.value,2,'<?=$_SESSION['empresa'];?>');" >
        </td>
    </tr>
    <tr>
        <td colspan="2" id="items-nuevos">
            
        </td>
    </tr>
    
    <tr>
        <td  colspan="2" style="text-align: right;" id="btn_submit"><input type="submit" value="Grabar"></td>
    </tr>
    </table>

</form>