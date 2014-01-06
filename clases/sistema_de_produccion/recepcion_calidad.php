<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Recepcion_Calidad{
	function Recepcion_Calidad(){
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
			tda.usuario_entrega_corte,
			tda.usuario_entrega, tda.cerrada,tda.usuario_cerrado,tda.fecha_cerrado
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
					$respuesta["usuario_entrega"] = $row['usuario_entrega'];
					$respuesta["cerrada"] = $row['cerrada'];
					$respuesta["usuario_cerrado"] = $row['usuario_cerrado'];
					$respuesta["fecha_cerrado"] = $row['fecha_cerrado'];
				}
				return $respuesta;
			}
		}
	}
	
	function resumen_recepcion($num_asignacion) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "
			SELECT recepcion_id, asignacion_id, fecha, nombres,apellidos,cantidad,dias_retraso
			FROM trecepcion, tusuarios
			WHERE usuario_recepcion=usuario_id and asignacion_id=".$num_asignacion." order by fecha";
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - resumen_recepcion - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
			    $contador = 0;
				while ($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["recepcion_id"] = $row['recepcion_id'];
					$respuesta[$contador]["asignacion_id"] = $row['asignacion_id'];
					$respuesta[$contador]["fecha"] = $row['fecha'];
					$respuesta[$contador]["usuario_recepcion"] = $row['nombres']." ".$row['apellidos'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["dias_retraso"] = $row['dias_retraso'];
					$contador++;
				}
				return $respuesta;
			}
		}
	}
	
	function total_recepcionado($num_asignacion) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "
			SELECT SUM(cantidad)
			FROM trecepcion
			WHERE asignacion_id=".$num_asignacion;
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - total_recepcionado - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return 0;
			else {
			    $total = 0;
				$row = mysql_fetch_array($resultado);
				$total = $total + $row[0];
                return $total;
			}
		}
	}
	
	function total_para_despacho($num_asignacion, $despacho_id) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
		    $consulta = "
			SELECT	cantidad
			FROM	despacho_detalle
			WHERE	asignacion_id=".$num_asignacion."
					AND despacho_id = ".$despacho_id;
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - total_para_despacho - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return 0;
			else {
				$row = mysql_fetch_array($resultado);
				$total = $row[0];
                return $total;
			}
		}
	}
	
	function registrar_recepcion_sin_retraso($num_asignacion,$cantidad,$usuario,$fecha_finalizacion) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "
			INSERT	into
			trecepcion (asignacion_id, fecha, usuario_recepcion, cantidad, dias_retraso)
			values (".$num_asignacion.", '".$fecha_finalizacion."', ".$usuario.", ".$cantidad.", 0)";
			// echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en ingresar recepcion ' . mysql_error());
		}	
	}

	function registrar_recepcion($num_asignacion, $cantidad, $usuario, $fecha_finalizacion) {
		$fecha_final_div = split("-", $fecha_finalizacion);
		$fecha = date("d-m-Y");
		$validar = new Validador();
		$fecha = $validar->cambiaf_a_mysql($fecha);
		$fecha_actual = date("Y-m-d");
		$fecha_actual_div = split("-",$fecha_actual);
		$dias_retraso = 0;
		//verificamos que el año y mes de las fechas sean iguales
	    //if($fecha_final_div[1]==$fecha_actual_div[1])
		if ($fecha_finalizacion <= $fecha_actual) {
			list($ano1, $mes1, $dia1) = split("-", $fecha_finalizacion);
			$nueva_fecha1 = $dia1."-".$mes1."-".$ano1;
			list($ano2, $mes2, $dia2) = split("-", $fecha_actual);
			$nueva_fecha2 = $dia2."-".$mes2."-".$ano2;
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
			$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2);
			
			$segundos_diferencia = $timestamp2 - $timestamp1;
			$dias_retraso = $segundos_diferencia / (60 * 60 * 24);
		}
		
		if ($dias_retraso < 0)
			$dias_retraso == 0;
		
		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		$dias_retraso = abs($dias_retraso);
		
		//quito los decimales a los días de diferencia
		$dias_retraso = floor($dias_retraso);
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "
			INSERT  into
			trecepcion(asignacion_id,fecha,usuario_recepcion,cantidad,dias_retraso)
			values(".$num_asignacion.",'".$fecha."',".$usuario.",".$cantidad.",".$dias_retraso.")";
			
			// echo $consulta;
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute; en ingresar recepcion ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
}
?>