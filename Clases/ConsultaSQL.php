<?php 
class ConsultaSQL extends ConexionBD {
	public function __construct() {
		global $BBDD; $BBDD = $BBDD;
		parent::__construct($BBDD["Base"], $BBDD["Tipo"], $BBDD["Host"], $BBDD["User"], $BBDD["Pass"]);
	}
}
?>