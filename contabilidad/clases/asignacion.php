<?php
require_once('../clases/conexion.php');

class Asignacion{
	
	function insertar_asignacion($act_id,$usuario_id,$resp_id,$fecha,$secundaria_id,$primaria_id,$resp_pri)
	{
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO asignacion (usuario_id, resp_id,fecha,act_id,secundaria_id,primaria_id,resp_pri)
			VALUES ('".$usuario_id."', '".$resp_id."','".$fecha."','".$act_id."','".$secundaria_id."','".$primaria_id."','".$resp_pri."')";
			
		//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_asignacion- fall&oacute;: ' . mysql_error());
		}
	}
	
	function actualizar_asignacion($usuario_id,$resp_id,$fecha,$act_id,$secundaria_id,$primaria_id,$resp_pri)
{ $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			INSERT INTO asignacion (usuario_id, resp_id,fecha,act_id,secundaria_id,primaria_id,resp_pri)
			VALUES ('".$usuario_id."', '".$resp_id."','".$fecha."','".$act_id."','".$secundaria_id."','".$primaria_id."','".$resp_pri."')";
			
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_activo- fall&oacute;: ' . mysql_error());
		}
}
}
?>