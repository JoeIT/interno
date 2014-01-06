<?php
require_once('../clases/conexion.php');

class Responsable{
	
	function busqueda_responsable($nombre){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT		r.resp_id, CONCAT(r.apellido,' ', r.nombre) AS completo
			FROM 		responsable r
			WHERE		CONCAT(r.apellido,' ', r.nombre) LIKE '%".$nombre."%'
						AND r.estado != 0  
			ORDER BY	CONCAT(r.apellido,' ', r.nombre)
			LIMIT 0,20
			";
			//echo "<br>SQL: ".$consulta,"<br>";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda responsable fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['resp_id'] = $row['resp_id'];
					$respuesta[$contador]['completo'] = $row['completo'];
					
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}
function insertar_responsable($nombre,$apellido,$cargo,$telefono,$celular,$area,$ci)
{
	
		
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO `responsable` (`nombre`, `apellido`,`cargo`,`estado`,`telefono`,`celular`,`area_id`,`ci`)
			VALUES ('".$nombre."', '".$apellido."','".$cargo."','1','".$telefono."','".$celular."','".$area."','".$ci."')";
			
		//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_responsable- fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
			 else return true;
		
		}
		
		
		
		
	
	
}
	
function mostrar_responsable($resp_id)
{
	$con = new Conexion;
	if($con->conectar() == true){
	   $consulta = "
			SELECT CONCAT(r.apellido,' ', r.nombre) AS completo,r.resp_id,r.cargo,r.telefono
			,r.celular,r.ci,r.resp_id,r.nombre,r.apellido
			FROM responsable r
 			WHERE  r.resp_id='".$resp_id."'
			AND r.estado != 0  
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -buscar responsable: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				
      			if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['completo'] = $row['completo'];					
					$respuesta['resp_id'] = $row['resp_id'];
					$respuesta['cargo']=$row['cargo'];
					$respuesta['telefono']=$row['telefono'];
					$respuesta['celular']=$row['celular'];
					$respuesta['ci']=$row['ci'];
					$respuesta['resp_id']=$row['resp_id'];
					$respuesta['nombre'] = $row['nombre'];
					$respuesta['apellido'] = $row['apellido'];
					
					
					return $respuesta;
					
				}
				else {
					return false;
				}
			}
	}	
}
function area($resp_id)
{
	$con = new Conexion;
	if($con->conectar() == true){
		
		$consulta = "
			SELECT a.area,a.area_id
			FROM responsable r,areas a
 			WHERE  r.resp_id='".$resp_id."' 
			AND r.area_id=a.area_id
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -buscar area del responsable: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				
      			if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['area'] = $row['area'];		
					$respuesta['area_id']=$row['area_id'];			
					
					return $respuesta;
					
				}
				else {
					return false;
				}
			}
		
	}
}
function actualizar_resp($nombre,$apellido,$cargo,$telefono,$celular,$area_id,$ci,$resp_id)
{
	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			UPDATE	responsable
			SET		nombre = '".$nombre."'
					, apellido = '".$apellido."'
					, cargo = '".$cargo."'
					, telefono='".$telefono."'
					,celular='".$celular."'
					,area_id='".$area_id."'
					,ci='".$ci."'
			WHERE	resp_id = ".$resp_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_reponsable- fall&oacute;: ' . mysql_error());
		}
	
	
}

function eliminar_resp($resp_id)
{
	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			UPDATE	responsable
			SET		estado = '0'
					
			WHERE	resp_id = ".$resp_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -eliminar_reponsable- fall&oacute;: ' . mysql_error());
		}
	
	
}
}
?>