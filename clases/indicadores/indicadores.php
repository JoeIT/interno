<?php
require_once('../../clases/includes/dbmanejador.php');
require_once("../sistema_de_produccion/excelwriter.inc.php");

class Indicadores {

//********************************************************************************
//******************************  Indicador 1  ***********************************
//********************************************************************************

	function reporte_indicador_1_general($fecha_inicio, $fecha_fin){//echo $fecha_inicio.$fecha_fin;
		$con = new DBmanejador;
		if($con->conectar() == true){set_time_limit(120);
			$consulta = "
			SELECT
			  SUM(endetalle.cantidad) AS cantidad, SUM(endetalle.cumplio) AS cumplio
			, (SUM(endetalle.cumplio) / SUM(endetalle.cantidad) * 100) AS porcentaje
			FROM
			(
			SELECT
			top.orden_id AS oid
			, (select sum(cantidad) from `tdetalleordenesproduccion` where orden_id = oid AND estado <>0
AND bloqueado <>1
) AS cantidad
			, SUM(IF (IFNULL(de.fecha_despacho, '2500-01-01') <= IFNULL(top.fecharepro, top.fechaentrega), tda.cantidad_asignada, 0)) AS cumplio
			FROM
			`tordenesproduccion` top
			, `tdetalleordenesproduccion` tdop
			LEFT JOIN `tdetalle_asignacion` tda ON tdop.detalle_id = tda.detalle_id
			LEFT JOIN `despacho_detalle` dede ON tda.asignacion_detalle_id = dede.asignacion_id
			LEFT JOIN `despacho` de ON dede.despacho_id = de.despacho_id
			WHERE
			top.orden_id = tdop.orden_id
            			
			AND IFNULL(top.fecharepro, top.fechaentrega) >= '".$fecha_inicio."'
			AND IFNULL(top.fecharepro, top.fechaentrega) <= '".$fecha_fin."'
			
			GROUP BY top.orden_id
			
			) AS endetalle
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_1_general- fall&oacute;: ' . mysql_error());
			    						 
             $respuesta['indicador_id']=1;
			
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
					$respuesta['cantidad'] = $row['cantidad'];
					$respuesta['cumplio'] = $row['cumplio'];
					$respuesta['porcentaje'] = $row['porcentaje'];
					
					}
				return $respuesta;
			}
		}set_time_limit(30);
	}

	function reporte_indicador_1_clientes($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){set_time_limit(60);
			
			$consulta = "
			SELECT
			  endetalle.cliente
			, (SUM(endetalle.cumplio) / SUM(endetalle.cantidad) * 100) AS porcentaje
			FROM
			(
			
			SELECT
			top.orden_id AS oid, top.cup_num, tc.nombre AS cliente, top.fechaentrega, top.fecharepro
			, (select sum(cantidad) from `tdetalleordenesproduccion` where orden_id = oid and estado<>0 and bloqueado<>1) AS cantidad
			, SUM(tda.cantidad_asignada) AS cant_asig
			, SUM(IF (IFNULL(de.fecha_despacho, '2500-01-01') <= IFNULL(top.fecharepro, top.fechaentrega), tda.cantidad_asignada, 0)) AS cumplio
			, ((SUM(IF (IFNULL(de.fecha_despacho, '2500-01-01') <= IFNULL(top.fecharepro, top.fechaentrega), tda.cantidad_asignada, 0)) / (select sum(cantidad) from `tdetalleordenesproduccion` where orden_id = oid)) * 100) AS porcentaje
			FROM
			`tordenesproduccion` top
			, `tclientes` tc
			, `familia` f
			, `tdetalleordenesproduccion` tdop
			LEFT JOIN `tdetalle_asignacion` tda ON tdop.detalle_id = tda.detalle_id
			LEFT JOIN `despacho_detalle` dede ON tda.asignacion_detalle_id = dede.asignacion_id
			LEFT JOIN `despacho` de ON dede.despacho_id = de.despacho_id
			WHERE
			top.orden_id = tdop.orden_id
			AND top.cliente_id = tc.cliente_id
			AND tdop.familia_id = f.familia_id
			AND IFNULL(top.fecharepro, top.fechaentrega) >= '".$fecha_inicio."'
			AND IFNULL(top.fecharepro, top.fechaentrega) <= '".$fecha_fin."'
			
			GROUP BY top.orden_id
			ORDER BY tc.nombre, top.cup_num
			
			) AS endetalle
			GROUP BY endetalle.cliente
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_1_clientes- fall&oacute;: ' . mysql_error());	
			
				
					
			$contador = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['cliente'] = $row['cliente'];
					$respuesta[$contador]['porcentaje'] = $row['porcentaje'];
										
					$contador ++;
				}
				return $respuesta;
			}
		}set_time_limit(30);
	}

	function reporte_indicador_1_ordenes($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){set_time_limit(60);
			$consulta = "
			SELECT
			top.orden_id AS oid, top.cup_num, tc.nombre AS cliente, top.fechaentrega, top.fecharepro
			, (select sum(cantidad) from `tdetalleordenesproduccion` where orden_id = oid and estado<>0 and bloqueado<>1) AS cantidad
			, (IFNULL(SUM(tda.cantidad_asignada), 0)) AS cant_asig
			, SUM(IF (IFNULL(de.fecha_despacho, '2500-01-01') <= IFNULL(top.fecharepro, top.fechaentrega), tda.cantidad_asignada, 0)) AS cumplio
			, ((SUM(IF (IFNULL(de.fecha_despacho, '2500-01-01') <= IFNULL(top.fecharepro, top.fechaentrega), tda.cantidad_asignada, 0)) / (select sum(cantidad) from `tdetalleordenesproduccion` where orden_id = oid)) * 100) AS porcentaje
			FROM
			`tordenesproduccion` top
			, `tclientes` tc
			, `familia` f
			, `tdetalleordenesproduccion` tdop
			LEFT JOIN `tdetalle_asignacion` tda ON tdop.detalle_id = tda.detalle_id
			LEFT JOIN `despacho_detalle` dede ON tda.asignacion_detalle_id = dede.asignacion_id
			LEFT JOIN `despacho` de ON dede.despacho_id = de.despacho_id
			WHERE
			top.orden_id = tdop.orden_id
			AND top.cliente_id = tc.cliente_id
			AND tdop.familia_id = f.familia_id
			
			AND IFNULL(top.fecharepro, top.fechaentrega) >= '".$fecha_inicio."'
			AND IFNULL(top.fecharepro, top.fechaentrega) <= '".$fecha_fin."'
			
			GROUP BY top.orden_id
			ORDER BY tc.nombre, top.cup_num
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_1_ordenes- fall&oacute;: ' . mysql_error());			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['cup_num'] = $row['cup_num'];
					$respuesta[$contador]['cliente'] = $row['cliente'];
					
					if ($row['fecharepro'] == '')
						$respuesta[$contador]['fecha'] = $row['fechaentrega'];
					else
						$respuesta[$contador]['fecha'] = $row['fecharepro'];
										
					$respuesta[$contador]['cantidad'] = $row['cantidad'];
					$respuesta[$contador]['cant_asig'] = $row['cant_asig'];
					$respuesta[$contador]['cumplio'] = $row['cumplio'];
					$respuesta[$contador]['porcentaje'] = $row['porcentaje'];
										
					$contador ++;
				}
				return $respuesta;
			}
		}set_time_limit(30);
	}
	

