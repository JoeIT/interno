<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Hoja_ruta_corte{
	function Hoja_ruta_corte(){
	}
	
	//muestra el detalle de la orden, sacando los datos de la tabla hoja
	function detalle_orden_inicial($op_id, $clave){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//cambiar para los de entrega
			//1 corte, 2 dividido, 3 desbaste, 4 sellado, 5 planchado
			switch ($clave){
				case 1: {
					$consulta = "
					SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo
							, tcli.descripcion AS clip, tco.descripcion AS color
							, h.cantidad, h.hoja_id, h.corte, h.dividido, h.desbaste
							, h.sellado, h.planchado, tdop.despiece, COUNT(dc.hoja_id) AS registrados
							, tdop.pedido
							, h.impresion
							, tdop.cantidad AS cant_orden,tdop.bloqueado
					FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop
							, `familia` f, `tpropiedades` tp, `estilo` e, `tclips` tcli
							, `hoja` h LEFT JOIN `detalle_corte` dc ON h.hoja_id = dc.hoja_id AND dc.personal_id != 0
							, `tcolores` tco
					WHERE	top.orden_id = tdop.orden_id AND tdop.detalle_id = h.detalle_id
							AND tdop.familia_id = f.familia_id AND tdop.propiedad_id = tp.prop_id
							AND tp.clip_id = tcli.clip_id AND tp.color_id = tco.color_id
							AND tcli.estado = 1 AND f.estilo_id = e.estilo_id
							AND e.estado = 1 AND top.orden_id = ".$op_id."
							
							AND tdop.estado != 0
					GROUP BY h.hoja_id
					ORDER BY f.nombre_familia, e.nombre_estilo, tcli.descripcion, color, h.cantidad DESC
					";
					break;
				}
				case 2: {
					$consulta = "
					SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo
							, tcli.descripcion AS clip, tco.descripcion AS color
							, h.cantidad, h.hoja_id, h.corte, h.dividido, h.desbaste
							, h.sellado, h.planchado, tdop.despiece, COUNT(dc.hoja_id) AS registrados, tdop.pedido,tdop.bloqueado
					FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop
							, `familia` f, `tpropiedades` tp, `estilo` e, `tclips` tcli
							, `hoja` h LEFT JOIN `detalle_dividido` dc ON h.hoja_id = dc.hoja_id AND dc.personal_id != 0
							, `tcolores` tco
					WHERE	top.orden_id = tdop.orden_id AND tdop.detalle_id = h.detalle_id
							AND tdop.familia_id = f.familia_id AND tdop.propiedad_id = tp.prop_id
							AND tp.clip_id = tcli.clip_id AND tp.color_id = tco.color_id
							AND tcli.estado = 1 AND f.estilo_id = e.estilo_id
							AND e.estado = 1 AND top.orden_id = ".$op_id."
							
							AND tdop.estado != 0
					GROUP BY h.hoja_id
					ORDER BY f.nombre_familia, e.nombre_estilo, tcli.descripcion, color, h.cantidad DESC
					";
					break;
				}
				case 3: {
					$consulta = "
					SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo
							, tcli.descripcion AS clip, tco.descripcion AS color
							, h.cantidad, h.hoja_id, h.corte, h.dividido, h.desbaste
							, h.sellado, h.planchado, tdop.despiece, COUNT(dc.hoja_id) AS registrados, tdop.pedido,tdop.bloqueado
					FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop
							, `familia` f, `tpropiedades` tp, `estilo` e, `tclips` tcli
							, `hoja` h LEFT JOIN `detalle_desbaste` dc ON h.hoja_id = dc.hoja_id AND dc.personal_id != 0
							, `tcolores` tco
					WHERE	top.orden_id = tdop.orden_id AND tdop.detalle_id = h.detalle_id
							AND tdop.familia_id = f.familia_id AND tdop.propiedad_id = tp.prop_id
							AND tp.clip_id = tcli.clip_id AND tp.color_id = tco.color_id
							AND tcli.estado = 1 AND f.estilo_id = e.estilo_id
							AND e.estado = 1 AND top.orden_id = ".$op_id."
							
							AND tdop.estado != 0
					GROUP BY h.hoja_id
					ORDER BY f.nombre_familia, e.nombre_estilo, tcli.descripcion, color, h.cantidad DESC
					";
					break;
				}
				case 4: {
					$consulta = "
					SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo
							, tcli.descripcion AS clip, tco.descripcion AS color
							, h.cantidad, h.hoja_id, h.corte, h.dividido, h.desbaste
							, h.sellado, h.planchado, tdop.despiece, COUNT(dc.hoja_id) AS registrados, tdop.pedido,tdop.bloqueado
					FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop
							, `familia` f, `tpropiedades` tp, `estilo` e, `tclips` tcli
							, `hoja` h LEFT JOIN `detalle_sellado` dc ON h.hoja_id = dc.hoja_id AND dc.personal_id != 0
							, `tcolores` tco
					WHERE	top.orden_id = tdop.orden_id AND tdop.detalle_id = h.detalle_id
							AND tdop.familia_id = f.familia_id AND tdop.propiedad_id = tp.prop_id
							AND tp.clip_id = tcli.clip_id AND tp.color_id = tco.color_id
							AND tcli.estado = 1 AND f.estilo_id = e.estilo_id
							AND e.estado = 1 AND top.orden_id = ".$op_id."
							
							AND tdop.estado != 0
					GROUP BY h.hoja_id
					ORDER BY f.nombre_familia, e.nombre_estilo, tcli.descripcion, color, h.cantidad DESC
					";
					break;
				}
				case 5: {
					$consulta = "
					SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo
							, tcli.descripcion AS clip, tco.descripcion AS color
							, h.cantidad, h.hoja_id, h.corte, h.dividido, h.desbaste
							, h.sellado, h.planchado, tdop.despiece, COUNT(dc.hoja_id) AS registrados, tdop.pedido,tdop.bloqueado
					FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop
							, `familia` f, `tpropiedades` tp, `estilo` e, `tclips` tcli
							, `hoja` h LEFT JOIN `detalle_planchado` dc ON h.hoja_id = dc.hoja_id AND dc.personal_id != 0
							, `tcolores` tco
					WHERE	top.orden_id = tdop.orden_id AND tdop.detalle_id = h.detalle_id
							AND tdop.familia_id = f.familia_id AND tdop.propiedad_id = tp.prop_id
							AND tp.clip_id = tcli.clip_id AND tp.color_id = tco.color_id
							AND tcli.estado = 1 AND f.estilo_id = e.estilo_id
							AND e.estado = 1 AND top.orden_id = ".$op_id."
							
							AND tdop.estado != 0
					GROUP BY h.hoja_id
					ORDER BY f.nombre_familia, e.nombre_estilo, tcli.descripcion, color, h.cantidad DESC
					";
					break;
				}
			}

			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle orden inicial- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				$numero_de_campos = 5;//no se considera entrega
				
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["detalle_id"] = $row['detalle_id'];
					$respuesta[$contador]["nombre_familia"] = $row['nombre_familia'];
					$respuesta[$contador]["nombre_estilo"] = $row['nombre_estilo'];
					$respuesta[$contador]["clip"] = $row['clip'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["hoja_id"] = $row['hoja_id'];
					$respuesta[$contador]["iguales"] = $row['nombre_familia']." ".$row['nombre_estilo'];
					
					$valor = $row['corte'] + $row['dividido'] + $row['desbaste'] + $row['sellado'] + $row['planchado'];
					if ($valor == $numero_de_campos){
						$respuesta[$contador]["completo"] = 1;
					} else {
						$respuesta[$contador]["completo"] = 0;
					}
					
					$respuesta[$contador]["despiece"] = $row['despiece'];
					$respuesta[$contador]["registrados"] = $row['registrados'];
					$respuesta[$contador]["pedido"] = $row['pedido'];
					
					$respuesta[$contador]["impresion"] = $row['impresion'];
					$respuesta[$contador]["cant_orden"] = $row['cant_orden'];
					$respuesta[$contador]["bloqueado"] = $row['bloqueado'];
					
					//////////////////////////////////////////////
					if ($clave == 1) {
						$sumar[$row['detalle_id']] +=  $row['cantidad'];
					}
					//////////////////////////////////////////////
					$contador = $contador + 1;
				}
				
				//////////////////////////////////////////////
				if ($clave == 1) {
					foreach ($respuesta as $key => $value) {
						if ($respuesta[$key]["cant_orden"] != $sumar[$respuesta[$key]["detalle_id"]])
							$respuesta[$key]["marcador"] = "mal";
					}
				}
				//////////////////////////////////////////////
				
				return $respuesta;
			}
		 }
	}
	
	//eliminamos una hoja determinada
	function eliminar_hoja($h_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE FROM `hoja`
			WHERE		hoja_id = ".$h_id;
			
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -eliminar hoja- fall&oacute;: ' . mysql_error());
		}
	}
	
	//insertar dos cantidades en la hoja
	function insertar_hoja($did, $cantidad1, $cantidad2){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO	hoja (detalle_id, cantidad) 
			VALUES		($did,".$cantidad1."),
						($did,".$cantidad2.")";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar hoja- fall&oacute;: ' . mysql_error());			
		}
	}
	
	//encabezado para el detalle en la division
	function detalle_division($h_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo, tcli.descripcion AS clip,
					h.cantidad, h.hoja_id
			FROM	`tordenesproduccion` top, `tdetalleordenesproduccion` tdop, `familia` f,
					`tpropiedades` tp, `estilo` e, `tclips` tcli, `hoja` h
			WHERE	top.orden_id = tdop.orden_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tdop.familia_id = f.familia_id AND
					tdop.propiedad_id = tp.prop_id AND
					
					tp.clip_id = tcli.clip_id AND
					tcli.estado = 1 AND
					
					f.estilo_id = e.estilo_id AND
					e.estado = 1 AND
					
					h.hoja_id = ".$h_id."
					
					ORDER by f.nombre_familia, e.nombre_estilo, tcli.descripcion, tdop.cantidad DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle division- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;

				while($row = mysql_fetch_array($resultado)){
				  $respuesta[$contador]["detalle_id"] = $row[0];
				  $respuesta[$contador]["nombre_familia"] = $row[1];
				  $respuesta[$contador]["nombre_estilo"] = $row[2];
				  $respuesta[$contador]["clip"] = $row[3];
				  $respuesta[$contador]["cantidad"] = $row[4];
				  $respuesta[$contador]["hoja_id"] = $row[5];
				  $contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	//resumen del despiece
	function resumen_del_despiece($h_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	CONCAT(tma.nombre, ' ', tma.descripcion) AS nombre
					, tcom.cantidad AS cantidad
					, CONCAT(tpp.descripcion , ' (',tdi.largo,' x ',tdi.ancho,')' ) AS posicion
					, tmt.tipo_material_id
			FROM `tcomponentes` tcom
				 , `tmateriales` tma LEFT JOIN `tpiezas` tpi ON tma.material_id=tpi.material_id LEFT JOIN `tposicion_pieza` tpp ON tpi.posicion_pieza_id = tpp.posicion_pieza_id LEFT JOIN `tdimensiones` tdi ON tpi.dimension_id = tdi.dimension_id
				 , `tipo_material` tmt
				 , `hoja` h
				 , `tdetalleordenesproduccion` tdop
			WHERE tcom.pieza_id = tpi.pieza_id
				AND tma.tipo_material_id = tmt.tipo_material_id
				AND tmt.tipo_material_id != -1
				AND tcom.detalle_id = tdop.detalle_id
				AND tdop.detalle_id = h.detalle_id
				AND tcom.cantidad >= 1
				AND h.hoja_id = ".$h_id."
			ORDER BY nombre, cantidad, posicion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -resumen del despiece 1-fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;

				while($row = mysql_fetch_array($resultado)){
				  $respuesta[$contador]["nombre"] = $row['nombre'];
				  $respuesta[$contador]["cantidad"] = $row['cantidad'];
				  $respuesta[$contador]["posicion"] = $row['posicion'];
				  $respuesta[$contador]["tipo_material"] = $row['tipo_material_id'];
				  $contador = $contador + 1;
				}
			}
			
			$consulta = "
			SELECT	CONCAT(tma.nombre, ' ', tma.descripcion) AS nombre, tcom.cantidad AS cantidad, 
					CONCAT(tpp.descripcion , ' (',tdi.largo,' x ',tdi.ancho,')' ) AS posicion,
					tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt, `hoja` h,
					`tdetalleordenesproduccion` tdop, `tposicion_pieza` tpp, `tdimensiones` tdi
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tpi.posicion_pieza_id = tpp.posicion_pieza_id AND
					tpi.dimension_id = tdi.dimension_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					(tmt.tipo_material_id = 3 or tmt.tipo_material_id = 2) AND
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tcom.cantidad < 1 AND
					
					h.hoja_id = ".$h_id."
					
					ORDER BY nombre, cantidad, posicion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -resumen del despiece 2- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
				  $respuesta[$contador]["nombre"] = $row['nombre'];
				  $respuesta[$contador]["cantidad"] = $row['cantidad'];
				  $respuesta[$contador]["posicion"] = $row['posicion'];
				  $respuesta[$contador]["tipo_material"] = $row['tipo_material_id'];
				  $contador = $contador + 1;
				}
			}
			return $respuesta;
		 }
	}




