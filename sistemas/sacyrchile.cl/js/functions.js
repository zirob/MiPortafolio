/*
 * 
 *  Funciones para Validar OC.
 * 
 * 
 */

function calcular_cambios_oc(cant){
    total_oc = 0;
    if(cant > 1){
        for(i=1;i<=cant;i++){
            if($('#cant_'+i).val()!=0){ can = $('#cant_'+i).val(); }else{  can= 0; }
            if($('#unit_'+i).val()!=0){ unit = $('#unit_'+i).val(); }else{  unit= 0; }
            if($('#precio_'+i).val()!=0){ precio = $('#precio_'+i).val(); }else{  precio= 0; }
            if($('#total_'+i).val()!=0){ total = $('#total_'+i).val(); }else{  total= 0; }
            
            total_prod = parseInt(can) * parseInt(precio);
            total_oc = parseInt(total_oc) + parseInt(total_prod);
            $('#total_'+i).val(total_prod);
            
        }
            
            
            $('#subtotal').val(total_oc);
            
            if($('#descuento_oc').val()!=0){  
                desc = $('#descuento_oc').val();
                desc_total = parseInt(total_oc) - parseInt(desc) ;
                $('#valor_neto_oc').val(desc_total);
                
                if($('#iva').val()!=0){
                    iva = parseInt($('#valor_neto_oc').val())* parseInt($('#iva').val());
                    iva_aplicado = parseInt(iva) / parseInt(100);
                    total = parseFloat(neto) + parseFloat(iva_aplicado);
                    $('#total_pago').val(total);
                }else{
                    $('#total_pago').val(desc_total);
                }
                
            }else{
                $('#valor_neto_oc').val(total_oc);
                
            }
            if($('#iva').val()!=0){
                    iva = parseInt($('#valor_neto_oc').val())* parseInt($('#iva').val());
                    iva_aplicado = parseInt(iva) / parseInt(100);
                    total = parseInt(neto) + parseInt(iva_aplicado);
                    $('#total_pago').val(total);
                }else{
                    $('#total_pago').val(desc_total);
                }
            
        
    }else{
            if($('#cant_1').val()!=0){ can = $('#cant_1').val(); }else{  can= 0; }
            if($('#unit_1').val()!=0){ unit = $('#unit_1').val(); }else{  unit= 0; }
            if($('#precio_1').val()!=0){ precio = $('#precio_1').val(); }else{  precio= 0; }
            if($('#total_1').val()!=0){ total = $('#total_1').val(); }else{  total= 0; }
            
            total_prod = parseInt(can) * parseInt(precio);
            
            $('#total_1').val(total_prod);
            total_oc = parseInt(total_oc) + parseInt(total_prod);
            $('#subtotal').val(total_oc);
            
            if($('#descuento_oc').val()!=0){  
                desc = $('#descuento_oc').val();
                desc_total = parseInt(total_oc) - parseInt(desc) ;
                $('#valor_neto_oc').val(desc_total);
                
                                
            }else{
                $('#descuento_oc').val(0);
                $('#valor_neto_oc').val(total_oc);
                
           }
          
           if($('#iva').val()!=0){
                    iva = parseInt(total_oc)* parseInt($('#iva').val());
                    iva_aplicado = parseInt(iva) / parseInt(100);
                    total = parseInt(total_oc) + parseInt(iva_aplicado);
                    $('#total_pago').val(total);
                }else{
                    $('#iva').val(0);
                    $('#total_pago').val(total_oc);
                }
            
            
}
}

function selecciona_proveedor(cod_prov){
     $('#datos_proveedor').remove();
         $.ajax({
		type: "POST",
		url: "includes/Admin/functions.php",
		data: "id_prov="+cod_prov,
		success: function(msg){
			if(msg){
                          
                           $('#proveedor_selec').append(msg);
			}
			
		}
	   });
}



