<script type="text/javascript">
    function ValidaSoloNumeros() {
     if ((event.keyCode < 48) || (event.keyCode > 57))
      event.returnValue = false;
}
</script>


<script type="text/javascript">

    function imprSelec(muestra)
    {
        $("#boton_im").css("display" ,'none');
        $("#volver").css("display" ,'none');
        var ficha=document.getElementById(muestra);var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);ventimp.document.close();ventimp.print();ventimp.close();
    }
</script>

<?php





// var_dump($_POST);

// VALIDACIONES
if(!empty($_POST['accion'])){

    $error=0;

    if($_POST['tipo_trabajo']==0 && empty($_POST['tipo_trabajo'])){
        $error=1;
        $mensaje="Debe ingresar Un Tipo de Trabajo ";
    }
    if($_POST['estado_ot']==0 && empty($_POST['estado_ot'])){
        $error=1;
        $mensaje="Debe Seleccionar El Estado de la Orden de trabajo";
    }
    if($_POST['asigna_ot']==0 && empty($_POST['asigna_ot'])){
        $error=1;
        $mensaje="Debe Seleccionar Asignar a la Orden de Trabajo ";

        $mensaje="Debe Seleccionar un Activo ";
        if($_POST['cod_detalle_producto']==0 && empty($_POST['cod_detalle_producto'])){
        }
    }

    //echo = "error";
}elseif($_POST['asigna_ot']==1){
    $error=1;
    $error=1;
    $mensaje="Debe Seleccionar una Patente de Activo ";
    if($_POST['cod_producto']==0 && empty($_POST['cod_producto'])){

    }else{
        if($_POST['id_lf']==0 && empty($_POST['id_lf'])){
            $error=1;
            $mensaje="Debe Seleccionar una Lugar Fisico ";
        }

    }

    if($_POST['centro_costos']==0 && empty($_POST['centro_costos'])){
        $error=1;
        $mensaje="Debe Seleccionar un Centro de Costos ";
    }
    if($_POST['solicitado']=="" && empty($_POST['solicitado'])){
        $error=1;
        $mensaje="Debe Definir Solicitado por ";
    }
    if($_POST['asignacion']=="" && empty($_POST['asignacion'])){
        $error=1;
        $mensaje="Debe Definir Asignación ";
    }

    // VALIDACION DETALLE DE OT
    if($_GET["action"]==2){
        for ($i = 1; $i <= $_POST["num_prd"]; $i++){

            if($_POST["EstadoItem_".$i]==1){

                if($_POST["operador_a_cargo_".$i]==''){
                    $error=1;
                    $mensaje="Debe Ingresar el Operador en el Item Nº ".$i."";
                }
                if($_POST["fecha_trabajo_".$i]==0){
                    $error=1;
                    $mensaje="Debe Ingresar la Fecha de Trabajo en el Item Nº ".$i."";
                }
                if($_POST["horas_hombre_".$i]==0){
                    $error=1;
                    $mensaje="Debe Ingresar las Horas Hombre en el Item Nº ".$i."";
                }
                if($_POST["horas_hombre_".$i]>24){
                    $error=1;
                    $mensaje="Las Horas Hombre deben ser menor a 24 en el Item Nº ".$i."";
                }
                if($_POST["estado_trabajo_".$i]==0){
                    $error=1;
                    $mensaje="Debe Ingresar el Estado del Trabajo en el Item Nº ".$i."";
                }
                if($_POST["descripcion_trabajo_".$i]==""){
                    $error=1;
                    $mensaje="Debe Ingresar la Descripción del Trabajo en el Item Nº ".$i."";
                }
            }

        }

    }

}

// RESCATA LOS DATOS

if(!empty($_GET['id_ot']) and empty($_POST['primera'])){
    $sql_res = "SELECT * FROM cabeceras_ot WHERE rut_empresa='".$_SESSION['empresa']."' AND id_ot='".$_GET["id_ot"]."' ";
    $res_res = mysql_query($sql_res,$con);
    $row_res = mysql_fetch_array($res_res);
    $_POST = $row_res;
    $_POST["solicitado"] = $row_res["solicitado"];

    $q=0;
    $sql_det = "SELECT * FROM detalle_ot WHERE rut_empresa='".$_SESSION['empresa']."' AND id_ot='".$row_res["id_ot"]."' AND estado_detalle_ot='1'";
    $res_det = mysql_query($sql_det,$con);
    $num_det = mysql_num_rows($res_det);
    $_POST["num_prd"] = $num_det;

    while($row_det = mysql_fetch_array($res_det)){
        $q++;
        $_POST["id_det_ot_".$q]=$row_det["id_det_ot"];
        $_POST["id_ot_".$q]=$row_det["id_ot"];
        $_POST["rut_empresa_".$q]=$row_det["rut_empresa"];
        $_POST["descripcion_trabajo_".$q]=$row_det["descripcion_trabajo"];
        $_POST["operador_a_cargo_".$q]=$row_det["operador_a_cargo"];
        $_POST["horas_hombre_".$q]=$row_det["horas_hombre"];
        $_POST["estado_trabajo_".$q]=$row_det["estado_trabajo"];
        $_POST["observaciones_".$q]=$row_det["observaciones"];

        if($row_det["fecha_trabajo"]!=0){
                      // $_POST["fecha_trabajo_".$q] =  date('Y-m-d', strtotime($row_det["fecha_trabajo"]));
          $_POST["fecha_trabajo_".$q] =  date('c', strtotime($row_det["fecha_trabajo"]));
      }else{
          $_POST["fecha_trabajo_".$q] = 0;
      }

      $_POST["EstadoItem_".$q] = 1;
  }

  if(($row_res["fecha_recep_taller"])!=0){
      $_POST["fecha_recep_taller"] = date('c', strtotime($row_res["fecha_recep_taller"]));
  }else{
    $_POST["fecha_recep_taller"] = 0;
}
if(($row_res["fecha_entrega_taller"])!=0){
  $_POST["fecha_entrega_taller"] = date('c', strtotime($row_res["fecha_entrega_taller"]));
}else{
    $_POST["fecha_entrega_taller"] = 0;
}
if(($row_res["fecha_recep_conductor"])!=0){
  $_POST["fecha_recep_conductor"] = date('c', strtotime($row_res["fecha_recep_conductor"]));
}else{
    $_POST["fecha_recep_conductor"] = 0;
}
if(($row_res["fecha_entrega_conductor"])!=0){
  $_POST["fecha_entrega_conductor"] = date('c', strtotime($row_res["fecha_entrega_conductor"]));
}else{
    $_POST["fecha_entrega_conductor"] = 0;
}
}


