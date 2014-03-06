<?php
$action=1;
$msg="";
$codigo_cc="";
$descripcion="";

if(isset($_GET['action'])){    
    if($_GET['action']==1 && !isset($_GET['user'])){
        
        $rev = "SELECT * FROM centros_costos WHERE codigo_cc='".$_POST['codigo']."'";
        $res_rev =mysql_query($rev,$con);
        if(mysql_num_rows($res_rev)==null || mysql_num_rows($res_rev)==0){
            $sql_ins="INSERT INTO centros_costos (rut_empresa ,codigo_cc,descripcion_cc ,usuario_ingreso,fecha_ingreso)
                    VALUES ('".$_SESSION['empresa']."', ".$_POST['codigo'].", '".$_POST['descripcion']."', '".$_SESSION['user']."', '".date('Y-m-d H:i:s')."')";

             if($_POST['codigo']!="" && !empty($_POST['codigo'])){
                 if($_POST['descripcion']!="" && !empty($_POST['descripcion'])){
                 mysql_query($sql_ins,$con);
                $msg ="Se ha Agregado correctamente el Centro de Costos";
                 }else{
                     $error="Debe Ingresar una Descripci&oacute;n para el Centro de Costo";
                 }
            }else{
                $error="Debe Ingresar un Codigo para el Centro de Costo";

            }
        }else{
            $error="Un Centro de Costo con el Codigo ingresado ya existe, favor ingresar otro codigo";
        }
    }else{
        if(isset($_GET['id_cc'])){

        }else{
            $action="1&new=1";
        }
    }

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        $sql_update="UPDATE centros_costos SET codigo_cc='".$_POST['codigo']."', descripcion_cc='".$_POST['descripcion']."' WHERE id_cc='".$_GET['id_cc']."'";
              if($_POST['codigo']!="" && !empty($_POST['codigo'])){
                 if($_POST['descripcion']!="" && !empty($_POST['descripcion'])){
                 mysql_query($sql_update,$con);
                    $msg ="Se ha Actualizado correctamente el Centro de Costos";
                 }else{
                     $error="Debe Ingresar una Descripci&oacute;n para el Centro de Costo";
                 }
            }else{
                $error="Debe Ingresar un Codigo para el Centro de Costo";

            }
       
    }


    if($_GET['action']==2 && isset($_GET['id_cc'])){
        $qry_prv = "SELECT * FROM centros_costos WHERE Id_cc =".$_GET['id_cc']."";

        $sel_prv = mysql_query($qry_prv,$con);

        $row = mysql_fetch_assoc($sel_prv);

        $codigo_cc=$row['codigo_cc'];
        $descripcion=$row['descripcion_cc'];

        $action="2&new=2&id_cc=".$_GET['id_cc'];
    }
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
<form action="?cat=2&sec=13&action=<?=$action; ?>" method="POST">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
        <td id="titulo_tabla" style="text-align:center;"> </td>
        <td id="list_link" ><a href="?cat=2&sec=12"><img src="img/view_previous.png" width="36px" height="36px" border="0" class="toolTIP" title="Volver al listado de Centros de Costos"></</a></td></tr>
   
    <tr>
        <td  colspan="2"><label>C&oacute;digo:(*)</label><br /><input class="fu nume" type="text" name="codigo" size="20" value="<?=$codigo_cc;?>"></td>
    </tr>
    <tr>
        <td  colspan="2"><label>Descripcion:(*)</label><br /><textarea cols="110" rows="2" name="descripcion"><?=$descripcion;?></textarea></td>
    </tr>
    <tr>
        <td  colspan="2" style="text-align: right;"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>
