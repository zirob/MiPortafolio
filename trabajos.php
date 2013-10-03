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
            <li class="active"><a href="trabajos.php">Trabajos</a></li>
            <li><a href="contactos.php">Contactos</a></li>
        </ul>
    </div>
</nav>

<!-- Contenido -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
        <!-- .... -->

<div class="tab-content">
                <div class="tab-pane active" id="trabajos"> 
                    <div class="row">
                        <!-- Primer trabajo -->
                        <div class="col-md-4 col-lg-3 ">
                            <a href="#"><img src="http://placehold.it/300x200" alt="placeholder" class="thumbnail"></a>
                        </div> 
                        <div class="col-md-8 col-lg-9">
                            <dl class="dl-horizontal ">
                              <dt>Sistema</dt>
                              <dd>Gestión de Órdenes de Compra.</dd>
                              <dt>Empresa</dt>
                              <dd>Sigtec Ltda.</dd>
                              <dt>Descripción</dt>
                              <dd>Sistema encargado del proceso de registro de órdenes de compra, recepción de productos y pago. Disponía información para un sistema de remuneraciones ERP.</dd>
                              <dt>Cliente</dt>
                              <dd>Constructora Aitue S.A.</dd>
                          </dl>
                      </div>

                      <div class="row">
                          <div >
                           <p><a href="#" class="btn btn-success">Probar Aplicación &raquo</a></p>
                       </div>
                   </div>
               </div>
           </div>
           <hr>
           <div class="row">
            <br>
            <div class="col-lg-3">
                <a href="../sacyrchile.cl" target="_blank"><img src="http://placehold.it/300x200" alt="placeholder" class="pull-left thumbnail"></a>
            </div> 
            <div class="col-lg-9">
                <dl class="dl-horizontal ">
                  <dt>Sistema</dt>
                  <dd>Administración de Ordenes de Trabajo y Compras.</dd>
                  <dt>Empresa</dt>
                  <dd>SRB Chile S.A.</dd>
                  <dt>Descripción</dt>
                  <dd>Sistema Administrador de proveedores, usuarios, asignar permisos, nuevos proveedores, agregar productos, asignar productos a una bodega en particular, realizar Solicitudes y ordenes de Compra, obtención de Reportes, etc. Para la Empresa Sacyr Chile, Empresa encargada de Proyectos Viales. </dd>
                  <dt>Cliente</dt>
                  <dd>Sacyr Chile S.A.</dd>
              </dl>
              <div class="col-lg-offset-2">
                <p><a href="../sacyrchile.cl" target="_blank" class="btn btn-success">Probar Aplicación &raquo</a></p>
            </div>
        </div>

    </div><!-- row -->
</div><!-- tab-pane -->
<?
include('footer.html');
?>