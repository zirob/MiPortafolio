<?
include '../Conexion.php';
$con = Conexion();


header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=OT.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<table style="margin-top:20px;" id="list_registros" border="1" cellpadding="3" cellspacing="2">
    <tr id="titulo_reg">
     <!--   <td style="width: 50px;"><label>Id O.T.</label></td>
       <td style="width: 100px;"><label>Fecha O.T</label></td>
       <td style="width: 100px;"><label>Estado O.T</label></td>
       <td style="width: 100px;"><label>Centro Costos</label></td>
       <td style="width: 100px;"><label>Producto</label></td>
       <td style="width: 100px;"><label>Patente</label></td>
       <td style="width: 150px;"><label>Descripcion OT</label></td>
       <td style="width: 100px;"><label>Horas Hombre</label></td> -->

       <td><label>#</label></td>
      <td><label>Tipo Trabajo</label></td>
      <td><label>Asignado a</label></td>
      <td><label>Concepto</label></td>
      <td><label>Descripción</label></td>
      <td><label>Centro Costo</label></td>
      <td><label>Estado</label></td>
      <td><label>Fecha Ing.</label></td>
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

?>
              <tr>
                    <td><?=$row["id_ot"];?></td>
                    <td><? 
                        if($row['tipo_trabajo']==1)
                            echo "Urgente";
                        if($row['tipo_trabajo']==2)
                            echo "Normal";
                        ?>
                    </td>
                    <td><? 
                        if($row['asigna_ot']==1)
                            echo "Activo";
                        if($row['asigna_ot']==2)
                            echo "Lugares Fisicos";
                        ?>
                    </td>
                    <!-- <td><?/*=$row["descripcion_prd"];*/?></td> -->
                    <!-- <td><?/*=$row['descripcion_lf'];*/?></td> -->
                    <td>
                        <? 
                        if($row['concepto_trabajo']==1)
                            echo "MANTENCION";
                        if($row['concepto_trabajo']==2)
                            echo "REPARACION";
                        ?>
                    </td>
                    <td style="text-align:left;"><?=$row['descripcion_ot'];?></td>
                    <td><?=$row['descripcion_cc'];?></td>
                    <td>
                        <? 
                        if($row['estado_ot']==1)
                            echo "Pendiente";
                        if($row['estado_ot']==2)
                            echo "En Proceso";
                        if($row['estado_ot']==3)
                            echo "Finalizado";
                        if($row['estado_ot']==4)
                            echo "En Espera de Materiales";
                        if($row['estado_ot']==5)
                            echo "Trabajo Suspendido";
                      ?>
                    </td>

                    <td><?=date("d-m-Y",strtotime($row['fecha_ingreso_ot'])); ?></td>
             
              </tr>  
<?    
        }

}else{ 
?>
    
     <tr  id="mensaje-sin-reg"><td colspan="9">No existen Registros con las caracteristicas seleccionadas</td></tr>
    
<? 
}
?>
   
            
</table>