//******************** funciones generales ********************//

	//averiguar si existe esta hoja funcion general
	function averiguar_detalle_corte($hid, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//una consulta diferente para cada proceso
			$consulta = "
			SELECT	dc.detalle_".$tabla."_id 
			FROM	`detalle_".$tabla."` dc 
			WHERE	dc.hoja_id = ".$hid;
			//echo "<br>La consulta: ".$consulta;

			$resultado = mysql_query($consulta) or die('La consulta -averiguar detalle corte- fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$contador = 0;
		
				while($row = mysql_fetch_array($resultado)){
				  $respuesta[$contador]["detalle_corte_id"] = $row[0];
				  $contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

	//insertar detalle funcion general
	function insertar_tipo_detalle_corte($hid, $proceso){
		switch ($proceso){
			case 1:{
				//corte
				$this->detalle_corte($hid);
				break;
			}
			case 2:{
				//dividido
				$this->detalle_dividido($hid);
				break;
			}
			case 3:{
				//desbaste
				$this->detalle_desbaste($hid);
				break;
			}
			case 4:{
				//desbaste
				$this->detalle_sellado($hid);
				break;
			}
			case 5:{
				//desbaste
				$this->detalle_planchado($hid);
				break;
			}
		}
	}

	//mostrar el detalle funcion general
	function mostrar_tipo_detalle_corte($hid, $tabla){
		//echo "<br>proceso: ".$proceso;
		if ($tabla == 'corte'){
			return $this->sacar_detalle_corte($hid);
		} else {
			return $this->sacar_detalle_dividido($hid, $tabla);
		}
	}

	//eliminar un detalle de la asignacion funcion general
	function eliminar_detalle_corte($dcid, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE FROM	detalle_".$tabla." 
			WHERE		detalle_".$tabla."_id = ".$dcid;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -eliminar detalle corte- fall&oacute;: ' . mysql_error());
		 }
	}

	//eliminamos una registro de la asignacion funcion general
	function eliminar_detalle_detalle_corte($dcid, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE FROM	`detalle_".$tabla."` 
			WHERE		detalle_".$tabla."_id = ".$dcid;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -eliminar detalle detalle corte- fall&oacute;: ' . mysql_error());
		}
	}
	
////////////////////////////////////////////////////


	//funcion para insertar datos en la tabla detalle_corte
	function detalle_corte($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//tmt.tipo_material_id != 3
			$consulta = "
				SELECT tmt.descripcion
					   , SUM(tcom.cantidad) AS cantidad
					   , 1 AS golpe, tmt.tipo_material_id
				FROM `tcomponentes` tcom
					 , `tmateriales` tma LEFT JOIN `tpiezas` tpi ON tma.material_id = tpi.material_id
					 , `tipo_material` tmt
					 , `hoja` h
					 , `tdetalleordenesproduccion` tdop
				WHERE	tcom.pieza_id = tpi.pieza_id  AND
						tpi.material_id = tma.material_id AND
						tma.tipo_material_id = tmt.tipo_material_id AND
						tmt.tipo_material_id != -1 AND
						
						tcom.detalle_id = tdop.detalle_id AND
						tdop.detalle_id = h.detalle_id AND
						
						tcom.cantidad >= 1 AND
						
						h.hoja_id = ".$hid."
						
						GROUP BY tmt.tipo_material_id
						ORDER BY tmt.descripcion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle corte 1- fall&oacute;: ' . mysql_error());
			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else {
			
				while($row = mysql_fetch_array($resultado)){
					$apodo = ereg_replace(' ','',$row[0]);
					$apodo = ereg_replace('\.','',$apodo);
					
					$consulta = "
					INSERT INTO	`detalle_corte` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`golpe`,`hoja_id`)
					VALUES		(".$row[3].",'".$apodo."',1,".$row[1].",".$row[2].",".$hid.")
					";
					//echo "<br>La consulta: ".$consulta;
					mysql_query($consulta) or die('La consulta -detalle insert corte- fall&oacute;: ' . mysql_error());
				}
			}
			
			$cod = 1;
			$consulta = "
			SELECT	tmt.descripcion, COUNT(*) AS cantidad, tcom.cantidad AS golpe, tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`hoja` h, `tdetalleordenesproduccion` tdop
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					(tmt.tipo_material_id = 3 or tmt.tipo_material_id = 2)AND
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tcom.cantidad < 1 AND
					
					h.hoja_id = ".$hid."
					
					GROUP BY tma.nombre, tcom.cantidad
					ORDER BY tma.nombre
			";
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle corte 2- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$cod ++;
					
					$apodo = ereg_replace(' ','',$row[0]);
					$apodo = ereg_replace('\.','',$apodo);
					
					$golpe = round(1 / $row[2]);
					
					$consulta = "
					INSERT INTO	`detalle_corte` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`golpe`,`hoja_id`)
					VALUES		(".$row[3].",'".$apodo."',".$cod.",".$row[1].",".$golpe.",".$hid.")
					";
					mysql_query($consulta) or die('La consulta -detalle insert 2- fall&oacute;: ' . mysql_error());
				}
			}		
		}
	}
	//fin insertar detalle_corte
	
	//armar la matriz de elementos de CORTE que se muestra en la asignacion
	function sacar_detalle_corte($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dc.detalle_corte_id, tmt.descripcion AS nombre, dc.cantidad, dc.golpe,
					CONCAT(dc.apodo, dc.codigo) as apodo, dc.personal_id, dc.fecha_hora_ini,
					dc.fecha_hora_fin, p.apellidos, p.nombres
			FROM	`tipo_material` tmt, `detalle_corte` dc left join `personal` p ON dc.personal_id = p.personal_id
			WHERE	dc.tipo_material_id = tmt.tipo_material_id AND
					dc.hoja_id = ".$hid."
					
			ORDER BY tmt.ordenar DESC, tmt.descripcion ASC, dc.cantidad DESC, dc.golpe DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -sacar detalle corte- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
		
				while($row = mysql_fetch_array($resultado)){
					$dcid[$contador] = $row[0];
					$nombre[$contador] = $row[1];
					$cantidad[$contador] = $row[2];
					$golpe [$contador] = $row[3];
					$apodo[$contador] = $row[4];
					$personal_id[$contador] = $row[5];
					$fhini[$contador] = $row[6];
					$fhfin[$contador] = $row[7];
					$completo[$contador] = trim($row[8]).' '.trim($row[9]);
					
					$contador = $contador + 1;
				}
			
			return $respuesta = array ("dcid" => $dcid, "nombre" => $nombre, "cantidad" => $cantidad, "golpe" => $golpe, "apodo" => $apodo, "personal_id" => $personal_id, "fhini" => $fhini, "fhfin" => $fhfin, "completo" => $completo);	
			}
		 }
	}


	//para imprimir detalle de CORTE
	function sacar_detalle_corte1($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dc.detalle_corte_id, tmt.descripcion AS nombre, dc.cantidad, dc.golpe,
					CONCAT(dc.apodo, dc.codigo) as apodo, dc.personal_id, dc.fecha_hora_ini,
					dc.fecha_hora_fin, p.apellidos, p.nombres
			FROM	`tipo_material` tmt, `detalle_corte` dc left join `personal` p ON dc.personal_id = p.personal_id
			WHERE	dc.tipo_material_id = tmt.tipo_material_id AND
					dc.hoja_id = ".$hid."
					
			ORDER BY tmt.ordenar DESC, tmt.descripcion ASC, dc.cantidad DESC, dc.golpe DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -sacar detalle corte 1- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
		
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['completo'] = $row['apellidos'].' '.$row['nombres'];
					$respuesta[$contador]['nombre'] = $row['nombre'];
					$respuesta[$contador]['sis_cantidad'] = $row['cantidad'];
					$respuesta[$contador]['sis_golpe'] = $row['golpe'];
					$respuesta[$contador]['fecha_hora_ini'] = $row['fecha_hora_ini'];
				
					$contador = $contador + 1;
				}
			
			return $respuesta;	
			}
		 }
	}
	
	//sacar el detalle del detalle del corte
	function detalle_detalle_corte($dcid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dc.tipo_material_id, dc.apodo, dc.cantidad, dc.golpe, dc.hoja_id, tmt.descripcion AS nombre
			FROM	`detalle_corte` dc, `tipo_material` tmt
			WHERE	dc.tipo_material_id = tmt.tipo_material_id AND
					dc.detalle_corte_id = ".$dcid;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle detalle corte - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta[0]['tipo_material_id'] = $row['tipo_material_id'];
					$respuesta[0]['apodo'] = $row['apodo'];
					$respuesta[0]['cantidad'] = $row['cantidad'];
					$respuesta[0]['golpe'] = $row['golpe'];
					$respuesta[0]['hoja_id'] = $row['hoja_id'];
					$respuesta[0]['nombre'] = $row['nombre'];
				}
				return $respuesta;
			}
		 }
	}
	
	//insertar dos cantidades en la hoja
	function insertar_detalle_detalle_corte($detalle, $cantidad1, $cantidad2){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
				INSERT INTO detalle_corte (tipo_material_id, apodo, cantidad, golpe, hoja_id)
				VALUES		(".$detalle['tipo_material_id'].",'".$detalle['apodo']."',".$cantidad1.",".$detalle['golpe'].",".$detalle['hoja_id']."),
							(".$detalle['tipo_material_id'].",'".$detalle['apodo']."',".$cantidad2.",".$detalle['golpe'].",".$detalle['hoja_id'].")";
			//echo "<br>La consulta: ".$consulta;
			mysql_query($consulta) or die('La consulta -insertar detalle detalle corte insert- fall&oacute;: ' . mysql_error());			
			
			$consulta = "
				SELECT	detalle_corte_id, CONCAT(dc.apodo, dc.codigo) as apodo
				FROM	detalle_corte dc
				WHERE	tipo_material_id = ".$detalle['tipo_material_id']." AND
						hoja_id = ".$detalle['hoja_id']."
				ORDER BY cantidad DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar detalle detalle corte select- fall&oacute;: ' . mysql_error());			
			
			$contador = 1;
			while($row = mysql_fetch_array($resultado)){
				$SQL = "
					UPDATE	detalle_corte 
					SET		codigo = ".$contador." 
					WHERE	detalle_Corte_id = ".$row[0];
				//echo "<br>La SQL: ".$SQL;
				mysql_query($SQL) or die('La consulta -insertar detalle detalle corte update- fall&oacute;: ' . mysql_error());
				
				$respuesta[$contador] = $row[1];

				$contador = $contador + 1;
			}
			return $respuesta;
		}
	}


	//modificar las cantidades del detalle de corte y los demas
	function guardar_parcial_detalle_corte($detalle_corte_id, $cantidad, $golpe, $personal_id, $fecha_hora_ini, $fecha_hora_fin, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			if ($tabla == 'corte'){
				$consulta = "
					UPDATE	detalle_corte
					SET		cantidad = ".$cantidad.",
							golpe = ".$golpe.",
							personal_id = ".$personal_id.",
							fecha_hora_ini = CURRENT_TIMESTAMP(),
							fecha_hora_fin = CURRENT_TIMESTAMP(),
							completo = 1
					WHERE	(	cantidad != ".$cantidad." OR
								golpe != ".$golpe." OR
								personal_id != ".$personal_id."
							) AND
							detalle_corte_id = ". $detalle_corte_id;
			} else {
				$consulta = "
					UPDATE	detalle_".$tabla."
					SET		cantidad = ".$cantidad.",
							personal_id = ".$personal_id.",
							fecha_hora_ini = CURRENT_TIMESTAMP(),
							fecha_hora_fin = CURRENT_TIMESTAMP(),
							completo = 1
					WHERE	(	cantidad != ".$cantidad." OR
								personal_id != ".$personal_id."
							) AND
							detalle_".$tabla."_id = ". $detalle_corte_id;
			}

			//echo "<br>La consulta detalle corte: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -guardar parcial detalle corte- fall&oacute;: ' . mysql_error());
		 }
	}


	//modificar solo el nombre cuando falla funcion general
	function guardar_parcial_solo_nombre_corte($detalle_corte_id, $personal_id, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
				UPDATE	detalle_".$tabla."
				SET		personal_id = ".$personal_id.", completo = 1
				WHERE	detalle_".$tabla."_id = ". $detalle_corte_id;
			//echo "<br>La consulta nombre corte: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -guardar parcial solo nombre corte- fall&oacute;: ' . mysql_error());
		 }
	}
	
	//comprobar para imprimir funcion general
	function comprobar_detalle_corte($hid, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
				SELECT	COUNT(*), SUM(dc.completo)
				FROM	`detalle_".$tabla."` dc
				WHERE	dc.hoja_id = ".$hid;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -comprobar detalle corte- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					if ($row[0] == $row[1])
						return true;
					else
						return false;
				}
			}
		}
	}

	//actualizar si la hoja esta completa funcion general
	function actualizar_completo_hoja($hid, $campo){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "
				UPDATE	hoja
				SET		".$campo." = 1
				WHERE hoja_id = ". $hid;
				
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -actualizar completo hoja- fall&oacute;: ' . mysql_error());
		 }
	}
	
	//verififamos si una asignacion determinada ha sido completada
	function verificar_impresion_hoja($hid, $campo){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	hoja_id
			FROM	hoja
			WHERE	hoja_id = ".$hid." AND ".$campo." = 1
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -verificar impresion hoja- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta['hoja_id'] = $row[0];
				}
				return $respuesta;
			}
		}
	}
	
	//para obtener la cabecera del reporte
	function cabecera_reporte($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	top.cup_num, f.nombre_familia, cu.descripcion AS cuero
					, cli.descripcion AS clip, es.nombre_estilo AS tipo
					, tch.descripcion AS chapa, tcol.descripcion AS color
					, tdop.observacion, h.cantidad, tdop.grabado, tdop.lugargrabado
					, tdop.tipoletra, tdop.instructivo_id,tdop.pedido,tdop.obs_interior
			FROM	`hoja` h, `tdetalleordenesproduccion` tdop, `tordenesproduccion` top, `tpropiedades` tp, familia f,
					`tcueros` cu, `tclips` cli, `estilo` es, `tchapas` tch, `tcolores` tcol
			WHERE	h.detalle_id = tdop.detalle_id
                    AND tdop.orden_id = top.orden_id
                    AND tdop.propiedad_id = tp.prop_id
                    AND tdop.familia_id = f.familia_id
                    AND tp.cuero_id = cu.cuero_id
                    AND tp.clip_id = cli.clip_id
                    AND f.estilo_id = es.estilo_id
                    AND tp.sello_id = tch.chapa_id
                    AND tp.color_id = tcol.color_id
                    AND h.hoja_id  = ".$hid;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cabecera reporte- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta['cup_num'] = $row[0];
					$respuesta['nombre_familia'] = $row[1];
					$respuesta['cuero'] = $row[2];
					$respuesta['clip'] = $row[3];
					$respuesta['tipo'] = $row[4];
					$respuesta['chapa'] = $row[5];
					$respuesta['color'] = $row[6];
					
					if ($row['tipoletra'] != null){
						$row['tipoletra'] = "(".$row['tipoletra'].")";
					}
					
					$respuesta['observacion'] = $row['grabado']." ".$row['tipoletra']." ".$row[7];
					$respuesta['cantidad'] = $row[8];
					$respuesta['fecha'] = date('Y-m-d');
					$respuesta['lugargrabado'] = $row['lugargrabado'];
					$respuesta['pedido'] = $row['pedido'];
					$respuesta['instructivo_id'] = $row['instructivo_id'];
					$respuesta['obs_interior'] = $row['obs_interior'];
				}
				return $respuesta;
			}
		}
	}
	
	//saca los datos de la cabecera
	function cabecera_hoja($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
				  tdop.detalle_id
				, f.nombre_familia
				, e.nombre_estilo
				, tcli.descripcion AS clip
				, h.cantidad
				, h.hoja_id
				, ti.url
				, ti.instructivo_id
			FROM
				 `tordenesproduccion` top
				 , `tdetalleordenesproduccion` tdop LEFT JOIN `tinstructivos` ti ON tdop.instructivo_id = ti.instructivo_id
				 , `familia` f
				 , `tpropiedades` tp
				 , `estilo` e
				 , `tclips` tcli
				 , `hoja` h
			WHERE
					 top.orden_id = tdop.orden_id
				 AND tdop.detalle_id = h.detalle_id
				 AND tdop.familia_id = f.familia_id
				 AND tdop.propiedad_id = tp.prop_id
				 AND tp.clip_id = tcli.clip_id
				 AND tcli.estado = 1
				 AND f.estilo_id = e.estilo_id
				 AND e.estado = 1
				 AND h.hoja_id = ".$hid."
				 ORDER by f.nombre_familia, e.nombre_estilo, tcli.descripcion, h.cantidad DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -cabecera hoja- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
				  $respuesta[0]["detalle_id"] = $row['detalle_id'];
				  $respuesta[0]["nombre_familia"] = $row['nombre_familia'];
				  $respuesta[0]["nombre_estilo"] = $row['nombre_estilo'];
				  $respuesta[0]["clip"] = $row['clip'];
				  $respuesta[0]["cantidad"] = $row['cantidad'];
				  $respuesta[0]["url"] = $row['url'];
				  $respuesta[0]["instructivo_id"] = $row['instructivo_id'];
				}
				return $respuesta;
			}
		}
	}
    
        ////////////////////////////////////////////////////////////////SEPARANDO instructivo
      /*Devuelve el instructivo asignado para esta familia */
	function instructivo_asignado($hid)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta= "SELECT	i.instructivo_id as instructivo
			FROM	`hoja` h, `tdetalleordenesproduccion` tdop, `tordenesproduccion` top,familia f,`tinstructivos` as i
			WHERE	h.detalle_id = tdop.detalle_id
                    AND tdop.orden_id = top.orden_id
                    AND tdop.familia_id = f.familia_id
                    AND i.familia_id=f.familia_id
                    AND h.hoja_id  =".$hid;
			//echo $consulta; 
		
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
        	
		if (!$resultado) return false;
			else
			{    
            	while($row = mysql_fetch_array($resultado))
				{
							$respuesta["nro_instructivo"]= $row['instructivo'];
				}
				return $respuesta;
			}
        }
    }
    ////////////////////////////////////////////////////////////////SEPARANDO instructivo

	//dado un id obtiene el nombre de un operario para realizar la asignacion
	function obtener_operario($personal_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	CONCAT(p.apellidos,' ', p.nombres) AS completo
			FROM	`personal` p
			WHERE	p.personal_id = ".$personal_id;
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -obtener operario- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
				  $respuesta = $row['completo'];
				}
				return $respuesta;
			}
		}
	}

