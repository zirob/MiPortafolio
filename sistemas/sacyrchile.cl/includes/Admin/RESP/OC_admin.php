<?php


$msg="";
$action=1;
$num_oc="";

  $sql_emp = "SELECT * FROM empresa WHERE rut_empresa='".$_SESSION['empresa']."'";
    $res_emp = mysql_query($sql_emp,$con);
    $row_emp = mysql_fetch_assoc($res_emp);


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
                    
      $plan_calidad="";
    $prop_econ="";
    $esp_tecnica="";
    

if(isset($_GET['action']) && $_GET['action']==2){
    if(isset($_POST['plan_calidad'])){   $plan_calidad=1;  }else{   $plan_calidad="";  }
    if(isset($_POST['esp_tecnica'])){   $esp_tecnica=1;  }else{   $esp_tecnica="";  }
    if(isset($_POST['prop_economica'])){   $prop_econ=1;  }else{   $prop_econ="";  }
    
    
    
    $id = $_GET['id_oc'];
    $sql_update ="UPDATE cabecera_oc SET id_solicitud_compra='".$_POST['solicitud_oc']."', solicitante='".$_POST['solicitante']."', centro_costos='".$_POST['centro_costo']."' 
                 ,moneda='".$_POST['moneda']."', subtotal='".$_POST['subtotal']."',descuento='".$_POST['descuento']."',valor_neto='".$_POST['valor_neto']."',iva='".$_POST['iva']."'
                 ,total='".$_POST['total_pago']."',forma_de_pago='".$_POST['forma_de_pago']."',fecha_entrega='".$_POST['anio_entrega']."-".$_POST['mes_entrega']."-".$_POST['dia_entrega']."',observaciones='...'
                 ,estado_oc='".$_POST['estado']."', sometido_plan_calidad='".$plan_calidad."', adj_esp_tecnica='".$esp_tecnica."', adj_propuesta_economica='".$prop_econ."'   WHERE id_oc=".$_GET['id_oc'];
         if(mysql_query($sql_update,$con)){
             
             $q = "DELETE * FROM detalle_oc WHERE id_oc=".$_GET['id_oc'];
             mysql_query($q,$con);
             
                    $cant= $_POST['cant'];
                    $unit = $_POST['unit'];
                    $descripcion=$_POST['descripcion'];
                    $precio=$_POST['precio'];
                    $total = $_POST['total'];
                    $n = count($cant);
                    for($i=0;$i<$n;$i++){
                        $sql="INSERT INTO detalle_oc (id_oc,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total, usuario_ingreso,fecha_ingreso)
                            VALUES ('".$id."','".$_POST['solicitud_oc']."','".$_SESSION['empresa']."','".$cant[$i]."','".$unit[$i]."','".$descripcion[$i]."','".$precio[$i]."','".$total[$i]."','".$_SESSION['user']."','".date('Y-m-d H:i:s')."')";
                        mysql_query($sql,$con);
                    }
             
             if(isset($_POST['vb_depto_compras'])){
                            $sql ="UPDATE cabecera_oc SET vb_depto_compras='1'".
                             ", nombre_vb_depto_compras='".$_SESSION['user']."'".
                             ", fecha_vb_depto_compras='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'";  
                            mysql_query($sql,$con);    
                            
                        }else{
                            $sql ="UPDATE cabecera_oc SET vb_depto_compras='null', nombre_vb_depto_compras='null', fecha_vb_depto_compras='null' WHERE id_oc='".$id."'"; 
                            mysql_query($sql,$con);    
                        }
                        
                        if(isset($_POST['vb_jefe_compras']) ){
                            $sql ="UPDATE cabecera_oc SET vb_jefe_compras='1'".
                             ", nombre_vb_jefe_compras='".$_SESSION['user']."'".
                             ", fecha_vb_jefe_compras='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'";  
                             mysql_query($sql,$con);    
                            
                        }else{
                            $sql ="UPDATE cabecera_oc SET vb_jefe_compras='null', nombre_vb_jefe_compras='null', fecha_vb_jefe_compras='null' WHERE id_oc='".$id."'"; 
                            mysql_query($sql,$con);    
                        }
                        
                        if(isset($_POST['vb_jefe_adm'])){
                            $sql ="UPDATE cabecera_oc SET vb_jefe_adm='1'".
                             ", nombre_vb_jefe_adm='".$_SESSION['user']."'".
                             ", fecha_vb_jefe_adm='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'"; 
                             mysql_query($sql,$con);    
                            
                        }else{
                            $sql ="UPDATE cabecera_oc SET vb_jefe_adm='null', nombre_vb_jefe_adm='null', fecha_vb_jefe_adm='null' WHERE id_oc='".$id."'"; 
                            mysql_query($sql,$con);    
                        }
                        
                        if(isset($_POST['vb_jefe_pm']) ){
                            $sql ="UPDATE cabecera_oc SET vb_jefe_parque_maquinaria='1'".
                             ", nombre_vb_jefe_pm='".$_SESSION['user']."'".
                             ", fecha_vb_jefe_pm='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'"; 
                             mysql_query($sql,$con);    
                            
                        }else{
                            $sql ="UPDATE cabecera_oc SET vb_jefe_parque_maquinaria='null', nombre_vb_jefe_pm='null', fecha_vb_jefe_pm='null' WHERE id_oc='".$id."'"; 
                            mysql_query($sql,$con);    
                        }
                        
                        if(isset($_POST['vb_depto_compras']) && isset($_POST['vb_jefe_compras']) && isset($_POST['vb_jefe_adm']) && isset($_POST['vb_jefe_pm'])){
                            $sql ="UPDATE cabecera_oc SET estado_oc='4' WHERE id_oc='".$id."'"; 
                             mysql_query($sql,$con);    
                        }
                        
                    if($total<50000){
                        $sql ="UPDATE cabecera_oc SET vb_depto_compras='1'".
                             ", nombre_vb_depto_compras='Automatico', fecha_vb_depto_compras='".date('Y-m-d H:i:s')."', vb_jefe_compras='1'".
                             ", nombre_vb_jefe_compras='Automatico', fecha_vb_jefe_compras='".date('Y-m-d H:i:s')."', vb_jefe_adm='1'".
                             ", nombre_vb_jefe_adm='Automatico', fecha_vb_jefe_adm='".date('Y-m-d H:i:s')."', vb_jefe_parque_maquinaria='1'".
                             ", nombre_vb_jefe_pm='Automatico', fecha_vb_jefe_pm='".date('Y-m-d H:i:s')."'  WHERE id_oc='".$id."'";
                        mysql_query($sql,$con);
                    }
             
             
             
             $sql = "SELECT * FROM cabecera_oc WHERE id_oc=".$id;
                    $res = mysql_query($res,$con);
                    $row = mysql_fetch_assoc($res);

                    $id_solic_oc = $row['id_solicitud_compra'];
                    $concepto = $row['concepto'];
                    $solicitante = $row['solicitante'];
                    $centro_costo = $row['centro_costos'];
                    $moneda = $row['moneda'];
                    $subtotal = $row['subtotal'];
                    $descuento=$row['descuento'];
                    $valor_neto=$row['valor_neto'];
                    $iva = $row['iva'];
                    $total=$row['total'];
                    $forma_de_pago=$row['forma_de_pago'];
                    $fec = explode($row['fecha_entrega'],"-");
                    $aux = explode(" ",$fec[2]);
                    $dia = $aux[0];
                    $mes = $fec[1];
                    $anio = $fec[0];
                    $estado = $row['estado_oc'];
                    $observacion = $row['observaciones'];
                    $proveedor=$row['rut_proveedor'];
                    
                    $vb_dc = $row['vb_depto_compras'];
                    $vb_jc = $row['vb_jefe_compras'];
                    $vb_j_adm = $row['vb_jefe_adm'];
                    $vb_j_pm = $row['vb_jefe_parque_maquinaria'];
                    
                      $plan_calidad=$row['sometido_plan_calidad'];
                        $prop_econ=$row['adj_esp_tecnica'];
                        $esp_tecnica=$row['adj_propuesta_economica'];
                    
                    $action="2&id_oc=".$_GET['id_oc'];
         }
    
}

