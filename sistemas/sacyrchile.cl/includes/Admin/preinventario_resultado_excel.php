<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Informe_Resultado_PreInventario.xls");
header("Pragma: no-cache");
header("Expires: 0");

  $sel = "SELECT pr.*, pn.*, pn.id_familia, f.descripcion_familia, pn.id_subfamilia, s.descripcion_subfamilia ";
  $sel.= "FROM preinventario_resultado pr inner join productos_new pn inner join familia f inner join subfamilia s ";
  $sel.= "WHERE pr.codbar_producto=pn.codbar_productonew AND pr.rut_empresa='".$_POST['empresa']."' AND pr.id_pi='".$_GET['id_pi']."' ";
  $sel.= "AND f.id_familia=pn.id_familia AND  s.id_subfamilia=pn.id_subfamilia ";

    if(!empty($_POST['codigobarras'])){
    $sel.=" and codbar_producto like '%".$_POST['codigobarras']."%' ";
    }
    if(!empty($_POST['familia']) && $_POST['familia']!=""){
        $sel.= " and pn.id_familia='".$_POST['familia']."'";
        $row["id_familia"] = $_POST["familia"]; 
    }
    if(!empty($_POST['subfamilia']) && $_POST['subfamilia']!=""){
        $sel.= " and pn.id_subfamilia='".$_POST['subfamilia']."'";
        $row["id_subfamilia"] = $_POST["subfamilia"]; 
    }


?>

<table style="margin-top:20px; width:1000px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
      <td>#</td>
      <td><label>Codigo de Barra</label></td>
      <td><label>Familia</label></td>
      <td><label>SubFamilia</label></td>
      <td><label>Descripción</label></td>
      <td><label>Cantidad Sistema</label></td>
      <td><label>Cantidad Ingresada</label></td>

   </tr>
   <?
    $i=1;
    $res = mysql_query($sel,$con);
   if(mysql_num_rows($res)!= NULL){
        while($row = mysql_fetch_assoc($res)){
                // $row["codbar_producto"] = (int)$row["codbar_producto"];
    ?>
           <tr class="listado_datos">
                <td><?=$i;$i++;?></td>
                <td><?=$row["codbar_producto"];?></td>
                <td><?=$row["descripcion_familia"];?></td>
                <td><?=$row["descripcion_subfamilia"];?></td>
                <td><?=$row["descripcion"];?></td>
                <td><?=$row["cantidad_sistema"];?></td>
                <td><?=$row["cantidad_ingresada"];?></td>
           </tr>

   <? 
            }    
        }else{ ?>
            
            <tr  id="mensaje-sin-reg">
                <td colspan="15">No existen Registros con las caracteristicas seleccionadas</td>
            </tr>
            
      <?  }  ?>
             
   
           
</table>