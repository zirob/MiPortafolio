<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>

<?php

// validaciones
$mostrar=0;
if(!empty($_POST['accion'])){

    $error=0;
    if(empty($_POST['descripcion'])){
        $error=1;
        $mensaje="Debes ingresar Descripción del Activo";
    }
    if($_GET["action"]==1){
        if(empty($_POST['familia'])){
            $error=1;
            $mensaje="Debes ingresar Familia del Activo";
        }
        if(empty($_POST['subfamilia'])){
            $error=1;
            $mensaje="Debes ingresar Subfamilia del Activo";
        }
    }
}

// Rescata los datos //////////
if(!empty($_GET['id_prod']) and empty($_POST['primera']))
{
    $sql = "SELECT * FROM productos WHERE cod_producto='".$_GET["id_prod"]."' AND rut_empresa='".$_SESSION['empresa']."'";
    $rec = mysql_query($sql);
    $row1 = mysql_fetch_array($rec);
    $_POST = $row1;
    $_POST["familia"] = $row1["id_familia"]; 
    $_POST["subfamilia"] = $row1["id_subfamilia"]; 
}             

if($_GET["action"]==2){
    $sql = "SELECT * FROM productos WHERE cod_producto='".$_GET["id_prod"]."' AND rut_empresa='".$_SESSION['empresa']."'";
    $rec = mysql_query($sql);
    $row1 = mysql_fetch_array($rec);
    $_POST["familia"] = $row1["id_familia"]; 
    $_POST["subfamilia"] = $row1["id_subfamilia"]; 
}

