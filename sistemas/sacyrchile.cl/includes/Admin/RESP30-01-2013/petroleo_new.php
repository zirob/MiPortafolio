<?php
$action="";
// Destinacion Procesos Productivos
$error="";
$msg="";
        $litros_pp="";
        $ie_recuperable="";

        // Destinacion Vehiculos Transporte

        $litros_vh="";
        $ie_no_recuperable="";

        // utlizados
        $litros_utilizados="";
        $total_ie_utilizado="";

        // Saldos
        $saldo_litros="";
        $saldo_ie="";
        $saldo=0;
        // Datos Factura
        $total_ief="";
        $valor_iev="";
        $valor_ief="";
        $litros="";
        $valor_factura="";
        $num_factura="";
        $dia="";
        $mes="";
        $anio="";
        
if(isset($_GET['action'])){    
    if($_GET['action']==1 && !isset($_GET['id_petroleo'])){
        
        $old = "SELECT dia,mes, agno, saldo_litros, valor_IEF FROM petroleo ORDER BY agno desc, mes desc, dia desc LIMIT 1";
        $old_res = mysql_query($old,$con);
        
        if(mysql_num_rows($old_res)!=NULL){
            $old_row = mysql_fetch_assoc($old_res);

            $saldo = $old_row['saldo_litros'];
        }
        if(isset($_POST['litros']) && $_POST['litros']!= "" && isset($_POST['valor_ief']) && $_POST['valor_ief']!=""){
            $total_ief = ($_POST['valor_ief']*$_POST['litros']);
        }else{
            $total_ief=0;
        }
        
        $sql_ins="INSERT INTO petroleo (dia,mes,agno ,rut_empresa ,num_factura ,litros,valor_factura,valor_IEF ,valor_IEV,
                total_IEF ,utilizado_litros ,utilizado_total_IE ,destinacion_PP_litros ,destinacion_PP_IE_Recuperable, 
                destinacion_VT_litros,destinacion_VT_IE_no_Recuperable,saldo_litros,saldo_impto_especifico,usuario_ingreso,fecha_ingreso)
                VALUES ('".$_POST['dia']."','".$_POST['mes']."', '".$_POST['anio']."', '".$_SESSION['empresa']."', '".$_POST['num_factura']."', 
                '".$_POST['litros']."', '".$_POST['valor_factura']."', '".number_format($_POST['valor_ief'], 2, ',', '')."', '".number_format($_POST['valor_iev'], 2, ',', '')."', ".$total_ief.", 
                '".$_POST['litros_utilizados']."', '".$_POST['total_ie_util']."', '".$_POST['dest_pp_litros']."', '".$_POST['ief_pp_recuperable']."',
                '".$_POST['dest_vt_litros']."', '".$_POST['ief_no_recuperable']."' , '".( $_POST['litros'] + $saldo )."' , '".($_POST['litros']*$_POST['valor_ief'])."',
                '".$_SESSION['user']."','".date('Y-m-d H:i:s')."')";

    
        if($_POST['dia']!="" && !empty($_POST['dia'])){
            if($_POST['mes']!="" && !empty($_POST['mes'])){
                if($_POST['anio']!="" && !empty($_POST['anio'])){
                         if($_POST['valor_ief']!="" && !empty($_POST['valor_ief'])){
                              
                                 if(mysql_query($sql_ins,$con)){
                                               $msg ="Se Agrego correctamente La Factura de Petroleo";
                                           }else{
                                               $error="Ha ocurrido un error al grabar datos intente mas tarde";
                                           }
                            }else{
                                $error="Debe ingresar Valor Impuesto Fijo (IEF) (*)";
                            }    
                        }else{
                    $error="Debe ingresar A&ntilde;o de la Factura (*)";
                }
            }else{
                $error="Debe ingresar Mes de la Factura (*)";
            }
        }else{
            $error="Debe ingresar Dia de la Factura (*)";
        }
    }
        
    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        if(isset($_POST['litros']) && $_POST['litros']!= "" && isset($_POST['valor_ief']) && $_POST['valor_ief']!=""){
            $total_ief = ($_POST['valor_ief']*$_POST['litros']);
        }else{
            $total_ief=0;
        }
        
        $old ="SELECT litros, saldo_litros, valor_IEF FROM petroleo WHERE dia='".$_POST['dia']."' and mes='".$_POST['mes']."' and agno='".$_POST['anio']."'";
        $old_r = mysql_query($old,$con);
        $old_w = mysql_fetch_assoc($old_r);
        
        
        if($_POST['litros']<$old_w['litros']){
            $diff = $old_w['litros'] - $_POST['litros'];
           $total_ief = $old_w['valor_IEF'] * $_POST['litros'];
           $saldo_litros = $old_w['saldo_litros']- $diff;
                   
           
           $ss = "SELECT * FROM petroleo WHERE dia > '".$_POST['dia']."' and mes >= '".$_POST['mes']."' and agno >= '".$_POST['anio']."'";
           $sr = mysql_query($ss,$con);
         
           if(mysql_num_rows($sr)!=NULL){
              
               while($sw = mysql_fetch_assoc($sr)){
               
               $lit = $sw['saldo_litros'];
               $ief_sl = $sw['valor_IEF'];
               $d = $sw['dia'];
               $m = $sw['mes'];
               $a = $sw['agno'];
               
               $lit = $lit - $diff;
               
               $rss = "UPDATE petroleo SET saldo_litros='".$lit."', saldo_impto_especifico='".$lit*$ief_sl."' WHERE dia='".$d."' and mes='".$m."' and agno='".$a."' ";
                mysql_query($rss,$con);
              
               }
           }
          
           
           
        }elseif($_POST['litros']>$ $old_w['saldo_litros']){
            $diff = $_POST['litros'] - $old_w['litros'];
            $total_ief = $old_w['valor_IEF'] * $_POST['litros'];
            $saldo_litros = $old_w['saldo_litros']+ $diff;
            
            
           $ss = "SELECT * FROM petroleo WHERE dia > '".$_POST['dia']."' and mes >= '".$_POST['mes']."' and agno >= '".$_POST['anio']."'";
           $sr = mysql_query($ss,$con);
           
           if(mysql_num_rows($sr)!=NULL){
               while($sw = mysql_fetch_assoc($sr)){
               
               $lit = $sw['saldo_litros'];
               $ief_sl = $sw['valor_IEF'];
               $d = $sw['dia'];
               $m = $sw['mes'];
               $a = $sw['agno'];
               
               $lit = $lit + $diff;
               
               $rss = "UPDATE petroleo SET saldo_litros='".$lit."', saldo_impto_especifico='".$lit*$ief_sl."' WHERE dia='".$d."' and mes='".$m."' and agno='".$a."' ";
              
               mysql_query($rss,$con);
               }
           }
           
            
           
            
        }
        
        $sql_update="UPDATE petroleo SET total_IEF='".$total_ief."', num_factura='".$_POST['num_factura']."', litros='".$_POST['litros']."', valor_factura='".$_POST['valor_factura']."', valor_IEF='".$_POST['valor_ief']."',  valor_IEV='".$_POST['valor_iev']."', saldo_litros='".$saldo_litros."' WHERE dia='".$_POST['dia']."' and mes='".$_POST['mes']."' and agno='".$_POST['anio']."'";

        
        if($_POST['dia']!="" && !empty($_POST['dia'])){
            if($_POST['mes']!="" && !empty($_POST['mes'])){
                if($_POST['anio']!="" && !empty($_POST['anio'])){
                         if($_POST['valor_ief']!="" && !empty($_POST['valor_ief'])){
                                           if(mysql_query($sql_update,$con)){
                                               $msg ="Se Actualizo correctamente La Factura de Petroleo";
                                           }else{
                                               $error="Ha ocurrido un error al grabar datos intente mas tarde.";
                                           }
                                        }else{
                                            $error="Debe ingresar Valor Impuesto Fijo (IEF) (*)";
                                        }    
                }else{
                    $error="Debe ingresar A&ntilde;o de la Factura (*)";
                }
            }else{
                $error="Debe ingresar Mes de la Factura (*)";
            }
        }else{
            $error="Debe ingresar Dia de la Factura (*)";
        }
    }

    if($_GET['action']==2 && isset($_GET['id_petroleo'])){
        
        $ar = explode("-",$_GET['id_petroleo']);
        $qry_prv = "SELECT * FROM petroleo WHERE dia ='".$ar[0]."' and  mes='".$ar[1]."' and agno='".$ar[2]."' ";
        $sel_prv = mysql_query($qry_prv,$con);
        
        $row = mysql_fetch_assoc($sel_prv);

        // Destinacion Procesos Productivos
        $litros_pp=$row['destinacion_PP_litros'];
        $ie_recuperable=$row['destinacion_PP_IE_Recuperable'];

        // Destinacion Vehiculos Transporte

        $litros_vh=$row['destinacion_VT_litros'];
        $ie_no_recuperable=$row['destinacion_VT_IE_no_Recuperable'];

        // utlizados
        $litros_utilizados=$row['utilizado_litros'];
        $total_ie_utilizado=$row['utilizado_total_IE'];

        // Saldos
        $saldo_litros=$row['saldo_litros'];
        $saldo_ie=$row['saldo_impto_especifico'];

        // Datos Factura
        $total_ief=$row['total_IEF'];
        $valor_iev=$row['valor_IEV'];
        $valor_ief=$row['valor_IEF'];
        $litros=$row['litros'];
        $valor_factura=$row['valor_factura'];
        $num_factura=$row['num_factura'];
        $dia=$row['dia'];
        $mes=$row['mes'];
        $anio=$row['agno'];

       
            $action="2&new=2&id_petroleo=".$_GET['id_petroleo'];
    
        
    }

    
}else{
    $action ="1&new=1";
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
<form action="?cat=3&sec=10&action=<?=$action; ?>" method="POST">
     <table  id="detalle-prov" border="0" cellpadding="5" cellspacing="6">
    <tr >
      <td style="text-align:center;" colspan="3">  <a href="?cat=3&sec=7"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Facturas de Petroleo"></</a></td></tr>
    <tr>
    </tr>
    <tr>
        <td colspan="3"><label>Fecha Fac. Petroleo:(*)</label><input class="fu" type="text" name="dia" size="2" value="<?=$dia;?>"> - <input class="fu" type="text" name="mes" size="2" value="<?=$mes;?>"> - <input class="fu" type="text" name="anio" size="4"value="<?=$anio;?>"> (DD-MM-YYYY)</td>
    </tr>
    <tr>
        <td><label>Num. Factura:</label><input class="fo nume" type="text" name="num_factura" value="<?=$num_factura;?>"></td>
        <td><label>Valor Factura:</label><input class="fo nume" type="text" name="valor_factura" value="<?=$valor_factura;?>"></td>
        <td><label>Litros:</label><br><input class="fu nume" type="text" name="litros" size="4" value="<?=$litros;?>"></td>
    </tr>
    <tr>
        <td><label>Valor IEV:</label><input class="fo nume" onchange="total_ief(this.value,1);" type="text" id="valor_iev" name="valor_iev" <? if($valor_iev!=""){echo "value='".number_format($valor_iev, 2, ',', '')."'"; }?> ></td>
        <td><label>Valor IEF:(*)</label><input class="fo nume" onchange="total_ief(this.value,2);" type="text" id="valor_ief" name="valor_ief" <? if($valor_ief!=""){echo "value='".number_format($valor_ief, 2, ',', '')."'"; }?> > </td>
        <td><label>Total IEF:</label><br /><input type="text" id="total_ief_calc" name="total_ief" <? if($total_ief!=""){echo "value='".$total_ief."'"; }?> readonly="readonly"></td>
    </tr>
    <tr>
        <td><label>Litros Utilizados:</label><input type="text" name="litros_utilizados" value="<?=$litros_utilizados?>" readonly="readonly"></td>
        <td><label>Total IE Utilizado:</label><input type="text" name="total_ie_util" readonly="readonly" value="<?=$total_ie_utilizado;?>"></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3"><br />
             <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <tr >
                    <td id="titulo_tabla" style="text-align:center;" colspan="2">  Destinacion Procesos Productivos</td>
                    <td id="titulo_tabla" style="text-align:center;" colspan="2">  Destinacion Vehiculos Transporte</td>
                </tr>
                <tr>
                    <td><label>Litros:</label><br/><input type="text" size="4" name="dest_pp_litros" readonly="readonly" value="<?=$litros_pp; ?>"></td>
                    <td><label>IE Recuperable:</label><br/><input size="20" type="text" name="ief_pp_recuperable" readonly="readonly" value="<?=$ie_recuperable;?>"></td>
                    <td><label>Litros:</label><br/><input type="text" size="4" name="dest_vt_litros" readonly="readonly" value="<?=$litros_vh;?>"></td>
                    <td><label>IE No Recuperable:</label><br/><input size="20" type="text" name="ief_no_recuperable" readonly="readonly" value="<?=$ie_no_recuperable;?>"></td>
                </tr>   
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <tr>
                    <td width="130px;"></td>
                    <td><label>Saldo Litros:</label><br/><input type="text" name="saldo_litros" readonly="readonly" value="<?=$saldo_litros;?>"></td>
                    <td><label>Saldo IE:</label><br/><input type="text" name="saldo_ief" readonly="readonly" value="<?=$saldo_ie;?>"></td>
                    <td width="130px;"></td>
                </tr>
            </table>   
        </td>
        
        
    </tr>
    <tr>
        <td style="text-align: right;" colspan="3"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>
