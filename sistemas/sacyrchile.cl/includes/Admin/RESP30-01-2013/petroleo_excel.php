<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Petroleo.xls");
header("Pragma: no-cache");
header("Expires: 0");

        $sql = "SELECT * FROM petroleo WHERE rut_empresa='".$_POST['rut_empresa']."'";
            
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


?>

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
       <td><?=$row['dia']."-".$row['mes']."-".$row['agno'];?></td>
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
             
   
           
</table>