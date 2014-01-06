<?php
require_once('../clases/conexion.php');

class Area{
	
function listararea(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT		area_id,area
			FROM 		areas
			
			";
			
            $resultado = mysql_query($consulta) or die ('La consulta busqueda responsable fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado))
				{
					$respuesta[$contador]['area_id'] = $row['area_id'];
					$respuesta[$contador]['area'] = $row['area'];
					
					$contador ++;
				}
				return $respuesta;
		  	}
	}


}
function listar_areas($area_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	area_id,area
			FROM	areas
			where area_id !='".$area_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar areas;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['area_id'] = $row['area_id'];
					$respuesta[$cont]['area'] = $row['area'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }

}
?>