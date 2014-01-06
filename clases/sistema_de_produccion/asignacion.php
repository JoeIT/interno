<?php
require_once('../../clases/includes/dbmanejador.php');

class Asignacion{
	function Asignacion(){
	}

	//averiguar si esta en despacho
	function condicion_despacho($num_asignacion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	nombre_despacho, fecha_despacho
			FROM	despacho_detalle dd, despacho d
			WHERE		d.despacho_id = dd.despacho_id
					AND dd.asignacion_id = ".$num_asignacion;
			
			$resultado = mysql_query($consulta) or die ('La consulta -buscar_asignacion_modificar- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)) {
					$respuesta["nombre_despacho"] = $row['nombre_despacho'];
					$respuesta["fecha_despacho"] = $row['fecha_despacho'];
					
					return $respuesta;
				}
			}
		}
	}

	
	function modificar_cantidad_asignada($num_asignacion, $cantidad_original, $cantidad_nueva) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			//colocando todo a su estado inicial
			$consulta = "
			SELECT	detalle_id
			FROM 	tdetalle_asignacion
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta cantidad- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado)) {
				$resp['detalle_id'] = $row['detalle_id'];
			}
			//adicionando la cantidad original
			$consulta = "
			UPDATE	resultados_asignacion
			SET		  asignados = asignados - ".$cantidad_original."
					, pendientes = pendientes + ".$cantidad_original."
			WHERE	detalle_id = ".$resp['detalle_id'];
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta cantidad- fall&oacute;: ' . mysql_error());
			
			//actualizamos la nueva cantidad a la asignacion
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		cantidad_asignada = ".$cantidad_nueva."
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta delete- fall&oacute;: ' . mysql_error());

			//modificamos resultados asignacion
			$consulta = "
			UPDATE	resultados_asignacion
			SET		  asignados = asignados + ".$cantidad_nueva."
					, pendientes = pendientes - ".$cantidad_nueva."
			WHERE	detalle_id = ".$resp['detalle_id'];
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta res asig- fall&oacute;: ' . mysql_error());
		}
	}

	function cantidad_asignada($num_asignacion) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			//averiguar la cantidad
			$consulta = "
			SELECT	cantidad_asignada, detalle_id
			FROM 	tdetalle_asignacion
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta cantidad- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado)) {
				$resp['cantidad'] = $row['cantidad_asignada'];
				$resp['detalle_id'] = $row['detalle_id'];
			}

			//resumen dela asignacion
			$consulta = "
			SELECT	pendientes, entregados
			FROM 	resultados_asignacion
			WHERE	detalle_id = ".$resp['detalle_id'];
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta cantidad- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado)) {
				$resp['pendientes'] = $row['pendientes'];
				$resp['entregados'] = $row['entregados'];
			}
		}
		return $resp;
	}
	
	function anular_hoja_ruta($num_asignacion) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			//averiguar la cantidad
			$consulta = "
			SELECT	cantidad_asignada, detalle_id
			FROM 	tdetalle_asignacion
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta cantidad- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado)) {
				$cantidad = $row['cantidad_asignada'];
				$detalle_id = $row['detalle_id'];
			}
			
			//modificamos resultados asignacion
			$consulta = "
			UPDATE	resultados_asignacion
			SET		  asignados = asignados - ".$cantidad."
					, pendientes = pendientes + ".$cantidad."
			WHERE	detalle_id = ".$detalle_id;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta res asig- fall&oacute;: ' . mysql_error());
			
			//eliminamos la hoja
			$consulta = "
			DELETE
			FROM 	tdetalle_asignacion
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -anular_hoja_ruta delete- fall&oacute;: ' . mysql_error());
		}
	}

	function modificar_maquinista($num_asignacion, $maquinista_id) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		personal_id = ".$maquinista_id."
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -modificar_maquinista- fall&oacute;: ' . mysql_error());
		}
	}
	
	function modificar_reprogramacion($num_asignacion) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		fecha_reprogramacion = null
					, responsable_reprogramacion = null
					, motivo_reprogramacion = null
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -modificar_reprogramacion- fall&oacute;: ' . mysql_error());
		}
	}
	
	function modificar_dado_baja($num_asignacion) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		fecha_entrega = null
					, usuario_entrega = 0
			WHERE	asignacion_detalle_id = ".$num_asignacion;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -modificar_dado_baja- fall&oacute;: ' . mysql_error());
			
			$consulta_detalle_id = "
			SELECT	detalle_id, cantidad_asignada
			FROM	tdetalle_asignacion
			WHERE	asignacion_detalle_id = ".$num_asignacion;

            $resultado_detalle_id = mysql_query($consulta_detalle_id) or die ('La consulta -$consulta_detalle_id- fall&oacute;: ' . mysql_error());
			
			if ($row = mysql_fetch_array($resultado_detalle_id)) {
					$respuesta["detalle_id"] = $row['detalle_id'];
					$respuesta["cantidad_asignada"] = $row['cantidad_asignada'];
			}			
			
			$consulta_resultados_asignacion = "
			UPDATE	resultados_asignacion
			SET		entregados = entregados - ".$respuesta["cantidad_asignada"]."
			WHERE	detalle_id = ".$respuesta["detalle_id"];
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta_resultados_asignacion) or die ('La consulta -consulta_resultados_asignacion- fall&oacute;: ' . mysql_error());			
		}
	}
	
	//consulta la asignacion para modificarlo
	function buscar_asignacion_modificar($num_asignacion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tda.asignacion_detalle_id
					, CONCAT(p.apellidos, ' ', p.nombres) AS maquinista
					, tda.usuario_entrega
					, tda.responsable_reprogramacion
					, tda.cerrada
			FROM	`tdetalle_asignacion` tda, `personal` p
			WHERE	tda.personal_id = p.personal_id
					AND tda.asignacion_detalle_id = ".$num_asignacion;
			
			$resultado = mysql_query($consulta) or die ('La consulta -buscar_asignacion_modificar- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)) {
					$respuesta["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta["maquinista"] = $row['maquinista'];
					$respuesta["usuario_entrega"] = $row['usuario_entrega'];
					$respuesta["responsable_reprogramacion"] = $row['responsable_reprogramacion'];
					$respuesta["cerrada"] = $row['cerrada'];
					
					return $respuesta;
				} else {
					return false;
				}
			}
		}
	}