// GUARDA Y ACTUALIZA LOS DATOS

if(!empty($_POST['accion'])){

  if($error==0){

     if($_POST['accion']=="guardar"){

            $sql_ins = "INSERT INTO cabeceras_ot( ";
            $sql_ins.= " centro_costos, rut_empresa , cod_producto, cod_detalle_producto";
            $sql_ins.= ", tipo_trabajo, concepto_trabajo, descripcion_ot, estado_ot, observaciones, observaciones1, observaciones2 ";
            $sql_ins.= ", usuario_ingreso, fecha_ingreso, id_lf, kilometro, horometro, asignacion, solicitado";
            $sql_ins.= ", fecha_recep_taller, persona_recep_taller, fecha_entrega_taller, persona_entrega_taller";
            $sql_ins.= ", fecha_recep_conductor, persona_recep_conductor, fecha_entrega_conductor, persona_entrega_conductor, asigna_ot) ";
            $sql_ins.= "VALUES ('".$_POST['centro_costos']."', '".$_SESSION['empresa']."', '".$_POST['cod_producto']."', '".$_POST['cod_detalle_producto']."' ";
            $sql_ins.= ", '".$_POST['tipo_trabajo']."', '".$_POST['concepto_trabajo']."', '".$_POST['descripcion_ot']."', '".$_POST['estado_ot']."','".$_POST['observaciones']."','".$_POST['observaciones1']."','".$_POST['observaciones2']."' ";
            $sql_ins.= ", '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."', '".$_POST["id_lf"]."', '".$_POST["kilometro"]."', '".$_POST["horometro"]."', '".$_POST["asignacion"]."', '".$_POST["solicitado"]."'";
            $sql_ins.= ", '".$_POST['fecha_recep_taller']."', '".$_POST['persona_recep_taller']."', '".$_POST['fecha_entrega_taller']."', '".$_POST['persona_entrega_taller']."'";
            $sql_ins.= ", '".$_POST['fecha_recep_conductor']."', '".$_POST['persona_recep_conductor']."',  '".$_POST['fecha_entrega_conductor']."',  '".$_POST['persona_entrega_conductor']."',  '".$_POST['asigna_ot']."')";
            $consulta=mysql_query($sql_ins,$con);

            $sql = "SELECT MAX(id_ot) as id_ot FROM cabeceras_ot WHERE rut_empresa='".$_SESSION["empresa"]."' ";
            $res=mysql_query($sql,$con);
            $row_idot=mysql_fetch_assoc($res);

            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, observaciones1, observaciones2, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'cabeceras_ot', '".$row_idot["id_ot"]."', '2'";
            $sql_even.= ", 'INSERT:centro_costos=".$_POST['centro_costos'].", rut_empresa=".$_SESSION['empresa']." , cod_producto=".$_POST['cod_producto'].", cod_detalle_producto=".$_POST['cod_detalle_producto']." ";
            $sql_even.= ", tipo_trabajo=".$_POST['tipo_trabajo'].", concepto_trabajo=".$_POST['concepto_trabajo'].", descripcion_ot=".$_POST['descripcion_ot'].", estado_ot=".$_POST['estado_ot'].", observaciones=".$_POST['observaciones'].",  observaciones1=".$_POST['observaciones1'].",  observaciones2=".$_POST['observaciones2']."";
            $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".date('Y-m-d H:i:s').", id_lf=".$_POST["id_lf"].", kilometro=".$_POST["kilometro"].", horometro=".$_POST["horometro"].", asignacion=".$_POST["asignacion"].", solicitado=".$_POST["solicitado"]."";
            $sql_even.= ", fecha_recep_taller=".$_POST['fecha_recep_taller'].", persona_recep_taller=".$_POST['persona_recep_taller'].", fecha_entrega_taller=".$_POST['fecha_entrega_taller'].", persona_entrega_taller=".$_POST['persona_entrega_taller']."";
            $sql_even.= ", fecha_recep_conductor=".$_POST['fecha_recep_conductor'].", persona_recep_conductor=".$_POST['persona_recep_conductor'].", fecha_entrega_conductor=".$_POST['fecha_entrega_conductor'].", persona_entrega_conductor=".$_POST['persona_entrega_conductor'].", asigna_ot=".$_POST['asigna_ot']."";
            $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."=', 'Insert de orden de trabajo', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);

            for($x=1; $x<= $_POST["num_prd"]; $x++){

                if(empty($_POST["id_det_ot_".$x])){

                    if($_POST["EstadoItem_".$x] == 1){

                        $sql_det = "INSERT INTO detalle_ot( ";
                        $sql_det.= "id_ot, rut_empresa, descripcion_trabajo ";
                        $sql_det.= ", fecha_trabajo, operador_a_cargo, horas_hombre, estado_trabajo";
                        $sql_det.= ", observaciones, usuario_actualiza, fecha_actualiza";
                        $sql_det.= ", estado_detalle_ot";
                        $sql_det.= ") VALUES(";
                        $sql_det.= "'".$row_idot["id_ot"]."', '".$_SESSION['empresa']."', '".$_POST["descripcion_trabajo_".$x]."' ";
                        $sql_det.= ", '".$_POST["fecha_trabajo_".$x]."', '".$_POST["operador_a_cargo_".$x]."', '".$_POST["horas_hombre_".$x]."', '".$_POST["estado_trabajo_".$x]."'";
                        $sql_det.= ", '".$_POST["observaciones_".$x]."','".$_SESSION["user"]."', '".$_POST["fecha_actualiza_".$x]."'";
                        $sql_det.= ", '1'";
                        $sql_det.= ")";
                        $consulta1=mysql_query($sql_det,$con);

                        $sql = "SELECT MAX(id_det_ot) as id_det_ot FROM detalle_ot WHERE rut_empresa='".$_SESSION["empresa"]."' ";
                        $res=mysql_query($sql,$con);
                        $row_idot2=mysql_fetch_assoc($res);
                        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones,observaciones1,observaciones2, estado_evento, fecha_evento) ";
                        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_ot', '".$row_idot2["id_det_ot"]."', '2'";
                        $sql_even.= ", 'INSERT:id_ot=".$row_idot2["id_ot"].", rut_empresa=".$_SESSION['empresa'].", descripcion_trabajo=".$_POST["descripcion_trabajo_".$x]." ";
                        $sql_even.= ", fecha_trabajo=".$_POST["fecha_trabajo_".$x].", operador_a_cargo=".$_POST["operador_a_cargo_".$x].", horas_hombre=".$_POST["horas_hombre_".$x].", estado_trabajo=".$_POST["estado_trabajo_".$x]."";
                        $sql_even.= ", observaciones=".$_POST["observaciones_".$x].", usuario_actualiza=".$_SESSION["user"].", fecha_actualiza=".$_POST["fecha_actualiza_".$x]."";
                        $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."=', 'Insert de detalle orden de trabajo', '1', '".date('Y-m-d H:i:s')."')";
                        mysql_query($sql_even, $con);

                    }
                }
            }

            /*if($consulta && $consulta1)
            $mensaje=" Orden de Trabajo Ingresada satisfactoriamente. ";
            $mostrar=1;    */
            if($consulta)
                $mensaje=" Orden de Trabajo Ingresada satisfactoriamente. ";
                $mostrar=1;
    }else{
            $sql_ins = "UPDATE cabeceras_ot SET ";
            $sql_ins.= " centro_costos='".$_POST['centro_costos']."', rut_empresa='".$_SESSION['empresa']."', cod_producto='".$_POST['cod_producto']."', cod_detalle_producto='".$_POST['cod_detalle_producto']."' ";
            $sql_ins.= ", tipo_trabajo='".$_POST['tipo_trabajo']."', concepto_trabajo='".$_POST['concepto_trabajo']."', descripcion_ot='".$_POST['descripcion_ot']."', estado_ot='".$_POST['estado_ot']."', observaciones='".$_POST['observaciones']."', observaciones1='".$_POST['observaciones1']."', observaciones2='".$_POST['observaciones2']."'";
            $sql_ins.= ", usuario_ingreso='".$_SESSION['user']."', id_lf='".$_POST["id_lf"]."', kilometro='".$_POST["kilometro"]."', horometro='".$_POST["horometro"]."', asignacion='".$_POST["asignacion"]."', solicitado='".$_POST["solicitado"]."'";
            $sql_ins.= ", fecha_recep_taller='".$_POST['fecha_recep_taller']."', persona_recep_taller='".$_POST['persona_recep_taller']."', fecha_entrega_taller='".$_POST['fecha_entrega_taller']."', persona_entrega_taller='".$_POST['persona_entrega_taller']."'";
            $sql_ins.= ", fecha_recep_conductor='".$_POST['fecha_recep_conductor']."', persona_recep_conductor='".$_POST['persona_recep_conductor']."', fecha_entrega_conductor='".$_POST['fecha_entrega_conductor']."', persona_entrega_conductor='".$_POST['persona_entrega_conductor']."'";
            $sql_ins.= ", asigna_ot='".$_POST["asigna_ot"]."'";
            $sql_ins.= " WHERE rut_empresa='".$_SESSION['empresa']."' AND id_ot='".$_GET["id_ot"]."' ";
            $consulta=mysql_query($sql_ins,$con);

            if($_POST["asigna_ot"]==1){
                $up_ot = "UPDATE cabecera_ot  SET id_lf='0' WHERE id_ot='".$_GET["id_ot"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                $res_ot=mysql_query($up_ot,$con);
            }else{
                $up_ot = "UPDATE cabecera_ot  SET cod_producto='', cod_detalle_producto='0' WHERE id_ot='".$row_idot["id_ot"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                $res_ot=mysql_query($up_ot,$con);
            }


            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, observaciones1, observaciones2, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'cabeceras_ot', '".$_GET["id_ot"]."', '2'";
            $sql_even.= ", 'UPDATE:centro_costos=".$_POST['centro_costos'].", rut_empresa=".$_SESSION['empresa'].", cod_producto=".$_POST['cod_producto'].", cod_detalle_producto=".$_POST['cod_detalle_producto']." ";
            $sql_even.= ", tipo_trabajo=".$_POST['tipo_trabajo'].", concepto_trabajo=".$_POST['concepto_trabajo'].", descripcion_ot=".$_POST['descripcion_ot'].", estado_ot=".$_POST['estado_ot'].", observaciones=".$_POST['observaciones'].", observaciones1=".$_POST['observaciones1'].", observaciones2=".$_POST['observaciones2']."";
            $sql_even.= ", usuario_ingreso=".$_SESSION['user'].", id_lf=".$_POST["id_lf"].", kilometro=".$_POST["kilometro"].", horometro=".$_POST["horometro"].", asignacion=".$_POST["asignacion"].", solicitado=".$_POST["solicitado"]."";
            $sql_even.= ", fecha_recep_taller=".$_POST['fecha_recep_taller'].", persona_recep_taller=".$_POST['persona_recep_taller'].", fecha_entrega_taller=".$_POST['fecha_entrega_taller'].", persona_entrega_taller=".$_POST['persona_entrega_taller']."";
            $sql_even.= ", fecha_recep_conductor=".$_POST['fecha_recep_conductor'].", persona_recep_conductor=".$_POST['persona_recep_conductor'].", fecha_entrega_conductor=".$_POST['fecha_entrega_conductor'].", persona_entrega_conductor=".$_POST['persona_entrega_conductor']."";
            $sql_even.= ", asigna_ot=".$_POST["asigna_ot"]." ";
            $sql_even.= "','".$_SERVER['REMOTE_ADDR']."', 'Update orden de trabajo', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);

            for($x=1; $x<= $_POST["num_prd"]; $x++){

                if(!empty($_POST["id_det_ot_".$x])){

                    $sql_det = "UPDATE detalle_ot SET";
                    $sql_det.= " id_ot='".$_GET["id_ot"]."', rut_empresa='".$_SESSION['empresa']."', descripcion_trabajo='".$_POST["descripcion_trabajo_".$x]."' ";
                    $sql_det.= ", fecha_trabajo='".$_POST["fecha_trabajo_".$x]."', operador_a_cargo='".$_POST["operador_a_cargo_".$x]."', horas_hombre='".$_POST["horas_hombre_".$x]."', estado_trabajo='".$_POST["estado_trabajo_".$x]."'";
                    $sql_det.= ", observaciones='".$_POST["observaciones_".$x]."', usuario_actualiza='".$_SESSION["user"]."', fecha_actualiza='".date('Y-m-d H:i:s')."'";
                    $sql_det.= ", estado_detalle_ot='".$_POST["EstadoItem_".$x]."' ";
                    $sql_det.= " WHERE rut_empresa='".$_SESSION['empresa']."' AND id_det_ot='".$_POST["id_det_ot_".$x]."'";
                    $consulta1=mysql_query($sql_det,$con);

                    $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                    $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                    $sql_even.= "VALUES('".$_GET["id_ot"]."', '".$_SESSION["empresa"]."', 'detalle_ot', '".$_POST["id_det_ot_".$x]."', '3'";
                    $sql_even.= ",'UPDATE:id_ot=".$row_idot["id_ot"].", rut_empresa=".$_SESSION['empresa'].", descripcion_trabajo=".$_POST["descripcion_trabajo_".$x]." ";
                    $sql_even.= ", fecha_trabajo=".$_POST["fecha_trabajo_".$x].", operador_a_cargo=".$_POST["operador_a_cargo_".$x].", horas_hombre=".$_POST["horas_hombre_".$x].", estado_trabajo=".$_POST["estado_trabajo_".$x]."";
                    $sql_even.= ", observaciones=".$_POST["observaciones_".$x].", usuario_actualiza=".$_SESSION["user"].", fecha_actualiza=".$_POST["fecha_actualiza_".$x]."";
                    $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."=', 'Update de detalle orden de trabajo', '1', '".date('Y-m-d H:i:s')."')";
                    mysql_query($sql_even, $con);
                }else{

                    if($_POST["EstadoItem_".$x] == 1){

                            $sql_det = "INSERT INTO detalle_ot( ";
                            $sql_det.= "id_ot, rut_empresa, descripcion_trabajo ";
                            $sql_det.= ", fecha_trabajo, operador_a_cargo, horas_hombre, estado_trabajo";
                            $sql_det.= ", observaciones, usuario_actualiza, fecha_actualiza";
                            $sql_det.= ", estado_detalle_ot ";
                            $sql_det.= ") VALUES(";
                            $sql_det.= "'".$_GET["id_ot"]."', '".$_SESSION['empresa']."', '".$_POST["descripcion_trabajo_".$x]."' ";
                            $sql_det.= ", '".$_POST["fecha_trabajo_".$x]."', '".$_POST["operador_a_cargo_".$x]."', '".$_POST["horas_hombre_".$x]."', '".$_POST["estado_trabajo_".$x]."'";
                            $sql_det.= ", '".$_POST["observaciones_".$x]."', '".$_SESSION["user"]."', '".$_POST["fecha_actualiza_".$x]."'";
                            $sql_det.= ", '1'";
                            $sql_det.= ")";
                            $consulta1=mysql_query($sql_det,$con);

                            $sql = "SELECT MAX(id_det_ot) as id_det_ot FROM detalle_ot WHERE rut_empresa='".$_SESSION["empresa"]."' ";
                            $res=mysql_query($sql,$con);
                            $row_idot=mysql_fetch_assoc($res);
                            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_ot', '".$row_idot["id_det_ot"]."', '2'";
                            $sql_even.= ", 'INSERT:id_ot=".$row_idot["id_ot"].", rut_empresa=".$_SESSION['empresa'].", descripcion_trabajo=".$_POST["descripcion_trabajo_".$x]." ";
                            $sql_even.= ", fecha_trabajo=".$_POST["fecha_trabajo_".$x].", operador_a_cargo=".$_POST["operador_a_cargo_".$x].", horas_hombre=".$_POST["horas_hombre_".$x].", estado_trabajo=".$_POST["estado_trabajo_".$x]."";
                            $sql_even.= ", observaciones=".$_POST["observaciones_".$x].", usuario_actualiza=".$_SESSION["user"].", fecha_actualiza=".$_POST["fecha_actualiza_".$x]."";
                            $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."=', 'Insert de detalle orden de trabajo', '1', '".date('Y-m-d H:i:s')."')";
                            mysql_query($sql_even, $con);

                    }else{

                        $sql_det = "UPDATE detalle_ot SET";
                        $sql_det.= ", estado_detalle_ot='".$_POST["EstadoItem_".$x]."' ";
                        $sql_det.= " WHERE rut_empresa='".$_SESSION['empresa']."' AND id_det_ot='".$_POST["id_det_ot_".$x]."'";
                        $consulta1=mysql_query($sql_det,$con);

                        $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                        $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                        $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'detalle_ot', '".$_POST["id_det_ot"]."', '3'";
                        $sql_even.= ", 'UPDATE: estado_detalle_ot=".$_POST["EstadoItem_".$x]."'";
                        $sql_even.= "', '".$_SERVER['REMOTE_ADDR']."=', 'Insert de detalle orden de trabajo', '1', '".date('Y-m-d H:i:s')."')";
                        mysql_query($sql_even, $con);
                    }
                }
            }

            if($consulta && $consulta1)
                $mensaje=" Orden de Trabajo Actualizada satisfactoriamente. ";
                $mostrar=1;
      }

   }
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



?>

<style>
    .foo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:11px;
        font-family:Tahoma, Geneva, sans-serif;
        text-align:left;
        width:142px;
    }

</style>

<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4" >
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">  </td>
        <td id="list_link" style="text-align:right;" ><a href="?cat=2&sec=17"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al Listado de Ordenes de Trabajo"></</a>
        </td>
    </tr>
</table>

<?php
if($mostrar==0){
    ?>

    <form action="?cat=2&sec=18&action=<?=$_GET["action"];?>&id_ot=<?=$_GET['id_ot'];?>" method="POST" id='f1'>
        <table id="detalle-prov"  style="margin-top:15px; border:3px #FFF solid;width:900px;"  cellpadding="3" cellspacing="4" border="0">
            <tr>
              <?
              $sql_idot = "SELECT MAX(id_ot) as id_ot FROM cabeceras_ot WHERE rut_empresa='".$_SESSION["empresa"]."' ";
              $res_idot=mysql_query($sql_idot,$con);
              $row_idot4=mysql_fetch_assoc($res_idot);
              $row_idot4["id_ot"]++;
              ?>
              <td  colspan='3'  style='text-align:center;'><label style=" font-weight:bold; font-size:24px;text-align:center;height:20px">ORDEN DE TRABAJO N° <?if($_GET["action"]==2){echo $_GET["id_ot"];}else{ echo $row_idot4["id_ot"];}?></label></td>
          </tr>
          <tr>
            <td ><label>Tipo Trabajo:</label><label style="color:red;">(*)</label><br>
                <select name="tipo_trabajo" class="foo" style="width:80px;">
                    <option value="0"> --- </option>
                    <option value="1" <?if($_POST["tipo_trabajo"]==1){ echo "SELECTED";}?>> Urgente </option>
                    <option value="2" <?if($_POST["tipo_trabajo"]==2){ echo "SELECTED";}?>> Normal </option>
                </select>
            </td>
            <td ><label>Concepto:</label><br>
                <select name="concepto_trabajo" class="foo" style="width:150px;">
                    <option value="0"> --- </option>
                    <option value="1" <?if($_POST["concepto_trabajo"]==1){ echo "SELECTED";}?>> MANTENCION </option>
                    <option value="2" <?if($_POST["concepto_trabajo"]==2){ echo "SELECTED";}?>> REPARACION </option>
                </select>
            </td>
            <td ><label>Estado:</label><label style="color:red;">(*)</label><br>
                <select name="estado_ot" class="foo" style="width:150px;">
                    <option value="0"> --- </option>
                    <option value="1" <?if($_POST["estado_ot"]==1){ echo "SELECTED";}?>> Pendiente </option>
                    <option value="2" <?if($_POST["estado_ot"]==2){ echo "SELECTED";}?>> En Proceso </option>
                    <option value="3" <?if($_POST["estado_ot"]==3){ echo "SELECTED";}?>> Finalizado </option>
                    <option value="4" <?if($_POST["estado_ot"]==4){ echo "SELECTED";}?>> En Espera de Materiales </option>
                    <option value="5" <?if($_POST["estado_ot"]==5){ echo "SELECTED";}?>> Trabajo Suspendido </option>
                </select>
            </td>
        </tr>
        <tr>

            <td id="detalle_prod">
                <label>Asignar a:</label><label style="color:red;">(*)</label><br>
                <select name="asigna_ot" onChange='submit()' class="foo" style="width:100px;">
                    <option value="0"> --- </option>
                    <option value="1" <?if($_POST["asigna_ot"]==1){ echo "SELECTED";}?>> Activo </option>
                    <option value="2" <?if($_POST["asigna_ot"]==2){ echo "SELECTED";}?>> Lugar Fisico </option>
                </select>

                <!-- De acuerdo a esto elijo a que va destinado la OT (Activo o Lugar Fisico) -->

            </td>
            <?
            if($_POST["asigna_ot"]==1){
                ?>
                <td id="detalle_prod">
                    <label>Activo:</label><label style="color:red;">(*)</label><br>
                    <select name="cod_producto" class="foo" style="width:120px;" onChange='submit()' <?=$disabled;?>>
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
                    </select>
                </td>
                <td>
                    <?
                    if( $_POST["cod_producto"]!=0){
                        $disabled = '';
                    }else{
                        $disabled = 'disabled="true"';
                    }

                    if($_POST["cod_producto"] != '999'){
                        ?>
                        <label>Patente:</label><label style="color:red;">(*)</label><br>
                        <select name="cod_detalle_producto" class="foo" style="width:120px;" <? if($estado==3 || empty($_POST["cod_producto"])){ echo "Disabled"; }?>>
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
                    }
                    ?>
                </td>
                <?
            }

            if($_POST["asigna_ot"]==2){
                ?>
                <td>
                    <label>Lugares Fisicos:</label><label style="color:red;">(*)</label><br>
                    <select name="id_lf" class="foo" style="width:150px;">
                        <?
                        $s = "SELECT * FROM lugares_fisicos WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_lf";
                        $res = mysql_query($s,$con);
                        ?>
                        <option value='0' <? if (isset($_POST['id_lf']) == 0) echo 'selected'; ?> class="foo"> ---</option>
                        <?
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                            <option value="<?=$r['id_lf'];?>" <? if($_POST["id_lf"]==$r['id_lf']){echo "SELECTED";}?>><?=$r['descripcion_lf'];?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <label>Centros de Costo:</label><label style="color:red;">(*)</label><br>
                    <select name="centro_costos" class="foo" style="width:150px;">
                        <?
                        $s = "SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                        $res = mysql_query($s,$con);
                        ?>
                        <option value='0' <? if (isset($_POST['centro_costos']) == 0) echo 'selected'; ?> class="foo"> ---</option>
                        <?
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                            <option value="<?=$r['Id_cc'];?>" <? if($_POST["centro_costos"]==$r['Id_cc']){echo "SELECTED";}?>><?=$r['descripcion_cc'];?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
                <?
            }
            ?>

        </tr>
        <?
        if($_POST["asigna_ot"]==1){
            ?>
            <tr>
                <td>
                    <label>Centros de Costo:</label><label style="color:red;">(*)</label><br>
                    <select name="centro_costos" class="foo" style="width:150px;">
                        <?
                        $s = "SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                        $res = mysql_query($s,$con);
                        ?>
                        <option value='0' <? if (isset($_POST['centro_costos']) == 0) echo 'selected'; ?> class="foo"> ---</option>
                        <?
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                            <option value="<?=$r['Id_cc'];?>" <? if($_POST["centro_costos"]==$r['Id_cc']){echo "SELECTED";}?>><?=$r['descripcion_cc'];?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <label>Kilometro:</label><br>
                    <input type="text" name="kilometro" value="<?=$_POST["kilometro"];?>" onKeyPress='ValidaSoloNumeros()' class="foo" style="width:100px;">
                </td>
                <td >
                    <label>horometro:</label><br>
                    <input type="text" name="horometro" value="<?=$_POST["horometro"];?>" onKeyPress='ValidaSoloNumeros()' class="foo" style="width:100px;">
                </td>

            </tr>
            <?
        }
        ?>
        <tr>
            <?
            if($_POST["asigna"]==2){
                ?>
                <td>
                    <label>Centros de Costo:</label><label style="color:red;">(*)</label><br>
                    <select name="centro_costos" class="foo" style="width:150px;">
                        <?
                        $s = "SELECT * FROM centros_costos WHERE rut_empresa = '".$_SESSION['empresa']."'  ORDER BY descripcion_cc";
                        $res = mysql_query($s,$con);
                        ?>
                        <option value='0' <? if (isset($_POST['centro_costos']) == 0) echo 'selected'; ?> class="foo"> ---</option>
                        <?
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                            <option value="<?=$r['Id_cc'];?>" <? if($_POST["centro_costos"]==$r['Id_cc']){echo "SELECTED";}?>><?=$r['descripcion_cc'];?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
                <?
            }

            ?>
            <td>
                <label>Solicitado por :</label><label style="color:red">(*)</label><br/>
                <input type="text"  name="solicitado" value="<?=$_POST["solicitado"];?>" class="foo" style="width:100%;" <? if($estado==3){ echo "Disabled"; }?>>
            </td>
            <td colspan='2'>
                <label>Asignación :</label><label style="color:red">(*)</label><br/>
                <input type="text"  name="asignacion" value="<?=$_POST["asignacion"];?>" class="foo" style="width:100%;" <? if($estado==3){ echo "Disabled"; }?>>
            </td>

        </tr>
        <tr>
            <td colspan="3"><label>Descripci&oacute;n:</label><br/>
                <textarea cols="155" rows="2"  name="descripcion_ot" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["descripcion_ot"];?></textarea>
            </td>

        </tr>
        <tr>
            <td colspan="3"><label>Observaciones:</label><br/>
                <textarea cols="155" rows="2" name="observaciones" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones"];?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3"><label>Observaciones 1:</label><br/>
                <textarea cols="155" rows="2" name="observaciones1" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones1"];?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3"><label>Observaciones 2:</label><br/>
                <textarea cols="155" rows="2" name="observaciones2" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones2"];?></textarea>
            </td>
        </tr>
    </table>
    <br>

    <!-- DETALLE DE OT-->
    <?if($_GET["action"]==2){?>

    <table align="center" style="margin-top:15px; solid;width:900px;text-align:center;"  cellpadding="3" cellspacing="4" border="0">    <tr>
        <tr>
            <td id="titulo_tabla" colspan="3"  style="text-align:center;"> ..:: Detalle Orden de Trabajo ::..</td>
        </tr>
    </table>
    <table  align="center" style="border-collapse:collapse;font-size:12px;font-family:Tahoma, Geneva, sans-serif;font-weight:bold;margin-top:15px; border:1px black solid;width:900px;"  cellpadding="3" cellspacing="4" border="1">    <tr>
        <tr>
          <td colspan='3' style="text-align:right;">
            <button type="submit" name="Agregar" value='Agregar' <? if($estado==3){ echo "Disabled"; }?> ><img src="img/add1.png" width="20" height="20" class="toolTIP" title="Agregar Detalle de Orden de Trabajo" /></button>
        </td>
    </tr>
    <tr style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:13px; text-align:center;">

        <td><b>#</b></td>
        <td><b>Eliminar</b></td>
        <td><b>Detalle</b></td>
    </tr>
    <?

    if (empty($_POST['num_prd'])) {
        $_POST['num_prd'] = 1;
        $i = $_POST['num_prd'];
        $_POST["EstadoItem_".$i] = 1;

    }

    for ($f = 1; $f <= $_POST["num_prd"]; $f++) {
        if (!empty($_POST["Eliminar_".$f])) {
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
        //echo "<br>i : ".$i;
        //echo "<br>num_prd : ".$_POST["num_prd"];
        //echo "<br>EstadoItem_".$i." : ".$_POST["EstadoItem_".$i];

    if ($_POST["EstadoItem_".$i] == 1) {
        ?>
        <tr>
            <td rowspan="5" align="center" style="width:20px;">
                <?
                echo $i;
                ?>
                <input type='hidden' name='id_det_ot_<?=$i;?>' value='<?=$_POST['id_det_ot_'.$i];?>'>
            </td>
        </tr>
        <tr>
            <td rowspan="5" align="center" style="width:50px;">
                <? echo "<button name='Eliminar_".$i."' value='Eliminar_".$i."' ";if($estado==3)echo "Disabled";echo " class='toolTIP' title='Eliminar Detalle de Solicitudes de Compra' ><img src='img/borrar.png' width='16' height='16' /> </button>";?>
            </td>
        </tr>
        <tr>
            <td >
                <label>Operador:</label><label style="color:red;">(*)</label>
                <select name='operador_a_cargo_<?=$i;?>' class="foo" style="width:90px;">
                    <?
                    $s = "SELECT * FROM usuarios WHERE rut_empresa = '".$_SESSION['empresa']."' and tipo_usuario=2 ORDER BY nombre";
                    $r = mysql_query($s,$con);
                    ?>
                    <option value='' <? if (empty($_POST["operador_a_cargo_".$i]) == '') echo 'selected'; ?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
                    <?
                    while($rw = mysql_fetch_assoc($r)){
                        ?>
                        <option value='<? echo $rw["usuario"];?>' <? if ($rw["usuario"] == $_POST['operador_a_cargo_'.$i]) echo "selected"; ?> style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><? echo $rw["nombre"];?></option>
                        <?
                    }
                    ?>
                </select>
                &nbsp&nbsp&nbsp
                <?
                        // Edicion de fomato para input 'datime-local' ////////////////////////
                        $fec_tbjo = substr ( "".$_POST["fecha_trabajo_".$i]."" , 0 , - 6 );  //
                        ///////////////////////////////////////////////////////////////////////
                        ?>
                        <label style="text-align:right;">Fecha:</label><label style="color:red;">(*)</label>
                        <input type="datetime-local" name="fecha_trabajo_<?=$i;?>" class="foo" size="30" value="<?=$fec_tbjo;?>">
                        &nbsp&nbsp&nbsp

                        <label>Horas:</label><label style="color:red;">(*)</label>
                        <input type="text" class="foo" style="width:20px;text-align:center;" name="horas_hombre_<?=$i;?>" value="<?=$_POST['horas_hombre_'.$i];?>" onKeyPress='ValidaSoloNumeros()'>
                        &nbsp&nbsp&nbsp

                        <label>Estado Trabajo:</label><label style="color:red;">(*)</label>
                        <select name="estado_trabajo_<?=$i;?>" class="foo" style="width:100px;">
                            <option value="0" > --- </option>
                            <option value="1" <?if($_POST['estado_trabajo_'.$i]==1){ echo "SELECTED";}?>> Pendiente </option>
                            <option value="2" <?if($_POST['estado_trabajo_'.$i]==2){ echo "SELECTED";}?>> En Proceso </option>
                            <option value="3" <?if($_POST['estado_trabajo_'.$i]==3){ echo "SELECTED";}?>> Finalizado </option>
                            <option value="4" <?if($_POST['estado_trabajo_'.$i]==4){ echo "SELECTED";}?>> En Espera de Materiales </option>
                            <option value="5" <?if($_POST['estado_trabajo_'.$i]==5){ echo "SELECTED";}?>> Trabajo Suspendido </option>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td ><label>Descripción:</label><label style="color:red;">(*)</label><br>
                        <textarea cols="155" rows="2" name="descripcion_trabajo_<?=$i;?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"><?=$_POST['descripcion_trabajo_'.$i];?></textarea>
                    </td>
                </tr>

                <tr>
                    <td ><label>Observaciones:</label><br/>
                        <textarea cols="155" rows="2" name="observaciones_<?=$i;?>" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;font-size:11px;font-family:Tahoma, Geneva, sans-serif;"><?=$_POST["observaciones_".$i];?></textarea>
                    </td>
                    <? echo "<input type='hidden' name='EstadoItem_".$i."' value='".$_POST["EstadoItem_".$i]."'>";?>

                    <?
                }else{

                    echo "<input type='hidden' name='id_det_sc_".$i."' value='".$_POST['id_det_sc_'.$i]."' >";
                    echo "<input type='hidden' name='EstadoItem_".$i."' value='0'>";
                    echo "<input type='hidden' name='id_det_ot_".$i."' value='".$_POST['id_det_ot_'.$i]."'>";

            // Actualiza el item de estado si fue eliminado
            // $upelim = "UPDATE detalles_sc SET estado_det_sc=0, usuario_elimina='".$_SESSION["user"]."', fecha_elimina='".date('Y-m-d H:i:s')."' WHERE id_det_sc=".$_POST['id_det_sc_'.$i]."";
            // mysql_query($upelim);

                }

            }

            ?>
            <input type='hidden' name='num_prd' value='<?=$_POST["num_prd"];?>'>
        </tr>
    </table>
    <?}?>
    <br>
    <?
    if($_POST["concepto_trabajo"]==1){
        ?>
        <!-- Checklist Mantenciones -->

        <table id="detalle-prov"  style="border-collapse:collapse;width:900px;"  cellpadding="3" cellspacing="4" border="0">
            <tr>
                <td id="titulo_tabla" colspan="4"  style="text-align:center;"> ..:: Checklist Mantencion/Observaciones ::..</td>
            </tr>
        </table>
        <table id="detalle-prov"  style="border-collapse:collapse;width:900px;"  cellpadding="3" cellspacing="4" border="1">
            <tr>
                <td colspan="3" ><label>Descripción</label><br/><br><br><br><br>
                </td>
                <td rowspan="4" style='text-align:left'><label>Checkeo Mantención</label><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Cambio Filtro Primario Aire</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Secundario Aire</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Primario Combustible</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Secundario Combustible</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Aceite</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Racord</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro </span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro </span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Aceite Motor</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Aceite Caja</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Aceite Dirección</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Aceite Hidráulico</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;'>Cambio Filtro Aceite </span>
                </td>
            </tr>
            <tr>
                <td rowspan="3"><label>Checkeo Fisico</label><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Chequeo Estado Correas</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Chequeo Estado Depositos</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Chequeo Frenos Delanteros</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Chequeo Frenos Traseros</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Chequeo Balatas</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Chequeo Neumaticos y Surcos</span>
                </td>
                <td rowspan="3"><label>Checkeo Electrico</label><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Revisión Cableado</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Revisión Luz de Freno</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Revisión Estado y Alineación de Luces</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Revisión Luces de Estacionamiento</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Revisión Luces de Posición, Altas y Bajas</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>Revisión de Baterias y Bornes</span>
                </td>
            </tr>
            <!-- <table border='1' width='100%' height='100%' align='center' style="border-collapse:collapse;" > -->
            <tr>

                <td>
                    <label>Checkeo Carroceria</label><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>&nbspRevisión Estructura General (Chassis)</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>&nbspRevisión Tren Delantero</span>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Checkeo Fluidos</label><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>&nbspRevisión Refrigerante</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>&nbspRevisión de Fugas y Filtraciones</span><br>
                    <span style='font-size:11px;font-family:Tahoma, Geneva, sans-serif;text-align:left;'>&nbspRevisión de Nivel de Aceite</span>

                </td>
            </tr>

        </table>
        <?}?>
        <!-- FIN DETALLE DE OT-->

        <table id="detalle-prov"  style="margin-top:15px; border:3px #FFF solid;width:900px;"  cellpadding="3" cellspacing="4" border="0">    <tr>
            <tr>
                <td id="titulo_tabla" colspan="4"  style="text-align:center;"> ..:: Taller ::..</td>
            </tr>
            <tr>
                <?
            // Edicion de fomato para input 'datime-local' ////////////////////////
                $fecha_recep_taller = substr ( "".$_POST["fecha_recep_taller"]."" , 0 , - 6 );
            ///////////////////////////////////////////////////////////////////////
                ?>
                <td><label>Fecha de Recepción:</label><br/>
                    <input type="datetime-local" name="fecha_recep_taller" class="foo" size="50" value="<?=$fecha_recep_taller;?>">
                </td>
                <td><label>Nombre de Recepción:</label><br/>
                    <input type="text" class="foo" style="width:150px;" name="persona_recep_taller" value="<?=$_POST['persona_recep_taller'];?>">
                </td>
                <?
            // Edicion de fomato para input 'datime-local' ////////////////////////
                $fecha_entrega_taller = substr ( "".$_POST["fecha_entrega_taller"]."" , 0 , - 6 );
            ///////////////////////////////////////////////////////////////////////
                ?>
                <td><label>Fecha de Entrega:</label><br/>
                    <input type="datetime-local" name="fecha_entrega_taller" class="foo" size="50" value="<?=$fecha_entrega_taller;?>">
                </td>
                <td><label>Nombre de Entrega:</label><br/>
                    <input type="text" class="foo" style="width:150px;" name="persona_entrega_taller" value="<?=$_POST['persona_entrega_taller'];?>">
                </td>
            </tr>
            <tr>
                <td id="titulo_tabla" colspan="4"  style="text-align:center;"> ..:: Conductor ::..</td>
            </tr>
            <tr>
                <?
            // Edicion de fomato para input 'datime-local' ////////////////////////
                $fecha_recep_conductor = substr ( "".$_POST["fecha_recep_conductor"]."" , 0 , - 6 );
            ///////////////////////////////////////////////////////////////////////
                ?>
                <td><label>Fecha de Recepción:</label><br/>
                    <input type="datetime-local" name="fecha_recep_conductor" class="foo" size="50" value="<?=$fecha_recep_conductor;?>">
                </td>
                <td><label>Nombre de Recepción:</label><br/>
                    <input type="text" class="foo" style="width:150px;" name="persona_recep_conductor" value="<?=$_POST['persona_recep_conductor'];?>">
                </td>
                <?
            // Edicion de fomato para input 'datime-local' ////////////////////////
                $fecha_entrega_conductor = substr ( "".$_POST["fecha_entrega_conductor"]."" , 0 , - 6 );
            ///////////////////////////////////////////////////////////////////////
                ?>
                <td><label>Fecha de Entrega:</label><br/>
                    <input type="datetime-local" name="fecha_entrega_conductor" class="foo"  size="50" value="<?=$fecha_entrega_conductor;?>">
                </td>
                <td><label>Nombre de Entrega:</label><br/>
                    <input type="text" class="foo" style="width:150px;" name="persona_entrega_conductor" value="<?=$_POST['persona_entrega_conductor'];?>">
                </td>
            </tr>
        </table>



        <table id="detalle-prov"  style="width:900px;"  cellpadding="3" cellspacing="4" border="0">    <tr>
            <tr>
                <td>
                    <div style="width:100%; height:auto; text-align:right;">
                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                        <?
                        if($_GET["action"]==1){
                            echo  " value='guardar' >Guardar</button>";
                        }

                        if($_GET["action"]==2){
                            echo " value='editar' >Actualizar</button>";
                        }
                        ?>
                    </div>
                    <input  type="hidden" name="primera" value="1"/>
                </td>
                <tr>
                    <td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                        (*) Campos de Ingreso Obligatorio.
                    </td>
                </tr>
            </table>


            <table align="center">
                <tr>
                    <td>
                        <input type="button" id="boton_im" onclick="javascript:imprSelec('f1')" value="Imprimir" />
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
// var_dump($_POST);
    ?>