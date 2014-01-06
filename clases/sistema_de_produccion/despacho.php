<?php
require_once('../../clases/includes/dbmanejador.php');

class Despacho{
	function Despacho(){
	}
	//Insertar nuevo despacho
	function insertar_nuevo_despacho($nombre_despacho, $fecha_despacho, $fecha_creacion, $usuario_creador){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO despacho (nombre_despacho, fecha_despacho, fecha_creacion, usuario_creador)
			VALUES ('".$nombre_despacho."','".$fecha_despacho."','".$fecha_creacion."',".$usuario_creador.")
			";
			//echo "<br>Constula: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -insertar nuevo despacho- fall&oacute;: ' . mysql_error());
			return mysql_insert_id();
		}
	}

	//sacar descripcion de despacho
	function descripcion_despacho($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	de.despacho_id, de.nombre_despacho, de.fecha_despacho
			FROM	despacho de
			WHERE	de.despacho_id = ".$deid;
			//echo "<br>Constula: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -descripcion despacho- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
					$respuesta["despacho_id"] = $row['despacho_id'];
					$respuesta["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta["fecha_despacho"] = $row['fecha_despacho'];
				}
				return $respuesta;
			}
		}
	}

	//busqueda de ordenes de produccion
	function busqueda_ordenes($num_orden){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT	top.orden_id, top.num_orden, top.cup_num
			FROM	`tordenesproduccion` top
			WHERE	top.num_orden like '%".$num_orden."%'
			ORDER BY top.fecha DESC, top.cup_num DESC
			LIMIT 0,20
			";
            $resultado = mysql_query($consulta) or die ('La consulta -busqueda ordenes- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['orden_id'] = $row['orden_id'];
					$respuesta[$contador]['num_orden'] = $row['num_orden'];
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}
	
	//sacar el id de una orden
	function busqueda_id_orden($num_orden){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT top.orden_id, top.num_orden
			FROM `tordenesproduccion` top
			WHERE
			top.num_orden = '".$num_orden."'
			";
			
			//echo "<br>Consulta: ". $consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -busqueda ordenes- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				if($row = mysql_fetch_array($resultado)){
					return $row['orden_id'];
				}
		  	}
		}
	}
	
	//resumen del detalle
	function resumen_detalle($orden_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  tdop.detalle_id
					, f.nombre_familia AS producto
					, e. nombre_estilo AS estilo
					, tcu.descripcion AS cuero
					, tc.descripcion AS color
					, tcl.descripcion AS clip
					, tdop.pedido, ra.asignados
					, ra.entregados
					, ra.pendientes
					, tdop.cantidad
					, SUM(dede.cantidad) AS despachado
					,tda.asignacion_detalle_id,tdop.bloqueado
					
			FROM	  `tordenesproduccion` top
					, `familia` f
					, `estilo` e
					, `tpropiedades` tpr
					, `tcueros` tcu
					, `tcolores` tc
					, `tclips` tcl
					, `resultados_asignacion` ra
                    , `tdetalleordenesproduccion` tdop LEFT JOIN `tdetalle_asignacion` tda ON tdop.detalle_id = tda.detalle_id LEFT JOIN `despacho_detalle` dede ON tda.asignacion_detalle_id = dede.asignacion_id
					
			WHERE	top.orden_id = tdop.orden_id
					AND tdop.familia_id = f.familia_id
					AND f.estilo_id = e.estilo_id
					AND tdop.propiedad_id = tpr.prop_id
					AND tpr.cuero_id = tcu.cuero_id
					AND tpr.color_id = tc.color_id
					AND tpr.clip_id = tcl.clip_id
					AND tdop.detalle_id = ra.detalle_id

					AND top.orden_id = ".$orden_id."
            GROUP BY tdop.detalle_id
			ORDER BY tdop.pedido, f.nombre_familia ASC, nombre_estilo, tc.descripcion, tcl.descripcion
			";
			//echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -consulta lista ordenes anual- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["detalle_id"] = $row['detalle_id'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["estilo"] = $row['estilo'];
					$respuesta[$contador]["cuero"] = $row['cuero'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["clip"] = $row['clip'];
					$respuesta[$contador]["pedido"] = $row['pedido'];
					$respuesta[$contador]["asignados"] = $row['asignados'];
					$respuesta[$contador]["pendientes"] = $row['pendientes'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["despachado"] = $row['despachado'];
					$respuesta[$contador]["bloqueado"] = $row['bloqueado'];
					
					$respuesta[$contador]["pend_despachar"] = $row['asignados'] - $row['despachado'];
 
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["habilitar"] = $this->contar($row['detalle_id']);
					
					
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	//insertar en la tabla detalle despacho BORRAR
	function insertar_elementos($despacho_id, $asignacion_id, $cantidad){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//verificamos si existe este elemento para despachar
			$consulta = "
			SELECT	dede.despacho_detalle_id, dede.cantidad
			FROM	`despacho_detalle` dede
			WHERE	dede.despacho_id = ".$despacho_id."
					AND asignacion_id = ".$asignacion_id."
			";
			//echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -insertar elementoss select- fall&oacute;: ' . mysql_error());
			
			if (!$resultado){
				return false;
			} else{
				//si existe modificamos
				if ($row = mysql_fetch_array($resultado)){
					$nueva_cantidad = $row['cantidad'] + $cantidad;
					
					$consulta_actualizar = "
					UPDATE	despacho_detalle 
					SET		cantidad = ".$nueva_cantidad."
					WHERE	despacho_detalle_id = ".$row['despacho_detalle_id']."
					";
					//echo "<br>Consulta: ". $consulta_actualizar;
					mysql_query($consulta_actualizar) or die ('La consulta -insertar elementos update- fall&oacute;: ' . mysql_error());					
				} else {
					//
					//si no existe insertamos
					$consulta_insertar = "
					INSERT INTO despacho_detalle (despacho_id, asignacion_id, cantidad)
					VALUES ('".$despacho_id."', '".$asignacion_id."', '".$cantidad."')
					";
					//echo "<br>Consulta: ". $consulta_insertar;
					mysql_query($consulta_insertar) or die ('La consulta -insertar elementos insert- fall&oacute;: ' . mysql_error());
				}
			}
		}
	}


	//insertar en la tabla detalle despacho
	function mostrar_asignaciones($did){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dede.despacho_detalle_id, tda.asignacion_detalle_id
					, CONCAT(p.apellidos, ' ', p.nombres) AS maquinista
                    , SUM(tda.cantidad_asignada) AS cantidad_asignada
                    , SUM(dede.cantidad) AS despachado
                    , p.personal_id
			FROM	`tdetalleordenesproduccion` tdop
					, `tdetalle_asignacion` tda LEFT JOIN `despacho_detalle` dede ON tda.asignacion_detalle_id = dede.asignacion_id
					, `personal` p
			WHERE	tdop.detalle_id = tda.detalle_id
					AND tda.personal_id = p.personal_id
					AND tdop.detalle_id = ".$did."
			GROUP BY tda.asignacion_detalle_id
            ORDER BY maquinista
			";
			//echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -insertar elementos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["maquinista"] = $row['maquinista'];
					$respuesta[$contador]["cantidad_asignada"] = $row['cantidad_asignada'];
					
					if ($row['despachado'] != null)
						$respuesta[$contador]["despachado"] = $row['despachado'];
					else
						$respuesta[$contador]["despachado"] = 0;
					
					$respuesta[$contador]["falta"] = $row['cantidad_asignada'] - $row['despachado'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	//mostrar la tabla detalle despacho
	function mostrar_elementos($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	top.num_orden, dede.despacho_detalle_id
					, CONCAT(f.nombre_familia, ' - ', e.nombre_estilo, ' - ', tc.descripcion, ' - ', tcl.descripcion) AS descripcion_producto
					, tda.asignacion_detalle_id
					, CONCAT(p.apellidos, ' ', p.nombres) AS maquinista
					, dede.cantidad
					, tdop.pedido,tdop.detalle_id
			FROM	`despacho` de, `despacho_detalle` dede, `tdetalle_asignacion` tda
					, `tdetalleordenesproduccion` tdop, `familia` f, `estilo` e
					, `tpropiedades` tpr, `tcolores` tc, `tclips` tcl, `personal` p
					, `tordenesproduccion` top
			WHERE	de.despacho_id = dede.despacho_id
					AND dede.asignacion_id = tda.asignacion_detalle_id
					AND tda.detalle_id = tdop.detalle_id
					AND tdop.familia_id = f.familia_id
					AND f.estilo_id = e.estilo_id
					AND tdop.propiedad_id = tpr.prop_id
					AND tpr.color_id = tc.color_id
					AND tpr.clip_id = tcl.clip_id
					AND tda.personal_id = p.personal_id
					AND tdop.orden_id = top.orden_id
					
					AND de.despacho_id = ".$deid."
			ORDER BY tda.asignacion_detalle_id";
			//echo "<br>Consulta: ". $consulta;
			//echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -mostrar elementos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["num_orden"] = $row['num_orden'];
					$respuesta[$contador]["despacho_detalle_id"] = $row['despacho_detalle_id'];
					$respuesta[$contador]["descripcion_producto"] = $row['descripcion_producto'];
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["maquinista"] = $row['maquinista'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["pedido"] = $row['pedido'];
					$respuesta[$contador]["detalle_id"] = $row['detalle_id'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	//eliminar un detalle del despacho
	function eliminar_detalle_despacho($dedeid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE
			FROM	despacho_detalle
			WHERE	despacho_detalle_id = ".$dedeid;
			
			//echo "<br>Consulta: ". $consulta;
            mysql_query($consulta) or die ('La consulta -eliminar detalle despacho- fall&oacute;: ' . mysql_error());
		}
	}


	//buscar despacho
	function buscar_despacho($nombre_despacho){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	de.despacho_id, de.nombre_despacho, de.fecha_despacho
			FROM	`despacho` de
			WHERE	de.nombre_despacho LIKE '%".$nombre_despacho."%'
			ORDER BY de.fecha_despacho DESC
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

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	
	//buscar ultimos 40 despacho
	function ultimos_despachos(){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	de.despacho_id, de.nombre_despacho, de.fecha_despacho, de.migrar_administracion
			FROM	`despacho` de
			ORDER BY de.fecha_despacho DESC
			LIMIT 0,40
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
					$respuesta[$contador]["migrar_administracion"] = $row['migrar_administracion'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	

	//mostrar la tabla detalle despacho para el reporte
	function mostrar_elementos_imprimir($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT tda.asignacion_detalle_id
				   , dede.cantidad
				   , f.nombre_familia AS producto
				   , e.nombre_estilo AS tipo
				   , tcl.descripcion AS clip
				   , tc.descripcion AS color
				   , tdop.pedido AS pedido
				   , tdop.observacion AS observaciones
				   , top.num_orden AS OP
				   , CONCAT(p.apellidos, ' ', p.nombres) AS maquinista
				   , tch.descripcion AS sello
				   , tdop.grabado
				   , tdop.lugargrabado
				   , top.observacion
			FROM
				`despacho` de, `despacho_detalle` dede, `tdetalle_asignacion` tda
				, `tdetalleordenesproduccion` tdop, `familia` f, `estilo` e
				, `tpropiedades` tpr, `tcolores` tc, `tclips` tcl, `personal` p
				, `tordenesproduccion` top, tchapas tch
			WHERE
				de.despacho_id = dede.despacho_id
				AND dede.asignacion_id = tda.asignacion_detalle_id
				AND tda.detalle_id = tdop.detalle_id
				AND tdop.familia_id = f.familia_id
				AND f.estilo_id = e.estilo_id
				AND tdop.propiedad_id = tpr.prop_id
				AND tpr.color_id = tc.color_id
				AND tpr.clip_id = tcl.clip_id
				AND tpr.sello_id = tch.chapa_id
				AND tda.personal_id = p.personal_id
				AND tdop.orden_id = top.orden_id
				
				AND de.despacho_id = ".$deid."
			ORDER BY pedido, asignacion_detalle_id";
			
			$resultado = mysql_query($consulta) or die ('La consulta -mostrar elementos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]["clip"] = $row['clip'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["pedido"] = $row['pedido'];
					$respuesta[$contador]["observaciones"] = $row['grabado']."-".$row['lugargrabado']."<br>".$row['observaciones'];
					$respuesta[$contador]["OP"] = $row['OP'];
					$respuesta[$contador]["maquinista"] = $row['maquinista'];
					$respuesta[$contador]["sello"] = $row['sello'];
					$respuesta[$contador]["observacion"] = $row['observacion'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	//reporte de maquinistas
	function reporte_maquinistas($despacho_id){
		//parametros para generar sueldos
		$descuentro_retraso = 5;
		$cantidad_incremento = 200;
		
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			  CONCAT(de.nombre_despacho, ' ', de.fecha_despacho) AS despacho
			, tda.asignacion_detalle_id AS codigo
			, o.cup_num AS op
			, CONCAT(p.apellidos, ' ', p.nombres) AS maquinista
			, f.nombre_familia AS producto
			, e.nombre_estilo AS tipo
			, tco.descripcion AS color
			, tdop.observacion AS obs
			, SUM(tre.cantidad) AS entregada
			, tda.fecha_finalizacion AS f_prevista
			, tda.fecha_reprogramacion AS reprog
			, MAX(tre.fecha) AS f_real
			, MAX(tre.dias_retraso) AS diferencia
			, (trech.cantidad) AS rechazados
			, tclf.nombre_fallo
			, tda.observaciones AS observacion
			FROM
			  `despacho` de
			, `despacho_detalle` dede
			, `tdetalle_asignacion` tda
			, `tdetalleordenesproduccion` tdop
			, `tordenesproduccion` o
			, `personal` p
			, `familia` f
			, `estilo` e
			, `tpropiedades` tpr
			, `tcolores` tco
			, `trecepcion` tre  LEFT JOIN `trechazo` trech ON tre.asignacion_id = trech.asignacion_id LEFT JOIN `tclasificacion_fallos` tclf ON trech.clasificacion_fallo_id = tclf.clasificacion_fallo_id
			WHERE
			de.despacho_id = dede.despacho_id
			AND dede.asignacion_id = tda.asignacion_detalle_id
			AND tda.detalle_id = tdop.detalle_id
			AND tdop.orden_id = o.orden_id
			AND tda.personal_id = p.personal_id
			AND tdop.familia_id = f.familia_id
			AND f.estilo_id = e.estilo_id
			AND tdop.propiedad_id = tpr.prop_id
			AND tpr.color_id = tco.color_id
			AND tda.asignacion_detalle_id = tre.asignacion_id
			AND de.despacho_id = ".$despacho_id."
			
			GROUP BY tda.asignacion_detalle_id, trech.rechazo_id
			ORDER BY tda.asignacion_detalle_id
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte maquinista- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$asignacion = $row['codigo'];
					$nombre_fallo = $row['nombre_fallo'];
					
					$maquinistas[$asignacion]['codigo'] = $row['codigo'];
					$maquinistas[$asignacion]['op'] = $row['op'];
					$maquinistas[$asignacion]['maquinista'] = $row['maquinista'];
					$maquinistas[$asignacion]['producto'] = $row['producto'];
					$maquinistas[$asignacion]['tipo'] = $row['tipo'];
					$maquinistas[$asignacion]['color'] = $row['color'];
					$maquinistas[$asignacion]['obs'] = $row['obs'];
					$maquinistas[$asignacion]['entregada'] = $row['entregada'];
					$maquinistas[$asignacion]['f_prevista'] = $row['f_prevista'];
					$maquinistas[$asignacion]['reprog'] = $row['reprog'];
					$maquinistas[$asignacion]['f_real'] = $row['f_real'];
					$maquinistas[$asignacion]['diferencia'] = $row['diferencia'];
				
					$maquinistas[$asignacion][$nombre_fallo] += $row['rechazados'];
					$maquinistas[$asignacion]['rechazados'] = $maquinistas[$asignacion]['B'] + $maquinistas[$asignacion]['C'];
					$maquinistas[$asignacion]['aceptada'] = $maquinistas[$asignacion]['entregada'] - $maquinistas[$asignacion]['rechazados'];
					$maquinistas[$asignacion]['porcentaje'] = ($maquinistas[$asignacion]['rechazados'] * 100)/$maquinistas[$asignacion]['entregada'];
				
					$maquinistas[$asignacion]['d_retraso'] = $descuentro_retraso * $maquinistas[$asignacion]['diferencia'];
					$maquinistas[$asignacion]['d_rechazo'] = $maquinistas[$asignacion]['porcentaje']."%";
					
					if (($maquinistas[$asignacion]['entregada'] >= $cantidad_incremento) && ($maquinistas[$asignacion]['rechazados'] == 0))
						$maquinistas[$asignacion]['incremento'] = "SI";
					else
						$maquinistas[$asignacion]['incremento'] = "NO";
					
					$maquinistas[$asignacion]['observacion'] = $row['observacion'];
				}
				return $maquinistas;
			}
		}
	}
	
	//reporte de limpiezas 
	function reporte_limpiezas($despacho_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tda.asignacion_detalle_id as codigo
					, top.cup_num as op
					, tlimp.cantidad as cantidad
					, CONCAT(per.apellidos, ' ', per.nombres ) as maquinista
					, fam.nombre_familia as producto
					, est.nombre_estilo tipo
					, col.descripcion as color
					, tda.observaciones As observaciones
					, CONCAT(per1.apellidos, ' ', per1.nombres ) as limpiadora
					, tlimp.fecha_finalizacion as fecha_fin
					, tda.cantidad_asignada as total
			FROM	`tdetalle_asignacion` tda
					, `despacho_detalle` despdet
					, `despacho` desp
					, `tordenesproduccion` top
					, `tdetalleordenesproduccion` tdop
					, `tlimpieza` tlimp
					,`personal` per
					, `familia` fam
					, `estilo` est
					, `tpropiedades` prop
					, `tcolores` col
					,`personal` per1
			WHERE	despdet.asignacion_id = tda.asignacion_detalle_id
					and desp.despacho_id=despdet.despacho_id
					and tdop.detalle_id=tda.detalle_id
					and tlimp.asignacion_detalle_id=tda.asignacion_detalle_id 
					and top.orden_id=tdop.orden_id
					and desp.despacho_id=".$despacho_id." 
					and tdop.familia_id=fam.familia_id
					and fam.estilo_id= est.estilo_id and per.personal_id=tda.personal_id
					and prop.prop_id=tdop.propiedad_id and prop.color_id=col.color_id
					and per1.personal_id=tlimp.limpiador_id";
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_limpieza- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["codigo"] = $row['codigo'];
					$respuesta[$contador]["op"] = $row['op'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["maquinista"] = $row['maquinista'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["observaciones"] = $row['observaciones'];
					$respuesta[$contador]["limpiadora"] = $row['limpiadora'];
					$respuesta[$contador]["fecha_fin"] = $row['fecha_fin'];
					$respuesta[$contador]["total"] = $row['total'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}


	//reporte de arreglos
	function reporte_arreglos($despacho_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
					tda.asignacion_detalle_id as codigo
					, top.cup_num as op
					, CONCAT(per.apellidos, ' ', per.nombres ) as maquinista
					, fam.nombre_familia as producto
					, est.nombre_estilo tipo
					, col.descripcion as color
					, rech.cantidad as rechazos
					, CONCAT(per1.apellidos, ' ', per1.nombres ) as arreglador
					, tcf.nombre_fallo as clasificacion
					, CONCAT(per2.apellidos, ' ', per2.nombres ) as culpable
					, rech.detalle_fallo as detalle
					, tda.observaciones As observaciones
			FROM
					  `tdetalle_asignacion` tda
					, `despacho_detalle` despdet
					, `despacho` desp
					, `tordenesproduccion` top
					, `tdetalleordenesproduccion` tdop
					, `trechazo` rech
					, `personal` per
					, `familia` fam
					, `estilo` est
					, `tpropiedades` prop
					, `tcolores` col
					, `personal` per1
					, `tarreglos` tarr
					, `personal` per2
					, `tclasificacion_fallos` tcf
			WHERE
						despdet.asignacion_id = tda.asignacion_detalle_id
					and desp.despacho_id = despdet.despacho_id
					and tdop.detalle_id = tda.detalle_id
					and rech.asignacion_id = tda.asignacion_detalle_id
					and top.orden_id = tdop.orden_id
					and tdop.familia_id = fam.familia_id
					and fam.estilo_id = est.estilo_id
					and per.personal_id = tda.personal_id
					and prop.prop_id = tdop.propiedad_id
					and prop.color_id = col.color_id
					and rech.rechazo_id = tarr.rechazo_id
					and per1.personal_id = tarr.responsable_arreglo
					and tcf.clasificacion_fallo_id = rech.clasificacion_fallo_id
					and per2.personal_id = rech.responsable_id
					and desp.despacho_id = ".$despacho_id;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_arreglos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["codigo"] = $row['codigo'];
					$respuesta[$contador]["op"] = $row['op'];
					$respuesta[$contador]["maquinista"] = $row['maquinista'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["rechazos"] = $row['rechazos'];
					$respuesta[$contador]["arreglador"] = $row['arreglador'];
					$respuesta[$contador]["clasificacion"] = $row['clasificacion'];
					$respuesta[$contador]["culpable"] = $row['culpable'];
					$respuesta[$contador]["detalle"] = $row['detalle'];
					$respuesta[$contador]["observaciones"] = $row['observaciones'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}


	//buscar despachos para enviar a administracion
	function despachos_administracion(){
		$con = new DBmanejador;
		if($con->conectar() == true){
				$consulta = "
				SELECT d.despacho_id, d.nombre_despacho, d.fecha_despacho, d.migrar_link
				FROM `despacho` d
				WHERE
				d.migrar_administracion = 1
				";
			//echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -ultimos despachos- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["despacho_id"] = $row['despacho_id'];
					$respuesta[$contador]["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta[$contador]["fecha_despacho"] = $row['fecha_despacho'];
					$respuesta[$contador]["migrar_link"] = $row['migrar_link'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}


	//actualizar los despachos que ya se imprimieron
	function actualizar_despacho_impreso($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	`despacho`
			SET		migrar_link = 0
			WHERE	despacho_id = ".$deid;
			
			$resultado = mysql_query($consulta) or die ('La consulta -despacho administracion- fall&oacute;: ' . mysql_error());
			
		}
	}


	//modificar descripcion de despacho
	function modificar_descripcion_despacho($deid, $nombre, $fecha, $creacion, $usuario){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	despacho de
			SET		de.nombre_despacho = '".$nombre."'
					, de.fecha_despacho = '".$fecha."'
					, de.fecha_creacion = '".$creacion."'
					, de.usuario_creador = ".$usuario."
			WHERE	de.despacho_id = ".$deid;
			//echo "<br>con: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta - modificar despacho- fall&oacute;: ' . mysql_error());
		}
	}


   //buscar despacho
	function buscar_despacho_sueldos($nombre_despacho,$fecha)
	{ 
	   //echo "consulta";
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	de.despacho_id, de.nombre_despacho, de.fecha_despacho, de.migrar_administracion
			FROM	`despacho` de
			WHERE	de.nombre_despacho LIKE '%".$nombre_despacho."%' and de.fecha_despacho='".$fecha."' 
			ORDER BY de.fecha_despacho DESC
			";
			//echo $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -buscar despacho sueldos - fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["despacho_id"] = $row['despacho_id'];
					$respuesta[$contador]["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta[$contador]["fecha_despacho"] = $row['fecha_despacho'];
					$respuesta[$contador]["migrar_administracion"] = $row['migrar_administracion'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
    function habilitar_sueldos($deid)
	{ 
	
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
	    	 $consulta_actualizar = "
					UPDATE	despacho
					SET		migrar_administracion = 1, migrar_link = 1
					WHERE	despacho_id = ".$deid."
					";
					//echo "<br>Consulta: ". $consulta_actualizar;
					mysql_query($consulta_actualizar) or die ('La consulta -habilitar sueldos- fall&oacute;: ' . mysql_error());					
					return true;
	   }
	   return false;
	}
	 function deshabilitar_sueldos($deid)
	{ 
	
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
	    	 $consulta_actualizar = "
					UPDATE	despacho
					SET		migrar_administracion = 0, migrar_link =0
					WHERE	despacho_id = ".$deid."
					";
					//echo "<br>Consulta: ". $consulta_actualizar;
					mysql_query($consulta_actualizar) or die ('La consulta -habilitar sueldos- fall&oacute;: ' . mysql_error());					
					return true;
	   }
	   return false;
	}
	function estado_despacho($deid)
	{ 
	
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
	    	 $consulta_actualizar = "
					SELECT	despacho_id,migrar_administracion,migrar_link
					FROM  despacho
					WHERE despacho_id = ".$deid."
					";
					//echo "<br>Consulta: ". $consulta_actualizar;
			$resultado=mysql_query($consulta_actualizar) or die ('La consulta -habilitar sueldos- fall&oacute;: ' . mysql_error());					
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta["despacho_id"] = $row['despacho_id'];
					$respuesta["migrar_administracion"] = $row['migrar_administracion'];
					$respuesta["migrar_link"] = $row['migrar_link'];
				}
				return $respuesta;
			}
	   }
	   return false;
	}
	function buscar_link($deid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	de.migrar_administracion, de.migrar_link
			FROM	`despacho` de
			WHERE	de.despacho_id ='".$deid."'
		
			";
		//	echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta buscar link : ' . mysql_error());
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
					$respuesta['migrar_administracion'] = $row['migrar_administracion'];
					$respuesta['migrar_link'] = $row['migrar_link'];
					return $respuesta;
					
				}
				else {
					return false;
				}
		}
	}
	
	}
	function contar($detalle_id)
	{
		
	$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT COUNT( tda.detalle_id ) AS contar
			FROM `tdetalle_asignacion` tda
			WHERE tda.detalle_id = '".$detalle_id."'
			GROUP BY detalle_id

			";
			//echo "<br>Consulta: ". $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta buscar link : ' . mysql_error());
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
				  
				   if ($row['contar'] == 1)
				   	return 1;
				   else
				   	return 0;
					
				}
				else {
					return false;
				}
		}
	}
	
	
	
	
	}
	
	
	
	
	
	
	
	
	
}
?>