//********************************************************************************
//******************************  Indicador 2  ***********************************
//********************************************************************************
	function reporte_indicador_2_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			  SUM(tda.cantidad_asignada) AS cantidad
			, it.clase
			, it.nombre AS t_material
			FROM
			  `tordenesproduccion` top
			, `tdetalleordenesproduccion` tdop
			, `tdetalle_asignacion` tda
			, `trecepcion` tre
			, `indicadores_tipo` it
			WHERE
				top.orden_id = tdop.orden_id
			AND tdop.detalle_id = tda.detalle_id
			AND tda.asignacion_detalle_id = tre.asignacion_id
			AND tdop.tipo = it.indicadores_tipo_id
			
			AND tre.fecha >= '".$fecha_inicio."'
			AND tre.fecha <= '".$fecha_fin."'
			
			GROUP BY t_material
			ORDER BY clase
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_2_general- fall&oacute;: ' . mysql_error());			
			
			
			
			
			
			$respuesta['A']['numero'] ='A';
			$respuesta['A']['tipo'] = 'Estuche de cuero';
			$respuesta['A']['cantidad'] = 0;
			$respuesta['A']['indicador_id'] = 2;
			
			$respuesta['B']['numero'] ='B';
			$respuesta['B']['tipo'] = 'Portabiblias cuero';
			$respuesta['B']['cantidad'] = 0;
			$respuesta['B']['indicador_id'] = 3;
			
			$respuesta['C']['numero'] ='C';
			$respuesta['C']['tipo'] = 'Accesorios de escritorio';
			$respuesta['C']['cantidad'] = 0;
			$respuesta['C']['indicador_id'] = 4;

			$respuesta['D']['numero'] ='D';
			$respuesta['D']['tipo'] = 'Maletines cuero ';
			$respuesta['D']['cantidad'] = 0;
			$respuesta['D']['indicador_id'] = 5;
			
			$respuesta['E']['numero'] ='E';
			$respuesta['E']['tipo'] = 'Estuches PU';
			$respuesta['E']['cantidad'] = 0;
			$respuesta['E']['indicador_id'] = 6;
			
			$respuesta['F']['numero'] ='F';
			$respuesta['F']['tipo'] = 'Portabiblias PU';
			$respuesta['F']['cantidad'] = 0;
			$respuesta['F']['indicador_id'] = 7;
			
					
			/*
			$respuesta['G']['numero'] ='G';
			$respuesta['G']['tipo'] = 'Portabiblias textil ';
			$respuesta['G']['cantidad'] = 0;
			$respuesta['G']['indicador_id'] = 8;*/
	/*para no mostrar en el reporte del indicadro requerimiento de monica*/
			/*$respuesta['H']['numero'] ='H';
			$respuesta['H']['tipo'] = 'Tapizado vehiculos ';
			$respuesta['H']['cantidad'] = 0;
			$respuesta['H']['indicador_id'] = 9;
			
			$respuesta['I']['numero'] ='I';
			$respuesta['I']['tipo'] = 'Tapizado muebles ';
			$respuesta['I']['cantidad'] = 0;
			$respuesta['I']['indicador_id'] = 10;	*/
			
			$respuesta['J']['numero'] ='J';
			$respuesta['J']['tipo'] = 'Otros';
			$respuesta['J']['cantidad'] = 0;
			$respuesta['J']['indicador_id'] = 11;	
					
					
	//		$contador = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){      
					$respuesta[$row['clase']]['numero'] = $row['clase'];  //la clase es igual an numero
					$respuesta[$row['clase']]['tipo'] = $row['t_material'];// //el t_material es igual tipo
					$respuesta[$row['clase']]['cantidad'] = $row['cantidad'];// la cantidad es igual a cantidad
					
					//$contador ++;
				}
				return $respuesta;
			}
		}
	}
	
	function total_indicador_2_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "SELECT
						  SUM(tda.cantidad_asignada) AS total
						FROM
						  `tordenesproduccion` top
						, `tdetalleordenesproduccion` tdop
						, `tdetalle_asignacion` tda
						, `trecepcion` tre
						, `indicadores_tipo` it
						WHERE
							top.orden_id = tdop.orden_id
						AND tdop.detalle_id = tda.detalle_id
						AND tda.asignacion_detalle_id = tre.asignacion_id
						AND tdop.tipo = it.indicadores_tipo_id
						
						AND tre.fecha >= '".$fecha_inicio."'
						AND tre.fecha <= '".$fecha_fin."'";
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_2_general- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return 0;
			else
			{
				$row = mysql_fetch_array($resultado);
				return $row['total'];
			}
		}
		else
			return 0;
	}

	function reporte_indicador_2_ordenes($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			  top.cup_num AS OP
			, f.nombre_familia AS Producto
			, e.nombre_estilo AS Estilo
			, tcu.descripcion AS origen_cuero
			, tdop.cantidad AS Cantidad_OP
			, SUM(tda.cantidad_asignada) AS Cantidad_asignada
			, it.nombre AS t_material
			FROM
			  `tordenesproduccion` top
			, `familia` f
			, `estilo` e
			, `tdetalleordenesproduccion` tdop
			, `tpropiedades` tpro
			, `tcueros` tcu
			, `tdetalle_asignacion` tda
			, `trecepcion` tre
			, `indicadores_tipo` it
			WHERE
				top.orden_id = tdop.orden_id
			AND tdop.familia_id = f.familia_id
			AND f.estilo_id = e.estilo_id
			AND tdop.detalle_id = tda.detalle_id
			AND tdop.propiedad_id = tpro.prop_id
			AND tpro.cuero_id = tcu.cuero_id
			AND tda.asignacion_detalle_id = tre.asignacion_id
			AND tdop.tipo = it.indicadores_tipo_id
			
			AND tre.fecha >= '".$fecha_inicio."'
			AND tre.fecha <= '".$fecha_fin."'
			
			GROUP BY tdop.detalle_id
			ORDER BY top.cup_num, f.nombre_familia
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_2_ordenes- fall&oacute;: ' . mysql_error());			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['op'] = $row['OP'];
					$respuesta[$contador]['producto'] = $row['Producto'];
					$respuesta[$contador]['estilo'] = $row['Estilo'];
					$respuesta[$contador]['origen_cuero'] = $row['origen_cuero'];
					$respuesta[$contador]['cantidad_op'] = $row['Cantidad_OP'];
					$respuesta[$contador]['cantidad_asignada'] = $row['Cantidad_asignada'];
					$respuesta[$contador]['tipo'] = $row['t_material'];
					$contador ++;
				}
				return $respuesta;
			}
		}
	}
//********************************************************************************
//******************************  Indicador 3  ***********************************
//********************************************************************************
	function reporte_indicador_3_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	IF (opn.tipo = 1,'Desarrollo de producto nuevo','Elaboración de muestra') as tipo
					, SUM(opn.cantidad) AS cantidad
			FROM	`tordenesproductos` opn
			WHERE	opn.fechasolicitud >= '".$fecha_inicio."'
					AND opn.fechasolicitud <= '".$fecha_fin."'
			GROUP BY tipo
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_3_general- fall&oacute;: ' . mysql_error());			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['tipo'] = $row['tipo'];
					$respuesta[$contador]['cantidad'] = $row['cantidad'];
					$contador ++;
				}
				return $respuesta;
			}
		}
	}
	
	function reporte_indicador_3_detalle($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  IF (opn.tipo = 1,'Desarrollo de producto nuevo','Elaboración de muestra') as tipo
					, opn.ordenproducto_id AS opid
					, opn.fechasolicitud
					, IFNULL(opn.fechareprogramacion, opn.fechaculminacion) AS fechafinalizacion
					, opn.modelo
					, opn.cantidad
					, e.nombre_estilo AS style
					, tc.descripcion AS clip
			FROM	  `tordenesproductos` opn
					, `estilo` e
					, `tclips` tc
			WHERE	    opn.estilo_id = e.estilo_id
					AND opn.clip_id = tc.clip_id
					AND opn.fechasolicitud >= '".$fecha_inicio."'
					AND opn.fechasolicitud <= '".$fecha_fin."'
			
			ORDER BY opn.tipo, opn.fechasolicitud
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte_indicador_3_detalle- fall&oacute;: ' . mysql_error());
			$contador = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row['tipo']][$row['opid']]['modelo'] = $row['modelo'];
					$respuesta[$row['tipo']][$row['opid']]['cantidad'] = $row['cantidad'];
					$respuesta[$row['tipo']][$row['opid']]['estilo'] = $row['style'];
					$respuesta[$row['tipo']][$row['opid']]['clip'] = $row['clip'];
					$respuesta[$row['tipo']][$row['opid']]['fechasolicitud'] = $row['fechasolicitud'];
					$respuesta[$row['tipo']][$row['opid']]['fechafinalizacion'] = $row['fechafinalizacion'];
				}
				return $respuesta;
			}
		}
	}

