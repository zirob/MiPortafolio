<?
function list_proveedores($id=null){
    $sql="";
    if($id==null){
        $sql = "SELECT * FROM proveedores ORDER BY razon_social";
    }else{
        $sql = "SELECT * FROM proveedores WHERE rut_proveedor='".$id."'";
    }
    
    $r = mysql_query($sql,$con);
    while($row = mysql_fetch_assoc($r)){
        $result[]['prov_rs'] = $row['razon_social'];
        $result[]['prov_rut'] = $row['rut_proveedor'];
        $result[]['prov_domicilio'] = $row['domicilio'];
        $result[]['prov_comuna'] = $row['comuna'];
        $result[]['prov_ciudad'] = $row['ciudad'];
        $result[]['prov_telefono1'] = $row['telefono_1'];
        $result[]['prov_telefono2'] = $row['telefono_2'];
        $result[]['prov_fax'] = $row['fax'];
        $result[]['prov_celular'] = $row['celular'];
        $result[]['prov_email'] = $row['mail'];
        $result[]['prov_obs'] = $row['observaciones'];
        $result[]['prov_usuario'] = $row['usuario_ingreso'];
        $result[]['prov_fecha'] = $row['fecha_ingreso'];
        
    }
    return $result;
}

function admin_proveedores($sql, $tipo){
    $r = mysql_query($sql,$con);
    
    if($tipo==1){
        $msg="Se agrego correctamente el Proveedor";
    }else{
        $msg="Se actualizo correctamente el Proveedor";
    }
    return $msg;
}

/***** Funciones para Centro de Costos ***/

function list_cc($id=null,$id_empresa=null){
    if($id!=null){
        $sql="SELECT * FROM centros_costos WHERE id_cc='".$id."'";
    }
    if($id_empresa!=null){
        $sql ="SELECT * FROM centros_costos WHERE rut_empresa='".$id_empresa."'";
    }
    
     $r = mysql_query($sql,$con);
    while($row = mysql_fetch_assoc($r)){
        $result[]['codigo_cc'] = $row['codigo_cc'];
        $result[]['descripcion_cc'] = $row['descripcion_cc'];
        $result[]['cc_usuario'] = $row['usuario_ingreso'];
        $result[]['cc_fecha'] = $row['fecha_ingreso'];
    }
    return $result;
}


function admin_cc($sql,$tipo){
    $r = mysql_query($sql,$con);
    if($tipo==1){
        $msg="Se agrego correctamente el Centro de Costo";
    }else{
        $msg="Se actualizo correctamente el Centro de Costo";
    }
    return $msg;
}

/****** Solicitudes de Compra *****/

function admin_solic_compra($sql){
    $r = mysql_query($sql,$con);
    // se agregan registros de archivos
    return $r;
}

function ins_archivos($sql,$id_cc){
    $r = mysql_query($sql,$con);
}

/************ Convertir Solicitud de Compra en Orden de Compra  ************/




/************ VB Ordenes de Compra *****************/

function VB_OC($id_oc,$tipo_vb,$usuario,$vb){
    $fecha_ing = date("d/m/Y H:i:s");
    
    
    if($tipo_vb==1){ // VB Depto Compras
        $sql = "UPDATE cabeceras_oc SET vb_depto_compras='".$vb."',nombre_vb_depto_compras='".$usuario."',fecha_vb_depto_compras='".$fecha_ing."' WHERE id_oc='".$id_oc."'";    
    }
    if($tipo_vb==2){ // VB Jefe Compras
        $sql = "UPDATE cabeceras_oc SET vb_jefe_compras='".$vb."',nombre_vb_jefe_compras='".$usuario."',fecha_vb_jefe_compras='".$fecha_ing."' WHERE id_oc='".$id_oc."'";    
    }
    if($tipo_vb==3){ // VB Jefe ADM
        $sql = "UPDATE cabeceras_oc SET vb_jefe_adm='".$vb."',nombre_vb_jefe_adm='".$usuario."',fecha_vb_jefe_adm='".$fecha_ing."' WHERE id_oc='".$id_oc."'";    
    }
    if($tipo_vb==4){ // VB Parque Maquinaria
        $sql = "UPDATE cabeceras_oc SET vb_jefe_pm='".$vb."',nombre_vb_jefe_pm='".$usuario."',fecha_vb_jefe_pm='".$fecha_ing."' WHERE id_oc='".$id_oc."'";    
    }
    
    $r = mysql_query($sql,$con);
}



function estado_accion($num){
    if($num==1){
        $msg="Aprobada";
    }
    if($num==2){
        $msg="Pendiente";
    }
    if($num==3){
        $msg="Rechazada";
    }
    //estado 4 es eliminado no aparecen de cara al usuario
    
    return $msg;
}

?>
