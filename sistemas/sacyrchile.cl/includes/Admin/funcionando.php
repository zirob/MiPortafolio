



<?php

	//Datos de la cabecera
    $sql_emp = "SELECT * FROM empresa WHERE rut_empresa='".$_SESSION['empresa']."'";
    $res_emp = mysql_query($sql_emp,$con);
    $row_emp = mysql_fetch_assoc($res_emp);
	
	//Indicadores
	$i=1;
	$j=0;
	
	//Agregar una linea
	if(!empty($_POST['agregar']))
	{
		$_POST['cantidad']++;
	}
	
	//Eliminar una fila
	if(!empty($_POST['eliminar']))
	{
		$_POST['visible'.$_POST['eliminar']]=1;
	}
	

?>
<table style="width:1000px;" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2"> <a href="?cat=2&sec=15"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Ordenes de Compra"></a></td></tr>
    
    <tr>
</table>
<form action="?cat=2&sec=16&action=<?=$action?>" method="POST" name='f1' id="f1">
<div style="width:100%; height:auto; ">
<table style="width:100%;" cellpadding="3" cellspacing="4" border="0">
            <tr id="cabecera_1" style="height: 100px;">
                <td colspan="2" width="100px" style="text-align: left;"><img src="img/sacyr.png" border="0" width="150px"></td>
                <td colspan="2"></td>
                <td colspan="2" width="100px">
                    <table width="100%" style="text-align:center;">
                        <tr><td><?=$row_emp['direccion'];?></td></tr>
                        <tr><td><?="Comuna ".$row_emp['comuna']."   ".$row_emp['ciudad'];?></td></tr>
                        <tr><td><?=$row_emp['telefono_1'];?></td></tr>
                        <tr><td><?=$row_emp['fax'];?></td></tr>
                        </table>