if(isset($_GET['action'])  && $_GET['action']==1  && $_POST['solicitud_oc']!=""){
    $plan_calidad="";
    $prop_econ="";
    $esp_tecnica="";
    
    if(isset($_POST['plan_calidad'])){   $plan_calidad=1;  }else{   $plan_calidad="";  }
    if(isset($_POST['esp_tecnica'])){   $esp_tecnica=1;  }else{   $esp_tecnica="";  }
    if(isset($_POST['prop_economica'])){   $prop_econ=1;  }else{   $prop_econ="";  }
    if(isset($_POST['vb_depto_compras']) && $_POST['vb_depto_compras']!="" && isset($_POST['vb_jefe_compras']) && $_POST['vb_jefe_compras']!="" && isset($_POST['vb_jefe_adm']) && $_POST['vb_jefe_adm']!="" && isset($_POST['vb_jefe_pm']) && $_POST['vb_jefe_pm']!=""){
        $estado = 1;
    }else{
        $estado = 2;
    }
    
    $sql_ins="INSERT INTO cabecera_oc (id_solicitud_compra,rut_empresa,fecha_oc,solicitante,concepto,centro_costos,moneda,
                subtotal,descuento,valor_neto,iva,total,forma_de_pago,fecha_entrega,observaciones,estado_oc,
                usuario_ingreso, fecha_ingreso,rut_proveedor,id_archivo,sometido_plan_calidad,adj_esp_tecnica,adj_propuesta_economica)
                VALUES ('".$_POST['solicitud_oc']."','".$_SESSION['empresa']."','".date('Y-m-d')."','".$_POST['solicitante']."','".$_POST['concepto']."','".$_POST['centro_costo']."','".$_POST['moneda']."'
                    ,'".$_POST['subtotal']."','".$_POST['descuento']."','".$_POST['valor_neto']."','".$_POST['iva']."','".$_POST['total_pago']."','".$_POST['forma_de_pago']."','".$_POST['anio_entrega']."-".$_POST['mes_entrega']."-".$_POST['dia_entrega']."','...'
                   ,'".$estado."','".$_SESSION['user']."','".date('Y-m-d H:i:s')."','".$_POST['proveedor']."','".$_POST['archivo']."','".$plan_calidad."','".$esp_tecnica."','".$prop_econ."')";
                
         if(mysql_query($sql_ins,$con)){
             $rs = mysql_query("SELECT @@identity AS id");
            
             if ($row = mysql_fetch_row($rs)) {
                    $id = trim($row[0]);
                    
                    $cant= $_POST['cant'];
                    $unit = $_POST['unit'];
                    $descripcion=$_POST['descripcion'];
                    $precio=$_POST['precio'];
                    $total = $_POST['total'];
                    $n = count($cant);
                    for($i=0;$i<$n;$i++){
                        $sql="INSERT INTO detalle_oc (id_oc,id_solicitud_compra,rut_empresa,cantidad,unidad,descripcion,precio,total, usuario_ingreso,fecha_ingreso)
                            VALUES ('".$id."','".$_POST['solicitud_oc']."','".$_SESSION['empresa']."','".$cant[$i]."','".$unit[$i]."','".$descripcion[$i]."','".$precio[$i]."','".$total[$i]."','".$_SESSION['user']."','".date('Y-m-d H:i:s')."')";
                        mysql_query($sql,$con);
                    }
                    
                    
                    $sql = "SELECT * FROM cabecera_oc WHERE id_oc=".$id;
                    $res = mysql_query($sql,$con);
                    $row = mysql_fetch_assoc($res);

                    $id_solic_oc = $row['id_solicitud_compra'];
                    $concepto = $row['concepto'];
                    $solicitante = $row['solicitante'];
                    $centro_costo = $row['centro_costos'];
                    $moneda = $row['moneda'];
                    $subtotal = $row['subtotal'];
                    $descuento=$row['descuento'];
                    $valor_neto=$row['valor_neto'];
                    $iva = $row['iva'];
                    $total=$row['total'];
                    $forma_de_pago=$row['forma_de_pago'];
                    $fec = explode(" ",$row['fecha_entrega']);
                    $aux = explode("-",$fec[0]);
                    $dia = $aux[2];
                    $mes = $aux[1];
                    $anio = $aux[0];
                    $estado = $row['estado_oc'];
                    $observacion = $row['observaciones'];
                    $proveedor=$row['rut_proveedor'];
                    
                    $vb_dc = $row['vb_depto_compras'];
                    $vb_jc = $row['vb_jefe_compras'];
                    $vb_j_adm = $row['vb_jefe_adm'];
                    $vb_j_pm = $row['vb_jefe_parque_maquinaria'];
                    
                    $plan_calidad=$row['sometido_plan_calidad'];
                        $prop_econ=$row['adj_esp_tecnica'];
                        $esp_tecnica=$row['adj_propuesta_economica'];
                    
                    
                    $action="2&id_oc=".$id;
             $nueva =1;
                    
                    
                        
                        if(isset($_POST['vb_depto_compras'])){
                            $sql ="UPDATE cabecera_oc SET vb_depto_compras='1'".
                             ", nombre_vb_depto_compras='".$_SESSION['user']."'".
                             ", fecha_vb_depto_compras='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'";  
                             mysql_query($sql,$con);    
                            
                        }
                        
                        if(isset($_POST['vb_jefe_compras']) ){
                            $sql ="UPDATE cabecera_oc SET vb_jefe_compras='1'".
                             ", nombre_vb_jefe_compras='".$_SESSION['user']."'".
                             ", fecha_vb_jefe_compras='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'";  
                             mysql_query($sql,$con);    
                            
                        }
                        
                        if(isset($_POST['vb_jefe_adm'])){
                            $sql ="UPDATE cabecera_oc SET vb_jefe_adm='1'".
                             ", nombre_vb_jefe_adm='".$_SESSION['user']."'".
                             ", fecha_vb_jefe_adm='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'"; 
                             mysql_query($sql,$con);    
                            
                        }
                        if(isset($_POST['vb_jefe_pm']) ){
                            $sql ="UPDATE cabecera_oc SET vb_jefe_parque_maquinaria='1'".
                             ", nombre_vb_jefe_pm='".$_SESSION['user']."'".
                             ", fecha_vb_jefe_pm='".date('Y-m-d H:i:s')."' WHERE id_oc='".$id."'"; 
                             mysql_query($sql,$con);    
                            
                        }
                        if(isset($_POST['vb_depto_compras']) && isset($_POST['vb_jefe_compras']) && isset($_POST['vb_jefe_adm']) && isset($_POST['vb_jefe_pm'])){
                            $sql ="UPDATE cabecera_oc SET estado_oc='4' WHERE id_oc='".$id."'"; 
                             mysql_query($sql,$con);    
                        }
                        
                    if($total<50000){
                        $sql ="UPDATE cabecera_oc SET vb_depto_compras='1'".
                             ", nombre_vb_depto_compras='Automatico', fecha_vb_depto_compras='".date('Y-m-d H:i:s')."', vb_jefe_compras='1'".
                             ", nombre_vb_jefe_compras='Automatico', fecha_vb_jefe_compras='".date('Y-m-d H:i:s')."', vb_jefe_adm='1'".
                             ", nombre_vb_jefe_adm='Automatico', fecha_vb_jefe_adm='".date('Y-m-d H:i:s')."', vb_jefe_parque_maquinaria='1'".
                             ", nombre_vb_jefe_pm='Automatico', fecha_vb_jefe_pm='".date('Y-m-d H:i:s')."'  WHERE id_oc='".$id."'";
                        mysql_query($sql,$con);
                    }
             
                     $msg ="Se ha Agregado correctamente la Solicitud de Compra";
            }else{
                $error="Ha ocurrido un error al grabar datos intente mas tarde";
        } 
             
           
        
    
    }else{
        $error="Ha ocurrido un error al grabar datos intente mas tarde";
    }
}


