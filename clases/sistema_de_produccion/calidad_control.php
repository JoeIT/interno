<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Calidad_control{
	function Calidad_control(){
	}

	//////////////////////////// busqueda del resumen
	function lista_orden_produccion($orden)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	o.orden_id, o.num_orden, c.nombre AS cliente, o.fecha, o.fechaentrega, o.fecharepro
					, o.observacion, SUM(ra.asignados) AS asignados, SUM(ra.entregados) AS entregados, SUM(ra.pendientes) AS pendientes
			FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop
					, `resultados_asignacion` ra
			WHERE	c.cliente_id = o.cliente_id
					AND o.orden_id = tdop.orden_id
					AND tdop.detalle_id = ra.detalle_id
					AND o.num_orden like '%" . $orden . "%'
			GROUP BY o.orden_id
			ORDER BY o.cup_num desc
			LIMIT 0 , 30
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -consulta lista ordenes anual- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["orden_id"] = $row['orden_id'];
					$respuesta[$contador]["num_orden"] = $row['num_orden'];
					$respuesta[$contador]["cliente"] = $row['cliente'];
					$respuesta[$contador]["fecha"] = $row['fecha'];
					
					$fecharepro = $row['fecharepro'];
					
					if (($fecharepro != null) && ($fecharepro != '0000-00-00'))
						$respuesta[$contador]["fechaentrega"] = $fecharepro;
					else
						$respuesta[$contador]["fechaentrega"] = $row['fechaentrega'];
						
					$respuesta[$contador]["observacion"] = $row['observacion'];					
					$respuesta[$contador]["asignados"] = $row['asignados'];
					$respuesta[$contador]["entregados"] = $row['entregados'];
					$respuesta[$contador]["pendientes"] = $row['pendientes'];

					$contador = $contador + 1;
				}
				
				///// para sacar los recepcionados
				$cont=0;
				
				while( $cont < $contador )
				{
					$respuesta[$cont]["recepcionados"]=$this->obtener_recep_orden($respuesta[$cont]["orden_id"]);

  					$cont = $cont + 1;
  				}
				
				
				return $respuesta;
			}
		}
	}
	///////////////////////////////////////////////
	//////////////// obtener - recepcnado por orden
	function obtener_recep_orden($orden)
	{
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
				SELECT	SUM(r.cantidad)
				FROM	tdetalle_asignacion AS d, tdetalleordenesproduccion AS dop, trecepcion AS r
				WHERE  d.detalle_id=dop.detalle_id AND
				       r.asignacion_id=d.asignacion_detalle_id AND 
					   dop.orden_id=".$orden;

            $resultado = mysql_query($consulta) or die('La consulta resumen inicial fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
					$row = mysql_fetch_array($resultado);
					$respuesta = $row[0];
					return $respuesta;
				}
		}
	}
	///////////////////////////////////////////////
	//////////////////  obtener - recepcionados por asignacion
	function obtener_cantidad($detalle)
	{
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
				SELECT	r.cantidad
				FROM	tdetalle_asignacion AS d, trecepcion AS r
				WHERE  d.detalle_id=".$detalle." AND r.asignacion_id=d.asignacion_detalle_id";

            $resultado = mysql_query($consulta) or die('La consulta resumen inicial fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
					$row = mysql_fetch_array($resultado);
					$respuesta = $row[0];
					return $respuesta;
				}
		}
	}
	//////////////////
	/////////////////////////// resumen - control - detalle de un orden ///////////////////
	function obtener_detalle_resumen($orden)
	{
	$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			SELECT  tdop.detalle_id, tdop.cantidad, f.nombre_familia, e.nombre_estilo, tco.descripcion AS color,
					tcli.descripcion AS clip, ra.asignados, ra.entregados, ra.pendientes
			FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop, `familia` f, `resultados_asignacion` ra,
					`tpropiedades` tp, `estilo` e, `tcolores` tco, `tclips` tcli
			WHERE	top.orden_id = tdop.orden_id AND
					tdop.familia_id = f.familia_id AND
					tdop.detalle_id = ra.detalle_id AND
					tdop.propiedad_id = tp.prop_id AND
					tp.color_id = tco.color_id AND
					tp.clip_id = tcli.clip_id AND
					f.estilo_id = e.estilo_id AND
					top.orden_id = ".$orden."
			ORDER by f.nombre_familia, e.nombre_estilo";

            $resultado = mysql_query($consulta) or die('La consulta resumen inicial fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$contador=0;
				while($row = mysql_fetch_array($resultado))
				{
					$respuesta[$contador]["detalle_id"] = $row[0];
  					$respuesta[$contador]["cantidad"] = $row[1];
  					$respuesta[$contador]["nombre_familia"] = $row[2];
					$respuesta[$contador]["nombre_estilo"] = $row[3];
  					$respuesta[$contador]["color"] = $row[4];
  					$respuesta[$contador]["clip"] = $row[5];
  					$respuesta[$contador]["asignados"] = $row[6];
					$respuesta[$contador]["entregados"] = $row[7];
					$respuesta[$contador]["pendientes"] = $row[8];
					$contador = $contador + 1;
  				}
				///// para sacar los recepcionados
				$cont=0;
				
				while( $cont < $contador )
				{
					$respuesta[$cont]["recepcionados"]=$this->obtener_cantidad($respuesta[$cont]["detalle_id"]);

  					$cont = $cont + 1;
  				}
						
				return $respuesta;
			}
		}
	}
	////////////////////////////////////////////
	/////////// reporte control - resumen 
	function lista_ordenes_produccion()
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	o.orden_id, o.num_orden, c.nombre AS cliente, o.fecha, o.fechaentrega, o.fecharepro
					, o.observacion, SUM(ra.asignados) AS asignados, SUM(ra.entregados) AS entregados, SUM(ra.pendientes) AS pendientes
			FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop
					, `resultados_asignacion` ra
			WHERE	c.cliente_id = o.cliente_id
					AND o.orden_id = tdop.orden_id
					AND tdop.detalle_id = ra.detalle_id
			GROUP BY o.orden_id
			ORDER BY o.fecha DESC, o.cup_num DESC
			LIMIT 0 , 30
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -consulta lista ordenes anual- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["orden_id"] = $row['orden_id'];
					$respuesta[$contador]["num_orden"] = $row['num_orden'];
					$respuesta[$contador]["cliente"] = $row['cliente'];
					$respuesta[$contador]["fecha"] = $row['fecha'];
					
					$fecharepro = $row['fecharepro'];
					
					if (($fecharepro != null) && ($fecharepro != '0000-00-00'))
						$respuesta[$contador]["fechaentrega"] = $fecharepro;
					else
						$respuesta[$contador]["fechaentrega"] = $row['fechaentrega'];
						
					$respuesta[$contador]["observacion"] = $row['observacion'];					
					$respuesta[$contador]["asignados"] = $row['asignados'];
					$respuesta[$contador]["entregados"] = $row['entregados'];
					$respuesta[$contador]["pendientes"] = $row['pendientes'];

					$contador = $contador + 1;
				}
				
				///// para sacar los recepcionados
				$cont=0;
				
				while( $cont < $contador )
				{
					$respuesta[$cont]["recepcionados"]=$this->obtener_recep_orden($respuesta[$cont]["orden_id"]);

  					$cont = $cont + 1;
  				}
				
				
				return $respuesta;
			}
		}
	}
	////////////////////////////////////////


	function cerrar_asignacion($num_asignacion,$fecha_cerrado,$usuario_cerrado)
	{
		$con = new DBmanejador;
		if($con->conectar() == true)
		{
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		fecha_cerrado = '".$fecha_cerrado."'
					, usuario_cerrado = '".$usuario_cerrado."'
					, cerrada = 1
			WHERE	asignacion_detalle_id = ".$num_asignacion."";

		}	
		$resultado = mysql_query($consulta) or die('La consulta -cerra la asignacion- fall&oacute;: ' . mysql_error());
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
			tda.cerrada,
			tda.fecha_cerrado,
			tda.usuario_cerrado
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
					$respuesta["cerrada"] = $row['cerrada'];
					$respuesta["fecha_cerrado"] = $row['fecha_cerrado'];
					$respuesta["usuario_cerrado"] = $row['usuario_cerrado'];
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
			SELECT	SUM(tre.cantidad) AS total
			FROM	`tdetalle_asignacion` tda, `trecepcion` tre
			WHERE	tda.asignacion_detalle_id = tre.asignacion_id
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
			SELECT	SUM(tli.cantidad) AS asignados
			FROM	`tdetalle_asignacion` tda, `tlimpieza` tli
			WHERE	tda.asignacion_detalle_id = tli.asignacion_detalle_id
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
			INSERT
			INTO	tlimpieza (asignacion_detalle_id, limpiador_id, fecha_inicio, cantidad, fecha_finalizacion, usuario_limpieza)
			VALUES	(".$asignacion_detalle_id.",".$limpiador_id.",'".$fecha_inicio."', ".$cantidad.", '".$fecha_finalizacion."', ".$usuario_limpieza.")
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar asignacion limpieza- fall&oacute;: ' . mysql_error());
			
			
			//se inserta al control de calidad
			//recuperando el ID de la limpieza
			$limpieza_id = mysql_insert_id();
			
			$consulta = "
			INSERT
			INTO	tcontrol_calidad (limpieza_id, cantidad)
			VALUES	(".$limpieza_id.", ".$cantidad.")
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
			SELECT	SUM(tre.cantidad) AS recepcionados
			FROM	`tdetalle_asignacion` tda, `trecepcion` tre
			WHERE	tda.asignacion_detalle_id = tre.asignacion_id
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
			SELECT	SUM(tli.cantidad) AS asignados_limpieza
			FROM	`tlimpieza` tli
			WHERE	tli.asignacion_detalle_id = ".$num_asignacion."
			ORDER BY tli.fecha_inicio DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cantidad limpieza falta 2- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["asignados_limpieza"] = $row['asignados_limpieza'];

				}
			}

			$consulta = "
			SELECT	SUM(tcc.cantidad) AS asignados_calidad
			FROM	`tcontrol_calidad` tcc, `tlimpieza` tli
			WHERE	tcc.limpieza_id = tli.limpieza_id
					AND tli.asignacion_detalle_id = ".$num_asignacion."
					AND tcc.responsable_control != 0
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cantidad limpieza falta 2- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
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


	//borrar una asignacion de limpieza
	function borrar_asignacion_limpieza($limpieza_id) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE
			FROM	tlimpieza
			WHERE	limpieza_id = ".$limpieza_id;
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -borrar asignacion limpieza- fall&oacute;: ' . mysql_error());
		}
	}

}
?>