function reasignar_items(cant,emp,prod){
       
        $.ajax({
		type: "POST",
		url: "includes/Admin/functions.php",
		data: "asign_bod=1&emp="+emp,
		success: function(msg){
			if(msg){
                            if ($('select').length){

                            }else{
                            for(i=1;i<=cant;i++){
                                $('#asignar_bodega_'+i).append(msg);
                            }
                          
                          
                            tdgrb ='<tr><td colspan="8">'+
                                 '<input type="text" name="rut_emp" value="'+emp+'" hidden="hidden">'+
                                 '<input type="text" name="cod_prod" value="'+prod+'" hidden="hidden">'+   
                                 '<input type="submit" value="Asignar Bodegas">'+
                                 '</form></td></tr>';
                           $('#list_registros').append(tdgrb);
                           }
			}
			//alert(msg);
		}
	   });
     
}



function valida_detalle_prod(){
    cant = $('cant_item').val();
    r=1;
    for(i=1;i<=cant;i++){
        
        if($('#arrendado_'+i).val()>0 && $('#valor_u_'+i).val()>0 && $('#estado_'+i).val()>0 && $('#especifico_'+i).val()>0){
          r++;
        }else{
            if(i==cant){
            alert('Debe ingresar los campos obligatorios (*)');
            }
        }
        
    }
    if(r==cant){
          $('#btn_submit').append('<input type="submit" value="Grabar">');
    }
}


function valida_email(valor){
    var reg=/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
    if(reg.test(valor)){ i=1; //email correcto
    }else{ 
        alert('Debe Ingresar una Direccion Email Valida xxxxx@xx.x'); 
        $('#email').focus();}
        //email incorrecto
}

function busca_prod_detalle(cod_prod,emp){
     $('#producto_detalle').remove();
    $.ajax({
		type: "POST",
		url: "includes/Admin/functions.php",
		data: "emp="+emp+"&cod_producto="+cod_prod,
		success: function(msg){
			if(msg){
                          
                           $('#detalle_prod').append(msg);
			}
			//alert(msg);
		}
	   });
}


function busca_prod_detalle_2(cod_prod,emp){
     $('#producto_detalle').remove();
    $.ajax({
		type: "POST",
		url: "includes/Admin/functions.php",
		data: "otro=1&emp="+emp+"&cod_producto="+cod_prod,
		success: function(msg){
			if(msg){
                          
                           $('#detalle_prod').append(msg);
			}
			//alert(msg);
		}
	   });
}

function agrega_centro_costo(cod_detalle){
      
    $.ajax({
		type: "POST",
		url: "includes/Admin/functions.php",
		data: "centro=1&cod_producto="+cod_detalle,
		success: function(msg){
			if(msg){
                          
                           $('#detalle_prod').append(msg);
			}
			//alert(msg);
		}
	   });
}

function solic_oc_select(id){
            $('#link-solic-oc').html("");
            $.ajax({
		type: "POST",
		url: "includes/Admin/functions.php",
		data: "id_solic="+id,
		success: function(msg){
			if(msg){
                           
                           $('#link-solic-oc').append(msg);
			}
			//alert(msg);
		}
	   });

}


function agregar_detalle_oc(num){
    num++;
    $('.detalle-oc').append( '<tr id="det_'+num+'"> '+
                    '<td><input class="fu" type="text" name="cant[]" size="4" id="cant_'+num+'" ></td>' +
                    '<td><input class="fu" type="text" name="unit[]" size="4" id="unit_'+num+'" ></td>' +
                    '<td><input class="fu" type="text" name="descripcion[]" size="30" id="descripcion_'+num+'" ></td>' +
                    '<td><input class="fu" type="text" name="precio[]" size="9" id="precio_'+num+'"></td>' +
                    '<td><input class="fu" type="text" id="total_'+num+'" name="total[]" value="" readonly="readonly" size="10"></td>' +
                    '<td style="text-align:center;"><a href="javascript:eliminar_detalle('+num+');"><img src="img/delete2.png" width="16px" height="16px"></a></td>' +
                    '</tr>'); 
   $('a#agregar').attr("href","javascript:agregar_detalle_oc("+num+");"); 
   $('input#calculo_total').attr("onClick","javascript:calcular_cambios_oc("+num+");");
}

