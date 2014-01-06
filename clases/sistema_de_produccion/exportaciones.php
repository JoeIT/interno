<?php
require_once('../../clases/includes/dbmanejador.php');

class Exportaciones{
	function Exportaciones(){
	}

	//observaciones de las ordenes de produccion
	function observaciones_pedido($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	top.num_orden, top.observacion
			FROM	  `despacho` de
					, `despacho_detalle` dede
					, `tdetalle_asignacion` tda
					, `tdetalleordenesproduccion` tdop
					, `tordenesproduccion` top
			WHERE		de.despacho_id = dede.despacho_id
					AND dede.asignacion_id = tda.asignacion_detalle_id
					AND tda.detalle_id = tdop.detalle_id
					AND tdop.orden_id = top.orden_id
					AND de.despacho_id = ".$deid."
					GROUP BY top.orden_id
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -ultimos despachos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$mensaje = "";
				while($row = mysql_fetch_array($resultado)){
					if (trim($row['observacion']) != "") {
						if ($mensaje != "")
							$mensaje = $mensaje ."<br><strong>". $row['num_orden'] ." : </strong>". $row['observacion'];
						else
							$mensaje = $mensaje ."<strong>".$row['num_orden'] ." : </strong>". $row['observacion'];
					}
				}
				return $mensaje;
			}
		}
	}

	//buscar ultimos 30 despacho
	function ultimos_despachos(){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			  de.despacho_id
			, de.nombre_despacho
			, de.fecha_despacho
			, SUM(dede.cantidad) AS cantidad
			
			FROM
			`despacho` de
			, `despacho_detalle` dede
			WHERE
			de.despacho_id = dede.despacho_id
			
			GROUP BY de.despacho_id
			
			ORDER BY de.despacho_id DESC
			LIMIT 0,30
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -ultimos despachos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["despacho_id"] = $row['despacho_id'];
					$respuesta[$contador]["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta[$contador]["fecha_despacho"] = $row['fecha_despacho'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["observaciones"] = $this->observaciones_pedido($row['despacho_id']);

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}


	//mostrar detalle del despacho
	function detalle_despachos($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  de.despacho_id
					, de.nombre_despacho
					, de.fecha_despacho
					, SUM(dede.cantidad) AS cantidad
					
			FROM	  `despacho` de
					, `despacho_detalle` dede
			WHERE	    de.despacho_id = dede.despacho_id
					AND de.despacho_id = ".$deid."
			GROUP BY de.despacho_id
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -ultimos despachos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
					$respuesta["despacho_id"] = $row['despacho_id'];
					$respuesta["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta["fecha_despacho"] = $row['fecha_despacho'];
					$respuesta["cantidad"] = $row['cantidad'];
				}
				return $respuesta;
			}
		}
	}

	//reporte los despachos
	function reporte_despachos($despacho_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			  tdop.detalle_id AS detalle_id
			, SUM(dede.cantidad) AS cantidad
			, f.nombre_familia AS modelo
			, e.nombre_estilo AS tipo
			, tcli.descripcion AS clip
			, tcol.descripcion AS color
			, tdop.pedido AS pedido
			, tdop.observacion AS observaciones
			, tdop.grabado AS grabado
			, tdop.lugargrabado AS lugargrabado
			, tch.descripcion AS sello
			, top.cup_num AS orden
			, top.observacion
			
			FROM
			  `despacho` de
			, `despacho_detalle` dede
			, `tdetalle_asignacion` tda
			, `tdetalleordenesproduccion` tdop
			, `familia` f
			, `estilo` e
			, `tpropiedades` tpro
			, `tcolores` tcol
			, `tclips` tcli
			, `tchapas` tch
			, `tordenesproduccion` top
			
			WHERE
			de.despacho_id = dede.despacho_id
			AND dede.asignacion_id = tda.asignacion_detalle_id
			AND tda.detalle_id = tdop.detalle_id
			AND tdop.familia_id = f.familia_id
			AND f.estilo_id = e.estilo_id
			AND tdop.propiedad_id = tpro.prop_id
			AND tpro.color_id = tcol.color_id
			AND tpro.clip_id = tcli.clip_id
			AND tpro.sello_id = tch.chapa_id
			AND tdop.orden_id = top.orden_id
			
			AND de.despacho_id = ".$despacho_id."
			
			GROUP BY tdop.detalle_id";
			
			//echo "<br>con: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_arreglos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["detalle_id"] = $row['detalle_id'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["modelo"] = $row['modelo'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]["clip"] = $row['clip'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["pedido"] = $row['pedido'];
					$respuesta[$contador]["observaciones"] = $row['observaciones'];
					$respuesta[$contador]["grabado"] = $row['grabado'];
					$respuesta[$contador]["lugargrabado"] = $row['lugargrabado'];
					$respuesta[$contador]["sello"] = $row['sello'];
					$respuesta[$contador]["orden"] = $row['orden'];
					$respuesta[$contador]["observacion"] = $row['observacion'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	//buscar despacho
	function buscar_despacho($nombre_despacho){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			  de.despacho_id
			, de.nombre_despacho
			, de.fecha_despacho
			, SUM(dede.cantidad) AS cantidad
			
			FROM
			`despacho` de
			, `despacho_detalle` dede
			WHERE
			de.despacho_id = dede.despacho_id
			AND
			de.nombre_despacho LIKE '%".$nombre_despacho."%'
			GROUP BY de.despacho_id
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -buscar despacho- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["despacho_id"] = $row['despacho_id'];
					$respuesta[$contador]["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta[$contador]["fecha_despacho"] = $row['fecha_despacho'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["observaciones"] = $this->observaciones_pedido($row['despacho_id']);

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}


}
?>