<?php
$msg="";
$error="";
$id = $_GET['id_petroleo'];
$aux = explode("-",$id);
$dia = $aux[0];
$mes = $aux[1];
$anio = $aux[2];


$dia2="";
$mes2="";
$anio2="";

$autorizado="";
$retira="";
$observaciones="";
$litros="";
$documento="";
$action="";
$cod_producto="";
$centro_costo="";
if(isset($_GET['id_petroleo']) && isset($_GET['action']) && $_GET['new']==1){
    
    if(isset($_POST['cod_producto']) && !empty($_POST['cod_producto']) && $_POST['cod_producto']!=NULL && $_POST['cod_producto']!=""){
        if(isset($_POST['detalle_prod']) && !empty($_POST['detalle_prod']) && $_POST['detalle_prod']!=NULL && $_POST['detalle_prod']!=""){
            if(isset($_POST['documento']) && !empty($_POST['documento']) && $_POST['documento']!=NULL && $_POST['documento']!=""){
                if(isset($_POST['litros']) && !empty($_POST['litros']) && $_POST['litros']!=NULL && $_POST['litros']!=""){
                    if(isset($_POST['autoriza']) && !empty($_POST['autoriza']) && $_POST['autoriza']!=NULL && $_POST['autoriza']!=""){
                        if(isset($_POST['retira']) && !empty($_POST['retira']) && $_POST['retira']!=NULL && $_POST['retira']!=""){
                               $s = "SELECT * FROM detalles_productos WHERE cod_detalle_producto=".$_POST['detalle_prod'];
                                $re = mysql_query($s,$con);
                                $r = mysql_fetch_assoc($re);
                                
                                
                                $pet = "SELECT * FROM petroleo WHERE dia='".$_POST['dia']."' and mes='".$_POST['mes']."' and agno='".$_POST['anio']."'";
                                
                                if($r['especifico']==1){
                                    
                                }else{
                                    
                                }
                                
                                
                                
                                $centro_costo = $r['centro_costo'];
                                
                                $sql ="INSERT INTO salida_petroleo(id_salida_petroleo, rut_empresa, dia, mes, agno, cod_producto, cod_detalle_producto,num_doc,litros,centro_costo,persona_autoriza,persona_retira,observacion,usuario_ingreso,fecha_ingreso)
                                 VALUES (NULL, '".$_SESSION['empresa']."', '".$_POST['dia']."', '".$_POST['mes']."', '".$_POST['anio']."', '".$_POST['cod_producto']."', '".$_POST['detalle_prod']."', '".$_POST['documento']."', '".$_POST['litros']."', '".$centro_costo."', '".$_POST['autoriza']."', '".$_POST['retira']."','".$_POST['observaciones']."', '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."');";
                                
                                if(mysql_query($sql,$con)){
                                        
                            //no especificos
                                $noEspecifico ="SELECT SUM(litros) as litros_noespecifico FROM salida_petroleo s inner join detalles_productos d on s.cod_detalle_producto = d.cod_detalle_producto WHERE d.especifico = 2 and s.dia='".$_POST['dia']."' and s.mes='".$_POST['mes']."' and s.agno='".$_POST['anio']."'";
                                $noEspecifico_res = mysql_query($noEspecifico,$con);
                                $noEspecifico_row = mysql_fetch_assoc($noEspecifico_res);
                                $litros_no_especificos = $noEspecifico_row['litros_noespecifico'];
                                //especificos
                                $especifico ="SELECT SUM(litros) litros_especificos FROM salida_petroleo s inner join detalles_productos d on s.cod_detalle_producto = d.cod_detalle_producto WHERE d.especifico = 1 and s.dia='".$_POST['dia']."' and s.mes='".$_POST['mes']."' and s.agno='".$_POST['anio']."'";
                                $especifico = mysql_query($especifico,$con);
                                $especifico_row = mysql_fetch_assoc($especifico);
                                $litros_especificos = $especifico_row['litros_especificos'];


                                $litros_utilizados = "SELECT SUM(litros) as total_utilizados FROM salida_petroleo WHERE dia='".$_POST['dia']."' and mes='".$_POST['mes']."' and agno='".$_POST['anio']."'";
                                $litros_utilizados_res = mysql_query($litros_utilizados,$con);                
                                $litros_utilizados_row = mysql_fetch_assoc($litros_utilizados_res);                

                                
                                $petroleo = "SELECT litros, valor_IEF FROM petroleo WHERE dia='".$_POST['dia']."' and mes='".$_POST['mes']."' and agno='".$_POST['anio']."'";
                                $petroleo_res = mysql_query($petroleo,$con);
                                
                                if(mysql_num_rows($petroleo_res)!=NULL){
                                    $petroleo_row = mysql_fetch_assoc($petroleo_res);

                                    $litros = $petroleo_row['litros'];
                                    $IEF = $petroleo_row['valor_IEF'];
                                }else{
                                    $ss = "SELECT dia,mes, agno, saldo_litros, valor_IEF FROM petroleo ORDER BY agno desc, mes desc, dia desc LIMIT 1";
                                    $rr = mysql_query($ss,$con);
                                    $rwr = mysql_fetch_assoc($rr);
                                    
                                    $litros = $rwr['saldo_litros'];
                                    $IEF = $rwr['valor_IEF'];
                                }


                                $total_utilizados = $litros_utilizados_row['total_utilizados'];                
                                $ief_tulizado =  $total_utilizados * $IEF;               

                                $pp_recuperable = $litros_especificos * $IEF;
                                $ie_no = $litros_no_especificos * $IEF;
                                 
                               // $saldo_litros = 0;
                               // $ss = "SELECT dia,mes, agno, saldo_litros FROM petroleo ORDER BY agno desc, mes desc, dia desc LIMIT 1";
                               // $rr = mysql_query($ss,$con);
                               // $rwr = mysql_fetch_assoc($rr);
                                
                               // $saldo_litros = $saldo_litros + $rwr['saldo_litros'];
                                
                                $saldo_litros = $litros - $total_utilizados;
                                $saldo_ie = $saldo_litros * $IEF;
                                $petroleo_upd = "UPDATE petroleo SET utilizado_litros='".$total_utilizados."', utilizado_total_IE='".$ief_tulizado."', destinacion_PP_litros='".$litros_especificos."', 
                                                destinacion_PP_IE_Recuperable='".$pp_recuperable."', destinacion_VT_litros='".$litros_no_especificos."', destinacion_VT_IE_no_Recuperable='".$ie_no."', saldo_litros='".$saldo_litros."', 
                                                    saldo_impto_especifico='".$saldo_ie."' WHERE dia='".$_POST['dia']."' and mes='".$_POST['mes']."' and agno='".$_POST['anio']."'";            
                                    mysql_query($petroleo_upd,$con);            
                                    
                                
                               $msg="Ha sido agregada correctamente la Salida de Petroleo";
                            }else{
                                $error="Ha Ocurrido un Error al Grabar los Datos";
                            }
                           }else{
                             $error="Debe Ingresa el Nombre de la Persona que Retira Petroleo";
                        }
                    }else{
                        $error="Debe Ingresar el Nombre de la Persona que Autoriza la Salida de Petroleo";
                    }

                }else{
                    $error="Debe Ingresar la Cantidad de Litros";
                }

            }else{
                $error="Debe Ingresar el Número de Documento";
            }

        }else{
            $error="Debe Seleccionar un Detalle de Producto valido";
        }
    }else{
        $error="Debe Seleccionar un Producto valido";
    }
    
    
    
    
    
    
}


