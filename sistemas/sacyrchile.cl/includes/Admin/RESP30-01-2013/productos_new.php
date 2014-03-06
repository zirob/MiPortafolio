<?php
$action=1;
$msg="";
$cod_producto="";
$tipo_producto="";
$equipo="";
$descripcion="";
$activo_fijo="";
$critico="";
$observaciones="";
$idd="";
$pasillo="";
$casillero="";

if(isset($_GET['action'])){    
    if($_GET['action']==1 && !isset($_GET['user'])){
        
         $descripcion = $_POST['descripcion'];
            $activo_fijo=$_POST['activo_fijo'];
            $critico=$_POST['critico'];
            $observaciones=$_POST['observaciones'];
            $tipo_producto = $_POST['tipo_producto'];
            $pasillo=$_POST['pasillo'];
            $casillero=$_POST['casillero'];
            
            
            $sql1 = "SELECT CAST(CAST(count(*)+1 AS UNSIGNED) AS SIGNED) FROM productos WHERE rut_empresa = '".$_SESSION['empresa']."'  and tipo_producto='".$tipo_producto."' and activo_fijo='".$activo_fijo."' and critico='".$critico."'";
            $re = mysql_query($sql1,$con);
            $ress = mysql_fetch_array($re);
            $idd = $ress[0];
                        
            if($idd<10){
                $idd = "000000".$idd;
            }else{
                if($idd<100){
                    $idd = "00000".$idd;
                }else{
                    if($idd<1000){
                        $idd = "0000".$idd;
                    }else{
                        if($idd<10000){
                            $idd = "000".$idd;
                        }else{
                            if($idd<100000){
                                $idd = "00".$idd;
                            }else{
                                if($idd<1000000){
                                    $idd = "0".$idd;
                                }
                            }
                        }
                    }
                }
            }
            
                    
            $tipo_producto=$_POST['tipo_producto'];
            if($tipo_producto<100){
                if($tipo_producto<10){
                    $tipo ="00".$tipo_producto;
                }else{
                    $tipo ="0".$tipo_producto;
                }
            }else{
                $tipo = $tipo_producto;
            }
            
               $cod_producto=$activo_fijo."".$critico."".$tipo."".$idd;
           
    
        if($cod_producto!=NULL && !empty($cod_producto)){
           if($tipo_producto!=NULL && !empty($tipo_producto) && $tipo_producto!=0){
                if($activo_fijo!=NULL && !empty($activo_fijo) && $activo_fijo!=""){
                    if($critico!=NULL && !empty($critico) && $critico!=""){
                            $sql_ins="INSERT INTO productos (cod_producto ,rut_empresa,tipo_producto ,descripcion,activo_fijo,critico,observaciones,usuario_ingreso,fecha_ingreso,pasillo,casillero)
                                    VALUES ('".$cod_producto."','".$_SESSION['empresa']."','".$tipo_producto."','".$descripcion."','".$activo_fijo."', ".$critico.", '".$observaciones."', '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."', '".$pasillo."', '".$casillero."')";
                            if(mysql_query($sql_ins,$con)){
                                 $msg ="Se ha Agregado correctamente el Producto";
                            }else{
                                 $error="Ha ocurrido un error al grabar datos intente mas tarde";
                            }
                    }else{
                        $error="Debe seleccionar si Producto es Critico";
                    }
                }else{
                    $error="Debe seleccionar si el Producto es un Activo Fijo";
                }
            }else{
                $error="Debe seleccionar un tipo de producto";
            }   
        }else{
            $error="Debe ingresar los datos obligatorios (*)";
        }       
        
                    
                    
                    
                    
    }else{
        if(isset($_GET['id_cc'])){

        }else{
            $action="1&new=1";
        }
    }

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        $sql_update="UPDATE productos SET tipo_producto='".$_POST['tipo_producto']."', descripcion='".$_POST['descripcion']."', activo_fijo='".$_POST['activo_fijo']."',critico='".$_POST['critico']."',observaciones='".$_POST['observaciones']."' WHERE cod_producto='".$_GET['id_prod']."'";

        if(mysql_query($sql_update,$con)){
            $msg ="Se han actualizado correctamente los datos";
        }else{
            $error="Ha ocurrido un error al actualizar intente mas tarde";
        }
    }


    if($_GET['action']==2 && isset($_GET['id_prod'])){
        $qry_prv = "SELECT * FROM productos WHERE cod_producto =".$_GET['id_prod']."";
        $sel_prv = mysql_query($qry_prv,$con);
        $row = mysql_fetch_assoc($sel_prv);

             
        
        
        // Datos del Producto
        $cod_producto=$row['cod_producto'];
        $tipo_producto=$row['tipo_producto'];
        $descripcion=$row['descripcion'];
        $activo_fijo=$row['activo_fijo'];
        $critico=$row['critico'];
        $observaciones=$row['observaciones'];
        
        
        //Detalle del Producto
        
        
        
        
        $action="2&new=2&id_prod=".$_GET['id_prod'];
    }
}

 if(isset($error) && !empty($error)){
        ?>
<script>
          alert(<?php echo $error;?>);
</script>
<?
    }elseif($msg){
?>
<script>
          alert(<?php echo $msg;?>);
</script>

<?
    }
