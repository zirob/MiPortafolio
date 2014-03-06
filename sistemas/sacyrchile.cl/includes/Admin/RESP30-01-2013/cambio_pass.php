<?php

$action=1;

if(isset($_GET['action']) && $_GET['action']==1){
    
    if(isset($_POST['pass1']) && !empty($_POST['pass1'])){
        if(isset($_POST['pass2']) && !empty($_POST['pass2'])){
            if($_POST['pass1']== $_POST['pass2']){
                $sql="UPDATE usuarios SET contrasena='".$_POST['pass1']."', cambio_password=0 WHERE rut_empresa='".$_SESSION['empresa']."' and usuario='".$_SESSION['user']."'";
                if(mysql_query($sql,$con)){
                    $msg="Se Actualizó Correctamente el Password del Usuario";
                }else{
                    $error="Ocurrio un Error al Actualizar Password, Favor Reintente";
                }
                
            }else{
                $error="Los Password Ingresados deben Coincidir";
            }
        }else{
            $error="Debe Re-Ingresar un Password Valido";
        }
    }else{
        $error="Debe Ingresar un Password Valido";
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
<form action="?cat=2&sec=20&action=<?=$action; ?>" method="POST">
<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
      <td id="titulo_tabla" style="text-align:center;" colspan="3">  </td></tr>
    <tr>
    </tr>
    <tr>
        <td colspan="3"><label></label></td>
    </tr>
    <tr>
    <tr>
        <td><label>Ingrese Password:(*)</label></td>
        <td><input class="fu" type="text" id="pass1" name="pass1" size="20"> </td>
    </tr>
    <tr>
        <td><label>Reingrese:(*)</label></td>
        <td><input class="fu" type="text" id="pass2" name="pass2" size="20"> </td>
    </tr>
    <tr>
        <td style="text-align: right;" colspan="2"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>

