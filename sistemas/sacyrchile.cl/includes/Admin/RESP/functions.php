<?php
include '../Conexion.php';
$con = Conexion();

if(isset($_POST['id_solic']) && $_POST['id_solic']!=""){
    $sql = "SELECT * FROM archivos WHERE estado_archivo=1 and id_solicitud=".$_POST['id_solic'];
    $rew = mysql_query($sql,$con);
    $r = mysql_fetch_assoc($rew);
    
    echo "<a href='".$r['ruta_archivo']."".$r['nombre_archivo'].".".$r['extension_archivo']."'>".$r['nombre_archivo']."</a> <input type='text' name='archivo' value='".$r['id_archivo']."' hidden='hidden'>";
}

if(isset($_POST['centro']) && $_POST['centro']==1 && isset($_POST['cod_producto']) && $_POST['cod_producto']!=""){
    $sql = "SELECT c.Id_cc, c.descripcion_cc FROM detalles_productos d inner join centros_costos c on d.centro_costo = c.Id_cc WHERE d.cod_detalle_producto=".$_POST['cod_producto'];
    $rew = mysql_query($sql,$con);  
    $html="";
    $html="<label>Centro Costo(*)</label><br />";
    $html.="<select name='centro_costo'>";
    while($r = mysql_fetch_assoc($rew)){
        $html.="<option value='".$r['Id_cc']."'>".$r['descripcion_cc']."</option>";
    }
  
    $html.="</select>";

 
    echo $html;
    
}



if(isset($_POST['otro']) && $_POST['otro']==1 && isset($_POST['cod_producto']) && $_POST['cod_producto']!=""){
    $sql = "SELECT patente, cod_detalle_producto FROM detalles_productos WHERE rut_empresa ='".$_POST['emp']."' and patente IS NOT NULL and patente <> '' and cod_producto=".$_POST['cod_producto'];
    $rew = mysql_query($sql,$con);  
    
    $html="";
    $html.="&nbsp;&nbsp;&nbsp;<div id='producto_detalle'><label>Patente:(*)</label>&nbsp;&nbsp;<select name='detalle_prod'>";
    $html.="<option> --- </option>";
   
    
    while($r = mysql_fetch_assoc($rew)){
        $html.="<option value='".$r['cod_detalle_producto']."'>".$r['patente']."</option>";
    }
  
    $html.="</select></div>";

 
    echo $html;
    
}

if(!isset($_POST['otro']) && isset($_POST['cod_producto']) && $_POST['cod_producto']!=""){
    $sql = "SELECT patente, cod_detalle_producto FROM detalles_productos WHERE patente IS NOT NULL and patente <> '' and cod_producto=".$_POST['cod_producto'];
    $rew = mysql_query($sql,$con);  
    
    $html="";
    $html.="&nbsp;&nbsp;&nbsp;<div id='producto_detalle'><label>Detalle:(*)</label><select name='detalle_prod'>";
    $html.="<option> --- </option>";
   
    
    while($r = mysql_fetch_assoc($rew)){
        $html.="<option value='".$r['cod_detalle_producto']."'>".$r['patente']."</option>";
    }
  
    $html.="</select></div>";

 
    echo $html;
    
}

