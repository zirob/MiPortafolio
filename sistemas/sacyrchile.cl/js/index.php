<?php 
session_start();
require_once 'includes/Conexion.php';
require_once 'includes/functions.php';

//Nuevas Asignaciones
$tipo=1;
$user=$_SESSION["user"];
$empresa=$_SESSION["empresa"];
$user_nombre=$_SESSION["nombre"];
//Originalmente se asignan vacias 
//


if(isset($_SESSION["user"]) && $_SESSION["user"]!=null && !empty($_SESSION["user"]) && empty($_GET["login"]))
{
    $user = $_SESSION["user"];
    $tipo = $_SESSION["tipo"];
    $empresa = $_SESSION["empresa"];
    $user_nombre = $_SESSION["nombre"];
}


$con = Conexion();
if(isset($_GET['exit']) && $_GET['exit']==1){

    @session_destroy();
    $parametros_cookies = session_get_cookie_params(); 
    @setcookie(session_name(),0,1,$parametros_cookies["path"]);
    $cat = 1;
}



if(!empty($_GET["cat"]) && isset($_SESSION["user"]))
	{
        $cat = $_GET["cat"];
    }
	else
	{
        $cat = 99;
    }
    
    if(!empty($_GET["sec"]) && isset($_SESSION["user"]))
	{
        $sec = $_GET["sec"];
    }
	else
	{
        $sec = 1;
    }

if($user=="")
{
	if(!empty($_POST["user"]) && !empty($_POST["pass"]) && !empty($_GET["login"]))
	{	
			$user = $_POST["user"];
			$pass = $_POST["pass"];
			//CODIFICA LA CLAVE
			$pass= md5($pass);
			$sql = "SELECT * FROM usuarios WHERE estado_usuario=1 and contrasena='".$pass."' and usuario ='".$user."'";
			$qry = mysql_query($sql, $con);     
			if(mysql_num_rows($qry)!=NULL)
			{
				while($res = mysql_fetch_assoc($qry))
				{
	
							if(isset($res["usuario"]) && !empty($res["usuario"]))
							{
								$_SESSION["user"] = $res["usuario"];
								$_SESSION["nombre"] = $res["nombre"];
								$_SESSION["tipo"] = 1;
								$_SESSION["empresa"] = $res["rut_empresa"];
							
								
								$user=$_SESSION["user"];
								$tipo=1;
								$emp = $_SESSION["empresa"];
								if($res['cambio_password']==1)
								{
										$cat = 2;
										$sec = 20;
								}
								else
								{        
										if(!empty($_GET["cat"]))
										{
											$cat = $_GET["cat"];
										}
										else
										{
											$cat = 1;
										}
								} 
							}
							else
							{
								$error = "Debe ingresar los datos solicitados o usuario no existe";
							}
	
				}
			}
			else
			{
				$error = "Debe ingresar los datos solicitados o usuario no existe";
			} 
			
		}
    else
   {
		$cat=99;
   }
} 

