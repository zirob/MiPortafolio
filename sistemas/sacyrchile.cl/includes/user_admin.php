<?php
//var_dump($_REQUEST);
//AL PRINCIPIO COMPRUEBO SI HICIERON CLICK EN ALGUNA PÁGINA
if(isset($_GET['page'])){
    $page= $_GET['page'];
}else{
//SI NO DIGO Q ES LA PRIMERA PÁGINA
    $page=1;
}

//ACA SE SELECCIONAN TODOS LOS DATOS DE LA TABLA
$consulta = "SELECT usuario, nombre, cargo, depto, Jefatura, fecha_ingreso FROM usuarios u inner join empresa e on u.rut_empresa = e.rut_empresa WHERE u.rut_empresa = '".$_SESSION['empresa']."'";


        // SI SE BUSCA POR EL CRITERIO DE USUARIO
if(!empty($_REQUEST['usuario']) && $_REQUEST['usuario']!="")
{ 
            //echo " buscado por usuario  <p/>";               
    $consulta .= " and usuario like '%".$_REQUEST['usuario']."%'";
}

        // SI SE BUSCA OOR EL CRITERIO DE NOMBRE
if(!empty($_REQUEST['nombre']) && $_REQUEST['nombre']!="")
{
            //echo " buscado por nombre  <p/>";
    $consulta .= " and nombre like '%".$_REQUEST['nombre']."%'";
}

        // SI SE BUSCA POR EL CRITERIO DE CARGO
if(!empty($_REQUEST['cargo']) && $_REQUEST['cargo']!="")
{
            //echo " buscado por cargo <p/>";
    $consulta .= " and cargo like '%".$_REQUEST['cargo']."%'";
}

        // SI SE BUSCA POR EL CRITERIO DE DEPARTAMENTO
if(!empty($_REQUEST['departamento']) && $_REQUEST['departamento']!="")
{
            //echo " buscado por departamento:  ". $_REQUEST['departamento']. "<p/>";
    $consulta .= " and depto like '%".$_REQUEST['departamento']."%'";
}

        // SI SE BUSCA POR EL CRITERIO DE JEFATURA
        //if(!empty($_POST['Jefatura']) && $_POST['Jefatura']!="")
if(!empty($_REQUEST['jefatura']))
{
             //echo " buscado por Jefatura:  '".$_REQUEST['Jefatura']."' <p/>";
             //echo " buscado por Jefatura:  <p/>";
            //$a = $_REQUEST['Jefatura'].selected;
            //echo "el valor es: $a";
    $consulta .= " and jefatura like '%".$_REQUEST['jefatura']."%'";
}

$consulta.=" ORDER BY usuario";

$datos=mysql_query($consulta,$con);

//MIRO CUANTOS DATOS FUERON DEVUELTOS
$num_rows=mysql_num_rows($datos);

//ACA SE DECIDE CUANTOS RESULTADOS MOSTRAR POR PÁGINA , EN EL EJEMPLO PONGO 15
$rows_per_page= 20;

//CALCULO LA ULTIMA PÁGINA
$lastpage= ceil($num_rows / $rows_per_page);

//COMPRUEBO QUE EL VALOR DE LA PÁGINA SEA CORRECTO Y SI ES LA ULTIMA PÁGINA
$page=(int)$page;
if($page > $lastpage){
    $page= $lastpage;
}
if($page < 1){
    $page=1;
}

//CREO LA SENTENCIA LIMIT PARA AÑADIR A LA CONSULTA QUE DEFINITIVA
$limit= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;



    // SI LA PÁGINA VIENE DE UN POSTBACK SE CARGAN LOS USUARIOS SEGÚN LOS CRITERIOS DE BÚSQUEDAS
