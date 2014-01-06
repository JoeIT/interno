<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Empaque_cerrado{
	function Empaque_cerrado()
	{
	}
	
	//muestra el detalle de la orden, sacando los datos de la tabla hoja
	function buscar_asignacion($num_asignacion)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			topr.cup_num AS cup,
			tda.asignacion_detalle_id AS asignacion,
			CONCAT(p.apellidos, ' ', p.nombres) AS completo,
			p.clase AS categoria,
			tda.fecha_inicio AS fini,
			tda.fecha_finalizacion AS ffin,
			tda.fecha_reprogramacion AS frep,
			tda.cantidad_asignada As cantidad,
			f.nombre_familia AS modelo,
			es.nombre_estilo AS tipo,
			col.descripcion AS color,
			clip.descripcion AS clip,
			cu.descripcion AS cuero,
			tda.observaciones,
			tda.usuario_entrega AS usuario_recepcion,
			p.personal_id,
			tda.fecha_entrega As fecha_recepcion,
			tda.usuario_entrega_corte
			FROM
			`tdetalle_asignacion` tda, `personal` p, `tdetalleordenesproduccion` tdop,
			`tordenesproduccion` topr, `familia` f, estilo es, `tpropiedades` tpro,
			`tcolores` col, `tclips` clip, `tcueros` cu
			WHERE
			tda.personal_id = p.personal_id
            AND tda.detalle_id = tdop.detalle_id
            AND tdop.orden_id = topr.orden_id
            AND tdop.familia_id = f.familia_id
            AND f.estilo_id = es.estilo_id
            AND tdop.propiedad_id = tpro.prop_id
            AND tpro.color_id = col.color_id
            AND tpro.clip_id = clip.clip_id
            AND tpro.cuero_id = cu.cuero_id
            AND tda.asignacion_detalle_id = ".$num_asignacion."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -buscar asignacion- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["cup"] = $row['cup'];
					$respuesta["asignacion"] = $row['asignacion'];
					$respuesta["completo"] = $row['completo'];
					$respuesta["categoria"] = $row['categoria'];
					$respuesta["fini"] = $row['fini'];
					$respuesta["ffin"] = $row['ffin'];
					$respuesta["frep"] = $row['frep'];
					$respuesta["cantidad"] = $row['cantidad'];
					$respuesta["modelo"] = $row['modelo'];
					$respuesta["tipo"] = $row['tipo'];
					$respuesta["color"] = $row['color'];
					$respuesta["clip"] = $row['clip'];
					$respuesta["cuero"] = $row['cuero'];
					$respuesta["observaciones"] = $row['observaciones'];
					$respuesta["usuario_recepcion"] = $row['usuario_recepcion'];
					$respuesta["personal_id"] = $row['personal_id'];
					$respuesta["fecha_recepcion"] = $row['fecha_recepcion'];
					$respuesta["usuario_entrega_corte"] = $row['usuario_entrega_corte'];
				}
				return $respuesta;
			}
		 }
	}

	//saca el detalle de la asignacion de limpieza
	function detalle_asignacion_limpieza($num_asignacion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT tli.limpieza_id,  tli.asignacion_detalle_id, tli.limpiador_id,
				   tli.fecha_inicio, tli.cantidad, tli.fecha_finalizacion,
				   CONCAT(p.apellidos, ' ', p.nombres) AS completo
			FROM   tlimpieza tli, personal p
			WHERE  tli.limpiador_id = p.personal_id
				   AND tli.asignacion_detalle_id = ".$num_asignacion."
			ORDER BY tli.fecha_inicio DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle asignacion limpieza- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["limpieza_id"] = $row['limpieza_id'];
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["tli.limpiador_id"] = $row['tli.limpiador_id'];
					$respuesta[$contador]["fecha_inicio"] = $row['fecha_inicio'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["fecha_finalizacion"] = $row['fecha_finalizacion'];
					$respuesta[$contador]["completo"] = $row['completo'];
					
					$contador ++;
				}
				return $respuesta;
			}
		 }
	}

	//saca el detalle de la asignacion de limpieza
	function cantidad_limpieza_falta($num_asignacion){
		//valores iniciales
		$respuesta["total"] = 0;
		$respuesta["pendientes"] = 0;
		$respuesta["asignados"] = 0;
		
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT SUM(tre.cantidad) AS total
			FROM `tdetalle_asignacion` tda, `trecepcion` tre
			WHERE
			tda.asignacion_detalle_id = tre.asignacion_id
			AND tda.asignacion_detalle_id = ".$num_asignacion."
			GROUP BY tda.asignacion_detalle_id
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cantidad limpieza falta- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["total"] = $row['total'];
				}
			}
			
			$consulta = "
			SELECT SUM(tli.cantidad) AS asignados
			FROM `tdetalle_asignacion` tda, `tlimpieza` tli
			WHERE
			tda.asignacion_detalle_id = tli.asignacion_detalle_id
			AND tda.asignacion_detalle_id = ".$num_asignacion."
			GROUP BY tda.asignacion_detalle_id
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cantidad limpieza falta 2- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["asignados"] = $row['asignados'];
				}
			}
			
			$respuesta["pendientes"] = $respuesta["total"] - $respuesta["asignados"];
			return $respuesta;
		}
	}

	//insertar asignacion de limpieza
	function insertar_asignacion_limpieza($asignacion_detalle_id, $limpiador_id, $fecha_inicio, $cantidad, $fecha_finalizacion, $usuario_limpieza){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//se inserta a la tabla limpieza
			$consulta = "
			INSERT INTO tlimpieza (asignacion_detalle_id, limpiador_id, fecha_inicio, cantidad, fecha_finalizacion, usuario_limpieza) VALUES (".$asignacion_detalle_id.",".$limpiador_id.",'".$fecha_inicio."', ".$cantidad.", '".$fecha_finalizacion."', ".$usuario_limpieza.")
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar asignacion limpieza- fall&oacute;: ' . mysql_error());
			
			
			//se inserta al control de calidad
			//recuperando el ID de la limpieza
			$limpieza_id = mysql_insert_id();
			
			$consulta = "
			INSERT INTO tcontrol_calidad (limpieza_id, cantidad) VALUES (".$limpieza_id.", ".$cantidad.")
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar control calidad- fall&oacute;: ' . mysql_error());
		 }
	}


	//saca el detalle de la asignacion de control de calidad
	function cantidad_calidad_falta($num_asignacion){
		//valores iniciales
		$respuesta["recepcionados"] = 0;
		$respuesta["asignados_limpieza"] = 0;
		$respuesta["asignados_calidad"] = 0;
		$respuesta["pendientes_calidad"] = 0;
		
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT SUM(tre.cantidad) AS recepcionados
			FROM `tdetalle_asignacion` tda, `trecepcion` tre
			WHERE
			tda.asignacion_detalle_id = tre.asignacion_id
			AND tda.asignacion_detalle_id = ".$num_asignacion."
			GROUP BY tda.asignacion_detalle_id
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cantidad limpieza falta- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["recepcionados"] = $row['recepcionados'];
				}
			}
			
			$consulta = "
			SELECT SUM(tli.cantidad) AS asignados_limpieza, SUM(tcc.cantidad) AS asignados_calidad
			FROM `tlimpieza` tli LEFT JOIN `tcontrol_calidad` tcc ON tli.limpieza_id = tcc.limpieza_id
			WHERE tli.asignacion_detalle_id = ".$num_asignacion."
			GROUP BY tli.asignacion_detalle_id
			ORDER BY tli.fecha_inicio DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cantidad limpieza falta 2- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["asignados_limpieza"] = $row['asignados_limpieza'];
					$respuesta["asignados_calidad"] = $row['asignados_calidad'];
				}
			}
			
			$respuesta["pendientes_calidad"] = $respuesta["asignados_limpieza"] - $respuesta["asignados_calidad"];
			
			//si los campos son nulos los convertimos a '0'
			foreach ($respuesta as $indice=>$actual){
				if ($actual == null)
					$respuesta[$indice] = 0;
			}
			
			return $respuesta;
		}
	}


	//saca el detalle de la asignacion de control de calidad
	function detalle_asignacion_control($num_asignacion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tcc.control_calidad_id, CONCAT(p.apellidos, ' ', p.nombres) AS limpiador
					, tli.fecha_inicio AS fecha_limpieza, tli.cantidad As limpieza_cantidad
					, CONCAT(p1.apellidos, ' ', p1.nombres) AS controlador
					, tcc.responsable_control AS controlador_id
			FROM	`tcontrol_calidad` tcc LEFT JOIN `personal` p1 ON 
					tcc.responsable_control = p1.personal_id,
					`tlimpieza` tli, personal p
			WHERE	tcc.limpieza_id = tli.limpieza_id
					AND tli.limpiador_id = p.personal_id
					AND tli.asignacion_detalle_id = ".$num_asignacion."
			ORDER BY tli.fecha_inicio DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle asignacion limpieza- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["control_calidad_id"] = $row['control_calidad_id'];
					$respuesta[$contador]["limpiador"] = $row['limpiador'];
					$respuesta[$contador]["fecha_limpieza"] = $row['fecha_limpieza'];
					$respuesta[$contador]["limpieza_cantidad"] = $row['limpieza_cantidad'];
					$respuesta[$contador]["controlador"] = $row['controlador'];
					$respuesta[$contador]["controlador_id"] = $row['controlador_id'];
					
					$contador ++;
				}
				return $respuesta;
			}
		 }
	}


	//actualizar asignaciones de control calidad
	function actualizar_asignacion_control($control_calidad_id, $fecha_inicio_control, $fecha_finalizacion_control, $responsable_control, $usuario_control){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//se inserta a la tabla limpieza
			$consulta = "
			UPDATE	tcontrol_calidad
			SET		fecha_inicio_control = '".$fecha_inicio_control."'
					, fecha_finalizacion_control = '".$fecha_finalizacion_control."'
					, responsable_control = ".$responsable_control."
					, usuario_control = ".$usuario_control."
			WHERE	control_calidad_id = ".$control_calidad_id."
					AND responsable_control != ".$responsable_control."";
					
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -actualizar asignacion control- fall&oacute;: ' . mysql_error());
		 }
	}

}
?>