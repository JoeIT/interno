<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Recepcion{
	function Recepcion(){
	}
	
	//muestra el detalle de la orden, sacando los datos de la tabla hoja
	function buscar_asignacion($num_asignacion){
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
			tda.usuario_entrega_corte,
			tda.medio_proceso_fecha,
			tda.medio_proceso_tipo,
			tda.muestra_inicial_fecha
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
			///echo "<br>La consulta: ".$consulta;
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
					$respuesta["medio_proceso_fecha"] = $row['medio_proceso_fecha'];
					$respuesta["medio_proceso_tipo"] = $row['medio_proceso_tipo'];
					$respuesta["muestra_inicial_fecha"] = $row['muestra_inicial_fecha'];
					
					switch ($row['medio_proceso_tipo']){
						case 0:
							$respuesta["medio_proceso_literal"] = "Sin control";
							break;
						case 1:
							$respuesta["medio_proceso_literal"] = "Muestra";
							break;
						case 2:
							$respuesta["medio_proceso_literal"] = "Medio proceso";
							break;
						case 3:
							$respuesta["medio_proceso_literal"] = "Ambos controles";
							break;
					}
				}
				return $respuesta;
			}
		 }
	}

	//modificar la tabla de asignacion
	function modificar_recepcion($num_asignacion, $fecha_hoy, $usuario_entrega_recepcion, $cantidad){
		//'".$fecha_hoy."',
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		fecha_entrega = NOW(),
					usuario_entrega = ".$usuario_entrega_recepcion."
			WHERE	asignacion_detalle_id = ".$num_asignacion."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -modificar asignacion- fall&oacute;: ' . mysql_error());
			
			//se tiene que encontrar el "resultados_asignacion_id" dado el "num_asignacion"
			$consulta = "
			SELECT	tda.detalle_id
			FROM	`tdetalle_asignacion` tda
			WHERE	tda.asignacion_detalle_id = ".$num_asignacion."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -modificar asignacion- fall&oacute;: ' . mysql_error());
			
			if ($resultado){
				if ($row = mysql_fetch_array($resultado)){
					$resultados_asignacion_id = $row['detalle_id'];
				}
			}
			
			$consulta = "
			UPDATE	resultados_asignacion
			SET		entregados = entregados + ".$cantidad."
			WHERE	detalle_id = ".$resultados_asignacion_id."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -modificar asignacion- fall&oacute;: ' . mysql_error());			
			
			return true;
		}
	}

	//muestra el detalle de la orden, sacando los datos de la tabla hoja
	function nombre_usuario($usuario_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	CONCAT(usu.apellidos, ' ', usu.nombres) AS usuario
			FROM	`tusuarios` usu
			WHERE	usu.usuario_id = ".$usuario_id."
			";
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -nombre_usuario- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["usuario"] = $row['usuario'];
				}
				return $respuesta;
			}
		 }
	}
}
?>