if(isset($_GET['filtro']) && $_GET['filtro']==1)
{
    $qry = "SELECT usuario, nombre, cargo, depto, Jefatura, fecha_ingreso FROM usuarios u inner join empresa e on u.rut_empresa = e.rut_empresa WHERE u.rut_empresa = '".$_SESSION['empresa']."'";
    
	    // SI SE BUSCA POR EL CRITERIO DE USUARIO
    if(!empty($_REQUEST['usuario']) && $_REQUEST['usuario']!="")
    { 
			//echo " buscado por usuario  <p/>";               
        $qry .= " and usuario like '%".$_REQUEST['usuario']."%'";
    }

	    // SI SE BUSCA OOR EL CRITERIO DE NOMBRE
    if(!empty($_REQUEST['nombre']) && $_REQUEST['nombre']!="")
    {
		    //echo " buscado por nombre  <p/>";
        $qry .= " and nombre like '%".$_REQUEST['nombre']."%'";
    }
    
	    // SI SE BUSCA POR EL CRITERIO DE CARGO
    if(!empty($_REQUEST['cargo']) && $_REQUEST['cargo']!="")
    {
		    //echo " buscado por cargo <p/>";
        $qry .= " and cargo like '%".$_REQUEST['cargo']."%'";
    }

	    // SI SE BUSCA POR EL CRITERIO DE DEPARTAMENTO
    if(!empty($_REQUEST['departamento']) && $_REQUEST['departamento']!="")
    {
		    //echo " buscado por departamento:  ". $_REQUEST['departamento']. "<p/>";
        $qry .= " and depto like '%".$_REQUEST['departamento']."%'";
    }

		// SI SE BUSCA POR EL CRITERIO DE JEFATURA
        //if(!empty($_REQUEST['Jefatura']) && $_REQUEST['Jefatura']!="")
    if(!empty($_REQUEST['jefatura']))
    {
		     //echo " buscado por Jefatura:  '".$_REQUEST['Jefatura']."' <p/>";
			 //echo " buscado por Jefatura:  <p/>";
			//$a = $_REQUEST['Jefatura'].selected;
			//echo "el valor es: $a";
        $qry .= " and jefatura like '%".$_REQUEST['jefatura']."%'";
    }

    $qry.=" ORDER BY usuario";
    $qry.= " ".$limit;

}

    // SI SE CARGA RECIEN LA PÁGINA SE LISTAN TODOS LOS USAURIOS
else
{
    $qry = "SELECT usuario, 
    nombre, 
    cargo, 
    depto, 
    Jefatura, 
    fecha_ingreso 
    FROM usuarios u 
    inner join empresa e on u.rut_empresa = e.rut_empresa 
    WHERE u.rut_empresa = '".$_SESSION['empresa']."' ORDER BY usuario";
    $qry.= " ".$limit;




}

$res = mysql_query($qry,$con);