</table>
</div>
<div >
                    <table style="margin-right: 0px;float:right;" border="0" width="100%">
                        <tr><td style="text-align: right;" width="150px"><b>Solicitante:</b></td><td><input class="fu" type="text" size="50" name="solicitante" value="<?=$_POST['solicitante'];?>" style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F; width:300px;"  ></td></tr>
                        <tr><td style="text-align: right;"><b>Concepto:</b></td><td>
                        <select name="concepto" style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F; width:100px;">
                        <option value=""> --- </option>
                        <option value="1" <? if($concepto==1){ echo "SELECTED" ;}?>>Compra</option>
                        <option value="2" <? if($concepto==2){ echo "SELECTED" ;}?>>Mantenci&oacute;n</option>
                        <option value="3" <? if($concepto==3){ echo "SELECTED" ;}?>>Reparaci&oacute;n</option>
                        </select></td>
                        </tr>
                        <tr><td style="text-align: right;"><b>Centro Costo:</b> </td>
                        <td>
                        <select name="centro_costo"  style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F; width:200px;">
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
                        <tr><td style="text-align: right;"><b>Solicitud Compra:</b> </td>
                            <td >
                                <select name="solicitud_oc"    style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F; width:200px;">
                                    <option value="0">---</option>
                                <?   $s = "SELECT id_solicitud_compra, descripcion_solicitud FROM solicitudes_compra WHERE estado=3 and rut_empresa='".$_SESSION['empresa']."'";
                               $r = mysql_query($s,$con);
                                if(mysql_num_rows($r)!= NULL){
                                    while($rw = mysql_fetch_assoc($r)){?>
                                  
                                    <option value="<?=$rw['id_solicitud_compra'];?>" <? if($_POST['solicitud_oc']==$rw['id_solicitud_compra']){ echo "SELECTED";}?>><?=$rw['descripcion_solicitud'];?></option>
                                <?    }
                                }   
                                ?>
                                </select>
                                
                                
                            </td>
                        </tr>
                        <tr>
                        	<td style="text-align:right; font-weight:bold;">Numero:</td><td><?="N°".$num_oc;?></td>
                        </tr>
                        <tr>
                        	<td style="text-align:right; font-weight:bold;">Fecha:</td><td><?=date('d-m-Y');?></td>
                        </tr>
                        
                            <td id="link-solic-oc">
                                <? 
                                    if($id_solic_oc!=""){
                                        $sql = "SELECT * FROM archivos WHERE estado_archivo=1 and id_solicitud=".$id_solic_oc;
                                        $rew = mysql_query($sql,$con);
                                        $r = mysql_fetch_assoc($rew);

                                        echo "<a href='".$r['ruta_archivo']."".$r['nombre_archivo'].".".$r['extension_archivo']."'>".$r['nombre_archivo']."</a> <input type='text' name='archivo' value='".$r['id_archivo']."' hidden='hidden'>";

                                    }
                                ?>
                                
                                
                            </td>
                        </tr>
                    </table>
  </div>
  <div>
                    <table width="100%"  style="width:100%;" cellspacing="3" cellpadding="3" border="0"> 
                        <tr>
                            <td width="145px;"id="proveedor_selec" style=" text-align:right;"><b>Proveedor:</b></td>
                            <td>
                               <select name="proveedor"  style="background-color:rgb(255,255,255); color:#000000;  border:1px solid #00F; width:300px;"  onchange="submit()"  >
                                    <option value=""> --- </option>
                                    <? $sql = "SELECT * FROM proveedores WHERE rut_empresa = '".$_SESSION['empresa']."' Order By razon_social";
     							       $res = mysql_query($sql,$con);
                                       while($row = mysql_fetch_assoc($res))
									   {
											echo "<option value=".$row['rut_proveedor'].""; if($row['rut_proveedor']==$_POST['proveedor']) echo " selected "; echo">".$row['rut_proveedor']."</option>";   
									   }
									?>
                               </select>
                         </td>
                      </tr>
                      </table>
                      <table>
                         </tr>
                               <? if($_POST['proveedor']!=""){
                                        $sql ="SELECT * FROM proveedores WHERE rut_proveedor='".$_POST['proveedor']."'";
                                        $res = mysql_query($sql,$con);
                                        $row = mysql_fetch_assoc($res);
                                         $html="";
										echo "<table border='0' width='100%'>";
                                        $html.='<tr>';
                                        $html.='<td width="150px" style="text-align: right;"><b>Razón Social:</b></td>';
                                        $html.='<td width="150px" style="text-align: left;" ><input type="text" value='.$row['razon_social'].' style="width:Auto" readonly></td>';
                         
                                        $html.='<td width="150px" style="text-align: right;"><b>Rut:</b></td>';
                                        $html.='<td width="150px" style="text-align: left;"><input type="text" value='.$row['rut_proveedor'].' style="width:Auto" readonly></td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                        $html.='<td width="150px" style="text-align: right;"><b>Domicilio:</b></td>';
                                        $html.='<td style="text-align: left;" width="200"><input type="text" value='.$row['domicilio'].' style="width:Auto" readonly></td>';
                        
                                        $html.='<td style="text-align: right;"><b>Comuna:</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['comuna'].' style="width:Auto" readonly></td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                        $html.='<td style="text-align: right;"><b>Ciudad:</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['rut_proveedor'].' style="width:Auto" readonly></td>';
                                       
                                        $html.='<td style="text-align: right;"><b>Celular:</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['rut_proveedor'].' style="width:Auto" readonly></td>';
                                   
                                        $html.='<td style="text-align: right;"><b>Telefono:</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['telefono_1'].' style="width:Auto" readonly></td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                        $html.='<td style="text-align: right;"><b>ATT (SR/A):</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['contacto'].' style="width:Auto" readonly></td>';
                                     
                                        $html.='<td style="text-align: right;"><b>Email:</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['mail'].' style="width:Auto" readonly></td>';
                                     
                                        $html.='<td style="text-align: right;"><b>Fax:</b></td>';
                                        $html.='<td style="text-align: left;"><input type="text" value='.$row['fax'].' style="width:Auto" readonly></td>';
                                        $html.='</tr>';
                                        $html.='</table>';

                                        echo $html;
                               
                               } ?>	
						</td>
                        </tr>
                    </table>
