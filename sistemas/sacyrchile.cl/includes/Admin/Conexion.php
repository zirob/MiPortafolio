<?php
	function Conexion(){
		$con = mysql_connect('localhost','root','directv');
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}else{
			$db = mysql_select_db('sacyr',$con);
			if (!$db) {
	    		die ('No se puede utilizar la base de datos:' . mysql_error());
			}
		}
		return $con;
	}

?>