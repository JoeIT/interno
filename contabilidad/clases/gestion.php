<?php
require_once('../clases/conexion.php');

class Gestion{
	
	function listar_gestion(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT		gestion,fecha_inicio,fecha_fin,gestion_id
			FROM 		gestion
			
			";
			
            $resultado = mysql_query($consulta) or die ('La consulta busqueda responsable fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado))
				{
					$respuesta[$contador]['gestion'] = $row['gestion'];
					$respuesta[$contador]['fecha_inicio'] = $row['fecha_inicio'];
					$respuesta[$contador]['fecha_fin'] = $row['fecha_fin'];
					$respuesta[$contador]['gestion_id'] = $row['gestion_id'];
					
					$contador ++;
				}
				return $respuesta;
		  	}
	}


}
function insertar_gestion($gestion,$fecha_inicio,$fecha_fin)
{
	
		
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO `gestion` (`gestion`, `fecha_inicio`,`fecha_fin`)
			VALUES ('".$gestion."', '".$fecha_inicio."','".$fecha_fin."')";
			
		//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_responsable- fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
			 else return true;
		
		}
		
		
		
		
	
	
}
function fecha_gestion($gest){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT		gestion,fecha_inicio,fecha_fin
			FROM 		gestion
			WHERE  gestion_id='".$gest."'
			
			";
			
            $resultado = mysql_query($consulta) or die ('La consulta busqueda responsable fall&oacute;: ' . mysql_error());
			
				
			if (!$resultado)
				return false;
			else {
			if($row=mysql_fetch_array($resultado))
      			
      			{
					$respuesta['gestion'] = $row['gestion'];
					$respuesta['fecha_inicio'] = $row['fecha_inicio'];
					$respuesta['fecha_fin'] = $row['fecha_fin'];
					$respuesta['gestion_id'] = $row['gestion_id'];
					
					return $respuesta;
					
				}
				else {
					return false;
				}				
		}
	}


}


}
?>