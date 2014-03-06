<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>
<?php
// var_dump($_POST);
//var_dump($_GET);

$action=1;
$_SESSION['id_solic']="";
$id_ot = "";
$descripcion= "";
$tipo=3;
$observaciones="";
$estado=6;
$concepto=4;
$archivos="";
$nueva="";
$msg="";
$sql_ins="";

//Elimina (Anula) registro
if(isset($_GET['elim']) && $_GET['elim']==1){
    $s = "UPDATE archivos SET estado_archivo='2' WHERE id_solicitud='".$_GET['id_solic']."' AND id_archivo='".$_GET["id_archivo"]."' AND rut_empresa='".$_SESSION["empresa"]."' ";
    if(mysql_query($s,$con)){
        $mensaje="Archivo ha sido Eliminado";

        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'archivos', '".$_GET['id_solic']."', '3'";
        $sql_even.= ", 'update:estado=2', '".$_SERVER['REMOTE_ADDR']."', 'anula solicitud de compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
    }
}

$mostrar=0;

// Validaciones 
if(!empty($_POST['accion'])){
    
    if(empty($_POST["vb_solic_compras"])){
        $error=0;
        if(empty($_POST['descripcion_solicitud'])){
            $error=1;
            $mensaje="Debe Ingresar una descripción para la Solicitud de Compra";
        } 
        if(empty($_POST['solicitante'])){
            $error=1;
            $mensaje="Debe Ingresar el Solicitante ";
        } 
        if($_POST['tipo_solicitud'] == 3){
            $error=1;
            $mensaje="Debe seleccionar el Tipo de la Solicitud de Compra";
         }   

        if(empty($_POST['concepto'])){
            $error=1;
            $mensaje="Debe seleccionar el Concepto de la Solicitud de Compra";

        }

        if(empty($_POST['estado'])){
            $error=1;
            $mensaje="Debe seleccionar un Estado de Solicitud de Compra valido";
        }    

        if(empty($_POST['tipo_solicitud_compra'])){
            $error=1;
            $mensaje="Debe seleccionar un Tipo de Solicitud de Compra";
        } 
        if(empty($_POST['material'])){
            $error=1;
            $mensaje="Debe seleccionar un Tipo de Material";
        }
        if(empty($_POST['prioridad'])){
            $error=1;
            $mensaje="Debe seleccionar un nivel de Prioridad";
        }
        if(empty($_POST['origen'])){
            $error=1;
            $mensaje="Debe seleccionar un origen de la Solicitud de Compra";
        }
        if(empty($_POST['centros_costos'])){
            $error=1;
            $mensaje="Debe seleccionar un Centro de Costo";
        }
        if(empty($_POST['tipo_solicitud'])){
            $error=1;
            $mensaje="Debe seleccionar Tipo";
        }
        
        $sitem=0;
        for ($i = 1; $i <= $_POST["num_prd"]; $i++){

            if($_POST["EstadoItem_".$i] == 1){
                if(empty($_POST["descripcion_".$i]) OR empty($_POST["unidad_medida_".$i]) OR empty($_POST["cantidad_".$i])){
                    $sitem++;   
                }
            } 
        }
        if($sitem != 0){
            $error=1;
            $mensaje="Debe Ingresar al menos un Item de Detalle de la Solicitud";
        }
    
    }else{
        if(empty($_POST['estado'])){
            $error=1;
            $mensaje="Debe seleccionar un Estado de Solicitud de Compra valido";
        } 
    }

}   


// Rescata los datos

