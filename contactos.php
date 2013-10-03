<!DOCTYPE html>
<html>
<head>
    <title>Portafolio | Boris Ramírez S.</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-glyphicons.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-2">
                <img src="http://placehold.it/150x150" alt="" class="img-thumbnail img-responsive">
            </div> <!-- col-lg-2 -->
            <div class="col-md-10">
                  <h4>
                      Boris Ramírez S. Joven Informatico, Geeks por naturaleza, amante de la tecnologia y el conocimiento web.
                  </h4>
                  <p>
                    <a class="btn btn-primary btn-large " href="doc/Curriculum Vitae - Boris Ramirez Saavedra.pdf" download="Curriculum Vitae - Boris Ramirez Saavedra">Descargue C.V.</a>
                    <!-- <a href="https://docs.google.com/file/d/0BzjNkunnxnVzb2o4Zm80QlRabmc/edit?usp=sharing" class="btn btn-primary btn-large ">Descargue C.V.</a> -->
                  </p>
            </div>
        </div>
    </div>
</div>

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
                <a href="http://www.twitter.com/Zirohb">
                    <img src="img/FollowOnTwitter.png" alt="Twitter">
                </a>
            </p>
            <p>
                <a href="http://www.twitter.com/Zirohb">
                    <img src="img/linkedin.png" alt="Twitter">
                </a>
            </p>
        </div>
    </div>

</div><!-- tab-pane -->
<?
include('footer.html');
?>