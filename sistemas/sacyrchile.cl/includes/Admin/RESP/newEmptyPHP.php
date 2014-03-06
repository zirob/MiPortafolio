<?
 $id_solic_oc ="";
    $concepto = "";
    $solicitante = "";
    $centro_costo = "";
    $moneda = "";
    $subtotal = "";
    $descuento="";
    $valor_neto="";
    $iva = "";
    $total="";
    $forma_de_pago="";
    $estado = "";
    $observacion="";
    $dia="";$mes="";$anio="";
    $proveedor="";

    $vb_dc = "";
    $vb_n_dc = "";
    $vb_jc = "";
    $vb_n_jc = "";
    $vb_j_adm = "";
    $vb_nj_adm = "";
    $vb_j_pm = "";
    $vb_nj_pm = "";
    
    
    $plan_calidad=1;
    $prop_econ=0;
    $esp_tecnica=0;
    
if($action==1){
    $s = "SELECT max(id_oc) as ultimo FROM cabecera_oc LIMIT 1";
    $r = mysql_query($s,$con);
    $rw = mysql_fetch_assoc($r);
    $num_oc = (int)$rw['ultimo']+1;
    
    $sql_emp = "SELECT * FROM empresa WHERE rut_empresa='".$_SESSION['empresa']."'";
    $res_emp = mysql_query($sql_emp,$con);
    $row_emp = mysql_fetch_assoc($res_emp);
    
    
    
}
?>
<table border="2" cellspacing="3" cellpadding="3" width="1000px">
            <tr id="cabecera_1" style="height: 100px;">
                <td colspan="2" width="100px" style="text-align: center;"><img src="img/sacyr.png" border="0" width="150px"></td>
                <td colspan="2"></td>
                <td colspan="2" width="100px">
                    <table width="100%">
                        <tr><td><?=$row_emp['direccion'];?></td></tr>
                        <tr><td><?="Comuna ".$row_emp['comuna']."   ".$row_emp['ciudad'];?></td></tr>
                        <tr><td><?=$row_emp['telefono_1'];?></td></tr>
                        <tr><td><?=$row_emp['fax'];?></td></tr>
                    </table>
                </td>
            </tr>
            <tr id="cabecera_2">
                <td colspan="2"><label style="margin-top:0;display:block;">Bodega</label></td>
                <td colspan="2" style="text-align: center;height: 60px;font-size: 32pt;font-weight: bold;">Orden Compra</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">
                    <table>
                        <tr><td>Solicitante</td></tr>
                        <tr><td><label>Concepto:</label></td></tr>
                        <tr><td><label>Centro Costo:</label> </td></tr>
                    </table>
                </td>
                <td colspan="2" style="text-align: left;">
                    <table>
                        <tr><td>Solicitante</td></tr>
                        <tr><td><select name="concepto" >
                        <option value=""> --- </option>
                        <option value="1" <? if($concepto==1){ echo "SELECTED" ;}?>>Compra</option>
                        <option value="2" <? if($concepto==2){ echo "SELECTED" ;}?>>Mantenci&oacute;n</option>
                        <option value="3" <? if($concepto==3){ echo "SELECTED" ;}?>>Reparaci&oacute;n</option>
                      </select></td></tr>
                        <tr>
                            <td>
                               <select name="centro_costo" >
                                <option value=""> --- </option>
                                <?
                                    $cc ="SELECT * FROM centros_costos where rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                                    $rc = mysql_query($cc,$con);
                                     while($ce = mysql_fetch_assoc($rc)){
                                     ?>
                                    <option value="<?=$ce['Id_cc'];?>" <? if($centro_costo==$ce['Id_cc']){ echo "SELECTED" ;}?>><?=$ce['descripcion_cc'];?></option>
                                     <?   
                                    }
                                ?>
                              </select> 
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="2" width="100px;" >
                    <table width="100%" border="1">
                        <tr>
                            <td width="50px" style="height: 50px;text-align: center; font-size: 20px;"><?=$num_oc;?></td>
                            <td width="50px" style="height: 50px;text-align: center;font-size: 20px;"><?=date('d-m-Y');?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table width="100%" border="1" cellspacing="3" cellpadding="3" >
                        <tr>
                            <td height="100%;" id="proveedor_selec">
                               <select name="proveedor" onchange="selecciona_proveedor('this.value')">
                                    <option value=""> --- </option>
                                   <? $sql = "SELECT * FROM proveedores WHERE rut_empresa = '".$_SESSION['empresa']."' Order By razon_social";
                                       $res = mysql_query($sql,$con);
                                       while($row = mysql_fetch_assoc($res)){
                                           ?>
                                   <option value="<?=$row['rut_proveedor'];?>" <?if($row['rut_proveedor']==$proveedor){ echo "SELECTED";}?>><?=$row['razon_social'];?></option>

                                    <?   }      ?>
                               </select><br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td id="detalle_Orden" colspan="6">
            <table style="width:900px;" id="detalle-prov" class="detalle-oc" border="0" cellpadding="3" cellspacing="4">
                <?
                if(isset($_GET['id_oc'])){
                $s = "SELECT * FROM detalle_oc WHERE id_oc=".$_GET['id_oc'];
                $result = mysql_query($s,$con);
                $can="";
                if(mysql_num_rows($result)!=NULL){
                    $can = mysql_num_rows($result);
                }
                ?>
                <tr><td colspan="7"><a href="javascript:agregar_detalle_oc(<?if($can!=""){ echo $can; }else{echo "1";}?>);" id="agregar"><img src="img/add1.png" width="20px" height="20px"></a></td></tr>
                <tr>
                    
                    <td>cantidad</td>
                    <td>Unidad</td>
                    <td>Descripcion</td>
                    <td>Precio</td>
                    <td>Total</td>
                    <td>eliminar</td>
                </tr>
                <? if($can>0){ 
                    $i=1;
                    while($rew = mysql_fetch_assoc($result)){
                    
                    ?>
                     <tr class="det" id="det_<?=$i;?>">
                    
                    <td><input class="fu nume" type="text" name="cant[]" id="cant_<?=$i;?>" size="4" onchange="calculo_total_detalle(1);" value="<?=$rew['cantidad'];?>"></td>
                    <td><input class="fu nume" type="text" name="unit[]"  id="unit_<?=$i;?>" size="4" value="<?=$rew['unidad'];?>"></td>
                    <td><input class="fu nume" type="text" name="descripcion[]"  id="descripcion_<?=$i;?>"size="30" value="<?=$rew['descripcion'];?>"></td>
                    <td><input class="fu nume" type="text" name="precio[]"  id="precio_<?=$i;?>" size="9" onchange="calculo_total_detalle(1);"  value="<?=$rew['precio'];?>"></td>
                    <td><input class="fu nume" type="text" id="total_<?=$i;?>" name="total[]"  value="<?=$rew['total'];?>" readonly="readonly"  size="10"></td>
                    <td></td>
                </tr>
                <?
                    $i++;
                    }
                }
                }else{
                ?>
                
                <tr><td colspan="7"><a href="javascript:agregar_detalle_oc(1);" id="agregar"><img src="img/add1.png" width="20px" height="20px"></a></td></tr>
                <tr>
                    
                    <td>cantidad</td>
                    <td>Unidad</td>
                    <td>Descripcion</td>
                    <td>Precio</td>
                    <td>Total</td>
                    <td>eliminar</td>
                </tr>
                 <tr id="det_1" class="det" >
                    
                    <td><input class="fu nume" type="text" name="cant[]" id="cant_1" size="4" onchange="calculo_total_detalle(1);"></td>
                    <td><input class="fu nume" type="text" name="unit[]"  id="unit_1" size="4"></td>
                    <td><input class="fu nume" type="text" name="descripcion[]"  id="descripcion_1"size="30"></td>
                    <td><input class="fu nume" type="text" name="precio[]"  id="precio_1" size="9" onchange="calculo_total_detalle(1);"></td>
                    <td><input class="fu nume" type="text" id="total_1" name="total[]" value="" readonly="readonly"  size="10"></td>
                    <td></td>
                </tr>
                    
                <?}?>
            </table>
        </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">Moneda</td>
                <td colspan="2"><input class="fo" type="text" name="moneda" value="<?=$moneda;?>"></td>
                <td colspan="2"></td>
            </tr>
             <tr>
                <td colspan="2" rowspan="2"></td>
                <td colspan="2" rowspan="2"></td>
                <td colspan="2" rowspan="3">
                    <table>
                        <tr>
                            <td>Subtotal</td>
                            <td><input class="fo" type="text" id="subtotal" name="subtotal" value="<?=$subtotal;?>" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td>Descuento</td>
                            <td><input class="fo nume" type="text" name="descuento" value="<?=$descuento;?>" id="descuento_oc" onchange="calculo_valor_neto();"></td>
                        </tr>
                        <tr>
                            <td>Valor Neto</td>
                            <td><input class="fo nume" type="text" id="valor_neto_oc" name="valor_neto" readonly="readonly" value="<?=$valor_neto;?>"></td>
                        </tr>
                        <tr>
                            <td>I.V.A 19%</td>
                            <td><input class="fo nume" type="text" name="iva" id="iva" onchange="calculo_total();" value="<?=$iva;?>"></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td><input class="fo" type="text" id="total_pago" name="total_pago" readonly="readonly" value="<?=$total?>"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="height: 60px;">
            </tr>
            <tr><td colspan="2">forma pago</td>
                <td><input class="fo" type="text" name="forma_de_pago" size="15" value="<?=$forma_de_pago;?>"></td>
            </tr>
            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center;font-size: 11px;">
                    <p>De conformidad a lo prescrito en la Ley Nº 19.983 del Año 2007, que REGULA LA TRANSFERENCIA Y OTORGA MERITO EJECUTIVO A COPIA DE LA</p>
                    <p>FACTURA, las partes acuerdan que el plazo indicado en el Número 3 del Artículo 2º de dicha Ley, para reclamar el contenido de las facturas presentadas</p>
                    <p>por el Proveedor, Subcontratista, Contratista o como se le tenga denomidado en el Contrato o en la <b>Órden de Compra, será de 30 DÍAS.</b></p>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table border="1" width="100%">
                        <tr>
                            <td><label>Fecha Entrega:</label> </td>
                            <td><input class="fu" type="text" name="dia_entrega" size="2" value="<?=$dia;?>" > - <input class="fu" type="text" name="mes_entrega" size="2" value="<?=$mes;?>"> - <input class="fu" type="text" name="anio_entrega" size="4" value="<?=$anio;?>"> (DD-MM-YYYY)</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" width="450px" style="font-size: 11px;">
                                <b>De acuerdo a lo conversado, rogamos emitan la factura a: SACYR CHILE S.A.<br/>AV VITACURA Nº 2939 OFICINA 1102 Las Condes, Santiago   R.U.T. 96.786.880 - 9<br/>ADJUNTAR COPIA DE ORDEN DE COMPRA A LA FACTURA</b>
                            </td>
                            <td width="100px"></td>
                            <td colspan="2" width="450px" rowspan="4"></td>
                        </tr>
                        <tr>
                            <td width="225px">Sometido al Plan de Calidad</td>
                            <td width="225px"><input type="checkbox" name="plan_calidad" <?if($plan_calidad==1){echo "checked";}?>></td>
                            <td></td>
                            
                            
                        </tr>
                        <tr>
                            <td>Adjunta Especificación Técnica</td>
                            <td><input type="checkbox" name="plan_calidad" <?if($esp_tecnica==1){echo "checked";}?>></td>
                            <td></td>
                            
                            
                        </tr>
                        <tr>
                            <td>Adjunta Propuesta Económica</td>
                            <td><input type="checkbox" name="plan_calidad" <input type="checkbox" <?if($prop_econ==1){echo "checked";}?>></td>
                            <td></td>
                            
                            
                        </tr>
                        <tr>
                            <td colspan="6" style="height: 30px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 100px; width: 200px">
                                <label>VB Depto Compras</label><br/><input type="checkbox" name="vb_depto_compras" <?if($row['vb_depto_compras']==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 200px">
                                <label>VB Jefe Compras</label><br/><input type="checkbox" name="vb_jefe_compras" <?if($row['vb_jefe_compras']==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 200px">
                                <label>VB Jefe ADM</label><br/><input type="checkbox" name="vb_jefe_adm" <?if($row['']==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 200px">
                                <label>VB Jefe PM</label><br/><input type="checkbox" name="vb_jefe_pm" <?if($row['']==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 100px"></td>
                            <td style="height: 100px; width: 100px"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="height: 30px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="text-align: center;font-size: 11px;">
                                <p><b>Horario Recepción de Materiales:   Lunes a Jueves de: 8:30 a 12:30 y de 14:30 a 18:00;   Viernes de: 8:30 a 12:00 Hrs.</b></p>
                            </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
            <tr><td  colspan="6"><input type="submit" value="GRABAR Orden de Compra"></td></tr>
        </table>
   </div>
   
   
   ---------------------------------------------
   ---------------------------------------------
   ---------------------------------------------