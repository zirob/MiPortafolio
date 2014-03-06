<?php
 include("includes/Conexion.php");
 $con=Conexion();
 $id_pi=			 $_REQUEST['id_pi'];
 $empresa=           $_REQUEST['rut'];
 $usuario=			$_REQUEST["user"];


 $sql = "DELETE FROM preinventario_resultado WHERE id_pi='".$id_pi."' AND rut_empresa='".$empresa."'";
@$rec = mysql_query($sql);
@$row = mysql_fetch_array($rec);
@$num = mysql_num_rows($rec);

$sql1 = "SELECT * FROM productos_new WHERE  rut_empresa='".$empresa."'";
$rec1=mysql_query($sql1);
while($row1=mysql_fetch_array($rec1)){	

/*echo "<script>\n";
echo " alert('".$row1['codbar_productonew']."')\n";
echo "</script>\n";*/

	$sql3 = "SELECT sum(cantidad) AS cant_detresul FROM preinventario_detalle ";
	$sql3.= "WHERE codigobarras='".$row1["codbar_productonew"]."' AND rut_empresa='".$empresa."' AND estado_det_pi!='2'";
	@$res3=mysql_query($sql3);
	@$row3=mysql_fetch_array($res3);

	$sql_ins = "INSERT INTO preinventario_resultado( id_pi, rut_empresa, codbar_producto, cantidad_sistema, cantidad_ingresada)";
	$sql_ins.= " VALUES('".$id_pi."', '".$empresa."', '".$row1["codbar_productonew"]."', '".$row1["cantidad"]."', ";
	$sql_ins.= "'".$row3["cant_detresul"]."')";
	@$rec2 = mysql_query($sql_ins);

}

$up = "UPDATE preinventario SET usuario_ing_resultado='".$usuario."' , fecha_ing_resultado='".date('Y-m-d H:i:s')."', estado_pi=3 ";
$up.= "WHERE id_pi='".$id_pi."' AND rut_empresa='".$empresa."' ";
mysql_query($up);
?>