//samuel en la line 260 se esta aumentando o.orden_id desc,
	//consulta la lista de ordenes anual
	function consulta_lista_ordenes_anual($año){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	o.orden_id, o.num_orden, c.nombre AS cliente
					, o.fecha, o.fechaentrega, o.fecharepro, o.observacion
					, SUM(tdop.cantidad) AS cantidad
					, SUM(ra.asignados) AS asignados, SUM(ra.entregados) AS entregados, SUM(ra.pendientes) AS pendientes
			FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop
					, `resultados_asignacion` ra
			WHERE	c.cliente_id = o.cliente_id
					AND o.orden_id = tdop.orden_id
					AND tdop.detalle_id = ra.detalle_id
			GROUP BY o.orden_id
			ORDER BY o.orden_id desc, o.fecha DESC, o.cup_num DESC
		    LIMIT 0 ,30
			";
				//	LIMIT 0 ,30
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
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					
					$respuesta[$contador]["asignados"] = $row['asignados'];
					$respuesta[$contador]["entregados"] = $row['entregados'];
					$respuesta[$contador]["pendientes"] = $row['pendientes'];
					
					$respuesta[$contador]["impresion"] = 1;

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	function consulta_lista_pendiente($año){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	o.orden_id, o.num_orden, c.nombre AS cliente
					, o.fecha, o.fechaentrega, o.fecharepro, o.observacion
					, SUM(tdop.cantidad) AS cantidad
					, SUM(ra.asignados) AS asignados, SUM(ra.entregados) AS entregados, SUM(ra.pendientes) AS pendientes
			FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop
					, `resultados_asignacion` ra
			WHERE	c.cliente_id = o.cliente_id
					AND o.orden_id = tdop.orden_id
					AND tdop.detalle_id = ra.detalle_id
			GROUP BY o.orden_id
			ORDER BY o.fecha DESC, o.cup_num DESC
		
			";
				//	LIMIT 0 ,30
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
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					
					$respuesta[$contador]["asignados"] = $row['asignados'];
					$respuesta[$contador]["entregados"] = $row['entregados'];
					$respuesta[$contador]["pendientes"] = $row['pendientes'];
					
					$respuesta[$contador]["impresion"] = 1;

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	//
	//resumen numerico de las asignaciones
	function resumen_numerico_asignaciones($orden_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	SUM(parciales.cantidad) AS cantidad
					, SUM(parciales.cant_asignada) AS cant_asignada
					, SUM(parciales.cant_entregada) AS cant_entregada
					, SUM(parciales.cant_pendiente) AS cant_pendiente
			FROM	(
					SELECT	tdop.cantidad AS cantidad
							, SUM(IF(tda.cantidad_asignada != 0, tda.cantidad_asignada, 0)) AS cant_asignada
							, SUM(IF(tda.usuario_entrega != 0, tda.cantidad_asignada, 0)) AS cant_entregada
							, tdop.cantidad - (IF(SUM(tda.cantidad_asignada) != 0, SUM(tda.cantidad_asignada), 0)) AS cant_pendiente
					FROM	`tordenesproduccion` top
							, `tdetalleordenesproduccion` tdop LEFT JOIN `tdetalle_asignacion` tda ON tdop.detalle_id = tda.detalle_id
					WHERE	top.orden_id = tdop.orden_id
							AND top.orden_id = ".$orden_id."
							AND tdop.estado != 0
					GROUP BY tdop.detalle_id
					) AS parciales
			";
			
			$resultado = mysql_query($consulta) or die ('La consulta -resumen_numerico_asignaciones- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)) {
					$respuesta["cantidad"] = $row['cantidad'];
					$respuesta["cant_asignada"] = $row['cant_asignada'];
					$respuesta["cant_entregada"] = $row['cant_entregada'];
					$respuesta["cant_pendiente"] = $row['cant_pendiente'];
					return $respuesta;
				} else {
					return false;
				}
			}
		}
	}
	
	//Samuel se esta cambiado en  la line 407 y 422 (ORDER BY o.cup_num desc por ORDER BY o.orden_id desc)
	function consultar_busqueda($cadena, $opcion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			if($opcion == "num_orden"){
				$consulta = "
				SELECT	o.orden_id, o.num_orden, c.nombre AS cliente, o.fecha, o.fechaentrega, o.fecharepro
						, o.observacion, SUM(ra.asignados) AS asignados, SUM(ra.entregados) AS entregados, SUM(ra.pendientes) AS pendientes
						, SUM(tdop.cantidad) AS cantidad
				FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop
						, `resultados_asignacion` ra
				WHERE	c.cliente_id = o.cliente_id
						AND o.orden_id = tdop.orden_id
						AND tdop.detalle_id = ra.detalle_id
						AND o.num_orden LIKE '%".$cadena."%'
				GROUP BY o.orden_id
				ORDER BY o.orden_id desc
				LIMIT 0 , 30
				";
			} else {
				$consulta = "
				SELECT	o.orden_id, o.num_orden, c.nombre AS cliente, o.fecha, o.fechaentrega, o.fecharepro
						, o.observacion, SUM(ra.asigados) AS asignados, SUM(ra.entregados) AS entregados, SUM(ra.pendientes) AS pendientes
						, SUM(tdop.cantidad) AS cantidad
				FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop
						, `resultados_asignacion` ra
				WHERE	c.cliente_id = o.cliente_id
						AND o.orden_id = tdop.orden_id
						AND tdop.detalle_id = ra.detalle_id
						AND c.nombre LIKE '%".$cadena."%'
				GROUP BY o.orden_id
				ORDER BY o.orden_id desc
				LIMIT 0 , 30
				";
			}
			
			//echo $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -consultar busqueda- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
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
					$respuesta[$contador]["cantidad"] = $row['cantidad'];

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	function cambiar_entrega($did,$descripcion)
	{
		$con = new DBmanejador;
		$fechas=date("Y-m-d H:i:s");
		if($con->conectar()==true)
		{
			$consulta = "update `tdetalle_asignacion` set entrega_almacen=1, fecha_entrega_almacen='".$fechas. "' where asignacion_detalle_id=".$did;
			
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener actualizacion: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else 
			{
				$consulta = "	SELECT * FROM tentrega_almacen
								WHERE asignacion_id='".$did."' 
								";
			    $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener actualizacion: ' . mysql_error());
				$row = mysql_fetch_array($resultado);
				if ($row[0]=="")
				{
					$consulta = "	INSERT INTO tentrega_almacen (asignacion_id,descripcion)
									VALUES ('".$did."','".$descripcion."') 
									";
					$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener actualizacion: ' . mysql_error());
		
					if (!$resultado)
						return false;
					else 
					{
							return true;
					}
				}
				else 
				{
					$consulta = "	UPDATE tentrega_almacen 
									SET descripcion='".$descripcion."' 
									WHERE asignacion_id='".$did."'
									";
					$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener actualizacion: ' . mysql_error());
		
					if (!$resultado)
						return false;
					else 
					{
							return true;
					}
				}		
				
			}
		}
		
	}
	
	function ver_entrega($did)
	{
		$con = new DBmanejador;
		
		if($con->conectar()==true)
		{
			$consulta = " SELECT entrega_almacen FROM `tdetalle_asignacion` WHERE asignacion_detalle_id =".$did.";";

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else 
			{
					$row = mysql_fetch_array($resultado);
					return $row[0];		
			}
		}
		
	}
	
	function existe_asignacion($did)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta = " SELECT * FROM `tdetalle_asignacion` WHERE asignacion_detalle_id =".$did.";";

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());
			$row = mysql_fetch_array($resultado);

			if ($row=="")
				return false;
			else 
				return true;	
				 
			
		}
	}
	
	function sacar_materiales_corte($did)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta = "
			 
			 SELECT DISTINCT d.total_piezas_material AS cantidad, CONCAT( m.nombre, ' - ', m.descripcion ) AS mat