//******************** funciones generales basadas en dividido ********************//

	//especificacion detalle dividido
	function detalle_dividido($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//tmt.tipo_material_id != 3
			//el codigo del carton es 3 y no se toma en cuenta
			$consulta = "
			SELECT	tmt.descripcion, SUM(tcom.cantidad) AS cantidad, tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`hoja` h, `tdetalleordenesproduccion` tdop
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					tmt.dividido = 1 AND
					
					tmt.tipo_material_id != 3 AND
					
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tcom.cantidad >= 1 AND
					
					h.hoja_id = ".$hid."
					
					GROUP BY tmt.tipo_material_id
					ORDER BY tmt.descripcion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle dividido select- fall&oacute;: ' . mysql_error());
			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else {
			
				while($row = mysql_fetch_array($resultado)){
					$apodo = ereg_replace(' ','',$row['descripcion']);
					$apodo = ereg_replace('\.','',$apodo);
					
					$consulta = "
					INSERT INTO `detalle_dividido` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`hoja_id`)
					VALUES (".$row['tipo_material_id'].",'".$apodo."',1,".$row['cantidad'].",".$hid.")
					";
					//echo "<br>La consulta: ".$consulta;
					mysql_query($consulta) or die('La consulta -detalle dividido insert- fall&oacute;: ' . mysql_error());
				}
			}
			
			$cod = 1;
			$consulta = "
			SELECT	tmt.descripcion, COUNT(*) AS cantidad, tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`hoja` h, `tdetalleordenesproduccion` tdop
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					tmt.dividido = 1 AND
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tcom.cantidad < 1 AND
					
					h.hoja_id = ".$hid."
					
					GROUP BY tma.nombre
					ORDER BY tma.nombre
			";
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle dividido select 1- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$cod ++;
					
					$apodo = ereg_replace(' ','',$row['descripcion']);
					$apodo = ereg_replace('\.','',$apodo);
					
					$golpe = round(1 / $row[2]);
					
					$consulta = "
					INSERT INTO `detalle_dividido` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`hoja_id`)
					VALUES (".$row['tipo_material_id'].",'".$apodo."',".$cod.",".$row['cantidad'].",".$hid.")
					";
					mysql_query($consulta) or die('La consulta -detalle dividido insert 1- fall&oacute;: ' . mysql_error());
				}
			}		
		}
	}
	//fin especificacion detalle dividido
	
	//especificacion detalle desbaste
	function detalle_desbaste($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tmt.descripcion, SUM(tcom.cantidad) AS cantidad, tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`hoja` h, `tdetalleordenesproduccion` tdop
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					tmt.dividido = 1 AND
					
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tcom.cantidad >= 1 AND
					
					h.hoja_id = ".$hid."
					
					GROUP BY tmt.tipo_material_id
					ORDER BY tmt.descripcion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle desbaste- fall&oacute;: ' . mysql_error());
			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else {
			
				while($row = mysql_fetch_array($resultado)){
					$apodo = ereg_replace(' ','',$row['descripcion']);
					$apodo = ereg_replace('\.','',$apodo);
					
					$consulta = "
					INSERT INTO `detalle_desbaste` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`hoja_id`)
					VALUES (".$row['tipo_material_id'].",'".$apodo."',1,".$row['cantidad'].",".$hid.")
					";
					//echo "<br>La consulta: ".$consulta;
					mysql_query($consulta) or die('La consulta -detalle desbaste insert- fall&oacute;: ' . mysql_error());
				}
			}
		}
	}
	//fin especificacion detalle desbaste


	//especificacion detalle sellado
	function detalle_sellado($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tmt.descripcion, SUM(tcom.cantidad) AS cantidad, tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`hoja` h, `tdetalleordenesproduccion` tdop
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					tmt.dividido = 1 AND
					tmt.tipo_material_id != 2 AND
					tmt.tipo_material_id != 3 AND
					
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					tcom.cantidad >= 1 AND
					
					h.hoja_id = ".$hid."
					
					GROUP BY tmt.tipo_material_id
					ORDER BY tmt.descripcion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle sellado select- fall&oacute;: ' . mysql_error());
			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else {
			
				while($row = mysql_fetch_array($resultado)){
					$apodo = ereg_replace(' ','',$row['descripcion']);
					$apodo = ereg_replace('\.','',$apodo);
					
					$consulta = "
					INSERT INTO `detalle_sellado` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`hoja_id`)
					VALUES (".$row['tipo_material_id'].",'".$apodo."',1,".$row['cantidad'].",".$hid.")
					";
					//echo "<br>La consulta: ".$consulta;
					mysql_query($consulta) or die('La consulta -detalle sellado insert- fall&oacute;: ' . mysql_error());
				}
			}
		}
	}
	//fin especificacion detalle sellado

	//especificacion detalle planchado
	function detalle_planchado($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	tmt.descripcion, COUNT(tcom.cantidad) AS cantidad, tmt.tipo_material_id
			FROM	`tcomponentes` tcom, `tpiezas` tpi, `tmateriales` tma, `tipo_material` tmt,
					`hoja` h, `tdetalleordenesproduccion` tdop
			WHERE	tcom.pieza_id = tpi.pieza_id  AND
					tpi.material_id = tma.material_id AND
					tma.tipo_material_id = tmt.tipo_material_id AND
					tmt.dividido = 1 AND
				
					tcom.detalle_id = tdop.detalle_id AND
					tdop.detalle_id = h.detalle_id AND
					
					h.hoja_id = ".$hid."
					
					GROUP BY tmt.tipo_material_id
					ORDER BY tmt.descripcion
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle planchado- fall&oacute;: ' . mysql_error());
			
			$contador = 0;
			
			if (!$resultado)
				return false;
			else {
			
				while($row = mysql_fetch_array($resultado)){
					$apodo = ereg_replace(' ','',$row['descripcion']);
					$apodo = ereg_replace('\.','',$apodo);
					
					$consulta = "
					INSERT INTO `detalle_planchado` (`tipo_material_id`,`apodo`,`codigo`,`cantidad`,`hoja_id`)
					VALUES (".$row['tipo_material_id'].",'".$apodo."',1,".$row['cantidad'].",".$hid.")
					";
					//echo "<br>La consulta: ".$consulta;
					mysql_query($consulta) or die('La consulta -detalle planchado insert- fall&oacute;: ' . mysql_error());
				}
			}
		}
	}
	//fin especificacion detalle planchado