if(isset($_POST['tipo']) && $_POST['tipo']==1){
    $prod = $_POST['prod'];
    $cant = $_POST["cant"];
    $emp = $_POST['emp'];
    $html="";
    
    for($i=1;$i<=$cant;$i++){
        
        $html.='<table id="detalle-prov" style="margin-top:15px; border:3px #FFF solid;width:900px;"  cellpadding="3" cellspacing="4">';
$html.='<tr><td colspan="3" id="titulo_tabla" style="text-align:center;">Detalle Producto '.$i.'</td></tr>';        
$html.='    <tr><td><label>Codigo Interno:</label><input class="fo" type="text" value="" name="cod_interno[]" id=""></td></tr>';
$html.='    <tr>';
$html.='        <td><label>Patente:</label><input type="text" class="fo" value="" name="patente[]" id="patente"></td>';
$html.='        <td><label>Año:</label><br/><input type="text" class="fu" size="4" value="" name="anio[]" id="anio"></td>';
$html.='        <td><label>Color:</label><input type="text" class="fo" value="" name="color[]" id="color"></td>';
$html.='    </tr>';
$html.='    <tr>';
$html.='        <td><label>Marca:</label><input type="text" class="fo"  value="" name="marca[]" id=""></td>';
$html.='        <td><label>Modelo:</label><input type="text" class="fo" value="" name="modelo[]" id=""></td>';
$html.='        <td><label>VIN:</label><input type="text" class="fo" value="" name="vin[]" id=""></td>';
$html.='    </tr>';
$html.='    <tr>';
$html.='        <td colspan="2"><label>Motor:</label><input class="fo" type="text" value="" name="motor[]" id=""></td>';
$html.='        <td><label>Peso Bruto:</label><input class="fo" type="text" value="" name="peso_bruto[]" id=""></td>';
$html.='    </tr>';
$html.='    <tr>';
$html.='        <td colspan="2"><label>Especifico:(*)</label><br/><select name="especifico[]" id="especifico_'.$i.'" onchange="valida_detalle_prod();"><option value="0">---</option><option value="1">Si</option><option value="2">No</option></select></td>';
$html.='        <td><label>Potencia KVA:</label><input class="fo" type="text" value="" name="potencia[]"></td>';
$html.='    </tr>';
$html.='    <tr>';
$html.='        <td><label>Horas Mensuales:</label><br/><input class="fu nume" type="text" value="" name="horas_mensuales[]" id=""></td>';
$html.='        <td><label>Consumo Nominal:</label><input class="fo nume" type="text" value="" name="consumo_nominal[]" id=""></td>';
$html.='        <td><label>Consumo Mensual:</label><input class="fo nume" type="text" value="" name="consumo_mensual[]" id=""></td>';
$html.='    </tr>';
$html.='    <tr>';
$html.='        <td><label>Centro Costo:</label><br/>';
$html.='            <select name="centro_costo[]"><option value="" >---</option>';
                    $s = "SELECT * FROM centros_costos WHERE rut_empresa='".$emp."' ORDER BY descripcion_cc";
                      $r = mysql_query($s,$con);
                    while($rw = mysql_fetch_assoc($r)){

$html.='                <option value="'.$rw['Id_cc'].'">'.$rw['descripcion_cc'].'</option>';

                    }

$html.='            </select>';
$html.='        </td>';
$html.='        <td><label>Referencia Sacyr:</label><input class="fo" type="text" value="" name="referencia[]" id=""></td>';
$html.='        <td><label>Estado:(*)</label><br/>';
$html.='            <select name="estado[]"  id="estado_'.$i.'" onchange="valida_detalle_prod();">';
$html.='                <option value="0">---</option>
                        <option value="1">Disponible</option>';
$html.='                <option value="2">NO Disponible</option>';
$html.='            </select>';
$html.='        </td>';
$html.='    </tr>    ';
$html.='    <tr>';
$html.='        <td colspan="3"><label>Asignar a Bodega:</label><br/>';
$html.='  <select name="bodega[]" id="bodega"><option value="" >---</option>';               
                $s = "SELECT * FROM bodegas WHERE rut_empresa='".$emp."' ORDER BY descripcion_bodega";
                 $r = mysql_query($s,$con);
                 while($rw = mysql_fetch_assoc($r)){

$html.="                <option value='".$rw['cod_bodega']."'>".$rw['descripcion_bodega']."></option>";

                  }
$html.='  </select>';
$html.='        </td>';
$html.='     </tr>   ';
$html.='        <tr>';
$html.='            <td colspan="3" id="titulo_tabla" style="text-align:center;" >..:: Comercial ::..</td>';
$html.='        </tr>';
$html.='        <tr>';
$html.='            <td><label>ID OC:</label><input class="fo"  type="text" value="" name="id_oc[]" id=""></td>';
$html.='            <td><label>Numero Factura:</label><input  class="fo" type="text" value="" name="n_factura[]" id=""></td>';
$html.='            <td><label>Fecha Factura:</label><input class="fo"  type="text" value="" name="f_factura[]" id=""></td>';
$html.='        </tr>';
$html.='        <tr>';
$html.='            <td><label>Numero Guia Despacho:</label><input class="fo"  type="text" value="" name="n_guia[]" id=""></td>';
$html.='            <td><label>Fecha Guia Despacho:</label><input  class="fo" type="text" value="" name="f_guia[]" id=""></td>';
$html.='            <td><label>Valor Unitario:(*)</label><input  class="fo nume" type="text" value="" name="valor_u[]"  id="valor_u_'.$i.'" onchange="valida_detalle_prod();"></td>';
$html.='        </tr>';
$html.='        <tr>';
$html.='            <td colspan="3"><label>Observaciones:</label>';
$html.='                <textarea name="observaciones[]" id="" ></textarea>';
$html.='            </td>';
$html.='        </tr>';
$html.='        <tr>';
$html.='            <td colspan="2"><label>Empresa Arriendo:</label><input  class="fo" type="text" value="" name="emp_arriendo[]" id=""></td>';
$html.='            <td><label>Producto Arrendado:(*)</label><br/>';
$html.='                <select name="arrendado[]" id="arrendado_'.$i.'" onchange="valida_detalle_prod();"><option value="" >---</option>

                        <option value="1">Arrendado</option>';
$html.='                <option value="2">NO Arrendado</option>';
$html.='                </select>';
$html.='</td>';
$html.='        </tr>    ';
$html.='</table>';
       
    }
    
    echo $html;
}