?>
<table id="list_registros" border="0" style="border-collapse:collapse; margin-top:10px;" >
	<!-- AGREGAR USUARIO -->
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="6">  </td>
        <td id="list_link" >
          <a href="?cat=2&sec=5">
             <img src="img/user1_add.png" width="36px" height="36px" border="0" class="toolTIP" 
             title="Agregar Usuario">
             </</a>
         </td>
     </tr>
 </table>
 <table id="list_registros" border="1" style="border-collapse:collapse;" >



   <!-- FILTRO DE BÚSQUEDA -->
   <!--Stylo-->
   <style>
   .fo
   {
      border:1px solid #09F;
      background-color:#FFFFFF;
      color:#000066;
      font-size:11px;
      font-family:Tahoma, Geneva, sans-serif;
      width:80%;
      text-align:center;
  }

  /* -------------------------------------------- */
  /* ----------- Pagination: Digg Style --------- */
  /* -------------------------------------------- */
  ul    { border:0; margin:0; padding:0; }
  #pagination-digg li          { border:0; margin:0; padding:0; font-size:11px; list-style:none; /* savers */ float:left; }
  #pagination-digg a           { border:solid 1px #9aafe5; margin-right:2px; }
  #pagination-digg .previous-off,
  #pagination-digg .next-off   { border:solid 1px #DEDEDE; color:#888888; display:block; float:left; font-weight:bold; margin-right:2px; padding:3px 4px; }
  #pagination-digg .next a,
  #pagination-digg .previous a { font-weight:bold; }
  #pagination-digg .active     { background:#2e6ab1; color:#FFFFFF; font-weight:bold; display:block; float:left; padding:4px 6px; /* savers */ margin-right:2px; }
  #pagination-digg a:link,
  #pagination-digg a:visited   { color:#0e509e; display:block; float:left; padding:3px 6px; text-decoration:none; }
  #pagination-digg a:hover     { border:solid 1px #0e509e; }
  </style>
  <form action="?cat=2&sec=4&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px" style="font-family:Tahoma; font-size:12px;" >Filtro:</td>
        <td style="text-align:center;"><input type="text" name="usuario"      value='<? echo  $_REQUEST['usuario']?>' class="fo" onFocus='this.value=""'></td>
        <td style="text-align:center;"><input type="text" name="nombre"       value='<? echo  $_REQUEST['nombre']?>'  class="fo" onFocus='this.value=""'></td>
        <td style="text-align:center;"><input type="text" name="cargo"        value='<? echo  $_REQUEST['cargo']?>'   class="fo" onFocus='this.value=""'></td>
        <td style="text-align:center;"><input type="text" name="departamento" value='<? echo  $_REQUEST['departamento']?>' class="fo" onFocus='this.value=""'></td>
        <td style="text-align:center;">

          <select name="jefatura"  class="fo">
              <option value="0"> --- </option>
              <option value="1" <? if($_REQUEST['jefatura'] == 1){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Dpto. Compras</option>
              <option value="2" <? if($_REQUEST['jefatura'] == 2){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Compras</option>
              <option value="3" <? if($_REQUEST['jefatura'] == 3){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Parque Maquinarias</option>
              <option value="4" <? if($_REQUEST['jefatura'] == 4){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe Grupo Obras</option>
              <option value="5" <? if($_REQUEST['jefatura'] == 5){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Gerente General</option>   
              <option value="6" <? if($_REQUEST['jefatura'] == 6){ echo "SELECTED";}?> style="background-color: rgb(255,255,255); border:1px solid #06F;color:#000000;"> Jefe de Administración</option> 
          </select>
      </td>
      <td style="text-align:center;"></td>
      <td style="text-align:right;" colspan='2'><input type="Submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"></td>
  </tr>
</form>

<!-- ENCABEZADO DE LA TABLA DE RESULTADOS -->

<tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
    <td width="30px;" style="text-align: center;" >#</td>
    <td style="text-align: center;">Usuario</td>
    <td style="text-align: center;">Nombre</td>
    <td width="150px;" style="text-align: center;">Cargo</td>
    <td width="150px;" style="text-align: center;">Departamento</td>
    <td width="100px;" style="text-align: center;">Jefatura</td>
    <td width="100px;" style="text-align: center;">Fec. Ingreso</td>
    <td width="50px;" style="text-align: center;">Editar</td>
     <td width="50px;" style="text-align: center;">Permisos</td>
</tr>    

<?
    // LISTADO DE USUARIOS
if(mysql_num_rows($res)!=null)
{
    $i=1;

    while($row = mysql_fetch_assoc($res))
    {
        ?>
        <style>
        #row:hover
        {
            background-color:#FF000;
        }
        </style>
        <tr  style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
            <td style="text-align: center;"><? echo $i; $i++; ?></td>
            <td style="text-align: center;"><?=utf8_encode($row['usuario']); ?></td>
            <td style="text-align: left;"><?=utf8_encode($row['nombre']); ?></td>
            <td style="text-align: center;"><?=$row['cargo']; ?></td>
            <td style="text-align: center;"><?=$row['depto']; ?></td>
            <td style="text-align: center;">
                <? 
                if($row['Jefatura']==1)
                   echo "Dpto. Compras";
               if($row['Jefatura']==2)
                   echo "Jefe de Compras";
               if($row['Jefatura']==3)
                   echo "Jefe Parque Maquinarias";
               if($row['Jefatura']==4)
                   echo "Jefe Grupo Obras";
               if($row['Jefatura']==5)
                   echo "Gerente General";	
               if($row['Jefatura']==6)
                   echo "Jefe de Administracion";				
               ?>

           </td>

           <td width="100px" style="text-align: center;"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
           <td style="text-align: center;">
            <a href="?cat=2&sec=5&action=2&user=<?=$row['usuario'];?>">
               <img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Usuario">
           </a>
           </td>
           <td align='center'>
           <a href="?cat=2&sec=99&action=2&user=<?=$row['usuario'];?>">
               <img src="img/candado.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Permisos">
           </a>

       </td>
   </tr>


   <?php 
}
}

	// MENSAJE INDICANDO QUE NO EXISTEN USUARIOS
else
{
    ?>
    <tr  id="mensaje-sin-reg">
      <td colspan="7" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;">No existen Usuarios para ser Desplegados</td>
  </tr>
  <? 
}
?>
</table>
<br>
<table width="900px" align="center" border="0" >
    <tr>
        <td>
            <?

//UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACIÓN SIEMPRE Y CUANDO HAYA MÁS DE UNA PÁGINA
            if($num_rows != 0){
             $nextpage= $page +1;
             $prevpage= $page -1;

             ?><ul id="pagination-digg"><?
//SI ES LA PRIMERA PÁGINA DESHABILITO EL BOTON DE PREVIOUS, MUESTRO EL 1 COMO ACTIVO Y MUESTRO EL RESTO DE PÁGINAS
             if ($page == 1) {
                ?>
                <li class="previous-off">&laquo; Previous</li>
                <li class="active">1</li> <?
                for($i= $page+1; $i<= $lastpage ; $i++){?>
                <li><a href="?&cat=2&sec=4&page=<? echo $i;?>&filtro=1&usuario=<?=$_REQUEST["usuario"];?>&nombre=<?=$_REQUEST["nombre"];?>&cargo=<?=$_REQUEST['cargo'];?>&departamento=<? echo  $_REQUEST['departamento'];?>&jefatura=<?=$_REQUEST["jefatura"];?>"><? echo $i;?></a></li>
                <? } 

 //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
                if($lastpage >$page ){?>       
                <li class="next"><a href="?&cat=2&sec=4&page=<? echo $nextpage;?>&filtro=1&usuario=<?=$_REQUEST["usuario"];?>&nombre=<?=$_REQUEST["nombre"];?>&cargo=<?=$_REQUEST['cargo'];?>&departamento=<? echo  $_REQUEST['departamento'];?>&jefatura=<?=$_REQUEST["jefatura"];?>" >Next &raquo;</a></li><?
            }else{?>
            <li class="next-off">Next &raquo;</li>
            <?  }
        } else {
    //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
            ?>
            <li class="previous"><a href="?&cat=2&sec=4&page=<? echo $prevpage;?>&filtro=1&usuario=<?=$_REQUEST["usuario"];?>&nombre=<?=$_REQUEST["nombre"];?>&cargo=<?=$_REQUEST['cargo'];?>&departamento=<? echo  $_REQUEST['departamento'];?>&jefatura=<?=$_REQUEST["jefatura"];?>"  >&laquo; Previous</a></li><?
            for($i= 1; $i<= $lastpage ; $i++){
            //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i){
                    ?>  <li class="active"><? echo $i;?></li><?
                }else{
                    ?>  <li><a href="?&cat=2&sec=4&page=<? echo $i;?>&filtro=1&usuario=<?=$_REQUEST["usuario"];?>&nombre=<?=$_REQUEST["nombre"];?>&cargo=<?=$_REQUEST['cargo'];?>&departamento=<? echo  $_REQUEST['departamento'];?>&jefatura=<?=$_REQUEST["jefatura"];?>" ><? echo $i;?></a></li><?
                }
            }
         //SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT    
            if($lastpage >$page ){    ?> 
            <li class="next"><a href="?&cat=2&sec=4&page=<? echo $nextpage;?>&filtro=1&usuario=<?=$_REQUEST["usuario"];?>&nombre=<?=$_REQUEST["nombre"];?>&cargo=<?=$_REQUEST['cargo'];?>&departamento=<? echo  $_REQUEST['departamento'];?>&jefatura=<?=$_REQUEST["jefatura"];?>">Next &raquo;</a></li><?
        }else{
            ?> <li class="next-off">Next &raquo;</li><?
        }
    }   
    ?></ul></div><?
}
?>
</td>
</tr>

</table>