//*******************************************************//



	//sacar el detalle del dividido y los demas
	function sacar_detalle_dividido($hid, $nomtabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dc.detalle_".$nomtabla."_id, tmt.descripcion AS nombre, dc.cantidad,
					CONCAT(dc.apodo, dc.codigo) as apodo, dc.personal_id, dc.fecha_hora_ini,
					dc.fecha_hora_fin, p.apellidos, p.nombres
			FROM	`tipo_material` tmt,
					`detalle_".$nomtabla."` dc left join `personal` p ON dc.personal_id = p.personal_id
			WHERE	dc.tipo_material_id = tmt.tipo_material_id AND
					dc.hoja_id = ".$hid."
					
					ORDER BY tmt.ordenar DESC, tmt.descripcion ASC, dc.cantidad DESC
			";
			//echo "<br>sacar detalle dividido: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -sacar detalle dividido- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
		
				while($row = mysql_fetch_array($resultado)){
					$dcid[$contador] = $row['detalle_'.$nomtabla.'_id'];
					$nombre[$contador] = $row['nombre'];
					$cantidad[$contador] = $row['cantidad'];
					$apodo[$contador] = $row['apodo'];
					$personal_id[$contador] = $row['personal_id'];
					$fhini[$contador] = $row['fecha_hora_ini'];
					$fhfin[$contador] = $row['fecha_hora_fin'];
					$completo[$contador] = trim($row['apellidos']).' '.trim($row['nombres']);
					
					$contador = $contador + 1;
				}
			
			$respuesta = array ("dcid" => $dcid, "nombre" => $nombre, "cantidad" => $cantidad, "apodo" => $apodo, "personal_id" => $personal_id, "fhini" => $fhini, "fhfin" => $fhfin, "completo" => $completo);	
	
			return $respuesta;
			}
		}
	}
	
	//sacar el detalle del detalle de los demas
	function detalle_detalle_dividido($dcid, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dc.tipo_material_id, dc.apodo, dc.cantidad, dc.hoja_id, tmt.descripcion AS nombre
			FROM	`detalle_".$tabla."` dc, `tipo_material` tmt
			WHERE	dc.tipo_material_id = tmt.tipo_material_id AND
					dc.detalle_".$tabla."_id = ".$dcid;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle detalle dividido- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta[0]['tipo_material_id'] = $row['tipo_material_id'];
					$respuesta[0]['apodo'] = $row['apodo'];
					$respuesta[0]['cantidad'] = $row['cantidad'];
					$respuesta[0]['hoja_id'] = $row['hoja_id'];
					$respuesta[0]['nombre'] = $row['nombre'];
				}
				return $respuesta;
			}
		}
	}

	//insertar dos cantidades en la asignacion para los demas
	function insertar_detalle_detalle_dividido($detalle, $cantidad1, $cantidad2, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
				INSERT INTO detalle_".$tabla." (tipo_material_id, apodo, cantidad, hoja_id)
				VALUES
				(".$detalle['tipo_material_id'].",'".$detalle['apodo']."',".$cantidad1.",".$detalle['hoja_id']."),
				(".$detalle['tipo_material_id'].",'".$detalle['apodo']."',".$cantidad2.",".$detalle['hoja_id'].")";
			//echo "<br>La consulta: ".$consulta;
			mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());			
			
			$consulta = "
				SELECT	detalle_".$tabla."_id, CONCAT(dc.apodo, dc.codigo) as apodo
				FROM	detalle_".$tabla." dc
				WHERE	tipo_material_id = ".$detalle['tipo_material_id']." AND
						hoja_id = ".$detalle['hoja_id']."
						ORDER BY cantidad DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());			
			
			$contador = 1;
			while($row = mysql_fetch_array($resultado)){
				$SQL = "UPDATE detalle_".$tabla." SET codigo = ".$contador." WHERE detalle_".$tabla."_id = ".$row[0];
				//echo "<br>La SQL: ".$SQL;
				mysql_query($SQL) or die('La consulta -insertar detalle detalle dividido- fall&oacute;: ' . mysql_error());
				
				$respuesta[$contador] = $row[1];

				$contador = $contador + 1;
			}
			return $respuesta;
		}
	}

	//detalle para la impresion funcion general
	function sacar_detalle_dividido1($hid, $tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	dc.detalle_".$tabla."_id, CONCAT(p.apellidos, ' ', p.nombres) As nombre,
                    tmt.descripcion AS material, dc.cantidad,
                    dc.fecha_hora_ini, dc.fecha_hora_fin
			FROM	`tipo_material` tmt,
					`detalle_".$tabla."` dc left join `personal` p ON dc.personal_id = p.personal_id
			WHERE	dc.tipo_material_id = tmt.tipo_material_id AND
					dc.hoja_id = ".$hid."
					
					ORDER BY tmt.descripcion ASC, dc.cantidad DESC
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -sacar detalle dividido 1- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
		
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nombre'] = $row['nombre'];
					$respuesta[$contador]['material'] = $row['material'];
					$respuesta[$contador]['cantidad'] = $row['cantidad'];
					$respuesta[$contador]['fecha_hora_ini'] = $row['fecha_hora_ini'];
					$respuesta[$contador]['fecha_hora_fin'] = $row['fecha_hora_fin'];
				
					$contador = $contador + 1;
				}
			
			return $respuesta;	
			}
		}
	}

	//detalle para la impresion funcion general
	function verificar_especiales($grupousuario_id, $usuario_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	p.personal_id, CONCAT(p.apellidos, ' ', p.nombres) AS completo, p.clase
			FROM	`tusuarios` tu
					,`tgrupousuario` tg
					, `personal` p
			WHERE	tu.grupo_id = tg.grupousuario_id
					AND tu.personal_id = p.personal_id
					AND tg.grupousuario_id = ".$grupousuario_id."
					AND tu.usuario_id = ".$usuario_id."
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -verificar especiales- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if($row = mysql_fetch_array($resultado)){
					$respuesta['personal_id'] = $row['personal_id'];
					$respuesta['completo'] = $row['completo'];
					$respuesta['clase'] = $row['clase'];
					
					return $respuesta;	
				}
			}
		}
	}


	//eliminamos el despiece existente
	function vaciar_detalle_hoja($hid, $nombre_tabla){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			DELETE FROM ".$nombre_tabla."
			WHERE		hoja_id = ".$hid;
			
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -eliminar hoja- fall&oacute;: ' . mysql_error());
		}
	}
	
	
	//modificar impresion
	function modificar_impresion($hid){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	hoja
			SET		impresion = 1
			WHERE	hoja_id = ".$hid;
			
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -modificar impresion- fall&oacute;: ' . mysql_error());
		}
	}
 //*******************************************
    //METODOS CREADO PARA EL REPORTE DE RETACERIA 
    //*******************************************
    //consulta la lista de ordenes anual
	function consulta_lista_ordenes_anual($a�o){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	o.orden_id, o.num_orden, c.nombre AS cliente
            , o.fecha, o.fechaentrega, o.fecharepro, o.observacion
            , SUM(tdop.cantidad) AS cantidad
            , SUM(tdop.bloqueado) AS cancelados
            FROM	tordenesproduccion o , tclientes c, `tdetalleordenesproduccion` tdop 
            WHERE	c.cliente_id = o.cliente_id
            AND o.orden_id = tdop.orden_id
            GROUP BY o.orden_id
            ORDER BY o.fecha DESC, o.cup_num DESC
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
					$respuesta[$contador]["cancelados"] = $row['cancelados'];	

					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
    //metodo para registrar el cuero utilizado
    function ingresar_cueroutilizado($hoja_id,$orden_id,$fecha_corte, $cortador,$cuero_utilizado,$retaceria,$retazos,$desperdicio,$cuero_utilizado_pies)
	{
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			INSERT INTO `cuero_utilizado`
			( `hoja_id`,`orden_id`, `fecha_corte`, `cortador`, `cuero_utilizado`, `retaceria`, `retazos`, `desperdicio`, `cuero_utilizado_pies`)
			VALUES
			('".$hoja_id."','".$orden_id."','".$fecha_corte."', '".$cortador."', '".$cuero_utilizado."', '".$retaceria."','".$retazos."','".$desperdicio."', '".$cuero_utilizado_pies."')
			";
		//	echo $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_apertura- fall&oacute;: ' . mysql_error());
		}
	}
	//muestra el detalle de la orden, sacando los datos de la tabla hoja
	function detalle_orden_cuero($op_id,$detalles_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
					$consulta = "
                    SELECT	tdop.detalle_id, f.nombre_familia, e.nombre_estilo,top.orden_id
                    , tcuero.descripcion AS cuero, tco.descripcion AS color
                    , h.cantidad, h.hoja_id, tdop.pedido
                    , tdop.cantidad AS cant_orden,tdop.bloqueado
                    , tdop.orden_id
                    FROM `tordenesproduccion` top, `tdetalleordenesproduccion` tdop
                    , `familia` f, `tpropiedades` tp, `estilo` e, tcueros  tcuero, `hoja` h LEFT JOIN `detalle_corte` dc ON h.hoja_id = dc.hoja_id AND dc.personal_id != 0
                    , `tcolores` tco
                    WHERE	top.orden_id = tdop.orden_id 
                    AND tdop.detalle_id = h.detalle_id
                    AND tdop.familia_id = f.familia_id 
                    AND tdop.propiedad_id = tp.prop_id
                    AND tp.cuero_id = tcuero.cuero_id 
                    AND tp.color_id = tco.color_id 
                    AND f.estilo_id = e.estilo_id
                    AND e.estado = 1 
                    AND top.orden_id = ".$op_id."						
                    AND tdop.estado != 0
                    GROUP BY h.hoja_id
                    ORDER BY f.nombre_familia, e.nombre_estilo, tco.descripcion, h.cantidad DESC
					";


			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle orden inicial- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
				    
                    foreach($detalles_id as $key => $value)
                    {
                    if($row['hoja_id']==$value["hoja_id"])
                    {
                    $respuesta[$contador]["detalle_id"] = $row['detalle_id'];
					$respuesta[$contador]["nombre_familia"] = $row['nombre_familia'];
					$respuesta[$contador]["nombre_estilo"] = $row['nombre_estilo'];
                    $respuesta[$contador]["orden_id"] = $row['orden_id'];
					$respuesta[$contador]["cuero"] = $row['cuero'];
					$respuesta[$contador]["color"] = $row['color'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["hoja_id"] = $row['hoja_id'];
					$respuesta[$contador]["iguales"] = $row['nombre_familia']." ".$row['nombre_estilo'];
					
					$valor = $row['corte'] + $row['dividido'] + $row['desbaste'] + $row['sellado'] + $row['planchado'];
					if ($valor == $numero_de_campos){
						$respuesta[$contador]["completo"] = 1;
					} else {
						$respuesta[$contador]["completo"] = 0;
					}
					
					$respuesta[$contador]["despiece"] = $row['despiece'];
					$respuesta[$contador]["registrados"] = $row['registrados'];
					$respuesta[$contador]["pedido"] = $row['pedido'];

					$respuesta[$contador]["impresion"] = $row['impresion'];
					$respuesta[$contador]["cant_orden"] = $row['cant_orden'];
					$respuesta[$contador]["bloqueado"] = $row['bloqueado'];
					$contador = $contador + 1;
                        }
                    }	
				}
				return $respuesta;
			}
		 }
	}
    //verificando que no se hayan registrado 
    function verificando_registro($op_id)
    {
        $con = new DBmanejador;
		if($con->conectar() == true){
					$consulta = "
                    SELECT h.hoja_id 
                    FROM `hoja` as h,tdetalleordenesproduccion as tdo 
                    WHERE h.detalle_id=tdo.detalle_id 
                    AND tdo.orden_id=".$op_id." AND h.hoja_id 
                    NOT IN(select hoja_id from cuero_utilizado where orden_id=".$op_id.")
					";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -detalle orden inicial- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["hoja_id"] = $row['hoja_id'];
					$contador = $contador + 1;
				}				
				return $respuesta;
			}
		 }
    }
    //Lista de los elementos introducidos
    function lista_cuero_utilizados($orden_id)
    {
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			//$consulta= "SELECT * FROM `cuero_utilizado` as cu WHERE orden_id=".$orden_id." order by cu.hoja_id";
            $consulta="SELECT cu.hoja_id,tdo.pedido,cu.fecha_corte,concat(nombres,' ',apellidos) as cortador,f.nombre_familia,e.nombre_estilo,h.cantidad,tcu.descripcion as cuero,tco.descripcion as color,cu.cuero_utilizado, cu.retaceria,cu.retazos,cu.desperdicio,cu.cuero_utilizado_pies  
            FROM `cuero_utilizado` as cu,hoja as h,tdetalleordenesproduccion as tdo,personal as pe,familia as f,estilo as e,tpropiedades as tp,tcueros as tcu,tcolores as tco 
            WHERE tp.color_id =tco.color_id and tp.cuero_id=tcu.cuero_id and tp.prop_id=tdo.propiedad_id and f.estilo_id=e.estilo_id and tdo.familia_id=f.familia_id and pe.personal_id=cu.cortador and h.detalle_id=tdo.detalle_id   and cu.hoja_id =h.hoja_id and cu.orden_id=".$orden_id." 
            order by cu.hoja_id";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
					while($row = mysql_fetch_array($resultado))
					{
					$lista[$cont]['hoja_id'] = $row['hoja_id'];
                    $lista[$cont]['pedido'] = $row['pedido'];
                    $lista[$cont]['fecha_corte'] = $row['fecha_corte'];
                    $lista[$cont]['cortador'] = $row['cortador'];
                    $lista[$cont]['nombre_familia'] = $row['nombre_familia'];
                    $lista[$cont]['nombre_estilo'] = $row['nombre_estilo'];
                    $lista[$cont]['cantidad'] = $row['cantidad'];
                    $lista[$cont]['cuero'] = $row['cuero'];
                    $lista[$cont]['color'] = $row['color'];
					$lista[$cont]['cuero_utilizado'] = $row['cuero_utilizado'];
					$lista[$cont]['retaceria'] = $row['retaceria'];
					$lista[$cont]['retazos'] = $row['retazos'];
                    $lista[$cont]['desperdicio'] = $row['desperdicio'];
					$lista[$cont]['cuero_utilizado_pies'] = $row['cuero_utilizado_pies'];			
					$cont++;
					}
				return $lista;
			}
		
	   }
    }
    //*******************************************
    //FIN METODOS CUERO UTILIZADO 
    //*******************************************
}
?>