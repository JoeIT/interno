<?php
require_once('../clases/conexion.php');

class LocPrimaria{
	
	function listar_locprimaria(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	primaria_id,localizacion,descripcion
			FROM	tprimaria
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar locprimaria- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['primaria_id'] = $row['primaria_id'];
					$respuesta[$cont]['localizacion'] = $row['localizacion'];
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }
    
    
    function listar_primarias($primaria_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	primaria_id,localizacion,descripcion
			FROM	tprimaria
			where primaria_id !='".$primaria_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar primarias- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['primaria_id'] = $row['primaria_id'];
					$respuesta[$cont]['localizacion'] = $row['localizacion'];
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }

    
    function localizacionpri($primaria_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	primaria_id,localizacion,descripcion
			FROM	tprimaria
			where primaria_id='".$primaria_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -localizacionpri- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			if($row=mysql_fetch_array($resultado))
      			
      			{
					$respuesta['primaria_id'] = $row['primaria_id'];
					$respuesta['localizacion'] = $row['localizacion'];
					$respuesta['descripcion'] = $row['descripcion'];
					
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