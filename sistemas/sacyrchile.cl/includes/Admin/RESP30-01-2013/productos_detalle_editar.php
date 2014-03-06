<?php
$error="";
$msg="";

if(isset($_GET['action'])){    
    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        
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
        
        
        
        $sql_update="UPDATE detalles_productos SET codigo_interno='".$cod_interno."',especifico=".$especifico.",
            patente='".$patente."',agno='".$anio."',color='".$color."',marca='".$marca."',
            modelo=".$modelo.",potencia_KVA=".$potencia.",horas_mensuales=".$horas_mensuales.",
            consumo_nominal=".$consumo_nominal.",consumo_mensual=".$consumo_mensual.",
            peso_bruto=".$peso_bruto.",referencia_sacyr='".$referencia."',vin_chasis='".$vin."',
            motor='".$motor."',centro_costo=".$centro_costo.",
            num_factura='".$n_factura."',fecha_factura='".date('Y-m-d',  strtotime($f_factura))."',
            num_guia_despacho=".$n_guia.",fecha_guia_despacho='".date('Y-m-d',  strtotime($f_guia))."',valor_unitario=".$valor_u.",
            observaciones='".$observaciones."',
            estado_producto=".$estado.",producto_arrendado=".$arrendado.",empresa_arriendo='".$emp_arriendo."',id_oc=".$id_oc."  
            WHERE cod_detalle_producto=".$_GET['id_det'];
        
       
        if(mysql_query($sql_update,$con)){
            $msg ="Se han actualizado correctamente los datos";
        }else{
            $msg="Ha ocurrido un error al actualizar intente mas tarde";
        }
    }
}