if(isset($_GET['id_oc']) && $_GET['id_oc']!=""){
    $sql = "SELECT * FROM cabecera_oc WHERE id_oc=".$_GET['id_oc'];
    $res = mysql_query($sql,$con);
    $row = mysql_fetch_assoc($res);
    $num_oc=$_GET['id_oc'];
    $id_solic_oc = $row['id_solicitud_compra'];
    $concepto = $row['concepto'];
    $solicitante = $row['solicitante'];
    $centro_costo = $row['centro_costos'];
    $moneda = $row['moneda'];
    $subtotal = $row['subtotal'];
    $descuento=$row['descuento'];
    $valor_neto=$row['valor_neto'];
    $iva = $row['iva'];
    $total=$row['total'];
    $forma_de_pago=$row['forma_de_pago'];
    $fec = explode("-",$row['fecha_entrega']);
    $aux = explode(" ",$fec[2]);
    $dia = $aux[0];
    $mes = $fec[1];
    $anio = $fec[0];
    $proveedor=$row['rut_proveedor'];
    $estado = $row['estado_oc'];
    $observacion = $row['observaciones'];
    $action="2&id_oc=".$_GET['id_oc'];
    
    $vb_dc = $row['vb_depto_compras'];
    $vb_n_dc = $row['nombre_vb_depto_compras'];
    $vb_jc = $row['vb_jefe_compras'];
    $vb_n_jc = $row['nombre_vb_jefe_compras'];
    $vb_j_adm = $row['vb_jefe_adm'];
    $vb_nj_adm = $row['nombre_vb_jefe_adm'];
    $vb_j_pm = $row['vb_jefe_parque_maquinaria'];
    $vb_nj_pm = $row['nombre_vb_jefe_pm'];
                    
    $plan_calidad=$row['sometido_plan_calidad'];
                        $prop_econ=$row['adj_esp_tecnica'];
                        $esp_tecnica=$row['adj_propuesta_economica'];
}
if($action==1){
    $s = "SELECT max(id_oc) as ultimo FROM cabecera_oc LIMIT 1";
    $r = mysql_query($s,$con);
    $rw = mysql_fetch_assoc($r);
    $num_oc = (int)$rw['ultimo']+1;
    
}



    if(isset($error) && !empty($error)){
        ?>

<div id="main-error">
    <?php echo $error;?>
</div>

<?
    }elseif($msg){
?>
<div id="main-ok">
    <?php echo $msg;?>
</div>
<?
    }