if(isset($_POST['tipo']) && $_POST['tipo']==2){
    $cod_prod = $_POST['prod'];
    if($cod_prod!="" && !empty($cod_prod)){
    $sql = "SELECT * FROM productos WHERE cod_producto=".$cod_prod;
    $r = mysql_query($sql,$con);
    
    if(mysql_num_rows($r)!=NULL){
        $row = mysql_fetch_assoc($r);



        if($row['tipo_producto']==1){
            $tipo="Maquinarias y Equipos";
        }elseif($row['tipo_producto']==2){
            $tipo="Vehiculo Menor";
        }elseif($row['tipo_producto']==3){
            $tipo="Herramientas";
        }elseif($row['tipo_producto']==4){
            $tipo="Muebles";
        }elseif($row['tipo_producto']==5){
            $tipo="Generador";
        }elseif($row['tipo_producto']==6){
            $tipo="Plantas";
        }elseif($row['tipo_producto']==7){
            $tipo="Equipos de Tunel";
        }elseif($row['tipo_producto']==8){
            $tipo="Otros";
        }

        if($row['activo_fijo']==1){
            $activo_fijo = "Si";
        }else{
            $activo_fijo = "No";
        }


        if($row['critico']==1){
            $critico = "Si";
        }else{
            $critico = "No";
        }

        $html ="";
       $html.='<table id="listado_det_prod" style="margin-top:15px; border:1px #FFF solid;width:900px;"  cellpadding="3" cellspacing="2">';
       $html.='<tr>';
       $html.="<td>Tipo: ".$tipo."</td>";
       $html.="<td>Activo Fijo: ".$activo_fijo."</td>";
       $html.="<td>Critico: ".$critico."</td>";
       $html.='</tr>';
       $html.='<tr>';
       $html.="<td>Descripcion: ".$row['descripcion']."</td>";
       $html.='</tr>';
       $html.='<table>';

        echo $html;
    }else{
        echo "2";
    }
    }else{
        echo "2";
    }
}




if(isset($_POST['asign_bod']) && $_POST['asign_bod']==1 && isset($_POST['emp'])){
    $sql = "SELECT * FROM bodegas WHERE rut_empresa='".$_POST['emp']."'";
    $res = mysql_query($sql,$con);
    
    $html="<select name='nueva_bodega[]'>";
    $html.="<option value='0'>---</option>";
    while($row = mysql_fetch_assoc($res)){
        $html.="<option value='".$row['cod_bodega']."'>".$row['descripcion_bodega']."</option>";
    }
    $html.="</select>";
    echo $html;
}



if(isset($_POST['id_prov']) && $_POST['id_prov']!="" && !empty($_POST['id_prov'])){
    $sql ="SELECT * FROM proveedores WHERE rut_proveedor='".$_POST['id_prov']."'";
    $res = mysql_query($sql,$con);
    $row = mysql_fetch_assoc($res);
     $html="";
    $html.='<table id="datos_proveedor">';
    $html.='<tr>';
    $html.='<td width="150px" style="text-align: right;"><b>RAZÓN SOCIAL:</b></td>';
    $html.='<td width="500px" style="text-align: left;" colspan="4">'.$row['razon_social'].'</td>';
    $html.='<td width="50px"></td>';
    $html.='<td width="150px" style="text-align: right;"><b>RUT:</b></td>';
    $html.='<td width="150px" style="text-align: left;">'.$row['rut_proveedor'].'</td>';
    $html.='</tr>';
    $html.='<tr>';
    $html.='<td width="150px" style="text-align: right;"><b>DOMICILIO:</b></td>';
    $html.='<td style="text-align: left;" colspan="4">'.$row['domicilio'].'</td>';
    $html.='<td></td>';
    $html.='<td style="text-align: right;"><b>COMUNA:</b></td>';
    $html.='<td style="text-align: left;">'.$row['comuna'].'</td>';
    $html.='</tr>';
    $html.='<tr>';
    $html.='<td style="text-align: right;"><b>CIUDAD:</b></td>';
    $html.='<td style="text-align: left;">'.$row['ciudad'].'</td>';
    $html.='<td></td>';
    $html.='<td style="text-align: right;"><b>CELULAR:</b></td>';
    $html.='<td style="text-align: left;">'.$row['celular'].'</td>';
    $html.='<td></td>';
    $html.='<td style="text-align: right;"><b>TELEFONO:</b></td>';
    $html.='<td style="text-align: left;">'.$row['telefono_1'].'</td>';
    $html.='</tr>';
    $html.='<tr>';
    $html.='<td style="text-align: right;"><b>ATT (SR/A):</b></td>';
    $html.='<td style="text-align: left;">'.$row['contacto'].'</td>';
    $html.='<td></td>';
    $html.='<td style="text-align: right;"><b>MAIL:</b></td>';
    $html.='<td style="text-align: left;">'.$row['mail'].'</td>';
    $html.='<td></td>';
    $html.='<td style="text-align: right;"><b>FAX:</b></td>';
    $html.='<td style="text-align: left;">'.$row['fax'].'</td>';
    $html.='</tr>';
    $html.='</table>';

    echo $html;
}



function verificaremail($email){ 
  if (!ereg("^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})$",$email)){ 
      return FALSE; 
  } else { 
       return TRUE; 
  } 
}
?>

