<?php if($tipo==1 && isset($cat) && $cat!=null && $cat!=99){ ?>
<?
  //Buscamos los permisos
  $sql="SELECT  permisos FROM  usuarios WHERE usuario='".$_SESSION['user']."' and rut_empresa='".$_SESSION['empresa']."'";
  $rec=mysql_query($sql);
  $row=mysql_fetch_array($rec);
  //Calculo de permisos iniciales
  for($i=1;$i<=20;$i++)
  {
    $var[$i]=substr($row['permisos'],$i-1,1);
  }

?>
<!-- Petroleo:
 - Petroleo Entrada
 - Petroleo Salida

Activos
  - Activos

Productos
 - Productos

Pre-Inventario
 - Pre-Inventario. -->

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="?cat=1">Home</a></li>
<li><a href="#">SACYR</a>
  <ul>
  <? 

  if($var[1]==1) echo "<li><a href='?cat=2&sec=4'>Usuarios</a></li>";
  if($var[2]==1) echo "<li><a href='?cat=2&sec=12'>Centros de Costos</a></li>";
  if($var[3]==1) echo "<li><a href='?cat=3&sec=1'>Bodegas</a></li>";
  if($var[4]==1) echo "<li><a href='?cat=2&sec=21'>Lugares Fisicos</a></li>";
  if($var[20]==1) echo "<li><a href='?cat=2&sec=24'>Plantas</a></li>";
  ?>
  </ul>
</li>
<li><a href="#">Compras</a>
  <ul>
  <?
  if($var[5]==1) echo "<li><a href='?cat=2&sec=10'>Solicitudes Compra</a></li>";
  if($var[6]==1) echo "<li><a href='?cat=2&sec=15'>Ordenes de Compra</a></li>";
  if($var[7]==1) echo "<li><a href='?cat=2&sec=7'>Proveedores</a></li>";
  ?>
  </ul>
</li>
<li><a href="#">Petroleo</a>
  <ul>
  <!-- <li><a href="?cat=3&sec=3">Productos</a></li> -->
  <?
  if($var[8]==1) echo"<li><a href='?cat=3&sec=13'>Petroleo Entrada</a></li>  "; 
  if($var[9]==1) echo"<li><a href='?cat=3&sec=14'>Petroleo Salida</a></li> ";
  ?>
  </ul>
</li>
<li><a href="#">Activos</a>
  <ul>
  <!-- <li><a href="?cat=3&sec=3">Productos</a></li> -->
  <?
  if($var[10]==1)echo"<li><a href='?cat=3&sec=3' >Activos</a></li>";
  ?>
  </ul>
</li>
<li><a href="#">Productos</a>
  <ul>
  <!-- <li><a href="?cat=3&sec=3">Productos</a></li> -->
  <?
  if($var[11]==1)echo"<li><a href='?cat=3&sec=15'>Productos</a></li>";
  ?>
  </ul>
</li>
<li><a href="#">Pre-Inventario</a>
  <ul>
  <!-- <li><a href="?cat=3&sec=3">Productos</a></li> -->
  <?
  if($var[12]==1)echo"<li><a href='?cat=3&sec=37'>Pre-Inventario</a></li>";
  ?>
  </ul>
</li>
<li><a href="#">Taller</a>
  <ul>
  <?
  if($var[13]==1)echo "<li><a href='?cat=2&sec=17'>Ordenes de Trabajo</a></li>";
  ?>
  </ul>  

<li><a href="#">Reportes</a>
  <ul>
  <?
  if($var[14]==1) echo "<li><a href='?cat=4&sec=1'>Reporte Orden de Compra</a></li>";
  if($var[15]==1) echo "<li><a href='?cat=4&sec=2'>Reporte Orden de trabajo</a></li>";
  if($var[16]==1) echo "<li><a href='?cat=4&sec=5'>Reporte Entrada Petroleo</a></li>";
  if($var[17]==1) echo "<li><a href='?cat=4&sec=3'>Reporte Salida Petroleo</a></li>";
  if($var[18]==1) echo "<li><a href='?cat=4&sec=4'>Reporte Productos</a></li>";
  if($var[19]==1) echo "<li><a href='?cat=4&sec=6'>Reporte Activos</a></li>";
  ?></ul>

    
</ul>
<br style="clear: left" />
</div>
<?php } if($tipo==2){ ?>


<?php } ?>


