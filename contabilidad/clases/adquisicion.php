<?php
require_once('../clases/conexion.php');

class Adquisicion{
	
	function listar_adquisicion()
	{
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
				SELECT	ad_id,nombre	
				FROM	adquisicion
				order by ad_id desc
			";
			//echo "<br>SQL: ".$consulta,"<br>";
            $resultado = mysql_query($consulta) or die ('La consulta listar adquisicion fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['ad_id'] = $row['ad_id'];
					$respuesta[$contador]['nombre'] = $row['nombre'];
					
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}
	function listar_adqui($ad_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
				SELECT	ad_id,nombre	
				FROM	adquisicion
				where ad_id != '".$ad_id."'
				order by ad_id desc
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar_adqui- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['ad_id'] = $row['ad_id'];
					$respuesta[$cont]['nombre'] = $row['nombre'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }

    function lisadqui($ad_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
				SELECT	ad_id,nombre	
				FROM	adquisicion
				where ad_id = '".$ad_id."'
				
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar_adqui- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			if($row=mysql_fetch_array($resultado))
      			
      			{
					$respuesta['ad_id'] = $row['ad_id'];
					$respuesta['nombre'] = $row['nombre'];
					return $respuesta;
					
					
				}
				else 
				return false;
		}
	}

	
	
    }
    
    
    
}
?>