FROM `tdetalle_asignacion` tda, tdespiece d, tmateriales m, tcomponentes c, tpiezas pie, tdetalleordenesproduccion tdo, tordenesproduccion top
WHERE tda.asignacion_detalle_id=".$did."
AND tdo.detalle_id = tda.detalle_id
AND tda.detalle_id = d.detalle_id
AND tda.detalle_id = c.detalle_id
AND d.material_id = m.material_id
AND pie.material_id = m.material_id
AND c.pieza_id = pie.pieza_id
AND tdo.detalle_id = tda.detalle_id
AND top.orden_id = tdo.orden_id
AND m.tipo_material_id IS NOT NULL 
ORDER BY mat
LIMIT 0 , 30";

			//echo "<br>consulta: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while($row = mysql_fetch_array($resultado))
				{
					$respuesta[$contador]["Orden"]= $row['orden'];
					$respuesta[$contador]["Asignacion"]= $row['asignacion'];
					$respuesta[$contador]["Personal"]= $row['completo'];	 
					$respuesta[$contador]["Cantidad"]=$row['cant'];
					$respuesta[$contador]["Cant_mat"]= $row['cantidad'];
					$respuesta[$contador]["Material"]= $row['mat'];
					$respuesta[$contador]["Producto"]= $row['producto'];
				
					
					
					$contador ++;					
				}
				
				return $respuesta;
			}
		}
	}
	
	function concatenar_materiales($did)
	{
	
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta = "SELECT	
       					 	 (d.total_piezas_material / tdo.cantidad) AS cantidad_unidad
        					 , CONCAT(m.nombre,' - ', m.descripcion) AS mat
						FROM `tdetalle_asignacion` tda, tdespiece d,
     						  tmateriales m,
     						 tdetalleordenesproduccion tdo
						WHERE tda.asignacion_detalle_id=".$did."
							 AND tdo.detalle_id = tda.detalle_id
							 AND tda.detalle_id = d.detalle_id
							 AND d.material_id = m.material_id
							 AND tdo.detalle_id = tda.detalle_id
							 AND m.tipo_material_id is null
							 order by mat";

			//echo "<br>consulta: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while($row = mysql_fetch_array($resultado))
				{
					$cant_nuevo=$row['cantidad_unidad'];
					$cant_cambiado=ceil($row['cantidad_unidad']);
					
				 	if ($cant_nuevo==$cant_cambiado)
					{
						$cant_nuevo=ceil($cant_nuevo);
						
					}
					$respuesta=$respuesta."/".$cant_nuevo."-". $row['mat']."/";
					$contador ++;					
				}
			}
			
			return $respuesta;
			
		}
	}
	
	function sacar_materiales($did)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta = "SELECT	
       					 	 (d.total_piezas_material / tdo.cantidad) AS cantidad_unidad
        					 , CONCAT(m.nombre,' - ', m.descripcion) AS mat
						FROM `tdetalle_asignacion` tda, tdespiece d,
     						  tmateriales m,
     						 tdetalleordenesproduccion tdo
						WHERE tda.asignacion_detalle_id=".$did."
							 AND tdo.detalle_id = tda.detalle_id
							 AND tda.detalle_id = d.detalle_id
							 AND d.material_id = m.material_id
							 AND tdo.detalle_id = tda.detalle_id
							 AND m.tipo_material_id is null
							 order by mat";

			//echo "<br>consulta: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while($row = mysql_fetch_array($resultado))
				{
				
					$respuesta[$contador]["Cant_mat"]= $row['cantidad_unidad'];
					$respuesta[$contador]["Material"]= $row['mat'];
					
					$contador ++;					
				}
				
				$consulta = "SELECT	top.num_orden AS orden,
      						 tda.asignacion_detalle_id AS asignacion,tda.cantidad_asignada as cantidad_asignada
							, CONCAT(p.apellidos,' ', p.nombres) AS completo,		
					      	  CONCAT(f.nombre_familia,' ',te.nombre_estilo,' ',tc.descripcion,' con clip  ',tcl.descripcion) AS producto

							FROM `tdetalle_asignacion` tda, `personal` p,
								  tpropiedades tp, familia f,
								  tdetalleordenesproduccion tdo,
								  estilo te, tcolores tc, tclips tcl, tordenesproduccion top
							
							WHERE tda.asignacion_detalle_id=".$did."
							 AND tdo.detalle_id = tda.detalle_id AND  tda.personal_id = p.personal_id
							 AND tdo.detalle_id = tda.detalle_id
							 AND tp.prop_id=tdo.propiedad_id
							 AND f.familia_id=tdo.familia_id AND f.estilo_id=te.estilo_id AND tp.color_id=tc.color_id
							 AND tp.clip_id=tcl.clip_id AND top.orden_id=tdo.orden_id";
				
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

				if (!$resultado)
					return false;
				else {
					$contador = 0;
					
					while($row = mysql_fetch_array($resultado))
					{
						$respuesta[$contador]["Orden"]= $row['orden'];
						$respuesta[$contador]["Asignacion"]= $row['asignacion'];
						$respuesta[$contador]["Personal"]= $row['completo'];	 
						$respuesta[$contador]["Cantidad"]=$row['cantidad_asignada'];
						$respuesta[$contador]["Producto"]= $row['producto'];
						$contador ++;					
					}
				 }	
				return $respuesta;
			}
		}
	}

	
	
	function resumen_inicial($op_id){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			SELECT    tdop.detalle_id
					, tdop.cantidad
					, f.nombre_familia
					, e.nombre_estilo
					, tco.descripcion AS color
					, tcli.descripcion AS clip
					, ra.asignados
					, ra.entregados
					, ra.pendientes
					, tcu.descripcion AS cuero
					,tdop.pedido,tdop.bloqueado
			FROM	  `tordenesproduccion` top
					, `tdetalleordenesproduccion` tdop
					, `familia` f
					, `resultados_asignacion` ra
					, `tpropiedades` tp
					, `estilo` e
					, `tcueros` tcu
					, `tcolores` tco
					, `tclips` tcli
			WHERE	top.orden_id = tdop.orden_id AND
					tdop.familia_id = f.familia_id AND
					tdop.detalle_id = ra.detalle_id AND
					tdop.propiedad_id = tp.prop_id AND
					tp.color_id = tco.color_id AND
					tp.cuero_id = tcu.cuero_id AND
					tp.clip_id = tcli.clip_id AND
					f.estilo_id = e.estilo_id AND
					top.orden_id = ".$op_id."";
			//ORDER by f.nombre_familia, e.nombre_estilo";
//echo "<br>consulta ".$consulta;
            $resultado = mysql_query($consulta) or die('La consulta resumen inicial fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["detalle_id"] = $row[0];
  					$respuesta[$contador]["cantidad"] = $row[1];
  					$respuesta[$contador]["nombre_familia"] = $row[2];
					$respuesta[$contador]["nombre_estilo"] = $row[3];
  					$respuesta[$contador]["cuero"] = $row['cuero'];
  					$respuesta[$contador]["color"] = $row[4];
  					$respuesta[$contador]["clip"] = $row[5];
  					$respuesta[$contador]["asignados"] = $row[6];
					$respuesta[$contador]["entregados"] = $row[7];
					$respuesta[$contador]["pendientes"] = $row[8];
					$respuesta[$contador]["pedido"] = $row[10];
					$respuesta[$contador]["bloqueado"] = $row['bloqueado'];
					
					$contador = $contador + 1;
  				}
				return $respuesta;
			}
		}
	}
	
	function obtener_detalle_orden($op_id, $de_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = '
			SELECT  e.nombre_estilo, o.descripcion, c.descripcion, l.descripcion, s.descripcion,
					t.descripcion, m.nombre_familia, d.cantidad, d.unidad, d.pedido, d.observacion,
					d.lugargrabado, d.prioridad, d.grabado, d.tipoletra, d.detalle_id, ra.asignados, 
					ra.entregados, ra.pendientes
			FROM  	tdetalleordenesproduccion d, estilo e, tcueros o, tcolores c, tclips l, tchapas s,
				 	tetiquetas t, tpropiedades p, familia m, `resultados_asignacion` ra
			WHERE	d.propiedad_id=p.prop_id AND
					m.estilo_id=e.estilo_id AND
					p.cuero_id=o.cuero_id AND
					p.color_id=c.color_id AND
					p.clip_id=l.clip_id AND
					p.sello_id=s.chapa_id AND
					p.etiqueta_id=t.etiqueta_id AND
					d.familia_id=m.familia_id AND
					d.estado=1 AND
					ra.detalle_id = d.detalle_id AND
					d.orden_id = '.$op_id.' AND
					d.detalle_id = '.$de_id;

			//echo "<br>consulta ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta obtener detalle orden fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["Cantidad"] = $row[7];
					$respuesta["Unidad"] = $row[8];	 
					$respuesta["Modelo"] = $row[6];
					$respuesta["Estilo"] = $row[0];
					$respuesta["Origen Cuero"] = $row[1];
					$respuesta["Color"] = $row[2];
					$respuesta["Clip"] = $row[3];
					$respuesta["Sello/Herraje"] = $row[4];
					$respuesta["Etiqueta"] = $row[5];
					$fuente = "";
					
					if(trim($row[14] != ""))
						$fuente="(".$row[14].")";
					$respuesta["Observaciones"] = $row[10].$row[13].$fuente;

					$respuesta["lugar"] = $row['lugargrabado'];
					$respuesta["detalle_id"] = $row[15];
					
					$respuesta["Asignados"] = $row['asignados'];
					$respuesta["Entregados"] = $row['entregados'];
					$respuesta["Pendientes"] = $row['pendientes'];
				}
				return $respuesta;
			}
		}
	}
	
	
	function busqueda_personal($nombre, $puesto){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT		p.personal_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase
			FROM 		personal p, personal_puesto_trabajo ppt
			WHERE			p.personal_id = ppt.personal_id
						AND ppt.puesto_trabajo_id = ".$puesto."
						AND CONCAT(p.apellidos,' ', p.nombres) LIKE '".$nombre."%'
						AND p.estado != 0
			ORDER BY	CONCAT(p.apellidos,' ', p.nombres)
			LIMIT 0,20
			";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda personal fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['personal_id'] = $row['personal_id'];
					$respuesta[$contador]['completo'] = $row['completo'];
					$respuesta[$contador]['clase'] = $row['clase'];
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}


	function asignar_detalle_resultados($detalle_id, $personal_id, $cantidad_asignada, $cantidad_muestra, $fecha_asignacion, $responsable_asignacion, $fecha_inicio, $fecha_medio, $fecha_finalizacion, $observaciones){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO	tdetalle_asignacion (detalle_id, personal_id, cantidad_asignada, cantidad_muestra,
						fecha_asignacion, responsable_asignacion, fecha_inicio, fecha_medio, fecha_finalizacion,
						observaciones)
			VALUES		($detalle_id, $personal_id, $cantidad_asignada, $cantidad_muestra, '".$fecha_asignacion."',
						$responsable_asignacion, '".$fecha_inicio."', '".$fecha_medio."', '".$fecha_finalizacion."',
						'".$observaciones."')
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta insertar asignar detalle resultadas fall&oacute;: ' . mysql_error());
			
			$consulta = "
			UPDATE	resultados_asignacion
			SET		asignados = asignados + ".$cantidad_asignada.", pendientes = pendientes - ".$cantidad_asignada."
			WHERE	detalle_id = ".$detalle_id;

			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta update asignar detalle resultados fall&oacute;: ' . mysql_error());
		}
	}
	
	function ver_detalle_resultados($did){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT		tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo,
						tda.cantidad_asignada, tda.fecha_inicio, tda.fecha_finalizacion, 
						tda.impresion_num, tda.impresion_max, tda.usuario_entrega_corte, tda.usuario_entrega
			FROM		`tdetalle_asignacion` tda, `personal` p
			WHERE		tda.personal_id = p.personal_id AND
						tda.detalle_id = ".$did." 
			ORDER BY	tda.asignacion_detalle_id DESC";

			$resultado = mysql_query($consulta) or die ('La consulta ver detalle resultados fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta[$contador]["completo"] = $row['completo'];	 
					$respuesta[$contador]["catidad_asignada"] = $row['cantidad_asignada'];
					$respuesta[$contador]["fecha_inicio"] = $row['fecha_inicio'];
					$respuesta[$contador]["fecha_finalizacion"]= $row['fecha_finalizacion'];
					$respuesta[$contador]["impresion_num"] = $row['impresion_num'];
					$respuesta[$contador]["impresion_max"] = $row['impresion_max'];
					$respuesta[$contador]["usuario_entrega_corte"] = $row['usuario_entrega_corte'];
					$respuesta[$contador]["usuario_entrega"] = $row['usuario_entrega'];
					$contador ++;					
				}
				return $respuesta;
			}
		}
	}


	function ver_modificar_detalle_resultados($daid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo,
					p.clase, tda.cantidad_asignada, tda.cantidad_muestra, tda.fecha_inicio, 
					tda.fecha_finalizacion, tda.observaciones, tda.personal_id
			FROM	`tdetalle_asignacion` tda, `personal` p
			WHERE	tda.personal_id = p.personal_id AND tda.asignacion_detalle_id = ".$daid;

			$resultado = mysql_query($consulta) or die ('La consulta -ver modificar detalle resultados- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta["completo"] = $row['completo'];	 
					$respuesta["clase"] = $row['clase'];
					$respuesta["cantidad_asignada"] = $row['cantidad_asignada'];
					$respuesta["cantidad_muestra"] = $row['cantidad_muestra'];
					$respuesta["fecha_inicio"] = $row['fecha_inicio'];
					$respuesta["fecha_finalizacion"] = $row['fecha_finalizacion'];
					$respuesta["observaciones"] = $row['observaciones'];
					$respuesta["personal_id"] = $row['personal_id'];
				}
				return $respuesta;
			}
		}
	}

	function sacar_cantidad($daid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tda.cantidad_asignada
			FROM	`tdetalle_asignacion` tda
			WHERE	tda.asignacion_detalle_id = ".$daid;

			//echo "<br>La consulta verificar personal: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -sacar cantidad- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["cantidad_asignada"] = $row['cantidad_asignada'];
				}
				return $respuesta;
			}
		}
	}

	function sacar_pendientes($did){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	pendientes 
			FROM	`resultados_asignacion`
			WHERE	detalle_id = ".$did;

			//echo "<br>La consulta verificar personal: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -sacar pendientes- fall&oacute; en: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["asignados"] = $row['pendientes'];
				}
				return $respuesta;
			}
		}
	}
	
	
	//reestablecer valores
	function reestablecer_asignar_detalle_resultados($detalle_id, $cantidad_asignada){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			UPDATE	resultados_asignacion
			SET		asignados = asignados - ".$cantidad_asignada.", pendientes = pendientes + ".$cantidad_asignada."
			WHERE	detalle_id = ".$detalle_id;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -reestablecer asignar detalle resultados- fall&oacute;: ' . mysql_error());
		}
	}
	
	//eliminamos de tdetalle asignacion
	function eliminar_asignar_detalle($daid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE
			FROM	`tdetalle_asignacion`
			WHERE	asignacion_detalle_id = ".$daid;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -eliminar asignar detalle- fall&oacute;: ' . mysql_error());
		}
	}
	
	function modificar_detalle_resultados($detalle_id, $daid, $personal_id, $cantidad_asignada, $cantidad_muestra, $fecha_asignacion, $responsable_asignacion, $fecha_inicio, $fecha_medio, $fecha_finalizacion, $observaciones){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		personal_id = ".$personal_id.",
					cantidad_asignada = ".$cantidad_asignada.",
					cantidad_muestra = ".$cantidad_muestra.",
					fecha_asignacion = '".$fecha_asignacion."',
					responsable_asignacion = ".$responsable_asignacion.",
					fecha_inicio = '".$fecha_inicio."',
					fecha_medio = '".$fecha_medio."',
					fecha_finalizacion = '".$fecha_finalizacion."',
					observaciones = '".$observaciones."' 
			WHERE	asignacion_detalle_id = ".$daid;
           
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta fall&oacute;: ' . mysql_error());
			
			$consulta = "
			UPDATE	resultados_asignacion
			SET		asignados = asignados + ".$cantidad_asignada.", pendientes = pendientes - ".$cantidad_asignada."
			WHERE	detalle_id = ".$detalle_id;

			//echo "<br>La consulta2: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar detalle resultados- fall&oacute;: ' . mysql_error());
		}
	}
	
	//modificar cada vez que se imprima
	function modificar_impresion($daid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			UPDATE	tdetalle_asignacion
			SET		impresion_num = 1
			WHERE	asignacion_detalle_id = ".$daid;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta fall&oacute;: ' . mysql_error());
		}
	}
	
	function reporte_modificar_detalle_resultados($daid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	 tda.asignacion_detalle_id
				   , CONCAT(p.apellidos,' ', p.nombres) AS completo
				   , tda.cantidad_asignada
				   , tda.cantidad_muestra
				   , tda.fecha_inicio
				   , tda.fecha_finalizacion
				   , f.nombre_familia
				   , e.nombre_estilo
				   , tcol.descripcion AS color
				   , tcli.descripcion AS clip
				   , tcu.descripcion AS cuero
				   , tdop.observacion
				   , top.num_orden
				   , tda.fecha_reprogramacion
				   , tdop.pedido
				   , tch.descripcion AS chapa
				   , tda.fecha_medio
				   , tdop.grabado
				   , tdop.lugargrabado
				   , tdop.tipoletra
				   , tda.observaciones AS obs_asig,tdop.obs_interior
				   
			FROM	`tordenesproduccion` top
				   , `tdetalle_asignacion` tda
				   , `personal` p
				   , `tdetalleordenesproduccion` tdop
				   , `familia` f
				   , `estilo` e
				   , `tpropiedades` tpr
				   , `tcolores` tcol
				   , `tclips` tcli
				   , `tcueros` tcu
				   , `tchapas` tch
			WHERE	top.orden_id = tdop.orden_id
					AND tda.personal_id = p.personal_id
					AND tda.detalle_id = tdop.detalle_id
					AND tdop.familia_id = f.familia_id
					AND f.estilo_id = e.estilo_id
					AND tdop.propiedad_id = tpr.prop_id
					AND tpr.color_id = tcol.color_id
					AND tpr.clip_id = tcli.clip_id
					AND tpr.cuero_id = tcu.cuero_id
					AND tpr.sello_id = tch.chapa_id
					AND tda.asignacion_detalle_id = ".$daid;

			$resultado = mysql_query($consulta) or die ('La consulta -reporte modificar detalle resutlados- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["asignacion_detalle_id"] = $row['asignacion_detalle_id'];
					$respuesta["completo"] = $row['completo'];	 
					$respuesta['obs_interior'] = $row['obs_interior'];
					$respuesta["cantidad_asignada"] = $row['cantidad_asignada'];
					$respuesta["cantidad_muestra"] = $row['cantidad_muestra'];
					$respuesta["fecha_inicio"] = $row['fecha_inicio'];
					$respuesta["fecha_finalizacion"] = $row['fecha_finalizacion'];
					$respuesta["nombre_familia"] = $row['nombre_familia'];
					$respuesta["nombre_estilo"] = $row['nombre_estilo'];
					$respuesta["color"] = $row['color'];
					$respuesta["clip"] = $row['clip'];
					$respuesta["cuero"] = $row['cuero'];
					
					if ($row['obs_asig'] != ""){
						$row['obs_asig'] = "|".$row['obs_asig']."|";
					}

					if ($row['tipoletra'] != "")
						$respuesta["observacion"] = "*".$row['observacion']."* ".$row['obs_asig']."*".$row['obs_interior']."*"."<br>".$row['grabado']." (".$row['tipoletra'].") ".$row['lugargrabado']."<br><br><center>".$row['chapa']."</center>";
					else
						$respuesta["observacion"] = "*".$row['observacion']."* ".$row['obs_asig']."*".$row['obs_interior']."*"."<br>".$row['grabado']." ".$row['lugargrabado']."<br><br><center>".$row['chapa']."</center>";
					
					$respuesta["num_orden"] = $row['num_orden'];
					$respuesta["fecha_reprogramacion"] = $row['fecha_reprogramacion'];
					$respuesta["pedido"] = $row['pedido'];
					$respuesta["fecha_medio"] = $row['fecha_medio'];
					
				}
				return $respuesta;
			}
		}
	}


	function busqueda_personal_reprogramacion($numero, $puesto){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo
					, p.clase, tda.fecha_inicio, tda.fecha_finalizacion, tda.fecha_reprogramacion
					, tda.motivo_reprogramacion, tda.usuario_entrega, tda.cerrada
			FROM	personal p, personal_puesto_trabajo ppt, `tdetalle_asignacion` tda
			WHERE	p.personal_id = ppt.personal_id AND
					ppt.puesto_trabajo_id = ".$puesto." AND
					p.personal_id = tda.personal_id AND
					tda.asignacion_detalle_id = ".$numero."
			ORDER BY tda.asignacion_detalle_id
			";
            $resultado = mysql_query($consulta) or die ('La consulta -busqueda personal reprogramacion- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta['asignacion_detalle_id'] = $row['asignacion_detalle_id'];
					$respuesta['completo'] = $row['completo'];
					$respuesta['clase'] = $row['clase'];
					$respuesta['fecha_inicio'] = $row['fecha_inicio'];
					$respuesta['fecha_finalizacion'] = $row['fecha_finalizacion'];
					$respuesta['fecha_reprogramacion'] = $row['fecha_reprogramacion'];
					$respuesta['motivo_reprogramacion'] = $row['motivo_reprogramacion'];
					$respuesta['usuario_entrega'] = $row['usuario_entrega'];
					$respuesta['cerrada'] = $row['cerrada'];
				}
				return $respuesta;
			}
		}
	}
	
	//modificar fecha de reprogramacion
	function modificar_fecha_reprogramacion($daid, $fec_reprog, $responsable_reprogramacion, $motivo){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	tdetalle_asignacion
			SET		fecha_reprogramacion = '".$fec_reprog."',
					responsable_reprogramacion = ".$responsable_reprogramacion.",
					motivo_reprogramacion = '".$motivo."'
			WHERE	asignacion_detalle_id = ".$daid;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -modificar fecha reprogramacion- fall&oacute;: ' . mysql_error());
		}
	}
	
	
	
	function reporte_maquinistas()
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT CONCAT(apellidos, ' ', nombres) as maquinista, tda.asignacion_detalle_id,
    			   tda.cantidad_asignada as cantidad,f.nombre_familia as producto,
			       e.nombre_estilo as tipo, ordenes.cup_num as orden,
				   tda.fecha_medio as fecha_medio, tda.fecha_finalizacion as fecha_fin,
				   SUM(recep.cantidad) as total_recepcionado,tda.fecha_entrega
			FROM  puesto_trabajo puesto, `personal_puesto_trabajo` ppt, personal p
				  LEFT JOIN `tdetalle_asignacion` tda ON p.personal_id=tda.personal_id
				  and tda.fecha_entrega is null
				  LEFT JOIN `tdetalleordenesproduccion` tdop ON tdop.detalle_id=tda.detalle_id
				  LEFT JOIN familia f ON f.familia_id=tdop.familia_id
				  LEFT JOIN estilo e ON f.estilo_id=e.estilo_id
				  LEFT JOIN `tordenesproduccion` ordenes ON ordenes.orden_id=tdop.orden_id
				  LEFT JOIN `trecepcion` as recep ON recep.asignacion_id=tda.asignacion_detalle_id
			WHERE p.estado!=0 and ppt.personal_id=p.personal_id
                  and ppt.puesto_trabajo_id=puesto.puesto_trabajo_id
                  and puesto.puesto_trabajo_id=3
			GROUP BY tda.asignacion_detalle_id,maquinista
			ORDER BY maquinista, tda.asignacion_detalle_id
			";
            $resultado = mysql_query($consulta) or die ('La consulta -reporte maquinistas fallo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['maquinista'] = $row['maquinista'];
					$respuesta[$contador]['asignacion_detalle_id'] = $row['asignacion_detalle_id'];
					$respuesta[$contador]['cantidad'] = $row['cantidad'];
					$respuesta[$contador]['producto'] = $row['producto'];
					$respuesta[$contador]['tipo'] = $row['tipo'];
					$respuesta[$contador]['orden'] = $row['orden'];
					$respuesta[$contador]['fecha_medio'] = $row['fecha_medio'];
					$respuesta[$contador]['fecha_fin'] = $row['fecha_fin'];
					$respuesta[$contador]['total_recepcionado'] = $row['total_recepcionado'];
					$respuesta[$contador]['fecha_entrega'] = $row['fecha_entrega'];
					$contador++;
				}
				return $respuesta;
			}
		}
	}
