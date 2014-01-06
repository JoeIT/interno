<?php
class DBManejador{
	var $conectar;

	function DBManejador(){
	}

	function conectar(){
		if(!($con=@mysql_connect("localhost", "root", ""))){
			echo "Error al conectar a la base de datos";
			exit();
		}
		if (!@mysql_select_db("macaws_bd",$con)){
			echo "Error al seleccionar la base de datos";
			exit();
		}
		
		$this->conectar=$con;
		return true;
	}
}
?>