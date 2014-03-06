<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=activos.xls");
header("Pragma: no-cache");
header("Expires: 0");

      
          


?>

<table style="margin-top:20px; width:1000px;" id="list_registros" border="0" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
  <td style="text-align:center; ">#</td>
        <td style="text-align:center; ">Codigo </td>
        <td style="text-align:center">Descripcion</td>
        <td style="text-align:center">Cod. Interno</td>
        <td style="text-align:center">Patente</td>
        <td style="text-align:center">Estado</td>
        <td style="text-align:center">Familia</td>
        <td style="text-align:center">Subfamilia</td>
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
                
    echo "    <td style='text-align:center'>".$i++."</td>";
    echo "    <td style='text-align:center'>".$row['cod_producto']."</td>";
    echo "    <td style='text-align:center'>".$row['descripcion']."</td>";
    echo "    <td style='text-align:center'>".$row['codigo_interno']."</td>";
    echo "    <td style='text-align:center'>".$row['patente']."</td>";
    echo "    <td style='text-align:center'>";
                  switch ($row['estado_producto']) {
                        case '1':
                            echo "Disponible";
                            break;
                        case '2':
                            echo "Asignado";
                            break;
                        case '3':
                            echo "Dado de Baja";
                            break;    
                        case '4':
                            echo "En Mantención";
                            break;    
                        case '5':
                            echo "En Reparación";
                            break;    
                        default:
                            echo "";
                            break;
                    } 
        
    echo "</td>";
    echo "    <td style='text-align:center'>".$row['descripcion_familia']."</td>";
	echo "    <td style='text-align:center'>".$row['descripcion_subfamilia']."</td>";				    
        echo "</tr>";
            }    
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="15">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }  ?>
             
   
           
</table>