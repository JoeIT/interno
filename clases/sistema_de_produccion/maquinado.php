<?php
require_once('../../clases/includes/dbmanejador.php');

class Maquinado{
	function Maquinado(){
	}

	//sacar --reporte para corte
	function reporte_corte($fechaini, $fechafin){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	o.cup_num AS op
					, CONCAT(p.apellidos, ' ', p.nombres) AS cortador
					, f.nombre_familia AS producto
					, e.nombre_estilo AS tipo
					, h.cantidad AS cantidad
					, timat.descripcion AS material
					, dcor.cantidad AS pieza_c
					, dcor.golpe AS por_golpe
					, dcor.fecha_hora_ini
					, dcor.fecha_hora_fin
					, dcor.fecha_hora_fin-dcor.fecha_hora_ini AS diferencia
			FROM	`detalle_corte` dcor
					, `personal` p
					, `hoja` h
					, `tdetalleordenesproduccion` tdop
					, `familia` f
					, `estilo` e
					, `tipo_material` timat
					, `tordenesproduccion` o
			WHERE	dcor.personal_id = p.personal_id
					AND dcor.hoja_id = h.hoja_id
					AND h.detalle_id = tdop.detalle_id
					AND tdop.familia_id = f.familia_id
					AND f.estilo_id = e.estilo_id
					AND dcor.tipo_material_id = timat.tipo_material_id
					AND tdop.orden_id = o.orden_id
					
					AND dcor.fecha_hora_ini >= '".$fechaini."'
					AND dcor.fecha_hora_fin <= '".$fechafin."'
					AND dcor.personal_id != 0
			
			ORDER BY dcor.fecha_hora_fin DESC
			";
			
			//echo "<br>rcorte: ". $consulta;
			
			$resultado = mysql_query($consulta) or die ('La consulta -reporte corte- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["op"] = $row['op'];
					$respuesta[$contador]["cortador"] = $row['cortador'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["material"] = $row['material'];
					$respuesta[$contador]["pieza_c"] = $row['pieza_c'];
					$respuesta[$contador]["por_golpe"] = $row['por_golpe'];
					$respuesta[$contador]["fecha_hora_ini"] = $row['fecha_hora_ini'];
					$respuesta[$contador]["fecha_hora_fin"] = $row['fecha_hora_fin'];
					$respuesta[$contador]["diferencia"] = $row['diferencia'];
					
					$contador ++;
				}
				return $respuesta;
			}
		}
	}

	//sacar --reporte para dividido
	function reporte_varios($fechaini, $fechafin, $clave){
		$con = new DBmanejador;
		if($con->conectar() == true){
			switch ($clave){
				case 2: {
					//dividido
					$consulta = "
					SELECT	o.cup_num AS op
							, CONCAT(p.apellidos, ' ', p.nombres) AS cortador
							, f.nombre_familia AS producto
							, e.nombre_estilo AS tipo
							, h.cantidad AS cantidad
							, timat.descripcion AS material
							, ddiv.cantidad AS pieza_c
							, ddiv.fecha_hora_ini
							, ddiv.fecha_hora_fin
							, ddiv.fecha_hora_fin-ddiv.fecha_hora_ini AS diferencia
					FROM	`detalle_dividido` ddiv, `personal` p, `hoja` h
							, `tdetalleordenesproduccion` tdop, `familia` f
							, `estilo` e, `tipo_material` timat, `tordenesproduccion` o
					WHERE	ddiv.personal_id = p.personal_id
							AND ddiv.hoja_id = h.hoja_id
							AND h.detalle_id = tdop.detalle_id
							AND tdop.familia_id = f.familia_id
							AND f.estilo_id = e.estilo_id
							AND ddiv.tipo_material_id = timat.tipo_material_id
							AND tdop.orden_id = o.orden_id
							AND ddiv.fecha_hora_ini >= '".$fechaini."'
							AND ddiv.fecha_hora_fin <= '".$fechafin."'
							AND ddiv.personal_id != 0
					ORDER BY ddiv.fecha_hora_fin DESC
					";
					break;
				}
				case 3: {
					//desbaste
					$consulta = "
					SELECT	o.cup_num AS op
							, CONCAT(p.apellidos, ' ', p.nombres) AS cortador
							, f.nombre_familia AS producto
							, e.nombre_estilo AS tipo
							, h.cantidad AS cantidad
							, timat.descripcion AS material
							, ddes.cantidad AS pieza_c
							, ddes.fecha_hora_ini
							, ddes.fecha_hora_fin
							, ddes.fecha_hora_fin-ddes.fecha_hora_ini AS diferencia
					FROM	`detalle_desbaste` ddes, `personal` p, `hoja` h
							, `tdetalleordenesproduccion` tdop, `familia` f
							, `estilo` e, `tipo_material` timat, `tordenesproduccion` o
					WHERE	ddes.personal_id = p.personal_id
							AND ddes.hoja_id = h.hoja_id
							AND h.detalle_id = tdop.detalle_id
							AND tdop.familia_id = f.familia_id
							AND f.estilo_id = e.estilo_id
							AND ddes.tipo_material_id = timat.tipo_material_id
							AND tdop.orden_id = o.orden_id
							
							AND ddes.fecha_hora_ini >= '".$fechaini."'
							AND ddes.fecha_hora_fin <= '".$fechafin."'
							AND ddes.personal_id != 0
							
					ORDER BY ddes.fecha_hora_fin DESC
					";
					break;
				}
				case 4: {
					//sellado
					$consulta = "
					SELECT	o.cup_num AS op
							, CONCAT(p.apellidos, ' ', p.nombres) AS cortador
							, f.nombre_familia AS producto
							, e.nombre_estilo AS tipo
							, h.cantidad AS cantidad
							, timat.descripcion AS material
							, dsel.cantidad AS pieza_c
							, dsel.fecha_hora_ini
							, dsel.fecha_hora_fin
							, dsel.fecha_hora_fin-dsel.fecha_hora_ini AS diferencia
					FROM	`detalle_sellado` dsel, `personal` p, `hoja` h
							, `tdetalleordenesproduccion` tdop, `familia` f
							, `estilo` e, `tipo_material` timat, `tordenesproduccion` o
					WHERE	dsel.personal_id = p.personal_id
							AND dsel.hoja_id = h.hoja_id
							AND h.detalle_id = tdop.detalle_id
							AND tdop.familia_id = f.familia_id
							AND f.estilo_id = e.estilo_id
							AND dsel.tipo_material_id = timat.tipo_material_id
							AND tdop.orden_id = o.orden_id
							
							AND dsel.fecha_hora_ini >= '".$fechaini."'
							AND dsel.fecha_hora_fin <= '".$fechafin."'
							AND dsel.personal_id != 0
							
					ORDER BY dsel.fecha_hora_fin DESC
					";
					break;
				}
				case 5: {
					//planchado
					$consulta = "
					SELECT	o.cup_num AS op
							, CONCAT(p.apellidos, ' ', p.nombres) AS cortador
							, f.nombre_familia AS producto
							, e.nombre_estilo AS tipo
							, h.cantidad AS cantidad
							, timat.descripcion AS material
							, dpla.cantidad AS pieza_c
							, dpla.fecha_hora_ini
							, dpla.fecha_hora_fin
							, dpla.fecha_hora_fin-dpla.fecha_hora_ini AS diferencia
					FROM	`detalle_planchado` dpla
							, `personal` p
							, `hoja` h
							, `tdetalleordenesproduccion` tdop
							, `familia` f
							, `estilo` e
							, `tipo_material` timat
							, `tordenesproduccion` o
					WHERE	dpla.personal_id = p.personal_id
							AND dpla.hoja_id = h.hoja_id
							AND h.detalle_id = tdop.detalle_id
							AND tdop.familia_id = f.familia_id
							AND f.estilo_id = e.estilo_id
							AND dpla.tipo_material_id = timat.tipo_material_id
							AND tdop.orden_id = o.orden_id
							AND dpla.fecha_hora_ini >= '".$fechaini."'
							AND dpla.fecha_hora_fin <= '".$fechafin."'
							AND dpla.personal_id != 0
							
					ORDER BY dpla.fecha_hora_fin DESC
					";
					break;
				}
			}
						
			$resultado = mysql_query($consulta) or die ('La consulta -reporte dividido- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["op"] = $row['op'];
					$respuesta[$contador]["cortador"] = $row['cortador'];
					$respuesta[$contador]["producto"] = $row['producto'];
					$respuesta[$contador]["tipo"] = $row['tipo'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["material"] = $row['material'];
					$respuesta[$contador]["pieza_c"] = $row['pieza_c'];
					$respuesta[$contador]["fecha_hora_ini"] = $row['fecha_hora_ini'];
					$respuesta[$contador]["fecha_hora_fin"] = $row['fecha_hora_fin'];
					$respuesta[$contador]["diferencia"] = $row['diferencia'];
					
					$contador ++;
				}
				return $respuesta;
			}
		}
	}
	
	//******************************************************
	function reporte_corte_excel($nombre_archivo, $datos){
		$excel=new ExcelWriter("../../reportes/maquinado/".$nombre_archivo.".xls");
		if($excel==false)
			echo $excel->error;
			
		$myArr=array("OP", "Cortador", "Producto", "Tipo", "Cantidad", "Material", "Pieza_c", "Por_golpe", "Fecha Inicio", "Fecha fin", "Diferencia");
		$excel->writeCabecera($myArr);
		
		foreach($datos as $key => $value){
			$op = $datos[$key]['op'];
			$cortador = $datos[$key]['cortador'];
			$producto = $datos[$key]['producto'];
			$tipo = $datos[$key]['tipo'];
			$cantidad = $datos[$key]['cantidad'];
			$material = $datos[$key]['material'];
			$pieza_c = $datos[$key]['pieza_c'];
			$por_golpe = $datos[$key]['por_golpe'];
			$fecha_hora_ini = $datos[$key]['fecha_hora_ini'];
			$fecha_hora_fin = $datos[$key]['fecha_hora_fin'];
			$diferencia = $datos[$key]['diferencia'];
			
			$myArr=array($op,$cortador,$producto,$tipo,$cantidad,$material,$pieza_c,$por_golpe,$fecha_hora_ini,$fecha_hora_fin,$diferencia);
			$excel->writeLine($myArr);
		}
		
		$excel->close();
		//echo "data is write into myXls.xls Successfully.";
	}


	function reporte_varios_excel($nombre_archivo, $datos){
/*		echo "<pre>";
			print_r($datos);
		echo "</pre>";*/
	
		$excel=new ExcelWriter("../../reportes/maquinado/".$nombre_archivo.".xls");
		if($excel==false)
			echo $excel->error;
			
		$myArr=array("OP", "Cortador", "Producto", "Tipo", "Cantidad", "Material", "Pieza_c", "Fecha Inicio", "Fecha Fin", "Diferencia");
		$excel->writeCabecera($myArr);
		
		foreach($datos as $key => $value){
			$op = $datos[$key]['op'];
			$cortador = $datos[$key]['cortador'];
			$producto = $datos[$key]['producto'];
			$tipo = $datos[$key]['tipo'];
			$cantidad = $datos[$key]['cantidad'];
			$material = $datos[$key]['material'];
			$pieza_c = $datos[$key]['pieza_c'];
			$fecha_hora_ini = $datos[$key]['fecha_hora_ini'];
			$fecha_hora_fin = $datos[$key]['fecha_hora_fin'];
			$diferencia = $datos[$key]['diferencia'];
			
			$myArr=array($op,$cortador,$producto,$tipo,$cantidad,$material,$pieza_c,$fecha_hora_ini,$fecha_hora_fin,$diferencia);
			$excel->writeLine($myArr);
		}
		
		$excel->close();
		//echo "data is write into myXls.xls Successfully.";
	}
	
	//******************************************************


}
?>