//********************************************************************************
//******************************  Indicador 4  ***********************************
//********************************************************************************
	function cumplimiento_fechas_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  (SUM(IF(DATE(thp.fecha) <= IFNULL(top.fechareprogramacion, top.fechaculminacion), top.cantidad, 0)) / SUM(top.cantidad) * 100) AS valor
			FROM	  `tordenesproductos` top
					, `thistorialordenprodnuevo` thp
			WHERE	    top.ordenproducto_id = thp.orden_producto_id
					AND top.estadoproducto = 'concluido'
					AND IFNULL(top.fechareprogramacion, top.fechaculminacion) >= '".$fecha_inicio."'
					AND IFNULL(top.fechareprogramacion, top.fechaculminacion) <= '".$fecha_fin."'
					AND thp.observaciones = 'Concluido'
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -cumplimiento_fechas_general- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				if ($row = mysql_fetch_array($resultado)) {
					$respuesta = $row['valor'];
				}
				return $respuesta;
			}
		}
	}

	function cumplimiento_fechas_detalle($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  top.ordenproducto_id
					, top.modelo
					, top.cantidad
					, top.fechasolicitud
					, IFNULL(top.fechareprogramacion, top.fechaculminacion) AS fechaconclusion
					, DATE(thp.fecha) AS fecharealconclusion
					, (IF(DATE(thp.fecha) <= IFNULL(top.fechareprogramacion, top.fechaculminacion), 'Cumplio', 'No cumplio')) AS cumplimiento
			FROM	  `tordenesproductos` top
					, `thistorialordenprodnuevo` thp
			WHERE	    top.ordenproducto_id = thp.orden_producto_id
					AND top.estadoproducto = 'concluido'
					AND IFNULL(top.fechareprogramacion, top.fechaculminacion) >= '".$fecha_inicio."'
					AND IFNULL(top.fechareprogramacion, top.fechaculminacion) <= '".$fecha_fin."'
					AND thp.observaciones = 'Concluido'
			ORDER BY cumplimiento, modelo
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -cumplimiento_fechas_detalle- fall&oacute;: ' . mysql_error());
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['ordenproducto_id'] = $row['ordenproducto_id'];
					$respuesta[$contador]['modelo'] = $row['modelo'];
					$respuesta[$contador]['cantidad'] = $row['cantidad'];
					$respuesta[$contador]['fechasolicitud'] = $row['fechasolicitud'];
					$respuesta[$contador]['fechaconclusion'] = $row['fechaconclusion'];
					$respuesta[$contador]['fecharealconclusion'] = $row['fecharealconclusion'];
					$respuesta[$contador]['cumplimiento'] = $row['cumplimiento'];
					$contador ++;
				}
				return $respuesta;
			}
		}
	}
	
//********************************************************************************
//******************************  Indicador 5  ***********************************
//********************************************************************************
	function medio_proceso_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  tda.medio_proceso_tipo AS tipo
					, COUNT(tda.medio_proceso_tipo) AS cantidad
			FROM    `tdetalle_asignacion` tda
			WHERE	    tda.fecha_inicio >= '".$fecha_inicio."'
					AND tda.fecha_inicio <= '".$fecha_fin."'
			GROUP BY tda.medio_proceso_tipo
			";
			
			//echo "<br>SQL: ".$consulta;
			
						
			
			
			
			$resultado = mysql_query($consulta) or die ('La consulta -medio_proceso_general- fall&oacute;: ' . mysql_error());
		
			$respuesta[0]['numero'] = 1; $respuesta[0]['tipo'] = 'Sin control';		$respuesta[0]['cantidad'] = 0;$respuesta[0]['indicador_id'] = 15; 
			$respuesta[1]['numero'] = 2; $respuesta[1]['tipo'] = 'Muestra';			$respuesta[1]['cantidad'] = 0;$respuesta[1]['indicador_id'] = 16;
			$respuesta[2]['numero'] = 3; $respuesta[2]['tipo'] = 'Medio proceso';	$respuesta[2]['cantidad'] = 0;$respuesta[2]['indicador_id'] = 17;
			$respuesta[3]['numero'] = 4; $respuesta[3]['tipo'] = 'Ambos controles';	$respuesta[3]['cantidad'] = 0;$respuesta[3]['indicador_id'] = 18;
			
			if (!$resultado)
				return false;
			else {
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$row['tipo']]['cantidad'] = $row['cantidad'];
					
				}
				return $respuesta;
			}
		}
	}

//********************************************************************************
//******************************  Indicador 9  ***********************************
//********************************************************************************

	function composicion_rechazo_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  IFNULL(tclfa.nombre_fallo, 'O') AS fallo
					, SUM(IFNULL(trech.cantidad, 0)) AS cantidad_rechazada
					, SUM(tda.cantidad_asignada) AS cantidad_asignada
			FROM	`tdetalle_asignacion` tda LEFT JOIN
					`trechazo` trech ON tda.asignacion_detalle_id = trech.asignacion_id LEFT JOIN
					`tclasificacion_fallos` tclfa ON trech.clasificacion_fallo_id = tclfa.clasificacion_fallo_id
			WHERE		tda.fecha_cerrado >= '".$fecha_inicio."'
					AND tda.fecha_cerrado <= '".$fecha_fin."'
			GROUP BY tclfa.nombre_fallo
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -composicion_rechazo_general- fall&oacute;: ' . mysql_error());
			
			$respuesta['A']['numero'] = 1; $respuesta['A']['fallo'] = 'Fallo tipo A'; $respuesta['A']['asignado'] = 0; $respuesta['A']['rechazado'] = 0;$respuesta['A']['indicador_id'] = 37;
			
			$respuesta['B']['numero'] = 2; $respuesta['B']['fallo'] = 'Fallo tipo B'; $respuesta['B']['asignado'] = 0; $respuesta['B']['rechazado'] = 0;$respuesta['B']['indicador_id'] = 38;
			
			$respuesta['C']['numero'] = 3; $respuesta['C']['fallo'] = 'Fallo tipo C'; $respuesta['C']['asignado'] = 0; $respuesta['C']['rechazado'] = 0;$respuesta['C']['indicador_id'] = 39;
			
			$respuesta['D']['numero'] = 4; $respuesta['D']['fallo'] = 'Fallo tipo D'; $respuesta['D']['asignado'] = 0; $respuesta['D']['rechazado'] = 0;$respuesta['D']['indicador_id'] = 40;
			
			$respuesta['O']['numero'] = 5; $respuesta['O']['fallo'] = 'Sin fallo';    $respuesta['O']['asignado'] = 0; $respuesta['O']['rechazado'] = 0;
			
			
			$total = 0;
			
			if (!$resultado)
				return false;
			else {
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$row['fallo']]['asignado'] = $row['cantidad_asignada'];
					$respuesta[$row['fallo']]['rechazado'] = $row['cantidad_rechazada'];
					$total = $total + $row['cantidad_asignada'];
				}
				if ($total > 0) {
					$respuesta['A']['porcentaje'] = ($respuesta['A']['rechazado'] / $total)*100;
					$respuesta['B']['porcentaje'] = ($respuesta['B']['rechazado'] / $total)*100;
					$respuesta['C']['porcentaje'] = ($respuesta['C']['rechazado'] / $total)*100;
					$respuesta['D']['porcentaje'] = ($respuesta['D']['rechazado'] / $total)*100;
					$respuesta['O']['porcentaje'] = ($respuesta['O']['rechazado'] / $total)*100;
				} else {
					$respuesta['A']['porcentaje'] = 0;
					$respuesta['B']['porcentaje'] = 0;
					$respuesta['C']['porcentaje'] = 0;
					$respuesta['D']['porcentaje'] = 0;
					$respuesta['O']['porcentaje'] = 0;
				}
				
				return $respuesta;
			}
		}
	}

