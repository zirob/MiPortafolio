<?php
$mostrar=0;
$action=1;

if(isset($_GET['action']) && $_GET['action']==1){
    
    if(isset($_POST['pass1']) && !empty($_POST['pass1'])){
        if(isset($_POST['pass2']) && !empty($_POST['pass2'])){
            if($_POST['pass1']==$_POST['pass2']){
                
                $_POST['pass1']=md5($_POST['pass1']);
                $sql="UPDATE usuarios SET contrasena='".$_POST['pass1']."', cambio_password=0 WHERE rut_empresa='".$_SESSION['empresa']."' and usuario='".$_SESSION['user']."'";
                $mostrar=1;
                if(mysql_query($sql,$con)){
                    $msg=" Password del Usuario Actualizado";
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
        $mostrar=0;
?>
        <div id="main-error" style=' width:100%; height:auto; border-top: solid 3px red ;border-bottom: solid 3px red; color:red; text-align:center;font-family:tahoma; font-size:18px;'>
<?php echo $error;?>
        </div>
<?
    }elseif($msg){ $mostrar=1;
?>
    <div id="main-ok" style=' width:100%; height:auto; border-top: solid 3px blue;border-bottom: solid 3px blue;color:blue; text-align:center; font-family:tahoma; font-size:18px;'>
    <?php echo $msg;?>
    </div>
<?
    }
?>


<?
   if($mostrar==0){
?>

<form action="?cat=2&sec=20&action=<?=$action; ?>" method="POST">
<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr >
      <td id="titulo_tabla" style="text-align:center;" colspan="2">  </td></tr>
    <tr>
    </tr>
    <tr>
        <td colspan="2"><label></label></td>
    </tr>
    <tr>
    <tr>
        <td><label>Ingrese Password:</label><label style="color:red">(*)</label></td>
        <td><input class="fu" type="password" id="pass1" name="pass1" size="20" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"> </td>
    </tr>
    <tr>
        <td><label>Reingrese:</label><label style="color:red">(*)</label></td>
        <td><input class="fu" type="password" id="pass2" name="pass2" size="20" style="background-color: rgb(255,255,255); border:1px solid #06F; color:#000000;"> </td>
    </tr>
    <tr>
        <td style="text-align: right;" colspan="2"><input type="submit" value="Grabar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
    </tr>
</table>
</form>

<?
    }
?>