//Discrimina si guarda o edita
if(!empty($_POST['accion'])){

if($_GET["action"]==1){
    //////////////////////////////////  Obtiene el Codigo del Activo ////////////////////////////////////
    $dig_fam = strlen($_POST["familia"]);//Obtiene cantidad de digitos                                  // 
    $dig_subfam = strlen($_POST["subfamilia"]);//Obtiene cantidad de digitos                            //    
    if($dig_fam==1){                                                                                    //
        $_POST["cod_producto"] = "330".$_POST["familia"];                                               //
    }else{                                                                                              //
        $_POST["cod_producto"] = "33".$_POST["familia"];                                                //
    }                                                                                                   //
    if($dig_subfam==1){                                                                                 //
        $_POST["cod_producto"].="0".$_POST["subfamilia"];                                               //
    }else{                                                                                              //
        $_POST["cod_producto"].=$_POST["subfamilia"];                                                   //
    }                                                                                                   //
    $num=0;                                                                                             //
    $sql = "SELECT * FROM productos WHERE rut_empresa='".$_SESSION['empresa']."' ";                     //
    $sql.= "AND id_familia='".$_POST["familia"]."' AND id_subfamilia='".$_POST["subfamilia"]."' ";      //
    $res = mysql_query($sql,$con);                                                                      //
    $num = mysql_num_rows($res);                                                                        //
    $num++;                                                                                             //
    $dig_sec = strlen($num);//Obtiene cantidad de digitos                                               //
    while($dig_sec<7){                                                                                  //
    $_POST["cod_producto"].="0";                                                                        //
        $dig_sec++;                                                                                     //
    }                                                                                                   //
    $_POST["cod_producto"].=$num;                                                                       //
    //////////////////////////////////////////////////////////////////////////////////////////////////////
}

    if($error==0){

        if($_POST['accion']=="guardar"){


                $sql_ins = "INSERT INTO productos (cod_producto , rut_empresa, descripcion, observaciones, observaciones1, observaciones2, usuario_ingreso, fecha_ingreso, id_familia, id_subfamilia) ";
                $sql_ins.= "VALUES ('".$_POST["cod_producto"]."','".$_SESSION['empresa']."', '".$_POST["descripcion"]."','".$_POST["observaciones"]."','".$_POST["observaciones1"]."','".$_POST["observaciones2"]."', ";
                $sql_ins.= "'".$_SESSION['user']."', '".date('Y-m-d H:i:s')."', '".$_POST["familia"]."', '".$_POST["subfamilia"]."')";
                $consulta=mysql_query($sql_ins,$con);
                if($consulta)
                    $mensaje=" Activo Ingresado Correctamente ";
                    $mostrar=1;

                $consulta = "SELECT MAX(cod_producto) as cod_producto FROM productos WHERE rut_empresa='".$_SESSION["empresa"]."'";
                $resultado=mysql_query($consulta);
                $fila=mysql_fetch_array($resultado);
                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos', '".$fila["id_det_sc"]."', '2'";
                $sql_even.= ", 'INSERT:cod_producto=".$_POST["cod_producto"]." , rut_empresa=".$_SESSION['empresa'].", ";
                $sql_even.= "descripcion=".$_POST["descripcion"].", observaciones=".$_POST["observaciones"].", usuario_ingreso=".$_SESSION['user'].",";
                $sql_even.= " fecha_ingreso=".date('Y-m-d H:i:s').", id_familia=".$_POST["familia"].", id_subfamilia=".$_POST["subfamilia"]."', ";
                $sql_even.= "'".$_SERVER['REMOTE_ADDR']."', 'Insert de activo=', '1', '".date('Y-m-d H:i:s')."')";
                mysql_query($sql_even, $con);    
        }else{
                $sql_up = "UPDATE productos SET descripcion='".$_POST["descripcion"]."', observaciones='".$_POST["observaciones"]."', observaciones1='".$_POST["observaciones1"]."', observaciones2='".$_POST["observaciones2"]."' ";
                $sql_up.= "WHERE cod_producto='".$_GET["id_prod"]."' AND rut_empresa='".$_SESSION["empresa"]."'";
                $consulta=mysql_query($sql_up);
                if($consulta)
                    $mensaje=" Actualizacion de Activo Correcta ";
                    $mostrar=1;

                $sql_even = "INSERT INTO eventos (usuario, rut_empresa, tabla_evento, id_registro_tabla_evento, tipo_evento ";
                $sql_even.= ", parametros_tipo_evento, ip_origen, observaciones, estado_evento, fecha_evento) ";
                $sql_even.= "VALUES('".$_SESSION["user"]."', '".$_SESSION["empresa"]."', 'productos', '".$_GET["id_prod"]."', '3'";
                $sql_even.= ", 'UPDATE:productos SET descripcion=".$_POST["descripcion"].", observaciones=".$_POST["observaciones"]."', '".$_SERVER['REMOTE_ADDR']."', 'Update de activos', '1', '".date('Y-m-d H:i:s')."')";
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

  

        

//Detalle del Producto
if($_GET["action"]==2){
     $_GET["action"]="2&new=2&id_prod=".$_GET['id_prod'];
}    

 ?>

    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">   <a href="?cat=3&sec=3"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Activos"></a></td></tr>
    
    <tr>
    </table>

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

<?php
if($mostrar==0){
?>  

<form action="?cat=3&sec=4&action=<?=$_GET["action"]; ?>" method="POST">
    <table border="0" style="width:80%;border-collapse:collapse;" id="detalle-prov" cellpadding="3" cellspacing="1"  style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif; ">
    
     <tr> 
        <!--<td >
            <label>Codigo:</label><br>
                <input class='fo' type="text" name="cod_producto"  onKeyPress='ValidaSoloNumeros()' style="width:200px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;" value="<?=$_POST["cod_producto"];?>" readonly >
        </td>-->
        <td>
            <label>Familia:</label><label style="color:red;">(*)</label><br>
            <select name="familia"   class="fo" <?if($_GET['action']==2) echo 'disabled';?> onChange='submit()'>
<?                
                $sql2 = "SELECT * FROM familia WHERE 1=1 ORDER BY id_familia ";
                $res2 = mysql_query($sql2,$con);
?>
                <option value='0' <? if (isset($_POST["familia"]) == 0) echo 'selected'; ?> class="fo">---</option>
<?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                   <option value='<? echo $row2["id_familia"]; ?>' <? if ($row2['id_familia'] == $_POST['familia']) echo "selected"; ?> class="fo"><?  echo $row2["descripcion_familia"];?></option>
<?php
                }
?>
            </select>
        </td>

        <td>
            <label>SubFamilia:</label><label style="color:red;">(*)</label><br>
            <select name="subfamilia"   class="fo" <?if($_GET['action']==2 || empty($_POST["familia"])) echo 'disabled';?>>
<?                
                $sql2 = "SELECT * FROM subfamilia WHERE id_familia='".$_POST["familia"]."' ORDER BY id_subfamilia ";
                $res2 = mysql_query($sql2,$con);
?>
                <option value='0' <? if (isset($_POST["subfamilia"]) == 0) echo 'selected'; ?> class="fo">---</option>
<?php              
                while($row2 = mysql_fetch_assoc($res2)){
?>
                   <option value='<? echo $row2["id_subfamilia"]; ?>' <? if ($row2['id_subfamilia'] == $_POST['subfamilia']) echo "selected"; ?> class="fo"><?  echo $row2["descripcion_subfamilia"];?></option>
<?php
                }
?>
        </td>
         <td><label>Codigo SAP:</label><br>
           <input type='text' disabled='true' name='codigo_sap' value='<?=$_REQUEST["codigo_sap"];?>' style='border:1px solid #09F;background-color:#FFFFFF;color:#000066;width:100px;' > 
        </td>
    </tr>
    <tr>
<?
                if($_POST["action"]==2){
?>
                    <input type=hidden name='id_familia' value='<?=$_POST["id_familia"];?>."'/>
                    <input type=hidden name='id_subfamilia' value='<?=$_POST["id_sufamilia"];?>."'/>
<?
                }
?>
       <td colspan="3"><label>Descripcion:</label><label style="color:red;">(*)</label><br /><textarea cols="110" rows="2" name="descripcion" style="width:800px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["descripcion"];?></textarea></td>
      
    </tr>
    
    <tr>
        <td colspan="3"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones" style="width:800px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones"];?></textarea></td>
    </tr>
    <tr>
        <td colspan="3"><label>Observaciones 1:</label><br /><textarea cols="110" rows="2" name="observaciones1" style="width:800px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones1"];?></textarea></td>
    </tr>
    <tr>
        <td colspan="3"><label>Observaciones 2:</label><br /><textarea cols="110" rows="2" name="observaciones2" style="width:800px; text-align=left; background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"><?=$_POST["observaciones2"];?></textarea></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left;">
            <div id="barcode" ></div>
            <a href="javascript:imprSelec('barcode')">
                
                <?if($_GET["action"]==2){ ?><img src="img/printer_ok.png" width="36px" height="36px" border="0"  class="toolTIP" title="Imprimir Codigo de Barra"><?}?>
            </a>
        </td>
    </tr>
    
    
    <tr>
        <td  colspan="3" style="text-align: right;">
            <div style="width:100%; height:auto; text-align:right;">
                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
                        <?

                        if($_GET["action"]==1)
                        {
                            echo  " value='guardar' >Guardar</button><input name='bandera_guardar' type='hidden' value='1'>";
                        }
                        if($_GET["action"]==2)
                        {
                            echo " value='editar' >Actualizar</button>";
                        }
                        ?>

                    </div>
                    <input  type="hidden" name="primera" value="1"/>


        </td>
    </tr>
    <tr>
                <td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                  (*) Campos de Ingreso Obligatorio.
            </td>
    </tr>
    </table>

</form>
<?
}
?>


<?
// var_dump($_POST);

if($_POST["cod_producto"]!=""){
?>
    <script type="text/javascript">
        $(document).ready(
            function(){
                $('#barcode').barcode("<?=$_POST["cod_producto"];?>", "code128");
            }
        );
    </script> 

    <script type="text/javascript">
        function imprSelec(barcode)
        {
            var ficha=document.getElementById(barcode);
            var ventimp=window.open(' ','popimpr');

ventimp.document.write("<table border=0><tr><td width=\"5\" height=\"90\"></td><td align=\"center\">"+ficha.innerHTML+"</td><td <td width=\"30\"></td><td>"+ficha.innerHTML+"</td><td width=\"0\"></td></tr></table>");
	    
ventimp.document.write("<table border=0><tr><td width=\"5\" height=\"130\"></td><td align=\"center\">"+ficha.innerHTML+"</td><td <td width=\"30\"></td><td>"+ficha.innerHTML+"</td><td width=\"0\"></td></tr></table>");
            ventimp.document.close();
            ventimp.print();
            ventimp.close();}
    </script>
<?
}
?>