//********************************************************************************
//******************************  Indicador 10  ***********************************
//********************************************************************************

	function tipo_rechazo_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  tclfa.nombre_fallo AS fallo
					, SUM(trech.cantidad) AS cantidad
			FROM	  `tdetalle_asignacion` tda
					, `trechazo` trech
					, `tclasificacion_fallos` tclfa
			WHERE	    tda.asignacion_detalle_id = trech.asignacion_id
					AND trech.clasificacion_fallo_id = tclfa.clasificacion_fallo_id
					AND tda.fecha_cerrado >= '".$fecha_inicio."'
					AND tda.fecha_cerrado <= '".$fecha_fin."'
			GROUP BY nombre_fallo
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -tipo_rechazo_general- fall&oacute;: ' . mysql_error());
			
			$respuesta['A']['numero'] = 1; $respuesta['A']['fallo'] = 'Fallo tipo A'; $respuesta['A']['cantidad'] = 0;$respuesta['A']['indicador_id'] = 41;
			$respuesta['B']['numero'] = 2; $respuesta['B']['fallo'] = 'Fallo tipo B'; $respuesta['B']['cantidad'] = 0;$respuesta['B']['indicador_id'] = 42;
			$respuesta['C']['numero'] = 3; $respuesta['C']['fallo'] = 'Fallo tipo C'; $respuesta['C']['cantidad'] = 0;$respuesta['C']['indicador_id'] = 43;
			$respuesta['D']['numero'] = 4; $respuesta['D']['fallo'] = 'Fallo tipo D'; $respuesta['D']['cantidad'] = 0;$respuesta['D']['indicador_id'] = 44;
			
			$total = 0;
			
			if (!$resultado)
				return false;
			else {
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$row['fallo']]['cantidad'] = $row['cantidad'];
					$total = $total + $row['cantidad'];
				}
				if ($total > 0) {
					$respuesta['A']['porcentaje'] = $respuesta['A']['cantidad'] / $total * 100;
					$respuesta['B']['porcentaje'] = $respuesta['B']['cantidad'] / $total * 100;
					$respuesta['C']['porcentaje'] = $respuesta['C']['cantidad'] / $total * 100;
					$respuesta['D']['porcentaje'] = $respuesta['D']['cantidad'] / $total * 100;
				} else {
					$respuesta['A']['porcentaje'] = 0;
					$respuesta['B']['porcentaje'] = 0;
					$respuesta['C']['porcentaje'] = 0;
					$respuesta['D']['porcentaje'] = 0;
				}
				
				return $respuesta;
			}
		}
	}
//********************************************************************************
//******************************  Indicador 11  ***********************************
//********************************************************************************
	function cumplimiento_asignaciones_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	IF(DATE(tda.fecha_entrega) <= IFNULL(tda.fecha_reprogramacion, tda.fecha_finalizacion), 1, 0) AS cumplio
					, COUNT(tda.asignacion_detalle_id) AS cantidad
			FROM	`tdetalle_asignacion` tda
			WHERE	tda.fecha_entrega >= '".$fecha_inicio."'
					AND tda.fecha_entrega <= '".$fecha_fin."'
			GROUP BY cumplio
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -cumplimiento_asignaciones_general- fall&oacute;: ' . mysql_error());
			
			$respuesta['indicador_id']=45;
			$resp[0]['cantidad'] = 0;
			$resp[1]['cantidad'] = 0;
			$total = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$resp[$row['cumplio']]['cantidad'] = $row['cantidad'];
					$total = $total + $row['cantidad'];
				}
				
				$respuesta['cantidad'] = $total;
				$respuesta['cumplio'] = $resp[1]['cantidad'];
				$respuesta['porcentaje'] = $resp[1]['cantidad'] / $total * 100;
				
				return $respuesta;
			}
		}
	}
	function cumplimiento_asignaciones_detalle($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  IF(tda.personal_id = 237, 1, 0) AS maquinista
					, IF(DATE(tda.fecha_entrega) <= IFNULL(tda.fecha_reprogramacion, tda.fecha_finalizacion), 1, 0) AS cumplio
					, COUNT(tda.asignacion_detalle_id) AS cantidad
			FROM	`tdetalle_asignacion` tda
			WHERE		tda.fecha_entrega >= '".$fecha_inicio."'
					AND tda.fecha_entrega <= '".$fecha_fin."'
			GROUP BY maquinista, cumplio
			";
			//echo "<br>SQL: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -cumplimiento_asignaciones_detalle- fall&oacute;: ' . mysql_error());
			
			$respuesta[0]['numero'] = 1; $respuesta[0]['tipo'] = 'Maquinista No cumplio'; $respuesta[0]['cantidad'] = 0;
			$respuesta[1]['numero'] = 2; $respuesta[1]['tipo'] = 'Maquinista cumplio';	  $respuesta[1]['cantidad'] = 0;
			$respuesta[2]['numero'] = 3; $respuesta[2]['tipo'] = 'Linea No cumplio';	  $respuesta[2]['cantidad'] = 0;
			$respuesta[3]['numero'] = 4; $respuesta[3]['tipo'] = 'Linea cumplio';		  $respuesta[3]['cantidad'] = 0;
			
			$total = 0;
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					if ($row['maquinista'] == 0 && $row['cumplio'] == 0) $respuesta[0]['cantidad'] = $row['cantidad'];
					if ($row['maquinista'] == 0 && $row['cumplio'] == 1) $respuesta[1]['cantidad'] = $row['cantidad'];
					if ($row['maquinista'] == 1 && $row['cumplio'] == 0) $respuesta[2]['cantidad'] = $row['cantidad'];
					if ($row['maquinista'] == 1 && $row['cumplio'] == 1) $respuesta[3]['cantidad'] = $row['cantidad'];
					
					$total = $total + $row['cantidad'];
				}
				
				$respuesta[0]['porcentaje'] = $respuesta[0]['cantidad'] / $total * 100;
				$respuesta[1]['porcentaje'] = $respuesta[1]['cantidad'] / $total * 100;
				$respuesta[2]['porcentaje'] = $respuesta[2]['cantidad'] / $total * 100;
				$respuesta[3]['porcentaje'] = $respuesta[3]['cantidad'] / $total * 100;
				
				return $respuesta;
			}
			/*
			echo "<pre>";
				print_r ($respuesta);
			echo "</pre>";
			*/
		}
	}
//********************************************************************************
//******************************  Indicador 12  ***********************************
//********************************************************************************

	function produccion_linea_general($fecha_inicio, $fecha_fin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	  COUNT(tda.asignacion_detalle_id) AS id
					, SUM(tda.cantidad_asignada) AS cantidad
					, IF(tda.personal_id = 237, 1, 0) AS maquinista
			FROM	  `tdetalle_asignacion` tda
			WHERE	    tda.fecha_entrega >= '".$fecha_inicio."'
					AND tda.fecha_entrega <= '".$fecha_fin."'
			GROUP BY maquinista
			";
			
			//echo "<br>SQL: ".$consulta;
			 $respuesta['indicador_id']=46;
			$resultado = mysql_query($consulta) or die ('La consulta -produccion_linea_general- fall&oacute;: ' . mysql_error());
			$resp[0]['numero'] = 1; $resp[0]['maqui'] = 'Otros'; $resp[0]['cantidad'] = 0;
			$resp[1]['numero'] = 2; $resp[1]['maqui'] = 'Linea'; $resp[1]['cantidad'] = 0;
			
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$resp[$row['maquinista']]['cantidad'] = $row['cantidad'];
				}
				
				$respuesta['cantidad'] = $resp[0]['cantidad'] + $resp[1]['cantidad'];
				$respuesta['cumplio'] = $resp[1]['cantidad'];
				$respuesta['porcentaje'] = $respuesta['cumplio'] / $respuesta['cantidad'] * 100;
				
				return $respuesta;
			}
		}
	}
