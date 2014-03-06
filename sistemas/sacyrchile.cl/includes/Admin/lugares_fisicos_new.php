<?php
// var_dump($_POST);
$mostrar=0;

if(!empty($_POST['accion'])){

    $error=0;
    if(empty($_POST['descripcion_lf'])){
        $error=1;
        $mensaje="Debe ingresar Descripción del Lugar Fisico (*)";
    }

}

//Rescata los datos
if(!empty($_GET['id_lf']) and empty($_POST['primera']))
{
    $sql = "SELECT * FROM  lugares_fisicos WHERE id_lf='".$_GET['id_lf']."' ";
    $rec = mysql_query($sql);
    $row = mysql_fetch_array($rec);
    $_POST=$row;
    $_POST["centros_costos"]=$row["id_cc"];
    
}

//Cosulta si el PROVEEDOR existe
$sql="SELECT  * FROM  lugares_fisicos WHERE id_lf='".$_POST['id_lf']."' ";
$rec=mysql_query($sql);
$row=mysql_fetch_array($rec);

if(!empty($_POST['accion'])){

    if($error==0){

        if($_POST['accion']=="guardar"){
            // Formateo de fecha_ingreso
            $fecha_ingreso = strftime("%Y-%m-%d   %T", time());

            $sql_ins = "INSERT INTO lugares_fisicos (descripcion_lf, observacion_lf,observacion_lf1,observacion_lf2, rut_empresa, usuario_ingreso, fecha_ingreso, id_cc) ";
            $sql_ins.= "VALUES ('".$_POST['descripcion_lf']."','".$_POST['observacion_lf']."','".$_POST['observacion_lf1']."','".$_POST['observacion_lf2']."','".$_SESSION['empresa']."','".$_SESSION['user']."', '".$fecha_ingreso."','".$_POST['centros_costos']."')";
            $consulta=mysql_query($sql_ins,$con);
            if($consulta)
                $mensaje=" Ingreso Correcto ";
            $mostrar=1;

            $consulta = "SELECT MAX(id_lf) as id_lf FROM lugares_fisicos WHERE rut_empresa='".$_SESSION["empresa"]."'";
            $resultado=mysql_query($consulta);
            $fila=mysql_fetch_array($resultado);

            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'lugares_fisicos', '".$fila["id_lf"]."', '2'";
            $sql_even.= ", 'INSERT:descripcion_lf=".$_POST['descripcion_lf'].", observacion_lf=".$_POST['observacion_lf'].", ";
            $sql_even.= "rut_empresa=".$_SESSION['empresa'].", usuario_ingreso=".$_SESSION['user'].", fecha_ingreso=".$fecha_ingreso.",";
            $sql_even.= " id_cc=".$_POST['centros_costos']."', '".$_SERVER['REMOTE_ADDR']."', 'insercion', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);

        }else{


            $sql_update = "UPDATE lugares_fisicos ";
            $sql_update.= "SET descripcion_lf='".$_POST['descripcion_lf']."', observacion_lf='".$_POST['observacion_lf']."',observacion_lf1='".$_POST['observacion_lf1']."',observacion_lf2='".$_POST['observacion_lf2']."', id_cc='".$_POST['centros_costos']."' ";
            $sql_update.= "WHERE id_lf='".$_POST['id_lf']."' ";
            $consulta=mysql_query($sql_update,$con);
            if($consulta)
                $mensaje=" Actualizacion Correcta ";
            $mostrar=1;

            $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
            $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
            $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'lugares_fisicos', '".$_POST['id_lf']."', '3'";
            $sql_even.= ", 'INSERT:descripcion_lf=".$_POST['descripcion_lf'].", observacion_lf=".$_POST['observacion_lf'].", id_cc=".$_POST['centros_costos']."', '".$_SERVER['REMOTE_ADDR']."', 'actualizacion', '1', '".date('Y-m-d H:i:s')."')";
            mysql_query($sql_even, $con);
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

        <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
           <tr>
            <!-- Boton para volver -->
            <td id="titulo_tabla" style="text-align:center;">
                <a href="?cat=2&sec=21">
                    <img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Lugares Fisicos">
                </a>
            </td>
            </tr>
        </table>
<?php
if($mostrar==0){
 ?>
    <form action="?cat=2&sec=22&action=<?=$action; ?>" method="POST">
        <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
        <tr>
            <td  colspan="2">
                <label>Descripción:</label><label style="color:red">(*)</label>
                <br/>
                <textarea cols="110" rows="2" name="descripcion_lf" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["descripcion_lf"];?></textarea>
            </td>
        </tr>
        <tr>
            <td  colspan="2">
                <label>Observación:</label>
                <br/>
                <textarea cols="110" rows="2" name="observacion_lf" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observacion_lf"];?></textarea>
            </td>
        </tr>
        <tr>
            <td  colspan="2">
                <label>Observación 1:</label>
                <br/>
                <textarea cols="110" rows="2" name="observacion_lf1" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observacion_lf1"];?></textarea>
            </td>
        </tr>
        <tr>
            <td  colspan="2">
                <label>Observación 2:</label>
                <br/>
                <textarea cols="110" rows="2" name="observacion_lf2" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observacion_lf2"];?></textarea>
            </td>
        </tr>
        <tr>
            <td>
            <label>Centro de Costos:</label><br/>
            <select name="centros_costos" style='width: 150px; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;width:190px'>
<?php
                $sql2 = "SELECT id_cc, descripcion_cc FROM centros_costos WHERE 1=1 ORDER BY descripcion_cc ";
                $res2 = mysql_query($sql2,$con);
?>
                    <option value='0' <? if (isset($_POST['centros_costos']) == 0) echo 'selected'; ?> class="fo" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;">---</option>
                <?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                    <option value='<? echo $row2["id_cc"]; ?>' <? if ($row2['id_cc'] == $_POST['centros_costos']) echo "selected"; ?> class="fo"  style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?  echo $row2["descripcion_cc"];?></option>
                    <?php
                }

?>
            </select>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;"  colspan="2">
                <div style="width:100%; height:auto; text-align:right;">
                    <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                    <?
                    if(empty($row['id_lf']))
                    {
                        echo  " value='guardar' >Guardar";
                    }
                    else
                    {
                        echo " value='editar' >Actualizar";
                    }
                    ?>
                </button>
            </div>
            <input  type="hidden" name="primera" value="1"/>
            <input  type="hidden" name="id_lf" value="<?php echo $_GET['id_lf']; ?>"/>
        </td>
    </tr>
    <tr>
        <td colspan="2" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                  (*) Campos de Ingreso Obligatorio.
        </td>
    </tr>
</table>
</form>

<?php  } ?>