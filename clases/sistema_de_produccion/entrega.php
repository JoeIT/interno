<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Entrega{
	function Entrega(){
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
			tda.entrega_corte AS entregado,
			p.personal_id,
			tda.fecha_entrega_corte,
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
					$respuesta["entregado"] = $row['entregado'];
					$respuesta["personal_id"] = $row['personal_id'];
					$respuesta["fecha_entrega_corte"] = $row['fecha_entrega_corte'];
					$respuesta["usuario_entrega_corte"] = $row['usuario_entrega_corte'];
				}
				return $respuesta;
			}
		 }
	}


	//resumen del despiece
	function resumen_del_despiece($asignacion_id, $cantidad){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "			
			SELECT	tma.nombre AS nombre,
                    CONCAT(tpp.descripcion, ' (', tdi.largo, ' x ', tdi.ancho, ')') AS posicion,
                    SUM(tcom.cantidad) As cantidad,
					tmt.tipo_material_id

			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`tdetalleordenesproduccion` tdop, `tposicion_pieza` tpp, `tdetalle_asignacion` tda,
					`tdimensiones` tdi
			WHERE	tcom.pieza_id = tpi.pieza_id
                    AND tpi.material_id = tma.material_id
                    AND tpi.posicion_pieza_id = tpp.posicion_pieza_id
					AND tpi.dimension_id = tdi.dimension_id
                    AND tma.tipo_material_id = tmt.tipo_material_id
                    AND tcom.detalle_id = tdop.detalle_id
                    AND tdop.detalle_id = tda.detalle_id
                    AND tcom.cantidad >= 1
                    AND tda.asignacion_detalle_id = ".$asignacion_id."
			GROUP BY nombre, posicion
			ORDER BY tmt.ordenar DESC, nombre, posicion, cantidad
			";
			//cuando no tengan dimensiones
            /*$consulta="SELECT	tma.nombre AS nombre,
SUM(tcom.cantidad) As cantidad,
tmt.tipo_material_id
FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
`tdetalleordenesproduccion` tdop,  `tdetalle_asignacion` tda
WHERE	tcom.pieza_id = tpi.pieza_id
AND tpi.material_id = tma.material_id					
AND tma.tipo_material_id = tmt.tipo_material_id
AND tcom.detalle_id = tdop.detalle_id
AND tdop.detalle_id = tda.detalle_id
AND tcom.cantidad >= 1
AND tda.asignacion_detalle_id = ".$asignacion_id."
GROUP BY nombre
ORDER BY tmt.ordenar DESC, nombre, cantidad";*/
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -resumen del despiece- fall&oacute;: ' . mysql_error());
			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
				  $respuesta[$contador]["nombre"] = $row['nombre'];
				  $respuesta[$contador]["posicion"] = $row['posicion'];
				  $respuesta[$contador]["cantidad"] = $row['cantidad'];
				  $respuesta[$contador]["total"] = $row['cantidad'] * $cantidad;
				  $respuesta[$contador]["tipo_material"] = $row['tipo_material_id'];
				  $respuesta[$contador]["para_check"] = $contador + 1;
				  $contador = $contador + 1;
				}
			}
		
		
			$consulta = "
			SELECT	tma.nombre AS nombre,
                    CONCAT(tpp.descripcion, ' (', tdi.largo, ' x ', tdi.ancho, ')') AS posicion,
                    COUNT(tcom.cantidad) As cantidad,
					tmt.tipo_material_id,
					ROUND(1/tcom.cantidad) AS dividido

			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`tdetalleordenesproduccion` tdop, `tposicion_pieza` tpp, `tdetalle_asignacion` tda,
					`tdimensiones` tdi
			WHERE	tcom.pieza_id = tpi.pieza_id
                    AND tpi.material_id = tma.material_id
                    AND tpi.posicion_pieza_id = tpp.posicion_pieza_id
                    AND tpi.dimension_id = tdi.dimension_id
                    AND tma.tipo_material_id = tmt.tipo_material_id
                    AND tcom.detalle_id = tdop.detalle_id
                    AND tdop.detalle_id = tda.detalle_id
                    AND tcom.cantidad < 1
                    AND tda.asignacion_detalle_id = ".$asignacion_id."
			GROUP BY nombre, posicion
			ORDER BY nombre, posicion, cantidad
			";
			//cuando no tengan dimensiones
            /*$consulta="SELECT	tma.nombre AS nombre,
COUNT(tcom.cantidad) As cantidad,
tmt.tipo_material_id,
ROUND(1/tcom.cantidad) AS dividido

FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
`tdetalleordenesproduccion` tdop, `tdetalle_asignacion` tda

WHERE	tcom.pieza_id = tpi.pieza_id
AND tpi.material_id = tma.material_id


AND tma.tipo_material_id = tmt.tipo_material_id
AND tcom.detalle_id = tdop.detalle_id
AND tdop.detalle_id = tda.detalle_id
AND tcom.cantidad < 1
AND tda.asignacion_detalle_id = ".$asignacion_id."
GROUP BY nombre
ORDER BY nombre, cantidad";*/
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -resumen del despiece- fall&oacute;: ' . mysql_error());
			
			//$contador = 0;
			
			if (!$resultado)
				return false;
			else {

				while($row = mysql_fetch_array($resultado)){
				 /* $respuesta[$contador]["nombre"] = $row['nombre']."<b> (1 &divide; ".$row['dividido'].") </b>";*/
				  $respuesta[$contador]["nombre"] = $row['nombre']."(1 &divide; ".$row['dividido'].") ";
				  $respuesta[$contador]["posicion"] = $row['posicion'];
				  $respuesta[$contador]["cantidad"] = $row['cantidad'];
				  $respuesta[$contador]["total"] = $row['cantidad'] * $cantidad;
				  $respuesta[$contador]["tipo_material"] = $row['tipo_material_id'];
				  $respuesta[$contador]["para_check"] = $contador + 1;
				  $contador = $contador + 1;
				}
			}
			
			return $respuesta;
		 }
	}

	//comprobar si el pin es correcto
	function comprobar_pin($personal_id, $pin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	pin
			FROM	`personal` p
			WHERE	p.personal_id = ".$personal_id."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -comprobar pin- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if($row = mysql_fetch_array($resultado)){
					if ($row['pin'] == $pin)
						return true;
					else
						return false;
				}
			}
		}
	}


	//modificar la tabla de asignacion
	function modificar_asignacion($asignacion_detalle_id, $fecha_hoy, $usuario_entrega_corte){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		entrega_corte = 1,
					fecha_entrega_corte = '".$fecha_hoy."',
					usuario_entrega_corte = ".$usuario_entrega_corte."
			WHERE	asignacion_detalle_id = ".$asignacion_detalle_id."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -modificar asignacion- fall&oacute;: ' . mysql_error());
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