//********************************************************************************
//**************************  listar indicadores  ********************************
//********************************************************************************
	function listar_indicadores(){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	(il.area) AS area
					, COUNT(il.area) AS items
			FROM	`indicadores_lista` il
			GROUP BY il.area
			ORDER BY il.area
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -listar_indicadores- fall&oacute;: ' . mysql_error());
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['area'] = $row['area'];
					$respuesta[$contador]['items'] = $row['items'];
					$contador ++;
				}
				return $respuesta;
			}
		}
	}

	function contenido_area($area, $limite){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT f.indicador_lista_id, f.area, f.grupo, f.nombre, f.fecha, f.valor, f.observaciones, f.fecha_actualizacion, f.indicador_valor_id
			FROM
			(
			 SELECT  il.indicador_lista_id, area, grupo, nombre, fecha, valor, observaciones, fecha_actualizacion, iv.indicador_valor_id
			 FROM `indicadores_lista` il LEFT JOIN `indicadores_valores` iv ON il.indicador_lista_id = iv.indicador_lista_id
			 WHERE area = '".$area."'
			) AS f
			LEFT JOIN
			(
			 SELECT  area, grupo, nombre, fecha, valor
			 FROM `indicadores_lista` il LEFT JOIN `indicadores_valores` iv ON il.indicador_lista_id = iv.indicador_lista_id
			 WHERE area = '".$area."'
			) AS f2
			
			ON (f.grupo = f2.grupo AND f.nombre = f2.nombre AND f.fecha <= f2.fecha)
			
			GROUP BY f.grupo, f.nombre, f.fecha
			HAVING COUNT(*) <= ".$limite."
			
			ORDER BY f.indicador_lista_id, f.area, f.grupo, f.indicador_lista_id, f.fecha
			";
			//f.area, f.grupo, f.nombre, f.fecha
			//f.indicador_lista_id
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -contenido_area- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					//$respuesta[$row['indicador_lista_id']][$row['area']][$row['grupo']][$row['nombre']][$row['fecha']] = $row['valor'];
					if($row['indicador_lista_id']!=8)
					{
					$respuesta[$row['area']][$row['grupo']][$row['nombre']]['id'] = $row['indicador_lista_id'];
					$respuesta[$row['area']][$row['grupo']][$row['nombre']][$row['fecha']] = $row['valor'];
					$observaciones[$row['indicador_lista_id']][$row['fecha']] = $row['observaciones'];
					$fecha_act[$row['indicador_lista_id']][$row['fecha']] = $row['fecha_actualizacion'];
					$meta_act[$row['indicador_lista_id']]['meta'] = $this->meta_ultimo_valor($row['indicador_lista_id']);
					}
				}
				
				
				$retorno['valores'] = $respuesta;
				$retorno['observaciones'] = $observaciones;
				$retorno['fecha_act'] = $fecha_act;
				$retorno['meta_act'] = $meta_act;
				return $retorno;
			}
		}
	}
	
	function ultima_fecha_area($iid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			 SELECT  iv.indicador_valor_id,il.nombre, iv.fecha, iv.valor, iv.observaciones
			 FROM    `indicadores_lista` il, `indicadores_valores` iv
			 WHERE   il.indicador_lista_id = iv.indicador_lista_id
					 AND il.indicador_lista_id = ".$iid."
			 ORDER BY iv.fecha DESC
			 LIMIT 0, 1";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -ultima_fecha_area- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
					$respuesta['indicador_valor_id'] = $row['indicador_valor_id'];
					$respuesta['fecha'] = $row['fecha'];
					$respuesta['valor'] = $row['valor'];
					$respuesta['observaciones'] = $row['observaciones'];
					$respuesta['nombre'] = $row['nombre'];
				}
				return $respuesta;
			}
		}
	}
	
	//insertar
	function insertar_valor_indicador($fecha, $valor, $observaciones, $fecha_actualizacion, $indicador_lista_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO `indicadores_valores` (`fecha`, `valor`, `observaciones`, `fecha_actualizacion`, `indicador_lista_id`)
			VALUES ('".$fecha."', '".$valor."', '".$observaciones."', '".$fecha_actualizacion."', '".$indicador_lista_id."')";
			
		//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_valor_indicador- fall&oacute;: ' . mysql_error());
		}
	}
	//actualizar
	function actualizar_valor_indicador($indicador_valor_id, $valor, $observaciones, $fecha_actualizacion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	indicadores_valores
			SET		valor = '".$valor."'
					, observaciones = '".$observaciones."'
					, fecha_actualizacion = '".$fecha_actualizacion."'
			WHERE	indicador_valor_id = ".$indicador_valor_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_valor_indicador- fall&oacute;: ' . mysql_error());
		}
	}
//********************************************************************************
//**************************  actualizar las metas  ********************************
//********************************************************************************
	function listado_metas_areas($area, $limite){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT f.indicador_lista_id, f.area, f.grupo, f.nombre, f.valor, f.observacion, f.fecha
			FROM
			(
			SELECT  il.indicador_lista_id, area, grupo, nombre, valor, observacion, fecha
			FROM `indicadores_lista` il LEFT JOIN `indicadores_metas` im ON il.indicador_lista_id = im.indicador_id
			WHERE area = '".$area."'
			) AS f
			LEFT JOIN
			(
			SELECT  il.indicador_lista_id, area, grupo, nombre, valor, observacion, fecha
			FROM `indicadores_lista` il LEFT JOIN `indicadores_metas` im ON il.indicador_lista_id = im.indicador_id
			WHERE area = '".$area."'
			) AS f2
						
			ON (f.grupo = f2.grupo AND f.nombre = f2.nombre AND f.fecha <= f2.fecha)
						
			GROUP BY f.grupo, f.nombre, f.fecha
			HAVING COUNT(*) <= ".$limite."
						
			ORDER BY f.indicador_lista_id, f.area, f.grupo, f.indicador_lista_id, f.fecha
			";
			//f.area, f.grupo, f.nombre, f.fecha
			//f.indicador_lista_id
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -contenido_area- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row['area']][$row['grupo']][$row['nombre']]['indicador_id'] = $row['indicador_lista_id'];
					$respuesta[$row['area']][$row['grupo']][$row['nombre']]['nombre'] = $row['nombre'];
					$respuesta[$row['area']][$row['grupo']][$row['nombre']]['valor'] = $row['valor'];
					$respuesta[$row['area']][$row['grupo']][$row['nombre']]['observacion'] = $row['observacion'];
					$respuesta[$row['area']][$row['grupo']][$row['nombre']]['fecha'] = $row['fecha'];
				}
				return $respuesta;
			}
		}
	}
	
	//insertar nueva meta
	function insertar_valor_meta($valor, $observacion, $fecha, $usuario, $indicador_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO `indicadores_metas` (`valor`, `observacion`, `fecha`, `usuario`, `indicador_id`)
			VALUES ('".$valor."', '".$observacion."', '".$fecha."', ".$usuario.", ".$indicador_id.")
			";
			
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_valor_meta- fall&oacute;: ' . mysql_error());
		}
	}
	//ultimo valor para una meta
	function meta_ultimo_valor($meta_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	im.valor, im.fecha, im.observacion
			FROM	`indicadores_metas` im
			WHERE	im.indicador_id = ".$meta_id."
			ORDER BY im.fecha DESC
			LIMIT 0, 1
			";
			
			//echo "<br>SQL: ".$consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				if($row = mysql_fetch_array($resultado)){
					$respuesta['valor'] = $row['valor'];
					$respuesta['fecha'] = $row['fecha'];
					$respuesta['obs'] = $row['observacion'];
				}
				return $respuesta;
			}
		}
	}