//	
	function medio_proceso($num_asignacion, $tipo, $fecha) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			switch ($tipo) {
				case 1:
					$consulta = "
					UPDATE	tdetalle_asignacion
					SET		muestra_inicial_fecha = '".$fecha."',
							medio_proceso_tipo  = medio_proceso_tipo + ".$tipo."
					WHERE	asignacion_detalle_id = ".$num_asignacion;
					break;
				case 2:
					$consulta = "
					UPDATE	tdetalle_asignacion
					SET		medio_proceso_fecha = '".$fecha."',
							medio_proceso_tipo  = medio_proceso_tipo + ".$tipo."
					WHERE	asignacion_detalle_id = ".$num_asignacion;
					break;
			}
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -medio proceso- fall&oacute;: ' . mysql_error());
		}
	}
//
 function reporte_asignacion($fecha_inicio,$fecha_fin)
 {

 	$con = new DBmanejador;
		if($con->conectar()==true){
			 $consulta = "
			SELECT top.cup_num AS cup
					, tda.asignacion_detalle_id AS id
					, tda.cantidad_asignada AS asignacion
					, f.nombre_familia AS descripcion
					, e.nombre_estilo AS estilo_es
					, tcol.descripcion AS color_es
					, tcli.descripcion AS clip_es
					, p.nombres , p.apellidos, p.clase
					, tda.fecha_finalizacion AS fecha_f
					, tda.fecha_reprogramacion AS fecha_r
					, tda.motivo_reprogramacion AS obs
			FROM
				`tordenesproduccion` top, `tdetalleordenesproduccion` tdop, `tdetalle_asignacion` tda
				, `familia` f, `estilo` e, `tpropiedades` tpr, `tcolores` tcol, `tclips` tcli
				, `personal` p

			WHERE
				top.orden_id = tdop.orden_id
				AND tdop.detalle_id = tda.detalle_id
				AND tdop.familia_id = f.familia_id
				AND f.estilo_id = e.estilo_id
				AND tdop.propiedad_id = tpr.prop_id
				AND tpr.color_id = tcol.color_id
				AND tpr.clip_id = tcli.clip_id
				AND tda.personal_id = p.personal_id

				AND tda.fecha_asignacion >= '".$fecha_inicio."' AND tda.fecha_asignacion <= '".$fecha_fin."'";
//echo $consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["cup"]= $row[0];
					$respuesta[$contador]["id"]= $row[1];	 
					$respuesta[$contador]["asignacion"]= $row[2];
					$respuesta[$contador]["descripcion"]= $row[3];
					$respuesta[$contador]["estilo_es"]= $row[4];
					$respuesta[$contador]["color_es"]= $row[5];
					$respuesta[$contador]["clip_es"]= $row[6];
					$respuesta[$contador]["nombres"]= $row[7];
					$respuesta[$contador]["apellidos"]= $row[8];
					$respuesta[$contador]["clase"]= $row[9];
					$respuesta[$contador]["fecha_f"]= $row[10];
					$respuesta[$contador]["fecha_r"]= $row[11];
					$respuesta[$contador]["obs"]= $row[12];
				$contador++;
				}
				return $respuesta;
			}
		}

 }


 
 function reporte_excel($fecha_inicio,$fecha_fin)
 {

 	$con = new DBmanejador;
		if($con->conectar()==true){
			 $consulta = "
			SELECT top.cup_num AS cup
					, tda.asignacion_detalle_id AS id
					, tda.cantidad_asignada AS asignacion
					, f.nombre_familia AS descripcion
					, e.nombre_estilo AS estilo_es
					, tcol.descripcion AS color_es
					, tcli.descripcion AS clip_es
					, p.nombres , p.apellidos, p.clase
					, tda.fecha_finalizacion AS fecha_f
					, tda.fecha_reprogramacion AS fecha_r
					, tda.motivo_reprogramacion AS obs
			FROM
				`tordenesproduccion` top, `tdetalleordenesproduccion` tdop, `tdetalle_asignacion` tda
				, `familia` f, `estilo` e, `tpropiedades` tpr, `tcolores` tcol, `tclips` tcli
				, `personal` p

			WHERE
				top.orden_id = tdop.orden_id
				AND tdop.detalle_id = tda.detalle_id
				AND tdop.familia_id = f.familia_id
				AND f.estilo_id = e.estilo_id
				AND tdop.propiedad_id = tpr.prop_id
				AND tpr.color_id = tcol.color_id
				AND tpr.clip_id = tcli.clip_id
				AND tda.personal_id = p.personal_id

				AND tda.fecha_asignacion >= '".$fecha_inicio."' AND tda.fecha_asignacion <= '".$fecha_fin."'";
//echo $consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["cup"]= $row[0];
					$respuesta[$contador]["id"]= $row[1];	 
					$respuesta[$contador]["asignacion"]= $row[2];
					$respuesta[$contador]["descripcion"]= $row[3];
					$respuesta[$contador]["estilo_es"]= $row[4];
					$respuesta[$contador]["color_es"]= $row[5];
					$respuesta[$contador]["clip_es"]= $row[6];
					$respuesta[$contador]["nombres"]= $row[7];
					$respuesta[$contador]["apellidos"]= $row[8];
					$respuesta[$contador]["clase"]= $row[9];
					$respuesta[$contador]["fecha_f"]= $row[10];
					$respuesta[$contador]["fecha_r"]= $row[11];
					$respuesta[$contador]["obs"]= $row[12];
				$contador++;
				}
				return $respuesta;
			}
		}

 }
 
}
?>