</div>
            <table style="width:90%; border-collapse:collapse;" id="detalle-prov" class="detalle-oc"  cellpadding="3" cellspacing="4" align="center">
              <tr>
              <td colspan="6" style="text-align:right;">
              	<button type="submit" name="agregar" value='1' ><img src="img/add1.png" width="20" height="20" /></button>
              </td>
              </tr>
                <tr>
                    
                    <td style="text-align:center; background-color:#40B5FB; border:1px solid rgb(0,0,0);"><b>Cantidad</b></td>
                    <td style="text-align:center; background-color:#40B5FB; border:1px solid rgb(0,0,0);"><b>Unidad</b></td>
                    <td style="text-align:center; background-color:#40B5FB; border:1px solid rgb(0,0,0);"><b>Descripci&oacute;n</b></td>
                    <td style="text-align:center; background-color:#40B5FB; border:1px solid rgb(0,0,0);"><b>Precio</b></td>
                    <td style="text-align:center; background-color:#40B5FB; border:1px solid rgb(0,0,0);"><b>Total</b></td>
                    <td style="text-align:center; background-color:#40B5FB; border:1px solid rgb(0,0,0);"><b>Eliminar</b></td>
                </tr>
                <input  type='hidden' name="cantidad" value='<? echo $_POST['cantidad'];?>'/>
                
                <style>
				.detalle
				{
					text-align:center; 
					background-color:#EBEBEB;
					border:1px solid rgb(0,0,0);
				}
				</style>
                <?
					while(($_POST['cantidad']>=$i))
					{
		 				
						echo "<tr>";
						if(empty($_POST['visible'.$i]))
						{
						echo "<td class='detalle' width='80px'><input type='text' name='cantidad".$i."'    value='".$_POST['cantidad'.$i]."' onchange='calcular".$i."()'  style='width:100px; text-align:center' ></td>";
						echo "<td class='detalle'><input type='text' name='unidad".$i."'      value='".$_POST['unidad'.$i]."' ></td>";
						echo "<td class='detalle'><input type='text' name='descripcion".$i."' value='".$_POST['descripcion'.$i]."' ></td>";
						echo "<td class='detalle'><input type='text' name='precio".$i."'      value='".$_POST['precio'.$i]."' onchange='calcular".$i."()' style='width:100px; text-align:right'></td>";
						echo "<td class='detalle'><input type='text' name='total".$i."'       value='".$_POST['total'.$i]."' readonly  style='width:100px; text-align:right' ></td>";
						echo "<td class='detalle'><button name='eliminar' value='".$i."' ><img src='img/borrar.png' width='16' height='16' /> </button></td>";
						}
						else
						{
						echo "<input type='hidden' name='total".$i."'       value='".$_POST['total'.$i]."' readonly  style='width:100px; text-align:right'>";
						}
						echo "<input type='hidden' name='visible".$i."' value='".$_POST['visible'.$i]."' />";
						echo "</tr>";
						$i++;
				    }
					
					
				?>
                </table>
                <table width="90%" border="0" align="center">
                 <tr>
                <td  style="text-align:right; font-weight:bold" width="760px">SubTotal:</td>
                <td  style="text-align:center"><input type="text" name="subtotal" value='<? echo $_POST['subtotal'];?>' style="width:80px; text-align:right" readonly /></td>					<tr>
                <tr>
                <td  style="text-align:right; font-weight:bold" width="760px">Descuento:</td>
                <td  style="text-align:center"><input type="text" name="descuento" value='<? echo $_POST['descuento'];?>' style="width:80px; text-align:right"  /></td>					<tr>
                 <tr>
                <td  style="text-align:right; font-weight:bold" width="760px">Neto:</td>
                <td  style="text-align:center"><input type="text" name="valor_neto" value='<? echo $_POST['valor_neto'];?>' style="width:80px; text-align:right"  readonly /></td>					<tr>
                 <tr>
                <td  style="text-align:right; font-weight:bold" width="760px">IVA:</td>
                <td  style="text-align:center"><input type="text" name="iva" value='<? echo $_POST['iva'];?>' style="width:80px; text-align:right"  readonly /></td>					<tr>
                 <tr>
                <td  style="text-align:right; font-weight:bold" width="760px">Total:</td>
                <td  style="text-align:center"><input type="text" name="total_doc" value='<? echo $_POST['total_doc'];?>' style="width:80px; text-align:right"  readonly /></td>					<tr>
      			
</table>
</form>    

<?   echo $_POST['cantidad'];?>

<?
 $k=1;
 while(($_POST['cantidad']>=$k))
 {
 echo "<script>
 ";
 echo "function calcular".$k."()
 ";
 echo "{
 ";
 echo "document.f1.total".$k.".value=document.f1.cantidad".$k.".value*document.f1.precio".$k.".value;
 ";
 echo " subtotal=0;
 ";
echo "	total=0;
";
echo "	iva=0;
";
echo "	document.f1.subtotal.value=subtotal;
";
echo " 	document.f1.valor_neto.value=document.f1.subtotal.value-document.f1.descuento.value;
";
echo " 	iva=(document.f1.valor_neto.value)*0.19;
";
echo "  iva=iva.toFixed(0);
";
echo " 	document.f1.iva.value=iva;
";
echo " 	total=(document.f1.valor_neto.value)*1.19;
";
echo " 	total=total.toFixed(0);
";
echo "  	document.f1.total_doc.value=total;
";
for($i=1;$i<=$_POST['cantidad'];$i++)
{
	//Valida si esta visible
	echo " if(document.f1.visible".$i.".value!=1)
	";
	echo " {
	";
		//Si esta visible y esta vacio le pone 0
		echo " if(document.f1.total".$i.".value=='')
		";
		echo " {
		";
		echo " document.f1.total".$i.".value=0
		";
		echo " }
		";
	echo " subtotal=subtotal+parseInt(document.f1.total".$i.".value);
	";
	echo " }
	";
}
echo " (document.f1.subtotal.value=subtotal)
";
echo "}
 ";
echo "</script>
 ";
 $k++;
 }
 

?>