function eliminar_detalle(num){
    ultimo = num - 1;
    total_nuevo = parseInt($('#subtotal').val())-parseInt($('#total_'+num).val());
    $('#subtotal').val(total_nuevo);
    $('#det_'+num).remove();
    $('input#calculo_total').attr("onClick","javascript:calcular_cambios_oc("+ultimo+");");
}

function calculo_total_detalle(num, tipo){
    resultado=0;
    if(tipo==2){
        if( $('#precio_'+num).val()!=0 && $('#cant_'+num).val()!=0 ){
            cant = $('#cant_'+num).val();
            valor = $('#precio_'+num).val();
            resultado =  cant * valor; 
        }
        calculo_total_OC(num);
        calculo_valor_neto();
        calculo_total();
    }else{
        if( $('#precio_'+num).val()!=0 && $('#cant_'+num).val()!=0 ){
            cant = $('#cant_'+num).val();
            valor = $('#precio_'+num).val();
            resultado =  cant * valor; 
        }
    }
    
    if(num>1){
        calculo_total_OC(num);
        calculo_valor_neto();
        calculo_total();
    }
    
    $('#total_'+num).val(resultado);
    calculo_total_OC(num);
}

function calculo_total_OC(ultimo){
    total_compra =0;
    for(i=ultimo;i>0;i--){
        total_compra = parseInt(total_compra) + parseInt($('#total_'+i).val());
    }
    $('#subtotal').val(total_compra);
}

function calculo_valor_neto(){
    tot = parseInt($('#subtotal').val()) - parseInt($('#descuento_oc').val());
    $('#valor_neto_oc').val(tot);
}

function calculo_total(){
    neto = parseInt($('#valor_neto_oc').val());
    iva = parseInt($('#valor_neto_oc').val())* parseInt($('#iva').val());
    
    iva_aplicado = parseInt(iva) / parseInt(100);
    total = parseFloat(neto) + parseFloat(iva_aplicado);
    
    $('#total_pago').val(total);
    
    if(total>50000){
        alert("Esta Orden de Compra necesita VB°");
        $('#aprovaciones_VB').append('<tr><td><label>VB Depto Compras</label><input type="checkbox" name="vb_depto_compras" ></td>'+
                                     '<td><label>VB Jefe Compras</label><input type="checkbox" name="vb_jefe_compras" ></td>' +
                                     '<td><label>VB Jefe ADM</label><input type="checkbox" name="vb_jefe_adm" ></td>' +
                                     '<td><label>VB Jefe PM</label><input type="checkbox" name="vb_jefe_pm" ></td></tr>');
    }else{
        alert("Esta Orden de Compra sera Aprobada automaticamente");
    }
}



