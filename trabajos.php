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
            <li class="active"><a href="trabajos.php">Trabajos</a></li>
            <li><a href="contactos.php">Contactos</a></li>
        </ul>
    </div>
    
</nav>

<!-- Contenido -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- <div class="tab-content">
                  <div class="tab-pane active" id="trabajos"> --> 
                      <div class="row">
                            <!-- Primer trabajo -->
                            <div class="col-md-4 col-lg-3 ">
                                <a href="#"><img src="http://placehold.it/300x200" alt="placeholder" class="thumbnail"></a>
                            </div> 

                            <div class="col-md-8 col-lg-9">
                                  <ul class="nav nav-tabs">
                                      <li class="active" ><a href="#trabajos1" data-toggle="tab">Descripción</a></li>
                                      <li ><a href="#contacto1" data-toggle="tab">Ficha Tecnica</a></li>
                                  </ul>
                                  <div class="tab-content">
                                      <div class="tab-pane active" id="trabajos1">
                                              <h3>Sistema Gestión Ordenes de Compras.</h3>
                                              <p>Desarrollado para una Constructora, encargado del proceso de registro de órdenes de compra, recepción de 
                                              productos. Sistema que disponía información para un sistema de remuneraciones ERP.</p>
                                      </div><!-- div trabajos -->
                                      <div class="tab-pane" id="contacto1">
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
                                      </div><!-- div trabajos -->
                                  </div><!-- tab-content -->
                            </div>

                      </div>
                  <hr>
                  <div class="row">
                      <br>
                      <div class="col-md-4 col-lg-3">
                          <a href="../sacyrchile.cl" target="_blank"><img src="img/sacyrchile.png" alt="placeholder" class="pull-left thumbnail"></a>
                      </div> 
                      <div class="col-md-8 col-lg-9">
                              <ul class="nav nav-tabs">
                                      <li class="active" ><a href="#trabajos2" data-toggle="tab">Descripción</a></li>
                                      <li ><a href="#contacto2" data-toggle="tab">Ficha Tecnica</a></li>
                              </ul>
                              <div class="tab-content">
                                   <div class="tab-pane active" id="trabajos2">
                                              <h3>Sistema Administración de Ordenes de Trabajo y Compras.</h3>
                                              <p>Sistema Administrador de proveedores, usuarios, asignar permisos, nuevos proveedores, agregar productos, asignar productos a una bodega en particular, realizar Solicitudes y
                                               ordenes de Compra, obtención de Reportes, etc. Para la Empresa Sacyr Chile, Empresa encargada de Proyectos Viales. </p>
                                   </div><!-- div trabajos -->
                                   <div class="tab-pane" id="contacto2">
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
                                    </div>
                              </div>
                              <!-- <div class="col-lg-offset-2">
                                <p><a href="../sacyrchile.cl" target="_blank" class="btn btn-success">Probar Aplicación &raquo</a></p>
                              </div> -->
                      </div>
                  <!-- </div>
            </div> -->
<?
include('footer.html');
?>