if(!empty($_GET['id_solic']) and empty($_POST['primera']))
{
    $sql="SELECT  * FROM  solicitudes_compra WHERE id_solicitud_compra='".$_GET['id_solic']."' AND rut_empresa='".$_SESSION["empresa"]."' ";
    $rec=mysql_query($sql);
    $row=mysql_fetch_array($rec);
     $_POST=$row;
     $_POST["centros_costos"]=$row["id_cc"];
     $_POST["id_solicitud_compra"]=$row["id_solicitud_compra"];
     $_POST["fecha_autoriza"] = date('d-m-Y h:m:s', strtotime($row["fecha_autoriza"]));

     $s=0;

     //  Si tiene estado autorizada checkea VºBº Compras
     if($row["estado"]==3) $_POST['vb_solic_compras']=1;

     $sql_soldet = "SELECT  * FROM  detalles_sc WHERE id_solicitud_compra='".$row["id_solicitud_compra"]."' AND rut_empresa='".$_SESSION["empresa"]."' ";
     $rec_soldet=mysql_query($sql_soldet);
    
     
     while($row_soldet=mysql_fetch_array($rec_soldet)){
        $s++;
        $_POST['id_det_sc_'.$s] = $row_soldet["id_det_sc"];
        $_POST['cantidad_'.$s] = $row_soldet["cantidad_det_sc"];
        $_POST['unidad_medida_'.$s] = $row_soldet["unidad_det_sc"];
        $_POST['descripcion_'.$s] =  $row_soldet["descripcion_det_sc"];
        $_POST["EstadoItem_".$s] = $row_soldet["estado_det_sc"];


     }

     $count = "SELECT  count(id_det_sc) AS count_det FROM  detalles_sc WHERE id_solicitud_compra='".$row["id_solicitud_compra"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
     $res_count=mysql_query($count);
     $row_count=mysql_fetch_array($res_count);
     $_POST['num_prd'] = $row_count["count_det"];
   
     // Rescata firma 
     $sql_firma="SELECT  * FROM  usuarios WHERE usuario='".$_SESSION['user']."' AND rut_empresa='".$_SESSION["empresa"]."'";
     $rec_firma=mysql_query($sql_firma);
     $row_firma=mysql_fetch_array($rec_firma);
     $_POST['archivo_firma']=$row_firma['nombre_arch_fd'];

     $sql_com = "SELECT * FROM archivos WHERE id_solicitud='".$_POST['id_solicitud_compra']."' AND estado_archivo='1' AND rut_empresa='".$_SESSION["empresa"]."'";
     $res_com = mysql_query($sql_com,$con);
     $row_com = mysql_fetch_array($res_com);
     $_POST["archivo_aprovado"] = $row_com["id_archivo"];

     // Nombre usuario que autoriza
     $sql_nom="SELECT  * FROM  usuarios WHERE usuario='".$_POST['usuario_autoriza']."' AND rut_empresa='".$_SESSION["empresa"]."'";
     $res_nom=mysql_query($sql_nom);
     $row_nom=mysql_fetch_array($res_nom);
     $_POST["usuario_autoriza"] = $row_nom["nombre"];
}  


//Subo el archivo
if ($_POST["action"] == "upload") 
{


    $nombre_archivo = $_FILES['archivo']['name']; 
    // echo "<br>nombre_archivo : ".$_FILES['archivo']['name'];
    $tipo_archivo = $_FILES['archivo']['type']; 
    $tamano_archivo = $_FILES['archivo']['size'];

    // Obtiene extension
    $ext = explode(".",$nombre_archivo);
    $nombre = $ext[0];
    $num = count($ext)-1;



    /*if(!(strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "png")))
    {
        $error=1;
        $mensaje = "La extensión no es correcta";
    }elseif($tamano_archivo > 100000){
        $error=1;
        $mensaje = "Tamaño del archivo no es correcta.";
    }else{*/
        $destino="documentos/".$_FILES['archivo']['name'];
        $fecha=date("Y-m-d H:i:s");
        
        $sql.=" UPDATE usuarios SET";
        $sql.=" nombre_arch_fd='".$_FILES['archivo']['name']."'";
        $sql.=",ruta_arch_fd='".$destino."'";
        $sql.=",ext_arch_fd='".$_FILES['archivo']['type']."'";
        $sql.=",user_insert_fd='".$_SESSION['user']."'";
        $sql.=",fecha_insert_fd='".$fecha."'";
        $sql.=" WHERE usuario='".$_GET['user']."'";
        mysql_query( $sql);
        
        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'usuarios', '".$_GET['user']."', '3'";
        $sql_even.= ", 'UPDATE:nombre_arch_fd=".$_FILES['archivo']['name'].",ruta_arch_fd=".$destino.",ext_arch_fd=".$_FILES['archivo']['type'].",user_insert_fd=".$_SESSION['user'].",fecha_insert_fd=".$fecha."', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion archivo', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);

        if(move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) {
                $sql_archivo = "INSERT INTO archivos (id_solicitud, rut_empresa, ruta_archivo, nombre_archivo, extension_archivo, usuario_carga, fecha_carga, estado_archivo) "; 
                $sql_archivo.= "VALUES ('".$_GET['id_solic']."', '".$_SESSION['empresa']."', '".$destino."', '".$nombre."','".$extension."','".$_SESSION['user']."', ";
                $sql_archivo.= "'".date('Y-m-d H:i:s')."',0)";
                mysql_query($sql_archivo,$con);

                $mensaje = "Archivo subido";

                $consulta = "SELECT MAX(id_archivo) as id_archivo FROM archivos WHERE rut_empresa='".$_SESSION["empresa"]."'";
                $resultado=mysql_query($consulta);
                $fila=mysql_fetch_array($resultado);

                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'archivos', '".$fila["id_archivo"]."', '2'";
                $sql_even.= ", 'INSERT:id_solicitud=".$_GET['id_solic'].", rut_empresa=".$_SESSION['empresa'].", ruta_archivo=".$destino.", nombre_archivo=".$nombre.", extension_archivo=".$extension.", usuario_carga=".$_SESSION['user'].", fecha_carga=".date('Y-m-d H:i:s').", estado_archivo=0', '".$_SERVER['REMOTE_ADDR']."', 'insercion a archivos', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);
        }

}

// if(isset($_GET['action'])){   
    if(!empty($_POST['accion'])){
        
        if($error==0){


            // INSERTO DATOS 
            if($_POST['accion']=="guardar"){
                               
                $sql_ins = "INSERT INTO solicitudes_compra (";
                $sql_ins.= " rut_empresa, id_ot, tipo_solicitud_compra, descripcion_solicitud, ";
                $sql_ins.= " tipo_solicitud, estado, concepto, observaciones,observaciones1,observaciones2, ";
                $sql_ins.= " usuario_ingreso, fecha_ingreso, material, prioridad, origen, ";
                $sql_ins.= " id_cc, cod_producto, cod_detalle_producto, otro_destino, solicitante) ";
                $sql_ins.= "VALUES (";
                $sql_ins.= "'".$_SESSION['empresa']."','".$_POST['id_ot']."', '".$_POST['tipo_solicitud_compra']."','".$_POST['descripcion_solicitud']."', ";
                $sql_ins.= "'".$_POST['tipo_solicitud']."','1','".$_POST['concepto']."', '".$_POST['observaciones']."', '".$_POST['observaciones1']."', '".$_POST['observaciones2']."', ";
                $sql_ins.= "'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."', '".$_POST['material']."', '".$_POST['prioridad']."', '".$_POST['origen']."', "; 
                $sql_ins.= "'".$_POST['centros_costos']."', '".$_POST['cod_producto']."', '".$_POST['cod_detalle_producto']."', '".$_POST['otro_destino']."', '".$_POST["solicitante"]."') ";
                $consulta=mysql_query($sql_ins,$con);
                // echo "<br>".$sql_ins;
               
                // Obtener numero de solicitud  //////////////////////////////////////////////////////////////
                $sql3 = "SELECT MAX(id_solicitud_compra) AS id_solicitud_compra FROM solicitudes_compra";   //
                $res3 = mysql_query($sql3);                                                                 //
                $row3 = mysql_fetch_assoc($res3);                                                           //
                $sol_ultnum = $row3["id_solicitud_compra"];                                               //
                //////////////////////////////////////////////////////////////////////////////////////////////

                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$sol_ultnum."', '2'";
                $sql_even.= ", 'insert:rut_empresa=".$_SESSION['empresa'].", id_ot=".$_POST['id_ot'].", tipo_solicitud_compra=".$_POST['tipo_solicitud_compra'].", descripcion_solicitud=".$_POST['descripcion_solicitud'].", ";
                $sql_even.= " tipo_solicitud=".$_POST['tipo_solicitud'].", estado=1, concepto=".$_POST['concepto'].", observaciones=".$_POST['observaciones'].", ";
                $sql_even.= " usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d H:i:s').", material=".$_POST['material'].", prioridad=".$_POST['prioridad'].", origen=".$_POST['origen'].", ";
                $sql_even.= " id_cc=".$_POST['centros_costos'].", cod_producto=".$_POST['cod_producto'].", cod_detalle_producto=".$_POST['cod_detalle_producto'].", otro_destino=".$_POST['otro_destino'].", solicitante=".$_POST["solicitante"]."', '".$_SERVER['REMOTE_ADDR']."', 'insercion en solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);


                for($x=1; $x<=$_POST["num_prd"]; $x++){
                    // echo "<br>entreamos_".$x;

                    $sql_det = "INSERT INTO detalles_sc (";
                    $sql_det.= "id_solicitud_compra, rut_empresa, cantidad_det_sc, unidad_det_sc, descripcion_det_sc, ";
                    $sql_det.= "estado_det_sc, usuario_ingreso, fecha_ingreso) ";
                    $sql_det.= "VALUES (";
                    $sql_det.= "'".$sol_ultnum."' ,'".$_SESSION['empresa']."','".$_POST['cantidad_'.$x]."', '".$_POST['unidad_medida_'.$x]."','".$_POST['descripcion_'.$x]."', ";
                    $sql_det.= "'".$_POST["EstadoItem_".$x]."' ,'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."' ) ";
                    $consulta2=mysql_query($sql_det,$con);
                 
                    $consulta = "SELECT MAX(id_det_sc) as id_det_sc FROM detalles_sc WHERE rut_empresa='".$_SESSION["empresa"]."'";
                    $resultado=mysql_query($consulta);
                    $fila=mysql_fetch_array($resultado);
                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_sc', '".$fila["id_det_sc"]."', '2'";
                    $sql_even.= ", 'INSERT:id_solicitud_compra=".$sol_ultnum.", rut_empresa=".$_SESSION['empresa'].", cantidad_det_sc=".$_POST['cantidad_'.$x].", unidad_det_sc=".$_POST['unidad_medida_'.$x].", descripcion_det_sc=".$_POST['descripcion_'.$x].", ";
                    $sql_even.= "estado_det_sc=".$_POST["EstadoItem_".$x].", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d H:i:s')."', '".$_SERVER['REMOTE_ADDR']."', 'insercion detalle solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                    mysql_query($sql_even, $con);

                }
                       
                if($consulta && $consulta2){
                        $mensaje="Solicitud de Compra Guardada Correctamente";
                        $mostrar=1;
                }
            
            //  ACTUALIZO DATOS
            }else{

                if($_POST["estado"]!=3){
                 
                    $sql_update = "UPDATE solicitudes_compra SET id_ot='".$_POST['id_ot']."', tipo_solicitud_compra='".$_POST['tipo_solicitud_compra']."', ";
                    $sql_update.= "tipo_solicitud='".$_POST['tipo_solicitud']."', estado='".$_POST["estado"]."', concepto='".$_POST['concepto']."', observaciones='".$_POST['observaciones']."',observaciones1='".$_POST['observaciones1']."',observaciones2='".$_POST['observaciones2']."', ";
                    $sql_update.= "material='".$_POST['material']."', prioridad='".$_POST['prioridad']."', origen='".$_POST['origen']."', solicitante='".$_POST["solicitante"]."', ";
                    $sql_update.= "id_cc='".$_POST['centros_costos']."', cod_producto='".$_POST['cod_producto']."', cod_detalle_producto='".$_POST['cod_detalle_producto']."', otro_destino='".$_POST['otro_destino']."' ";
                    $sql_update.= "WHERE id_ot='".$_POST['id_ot']."' AND id_solicitud_compra='".$_POST["id_solicitud_compra"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                    $consulta=mysql_query($sql_update);
                    
                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$_POST["id_solicitud_compra"]."', '3'";
                    $sql_even.= ", 'UPDATE: id_ot=".$_POST['id_ot'].", tipo_solicitud_compra=".$_POST['tipo_solicitud_compra'].",  ";
                    $sql_even.= " tipo_solicitud=".$_POST['tipo_solicitud'].", estado=".$_POST["estado"].", concepto=".$_POST['concepto'].", observaciones=".$_POST['observaciones'].", ";
                    $sql_even.= " material=".$_POST['material'].", prioridad=".$_POST['prioridad'].", origen0".$_POST['origen'].", ";
                    $sql_even.= " id_cc=".$_POST['centros_costos'].", cod_producto=".$_POST['cod_producto'].", cod_detalle_producto=".$_POST['cod_detalle_producto'].", otro_destino0".$_POST['otro_destino'].", solicitante=".$_POST["solicitante"]."', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                    mysql_query($sql_even, $con);

                    if(isset($_POST["vb_solic_compras"])){/*echo "<br>entre al update deautoriza<br>";*/
                        $sql_autotiza = "UPDATE solicitudes_compra SET  usuario_autoriza='".$_SESSION['user']."', fecha_autoriza='".date('Y-m-d H:i:s')."', estado='3' ";
                        $sql_autotiza.= "WHERE id_solicitud_compra='".$_POST["id_solicitud_compra"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                        // echo "<br>".$sql_autotiza."<br>";
                        $consulta=mysql_query($sql_autotiza);

                        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$_POST["id_solicitud_compra"]."', '3'";
                        $sql_even.= ", 'UPDATE:usuario_autoriza=".$_SESSION['user'].",fecha_autoriza=".date('Y-m-d H:i:s').",estado=3', '".$_SERVER['REMOTE_ADDR']."', 'autorizacion de solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                        mysql_query($sql_even, $con);
                    }

                    /*$up_bor = "DELETE FROM detalles_sc WHERE id_solicitud_compra='".$_POST["id_solicitud_compra"]."'";
                    $consulta=mysql_query($up_bor,$con);*/
                 

                    for($x=1; $x<=$_POST["num_prd"]; $x++){
                       
                        if(empty($_POST["id_det_sc_".$x])){

                                if($_POST["EstadoItem_".$x] == 1){
                                    $sql_det = "INSERT INTO detalles_sc (";
                                    $sql_det.= "id_solicitud_compra, rut_empresa, cantidad_det_sc, unidad_det_sc, descripcion_det_sc, ";
                                    $sql_det.= "estado_det_sc, usuario_ingreso, fecha_ingreso) ";
                                    $sql_det.= "VALUES (";
                                    $sql_det.= "'".$_POST["id_solicitud_compra"]."' ,'".$_SESSION['empresa']."','".$_POST['cantidad_'.$x]."', '".$_POST['unidad_medida_'.$x]."','".$_POST['descripcion_'.$x]."', ";
                                    $sql_det.= "'".$_POST["EstadoItem_".$x]."' ,'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."' ) ";
                                    $consulta2=mysql_query($sql_det,$con);

                                    $consulta = "SELECT MAX(id_det_sc) as id_det_sc FROM detalles_sc WHERE rut_empresa='".$_SESSION["empresa"]."'";
                                    $resultado=mysql_query($consulta);
                                    $fila=mysql_fetch_array($resultado);
                                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                                    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_sc', '".$fila["id_det_sc"]."', '2'";
                                    $sql_even.= ", 'INSERT:id_solicitud_compra=".$_POST["id_solicitud_compra"].", rut_empresa=".$_SESSION['empresa'].", cantidad_det_sc=".$_POST['cantidad_'.$x].", unidad_det_sc0".$_POST['unidad_medida_'.$x].", descripcion_det_sc=".$_POST['descripcion_'.$x].", ";
                                    $sql_even.= "estado_det_sc0".$_POST["EstadoItem_".$x].", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso0".date('Y-m-d H:i:s')."', '".$_SERVER['REMOTE_ADDR']."', 'insercion detalle solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                                    mysql_query($sql_even, $con);

                                }
                        }else{
                                if($_POST["EstadoItem_".$x] == 1){
                                    $sql_det = "UPDATE detalles_sc SET ";
                                    $sql_det.= "id_solicitud_compra='".$_POST["id_solicitud_compra"]."', rut_empresa='".$_SESSION['empresa']."', cantidad_det_sc='".$_POST['cantidad_'.$x]."', unidad_det_sc='".$_POST['unidad_medida_'.$x]."', descripcion_det_sc='".$_POST['descripcion_'.$x]."', ";
                                    $sql_det.= "estado_det_sc='".$_POST["EstadoItem_".$x]."' ";
                                    $sql_det.= "WHERE id_det_sc='".$_POST["id_det_sc_".$x]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                                    $consulta2=mysql_query($sql_det,$con);

                                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                                    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_sc', '".$_POST["id_det_sc"]."', '3'";
                                    $sql_even.= ", 'UPDATE:id_solicitud_compra=".$_POST["id_solicitud_compra"].", rut_empresa=".$_SESSION['empresa'].", cantidad_det_sc=".$_POST['cantidad_'.$x].", unidad_det_sc=".$_POST['unidad_medida_'.$x].", descripcion_det_sc=".$_POST['descripcion_'.$x].", ";
                                    $sql_even.= "estado_det_sc=".$_POST["EstadoItem_".$x]."', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion detalle solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                                    mysql_query($sql_even, $con);

                                }else{
                                    $sql_det = "UPDATE detalles_sc SET ";
                                    $sql_det.= " usuario_elimina='".$_SESSION['user']."', fecha_elimina='".date('Y-m-d H:i:s')."', estado_det_sc='".$_POST["EstadoItem_".$x]."' ";
                                    $sql_det.= "WHERE id_det_sc='".$_POST["id_det_sc_".$x]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                                    $consulta2=mysql_query($sql_det,$con);

                                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                                    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalles_sc', '".$_POST["id_det_sc"]."', '3'";
                                    $sql_even.= ", 'UPDATE: usuario_elimina=".$_SESSION['user'].", fecha_elimina=".date('Y-m-d H:i:s').", estado_det_sc=".$_POST["EstadoItem_".$x]."'";
                                    $sql_even.= ", '".$_SERVER['REMOTE_ADDR']."', 'actualizacion detalle solicitudes de compra', '1', '".date('Y-m-d H:i:s')."')";
                                    mysql_query($sql_even, $con);
                                }
                        }
                    }
                    

                }else{
                    $sql_update = "UPDATE solicitudes_compra SET estado='".$_POST["estado"]."', observaciones='".$_POST['observaciones']."', observaciones1='".$_POST['observaciones1']."', observaciones2='".$_POST['observaciones2']."' ";
                    $sql_update.= "WHERE id_solicitud_compra='".$_POST["id_solicitud_compra"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                    $consulta3=mysql_query($sql_update,$con);

                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                    $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'solicitudes_compra', '".$_POST["id_solicitud_compra"]."', '3'";
                    $sql_even.= ", 'UPDATE:estado=".$_POST["estado"].", observaciones=".$_POST['observaciones'].",'".$_SERVER['REMOTE_ADDR']."', 'actualizacion de solicitud de compra', '1', '".date('Y-m-d H:i:s')."')";
                    mysql_query($sql_even, $con);

                }
                 
                if(($consulta && $consulta2) || $consulta3){
                       $mensaje="Solicitud de Compra Actualizada Correctamente";
                       $mostrar=1;
                }


            }

        }
    }

    // Aprueba archivo
    if(!empty($_POST['archivo_aprovado'])){
        // echo "<br>estados actualizados";
        $s2 = "UPDATE archivos SET estado_archivo=0 WHERE id_solicitud='".$_POST['id_solicitud_compra']."' AND estado_archivo!=2 AND rut_empresa='".$_SESSION["empresa"]."'";
        mysql_query($s2,$con);
        // echo "<br>".$s2;

        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'archivos', '".$_POST["id_solicitud_compra"]."', '3'";
        $sql_even.= ", 'UPDATE:estado_archivo=0', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion estado del archivo de solicitud de compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);


        $s = "UPDATE archivos SET estado_archivo=1 WHERE id_archivo='".$_POST['archivo_aprovado']."' AND rut_empresa='".$_SESSION["empresa"]."'";
        mysql_query($s,$con);

        // if(empty($_POST["editar"]))  $mensaje="Estado de archivos ha sido Actualizada Correctamente";
         // $mostrar=1;

        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'archivos', '".$_POST["id_solicitud_compra"]."', '3'";
        $sql_even.= ", 'UPDATE:estado_archivo=1', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion estado del archivo de solicitud de compra', '1', '".date('Y-m-d H:i:s')."')";
        mysql_query($sql_even, $con);
    }      


if($_POST["estado"]==3){
    $mensaje = "Solicitud de Compra Autorizada, ya no se pueden realizar cambios.";  
}


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


    if($_GET['action']==2 && isset($_GET['id_solic'])){

        $qry_prv = "SELECT * FROM solicitudes_compra WHERE id_solicitud_compra ='".$_GET['id_solic']."' AND rut_empresa='".$_SESSION["empresa"]."'";
        $_SESSION['id_solic']=$_GET['id_solic'];
        $sel_prv = mysql_query($qry_prv,$con);


        $row = mysql_fetch_assoc($sel_prv);
        $id = $_GET['id_solic'];
        $id_ot = $row['id_ot'];
        $descripcion= $row['descripcion_solicitud'];
        $tipo=$row['tipo_solicitud'];
        $observaciones=$row['observaciones'];
        $observaciones1=$row['observaciones1'];
        $observaciones2=$row['observaciones2'];
        $concepto = $row['concepto'];
        $estado = $row['estado'];
        $action="2&new=2&id_solic=".$_GET['id_solic'];
    }

    
?>


<style>
    a:hover{
        text-decoration:none;
    }

    .fo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:12px;
        font-family:Tahoma, Geneva, sans-serif;
        width:80%;
        text-align:center;
    }
</style>



<form action="?cat=2&sec=11&action=<?=$action; ?>" method="POST"  enctype="multipart/form-data">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4" >
        <tr>
            <td id="titulo_tabla" style="text-align:center;" colspan="3"> <a href="?cat=2&sec=10"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Solicitudes de Compra"></</a></td></tr>
        </tr>
    </table>

<?

if($mostrar==0){

    // Obtener numero de solicitud  //////////////////////////////////////////////////////////////
    $sql3 = "SELECT MAX(id_solicitud_compra) AS id_solicitud_compra FROM solicitudes_compra";   //
    $res3 = mysql_query($sql3);                                                                 //
    $row3 = mysql_fetch_assoc($res3);                                                           //
    $sol_num = $row3["id_solicitud_compra"]+1;                                                  //
    //////////////////////////////////////////////////////////////////////////////////////////////


      if(isset($_POST["vb_solic_compras"])){
              $disabled = "disabled='true'"; 
              $readonly = "readonly";
      } 

?>

<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4" >
   <tr>
    <td colspan="3" style="text-align:center; font-size:15pt; font-family:Tahoma, Geneva, sans-serif;">Solicitud de Compra Nº <?if(empty($_GET['id_solic'])){echo $sol_num;}else{echo $_GET['id_solic'];}?> </td>
</tr>
<tr>
    <td colspan="3" />
</tr>    
<tr>
    <td><label>ID OT</label><br />
        <select name="id_ot"  <? if($estado == 3) echo "Disabled"; ?> class='fo' <?=$readonly;?> >
            <option value="0" <? if($id_ot == 0) echo "selected"; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
            <? 
            $qryOT = "SELECT * FROM cabeceras_ot WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY id_ot";
            $resOT = mysql_query($qryOT,$con);
            while($OT = mysql_fetch_assoc($resOT)){
                if($id_ot==$OT['id_ot']){
                    $selected ="SELECTED";
                }else{
                    $selected="";
                }
                            // echo "<option value='".$OT['id_ot']."' ".$selected.">".$OT['id_ot']."</option>";
                ?>
                <option value='<? echo $OT["id_ot"]; ?>' <? if ($OT["id_ot"] == $_POST['id_ot']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $OT['id_ot'];?></option>
                <?
            }
            ?>
        </select>
    </td>
    <td>
        <label>Tipo Solicitud Compra:</label><label style="color:red">(*)</label><br/>
        <select name="tipo_solicitud_compra" style="width:150px;"  class="fo" <?=$disabled;?>  >
            <option value="0" <? if($_POST['tipo_solicitud_compra']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['tipo_solicitud_compra']==1) echo " selected "; ?> >Cotización</option>
            <option value="2" <? if($_POST['tipo_solicitud_compra']==2) echo " selected "; ?> >Compra</option>
            <option value="3" <? if($_POST['tipo_solicitud_compra']==3) echo " selected "; ?> >Solicitud</option>
            <option value="4" <? if($_POST['tipo_solicitud_compra']==4) echo " selected "; ?> >regularizacion</option>
        </select>
    </td> 
    <td>
        <label>Material:</label><label style="color:red">(*)</label><br/>
        <select name="material" style="width:120px;"  class="fo" <?=$disabled;?>>
            <option value="0" <? if($_POST['material']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['material']==1) echo " selected "; ?> >Electrico</option>
            <option value="2" <? if($_POST['material']==2) echo " selected "; ?> >Mecanico</option>
            <option value="3" <? if($_POST['material']==3) echo " selected "; ?> >Filtros</option>
            <option value="4" <? if($_POST['material']==4) echo " selected "; ?> >Lubricantes</option>
            <option value="5" <? if($_POST['material']==5) echo " selected "; ?> >Ferreteria</option>
            <option value="6" <? if($_POST['material']==6) echo " selected "; ?> >Otros</option>
        </select>
    </td>   
</tr>
<tr>
    <td>
        <label>Prioridad:</label><label style="color:red">(*)</label><br/>
        <select name="prioridad" style="width:120px;"  class="fo" <?=$disabled;?> >
            <option value="0" <? if($_POST['prioridad']==0) echo " selected "; ?> >---</option>
            <option value="1" <? if($_POST['prioridad']==1) echo " selected "; ?> >Urgente</option>
            <option value="2" <? if($_POST['prioridad']==2) echo " selected "; ?> >Alta</option>
            <option value="3" <? if($_POST['prioridad']==3) echo " selected "; ?> >Normal</option>
        </select>
    </td>
    <td>
        <label>Origen:</label><label style="color:red">(*)</label><br/>
        <select name="origen"  class='fo' <?=$disabled;?> >
            <option value="0" <? if(isset($_POST['origen']) == 0) echo "selected"; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
            <? 
            $qryPLA = "SELECT * FROM plantas WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY cod_planta";
            $resPLA = mysql_query($qryPLA,$con);
            while($PLA = mysql_fetch_assoc($resPLA)){
            ?>
                <option value='<? echo $PLA["cod_planta"]; ?>' <? if ($PLA["cod_planta"] == $_POST['origen']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $PLA['descripcion_planta'];?></option>
            <?
            }
            ?>
        </select>
        
    </td>

<!-- 6) nuevo campo centro de costos en tabla solicitudes_compra Id_cc = FK con la 
    tabla Centros_Costos (mostrar las descripciones) -->

    <td>
        <label>Centro de Costos:</label><label style="color:red">(*)</label><br/>
        <select name="centros_costos" class='fo' <?=$disabled;?>>
            <?php
            $sql2 = "SELECT id_cc, descripcion_cc FROM centros_costos WHERE 1=1 AND rut_empresa='".$_SESSION["empresa"]."' ORDER BY descripcion_cc ";
            $res2 = mysql_query($sql2,$con);
            ?>
            <option value='0' <? if (isset($_POST['centros_costos']) == 0) echo 'selected'; ?>  >---</option>
            <?php              
            while($row2 = mysql_fetch_assoc($res2)){
                ?>
                <option value='<? echo $row2["id_cc"]; ?>' <? if ($row2['id_cc'] == $_POST['centros_costos']) echo "selected"; ?> ><?  echo $row2["descripcion_cc"];?></option>
                <?php
            }
            ?>
        </select>
    </td>
</tr>
<tr>
    <td>

        <label>Activo Destino:</label><br/>
        <select name="cod_producto" class='fo' style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;' onChange='submit()' <?=$disabled;?>>
            <?php
            $sql2 = "SELECT cod_producto, descripcion FROM productos WHERE 1=1 AND rut_empresa='".$_SESSION["empresa"]."' ORDER BY descripcion ";
            $res2 = mysql_query($sql2,$con);
            ?>
            <option value='0' <? if (isset($_POST['cod_producto']) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
            <?php              
            while($row2 = mysql_fetch_assoc($res2)){
                ?>
                <option value='<? echo $row2["cod_producto"]; ?>' <? if ($row2['cod_producto'] == $_POST['cod_producto']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $row2["descripcion"];?></option>
                <?php
            }
            ?>
            <option value='999' <? if($_POST['cod_producto'] == 999) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">Otro</option>
        </select>
    </td>
    <?
    if( $_POST["cod_producto"]!=0){
     $disabled = ''; 
 }else{
     $disabled = 'disabled="true"'; 
 }
 ?>
 <td>
    <?
    if($_POST["cod_producto"] != '999'){
        ?>
        <label>Patente Destino:</label><br/>
        <select name="cod_detalle_producto" class='fo' style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;' <? if($estado==3 || empty($_POST["cod_producto"])){ echo "Disabled"; }?>>
            <?php
            $sql2 = "SELECT cod_detalle_producto, codigo_interno, patente FROM detalles_productos WHERE 1=1 AND rut_empresa='".$_SESSION["empresa"]."'  ";
            if($_POST["cod_producto"] != '999'){
                $sql2.= "AND cod_producto='".$_POST["cod_producto"]."'";
            }
            $res2 = mysql_query($sql2,$con);
            ?>
            <option value='0' <? if (isset($_POST['cod_detalle_producto']) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
            <?php              
            while($row2 = mysql_fetch_assoc($res2)){
                ?>
                <option value='<? echo $row2["cod_detalle_producto"]; ?>' <? if ($row2['cod_detalle_producto'] == $_POST['cod_detalle_producto']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" ><? if($row2["patente"]!=''){echo $row2["patente"];}else{echo $row2["codigo_interno"];}?></option>
                <?php
            }
            ?>

        </select>
        <input  type="hidden" name="otro_destino" value="" />
        <?
    }else{
        ?>                    
        <label>Otro Destino:</label><br/>
        <input type="text"  name="otro_destino" value="<?=$_POST["otro_destino"];?>" style="width:80%; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" <? if($estado==3){ echo "Disabled"; }?>>         
        <input  type="hidden" name="cod_detalle_producto" value="0" />
        
        <?
    }
    ?>                    
</td>
<td>
    <label>Solicitante:</label><label style="color:red">(*)</label><br/>
        <input type="text"  name="solicitante" value="<?=$_POST["solicitante"];?>" style="width:80%; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" <? if($estado==3){ echo "Disabled"; }?>>         
        
</td>    
</tr>
<tr>
    <td colspan="3">
        <label>Descripción:</label><label style="color:red">(*)</label>
        <br />
        <textarea  <? if($estado==3){ echo "readonly"; }?> cols="110" rows="2" name="descripcion_solicitud"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000; text-align=left; font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["descripcion_solicitud"];?></textarea>
    </td>
</tr>
<tr>
    <td><label>Tipo:</label><label style="color:red">(*)</label> <br/>
        <select name="tipo_solicitud"  <? if($estado==3){ echo "Disabled"; }?> class="fo" >
            <option value="0" >---</option>
            <option value="1" <? if($_POST["tipo_solicitud"]==1){ echo "selected";} ?> >Nacional</option>
            <option value="2" <? if($_POST["tipo_solicitud"]==2){ echo "selected";} ?> >Internacional</option>
        </select>
    </td>
    <td><label>Concepto:</label><label style="color:red">(*)</label><br/>
        <select name="concepto" <? if($estado==3){ echo "Disabled"; }?> class='fo' >
            <!-- <option value="4" >---</option> -->
                    <!-- <option value="1" <?/*if($concepto==1){ echo "SELECTED";}*/?>>Compra</option>
                    <option value="2" <?/*if($concepto==2){ echo "SELECTED";}*/?>>Mantenci&oacute;n</option>
                    <option value="3" <?/*if($concepto==3){ echo "SELECTED";}*/?>>Reparaci&oacute;n</option> -->

                    <option value="0" <? if($_POST['concepto']==0) echo " selected "; ?> >---</option>  
                    <option value="1" <? if($_POST['concepto']==1) echo " selected "; ?> >MANTENCION</option>
                    <option value="2" <? if($_POST['concepto']==2) echo " selected "; ?> >REPARACION</option>
                    <option value="3" <? if($_POST['concepto']==3) echo " selected "; ?> >SERVICIOS</option>
                    <option value="4" <? if($_POST['concepto']==4) echo " selected "; ?> >ACTIVOS</option>
                    <option value="5" <? if($_POST['concepto']==5) echo " selected "; ?> >REPUESTOS</option>
                    <option value="6" <? if($_POST['concepto']==6) echo " selected "; ?> >FABRICACION</option>
                    <option value="7" <? if($_POST['concepto']==7) echo " selected "; ?> >RECTIFICACIÓN</option>

                </select>
     </td>
     <td>
                <label>Estado:</label><label style="color:red">(*)</label><br/>
                <?
                if(!isset($_GET['id_solic'])){
                    ?> 
                    <!-- <input class="fo" readonly="readonly" name="estado" value="ABIERTA"> -->
                    <input class="fo" readonly="readonly" name="estado" value="ABIERTA" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                    <?
                }else{

                    if($estado==3){
                        ?>
                        <input class="fo" readonly="readonly" name="estado" value="AUTORIZADA" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">
                        <!-- <select name="estado" class='fo' >
                            <option value="6" >---</option>
                            <option value="3" <?if($_POST["estado"]==3){ echo "SELECTED";}?>>Autorizada</option>
                            <option value="5" <?if($_POST["estado"]==5){ echo "SELECTED";}?>>Cerrada</option>
                            <option value="4" <?if($_POST["estado"]==4){ echo "SELECTED";}?>>Anulada</option>
                        </select> -->
                        <?
                    }else{

                        ?>
                        <select name="estado" class='fo' >
                            <option value="6" >---</option>
                            <option value="1" <?if($_POST["estado"]==1){ echo "SELECTED";}?>>Abierta</option>
                            <option value="2" <?if($_POST["estado"]==2){ echo "SELECTED";}?>>En espera de Informaci&oacute;n</option>
                            <!--<option value="3" <?/*if($_POST["estado"]==3){ echo "SELECTED";}*/?>>Autorizada</option>-->
                            <option value="5" <?if($_POST["estado"]==5){ echo "SELECTED";}?>>Cerrada</option>
                            <option value="4" <?if($_POST["estado"]==4){ echo "SELECTED";}?>>Anulada</option>
                        </select>
                        <?
                    }
                } 
                ?>
        </td>
</tr>
<tr>
        <td colspan="3"><label>Observaciones:</label>
            <br/>
            <textarea cols="110" rows="2" name="observaciones"  <? if($estado==3){ echo "readonly"; }?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;  font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones"];?></textarea>
        </td>
</tr>
<tr>
        <td colspan="3"><label>Observaciones 1:</label>
            <br/>
            <textarea cols="110" rows="2" name="observaciones1"  <? if($estado==3){ echo "readonly"; }?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;  font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones1"];?></textarea>
        </td>
</tr>
<tr>
        <td colspan="3"><label>Observaciones 2:</label>
            <br/>
            <textarea cols="110" rows="2" name="observaciones2"  <? if($estado==3){ echo "readonly"; }?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;  font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones2"];?></textarea>
        </td>
</tr>

        <!--////////////////////// DETALLE /////////////////////////////////////////////////////////////////////-->
</table><br>

<table style="width:900px; border-collapse:collapse; font-size:12px; text-align:center;" align='center'  border="1" cellpadding="3" cellspacing="3" >

<tr>
              <td colspan='5' style="text-align:right;">
                    <!-- <button type='submit' name='Agregar' value='Agregar' class='boton'><img src='".$Base."iconos/add.png' border='0'> Agregar</button></td>"; -->
                    <button type="submit" name="Agregar" value='Agregar' <? if($estado==3){ echo "Disabled"; }?> ><img src="img/add1.png" width="20" height="20" class="toolTIP" title="Agregar Detalle de Solicitudes de Compra" /></button>
              </td>
</tr>
<tr style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:13px;">
                    
                <td  width"1%"><b>#</b></td>
                <td  width"73%"><b>Descripci&oacute;n</b></td>
                <td  width="16%" ><b>Unidad</b></td>
                <td  width="10%" ><b>Cantidad</b></td>
                <!-- <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" ><b>Precio</b></td> -->
                <!-- <td style="text-align:center; background-color:#CCC; border:1px solid rgb(0,0,0);" width="50px"><b>Total</b></td> -->
                <td  width="9%"><b>Eliminar</b></td>
             <input  type='hidden' name="cantidad" value='<? echo $_POST['cantidad'];?>'/>
</tr>
                
        <style>
                .detalle
                {
                    text-align:center; 
                    background-color:#EBEBEB;
                    border:1px solid #666;
                }

        </style>
<?

if (empty($_POST['num_prd'])) {
        $_POST['num_prd'] = 1;
        $i = $_POST['num_prd'];
        $_POST["EstadoItem_".$i] = 1;

}

for ($f = 1; $f <= $_POST["num_prd"]; $f++) {
        if (!empty($_POST["Eliminar_".$f])) {echo "entro";
            $_POST["EstadoItem_".$f] = 0;
            // $_POST["subtotal"] = $_POST["subtotal"] - $_POST["total_".$f];
        }
}

if (!empty($_POST['Agregar'])) {
       $_POST['num_prd']++;
       $i = $_POST['num_prd'];
       $_POST["EstadoItem_".$i] = 1;
}
                    $j=0;
                    $_POST["subtotal"]=0;
                    for ($i = 1; $i <= $_POST["num_prd"]; $i++) 
                    {
                           // echo "<br>i : ".$i;
                           // echo "<br>num_prd : ".$_POST["num_prd"];
                           // echo "<br>EstadoItem_".$i." : ".$_POST["EstadoItem_".$i];
                        

                        if ($_POST["EstadoItem_".$i] == 1) {
                        
                            $j++;
                            echo "<tr >";
                            echo "<td>".$j."</td>";
                            echo "<input type='hidden' name='id_det_sc_".$i."' value='".$_POST['id_det_sc_'.$i]."' >";
                            echo "<td><input type='text' name='descripcion_".$i."' value='".$_POST['descripcion_'.$i]."' class='fo' style='width:95%;text-align:left;'  ".$readonly."></td>";
                            echo "<td><input type='text' name='unidad_medida_".$i."'      value='".$_POST['unidad_medida_'.$i]."' onchange='calcular".$i."()' class='fo' style='width:90%; text-align:left; ' ".$readonly." ></td>";
                            echo "<td><input type='text' name='cantidad_".$i."'    value='".$_POST['cantidad_'.$i]."' class='fo' style='width:90%; text-align:center' onFocus='mult_".$i."()' onKeyUp='mult_".$i."()' onKeyPress='ValidaSoloNumeros()' ".$readonly." ></td>";
                            // echo "<td class='detalle'><input type='text' name='unidad_".$i."'      value='".$_POST['unidad_'.$i]."' onFocus='mult_".$i."()' onKeyUp='mult_".$i."()'></td>";
                            // echo "<td class='detalle'><input type='text' name='total_".$i."'       value='".$_POST['total_'.$i]."' readonly  style='width:100px; text-align:right' ></td>";
                            echo "<td><button name='Eliminar_".$i."' value='Eliminar_".$i."' ";if($estado==3)echo "Disabled";echo " class='toolTIP' title='Eliminar Detalle de Solicitudes de Compra' ><img src='img/borrar.png' width='16' height='16' /> </button></td>";
                            
                            echo "<input type='hidden' name='LineaVisible_".$i."' value='".$j."'>";
                            echo "<input type='hidden' name='EstadoItem_".$i."' value='".$_POST["EstadoItem_".$i]."'>";
                            echo "</tr>";
                                
                        }
                        else
                        {
                            echo "<input type='hidden' name='id_det_sc_".$i."' value='".$_POST['id_det_sc_'.$i]."' >";
                            echo "<input type='hidden' name='EstadoItem_".$i."' value='0'>";
                            echo "<input type='hidden' name='total_".$i."'  value='0'>";
                            echo "<input type='hidden' name='unidad_".$i."'  value='0'>";
                            echo "<input type='hidden' name='cantiddad_".$i."'  value='0'>";

                            // Actualiza el item de estado si fue eliminado
                            // $upelim = "UPDATE detalles_sc SET estado_det_sc=0, usuario_elimina='".$_SESSION["user"]."', fecha_elimina='".date('Y-m-d H:i:s')."' WHERE id_det_sc=".$_POST['id_det_sc_'.$i]."";
                            // mysql_query($upelim);
                               

                        }
                        
                        $_POST["subtotal"] += $_POST["total_".$i];                       
                        
                    }

                    $_POST["valor_neto"] = $_POST["subtotal"] - $_POST["descuento"];
                    $_POST["iva"] = ($_POST["valor_neto"] * 0.19);
                    $_POST["total_doc"] = ($_POST["valor_neto"] + $_POST["iva"]);



?>
                    <input type='hidden' name='num_prd' value='<?=$_POST["num_prd"];?>'>

                <!-- <tr>
                    <td  colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0); font-weight:bold;"  width="147px">SubTotal:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);" width="137px;"><input type="text" name="subtotal" value='<?/* echo $_POST['subtotal'];*/?>' style="width:80px; text-align:right" readonly /></td>                   
                    <td ></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Descuento:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);" ><input type="text" onFocus="total_neto()" onKeyUp="total_neto()" name="descuento" value='<?/* echo $_POST['descuento'];*/?>' style="width:80px; text-align:right"  /></td>                    
                    <td></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Neto:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="valor_neto" value='<?/* echo $_POST['valor_neto'];*/?>' style="width:80px; text-align:right"  readonly /></td>                 
                    <td></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">IVA:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="iva" value='<?/* echo $_POST['iva'];*/?>' style="width:80px; text-align:right"  readonly /></td>                  
                    <td></td>

                </tr>
                <tr>
                    <td colspan='3'></td>
                    <td  style="text-align:right; background-color:#CCC; border:1px solid rgb(0,0,0);font-weight:bold;" width="">Total:</td>
                    <td  style="text-align:center;border:1px solid rgb(0,0,0);"><input type="text" name="total_doc" value='<?/* echo $_POST['total_doc'];*/?>' style="width:80px; text-align:right"  readonly /></td>                   
                    <td></td>
                </tr> -->
                

                    
       
</table>

<br>
<table style="width:900px; border-collapse:collapse; font-size:12px; text-align:center;" align='center'  border="1" cellpadding="3" cellspacing="3"  >

<?
        // CUANDO ESTAMOS EN EDITAR

        if($nueva==1 || (isset($_GET['id_solic'])&& $_GET['id_solic']!=null)){
?>
    
                <tr style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:13px;">
                    <td width='68%'><b>Archivo</b></td>
                    <td width='7%'><b>Ver</b></td>
                    <td width='10%'> <b>Comparativo General</b></td>
                    <td width='7%'> <b>Eliminar</b></td>
                </tr>
<? 
                            $qr_a ="SELECT * FROM archivos WHERE id_solicitud='".$id."' AND estado_archivo!=2";
                            $r_a = mysql_query($qr_a,$con);
                            if(mysql_num_rows($r_a)!=NULL){
                                $i=1;
                                while($r = mysql_fetch_assoc($r_a)){
?>
                                    <tr style='font-family:Tahoma; font-size:12px;'>    
                                        <td align='left'><?=$r['nombre_archivo'];?></td>
                                        <td ><a href="<?=$r['ruta_archivo'];?>" target="_blank" style='text-decoration:none; font-size:13px;'>ver</a></td>
                                        <!-- <td><input type="checkbox" value="<?/*=$r['id_archivo'];*/?>" onchange="archivo_seleccionado(this.value);submit();"  id="archivo_aprovado_<?/*=$r['id_archivo'];*/?>" name="archivo_aprovado" <?/*if($r['estado_archivo']==1){ echo "checked";}*/?>  <? /*if($estado==3){ echo "Disabled"; }*/?>></td>     -->
                                        <td> <input type="checkbox" value="<?=$r['id_archivo'];?>"  onchange="archivo_seleccionado(this.value);submit();" id="archivo_aprovado_<?=$r['id_archivo'];?>" name="archivo_aprovado" <?if($r['id_archivo']==$_POST["archivo_aprovado"] OR $r['estado_archivo']==1){ echo "checked";}?>  <? if($estado==3){ echo "Disabled"; }?>></td>
                                        
                                        <td ><?if($estado!=3){?><a href="?cat=2&sec=11&elim=1&&action=<?=$action;?>&id_archivo=<?=$r['id_archivo'];?>"><img src="img/delete2.png" width="24px" height="24px" border="0" class="toolTIP" title="Eliminar Archivo Comparativo General"></a><?}?></td>

                                    </tr>
<?
                                    $i++;
                                }
                            }else{
?>                              
                                <tr>
                                    <td colspan="4" style="text-align: center;font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;"><b>No existen archivos para ser desplegados<b></td>   
                                </tr>
<?
                            }

?>
        <tr>
            <td colspan='4'>
               <div align='center'>
                        <!-- <form   action="?cat=2&sec=5&action=2&user=<?/* echo $_GET['user'];*/ ?>"  method="post" enctype="multipart/form-data" style=" text-align:center; background-color:#EEE"> -->
                        <label style='font-family:tahoma;font-size:12px;font-weight:normal;'>Agregar Archivos</label><br><input  <? if($estado==3){ echo "Disabled"; }?> name="archivo" type="file" size="35" style=" background-color:#fff"></input>
                        <input name="enviar" type="submit" value="Subir Archivo" <? if($estado==3){ echo "Disabled"; }?> /><br>

                        <input name="action" type="hidden" value="upload" /> 
                </div>
            </td>
        </tr>
</table>
<br>
<table style="width:900px; border-collapse:collapse; font-size:12px; text-align:center;" align='center'  border="1" cellpadding="3" cellspacing="3" >
<?
            $sql_com = "SELECT * FROM archivos WHERE id_solicitud='".$_POST['id_solicitud_compra']."' AND estado_archivo='1' AND rut_empresa='".$_SESSION["empresa"]."'";
            $res_com = mysql_query($sql_com,$con);
            $num_com = mysql_num_rows($res_com);
            if(empty($_POST["archivo_aprovado"]) ){
                    $disabled2 = "disabled='true'";
            }
             
?>
        <tr>
            <td style="height: 100px; border: 1px solid; text-align:center" >
                        <b>VºBº Solicitud Compra </b><br/><input type="checkbox" name="vb_solic_compras" <? if($_POST['vb_solic_compras']==1){ echo "checked";}?> value="1" <?=$disabled2;?> <? if($estado==3){ echo "Disabled"; }?> >
            </td>
<?
            if(isset($_POST["vb_solic_compras"])){
?>            
            <td colspan='2'>
<?
            if(isset($_POST["archivo_firma"])){
?>
                <img src='firmas/<?=$_POST['archivo_firma']?>' width='200px' height='100px'><br>
                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Autorizado por: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;" ><?=$_POST["usuario_autoriza"];?></label><br>
                <label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; font-weight:bold;">Fecha Autorización: </label ><label style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;"><?=$_POST["fecha_autoriza"];?></label>
<?
            }else{
?>
                <label style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; font-size:16px;">AUTORIZADO</label><br>
                <label style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; font-size:12px;">Autorizado por: </label><label style="font-family:Tahoma, Geneva, sans-serif;; font-size:12px;"><?=$_POST["usuario_autoriza"];?></label><br>
                <label style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; font-size:12px;">Fecha Autorización: </label><label style="font-family:Tahoma, Geneva, sans-serif;; font-size:12px;"><?=$_POST["fecha_autoriza"];?></label>
<?
            }
?>
            </td>
<?
            }
?>

        </tr>
</table>
<?
        }
?>

<br>

<? 
if($_POST["estado"]!='5'){
?>
    <table  style="width:900px;  font-size:12px; text-align:center;" align='center'  border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="text-align: right;" colspan="3">
                    <!-- <input type="submit" value="Grabar"> -->
                    <div style="width:100%; height:auto; text-align:right;">
                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                        <?
                                if($_GET["action"]==1)
                                {
                                echo  " value='guardar' >Guardar";
                                }
                                else
                                {
                                    echo " value='editar' >Actualizar ";
                                    
                                    if(!empty($_POST['vb_solic_compras']) and $_POST["estado"]!=3){
                                        echo "<input type='hidden' name='vb_solic_compras' value='".$_POST["vb_solic_compras"]."' />";
                                    }
                                }
                                ?>
                        </button>
                    </div>
                    <input  type="hidden" name="primera" value="1"/>
                    <input  type="hidden" name="id_solicitud_compra" value="<?=$row["id_solicitud_compra"]?>"/>
                    
                </td>
            </tr>
            <tr>
                <td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                  (*) Campos de Ingreso Obligatorio.
            </td>
            </tr>
    </table>
<?
}
?>

<?

    multiplicador_nitrh($_POST["num_prd"]);

    // var_dump($_POST);
?>

</form>

<?
}
?>

<?php 



function multiplicador_nitrh($num, $dec = 0) {
    $ceros = "";
    for ($d = 1; $d <= $dec; $d++) $ceros .= "0";
    $pot = "1".$ceros;
    $pot = round($pot, 0);

    echo "\n\n <script language='javascript' type='text/javascript'> \n\n";
   
    echo "function total_neto(input) { \n";
    echo "var sub = 0; \n";
    echo "var desc = 0; \n";
    echo "\n desc = eval(document.forms[0].descuento.value); \n";
    echo "if (isNaN(desc)) document.forms[0].descuento.value = Math.round(0); \n";

    for ($i = 1; $i <= $num; $i++) {
        echo "sum = eval(document.forms[0].total_".$i.".value); \n";
        echo "if (isNaN(sum)) sum = 0; \n";
        echo "sub = eval(eval(sub) + eval(sum)); \n";
    }
    echo "\n net = eval(eval(sub) - eval(desc)); \n";
    echo "\n document.forms[0].subtotal.value = Math.round(sub*".$pot.")/".$pot."; \n";
    echo "if (desc > sub) desc = 0; \n";
    
    echo "\n document.forms[0].valor_neto.value = Math.round(net*".$pot.")/".$pot."; \n";

    echo "\n document.forms[0].iva.value = Math.round(net*0.19*".$pot.")/".$pot."; \n";
    echo "\n document.forms[0].total_doc.value = Math.round(net*1.19*".$pot.")/".$pot."; \n";
    // echo "\n document.forms[0].ret.value = Math.round(neto*0.10*".$pot.")/".$pot."; \n";
    // echo "\n document.forms[0].hon.value = Math.round(neto*0.90*".$pot.")/".$pot."; \n";
    // echo "\n document.forms[0].tot2.value = Math.round(neto*".$pot.")/".$pot."; \n";
    echo "} \n\n";
    

    /*echo "function desc_sub(input) { \n";
    echo "var desc = 0; \n";
    echo "var sub = 0; \n";
    echo "\n desc = eval(document.forms[0].descuento.value); \n";
    echo "if (isNaN(desc)) desc = 0; \n";
    for ($i = 1; $i <= $num; $i++) {
        echo "sum = eval(document.forms[0].total_".$i.".value); \n";
        echo "if (isNaN(sum)) sum = 0; \n";
        echo "sub = eval(eval(sub) + eval(sum)); \n";
    }
    echo "\n net = eval(eval(sub) - eval(desc)); \n";
    // echo "\n alert(desc); \n";

    echo "\n document.forms[0].valor_neto.value = Math.round(net*".$pot.")/".$pot."; \n";
    echo "} \n\n";
*/

    for ($i = 1; $i <= $num; $i++) {
        echo "function mult_".$i."(input) { \n";
        // echo "  canm = document.forms[0].cant_max_".$i.".value*10000; \n";
        echo "  cant = document.forms[0].cantidad_".$i.".value*10000; \n";
        echo "  unit = document.forms[0].unidad_".$i.".value*100; \n";
    // echo "\n alert(unit)\n";
       /* echo "  if (eval(cant) > eval(canm)) { \n";
        echo "      document.forms[0].cantidad_".$i.".value = ''; \n";
        echo "      cant = 0; \n";
        echo "      alert('Valor excede máximo permitido.'); \n";
        echo "  } \n";*/
        echo "  if (isNaN(cant)) document.forms[0].cantidad_".$i.".value = ''; \n";
        echo "  if (isNaN(unit)) document.forms[0].unidad_".$i.".value = ''; \n";
        echo "  if (cant < 0) document.forms[0].cantidad_".$i.".value = ''; \n";
        echo "  if (unit < 0) document.forms[0].unidad_".$i.".value = ''; \n";
        echo "  subt = (cant*unit)/1000000; \n";
        echo "  document.forms[0].total_".$i.".value = Math.round(subt*".$pot.")/".$pot."; \n";
        echo "  total_neto(); \n";
        echo "} \n \n";
    };
    echo "\n</script> \n";
}

 /*$k=1;
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
echo "  total=0;
";
echo "  iva=0;
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
echo "  document.f1.valor_neto.value=document.f1.subtotal.value-document.f1.descuento.value;
";
echo "  iva=(document.f1.valor_neto.value)*0.19;
";
echo "  iva=iva.toFixed(0);
";
echo "  document.f1.iva.value=iva;
";
echo "  total=(document.f1.valor_neto.value)*1.19;
";
echo "  total=total.toFixed(0);
";
echo "      document.f1.total_doc.value=total;
";

echo "}
 ";
echo "</script>
 ";
 $k++;
 }*/


/*}*/ 

// var_dump($_POST);
?>