/****  OT   y Detalle OT **/
function eliminar_detOT(num){
    $('#detalle_ot_'+num).remove();
}
function nuevo_detOT(num){
    num++;
    $('#detalles-ot').append(
    '<table id="detalle_ot_'+num+'" style="width:900px; margin-top:10px" id="detalle-cc" border="1" cellpadding="3" cellspacing="2">' +
    '<tr><td colspan="3"><a href="javascript:eliminar_detOT('+num+');"><img alt="Eliminar Detalle" src="img/borrar.png" border="0"></a></td></tr>' +   
    '<tr>' +
          '      <td colspan="3"><label>Operador:</label>'+
                    '<select name="operador_'+num+'"><option value="" >---</option>'+
                        '<? '+
                        '    $s = "SELECT * FROM usuarios WHERE tipo_usuario=9 ORDER BY nombre";'+
                        '    $r = mysql_query($s,$con);'+
                        '   if(mysql_num_rows($r)!= null){'+ 
                        '       while($rw = mysql_fetch_assoc($r)){ ?>' +
                        '          <option value="<?=$rw["usuario"];?>"><?=$rw["nombre"];?></option>' +
                        ' <?       }' +
                        '    }else{ ?>' +
                        '        <option value="0"> ---- </option>' +
                        ' <?   } ?> ' +
                        '?>'+
                        
                    '</select></td>'+
            '</tr>'+
            '<tr>'+
            '    <td colspan="3"><label>Descripcion:</label><br /><textarea cols="110" rows="2" name="descripcion_'+num+'"></textarea></td>' +
            '</tr>'+
            '<tr>'+
            '    <td ><label>Fecha:</label><input type="text" name="fecha_'+num+'" size="20" value=""></td>'+
            '    <td ><label>Horas:</label><input type="text" name="horas_'+num+'" size="20" value=""></td>'+
            '    <td ><label>Estado del Trabajo:</label>'+
            '<select name="estado_trabajo[]">'+
            '    <option> --- </option>'+
            '    <option value="1" > Pendiente </option>'+
            '    <option value="2" > En Proceso </option>'+
            '    <option value="3" > Finalizado </option>'+
            '    <option value="4" > En Espera de Materiales </option>'+
            '    <option value="5" > Trabajo Suspendido </option>'+
            '</select>  '+
            '</tr>'+
            '<tr>'+
            '    <td colspan="3"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones_'+num+'"></textarea></td>'+
            '</tr>'+
            '</table>');
     $('a#agregar').attr("href","");   
     $('a#agregar').attr("href","javascript:nuevo_detOT("+num+");");    
    
}



function imprimir(id)
    {
        var div, imp;
        div = document.getElementById(id);//seleccionamos el objeto
        imp = window.open(" ","Formato de Impresion"); //damos un titulo
        imp.document.open();     //abrimos
        imp.document.write(div.innerHTML);//agregamos el objeto
        imp.document.close();
        imp.print();   //Abrimos la opcion de imprimir
        imp.close(); //cerramos la ventana nueva
    }
    
    
    
    
    function archivo_seleccionado(val){
        $(":checkbox").attr('checked', false);
        $("#archivo_aprovado_"+val).attr('checked', true);
        
    }
    
    
    function nuevos_items(val, tipo,emp){
        
        if(tipo==1){
            prod = val;
            cant = $('#cant_item').val();
        }else{
            cant = val;
            prod = $('#codigo_producto').val();
        }
        emp = String(emp);
        if(prod!="" && cant!=""){
        

            $.ajax({
                    type: "POST",
                    url: "includes/Admin/functions.php",
                    data: "tipo=1&prod="+prod+"&cant="+cant+"&emp="+emp,
                    success: function(msg){
                            if(msg){

                               $('#items-nuevos').append(msg);
                            }
                            //alert(msg);
                    }
               });
        }
    }
    
    function obtiene_prod(cod){
        if(cod!=" "&& cod!=null){
            $.ajax({
                    type: "POST",
                    url: "includes/Admin/functions.php",
                    data: "tipo=2&prod="+cod,
                    success: function(msg){
                            if(msg!=2){
                                $('#listado_det_prod').remove();
                               $('#detalle_prod').append(msg);
                            }else{
                                alert("Debe ingresar un codigo de producto valido");
                                $('#codigo_producto').focus();
                            }
                            //alert(msg);
                    }
               });
       }else{
            alert("Debe ingresar un codigo de producto valido");
            $('#codigo_producto').focus();
       }        
    }
    
    
    
/* 
 * 
 *          CALCULOS PARA PETROLEO.
 * 
 * */    
    
 function total_ief(val,tipo){
     if(tipo==1){
         iev = val;
         if($('#valor_ief').val()!=""){
            ief = $('#valor_ief').val();
         }else{
            ief = 0; 
         }
     }else{
         ief = val;
         if($('#valor_iev').val()!=""){
             iev = $('#valor_iev').val();
         }else{
             iev = 0;
         }
     }
     total = parseInt(ief) * parseInt(iev);
     $('#total_ief_calc').val(total);
 }