<?
if(isset($_GET['filtro']) && $_GET['filtro']==1){
            $sql ="SELECT c.id_ot, c.fecha_ingreso, c.estado_ot, cc.descripcion_cc, pp.tipo_producto, p.patente, c.descripcion_ot, SUM( d.horas_hombre ) AS horas_hombre
                    FROM detalles_productos p, cabeceras_ot c, detalle_ot d,centros_costos cc, bodegas b, productos pp WHERE p.rut_empresa='".$_SESSION['empresa']."' ";
        if($_POST['bodega']==0){
            
            $sql.="";
        }else{
            $sql.=" and b.cod_bodega=".$_POST['bodega'];
        }
          
        
         if($_POST['estado']!=""){
            $sql .=" and c.estado_ot='".$_POST['estado']."'"; 
          }
            
          if($_POST['id_ot']!=""){
            $sql .=" and c.id_ot='".$_POST['id_ot']."'"; 
          }
          if($_POST['centro_costo']!=""){
            $sql .=" and c.centro_costos='".$_POST['centro_costo']."'";
          }
          if($_POST['patente']!=""){
            $sql .=" and p.patente='".$_POST['patente']."'";
          }
          if($_POST['cod_producto']!=""){
            $sql .=" and pp.cod_producto='".$_POST['cod_producto']."'";
          }
          if($_POST['fecha_desde']!="" && $_POST['fecha_hasta']==""){
              $d = explode("-",$_POST['fecha_desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
            $sql .=" and fecha_oc BETWEEN '".$desde."' and '".date('Y-m-d H:i:s')."'";
          }
          if($_POST['fecha_hasta']!="" && $_POST['fecha_desde']==""){
              $x = explode("-",$_POST['fecha_hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
            $sql .=" and fecha_oc BETWEEN '2010-01-01 00:00:00' and '".$hasta."'";
          }
          
          if($_POST['fecha_desde']!="" && $_POST['fecha_hasta']!=""){
              $d = explode("-",$_POST['fecha_desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
              
              $x = explode("-",$_POST['fecha_hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
              $sql .=" and fecha_oc BETWEEN '".$desde."' and '".$hasta."'";
              
              
          }
          
          $res = mysql_query($sql,$con);
}


?>
<form action="?cat=4&sec=2&filtro=1" method="POST">
<table style="width:900px; background-color: #bbb;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
         
  
     <tr>
        <td colspan="6"><label>Bodega<label><select name="bodega" id="bodega">
                            <option value="0">Todas las Bodegas</option>
                            <?
                                $s ="SELECT * FROM bodegas WHERE rut_empresa='".$_SESSION['empresa']."'";
                                $r = mysql_query($s,$con);
                                while($row = mysql_fetch_assoc($r)){
                            ?>
                            <option value="<?=$row['cod_bodega'];?>"><?=$row['descripcion_bodega'];?></option>
                            <?  }?>
                        </select></td>
    </tr>
    <tr>
        <td colspan="6"><label>Estado O.T.<label><select name="estado" id="estado">
                            <option value="1"> Abierta </option>
                            <option value="2"> En Proceso </option>
                            <option value="3"> Finalizada </option>
                            <option value="4"> Cerrada </option>
                        </select></td>
    </tr>
    <tr>
        <td width="145px"><label style="width:145px;">Id O.T.<br/> &nbsp;</label></td>
        <td width="145px"><label style="width:145px;" >Centro Costos<br/> &nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Cod Producto<br/> &nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Patente<br/> &nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Fecha OT Desde<br/>(DD-MM-YYYY)</label></td>
        <td width="145px"><label style="width:145px;">Fecha OT Hasta<br/>(DD-MM-YYYY)</label></td>
    </tr>
    <tr>
        <td><input type="text" class="fu" name="id_ot" id="id_ot" size="10"></td>
        <td>
            <SELECT name="centro_costo">
                <option value=""> --- </option>
                <? $s = "SELECT * FROM centros_costos WHERE rut_empresa='".$_SESSION['empresa']."'";
                    $r = mysql_query($s,$con);
                    while($row = mysql_fetch_assoc($r)){ ?>
                <option value="<?=$row['Id_cc'];?>"><?=$row['descripcion_cc'];?></option>
                 <?   }
                ?>
            </SELECT>
        </td>
        <td><input type="text" class="fu" name="cod_producto" id="cod_producto"  size="20"></td>
        <td><input type="text" class="fu" name="patente" id="patente"  size="20"></td>
        <td><input type="text" class="fu" name="fecha_desde" id="fecha_desde"  size="20"></td>
        <td><input type="text" class="fu" name="fecha_hasta" id="fecha_hasta"  size="20"></td>
    </tr>
    <tr>
        <td colspan="6">
            <input type="submit" name="buscar" value="Buscar">
        </td>
    </tr>
</table>
</form>    


<table style="margin-top:20px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td style="width: 50px;"><label>Id O.T.</label></td>
       <td style="width: 100px;"><label>Fecha O.T</label></td>
       <td style="width: 100px;"><label>Estado O.C</label></td>
       <td style="width: 100px;"><label>Centro Costos</label></td>
       <td style="width: 100px;"><label>Producto</label></td>
       <td style="width: 100px;"><label>Patente</label></td>
       <td style="width: 150px;"><label>Descripcion OT</label></td>
       <td style="width: 100px;"><label>Horas Hombre</label></td>
    </tr>
   <? if(isset($_GET['filtro']) && $_GET['filtro']==1){
        if(mysql_num_rows($res)!= NULL && mysql_num_rows($res)!= 1 ){
            while($row = mysql_fetch_assoc($res)){
                
                 $aux1 = explode(" ",$row['fecha_ingreso']);
                $d = explode("-",$aux1[0]);
                
                
                switch($row['tipo_producto']){
                    case 1:
                        $tipo="Maquinarias y Equipos"; break;
                    case 2:
                        $tipo="Vehiculo Menor"; break;
                    case 3:
                        $tipo="Herramientas"; break;
                    case 4:
                        $tipo="Muebles"; break;
                    case 5:
                        $tipo="Generador"; break;
                    case 6:
                        $tipo="Plantas"; break;
                    case 7:
                        $tipo="Equipos de Tunel"; break;
                    case 8:
                        $tipo="Otros"; break;
                }
                
                
                ?>

   <tr class="listado_datos">
       <td ><?=$row['id_ot'];?></td>
       <td ><?=$d[2]."-".$d[1]."-".$d[0];?></td>
       <td ><?=$row['estado_ot'];?></td>
       <td ><?=$row['descripcion_cc'];?></td>
       <td ><?=$tipo;?></td>
       <td ><?=$row['patente'];?></td>
       <td ><?=$row['descripcion_ot'];?></td>
       <td ><?=$row['horas_hombre'];?></td>
       
   </tr>

   <?
           }    
   
             
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="9">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }    ?>
   
             <tr><td>    
        <form action="includes/Admin/OT_excel.php" method="POST">     
            <input type="text" name="rut_empresa" id="rut_empresa"  hidden="hidden" value="<? echo $_SESSION['empresa']; ?>">
            <input type="text" name="bodega" id="bodega"  hidden="hidden" value="<? if(isset($_POST['bodega'])){ echo $_POST['bodega'];}  ?>">
            <input type="text" name="estado" id="estado"  hidden="hidden" value="<? if(isset($_POST['estado'])){ echo $_POST['estado'];}  ?>">
            
            <input type="text" name="id_ot" id="id_ot"  hidden="hidden" value="<? if(isset($_POST['id_ot'])){ echo $_POST['id_ot'];}  ?>">
            <input type="text" name="centro_costo" id="centro_costo"  hidden="hidden" value="<? if(isset($_POST['centro_costo'])){ echo $_POST['centro_costo'];}  ?>">
            <input type="text" name="cod_producto" id="cod_producto"   hidden="hidden" value="<? if(isset($_POST['cod_producto'])){ echo $_POST['cod_producto'];}  ?>">
            <input type="text" name="patente" id="patente"   hidden="hidden" value="<? if(isset($_POST['patente'])){ echo $_POST['patente'];}  ?>">
            <input type="text" name="fecha_desde" id="fecha_desde"   hidden="hidden" value="<? if(isset($_POST['fecha_desde'])){ echo $_POST['fecha_desde'];}  ?>">
            <input type="text" name="fecha_hasta" id="fecha_hasta"   hidden="hidden" value="<? if(isset($_POST['fecha_hasta'])){ echo $_POST['fecha_hasta'];}  ?>">
               
           <input type="submit" value="Exportar a Excel">  </form></td></tr>
             <? }else{ ?>
   <tr id="mensaje-sin-reg">
       <td colspan="9">Seleccione Filtro para desplegar resultados</td>
   </tr>
   <? } ?>

</table>

