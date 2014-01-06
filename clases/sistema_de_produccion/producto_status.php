<?php
require_once('../../clases/includes/dbmanejador.php');

class ProductoStatus{
	function ProductoStatus(){
	}
	
	//consulta la lista de ordenes anual
	function estado_productos($modelo, $cuero, $clip, $cliente){
		$con = new DBmanejador;
		if($con->conectar() == true){
			if ($cuero == -1)//sin cuero
				$consulta = "
				SELECT	tordenesproduccion.num_orden AS op
						, tclientes.nombre AS cliente
						, familia.nombre_familia AS producto
						, estilo.nombre_estilo AS modelo
						, tcueros.descripcion AS cuero
						, tclips.descripcion AS clip
						, tdetalleordenesproduccion.pedido AS pedido
						, tdetalle_asignacion.asignacion_detalle_id AS asignacion
						, tdetalle_asignacion.cantidad_asignada AS cantasignacion
						, tdetalle_asignacion.fecha_inicio AS feciniasig
						, tdetalle_asignacion.fecha_finalizacion AS fecfinasig
						, tdetalle_asignacion.fecha_reprogramacion As fecrepasig
						, CONCAT(despacho.nombre_despacho , ' ', despacho.fecha_despacho) AS despacho
				FROM	(despacho_detalle RIGHT JOIN (tdetalle_asignacion RIGHT JOIN (tcueros INNER JOIN (((((tdetalleordenesproduccion INNER JOIN (tordenesproduccion JOIN tclientes On tordenesproduccion.cliente_id = tclientes.cliente_id) ON tdetalleordenesproduccion.orden_id = tordenesproduccion.orden_id) INNER JOIN familia ON tdetalleordenesproduccion.familia_id = familia.familia_id) INNER JOIN tpropiedades ON tdetalleordenesproduccion.propiedad_id = tpropiedades.prop_id) INNER JOIN estilo ON familia.estilo_id = estilo.estilo_id) INNER JOIN tclips ON tpropiedades.clip_id = tclips.clip_id) ON tcueros.cuero_id = tpropiedades.cuero_id) ON tdetalle_asignacion.detalle_id = tdetalleordenesproduccion.detalle_id) ON despacho_detalle.asignacion_id = tdetalle_asignacion.asignacion_detalle_id) LEFT JOIN despacho ON despacho_detalle.despacho_id = despacho.despacho_id
				WHERE	familia.familia_id=".$modelo."
						AND tpropiedades.clip_id=".$clip."
						AND tclientes.cliente_id=".$cliente."
				ORDER BY tordenesproduccion.orden_id DESC
				";
			else
				$consulta = "
				SELECT	tordenesproduccion.num_orden AS op
						, tclientes.nombre AS cliente
						, familia.nombre_familia AS producto
						, estilo.nombre_estilo AS modelo
						, tcueros.descripcion AS cuero
						, tclips.descripcion AS clip
						, tdetalleordenesproduccion.pedido AS pedido
						, tdetalle_asignacion.asignacion_detalle_id AS asignacion
						, tdetalle_asignacion.cantidad_asignada AS cantasignacion
						, tdetalle_asignacion.fecha_inicio AS feciniasig
						, tdetalle_asignacion.fecha_finalizacion AS fecfinasig
						, tdetalle_asignacion.fecha_reprogramacion As fecrepasig
						, CONCAT(despacho.nombre_despacho , ' ', despacho.fecha_despacho) AS despacho
				FROM	(despacho_detalle RIGHT JOIN (tdetalle_asignacion RIGHT JOIN (tcueros INNER JOIN (((((tdetalleordenesproduccion INNER JOIN (tordenesproduccion JOIN tclientes On tordenesproduccion.cliente_id = tclientes.cliente_id) ON tdetalleordenesproduccion.orden_id = tordenesproduccion.orden_id) INNER JOIN familia ON tdetalleordenesproduccion.familia_id = familia.familia_id) INNER JOIN tpropiedades ON tdetalleordenesproduccion.propiedad_id = tpropiedades.prop_id) INNER JOIN estilo ON familia.estilo_id = estilo.estilo_id) INNER JOIN tclips ON tpropiedades.clip_id = tclips.clip_id) ON tcueros.cuero_id = tpropiedades.cuero_id) ON tdetalle_asignacion.detalle_id = tdetalleordenesproduccion.detalle_id) ON despacho_detalle.asignacion_id = tdetalle_asignacion.asignacion_detalle_id) LEFT JOIN despacho ON despacho_detalle.despacho_id = despacho.despacho_id
				WHERE	familia.familia_id=".$modelo."
						AND tpropiedades.clip_id=".$clip."
						AND tclientes.cliente_id=".$cliente."
						AND tcueros.cuero_id=".$cuero."
				ORDER BY tordenesproduccion.orden_id DESC
				";
			
			$resultado = mysql_query($consulta) or die ('La consulta -estado productos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["op"] = $row['op'];
					$respuesta[$contador]["cliente"] = $row['cliente'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["modelo"] = $row['modelo'];
					$respuesta[$contador]["cuero"] = $row['cuero'];
					$respuesta[$contador]["clip"] = $row['clip'];
					$respuesta[$contador]["pedido"] = $row['pedido'];
					$respuesta[$contador]["asignacion"] = $row['asignacion'];
					$respuesta[$contador]["cantasignacion"] = $row['cantasignacion'];
					$respuesta[$contador]["feciniasig"] = $row['feciniasig'];
					$respuesta[$contador]["fecfinasig"] = $row['fecfinasig'];
					$respuesta[$contador]["fecrepasig"] = $row['fecrepasig'];
					$respuesta[$contador]["despacho"] = $row['despacho'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}	

}
?>