if(isset($_GET['id_petroleo']) && isset($_GET['id_salida']) && $_GET['id_petroleo']!="" && $_GET['id_salida']){
    $sql ="UPDATE salida_petroleo SET    WHERE";
    if(mysql_query($sql,$con)){
        
    }else{
        
    }
}else{
    $action="1&new=1&id_petroleo=".$id;
}
?>
<?
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
<form action="?cat=3&sec=12&action=<?=$action;?>" method="POST">
<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="" colspan=3"> <? echo $dia."-".$mes."-".$anio;?> ::.. <a href="?cat=3&sec=11&id_petroleo=<?=$dia."-".$mes."-".$anio;?>"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Salidas de Petroleo"></a></td></tr>
    </tr>
    <tr>
        <td colspan="2" id="detalle_prod">
            <label>Producto:(*)</label<br/>
            <select name="cod_producto"  onchange="busca_prod_detalle_2(this.value,'<?=$_SESSION['empresa'];?>')">
                <option value=""> --- </option>
                <? 
                    $s = "SELECT * FROM productos p INNER JOIN detalles_productos d ON p.cod_producto = d.cod_producto WHERE d.patente <> '' and p.rut_empresa = '".$_SESSION['empresa']."'  ORDER BY p.descripcion";
                    $res = mysql_query($s,$con);
                    if(mysql_num_rows($res)!=NULL){
                        while($r = mysql_fetch_assoc($res)){
                            ?>
                <option value="<?=$r['cod_producto'];?>" <?if($cod_producto==$r['cod_producto']){ echo "SELECTED";}?>><?=$r['descripcion'];?></option>
                <?
                        }
                    }
                ?>
            </select>
        </td>
        <td><label>Fecha Salida: (dd-mm-YYYY)(*)</label><br/>
            <input class="fu" type="text" name="dia" size="2" value="<?=$dia;?>" readonly> - <input class="fu" type="text" name="mes" size="2" value="<?=$mes;?>" readonly> - <input class="fu" type="text" name="anio" size="4" value="<?=$anio;?>" readonly>
        </td>
    </tr>
    <tr>
        <td><label>Numero Documento:(*)</label><br/><input type="text" class="fu" size="20" name="documento" value="<?=$documento;?>"></td>
        <td><label>Litros:(*)</label><br/><input size="10" class="fu nume" type="text" name="litros" value="<?=$litros;?>"></td>
        <td>
        </td>
    </tr>
    <tr>
      
        
    </tr>
    <tr>
        <td><label>Autorizado por:(*)</label><br/><input type="text" class="fo" name="autoriza" value="<?=$autorizado;?>"></td>
        <td><label>Retirado por:(*)</label><br/><input type="text" class="fo" name="retira" value="<?=$retira;?>"></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3"><label>Observacion:</label><br /><textarea cols="100" rows="4" name="observaciones"><?=$observaciones;?></textarea></td>
   </tr>
   <tr>
       <td colspan="3"><input type="submit" value="Grabar"></td>
   </tr>
   </table>
</form>