?>
<!DOCTYPE >
<html>
    <head>
      
     
        <!-- <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" /> -->
        <link rel="stylesheet" href="js/jquery.ui.plupload/css/jquery.ui.css" type="text/css" />
        <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> -->
        <script type="text/javascript" src="js/jquery.mins.js"></script>
        <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script> -->
        <script type="text/javascript" src="js/query.ui.min.js"></script>
        <!--<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>-->
        <script type="text/javascript" src="js/browserplus.js"></script>
        <link rel="stylesheet" href="js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
        <script type="text/javascript" src="js/plupload.js"></script>
        <script type="text/javascript" src="js/jquery-barcode.js"></script>
        <script type="text/javascript" src="js/jquery.numeric.js"></script>
        <script type="text/javascript" src="js/jquery-print.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        <script type="text/javascript" src="js/plupload.gears.js"></script>
        <script type="text/javascript" src="js/plupload.silverlight.js"></script>
        <script type="text/javascript" src="js/plupload.flash.js"></script>
        <script type="text/javascript" src="js/plupload.browserplus.js"></script>
        <script type="text/javascript" src="js/plupload.html4.js"></script>
        <script type="text/javascript" src="js/plupload.html5.js"></script>
        <script type="text/javascript" src="js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
        <script type="text/javascript" src="js/plupload.full.js"></script>
        <script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
        <script src="js/jquery.Rut.js" type="text/javascript"></script>
        <script src="js/jquery.tipTip.js" type="text/javascript"></script>
        <link rel="stylesheet" href="css/tipTip.css" type="text/css" />
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>.:: Sacyr ::.  Administracion </title>
        
        <link href="css/style.css" type="text/css" rel="stylesheet">
        <link href="css/style_admin.css" type="text/css" rel="stylesheet">
        <link href="css/style_vistas.css" type="text/css" rel="stylesheet">
          
        <link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />
        <link rel="stylesheet" type="text/css" href="css/ddsmoothmenu-v.css" />
        <script src="js/jqbanner.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="css/jqbanner.css" />

        <script type="text/javascript" src="js/ddsmoothmenu.js"></script>
        
    </head>
    <body>
        <?php
        if($cat!=null && !empty($cat) && $tipo==1){
        
            switch($cat){
                case 1:
                    $pag="includes/home.php";
                    break;
                case 2:
                    switch($sec){
                        
                        case 1:
                            $pag="includes/Admin/empresa_admin.php";
                            break;
                        case 2:
                            $pag="includes/Admin/empresa_new.php";
                            break;
                        case 3:
                            $pag="includes/Admin/empresa_detalle.php";
                            break;
                        case 4:
                            $pag="includes/Admin/user_admin.php";
                            $img="img/titulos/tit-usuarios.png";
                            break;
                        case 5:
                            $pag="includes/Admin/user_new.php";
                            $img="img/titulos/tit-usuarios.png";
                            break;
                        case 6:
                            $pag="includes/Admin/user_detalle.php";
                            $img="img/titulos/tit-usuarios.png";
                            break;
                        case 7:
                            $pag="includes/Admin/proveedores.php";
                            $img="img/titulos/tit-proveedores.png";
                            break;
                        case 8:
                            $pag="includes/Admin/proveedores_new.php";
                            $img="img/titulos/tit-proveedores.png";
                            break;
                        case 9:
                            $pag="includes/Admin/proveedores_detalle.php";
                            $img="img/titulos/tit-proveedores.png";
                            break; 
                        case 10:
                            $pag="includes/Admin/Solic_OC_list.php";
                            $img="img/titulos/tit-solcompra.png";
                            break;
                        case 11:
                            $pag="includes/Admin/Solic_OC_admin.php";
                            $img="img/titulos/tit-solcompra.png";
                            break;
                        case 12:
                            $pag="includes/Admin/centro_costos.php";
                            $img="img/titulos/tit-ccostos.png";
                            break;
                        case 13:
                            $pag="includes/Admin/centro_costos_new.php";
                            $img="img/titulos/tit-ccostos.png";
                            break;
                        case 14:
                            $pag="includes/Admin/solic_a_OC.php";
                            $img="img/titulos/";
                            break;
                        case 15:
                            $pag="includes/Admin/OC.php";
                            $img="img/titulos/tit-ordcompra.png";
                            break;
                        case 16:
                            $pag="includes/Admin/OC_admin.php";
                            $img="img/titulos/tit-ordcompra.png";
                            break;
                        case 17:
                            $pag="includes/Admin/OT.php";
                            $img="img/titulos/tit-ordtrabajo.png";
                            break;
                        case 18:  
                            $pag="includes/Admin/OT_admin.php";
                            $img="img/titulos/tit-ordtrabajo.png";
                            break;
                        case 19:
                            $pag="includes/Admin/OC_ver.php";
                            $img="img/titulos/tit-ordtrabajo.png";
                            break;
                        case 20:
                            $pag="includes/Admin/cambio_pass.php";
                            $img="img/titulos/tit-pass.png";
                            break;
			case 21:
                            $pag="includes/Admin/lugares_fisicos.php";
                            //$img="img/titulos/tit-pass.png";
                            break;
			case 22:
                            $pag="includes/Admin/lugares_fisicos_new.php";
                            //$img="img/titulos/tit-pass.png";
                            break;
                        default:
                            $pag="includes/Admin/user_admin.php";
                            $img="img/titulos/tit-usuarios.png";
                    }
                    break;
                 case 3:
                      switch($sec){
                        
                        case 1:
                            $pag="includes/Admin/bodegas.php";
                            $img="img/titulos/tit-bodegas.png";
                            break;
                        case 2:
                            $pag="includes/Admin/bodegas_new.php";
                            $img="img/titulos/tit-bodegas.png";
                            break;
                        case 3:
                            $pag="includes/Admin/productos.php";
                            $img="img/titulos/tit-productos.png";
                            break;
                        case 4:
                            $pag="includes/Admin/productos_new.php";
                            $img="img/titulos/tit-productos.png";
                            break;
                         case 5:
                            $pag="includes/Admin/productos_detalle.php";
                             $img="img/titulos/tit-productos.png";
                            break;
                        case 6:
                            $pag="includes/Admin/productos_detalle_new.php";
                            $img="img/titulos/tit-productos.png";
                            break;
                        case 7:
                            $pag="includes/Admin/petroleo.php";
                            $img="img/titulos/tit-petroleo.png";
                            break;
                        case 8:
                            $pag="includes/Admin/productos_detalle_asignar.php";
                            $img="img/titulos/tit-productos.png";
                            break;
                        case 9:
                            $pag="includes/Admin/productos_detalle_editar.php";
                            $img="img/titulos/tit-productos.png";
                            break;
                        case 10:
                            $pag="includes/Admin/petroleo_new.php";
                            $img="img/titulos/tit-petroleo.png";
                            break;
                        case 11:
                            $pag="includes/Admin/petroleo_salida.php";
                            $img="img/titulos/tit-petroleo.png";
                            break;
                        case 12:
                            $pag="includes/Admin/petroleo_salida_admin.php";
                            $img="img/titulos/tit-petroleo.png";
                            break;
                        default:
                            $pag = "includes/Admin/bodegas.php";
                        }
                   break;
              case 4:
                  switch($sec){
                        
                        case 1:
                            $pag="includes/Admin/reportes_1.php";
                            $img="img/titulos/tit-rep-ordenescompra.png";
                            break;
                        case 2:
                            $pag="includes/Admin/reportes_2.php";
                            $img="img/titulos/tit-rep-ordenestrabajo.png";
                            break;
                        case 3:
                            $pag="includes/Admin/reportes_3.php";
                            $img="img/titulos/tit-rep-petroleo.png";
                            break;
                        case 4:
                            $pag="includes/Admin/reportes_4.php";
                            $img="img/titulos/tit-rep-productos.png";
                            break;
                        case 5:
                            $pag="includes/Admin/OC_excel.php";
                            break;
                        case 6:
                            $pag="includes/Admin/OC_pdf.php";
                            break;
                        case 7:
                            $pag="includes/Admin/OT_excel.php";
                            break;
                        case 8:
                            $pag="includes/Admin/OT_pdf.php";
                            break;
                        case 9:
                            $pag="includes/Admin/petroleo_excel.php";
                            break;
                        case 10:
                            $pag="includes/Admin/petroleo_pdf.php";
                            break;
                        case 11:
                            $pag="includes/Admin/productos_excel.php";
                            break;
                        case 12:
                            $pag="includes/Admin/productos_detalle_rep.php";
                            $img="img/titulos/tit-rep-productos.png";
                            break;
                        case 13:
                            $pag="includes/Admin/petroleo_detalle_salida.php";
                            $img="img/titulos/tit-rep-petroleo.png";
                            break;
                  }
                  break;
              default:
                            $pag = "includes/login.php";
                   
            }
        }
        ?>
        <div id="main-sacyr">
            <? if($cat!=99){?>
            <div id="main-banner"><img src="img/sacyr.png" border="0" style="float:left;width: 300px; height: 152px;">
                <div id="img-rotate">
                            <div id="jqb_object">

                            <div class="jqb_slides">
                            <div class="jqb_slide" title="" ><span><img src="img/banner1_web.jpg"></span></div>
                            <div class="jqb_slide" title="" ><span><img src="img/banner2_web.jpg"></span></div>
                            <div class="jqb_slide" title=""><span><img src="img/banner3_web.jpg"></span></div>
                            
                            <div class="jqb_bar">
                            <div class="jqb_info"></div>
                            <div id="btn_next" class="jqb_btn jqb_btn_next"></div>
                            <div id="btn_pauseplay" class="jqb_btn jqb_btn_pause"></div>
                            <div id="btn_prev" class="jqb_btn jqb_btn_prev"></div>
                            </div>

                            </div>
                </div>
            </div>
            <div id="banner-div">&nbsp;</div>
            <div id="usuario-div"><label style="font-weight: bold;">Bienvenido:</label> <? echo utf8_encode($_SESSION['nombre']);?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="?exit=1">Logout</a></div>
            <? } ?>
            
            <? if(!empty($cat) && isset($_SESSION["user"])){ ?><? include("includes/menu.php");?> <? } ?>
            <div id="main-body" <?if($cat==99){ echo "style='background-color:#fff;'"; }?>>
                <?if($cat!=99 && $cat!=1){?>
                    <div id="titulo-main"><img src="<?=$img;?>"></div>
                <?}?>
                <?php 
                 if(!empty($error) && $error!=""){
                 ?>
                <div id="main-error"><? echo $error;?></div>
                <?php
                 }
                include $pag; 


                ?>
            </div>
            <? if($cat!=99){?>
            <div id="main-footer"><img src="img/footer.png" width="1000px" border="0"></div>
            <? }else{ ?>
             <div id="main-footer"><img src="img/footer.png" width="1000px" border="0"></div>   
            <?} ?>
        </div>
    <script>
         $("#rut").Rut();
    $(document).ready(function(){
        $(".listado_datos:even").css("background-color", "#356AF4"); // filas pares
        $('input#codigo_producto').numeric();
        $('input#cant_item').numeric();
       // $('input.nume').numeric();
   }); 
    
    
    </script>
    <script>
        $(function(){  $(".someClass").tipTip(); });
    </script>
        
    
 <script type="text/javascript"> 
            $(function() { 
                var uploader = new plupload.Uploader({ 
                    runtimes : 'gears,html5,flash,silverlight,browserplus', 
                    browse_button : 'pickfiles', max_file_size : '10mb', 
                    url : 'includes/upload.php', 
                    flash_swf_url : 'js/plupload.flash.swf', 
                    silverlight_xap_url : 'js/plupload.silverlight.xap', 
                    filters : [ {title : "Image files", extensions : "jpg,gif,png,pdf,doc,docx,xls,xlsx,txt"}, {title : "Zip files", extensions : "zip,rar"} ], 
                    resize : {width : 320, height : 240, quality : 90} }); 
                    uploader.bind('Init', function(up, params) { 
                        //$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>"); 
                        
                    }); 
                    uploader.bind('FilesAdded', function(up, files) { $.each(files, function(i, file) { 
                           // $('#filelist').append( '<div id="' + file.id + '">' + 'Archivo: ' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>' ); 
                            $('#filelist').append( '<div id="' + file.id + '">' + 'Archivo: ' + file.name.replace(/\s/g,"_") + ' <label><b></b></label>' + '</div>' ); 
                            $('#archivos_cargados').append('<input name="archivos[]" value="' + file.name.replace(/\s/g,"_") + '" type="hidden" >');
                        }); 
                    }); 
                    uploader.bind('UploadProgress', function(up, file) { 
                        $('#' + file.id + " b").html(file.percent + "%"); }); 
                        $('#uploadfiles').click(function(e) { uploader.start();
                            e.preventDefault(); 
                        }); 
                        uploader.init();
                       
                    }); 
        </script>   
      
 <script type="text/javascript">
 
            ddsmoothmenu.init({
                    mainmenuid: "smoothmenu1", //menu DIV id
                    orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                    classname: 'ddsmoothmenu', //class added to menu's outer DIV
                    //customtheme: ["#1c5a80", "#18374a"],
                    contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            })

           ddsmoothmenu.init({
                    mainmenuid: "smoothmenu2", //Menu DIV id
                    orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
                    classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
                    //customtheme: ["#804000", "#482400"],
                    contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            })
 
</script>

    </body>
</html>
