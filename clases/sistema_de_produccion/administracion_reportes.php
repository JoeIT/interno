<?php
require_once('../../clases/includes/dbmanejador.php');

class Administracion_reportes{
	function Administracion_reportes(){
	}

	//resumen de maquinistas
	function resumen_maquinistas($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	p.personal_id, CONCAT(p.apellidos, ' ', p.nombres) AS maquinsitas
			FROM	  `tdetalle_asignacion` tda
					, `personal` p
			WHERE	    tda.personal_id = p.personal_id
					AND tda.fecha_inicio >= '".$fecha_inicio."'
					AND tda.fecha_finalizacion <= '".$fecha_fin."'
			GROUP BY p.personal_id
			ORDER BY CONCAT(p.apellidos, ' ', p.nombres)
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -resumen_maquinistas- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["personal_id"] = $row['personal_id'];
					$respuesta[$contador]["maquinsitas"] = $row['maquinsitas'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	//resumen de asignaciones
	function resumen_asignaciones($fecha_inicio, $fecha_fin, $personal_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT tda.asignacion_detalle_id
				   , tda.cantidad_asignada AS cantidad
				   , f.nombre_familia AS producto
				   , e.nombre_estilo AS tipo
				   , CONCAT(p.apellidos, ' ', p.nombres) AS maquinsitas
				   , tda.fecha_inicio
				   , tda.fecha_finalizacion
			FROM   `tdetalle_asignacion` tda
				 , `personal` p
				 , `tdetalleordenesproduccion` tdop
				 , `familia` f
				 , `estilo` e
			WHERE		tda.personal_id = p.personal_id
					AND tda.detalle_id = tdop.detalle_id
					AND tdop.familia_id = f.familia_id
					AND f.estilo_id = e.estilo_id
					AND tda.fecha_inicio >= '".$fecha_inicio."'
					AND tda.fecha_finalizacion <= '".$fecha_fin."'
					AND p.personal_id = ".$personal_id."
			";
			//echo "<br>consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -resumen_asignaciones- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]['maquinsitas'] = $row['maquinsitas'];
					$respuesta[$contador]["fecha_inicio"] = $row['fecha_inicio'];
					$respuesta[$contador]["fecha_finalizacion"] = $row['fecha_finalizacion'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	
	
   
}
?>