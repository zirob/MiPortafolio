<?php

if(isset($_GET['filtro']) && $_GET['filtro']==1){
 $sql = "SELECT p.cod_producto, d.cod_detalle_producto, c.descripcion_cc,b.descripcion_bodega, p.tipo_producto, 
        d.patente, d.producto_arrendado, p.activo_fijo, p.critico, d.especifico,d.estado_producto, p.descripcion,
        d.valor_unitario FROM productos p INNER JOIN detalles_productos d on p.cod_producto = d.cod_producto 
        inner join centros_costos c on d.centro_costo = c.Id_cc inner join bodegas b on d.asignado_a_bodega = b.cod_bodega 
        WHERE p.rut_empresa='".$_SESSION['empresa']."'";
    
        if(isset($_POST['bodega']) && $_POST['bodega']!="" && $_POST['bodega']!=0){
            $sql.=" and b.cod_bodega=".$_POST['bodega'];
        }
        if(isset($_POST['tipo']) && $_POST['tipo']!="" && $_POST['tipo']!=0){
            $sql.=" and p.tipo_producto=".$_POST['tipo'];
        }    
        if(isset($_POST['cod_barra']) && $_POST['cod_barra']!=""){
            $sql.=" and p.cod_producto=".$_POST['cod_barra'];
        }
        if(isset($_POST['nfactura']) && $_POST['nfactura']!=""){
            $sql.=" and d.num_factura=".$_POST['nfactura'];
        }
        if(isset($_POST['noc']) && $_POST['noc']!=""){
            $sql.=" and d.id_oc=".$_POST['noc'];
        }
        if(isset($_POST['especifico']) && $_POST['especifico']!="" && $_POST['especifico']!=0){
            $sql.=" and d.especifico=".$_POST['especifico'];
        }
        if(isset($_POST['activofijo']) && $_POST['activofijo']!="" && $_POST['activofijo']!=0){
            $sql.=" and p.activo_fijo=".$_POST['activofijo'];
        }
        if(isset($_POST['critico']) && $_POST['critico']!="" && $_POST['critico']!=0){
            $sql.=" and p.critico=".$_POST['critico'];
        }
        if(isset($_POST['arrendado']) && $_POST['arrendado']!="" && $_POST['arrendado']!=0){
            $sql.=" and d.producto_arrendado ='".$_POST['arrendado']."'";
        }
        if(isset($_POST['centro_costo']) && $_POST['centro_costo']!=""){
            $sql.=" and centro_costo=".$_POST['centro_costo'];
        }
        if(isset($_POST['patente']) && $_POST['patente']!=""){
            $sql.=" and d.patente like '%".$_POST['patente']."%'";
        }
        
         if(isset($_POST['estado']) && $_POST['estado']!="" && $_POST['estado']!=0){
            $sql.=" and d.estado_producto='".$_POST['estado']."'";
        }
        
        if(isset($_POST['desde']) && $_POST['desde']!="" && $_POST['desde']==""){
              $d = explode("-",$_POST['desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
            $sql .=" and p.fecha_ingreso BETWEEN '".$desde."' and '".date('Y-m-d H:i:s')."'";
          }
          if(isset($_POST['hasta']) && $_POST['hasta']!="" && $_POST['hasta']==""){
              $x = explode("-",$_POST['hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
            $sql .=" and p.fecha_ingreso BETWEEN '2010-01-01 00:00:00' and '".$hasta."'";
          }
          
          if(isset($_POST['desde']) && isset($_POST['hasta']) && $_POST['desde']!="" && $_POST['hasta']!=""){
              $d = explode("-",$_POST['desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
              
              $x = explode("-",$_POST['hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
              $sql .=" and p.fecha_ingreso BETWEEN '".$desde."' and '".$hasta."'";
          }   
    
}else{
     $sql = "SELECT p.cod_producto, d.cod_detalle_producto, c.descripcion_cc,b.descripcion_bodega, p.tipo_producto, 
        d.patente, d.producto_arrendado, p.activo_fijo, p.critico, d.especifico,d.estado_producto, p.descripcion,
        d.valor_unitario FROM productos p INNER JOIN detalles_productos d on p.cod_producto = d.cod_producto 
        inner join centros_costos c on d.centro_costo = c.Id_cc inner join bodegas b on d.asignado_a_bodega = b.cod_bodega 
        WHERE p.rut_empresa='".$_SESSION['empresa']."'";
}
?>
<form action="?cat=4&sec=4&filtro=1" method="POST">
<table style="width:1000px; background-color: #bbb;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
    <tr>
        <td colspan="11"><label>Bodega<label>
                        <select name="bodega" id="bodega">
                           <option value="0">Todas las Bodegas</option>
                            <?
                                $b = "SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION['empresa']."'";
                                $bs = mysql_query($b,$con);
                                while($br = mysql_fetch_assoc($bs)){
                            ?>
                                <option value="<?=$br['cod_bodega'];?>"><?=$br['descripcion_bodega'];?></option>
                            <? } ?>
                        </select>
        </td>
    </tr>
      <tr>
        <td colspan="11"><label>Tipo Producto<label>
                        <select name="tipo" id="tipo">
                           <option value="0">Todos los Tipos de Productos</option>
                            <option value="1">Maquinarias y Equipos</option>
                            <option value="2">Vehiculo Menor</option>
                            <option value="3">Herramientas</option>
                            <option value="4">Muebles</option>
                            <option value="5">Generador</option>
                            <option value="6">Plantas</option>
                            <option value="7">Equipos de Tunel</option>
                            <option value="8">Otros</option>
                        </select>
        
                     
                    </td>
    </tr>
    <tr>
        <td colspan="11"><label>Estado<label>
                        <select name="estado" id="estado">
                           <option value="0">Todos los Estados del Productos</option>
                            <option value="1">Disponible</option>
                            <option value="2">No Disponible</option>
                        </select>
        
                     
                    </td>
    </tr>
     <?  /* <tr>
        <td colspan="11"><label>Estado Producto<label>
                        <select name="estado" id="estado">
                           <option value="0">Todos los Estados</option>
                            <option value="2">Pendiente</option>
                            <option value="3">Aprobada</option>
                            <option value="4">Cerrada</option>
                        </select>
        </td>
    </tr> */ ?>
    <tr id="titulo-filtro-productos" style="font-size: 11px; font-weight: bold;">
        <td style="border:1px solid;">Código Barras<br/>&nbsp;</td>
        <td style="border:1px solid;">N° Factura<br/>&nbsp;</td>
        <td style="border:1px solid;">N° O.C.<br/>&nbsp;</td>
        <td style="border:1px solid;">Especifico<br/>&nbsp;</td>
        <td style="border:1px solid;">Activo Fijo<br/>&nbsp;</td>
        <td style="border:1px solid;">Critico<br/>&nbsp;</td>
        <td style="border:1px solid;">Productos<br/>Arrendados</td>
        <td style="border:1px solid;">Centro Costos<br/>&nbsp;</td>
        <td style="border:1px solid;">Petente<br/>&nbsp;</td>
        <td style="border:1px solid;">Fecha Ingreso Desde<br/>(DD-MM-YYYY)</td>
        <td style="border:1px solid;">Fecha Ingreso Hasta<br/>(DD-MM-YYYY)</td>
    </tr>
    <tr>
        <td style="border:1px solid;"><input type="text" name="cod_barra" size="12" class="fu"></td>
        <td style="border:1px solid;"><input type="text" name="nfactura" size="12" class="fu"></td>
        <td style="border:1px solid;"><input type="text" name="noc" size="12" class="fu"></td>
        <td style="border:1px solid;"><select name="especifico" style="width: 50px;"><option value="0">---</option><option value="1">Si</option><option value="2">No</option></select></td>
        <td style="border:1px solid;"><select name="activofijo" style="width: 50px;"><option value="0">---</option><option value="1">Si</option><option value="2">No</option></select></td>
        <td style="border:1px solid;"><select name="critico" style="width: 50px;"><option value="0">---</option><option value="1">Si</option><option value="2">No</option></select></td>
        <td style="border:1px solid;"><select name="arrendado" style="width: 50px;"><option value="0">---</option><option value="1">Si</option><option value="2">No</option></select></td>
        <td style="border:1px solid;">
            <SELECT name="centro_costo" style="width: 100px;">
                <option value=""> --- </option>
                <? $s = "SELECT * FROM centros_costos WHERE rut_empresa='".$_SESSION['empresa']."'";
                    $r = mysql_query($s,$con);
                    while($row = mysql_fetch_assoc($r)){ ?>
                <option value="<?=$row['Id_cc'];?>"><?=$row['descripcion_cc'];?></option>
                 <?   }
                ?>
            </SELECT>
        </td>
        <td style="border:1px solid;"><input type="text" name="patente" size="8" class="fu"></td>
        <td style="border:1px solid;"><input type="text" name="desde" size="10" class="fu"></td>
        <td style="border:1px solid;"><input type="text" name="hasta" size="10" class="fu"></td>
       
    </tr>
    <tr>
        <td colspan="6">
            <input type="submit" name="buscar" value="Buscar">
        </td>
    </tr>
</table>
</form>    

<table style="width: 1000px;margin-top:20px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td ><label>Código Barras</label></td>
       <td ><label>Id. Producto</label></td>
       <td ><label>Centro Costos</label></td>
       <td ><label>Bodega</label></td>
       <td ><label>Tipo Producto</label></td>
       <td ><label>Patente</label></td>
       <td ><label>Arrendado</label></td>
       <td ><label>Activo Fijo</label></td>
       <td ><label>Critico</label></td>
       <td ><label>Especifico</label></td>
       <td ><label>Estado</label></td>
       <td ><label>Descripcion</label></td>
       <td ><label>Valor</label></td>
    </tr>
    <? $res = mysql_query($sql,$con);
 
        if(mysql_num_rows($res)>0){
        
        while($row = mysql_fetch_assoc($res)){
            
            switch($row['tipo_producto']){
                    case 1:  $tipo="Maquinarias y Equipos"; break;
                    case 2:  $tipo="Vehiculo Menor"; break;
                    case 3:  $tipo="Herramientas"; break;
                    case 4:  $tipo="Muebles"; break;
                    case 5:  $tipo="Generador"; break;
                    case 6:  $tipo="Plantas"; break;
                    case 7:  $tipo="Equipos de Tunel"; break;
                    case 8:  $tipo="Otros"; break;
                }
                
                
                switch($row['producto_arrendado']){
                    case 0: $arrendado="Si"; break;
                    case 1:  $arrendado="Si"; break;
                    case 2:  $arrendado="No"; break;
                    }
                switch($row['activo_fijo']){
                    case 1:  $activo_fijo="Si"; break;
                    case 2:  $activo_fijo="No"; break;
                    }
                switch($row['critico']){
                    case 1:  $critico="Si"; break;
                    case 2:  $critico="No"; break;
                    }
                switch($row['especifico']){
                    case 1:  $especifico="Si"; break;
                    case 2:  $especifico="No"; break;
                    }    
                switch($row['estado_producto']){
                    case 1:  $estado="Activo"; break;
                    case 2:  $estado="Inactivo"; break;
                    }        
                
        ?>
    <tr class="listado_datos">
        <td><?=$row['cod_producto'];?></td>
        <td><?="<a href='?cat=4&sec=12&id_detalle=".$row['cod_detalle_producto']."' >".$row['cod_detalle_producto']."</a>";?></td>
        <td><?=$row['descripcion_cc'];?></td>
        <td><?=$row['descripcion_bodega'];?></td>
        <td><?=$tipo;?></td>
        <td><?=$row['patente'];?></td>
        <td><?=$arrendado;?></td>
        <td><?=$activo_fijo;?></td>
        <td><?=$critico;?></td>
        <td><?=$especifico;?></td>
        <td><?=$estado;?></td>
        <td><?=$row['descripcion'];?></td>
        <td><?=$row['valor_unitario'];?></td>
    </tr>
    <? } ?>
    
    
        <tr><td>    
        <form action="includes/Admin/productos_excel.php" method="POST">     
        <input type="text" name="rut_empresa" id="rut_empresa"  hidden="hidden" value="<? echo $_SESSION['empresa']; ?>">
        
        
        <input type="text" name="tipo" id="tipo"  hidden="hidden" value="<? if(isset($_POST['tipo'])){ echo $_POST['tipo'];}  ?>">
        <input type="text" name="bodega" id="bodega"  hidden="hidden" value="<? if(isset($_POST['bodega'])){ echo $_POST['bodega'];}  ?>">
        <input type="text" name="nfactura" id="nfactura"  hidden="hidden" value="<? if(isset($_POST['nfactura'])){ echo $_POST['nfactura'];}  ?>">
        <input type="text" name="noc" id="noc"  hidden="hidden" value="<? if(isset($_POST['noc'])){ echo $_POST['noc'];}  ?>">
        <input type="text" name="especifico" id="especifico"  hidden="hidden" value="<? if(isset($_POST['especifico'])){ echo $_POST['especifico'];}  ?>">
        <input type="text" name="activofijo" id="activofijo"  hidden="hidden" value="<? if(isset($_POST['activofijo'])){ echo $_POST['activofijo'];}  ?>">
        <input type="text" name="critico" id="critico"  hidden="hidden" value="<? if(isset($_POST['critico'])){ echo $_POST['critico'];}  ?>">
        <input type="text" name="arrendado" id="arrendado"  hidden="hidden" value="<? if(isset($_POST['arrendado'])){ echo $_POST['arrendado'];}  ?>">
        <input type="text" name="centro_costo" id="centro_costo"  hidden="hidden" value="<? if(isset($_POST['centro_costo'])){ echo $_POST['centro_costo'];}  ?>">
        <input type="text" name="cod_barra" id="cod_barra"   hidden="hidden" value="<? if(isset($_POST['cod_barra'])){ echo $_POST['cod_barra'];}  ?>">
        <input type="text" name="patente" id="patente"   hidden="hidden" value="<? if(isset($_POST['patente'])){ echo $_POST['patente'];}  ?>">
        <input type="text" name="desde" id="desde"   hidden="hidden" value="<? if(isset($_POST['desde'])){ echo $_POST['desde'];}  ?>">
        <input type="text" name="hasta" id="hasta"   hidden="hidden" value="<? if(isset($_POST['hasta'])){ echo $_POST['hasta'];}  ?>">
        <input type="text" name="estado" id="estado"   hidden="hidden" value="<? if(isset($_POST['estado'])){ echo $_POST['estado'];}  ?>">

       <input type="submit" value="Exportar a Excel">  </form></td></tr>
    <? }else{ ?>
     <tr  id="mensaje-sin-reg"><td colspan="13">No existen Registros con las caracteristicas seleccionadas</td></tr>
     <? } ?>
     
</table>    