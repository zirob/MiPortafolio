<?php
$action=1;
$msg="";
$codigo="";
$descripcion="";

if(isset($_GET['action'])){    
    if($_GET['action']==1){
        if($_POST['descripcion']!=null && $_POST['descripcion']!=""){
        $sql_ins="INSERT INTO bodegas (descripcion_bodega,rut_empresa ,usuario_ingreso,fecha_ingreso)
                VALUES ('".$_POST['descripcion']."','".$_SESSION['empresa']."','".$_SESSION['user']."', '".date('Y-m-d H:i:s')."')";

                if(mysql_query($sql_ins,$con)){
                   $msg ="Se ha Agregado correctamente la Bodega";
               }else{
                   $msg="Ha ocurrido un error al grabar datos intente mas tarde";
               }
        }else{
            $msg="Debe Ingresar Descripcion de la Bodega a Crear";
        }
    }else{
              $action="1&new=1";
        }
    

    if($_GET['action']==2 && isset($_GET['new']) && $_GET['new']==2){
        
        if($_POST['descripcion']!=null && $_POST['descripcion']!=""){
            $sql_update="UPDATE bodegas SET descripcion_bodega='".$_POST['descripcion']."' WHERE cod_bodega='".$_GET['cod']."'";

            if(mysql_query($sql_update,$con)){
                $msg ="Se han actualizado correctamente los datos";
            }else{
                $msg="Ha ocurrido un error al actualizar intente mas tarde";
            }
        }else{
            $msg="Debe Ingresar Descripcion de la Bodega a Crear";
        }
    }


    if($_GET['action']==2 && isset($_GET['cod'])){
        $qry_prv = "SELECT * FROM bodegas WHERE cod_bodega ='".$_GET['cod']."'";

        $sel_prv = mysql_query($qry_prv,$con);


        $row = mysql_fetch_assoc($sel_prv);

        $codigo=$row['cod_bodega'];
        $descripcion=$row['descripcion_bodega'];

        $action="2&new=2&cod=".$_GET['cod'];
    }
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
<form action="?cat=3&sec=2&action=<?=$action; ?>" method="POST">
    <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
      <td id="titulo_tabla" style="text-align:center;"> <a href="?cat=3&sec=1"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Bodegas"></a></td></tr>
    
    
    <tr>
        <td  colspan="2"><label>Descripcion:(*)</label><br /><textarea cols="110" rows="2" name="descripcion"><?=$descripcion;?></textarea></td>
    </tr>
    <tr>
        <td style="text-align: right;"  colspan="2"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>
