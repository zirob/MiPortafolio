<?
include('header.html');
?>

<!-- boton para que aparece para resoluciones pequeñas (Smartphones) -->
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Cambiar Navegación</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a href="#" class="navbar-brand">Portafolio</a>
    </div>
    <!-- Opciones del menu -->
    <div class="collapse navbar-collapse navbar-ex1-collapse ">
        <ul class="nav navbar-nav">
            <li ><a href="trabajos.php">Trabajos</a></li>
            <li class="active"><a href="contactos.php">Contactos</a></li>
        </ul>
    </div>
</nav>

<!-- Contenido -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
        <!-- .... -->


<div class="tab-pane" id="contacto">
    <div class="row">
        <div class="col-lg-7">
            <div class="page-header">
                <h2>Contactame</h2>
            </div>
            <form action="envia_mail.php" name="contactame" class="form-horizontal" role="form" method="POST">
                <div class="form-group">
                    <label for="nombre" class="control-label col-lg-3">Nombre</label>
                    <div class="col-lg-4">
                        <input type="text" name="nombre" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="apellido" class="control-label col-lg-3">Apellido</label>
                    <div class="col-lg-4">
                        <input type="text" name="apellido" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="empresa" class="control-label col-lg-3">Empresa</label>
                    <div class="col-lg-4">
                        <input type="text" name="empresa" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Mensaje" class="control-label col-lg-3">Mensaje</label>
                    <div class="col-lg-6">
                        <textarea name="mensaje" class="form-control" rows="4"></textarea> 
                    </div>
                </div>
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button type="button" class="btn btn-default">Cancelar</button>
                </div>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="page-header">
                <h2>Redes Sociales</h2>
            </div>
            <p>
                <a href="http://www.twitter.com/Zirohb" target="_blank">
                    <img src="img/sigueme_twitter.png" alt="Twitter">
                </a>
            </p>
            <p>
                <a href="http://www.linkedin.com/in/borisramirezsaavedra" target="_blank">
                    <img src="img/sigueme_linkedin.jpg" alt="Twitter">
                </a>
            </p>
        </div>
    </div>

</div><!-- tab-pane -->
<?
include('footer.html');
?>