<?

if(isset($_GET['filtro']) && $_GET['filtro']==1){
    
            $sql = "SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION['empresa']."'";
            
        
          
            
         if($_POST['dia']!=""){
            $sql .=" and dia='".$_POST['dia']."'"; 
          }
          if($_POST['mes']!=""){
            $sql .=" and mes='".$_POST['mes']."'";
          }
          if($_POST['anio']!="" && $_POST['anio']!=0){
            $sql .=" and agno='".$_POST['anio']."'";
          }
          if($_POST['nfactura']!=""){
              $sql .=" and num_factura='".$_POST['nfactura']."'";
          }
          if($_POST['fecha_desde']!="" && $_POST['fecha_hasta']==""){
              $d = explode("-",$_POST['fecha_desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
            $sql .=" and fecha_ingreso BETWEEN '".$desde."' and '".date('Y-m-d H:i:s')."'";
          }
          if($_POST['fecha_hasta']!="" && $_POST['fecha_desde']==""){
              $x = explode("-",$_POST['fecha_hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
            $sql .=" and fecha_ingreso BETWEEN '2010-01-01 00:00:00' and '".$hasta."'";
          }
          
          if($_POST['fecha_desde']!="" && $_POST['fecha_hasta']!=""){
              $d = explode("-",$_POST['fecha_desde']);
              $desde = $d[2]."-".$d[1]."-".$d[0]." 00:00:00";
              
              $x = explode("-",$_POST['fecha_hasta']);
              $hasta = $x[2]."-".$x[1]."-".$x[0]." 23:59:59";
              $sql .=" and fecha_ingreso BETWEEN '".$desde."' and '".$hasta."'";
          }
          
          $res = mysql_query($sql,$con);
}else{
    $sql = "SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION['empresa']."'";
}
?>
<form action="?cat=4&sec=3&filtro=1" method="POST">
<table style="width:900px; background-color: #bbb;" id="detalle-cc" border="0" cellpadding="3" cellspacing="2">
    
    <tr>
        <td width="100px"><label style="width:145px;">Dia<br/>(DD)</label></td>
        <td width="145px"><label style="width:145px;">Mes<br/>(MM)</label></td>
        <td width="145px"><label style="width:145px;">Año<br/>(YYYY)</label></td>
        <td width="145px"><label style="width:145px;">N. Factura<br/>&nbsp;</label></td>
        <td width="145px"><label style="width:145px;">Fecha Ing. Desde<br/>(DD-MM-YYYY)</label></td>
        <td width="145px"><label style="width:145px;">Fecha Ing. Hasta<br/>(DD-MM-YYYY)</label></td>
    </tr>
    <tr>
        <td><input type="text" class="fu" name="dia" id="dia" size="2"></td>
        <td><input type="text" class="fu" name="mes" id="mes" size="2"></td>
        <td><input type="text" class="fu" name="anio" id="anio" size="2"></td>
        <td><input type="text" class="fu" name="nfactura" id="nfactura"  size="12"></td>
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
<table style="margin-top:20px; width:1000px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td><label>Fecha</label></td>
       <td><label>Factura</label></td>
       <td><label>Litros</label></td>
       <td><label>Valor Factura</label></td>
       <td><label>Valor IEF</label></td>
       <td><label>Valor IEV</label></td>
       <td><label>Total IE</label></td>
       <td><label>Litros Utilizados</label></td>
       <td><label>IE Utilizados</label></td>
       <td><label>Dest. PP Litros</label></td>
       <td><label>Dest. PP IE</label></td>
       <td><label>Dest. VT Litros</label></td>
       <td><label>Dest. VT IE NO Recup</label></td>
       <td><label>Saldo Litros</label></td>
       <td><label>Saldo IE</label></td>
   </tr>
   <?
    $res = mysql_query($sql,$con);
   if(mysql_num_rows($res)!= NULL){
            while($row = mysql_fetch_assoc($res)){
                
    ?>

   <tr class="listado_datos">
       <td><?="<a href='?cat=4&sec=13&id_petroleo=".$row['dia']."-".$row['mes']."-".$row['agno']."'>".$row['dia']."-".$row['mes']."-".$row['agno']."</a>";?></td>
       <td><?=$row['num_factura'];?></td>
       <td><?=$row['litros'];?></td>
       <td><?=$row['valor_factura'];?></td>
       <td><?=$row['valor_IEF'];?></td>
       <td><?=$row['valor_IEV'];?></td>
       <td><?=number_format($row['total_IEF'],2,".",",");?></td>
       <td><?=$row['utilizado_litros'];?></td>
       <td><?=$row['utilizado_total_IE'];?></td>
       <td><?=$row['destinacion_PP_litros'];?></td>
       <td><?=$row['destinacion_PP_IE_Recuperable'];?></td>
       <td><?=$row['destinacion_VT_litros'];?></td>
       <td><?=$row['destinacion_VT_IE_no_Recuperable'];?></td>
       <td><?=$row['saldo_litros'];?></td>
       <td><?=$row['saldo_impto_especifico'];?></td>
       
   </tr>

   <? 
            }    
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="15">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }  ?>
             
   
           <tr>
               <td colspan="15">
                   
          <form action="includes/Admin/petroleo_excel.php" method="POST">     
            <input type="text" name="dia" id="dia" hidden="hidden" value="<? if(isset($_POST['dia'])){ echo $_POST['dia'];}?>">
            <input type="text" name="mes" id="mes" hidden="hidden" value="<? if(isset($_POST['mes'])){ echo $_POST['mes'];}?>" >
            <input type="text" name="anio" id="anio" hidden="hidden" value="<? if(isset($_POST['anio'])){ echo $_POST['anio'];}?>" >
            <input type="text" name="nfactura" id="nfactura" hidden="hidden" value="<? if(isset($_POST['nfactura'])){ echo $_POST['nfactura'];}?>" >
            <input type="text" name="fecha_desde" id="fecha_desde" hidden="hidden" value="<? if(isset($_POST['fecha_desde'])){ echo $_POST['fecha_desde'];}?>" >
            <input type="text" name="fecha_hasta" id="fecha_hasta" hidden="hidden" value="<? if(isset($_POST['fecha_hasta'])){ echo $_POST['fecha_hasta'];}?>" >
            <input type="text" name="rut_empresa" id="rut_empresa" hidden="hidden" value="<?=$_SESSION['empresa']?>" >
               
           <input type="submit" value="Exportar a Excel">  </form> </td></tr>
</table>