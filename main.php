<?
include('header.html');
?>
<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <h1 class="text-center">Sistemas</h1>
                  <!-- <h4>Ingeniero Ejecución en Computación e Informática</h4>     -->
                  
                 <!--  <p>
                   <a class="btn btn-primary btn-large " href="doc/Curriculum Vitae - Boris Ramirez Saavedra.pdf" download="Curriculum Vitae - Boris Ramirez Saavedra">Descargue C.V.</a>
                   <a href="https://docs.google.com/file/d/0BzjNkunnxnVzb2o4Zm80QlRabmc/edit?usp=sharing" class="btn btn-primary btn-large ">Descargue C.V.</a>
                 </p> -->
            </div>
        </div>
</div>

<br><br>

<!-- Contenido -->
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
                  
                  <!-- Primer Sistema -->
                  <div class="panel panel-default">
                      <div class="panel-body">
                            <!-- Primer trabajo -->
                          <div class="row">
                                <div class="col-md-6">
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
                                                  <dl class="dl-horizontal _dl-horizontal">
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
                                      </div>
                                </div>
                                <div id="myGallery" class="col-md-6 image-overlay">
                                    <a href="#">
                                        <img src="data:application/octet-stream;base64,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" alt="placeholder" class="img-rounded">
                                        <div class="caption">
                                              <h3>Texto de prueba</h3>
                                              <p>Put a Caption Here</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                      </div><!--Fin panel-body -->
                  </div><!--Fin panel-default -->

                  <!-- <hr> -->

                  <!-- Segundo trabajo -->
                  <div class="panel panel-default">
                      <div class="panel-body">
                            <div class="row">
                                  <div class="col-md-6">
                                          <ul class="nav nav-tabs">
                                                  <li class="active" ><a href="#trabajos2" data-toggle="tab">Descripción</a></li>
                                                  <li ><a href="#contacto2" data-toggle="tab">Ficha Tecnica</a></li>
                                          </ul>
                                          <div class="tab-content">
                                               <div class="tab-pane fade in active" id="trabajos2">
                                                          <h3>Administración de Ordenes de Compras y Trabajos.</h3>
                                                          <p>Sistema encargado de la gestión y administración ordnes de compra, ordenes de trabajo, entrada y salida de petroleo, 
                                                          productos y desarrollo de pre-inventario. Desarrollado para la empresa encargada de proyectos viales Sacyr Chile. </p>
                                               </div><!-- div trabajos -->
                                               <div class="tab-pane fade" id="contacto2">
                                                      <dl class="dl-horizontal _dl-horizontal">
                                                          <dt>Sistema</dt>
                                                          <dd>Administración de Ordenes de Compras y Trabajos.</dd>
                                                          <dt>Empresa</dt>
                                                          <dd>SRB Chile S.A.</dd>
                                                          <dt>Cliente</dt>
                                                          <dd>Sacyr Chile S.A.</dd>
                                                          <dt>Desarrollo</dt>
                                                          <dd>PHP, MySQL, JavaScript y Jquery.</dd>
                                                      </dl>
                                                </div>
                                          </div>
                                  </div>
                                  <div class="col-md-6">
<?php
// $Consulta = new ConsultaSQL();
// $sql = "select usuario, contrasena from usuarios where usuario='admin'";
// $res = $Consulta->query($sql);
// $row = $Consulta->fetch($res)) {
?>

                                      <a href="sistemas/sacyrchile.cl" target="_blank"><img src="http://placehold.it/500x300" alt="placeholder" class="img-rounded img-responsive"></a>
                                  </div> 
                              </div>
                        </div><!--Fin panel-body -->
                  </div><!--Fin panel-default -->

         </div>
    </div>  
</div>
</body>