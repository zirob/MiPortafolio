<?php

include("includes/Conexion.php");
$con=Conexion();
$codbar_productonew=$_REQUEST['codbar_productonew'];
$empresa=           $_REQUEST['rut'];
$fila=				$_REQUEST['fila'];
$id_preinv=			$_REQUEST["id_pi"];
$usuario=			$_REQUEST["user"];
$cantidad=			$_REQUEST["cant"];
$id_detpreinv=		$_REQUEST["id_detpi"];


 $sql=" select descripcion from productos_new WHERE codbar_productonew=".$codbar_productonew." AND rut_empresa='".$empresa."'";//
 @$rec=mysql_query($sql);
 @$row=mysql_fetch_array($rec);
 @$num=mysql_num_rows($rec);

 if(empty($cantidad)){
 	if($num>0)
 	{
 		
		$sql_ins = "INSERT INTO preinventario_detalle(rut_empresa, id_pi, codigobarras, cantidad, estado_det_pi, usuario_ingreso, fecha_ingreso )";
		$sql_ins.= " VALUES('".$empresa."', '".$id_preinv."', '".$codbar_productonew."', '1', '1', '".$usuario."' , '".date('Y-m-d H:i:s')."')";
		mysql_query($sql_ins);

 		$sql1 = "SELECT MAX(id_det_pi) as id_det_pi FROM preinventario_detalle WHERE codigobarras='".$codbar_productonew."' AND id_pi='".$id_preinv."' AND rut_empresa='".$empresa."'";
 		$rec1=mysql_query($sql1);
 		$row1=mysql_fetch_array($rec1);
 		//$num1=mysql_num_rows($rec1);

		echo "<script>
		";
		echo "document.f1.descripcion_".$fila.".value='".$row[0]."'
		";
		echo "</script>
		";

		echo "<script>\n";
		echo "document.f1.id_det_pi_".$fila.".value='".$row1["id_det_pi"]."'\n";
		echo "</script>\n";		
 		
 		echo "<script>\n";
 		echo "document.getElementById('codbarra_".$fila."').readOnly = true;\n";
 		echo "</script>\n";
	
 	}
 	else
 	{
 		echo "<script>
 		";
 		echo " alert('Codigo ingresado no se encuentra registrado')
 		";
 		echo "document.f1.descripcion_".$fila.".value=''
 		";
 		echo "document.f1.codbarra_".$fila.".value=''
 		";
 		echo "</script>
 		";
 	}
 }else{

 	$up = "UPDATE preinventario_detalle SET cantidad='".$cantidad."' WHERE codigobarras='".$codbar_productonew."' ";
 	$up.= "AND id_pi='".$id_preinv."' AND rut_empresa='".$empresa."' AND id_det_pi=".$id_detpreinv.""; 
 	mysql_query($up);

 	echo "<script>\n";
	echo "document.getElementById('cantidad_".$fila."').readOnly = true;\n";
	echo "</script>\n";

 }

 ?>