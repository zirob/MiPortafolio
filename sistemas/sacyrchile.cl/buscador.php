<?php
 include("includes/Conexion.php");
 $con=Conexion();
 $codbar_productonew=$_REQUEST['codbar_productonew'];
 $empresa=           $_REQUEST['rut'];
 
 $sql=" select descripcion from productos_new WHERE codbar_productonew=".$codbar_productonew." AND rut_empresa='".$empresa."'";
 @$rec=mysql_query($sql);
 @$row=mysql_fetch_array($rec);
 @$num=mysql_num_rows($rec);
 
 if($num>0)
 {
	 echo "<script>
	 ";
	 echo "document.f1.descripcion_pi.value='".$row[0]."'
	 ";
	 echo "</script>
	 ";
 }
 else
 {
	  echo "<script>
	 ";
	 echo " alert('Codigo ingresado no se encuentra registrado')
	 ";
	 echo "document.f1.descripcion_pi.value=''
	 ";
	 echo "document.f1.codbar_productonew.value=''
	 ";
	 echo "</script>
	 ";
 }
 

?>