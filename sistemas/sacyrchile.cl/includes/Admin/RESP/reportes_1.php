<?php


if(isset($_GET['filtro']) && $_GET['filtro']==1){
    
            $sql = "SELECT * FROM cabecera_oc WHERE estado_oc=".$_POST['estado'];
            
        
          
            
          if($_POST['id_oc']!=""){
            $sql .=" and id_oc='".$_POST['id_oc']."'"; 
          }
          if($_POST['id_solic']!=""){
            $sql .=" and id_solicitud_compra='".$_POST['id_solic']."'";
          }
          if($_POST['centro_costo']!="" && $_POST['centro_costo']!=0){
            $sql .=" and centro_costos='".$_POST['centro_costo']."'";
          }
          if($_POST['rut_proveedor']!=""){
              $rut = str_replace(".","",$_POST['rut_proveedor']);
            $sql .=" and rut_proveedor='".$rut."'";
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
<form action="?cat=4&sec=1&filtro=1" method="POST">
<table style="width:900px; background-color: #bbb;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
    <tr>
        <td colspan="6"><label>Estado O.C<label><select name="estado" id="estado">
                           <option value="1">Abierta</option>
                            <option value="2">Pendiente</option>
                            <option value="3">Aprobada</option>
                            <option value="4">Cerrada</option>
                        </select></td>
    </tr>
    <tr>
        <td width="100px"><label style="width:145px;">Id O.C.<br/>&nbsp;</label></td>
        <td width="145px"><label style="width:145px;" >Id Sol. Compra<br/>&nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Centro Costos<br/>&nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Rut Proveedor<br/>&nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Fecha OC Desde<br/>(DD-MM-YYYY)</label></td>
        <td width="145px"><label style="width:145px;">Fecha OC Hasta<br/>(DD-MM-YYYY)</label></td>
    </tr>
    <tr>
        <td><input type="text" class="fu" name="id_oc" id="id_oc" size="5"> <br /> </td>
        <td><input type="text" class="fu" name="id_solic" id="id_solic" size="20"> <br /> </td>
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
        <td><input type="text" class="fu" name="rut_proveedor" id="rut"  size="20"><br /></td>
        <td><input type="text" class="fu" name="fecha_desde" id="fecha_desde"  size="20"><br /></td>
        <td><input type="text" class="fu" name="fecha_hasta" id="fecha_hasta"  size="20"><br /></td>
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
       <td style="width: 50px;"><label>Id O.C.</label></td>
       <td style="width: 100px;"><label>Id Sol. Compra</label></td>
       <td style="width: 100px;"><label>Fecha O.C</label></td>
       <td style="width: 100px;"><label>Estado O.C</label></td>
       <td style="width: 100px;"><label>Centro Costos</label></td>
       <td style="width: 100px;"><label>Proveedor</label></td>
       <td style="width: 150px;"><label>Descripcion Compra</label></td>
       <td style="width: 100px;"><label>Total O.C</label></td>
       <td style="width: 100px;"><label>Fecha Entrega</label></td>
   </tr>
   <? if(isset($_GET['filtro']) && $_GET['filtro']==1){
        if(mysql_num_rows($res)!= NULL){
            while($row = mysql_fetch_assoc($res)){
                
                $aux1 = explode(" ",$row['fecha_oc']);
                $d = explode("-",$aux1[0]);
                
                $aux2 = explode(" ",$row['fecha_entrega']);
                $d2 = explode("-",$aux2[0]);
                
                switch($row['estado_oc']){
                    case 1:
                        $estado="Abierta";
                        break;
                    case 2:
                        $estado="Pendiente";
                        break;
                    case 3:
                        $estado="Aprobada";
                        break;
                    case 4:
                        $estado="Cerrada";
                        break;
                }
                
                $cc = "SELECT * FROM centros_costos WHERE Id_cc=".$row['centro_costos'];
                $sc =mysql_query($cc,$con);
                $rc = mysql_fetch_assoc($sc);
                ?>

   <tr class="listado_datos">
       <td ><?=$row['id_oc'];?></td>
       <td ><?=$row['id_solicitud_compra'];?></td>
       <td ><?=$d[2]."-".$d[1]."-".$d[0];?></td>
       <td ><?=$estado;?></td>
       <td ><?=$rc['descripcion_cc'];?></td>
       <td ><?=$row['rut_proveedor'];?></td>
       <td ><?=$row['observaciones'];?></td>
       <td ><?=$row['total'];?></td>
       <td ><?=$d2[2]."-".$d2[1]."-".$d2[0];?></td>
   </tr>

   <?
            }    
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="9">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }  ?>
             
   
           <tr>
               <td colspan="9">
                   
           <form action="includes/Admin/OC_excel.php" method="POST">     
                <input type="text" name="rut_empresa" id="rut_empresa"  hidden="hidden" value="<? echo $_SESSION['empresa']; ?>">
           <input type="text" name="estado" id="estado" hidden="hidden" value="<? if(isset($_POST['estado'])){ echo $_POST['estado'];}  ?>">
           <input type="text" name="id_oc" id="id_oc" hidden="hidden" value="<? if(isset($_POST['id_oc'])){ echo $_POST['id_oc'];}  ?>">
           <input type="text" name="id_solic" id="id_solic" hidden="hidden" value="<? if(isset($_POST['id_solic'])){ echo $_POST['id_solic'];}  ?>">
           <input type="text" name="centro_costo" id="centro_costo" hidden="hidden" value="<? if(isset($_POST['centro_costo'])){ echo $_POST['centro_costo'];}  ?>">
           <input type="text" name="rut_proveedor" id="rut_proveedor" hidden="hidden" value=" <? if(isset($_POST['rut_proveedor'])){ echo $_POST['rut_proveedor'];}  ?>">
           <input type="text" name="fecha_desde" id="fecha_desde" hidden="hidden" value="<? if(isset($_POST['fecha_desde'])){ echo $_POST['fecha_desde'];}  ?>">
           <input type="text" name="fecha_hasta" id="fecha_hasta" hidden="hidden" value="<? if(isset($_POST['fecha_hasta'])){ echo $_POST['fecha_hasta'];}  ?>">
               
           <input type="submit" value="Exportar a Excel">  </form> </td></tr><? /*|
           
           <form action="?cat=4&sec=6&filtro=1" method="POST">     
           <input type="text" name="estado" id="estado" hidden="hidden" value="<? if(isset($_POST['estado'])){ echo $_POST['estado'];}  ?>">
           <input type="text" name="id_oc" id="id_oc" hidden="hidden" value="<? if(isset($_POST['id_oc'])){ echo $_POST['id_oc'];}  ?>">
           <input type="text" name="id_solic" id="id_solic" hidden="hidden" value="<? if(isset($_POST['id_solic'])){ echo $_POST['id_solic'];}  ?>">
           <input type="text" name="centro_costo" id="centro_costo" hidden="hidden" value="<? if(isset($_POST['centro_costo'])){ echo $_POST['centro_costo'];}  ?>">
           <input type="text" name="rut_proveedor" id="rut_proveedor" hidden="hidden" value=" <? if(isset($_POST['rut_proveedor'])){ echo $_POST['rut_proveedor'];}  ?>">
           <input type="text" name="fecha_desde" id="fecha_desde" hidden="hidden" value="<? if(isset($_POST['fecha_desde'])){ echo $_POST['fecha_desde'];}  ?>">
           <input type="text" name="fecha_hasta" id="fecha_hasta" hidden="hidden" value="<? if(isset($_POST['fecha_hasta'])){ echo $_POST['fecha_hasta'];}  ?>">
           
           <input type="submit" value="Exportar a PDF"></form></td></tr>
      
       <?     */   }else{ ?>
   <tr id="mensaje-sin-reg">
       <td colspan="9">Seleccione Filtro para desplegar resultados</td>
   </tr>
   <? } ?>

</table>


  
  
  
  
 
  
  