<?php
require_once('includes/dbmanejador.php');
require('../controladores/includes/fecha.php');

class Logeo {
	function Logeo(){
	}

	function comprobar($login, $pass)
	{       
                if (!isset($_SESSION)){
		session_start();
		
                }
		require('../controladores/includes/fecha.php');
		
		$con = new DBmanejador;
		if($con->conectar()==true){
			
			$consulta = "select usuario_id, nombres, apellidos, nick, grupo_id from tusuarios where nick='".$login."' and password='".$pass."'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;:' . mysql_error());
			$row = mysql_fetch_array($resultado);
			//echo $consulta;
			if ($row) {
				
				$id=$row["usuario_id"];
				$_SESSION["usuario_id"] = $row["usuario_id"];
				$_SESSION["nombres"] = $row["nombres"];
				$_SESSION["apellidos"] = $row["apellidos"];
				$_SESSION["grupo_id"] = $row["grupo_id"];
				$_SESSION["login"]=$login;
				
				$_SESSION["fecha_ingreso"]=$fecha;
				$_SESSION["logeo"]=true;
			
				return true;
			} else {
				return false;
			}
		}
	}
	
	function salir_session(){
		session_start();

		if(isset($_SESSION)){
			$_SESSION = array();
			session_unset();
			session_destroy();
			unset($_SESSION);
	
			return true;
		} else {
			return false;
		}
	}
}
?>