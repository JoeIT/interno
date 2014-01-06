<?php
require_once('../clases/conexion.php');

class Moneda{
	
	function listar_moneda(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	m.moneda,m.id,t.tipo_cambio,t.tipo_id,t.fecha_ini	
			FROM	moneda m,tipo_cambio t
			WHERE  m.id=t.id and t.fecha_fin='0000-00-00'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar-moneda- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['moneda'] = $row['moneda'];
					$respuesta[$cont]['id'] = $row['id'];
					$respuesta[$cont]['tipo_cambio'] = $row['tipo_cambio'];
					$respuesta[$cont]['tipo_id'] = $row['tipo_id'];
					$respuesta[$cont]['fecha_ini'] = $row['fecha_ini'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }

    
   function seleccionar_id($tipo_id)
    { $con = new Conexion;
        if($con->conectar()==true){
			$consulta = "
			SELECT	id
			FROM	`tipo_cambio`
			WHERE	tipo_id = '".$tipo_id."'
			";
			$resultado = mysql_query($consulta) or die('La consulta -selccionar_id- fall&oacute;: ' . mysql_error());

			if ($row = mysql_fetch_array($resultado)){
				return $row['id'];
			} else {
				return 0;
			}			
		}
    }
    
      //funcion para q devuelva el tipo de dolar y ufv
   function seleccionar_tipo($fecha)
    { $con = new Conexion;
        if($con->conectar()==true){
			$consulta = "
            SELECT	id,tipo_cambio,fecha_ini 
            FROM	`tipo_cambio`
            WHERE	fecha_ini = '".$fecha."'
			";
			$resultado = mysql_query($consulta) or die('La consulta -selccionar_id- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					
					$respuesta[$cont]['id'] = $row['id'];
					$respuesta[$cont]['tipo_cambio'] = $row['tipo_cambio'];
					$respuesta[$cont]['fecha_ini'] = $row['fecha_ini'];
					$cont++;
					
					
				}
                	return $respuesta;
		  }
        }
    } 
    
}
?>