?>
<form action="?cat=3&sec=4&action=<?=$action; ?>" method="POST">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;" colspan="2">   <a href="?cat=3&sec=3"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Productos"></a></td></tr>
    
    <tr>
        <td ><label>Codigo:</label><br/><input class="fu" type="text" name="cod_producto" size="20" value="<?=$cod_producto;?>" readonly></td>
        <td><label>Tipo Producto:(*)</label><br/>
            <select name="tipo_producto" <?if($tipo_producto!=""){echo "DISABLED";}?>>
                <option value="0" >---</option>
                <option value="7" <? if($tipo_producto==7){ echo "SELECTED";}?>>Equipos de Tunel</option>
                <option value="5" <? if($tipo_producto==5){ echo "SELECTED";}?>>Generador</option>
                <option value="3" <? if($tipo_producto==3){ echo "SELECTED";}?>>Herramientas</option>
                <option value="1" <? if($tipo_producto==1){ echo "SELECTED";}?>>Maquinarias y Equipo</option>
                <option value="4" <? if($tipo_producto==4){ echo "SELECTED";}?>>Muebles</option>
                <option value="6" <? if($tipo_producto==6){ echo "SELECTED";}?>>Plantas</option>
                <option value="2" <? if($tipo_producto==2){ echo "SELECTED";}?>>Vehiculo Menor</option>
                <option value="8" <? if($tipo_producto==8){ echo "SELECTED";}?>>Otros</option>
            </select>
            
            
         </td>
    </tr>
    <tr>
       <td colspan="2"><label>Descripcion:</label><br /><textarea cols="110" rows="2" name="descripcion"><?=$descripcion;?></textarea></td>
    </tr>
    <tr>
        <td><label>Activo Fijo:(*)</label><br/>
            <select name="activo_fijo" <?if($activo_fijo!=""){echo "DISABLED";}?>>
                <option value="" >---</option>
                <option value="1" <? if($activo_fijo==1){ echo "SELECTED";}?>>Si</option>
                <option value="2" <? if($activo_fijo==2){ echo "SELECTED";}?>>No</option>
            </select>
        </td>  
         <td><label>Critico:(*)</label><br/>
             <select name="critico" <?if($critico!=""){echo "DISABLED";}?>>
                <option value="" >---</option>
                <option value="1" <? if($critico==1){ echo "SELECTED";}?>>Si</option>
                <option value="2" <? if($critico==2){ echo "SELECTED";}?>>No</option>
            </select>
         </td>
    </tr>
    <tr>
        <td><label>Pasillo:</label><br/><input class="fu" type="text" name="pasillo" size="3" value="<?=$pasillo;?>"></td>
        <td><label>Casillero:</label><br/><input class="fu" type="text" name="casillero" size="3" value="<?=$casillero;?>"></td>
    </tr>
    <tr>
        <td colspan="2"><label>Observaciones:</label><br /><textarea cols="110" rows="2" name="observaciones"><?=$observaciones;?></textarea></td>
    </tr>
    <tr>
        <td><div id="barcode"></div>
             <a href="javascript:void(0)" id="imprime">Imprimir Codigo de Barra</a>
        </td>
    </tr>
    
    
    <tr>
        <td  colspan="2" style="text-align: right;"><input type="submit" value="Grabar"></td>
    </tr>
    </table>

</form>
<? if($cod_producto!=""){?>
  <script type="text/javascript">
      $(document).ready(function(){
        $('#barcode').barcode("<?=$cod_producto;?>", "code39");
      });
</script>    
<script type="text/javascript">
$("#imprime").click(function (){
$("div#barcode").printArea();
})
</script>
<? } ?>