<?php
require_once('../clases/conexion.php');

class TipoCambio{
	
	   

    function actualizar_tipocambio($tipo_cambio,$fecha_ini,$fecha_fin,$id)
    { $con = new Conexion;
		if($con->conectar() == true){
			$consulta= "INSERT  into  tipo_cambio (tipo_cambio,fecha_ini,fecha_fin,id) 
				                    values('".$tipo_cambio."','".$fecha_ini."','".$fecha_fin."','".$id."')";
				$resultado=mysql_query($consulta) or die('La consulta  actualizar tipocambio fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;
			
		}
    }
    
     function cambio_fecha($tipo_id,$fecha_ini)
    { $con = new Conexion;
		if($con->conectar() == true){
			$consulta= "update tipo_cambio set fecha_fin='".$fecha_ini."' 
				             where tipo_id=".$tipo_id;
			//echo "<br>SQL: ".$consulta,"<br>";	
			$resultado=mysql_query($consulta) or die('La consulta cambio fecha fall&oacute;: ' . mysql_error());

			 if (!$resultado) return false;
			 else return true;
			
		}
    }
    
    function buscar_fecha_tipocambio($fecha,$tipo){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tipo_cambio
			FROM	tipo_cambio 
			where fecha_ini='".$fecha."' and id='".$tipo."'
			";
		//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -locgru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
		if($row=mysql_fetch_array($resultado))
      			
      			{
					$respuesta = $row['tipo_cambio'];

					
					return $respuesta;
					
				}
				else {
					return false;
				}	
				
		}
	}

	
	
    }
   
       function buscar_fecha($fecha,$tipo){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tipo_cambio
			FROM	tipo_cambio 
			where fecha_ini='".$fecha."' and tipo_cambio='".$tipo."'
			";
		//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -locgru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
		if($row=mysql_fetch_array($resultado))
      			
      			{
					$respuesta = $row['tipo_cambio'];
					
					
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