//****************************************************
	function escribir_excel($nombre_archivo, $datos) {
		$excel = new ExcelWriter("../../reportes/indicadores/".$nombre_archivo.".xls");
		if($excel == false)
			echo $excel->error;
		
		$cab = "";
		$cabecera = $datos[0];
		foreach($cabecera as $llave => $valor){
			$cab[] = $llave;
		}
		$excel->writeCabecera($cab);
		
		foreach($datos as $key => $value){
			$myArr = "";
			foreach($value as $llave => $valor){
				$myArr[] = $valor;
			}
			$excel->writeLine($myArr);
		}
		$excel->close();
	}
	/*reporte Monica indicador #6*/
	function reporte_ind6($fecha_inicio,$fecha_fin)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT top.cup_num AS OP	, tclien.nombre AS cliente
						, tdop.cantidad AS cant_OP
						, f.nombre_familia AS producto
						, e.nombre_estilo AS estilo
						, tcu.descripcion AS cuero
						, SUM(tdes.total_piezas_material) AS total_cuero_despiece
						, it.nombre AS tipo_nom	
				   FROM
  						`tordenesproduccion` top
						, `tclientes` tclien
						, `tdetalleordenesproduccion` tdop
						, `indicadores_tipo` it
						, `familia` f
						, `estilo` e
						, `tpropiedades` tpro
						, `tcueros` tcu
						, `tdespiece` tdes
						, `tmateriales` tmat
				  WHERE
						top.orden_id = tdop.orden_id
						AND top.cliente_id = tclien.cliente_id
						AND tdop.tipo = it.indicadores_tipo_id
						AND tdop.familia_id = f.familia_id
						AND f.estilo_id = e.estilo_id
						AND tdop.propiedad_id = tpro.prop_id
						AND tpro.cuero_id = tcu.cuero_id
						AND tdop.detalle_id = tdes.detalle_id
						AND tdes.material_id = tmat.material_id
						AND (tmat.tipo_material_id = 4 || tmat.tipo_material_id = 5)
						AND tdop.detalle_id in
						(
						SELECT detalle_cortado.detalle_id
						FROM
						(
  						SELECT
      							tdop.detalle_id
    							, tdop.cantidad
    							, h.hoja_id
    							, h.cantidad AS cant_cortada
    							, decor.completo
    							, decor.tipo_material_id
  						FROM
    							`tdetalleordenesproduccion` tdop
    							, `hoja` h
    							, `detalle_corte` decor
  						WHERE
    							tdop.detalle_id = h.detalle_id
    							AND h.hoja_id = decor.hoja_id

    							AND (decor.tipo_material_id = 4 || decor.tipo_material_id = 5)
    							AND decor.fecha_hora_ini >= '".$fecha_inicio." 00:00:00'
    							AND decor.fecha_hora_fin <= '".$fecha_fin." 24:00:00'

  						GROUP BY tdop.detalle_id
  						HAVING tdop.cantidad = SUM(h.cantidad)
						) AS detalle_cortado
						)
				GROUP BY tdop.detalle_id";
		//echo $consulta;

	          $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['OP'];
					$respuesta[$contador]['cliente'] = $row['cliente'];
					$respuesta[$contador]['cant_OP'] = $row['cant_OP'];
					$respuesta[$contador]['producto'] = $row['producto'];
					$respuesta[$contador]['estilo'] = $row['estilo'];
					$respuesta[$contador]['cuero'] = $row['cuero'];
					$respuesta[$contador]['total_cuero_despiece'] = $row['total_cuero_despiece'];
					$respuesta[$contador]['tipo_nom'] = $row['tipo_nom'];
					$contador ++;
				}
				return $respuesta;
			}
			}
		}
		function reporte_excelind6($fecha_inicio,$fecha_fin)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT top.cup_num AS OP	, tclien.nombre AS cliente
						, tdop.cantidad AS cant_OP
						, f.nombre_familia AS producto
						, e.nombre_estilo AS estilo
						, tcu.descripcion AS cuero
						, SUM(tdes.total_piezas_material) AS total_cuero_despiece
						, it.nombre AS tipo_nom	
				   FROM
  						`tordenesproduccion` top
						, `tclientes` tclien
						, `tdetalleordenesproduccion` tdop
						, `indicadores_tipo` it
						, `familia` f
						, `estilo` e
						, `tpropiedades` tpro
						, `tcueros` tcu
						, `tdespiece` tdes
						, `tmateriales` tmat
				  WHERE
						top.orden_id = tdop.orden_id
						AND top.cliente_id = tclien.cliente_id
						AND tdop.tipo = it.indicadores_tipo_id
						AND tdop.familia_id = f.familia_id
						AND f.estilo_id = e.estilo_id
						AND tdop.propiedad_id = tpro.prop_id
						AND tpro.cuero_id = tcu.cuero_id
						AND tdop.detalle_id = tdes.detalle_id
						AND tdes.material_id = tmat.material_id
						AND (tmat.tipo_material_id = 4 || tmat.tipo_material_id = 5)
						AND tdop.detalle_id in
						(
						SELECT detalle_cortado.detalle_id
						FROM
						(
  						SELECT
      							tdop.detalle_id
    							, tdop.cantidad
    							, h.hoja_id
    							, h.cantidad AS cant_cortada
    							, decor.completo
    							, decor.tipo_material_id
  						FROM
    							`tdetalleordenesproduccion` tdop
    							, `hoja` h
    							, `detalle_corte` decor
  						WHERE
    							tdop.detalle_id = h.detalle_id
    							AND h.hoja_id = decor.hoja_id

    							AND (decor.tipo_material_id = 4 || decor.tipo_material_id = 5)
    							AND decor.fecha_hora_ini >= '".$fecha_inicio." 00:00:00'
    							AND decor.fecha_hora_fin <= '".$fecha_fin." 24:00:00'

  						GROUP BY tdop.detalle_id
  						HAVING tdop.cantidad = SUM(h.cantidad)
						) AS detalle_cortado
						)
				GROUP BY tdop.detalle_id";
		//echo $consulta;

	          $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['OP'];
					$respuesta[$contador]['cliente'] = $row['cliente'];
					$respuesta[$contador]['cant_OP'] = $row['cant_OP'];
					$respuesta[$contador]['producto'] = $row['producto'];
					$respuesta[$contador]['estilo'] = $row['estilo'];
					$respuesta[$contador]['cuero'] = $row['cuero'];
					$respuesta[$contador]['total_cuero_despiece'] = $row['total_cuero_despiece'];
					$respuesta[$contador]['tipo_nom'] = $row['tipo_nom'];
					$contador ++;
				}
				return $respuesta;
			}
			}
		}
		//reporte indicador 7 moni 
		function reporte_ind7($fecha_inicio,$fecha_fin)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT
 						 top.cup_num AS OP
						 , tclien.nombre AS cliente
						 , tdop.cantidad AS cant_OP
						 , f.nombre_familia AS producto
						 , e.nombre_estilo AS estilo
						 , tcu.descripcion AS cuero
						 , tmat.nombre AS tipo_cueros
                         , (tdes.total_piezas_material) AS total_cuero_despiece
					     ,(IF(tcu.descripcion LIKE '%napa llama%', 'A',
     					  IF(tcu.descripcion LIKE '%tamponato%' || tcu.descripcion LIKE '%sonora%' || tcu.descripcion LIKE '%nubuck%' || tcu.descripcion LIKE '%graso%' || tcu.descripcion LIKE '%oscaria%' || tcu.descripcion LIKE '%softy oscaria%' || tcu.descripcion LIKE '%napa%' || tcu.descripcion LIKE '%ejecutivo%' || tcu.descripcion LIKE '%monarch%' || tcu.descripcion LIKE '%metalizado%' || tcu.descripcion LIKE '%brandy%', 'B',
       					  IF(tcu.descripcion LIKE '%pu%', 'C',
        				  IF(tcu.descripcion LIKE '%vinyl%', 'D', 'E')
       							)
     						   )
   							  )
  							) AS t_material
					FROM
  						`tordenesproduccion` top
						, `tclientes` tclien
						, `tdetalleordenesproduccion` tdop
						, `familia` f
						, `estilo` e		
						, `tpropiedades` tpro
						, `tcueros` tcu
						, `tdespiece` tdes
						, `tmateriales` tmat
				    WHERE
						top.orden_id = tdop.orden_id
						AND top.cliente_id = tclien.cliente_id
						AND tdop.familia_id = f.familia_id
						AND f.estilo_id = e.estilo_id
						AND tdop.propiedad_id = tpro.prop_id
						AND tpro.cuero_id = tcu.cuero_id
						AND tdop.detalle_id = tdes.detalle_id
						AND tdes.material_id = tmat.material_id
						AND (tmat.tipo_material_id = 4 || tmat.tipo_material_id = 5)
						AND tdop.detalle_id in
							(
						SELECT detalle_cortado.detalle_id
							FROM
							(
								  SELECT
								      tdop.detalle_id
								    , tdop.cantidad
								    , h.hoja_id
								    , h.cantidad AS cant_cortada
								    , decor.completo
								    , decor.tipo_material_id
								  FROM
								    `tdetalleordenesproduccion` tdop
								    , `hoja` h
								    , `detalle_corte` decor
								  WHERE
								    tdop.detalle_id = h.detalle_id
								    AND h.hoja_id = decor.hoja_id
								
								    AND (decor.tipo_material_id = 4 || decor.tipo_material_id = 5)
								    AND decor.fecha_hora_ini >= '".$fecha_inicio." 00:00:00'
								    AND decor.fecha_hora_fin <= '".$fecha_fin." 24:00:00'
								
								  GROUP BY tdop.detalle_id
								  HAVING tdop.cantidad = SUM(h.cantidad)
								) AS detalle_cortado
								)
								ORDER BY t_material";
		//echo $consulta;

	          $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['OP'];
					$respuesta[$contador]['cliente'] = $row['cliente'];
					$respuesta[$contador]['cant_OP'] = $row['cant_OP'];
					$respuesta[$contador]['producto'] = $row['producto'];
					$respuesta[$contador]['estilo'] = $row['estilo'];
					$respuesta[$contador]['cuero'] = $row['cuero'];
					$respuesta[$contador]['tipo_cueros'] = $row['tipo_cueros'];
					$respuesta[$contador]['total_cuero_despiece'] = $row['total_cuero_despiece'];
					$respuesta[$contador]['t_material'] = $row['t_material'];
					$contador ++;
				}
				return $respuesta;
			}
			}
		}
		
			function reporte_excelind7($fecha_inicio,$fecha_fin)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT
 						 top.cup_num AS OP
						 , tclien.nombre AS cliente
						 , tdop.cantidad AS cant_OP
						 , f.nombre_familia AS producto
						 , e.nombre_estilo AS estilo
						 , tcu.descripcion AS cuero
						 , tmat.nombre AS tipo_cueros
                         , (tdes.total_piezas_material) AS total_cuero_despiece
					     ,(IF(tcu.descripcion LIKE '%napa llama%', 'A',
     					  IF(tcu.descripcion LIKE '%tamponato%' || tcu.descripcion LIKE '%sonora%' || tcu.descripcion LIKE '%nubuck%' || tcu.descripcion LIKE '%graso%' || tcu.descripcion LIKE '%oscaria%' || tcu.descripcion LIKE '%softy oscaria%' || tcu.descripcion LIKE '%napa%' || tcu.descripcion LIKE '%ejecutivo%' || tcu.descripcion LIKE '%monarch%' || tcu.descripcion LIKE '%metalizado%' || tcu.descripcion LIKE '%brandy%', 'B',
       					  IF(tcu.descripcion LIKE '%pu%', 'C',
        				  IF(tcu.descripcion LIKE '%vinyl%', 'D', 'E')
       							)
     						   )
   							  )
  							) AS t_material
					FROM
  						`tordenesproduccion` top
						, `tclientes` tclien
						, `tdetalleordenesproduccion` tdop
						, `familia` f
						, `estilo` e		
						, `tpropiedades` tpro
						, `tcueros` tcu
						, `tdespiece` tdes
						, `tmateriales` tmat
				    WHERE
						top.orden_id = tdop.orden_id
						AND top.cliente_id = tclien.cliente_id
						AND tdop.familia_id = f.familia_id
						AND f.estilo_id = e.estilo_id
						AND tdop.propiedad_id = tpro.prop_id
						AND tpro.cuero_id = tcu.cuero_id
						AND tdop.detalle_id = tdes.detalle_id
						AND tdes.material_id = tmat.material_id
						AND (tmat.tipo_material_id = 4 || tmat.tipo_material_id = 5)
						AND tdop.detalle_id in
							(
						SELECT detalle_cortado.detalle_id
							FROM
							(
								  SELECT
								      tdop.detalle_id
								    , tdop.cantidad
								    , h.hoja_id
								    , h.cantidad AS cant_cortada
								    , decor.completo
								    , decor.tipo_material_id
								  FROM
								    `tdetalleordenesproduccion` tdop
								    , `hoja` h
								    , `detalle_corte` decor
								  WHERE
								    tdop.detalle_id = h.detalle_id
								    AND h.hoja_id = decor.hoja_id
								
								    AND (decor.tipo_material_id = 4 || decor.tipo_material_id = 5)
								    AND decor.fecha_hora_ini >= '".$fecha_inicio." 00:00:00'
								    AND decor.fecha_hora_fin <= '".$fecha_fin." 24:00:00'
								
								  GROUP BY tdop.detalle_id
								  HAVING tdop.cantidad = SUM(h.cantidad)
								) AS detalle_cortado
								)
								ORDER BY t_material";
	//	echo $consulta;

	          $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['OP'];
					$respuesta[$contador]['cliente'] = $row['cliente'];
					$respuesta[$contador]['cant_OP'] = $row['cant_OP'];
					$respuesta[$contador]['producto'] = $row['producto'];
					$respuesta[$contador]['estilo'] = $row['estilo'];
					$respuesta[$contador]['cuero'] = $row['cuero'];
					$respuesta[$contador]['tipo_cueros'] = $row['tipo_cueros'];
					$respuesta[$contador]['total_cuero_despiece'] = $row['total_cuero_despiece'];
					$respuesta[$contador]['t_material'] = $row['t_material'];
					$contador ++;
				}
				return $respuesta;
			}
			}
		}
		
		//reporte indicador 8 moni 
		function reporte_ind8($fecha_inicio,$fecha_fin)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT
 						 top.cup_num AS OP
						, tdop.detalle_id
						, tdop.cantidad AS Cantidad_OP
						, f.nombre_familia AS Producto
						, e.nombre_estilo AS Estilo
						, tda.asignacion_detalle_id AS Num_asignacion
						, tda.fecha_inicio
						, IFNULL(tda.fecha_reprogramacion, fecha_finalizacion) AS fecha_finalizacion
						, tda.cantidad_asignada
						, CONCAT(p.nombres, ' ', p.apellidos) AS Maquinista
						, tda.fecha_entrega
						, it.clase
						, DATEDIFF(tda.fecha_entrega, tda.fecha_inicio) AS dias_trab
						, (tda.cantidad_asignada / (1 * DATEDIFF(tda.fecha_entrega, tda.fecha_inicio))) AS productividad

					FROM
 						`tordenesproduccion` top
						, `indicadores_tipo` it
						, `familia` f
						, `estilo` e
						, `tdetalleordenesproduccion` tdop
						, `tdetalle_asignacion` tda
						, `personal` p
					WHERE
 					   top.orden_id = tdop.orden_id
						AND tdop.tipo = it.indicadores_tipo_id
						AND tdop.familia_id = f.familia_id
						AND f.estilo_id = e.estilo_id
						AND tdop.detalle_id = tda.detalle_id
						AND tda.personal_id = p.personal_id

						AND tda.fecha_entrega >= '".$fecha_inicio."00:00:00'
						AND tda.fecha_entrega <= '".$fecha_fin." 24:00:00'

						ORDER BY clase, Num_asignacion

						";
		//echo $consulta;

	          $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['OP'];
					$respuesta[$contador]['detalle_id'] = $row['detalle_id'];
					$respuesta[$contador]['Cantidad_OP'] = $row['Cantidad_OP'];
					$respuesta[$contador]['Producto'] = $row['Producto'];
					$respuesta[$contador]['Estilo'] = $row['Estilo'];
					$respuesta[$contador]['Num_asignacion'] = $row['Num_asignacion'];
					$respuesta[$contador]['fecha_inicio'] = $row['fecha_inicio'];
					$respuesta[$contador]['fecha_finalizacion'] = $row['fecha_finalizacion'];
					$respuesta[$contador]['cantidad_asignada'] = $row['cantidad_asignada'];
					$respuesta[$contador]['Maquinista'] = $row['Maquinista'];
					$respuesta[$contador]['fecha_entrega'] = $row['OP'];
					$respuesta[$contador]['clase'] = $row['clase'];
					$respuesta[$contador]['dias_trab'] = $row['dias_trab'];
					$respuesta[$contador]['productividad'] = $row['productividad'];
					$contador ++;
				}
				return $respuesta;
			}
			}
		}

	function reporte_excelind8($fecha_inicio,$fecha_fin)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT
 						 top.cup_num AS OP
						, tdop.detalle_id
						, tdop.cantidad AS Cantidad_OP
						, f.nombre_familia AS Producto
						, e.nombre_estilo AS Estilo
						, tda.asignacion_detalle_id AS Num_asignacion
						, tda.fecha_inicio
						, IFNULL(tda.fecha_reprogramacion, fecha_finalizacion) AS fecha_finalizacion
						, tda.cantidad_asignada
						, CONCAT(p.nombres, ' ', p.apellidos) AS Maquinista
						, tda.fecha_entrega
						, it.clase
						, DATEDIFF(tda.fecha_entrega, tda.fecha_inicio) AS dias_trab
						, (tda.cantidad_asignada / (1 * DATEDIFF(tda.fecha_entrega, tda.fecha_inicio))) AS productividad

					FROM
 						`tordenesproduccion` top
						, `indicadores_tipo` it
						, `familia` f
						, `estilo` e
						, `tdetalleordenesproduccion` tdop
						, `tdetalle_asignacion` tda
						, `personal` p
					WHERE
 					   top.orden_id = tdop.orden_id
						AND tdop.tipo = it.indicadores_tipo_id
						AND tdop.familia_id = f.familia_id
						AND f.estilo_id = e.estilo_id
						AND tdop.detalle_id = tda.detalle_id
						AND tda.personal_id = p.personal_id

						AND tda.fecha_entrega >= '".$fecha_inicio."00:00:00'
						AND tda.fecha_entrega <= '".$fecha_fin." 24:00:00'

						ORDER BY clase, Num_asignacion

						";
		//echo $consulta;

	          $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
			
			
			$contador = 0;
			if (!$resultado)
				return false;
			else{
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['OP'];
					$respuesta[$contador]['detalle_id'] = $row['detalle_id'];
					$respuesta[$contador]['Cantidad_OP'] = $row['Cantidad_OP'];
					$respuesta[$contador]['Producto'] = $row['Producto'];
					$respuesta[$contador]['Estilo'] = $row['Estilo'];
					$respuesta[$contador]['Num_asignacion'] = $row['Num_asignacion'];
					$respuesta[$contador]['fecha_inicio'] = $row['fecha_inicio'];
					$respuesta[$contador]['fecha_finalizacion'] = $row['fecha_finalizacion'];
					$respuesta[$contador]['cantidad_asignada'] = $row['cantidad_asignada'];
					$respuesta[$contador]['Maquinista'] = $row['Maquinista'];
					$respuesta[$contador]['fecha_entrega'] = $row['OP'];
					$respuesta[$contador]['clase'] = $row['clase'];
					$respuesta[$contador]['dias_trab'] = $row['dias_trab'];
					$respuesta[$contador]['productividad'] = $row['productividad'];
					$contador ++;
				}
				return $respuesta;
			}
			}
		}
		
	function reporte_indicador()
	{    $con = new DBmanejador;
		if($con->conectar() == true){
		
		$consulta="SELECT     grupo 
                    FROM	indicadores_lista
                    WHERE	indicador_lista_id in (15,19,29,34)
                    order by indicador_lista_id asc
                    "
		          ;	
		
		 $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
	//	echo $consulta;
		
	 $contador = 0;
	  if (!$resultado)
				return false;
			else{
				
			while ($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['grupo'] = $row['grupo'];
					$contador  ++;
						
					
					}
				    return $respuesta;
								
		    	}	
	  }
	
	}
	
	function verificar_fecha($fecha,$indicador_lista_id)
	{    $con = new DBmanejador;
		if($con->conectar() == true){
		
			
			$consulta="SELECT l.fecha,l.indicador_valor_id
 						FROM indicadores_valores l
 						WHERE l.indicador_lista_id ='".$indicador_lista_id."'AND l.fecha in ( SELECT   Max(i.fecha)
                    FROM	indicadores_valores i
                    WHERE	 i.indicador_lista_id='".$indicador_lista_id."')";
			
				
			
		
		
		 $resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
		// echo $consulta;
		
	     
	       if (!$resultado)
				return false;
			else{
				
			    if($row = mysql_fetch_array($resultado)  )
			     { $respuesta['fecha']= $row['fecha'];
				   $respuesta['indicador_valor_id']= $row['indicador_valor_id'];		
			    	
				   
			       }
				return $respuesta;
			     
			     }
			         
							
		    }	
	  }
	  
	  function reporte_muestra($fecha_inicio,$fecha_fin)
	  	  
	  {
	  	
	  	 $con = new DBmanejador;
		if($con->conectar() == true){
			/*persoal id solo de alex Bravo*/
		$consulta="SELECT	  asig.asignacion_detalle_id, asig.muestra_inicial_fecha, asig.medio_proceso_fecha,asig.medio_proceso_tipo AS tipo
					
			FROM    `tdetalle_asignacion` asig
			WHERE	    asig.fecha_inicio >= '".$fecha_inicio."'
			AND asig.fecha_inicio <= '".$fecha_fin."' and asig.medio_proceso_tipo!=0";
			//echo "<br>SQL: ".$consulta,"<br>";	   
		$resultado = mysql_query($consulta) or die ('La consulta -meta_ultimo_valor- fall&oacute;: ' . mysql_error());
		
		
			 if (!$resultado)
				return false;
			else{
				$contador=0;
			    while($row = mysql_fetch_array($resultado))
			    {
			     
			       $respuesta[$contador]['asignacion_detalle_id']= $row['asignacion_detalle_id'];
				   $respuesta[$contador]['muestra_inicial_fecha']= $row['muestra_inicial_fecha'];		
			       $respuesta[$contador]['medio_proceso_fecha']= $row['medio_proceso_fecha'];
				   /*$respuesta[$contador]['nombres']= $row['nombres'];
				   $respuesta[$contador]['apellidos']= $row['apellidos'];*/
				   $contador++;
			       }
				return $respuesta;
			     
			     }
			         
			
			
		    }
			  	
	  	
	  }
	  
	
	}
	
	

	
	
	
	 

?>