?>
<table style="width:1000px;" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2"> <a href="?cat=2&sec=15"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Ordenes de Compra"></a></td></tr>
    
    <tr>
</table>
<form action="?cat=2&sec=16&action=<?=$action?>" method="POST">
<table style="width:1000px;" cellpadding="3" cellspacing="4">
            <tr id="cabecera_1" style="height: 100px;">
                <td colspan="2" width="100px" style="text-align: center;"><img src="img/sacyr.png" border="0" width="150px"></td>
                <td colspan="2"></td>
                <td colspan="2" width="100px">
                    <table width="100%" style="text-align:center;">
                        <tr><td><?=$row_emp['direccion'];?></td></tr>
                        <tr><td><?="Comuna ".$row_emp['comuna']."   ".$row_emp['ciudad'];?></td></tr>
                        <tr><td><?=$row_emp['telefono_1'];?></td></tr>
                        <tr><td><?=$row_emp['fax'];?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">
                    <table style="margin-right: 0px;float:right;">
                        <tr><td style="text-align: right;"><b>Solicitante</b></td></tr>
                        <tr><td style="text-align: right;"><b>Concepto:</b></td></tr>
                        <tr><td style="text-align: right;"><b>Centro Costo:</b> </td></tr>
                          <tr><td style="text-align: right;"><b>Solicitud Compra:</b> </td></tr>
                          <tr><td>&nbsp;</td></tr>
                    </table>
                </td>
                <td colspan="2" style="text-align: left;">
                    <table>
                        <tr><td><input class="fu" type="text" name="solicitante" value="<?=$solicitante;?>"></td></tr>
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
                        <tr>
                            <td >
                                <select name="solicitud_oc" onchange="solic_oc_select(this.value)">
                                    <option value="0">---</option>
                                <?   $s = "SELECT id_solicitud_compra, descripcion_solicitud FROM solicitudes_compra WHERE estado=3 and rut_empresa='".$_SESSION['empresa']."'";
                               $r = mysql_query($s,$con);
                                if(mysql_num_rows($r)!= NULL){
                                    while($rw = mysql_fetch_assoc($r)){?>
                                  
                                    <option value="<?=$rw['id_solicitud_compra'];?>"><?=$rw['descripcion_solicitud'];?></option>
                                <?    }
                                }   
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td id="link-solic-oc"></td>
                        </tr>
                    </table>
                </td>
                <td colspan="2" width="100px;" >
                    <table width="100%" border="1" cellspacing="1" cellpadding="1">
                        <tr>
                            <td width="50%" style="height: 50px;text-align: center; font-size: 20px;"><?="N°".$num_oc;?></td>
                            <td width="50%" style="height: 50px;text-align: center;font-size: 20px;"><?=date('d-m-Y');?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table width="100%"  style="width:100%; border:1px solid;" cellspacing="3" cellpadding="3" >
                        <tr>
                            <td height="100%;" id="proveedor_selec"><b>Proveedor:</b>
                               <select name="proveedor" onchange="selecciona_proveedor(this.value)">
                                    <option value=""> --- </option>
                                   <? $sql = "SELECT * FROM proveedores WHERE rut_empresa = '".$_SESSION['empresa']."' Order By razon_social";
                                       $res = mysql_query($sql,$con);
                                       while($row = mysql_fetch_assoc($res)){
                                           ?>
                                   <option value="<?=$row['rut_proveedor'];?>" <?if($row['rut_proveedor']==$proveedor){ echo "SELECTED";}?>><?=$row['razon_social'];?></option>

                                    <?   }      ?>
                               </select><br />
                               <? if($proveedor!=""){
                                        $sql ="SELECT * FROM proveedores WHERE rut_proveedor='".$proveedor."'";
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
                               
                               } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td id="detalle_Orden" colspan="6">
            <table style="width:100%; border:1px solid;" id="detalle-prov" class="detalle-oc"  cellpadding="3" cellspacing="4">
                <?
                if(isset($_GET['id_oc'])){
                $s = "SELECT * FROM detalle_oc WHERE id_oc=".$_GET['id_oc'];
                $result = mysql_query($s,$con);
                $can="";
                if(mysql_num_rows($result)!=NULL){
                    $can = mysql_num_rows($result);
                }
                ?>
                <tr><td colspan="7"><a href="javascript:agregar_detalle_oc(<?if($can!=""){ echo $can; }else{echo "1";}?>);" id="agregar"><img src="img/add1.png" width="20px" height="20px"></a>
                      <input type="button" value="Calcular" style="float:right;" onclick="calcular_cambios_ot(<?=$can;?>);">
                    </td></tr>
                <tr>
                    
                    <td><b>Cantidad</b></td>
                    <td><b>Unidad</b></td>
                    <td><b>Descripci&oacute;n</b></td>
                    <td><b>Precio</b></td>
                    <td><b>Total</b></td>
                    <td><b>Eliminar</b></td>
                </tr>
                <? if($can>0){ 
                    $i=1;
                    while($rew = mysql_fetch_assoc($result)){
                    
                    ?>
                     <tr class="det" id="det_<?=$i;?>">
                    
                    <td><input class="fu nume" type="text" name="cant[]" id="cant_<?=$i;?>" size="4" onchange="calculo_total_detalle(<?=$i;?>);" value="<?=$rew['cantidad'];?>"></td>
                    <td><input class="fu nume" type="text" name="unit[]"  id="unit_<?=$i;?>" size="4" value="<?=$rew['unidad'];?>"></td>
                    <td><input class="fu nume" type="text" name="descripcion[]"  id="descripcion_<?=$i;?>"size="30" value="<?=$rew['descripcion'];?>"></td>
                    <td><input class="fu nume" type="text" name="precio[]"  id="precio_<?=$i;?>" size="9" onchange="calculo_total_detalle(<?=$i;?>,<?if(isset($_GET['id_oc'])){ echo2;}else{echo 1;}?>);"  value="<?=$rew['precio'];?>"></td>
                    <td style="background-color: #fff;"><input class="fu nume" type="text" id="total_<?=$i;?>" name="total[]"  value="<?=$rew['total'];?>" readonly="readonly"  size="10"></td>
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
                    <td><b>Cantidad</b></td>
                    <td><b>Unidad</b></td>
                    <td><b>Descripci&oacute;n</b></td>
                    <td><b>Precio</b></td>
                    <td><b>Total</b></td>
                    <td><b>Eliminar</b></td>
                </tr>
                 <tr id="det_1" class="det" >
                    
                    <td><input class="fu nume" type="text" name="cant[]" id="cant_1" size="4" onchange="calculo_total_detalle(1);"></td>
                    <td><input class="fu nume" type="text" name="unit[]"  id="unit_1" size="4"></td>
                    <td><input class="fu nume" type="text" name="descripcion[]"  id="descripcion_1"size="30"></td>
                    <td><input class="fu nume" type="text" name="precio[]"  id="precio_1" size="9" onchange="calculo_total_detalle(1,1);"></td>
                    <td ><input class="fu nume" type="text" id="total_1" name="total[]" value="" readonly="readonly"  size="10"  style="background-color: #fff;color:#000;"></td>
                    <td></td>
                </tr>
                    
                <?}?>
            </table>
        </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><b>Moneda</b></td>
                <td colspan="2"><input class="fo" type="text" name="moneda" value="<?=$moneda;?>"></td>
                <td colspan="2"></td>
            </tr>
             <tr>
                <td colspan="2" rowspan="2"></td>
                <td colspan="2" rowspan="2"></td>
                <td colspan="2" rowspan="3">
                    <table>
                        <tr>
                            <td><b>Subtotal</b></td>
                            <td><input class="fo" type="text" id="subtotal" name="subtotal" value="<?=$subtotal;?>" readonly="readonly"  style="background-color: #fff;color:#000;"></td>
                        </tr>
                        <tr>
                            <td><b>Descuento</b></td>
                            <td><input class="fo nume" type="text" name="descuento" value="<?=$descuento;?>" id="descuento_oc" onchange="calculo_valor_neto();"></td>
                        </tr>
                        <tr>
                            <td><b>Valor Neto</b></td>
                            <td><input class="fo nume" type="text" id="valor_neto_oc" name="valor_neto" readonly="readonly" value="<?=$valor_neto;?>"  style="background-color: #fff;color:#000;"></td>
                        </tr>
                        <tr>
                            <td><b>I.V.A %</b></td>
                            <td><input class="fo nume" type="text" name="iva" id="iva" onchange="calculo_total();" value="<?=$iva;?>"></td>
                        </tr>
                        <tr>
                            <td><b>Total</b></td>
                            <td><input class="fo" type="text" id="total_pago" name="total_pago" readonly="readonly" value="<?=$total?>"  style="background-color: #fff;color:#000;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="height: 60px;">
            </tr>
            <tr><td colspan="2" style="text-align: right;"><b>Forma Pago</b></td>
                <td colspan="2">
                    <textarea cols="30" rows="3" name="forma_de_pago"><?=$forma_de_pago;?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: center;font-size: 11px;border: 1px solid;">
                    <p>De conformidad a lo prescrito en la Ley Nº 19.983 del Año 2007, que REGULA LA TRANSFERENCIA Y OTORGA MERITO EJECUTIVO A COPIA DE LA</p>
                    <p>FACTURA, las partes acuerdan que el plazo indicado en el Número 3 del Artículo 2º de dicha Ley, para reclamar el contenido de las facturas presentadas</p>
                    <p>por el Proveedor, Subcontratista, Contratista o como se le tenga denomidado en el Contrato o en la <b>Órden de Compra, será de 30 DÍAS.</b></p>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table  width="100%">
                        <tr>
                            <td><b>Fecha Entrega: </b></td>
                            <td style="padding-top:4px;"><input class="fu" type="text" name="dia_entrega" size="2" value="<?=$dia;?>" > - <input class="fu" type="text" name="mes_entrega" size="2" value="<?=$mes;?>"> - <input class="fu" type="text" name="anio_entrega" size="4" value="<?=$anio;?>"></td>
                            <td> <p style="font-size: 11px;">(DD-MM-YYYY)</p></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" width="450px" style="font-size: 10px;padding-top:15px; padding-bottom: 15px;border: 1px solid;">
                                <b>De acuerdo a lo conversado, rogamos emitan la factura a: SACYR CHILE S.A.<br/>AV VITACURA Nº 2939 OFICINA 1102 Las Condes, Santiago   R.U.T. 96.786.880 - 9<br/>ADJUNTAR COPIA DE ORDEN DE COMPRA A LA FACTURA</b>
                            </td>
                            <td width="100px"></td>
                            <td colspan="2" width="450px">
                                <table>
                                    <tr>
                                        <td colspan="2" width="225px" style="padding-top:15px;"><b>Sometido al Plan de Calidad</b><input type="checkbox" name="plan_calidad" <?if($plan_calidad==1){echo "checked";}?> style="float:right;"></td>
                                       
                                    </tr>
                                    <tr>
                                        <td colspan="2" ><b>Adjunta Especificación Técnica</b><input type="checkbox" name="esp_tecnica" <?if($esp_tecnica==1){echo "checked";}?> style="float:right;"></td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan="2" ><b>Adjunta Propuesta Económica<b><input type="checkbox" name="prop_economica" <input type="checkbox" <?if($prop_econ==1){echo "checked";}?> style="float:right;"></td>
                                         </tr>
                                </table>
                                    
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="6" style="height: 30px;"></td>
                        </tr>
                        <tr>
                            <td style="height: 100px; width: 200px;border: 1px solid;">
                                <b>VB Depto Compras</b><br/><input type="checkbox" name="vb_depto_compras" <?if($vb_dc==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 200px;border: 1px solid;">
                                <b>VB Jefe Compras</b><br/><input type="checkbox" name="vb_jefe_compras" <?if($vb_jc==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 200px;border: 1px solid;">
                                <b>VB Jefe ADM</b><br/><input type="checkbox" name="vb_jefe_adm" <?if($vb_j_adm==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 200px;border: 1px solid;">
                                <b>VB Jefe PM</b><br/><input type="checkbox" name="vb_jefe_pm" <?if($vb_j_pm==1){ echo "checked";}?>>
                            </td>
                            <td style="height: 100px; width: 100px"></td>
                            <td style="height: 100px; width: 100px"><b>Estado Orden de Compra</b><br />
                                <select name="estado" <?if($estado==4 || !isset($_GET['id_oc'])){ echo "DISABLED";}?>>
                                    <option value="1" <?if($estado==1 || !isset($_GET['id_oc'])){ echo "SELECTED";}?>>Abierta</option>
                                    <option value="2" <?if($estado==2){ echo "SELECTED";}?>>En Espera de Informacion</option>
                                    <option value="3" <?if($estado==3){ echo "SELECTED";}?>>Cerrada</option>
                                    <option value="4" <?if($estado==4){ echo "SELECTED";}?>>Aprobada</option>
                                </select>
                            
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="6" style="height: 30px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="text-align: center;font-size: 11px;border: 1px solid;">
                                <p><b>Horario Recepción de Materiales:   Lunes a Jueves de: 8:30 a 12:30 y de 14:30 a 18:00;   Viernes de: 8:30 a 12:00 Hrs.</b></p>
                            </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
            <tr><td  colspan="6"><input type="submit" value="GRABAR Orden de Compra"></td></tr>
        </table>
</form>    



