<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=productos.xls");
header("Pragma: no-cache");
header("Expires: 0");

      
          


?>

<table style="margin-top:20px; width:1000px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
        <td style="text-align:center; ">#</td>
        <td style="text-align:center; ">Codigo de<br /> Barras</td>
        <td style="text-align:center">Descripcion</td>
        <td style="text-align:center">Codigo Interno</td>
        <td style="text-align:center">Familia</td>
        <td style="text-align:center">SubFamilia</td>
        <td style="text-align:center">Pasillo</td>
        <td style="text-align:center">Casillero</td>
        <td style="text-align:center">Observacion</td>
   </tr>
   <?
  	$a=" \ ";
	$a=trim($a);
    $sql=$_POST['sql'];
	$sql=str_replace($a,"",$sql);
	$i=0;
	
    $res = mysql_query($sql,$con);
    if(mysql_num_rows($res)!= NULL)
	{
            while($row = mysql_fetch_assoc($res))
			{
                
                // $codbar_productonew = (int)$row['codbar_productonew'];
                echo "    <td style='text-align:center'>".$i++."</td>";
                echo "    <td style='text-align:center'>".$row['codbar_productonew']."</td>";
                echo "    <td style='text-align:left'>".$row['descripcion']."</td>";
                echo "    <td style='text-align:center'>".$row['codigo_interno']."</td>";
                echo "    <td style='text-align:center'>".$row['descripcion_familia']."</td>";
                echo "    <td style='text-align:center' >".$row['descripcion_subfamilia']."</td>";
                echo "    <td style='text-align:center'>".$row['pasillo']."</td>";
                echo "    <td style='text-align:center'>".$row['casillero']."</td>";
                echo "    <td style='text-align:left' >".$row['observaciones']."</td>";				    
                echo "</tr>";
            }    
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="15">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }  ?>
             
   
           
</table>