<?php

require_once('../clases/includes/dbmanejador.php');

  class Aviso
  {
		function obtener_lista_avisos($usuario)		
		{	
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			 	$sql="	SELECT a.fecha, a.asunto , concat( u.nombres,' ',u.apellidos),a.aviso_id as codigo
						FROM tavisos a,tusuarios u, tgrupousuario g 
						WHERE  	u.usuario_id=a.emisor AND g.grupousuario_id=u.grupo_id AND 
								(a.receptor=(SELECT u1.grupo_id from tusuarios u1 where u1.usuario_id=".$usuario.")
								OR a.receptor=0)
						order by codigo desc limit 0,7" ;	  				
				$resultado=mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
				if (!$resultado) return false;
				else
				{      $contador=0;
	
						while($row = mysql_fetch_array($resultado))
						{
							$respuesta[$contador]["fecha"]= $row['0'];
							$respuesta[$contador]["emisor"]= $row['2'];
							$respuesta[$contador]["descripcion"]= $row['1'];
							$respuesta[$contador]["codigo"]= $row['3'];
							$contador=$contador+1;
						}
						return $respuesta;
				}
			}
		}
	  
	  function eliminar_aviso($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tavisos where aviso_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  
	  // FUNCION PARA INSERTAR UN NUEVO MODELO
	 function nuevo_aviso($asunto,$emisor,$receptor)//ojo la fecha
      {
            $con = new DBmanejador;
			$fecha=date("Y-m-d");
			
			if($con->conectar()==true)
         	{ 
		    	$consulta= "INSERT  into tavisos (asunto,fecha,emisor,receptor) values('".$asunto."','".$fecha."','".$emisor."','".$receptor."')";
			
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			 if (!$resultado) return "false";
			 else return "true";

        	}
      }
	  
	//cumpleañeros
	function busqueda_cumpleanios($mes, $dia){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	DISTINCT(CONCAT(p.apellidos, ' ', p.nombres)) AS nombre, p.fotografia AS imagen, p.fecha_nac
			FROM	`personal` p
			WHERE	    MONTH(p.fecha_nac) = ".$mes."
					AND DAY(p.fecha_nac) = ".$dia."
					AND p.estado = 1
			ORDER BY DAY(p.fecha_nac) ASC
			";
			//echo "SQL: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -busqueda_cumpleanios- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nombre'] = $row['nombre'];
					$respuesta[$contador]['imagen'] = $row['imagen'];
					
					$dia = date("d", strtotime($row['fecha_nac']));
					$mes = date("m", strtotime($row['fecha_nac']));
					$anio = date("Y");
					
					$fecha_actual = $anio."-".$mes."-".$dia;
					
					$respuesta[$contador]['fecha'] = $this->translation_days(date("l", strtotime($fecha_actual)));
					$respuesta[$contador]['dia'] = $dia;
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}

function translation_days($dia) {
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miércoles";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";
	return $dia;
}

	//cumpleañeros
	function cumpleanios_mes($mes, $dia){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	DISTINCT(CONCAT(p.apellidos, ' ', p.nombres)) AS nombre, p.fecha_nac
			FROM	`personal` p
			WHERE	    MONTH(p.fecha_nac) = ".$mes."
					AND DAY(p.fecha_nac) != ".$dia."
					AND p.estado = 1
			ORDER BY DAY(p.fecha_nac) ASC
			";
			//echo "SQL: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -busqueda_cumpleanios- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nombre'] = $row['nombre'];
					
					$dia = date("d", strtotime($row['fecha_nac']));
					$mes = date("m", strtotime($row['fecha_nac']));
					$anio = date("Y");
					
					$fecha_actual = $anio."-".$mes."-".$dia;
					
					$respuesta[$contador]['fecha'] = $this->translation_days(date("l", strtotime($fecha_actual)));
					$respuesta[$contador]['dia'] = $dia;
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}


}	  
?>