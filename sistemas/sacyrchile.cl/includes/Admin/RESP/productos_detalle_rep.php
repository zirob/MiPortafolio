<?php

$sql = "SELECT p.cod_producto, p.tipo_producto, p.descripcion, p.activo_fijo, p.critico, p.pasillo, p.casillero, p.observaciones as prod_obs,
        d.cod_detalle_producto, d.codigo_interno, d.especifico, d.patente, d.agno, d.color, d.marca, d.modelo, d.potencia_KVA, d.horas_mensuales,
        d.consumo_nominal, d.consumo_mensual, d.peso_bruto, d.referencia_sacyr, d.vin_chasis, d.motor, d.centro_costo, d.asignado_a_bodega, 
        d.num_factura, d.fecha_factura, d.num_guia_despacho, d.fecha_guia_despacho, d.valor_unitario, d.observaciones,d.estado_producto, 
        d.producto_arrendado, d.empresa_arriendo, d.id_oc  FROM productos p INNER JOIN detalles_productos d on p.cod_producto = d.cod_producto 
        WHERE d.cod_detalle_producto=".$_GET['id_detalle'];
$res = mysql_query($sql,$con);
$row = mysql_fetch_assoc($res);



$cod_producto=$row['cod_producto'];
$tipo_producto=$row['tipo_producto'];


 switch($row['tipo_producto']){
                    case 1:  $tipo_producto="Maquinarias y Equipos"; break;
                    case 2:  $tipo_producto="Vehiculo Menor"; break;
                    case 3:  $tipo_producto="Herramientas"; break;
                    case 4:  $tipo_producto="Muebles"; break;
                    case 5:  $tipo_producto="Generador"; break;
                    case 6:  $tipo_producto="Plantas"; break;
                    case 7:  $tipo_producto="Equipos de Tunel"; break;
                    case 8:  $tipo="Otros"; break;
                }

$equipo="";
$descripcion=$row['descripcion'];
$activo_fijo2=$row['activo_fijo'];
if($activo_fijo2==1){$activo_fijo="Si";}else{$activo_fijo="No";}
$critico2=$row['critico'];
if($critico2==1){$critico="Si";}else{$critico="No";}

$observaciones=$row['prod_obs'];
$idd="";
$pasillo=$row['pasillo'];
$casillero=$row['casillero'];
$cod_interno=$row['codigo_interno'];
$patente=$row['patente'];
$anio=$row['agno'];
$color=$row['color'];
$marca=$row['marca'];
$modelo=$row['modelo'];
$vin=$row['vin_chasis'];
$motor=$row['motor'];
$peso_bruto=$row['peso_bruto'];
$especifico=$row['especifico'];
$kva=$row['potencia_KVA'];
$potencia=$row['potencia_KVA'];
$horas_mensuales=$row['horas_mensuales'];
$consumo_nominal =$row['consumo_nominal'];
$consumo_mensual =$row['consumo_mensual'];
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
$arrendado=$row['producto_arrendado'];
$id_detalle = $_GET['id_detalle'];


?>
<div id="imprime">
<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">   <a href="?cat=4&sec=4">..:Datos del Producto:..<img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Reporte de Productos"></a></td></tr>
    
    <tr>
        <td ><label>Codigo:</label><br/><input class="fu" type="text" name="cod_producto" size="20" value="<?=$cod_producto;?>" readonly></td>
        <td><label>Tipo Producto:</label><br/><input class="fu" type="text" name="cod_producto" size="20" value="<?=$tipo_producto;?>" readonly></td>
    </tr>
    <tr>
        <td colspan="2"><label>Descripcion:</label><br /><textarea cols="110" rows="2" name="descripcion" disabled"><?=$descripcion;?></textarea></td>
    </tr>
    <tr>
        <td><label>Activo Fijo:</label><br/><input class="fu" type="text" name="activo_fijo" size="20" value="<?=$activo_fijo;?>" readonly></td>  
         <td><label>Critico:</label><br/><input class="fu" type="text" name="critico" size="20" value="<?=$critico;?>" readonly></td>
    </tr>
    <tr>
        <td><label>Pasillo:</label><br/><input class="fu" type="text" name="pasillo" size="3" value="<?=$pasillo;?>" readonly></td>
        <td><label>Casillero:</label><br/><input class="fu" type="text" name="casillero" size="3" value="<?=$casillero;?>" readonly></td>
    </tr>
    <tr>
        <td colspan="2"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones"><?=$observaciones;?></textarea></td>
    </tr>
     <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">  Detalle del Producto </td></tr>
    
    <tr>
