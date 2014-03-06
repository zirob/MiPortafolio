<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Salida_Petroleo.xls");
header("Pragma: no-cache");
header("Expires: 0");

      
        

?>

<table style="margin-top:20px; width:1000px;" id="list_registros" border="1" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
       <td><label>#</label></td>
       <td><label>Id</label></td>
       <td><label>Dia</label></td>
       <td><label>Mes</label></td>
       <td><label>Anho</label></td>
       <td><label>Codigo Producto</label></td>
       <td><label>Detalle Producto</label></td>
       <td><label>Litros </label></td>
       <td><label>Centro Costo</label></td>
       <td><label>Persona Autoriza</label></td>
       <td><label>Persona Retira</label></td>
       <td><label>Tipo de Salida</label></td>
       <td><label>Kilometros</label></td>
       <td><label>Horometros</label></td>
       <td><label>Lugar Fisico</label></td>
       <td><label>Observacion</label></td>
       <td><label>Patente</label></td>
   </tr>
   <?
  	$a=" \ ";
	$a=trim($a);
    $sql=$_POST['sql'];
	$sql=str_replace($a,"",$sql);
	$i=0;
    $res = mysql_query($sql,$con);
    if(mysql_num_rows($res)!= NULL){
            while($row = mysql_fetch_assoc($res))
			{
                
		if($row['tipo_salida']==1) $row['tipo_salida']="Activo";
    	if($row['tipo_salida']==2) $row['tipo_salida']="Lugares Fisicos";
          echo "<tr style='font-family:tahoma;font-size:12px;'>";
          echo "<td style='text-align:center'>".$i++."</td>";
          echo "<td style='text-align:center'>".$row['id_salida_petroleo']."</td>";
          echo "<td style='text-align:center'>".$row['dia']."</td>";
          echo "<td style='text-align:center'>".$row['mes']."</td>";
          echo "<td style='text-align:center'>".$row['agno']."</td>";
          echo "<td style='text-align:center'>".$row['cod_producto']."</td>";
          echo "<td style='text-align:center'>".$row['descripcion']."</td>";
          echo "<td style='text-align:center'>".$row['litros']."</td>";
          echo "<td style='text-align:center'>".$row['descripcion_cc']."</td>";
          echo "<td style='text-align:center'>".$row['persona_autoriza']."</td>"; 
          echo "<td style='text-align:center'>".$row['persona_retira']."</td>"; 
          echo "<td style='text-align:center'>".$row['tipo_salida']."</td>";
          echo "<td style='text-align:center'>".$row['kilometro']."</td>" ;
          echo "<td style='text-align:center'>".$row['horometro']."</td>";
          echo "<td style='text-align:center'>".$row['descripcion_lf']."</td>";
          echo "<td style='text-align:center'>".$row['observacion']."</td>";
          echo "<td style='text-align:center'>".$row['patente']."</td>";
          echo "<td style='text-align: center;'>
    				    
    				</td>";
        echo "</tr>";
            }    
        }else{ ?>
            
             <tr  id="mensaje-sin-reg"><td colspan="17">No existen Registros con las caracteristicas seleccionadas</td></tr>
            
      <?  }  ?>
             
   
           
</table>