if(isset($_GET['id_det'])){
    $sql = "SELECT * FROM detalles_productos WHERE cod_detalle_producto=".$_GET['id_det'];
    $res = mysql_query($sql,$con);
    $row = mysql_fetch_assoc($res);
    $cod_interno=$row['codigo_interno'];
    $patente=$row['patente'];
    $anio=$row['agno'];
    $color=$row['color'];
    $marca=$row['marca'];
    $modelo=$row['modelo'];
    $vin=$row['vin_chasis'];
    $motor=$row['motor'];
    $peso_bruto=$row['peso_bruto'];
    $centro_costo=$row['centro_costo'];
    $referencia=$row['referencia_sacyr'];
    $estado=$row['estado_producto'];
    $bodega=$row['asignado_a_bodega'];
    $id_oc=$row['id_oc'];
    $n_factura=$row['num_factura'];
    $f_factura=$row['fecha_factura'];
    $n_guia=$row['num_guia_despacho'];
    $f_guia=$row['fecha_guia_despacho'];
    $valor_u=$row['valor_unitario'];
    $observaciones=$row['observaciones'];
    $emp_arriendo=$row['empresa_arriendo'];
    $arrendado=$row['producto_arrendado'];
    $especifico = $row['especifico'];
    $potencia = $row['potencia_KVA'];
    $horas_mensuales = $row['horas_mensuales'];
    $consumo_nominal = $row['consumo_nominal'];
    $consumo_mensual = $row['consumo_mensual'];
    $asignado = $row['asignado_a_bodega'];
    $cod_prod = $row['cod_producto'];
    
    
    
    
    $action="2&new=2&id_prod=".$cod_prod."&id_det=".$_GET['id_det'];
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
<form action="?cat=3&sec=9&action=<?=$action; ?>" method="POST">
    <table style="width:900px;background-color:#bbb;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
    <tr >
      <td id="titulo_tabla" style="text-align:center;" colspan="2"> </td>
        <td id="list_link" ><a href="?cat=3&sec=5&id_prod=<?=$_GET['id_prod'];?>"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al Listado Detalle de Productos"></a></td></tr>
    <tr>
        <td colspan="3">
        <table id="listado_det_prod" style="margin-top:15px; border:1px #FFF solid;width:900px;"  cellpadding="3" cellspacing="2">
                <tr><td><label>Codigo Interno:</label><input type="text" value="<?=$cod_interno;?>" name="cod_interno" id=""></td></tr>
                <tr>
                    <td><label>Patente:</label><input type="text" value="<?=$patente;?>" name="patente" id="patente"></td>
                    <td><label>Año:</label><input type="text" value="<?=$anio;?>" name="anio" id="anio"></td>
                    <td><label>Color:</label><input type="text" value="<?=$color;?>" name="color" id="color"></td>
                </tr>
                <tr>
                    <td><label>Marca:</label><input type="text" value="<?=$marca;?>" name="marca" id=""></td>
                    <td><label>Modelo:</label><input type="text" value="<?=$modelo;?>" name="modelo" id=""></td>
                    <td><label>VIN:</label><input type="text" value="<?=$vin;?>" name="vin" id=""></td>
                </tr>
                <tr>
                    <td colspan="2"><label>Motor:</label><input type="text" value="<?=$motor;?>" name="motor" id=""></td>
                    <td><label>Peso Bruto:</label><input type="text" value="<?=$peso_bruto;?>" name="peso_bruto" id=""></td>
                </tr>
                <tr>
                    <td colspan="2"><label>Especifico:(*)</label>
                        <select name="especifico">
                            <option value="1" <? if($especifico==1){ echo "SELECTED";}?>>Si</option>
                            <option value="2" <? if($especifico==2 || $especifico==0){ echo "SELECTED";}?>>No</option>
                        </select>
                    </td>
                    <td><label>Potencia KVA:</label><input type="text" value="<?=$potencia;?>" name="potencia" id=""></td>
                </tr>
                <tr>
                    <td><label>Horas Mensuales:</label><input type="text" value="<?=$horas_mensuales;?>" name="horas_mensuales" id=""></td>
                    <td><label>Consumo Nominal:</label><input type="text" value="<?=$consumo_nominal;?>" name="consumo_nominal" id=""></td>
                    <td><label>Consumo Mensual:</label><input type="text" value="<?=$consumo_mensual?>" name="consumo_mensual" id=""></td>
                </tr>
                <tr>
                    <td><label>Centro Costo:</label>
                        <select name="centro_costo">
                             <? $s = "SELECT * FROM centros_costos WHERE rut_empresa='".$_SESSION['empresa']."'";
                                  $r = mysql_query($s,$con);
                                while($rw = mysql_fetch_assoc($r)){ ?>

                            <option value='<?=$rw['Id_cc'];?>' <?if($centro_costo==$rw['Id_cc']){ echo "SELECTED";}?>><?=$rw['descripcion_cc']?></option>

                               <? } ?>

                        </select>
                    </td>
                    <td><label>Referencia Sacyr:</label><input type="text" value="<?=$referencia;?>" name="referencia" id=""></td>
                    <td><label>Estado:</label>
                        <select name="estado">
                            <option value="1" <?if($estado==1){ echo "SELECTED";}?>>Disponible</option>
                            <option value="2" <?if($estado==2 || $estado==0){ echo "SELECTED";}?>>NO Disponible</option>
                        </select>
                    </td>
                </tr>    
                <tr>
                    <td colspan="3"><label>Asignar a Bodega:</label>
              <select name="bodega" id="bodega">               
                           <? $s = "SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION['empresa']."'";
                             $r = mysql_query($s,$con);
                             while($rw = mysql_fetch_assoc($r)){
                             ?>
                 <option value='<?=$rw['cod_bodega'];?>' <?if($bodega==$rw['cod_bodega']){ echo "SELECTED";}?>><?=$rw['descripcion_bodega'];?></option>";

                     <?         } ?>
              </select>
                    </td>
                 </tr>   
                    <tr>
                        <td colspan="3">..:: Comercial ::..</td>
                    </tr>
                    <tr>
                        <td><label>ID OC:</label><input  type="text" value="<?=$id_oc;?>" name="id_oc" id=""></td>
                        <td><label>Numero Factura:</label><input  type="text" value="<?=$n_factura;?>" name="n_factura" id=""></td>
                        <td><label>Fecha Factura:</label><input  type="text" value="<?=$f_factura;?>" name="f_factura" id=""></td>
                    </tr>
                    <tr>
                        <td><label>Numero Guia Despacho:</label><input  type="text" value="<?=$n_guia;?>" name="n_guia" id=""></td>
                        <td><label>Fecha Guia Despacho:</label><input  type="text" value="<?=$f_guia;?>" name="f_guia" id=""></td>
                        <td><label>Valor Unitario:(*)</label><input  type="text" value="<?=$valor_u;?>" name="valor_u" id=""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><label>Observaciones:</label>
                            <textarea name="observaciones" id="" ><?=$observaciones;?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><label>Empresa Arriendo:</label><input  type="text" value="" name="emp_arriendo" id=""></td>
                        <td><label>Producto Arrendado:(*)</label>
                            <select name="arrendado">
                            <option value="1" <?if($arrendado==1){ echo "SELECTED";}?>>Arrendado</option>
                            <option value="2" <?if($arrendado==2 || $arrendado==0){ echo "SELECTED";}?>>NO Arrendado</option>
                            </select>
            </td>
                </tr>    
        </table>

        </td>
    </tr>
    <tr>
        <td style="text-align: right;" colspan="3"><input type="submit" value="Grabar"></td>
    </tr>
    </table>
</form>    

















       