</table>
 <table style="width:900px;background-color:#bbb;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
    <tr><td colspan="3">
        <table id="listado_det_prod" style="margin-top:15px; border:1px #FFF solid;width:900px;"  cellpadding="3" cellspacing="2">
                <tr><td><label>Codigo Interno:</label><input type="text" value="<?=$cod_interno;?>" name="cod_interno" readonly></td></tr>
                <tr>
                    <td><label>Patente:</label><input type="text" value="<?=$patente;?>" name="patente" id="patente"readonly></td>
                    <td><label>Año:</label><input type="text" value="<?=$anio;?>" name="anio" id="anio"readonly></td>
                    <td><label>Color:</label><input type="text" value="<?=$color;?>" name="color" id="color"readonly></td>
                </tr>
                <tr>
                    <td><label>Marca:</label><input type="text" value="<?=$marca;?>" name="marca" id=""readonly></td>
                    <td><label>Modelo:</label><input type="text" value="<?=$modelo;?>" name="modelo" id=""readonly></td>
                    <td><label>VIN:</label><input type="text" value="<?=$vin;?>" name="vin" id=""readonly></td>
                </tr>
                <tr>
                    <td colspan="2"><label>Motor:</label><input type="text" value="<?=$motor;?>" name="motor" id="" readonly></td>
                    <td><label>Peso Bruto:</label><input type="text" value="<?=$peso_bruto;?>" name="peso_bruto" id="" readonly></td>
                </tr>
                <tr>
                    <td colspan="2"><label>Especifico:(*)</label>
                        <select name="especifico" disabled>
                            <option value="1" <? if($especifico==1){ echo "SELECTED";}?>>Si</option>
                            <option value="2" <? if($especifico==2 || $especifico==0){ echo "SELECTED";}?>>No</option>
                        </select>
                    </td>
                    <td><label>Potencia KVA:</label><input type="text" value="<?=$potencia;?>" name="potencia" id=""></td>
                </tr>
                <tr>
                    <td><label>Horas Mensuales:</label><input type="text" value="<?=$horas_mensuales;?>" name="horas_mensuales" id="" readonly></td>
                    <td><label>Consumo Nominal:</label><input type="text" value="<?=$consumo_nominal;?>" name="consumo_nominal" id="" readonly></td>
                    <td><label>Consumo Mensual:</label><input type="text" value="<?=$consumo_mensual?>" name="consumo_mensual" id=""readonly></td>
                </tr>
                <tr>
                    <td><label>Centro Costo:</label>
                        <select name="centro_costo"disabled>
                             <? $s = "SELECT * FROM centros_costos WHERE rut_empresa='".$_SESSION['empresa']."'";
                                  $r = mysql_query($s,$con);
                                while($rw = mysql_fetch_assoc($r)){ ?>

                            <option value='<?=$rw['Id_cc'];?>' <?if($centro_costo==$rw['Id_cc']){ echo "SELECTED";}?>><?=$rw['descripcion_cc']?></option>

                               <? } ?>

                        </select>
                    </td>
                    <td><label>Referencia Sacyr:</label><input type="text" value="<?=$referencia;?>" name="referencia" id="" readonly></td>
                    <td><label>Estado:</label>
                        <select name="estado" disabled>
                            <option value="1" <?if($estado==1){ echo "SELECTED";}?>>Disponible</option>
                            <option value="2" <?if($estado==2 || $estado==0){ echo "SELECTED";}?>>NO Disponible</option>
                        </select>
                    </td>
                </tr>    
                <tr>
                    <td colspan="3"><label>Asignar a Bodega:</label>
              <select name="bodega" id="bodega" disabled>               
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
                        <td><label>ID OC:</label><input  type="text" value="<?=$id_oc;?>" name="id_oc" id="" readonly></td>
                        <td><label>Numero Factura:</label><input  type="text" value="<?=$n_factura;?>" name="n_factura" id="" readonly></td>
                        <td><label>Fecha Factura:</label><input  type="text" value="<? if($f_factura!="0000-00-00 00:00:00"){echo date('dd-mm-YYYY',  strtotime($f_factura));}else{echo " ";}?>" name="f_factura" id="" readonly></td>
                    </tr>
                    <tr>
                        <td><label>Numero Guia Despacho:</label><input  type="text" value="<?=$n_guia;?>" name="n_guia" id="" readonly></td>
                        <td><label>Fecha Guia Despacho:</label><input  type="text" value="<? if($f_guia!="0000-00-00 00:00:00"){echo date('dd-mm-YYYY',  strtotime($f_guia));}else{echo " ";}?>" name="f_guia" id="" readonly></td>
                        <td><label>Valor Unitario:(*)</label><input  type="text" value="<?=$valor_u;?>" name="valor_u" id="" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="3"><label>Observaciones:</label>
                            <textarea name="observaciones" cols="100" rows="5" id="" disabled><?=$observaciones;?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><label>Empresa Arriendo:</label><input  type="text" value="" name="emp_arriendo" id="" readonly></td>
                        <td><label>Producto Arrendado:(*)</label>
                            <select name="arrendado" disabled>
                            <option value="1" <?if($arrendado==1){ echo "SELECTED";}?>>Arrendado</option>
                            <option value="2" <?if($arrendado==2 || $arrendado==0){ echo "SELECTED";}?>>NO Arrendado</option>
                            </select>
            </td>
                </tr>    
        </table>

        </td>
    </tr>
    </table>
</div>
<div id="print"><img src="img/printer_ok.png"></div>
<script type="text/javascript">
$("#print").click(function (){
$("div#imprime").printArea();
})
</script>