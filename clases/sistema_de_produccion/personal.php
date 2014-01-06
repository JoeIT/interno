<?php
require_once('../../clases/includes/dbmanejador.php');

class Personal {
	function Personal(){
	}

	//Insertar una observacion
	function ingresar_observacion($personal_id, $fecha_ing_obs, $hora_ing_obs, $hora_sal_obs, $observaciones){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			INSERT INTO `inoutpersonas`
			(`idpersona`, `fechain`, `horain`, `observaciones`, `fechaout`, `horaout`)
			VALUES
			(".$personal_id.", '".$fecha_ing_obs."', '".$hora_ing_obs."', '".$observaciones."', '".$fecha_ing_obs."', '".$hora_sal_obs."')
			";
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_observacion- fall&oacute;: ' . mysql_error());
		}
	}

	//Modificar observaciones
	function modificar_observaciones($codinout, $observaciones){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			UPDATE	inoutpersonas
			SET		observaciones = '".$observaciones."'
			WHERE	codinout = ".$codinout;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		}
	}

	//buscar personal
	function busqueda_personal($nombre){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT		p.personal_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase
			FROM 		personal p
			WHERE		    CONCAT(p.apellidos,' ', p.nombres) LIKE '%".$nombre."%'
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
	//sacar registros
	function registros_asistencia($personal_id, $fecha_obs, $fecha_fin_obs){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = "
			SELECT	codinout, fechain, horain, fechaout, horaout, observaciones
			FROM	`inoutpersonas` io
			WHERE		fechain >= '".$fecha_obs."'
					AND fechain <= '".$fecha_fin_obs."'
					AND idpersona = ".$personal_id."
			ORDER BY fechain, horain";
			
			//echo "<br>sql: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -registros_asistencia- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["codinout"] = $row['codinout'];
					$respuesta[$contador]["fechain"] = $row['fechain'];
					$respuesta[$contador]["horain"] = $row['horain'];
					$respuesta[$contador]["fechaout"] = $row['fechaout'];
					$respuesta[$contador]["horaout"] = $row['horaout'];
					$respuesta[$contador]["observaciones"] = trim($row['observaciones']);
					$contador ++;
  				}
				return $respuesta;
			}
		}
	}

	//verificar nombre
	function verificar_nombre($nombre){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = "
			SELECT	personal_id, apellidos, nombres
			FROM	`personal`
			WHERE	CONCAT(apellidos, ' ', nombres) = '".$nombre."'
			";
			$resultado = mysql_query($consulta) or die('La consulta -verificar nombre- fall&oacute;: ' . mysql_error());

			if ($row = mysql_fetch_array($resultado)){
				return $row['personal_id'];
			} else {
				return 0;
			}			
		}
	}
	
	//reporte excel
	function reporte_asistencia_excel($nombre_archivo, $datos){
		$excel=new ExcelWriter("../../reportes/asistencia/".$nombre_archivo.".xls");
		if($excel==false)
			echo $excel->error;
			
		$myArr=array("idpersona", "nombres", "apellidos", "puesto_trab", "fechain", "horain", "fechaout", "horaout", "observaciones");
		$excel->writeCabecera($myArr);
		
		foreach($datos as $key => $value){
			$idpersona = $datos[$key]['idpersona'];
			$nombres = $datos[$key]['nombres'];
			$apellidos = $datos[$key]['apellidos'];
			$puesto_trab = $datos[$key]['puesto_trab'];
			$fechain = $datos[$key]['fechain'];
			$horain = $datos[$key]['horain'];
			$fechaout = $datos[$key]['fechaout'];
			$horaout = $datos[$key]['horaout'];
			$observaciones = $datos[$key]['observaciones'];
			
			$myArr=array($idpersona, $nombres, $apellidos, $puesto_trab, $fechain, $horain, $fechaout, $horaout, $observaciones);
			$excel->writeLine($myArr);
		}
		
		$excel->close();
		//echo "data is write into myXls.xls Successfully.";
	}
	
	//reporte asistencia
	function reporte_asistencia($fecha_inicio, $fecha_fin, $puesto_trabajo){
		$con = new DBmanejador;
        if($con->conectar()==true){
			if ($puesto_trabajo == "todos")
				$consulta = "
				SELECT    p.personal_id AS idpersona
						, p.nombres
						, p.apellidos
						, pt.nombre_puesto_trabajo AS puesto_trab
						, io.fechain
						, io.horain
						, io.fechaout
						, io.horaout
						, io.observaciones
				FROM	  `personal` p
						, `personal_puesto_trabajo` ppt
						, `puesto_trabajo` pt
						, `inoutpersonas` io
				WHERE		p.personal_id = ppt.personal_id
						AND pt.puesto_trabajo_id = ppt.puesto_trabajo_id
						AND p.personal_id = io.idpersona
						AND io.fechain >= '".$fecha_inicio."'
						AND io.fechain <= '".$fecha_fin."'
				GROUP BY io.codinout
				ORDER BY io.fechain, io.horain
				" ;
			else
				$consulta = "
				SELECT    p.personal_id AS idpersona
						, p.nombres
						, p.apellidos
						, pt.nombre_puesto_trabajo AS puesto_trab
						, io.fechain
						, io.horain
						, io.fechaout
						, io.horaout
						, io.observaciones
				FROM	  `personal` p
						, `personal_puesto_trabajo` ppt
						, `puesto_trabajo` pt
						, `inoutpersonas` io
				WHERE		p.personal_id = ppt.personal_id
						AND pt.puesto_trabajo_id = ppt.puesto_trabajo_id
						AND p.personal_id = io.idpersona
						AND io.fechain >= '".$fecha_inicio."'
						AND io.fechain <= '".$fecha_fin."'
						AND pt.puesto_trabajo_id = ".$puesto_trabajo."
				GROUP BY io.codinout
				ORDER BY io.fechain, io.horain
				" ;
			
			//echo "<br>sql: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -reporte_asistencia- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["idpersona"] = $row['idpersona'];
					$respuesta[$contador]["nombres"] = $row['nombres'];
					$respuesta[$contador]["apellidos"] = $row['apellidos'];
					$respuesta[$contador]["puesto_trab"] = $row['puesto_trab'];
					$respuesta[$contador]["fechain"] = $row['fechain'];
					$respuesta[$contador]["horain"] = $row['horain'];
					$respuesta[$contador]["fechaout"] = $row['fechaout'];
					$respuesta[$contador]["horaout"] = $row['horaout'];
					$respuesta[$contador]["observaciones"] = $row['observaciones'];
					$contador ++;
  				}
				return $respuesta;
			}
		}
	}


	//reporte asistencia de nombres
	function reporte_asistencia_nombres($fecha_inicio, $fecha_fin, $personal_id){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = "
			SELECT    p.personal_id AS idpersona
					, p.nombres
					, p.apellidos
					, pt.nombre_puesto_trabajo AS puesto_trab
					, io.fechain
					, io.horain
					, io.fechaout
					, io.horaout
					, io.observaciones
			FROM	  `personal` p
					, `personal_puesto_trabajo` ppt
					, `puesto_trabajo` pt
					, `inoutpersonas` io
			WHERE		p.personal_id = ppt.personal_id
					AND pt.puesto_trabajo_id = ppt.puesto_trabajo_id
					AND p.personal_id = io.idpersona
					AND io.fechain >= '".$fecha_inicio."'
					AND io.fechain <= '".$fecha_fin."'
					AND p.personal_id = ".$personal_id."
			GROUP BY io.codinout
			ORDER BY io.fechain, io.horain
			" ;
			
			//echo "<br>sql: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -reporte_asistencia_nombres- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["idpersona"] = $row['idpersona'];
					$respuesta[$contador]["nombres"] = $row['nombres'];
					$respuesta[$contador]["apellidos"] = $row['apellidos'];
					$respuesta[$contador]["puesto_trab"] = $row['puesto_trab'];
					$respuesta[$contador]["fechain"] = $row['fechain'];
					$respuesta[$contador]["horain"] = $row['horain'];
					$respuesta[$contador]["fechaout"] = $row['fechaout'];
					$respuesta[$contador]["horaout"] = $row['horaout'];
					$respuesta[$contador]["observaciones"] = $row['observaciones'];
					$contador ++;
  				}
				return $respuesta;
			}
		}
	}


	function listar_puestos(){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT	pt.puesto_trabajo_id, pt.nombre_puesto_trabajo 
			FROM	`puesto_trabajo` pt
			WHERE	pt.estado = 1
			ORDER BY pt.nombre_puesto_trabajo' ;
			$resultado = mysql_query($consulta) or die('La consulta -listar puestos- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row[0]]["puesto_trabajo_id"]= $row['puesto_trabajo_id'];
					$respuesta[$row[0]]["nombre_puesto_trabajo"]= $row['nombre_puesto_trabajo'];
  				}
				return $respuesta;
			}
		}
	}
/*Name function: encontrar_existe_personal
  Observation: No puede ponerse el id porq hasta este punto se sabe si existe una persona con ese carnet de identidad si existen dos recuperara del primero q recupere
*/

	function encontrar_existe_personal($ci){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT	personal_id, apellidos, nombres
			FROM	`personal`
			WHERE	ci = '.$ci;
			$resultado = mysql_query($consulta) or die('La consulta - encontrar existe personal- fall&oacute;: ' . mysql_error());

			if ($row = mysql_fetch_array($resultado)){
				$lista['personal_id'] = $row['personal_id'];
				$lista['completo'] = $row['apellidos']." ".$row['nombres'];
				return $lista;
			} else {
				return false;
			}			
		}
	}

	function encontrar_existe_personal_id($pid){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT	personal_id, apellidos, nombres
			FROM	`personal`
			WHERE	personal_id = '.$pid;
			$resultado = mysql_query($consulta) or die('La consulta - encontrar existe personal- fall&oacute;: ' . mysql_error());

			if ($row = mysql_fetch_array($resultado)){
				$lista['personal_id'] = $row['personal_id'];
				$lista['completo'] = $row['apellidos']." ".$row['nombres'];
				return $lista;
			} else {
				return false;
			}			
		}
	}

	function sacar_puesto_trabajo_personal($personal_id){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT	puesto_trabajo_id 
			FROM	`personal_puesto_trabajo` 
			WHERE	personal_id = '.$personal_id;
			$resultado = mysql_query($consulta) or die ('La consulta -sacar puesto trabajo personal- fall&oacute;: ' . mysql_error());

		 	if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row['puesto_trabajo_id']] = $row['puesto_trabajo_id'];
				}
		     	return $respuesta;
			}		
		}
	}

	function sacar_datos_personal_id($pid){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT	nombres, apellidos, ci, expiracion_ci, fecha_nac, tiposangre,
					fecha_ini, fecha_fin, telefono,estado, auto_permitido, fecha_expiracion_brevet,
					notariado, observaciones, fotografia 
			FROM personal
			WHERE personal_id = '.$pid;
			$resultado = mysql_query($consulta) or die('La consulta - sacar datos personal id- fall&oacute;: ' . mysql_error());

		 	if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["nombresp"] = $row["nombres"];
					$respuesta["apellidosp"] = $row["apellidos"];
					$respuesta["ci"]= $row["ci"];
					$respuesta["ciexpirar"] = $row["expiracion_ci"];
					$respuesta["fecnac"]= $row["fecha_nac"];
					/*$tiposangre_aux = $row["tiposangre"];
					$tiposangre_aux = str_replace("\\","",$tiposangre_aux);
					$respuesta["tiposangre"] = $tiposangre_aux;*/
					$respuesta["tiposangre"] = $row["tiposangre"];
					$respuesta["fecinicio"] = $row["fecha_ini"];
					$respuesta["fecfin"] = $row["fecha_fin"];
					$respuesta["telefono"] = $row["telefono"];
					$respuesta["habilitado"] = $row["estado"];
					$respuesta["auto_permitido"] = $row["auto_permitido"];
					$respuesta["fexpiracionbrevet"] = $row["fecha_expiracion_brevet"];
					$respuesta["legalizado"] = $row["notariado"];
					$respuesta["observaciones"] = $row["observaciones"];
					$respuesta["fotografia"] = $row["fotografia"];
				}
		     	return $respuesta;
			}
		}
	}

	function ingresar_personal(	$nombresp, $apellidosp, $ci, $ciexpirar, $fecnac, $tiposangre, $usuario_id, 
								$fecinicio, $fecfin, $fecactual, $autopermitido, $fechaexpiracionbrevet,$telefono, 
								$habilitado, $legalizado, $modificado, $observaciones, $fotografia){
		$tiposangre_aux = $tiposangre;
		$tiposangre = str_replace('"','\\"',$tiposangre_aux);	
		$con = new DBmanejador;
        if($con->conectar() == true){
			if ($fotografia == false){
				$consulta = '
				INSERT INTO `personal`
						(`nombres`, `apellidos`, `ci`, `expiracion_ci`, `fecha_nac`, `tiposangre`,
						 `fecha_ini`, `fecha_fin`, `telefono`, `estado`, `autorizado_por`, `fecha_hoy`,
						 `auto_permitido`, `fecha_expiracion_brevet`, `reg_modificado`,
						 `notariado`, `observaciones`)
				VALUES
						("'.$nombresp.'", "'.$apellidosp.'", '.$ci.', "'.$ciexpirar.'", "'.$fecnac.'",
						 "'.$tiposangre.'", "'.$fecinicio.'", "'.$fecfin.'", "'.$telefono.'", '.$habilitado.',
						  '.$usuario_id.', "'.$fecactual.'", '.$autopermitido.', "'.$fechaexpiracionbrevet.'",
						 "'.$modificado.'", "'.$legalizado.'", "'.$observaciones.'")';
			} else {
				$consulta = '
				INSERT INTO `personal`
						(`nombres`, `apellidos`, `ci`, `expiracion_ci`, `fecha_nac`, `tiposangre`,
						 `fecha_ini`, `fecha_fin`, `telefono`, `estado`, `autorizado_por`, `fecha_hoy`,
						 `auto_permitido`, `fecha_expiracion_brevet`, `reg_modificado`,
						 `notariado`, `observaciones`, `fotografia`)
				VALUES
						("'.$nombresp.'", "'.$apellidosp.'", '.$ci.', "'.$ciexpirar.'", "'.$fecnac.'",
						 "'.$tiposangre.'", "'.$fecinicio.'", "'.$fecfin.'", "'.$telefono.'", '.$habilitado.',
						  '.$usuario_id.', "'.$fecactual.'", '.$autopermitido.', "'.$fechaexpiracionbrevet.'",
						 "'.$modificado.'", "'.$legalizado.'", "'.$observaciones.'", "'.$fotografia.'")';
			}
		
			//echo "<br>consulta personal: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -ingresar personal- fall&oacute;: ' . mysql_error());
		}
	}

	function borrar_personal_puesto($personal_id){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = '
			DELETE
			FROM	`personal_puesto_trabajo` 
			WHERE	personal_id = '.$personal_id;
			$resultado = mysql_query($consulta) or die ('La consulta -borrar personal puesto- fall&oacute;: ' . mysql_error());
		}
	}

	function insertar_personal_puesto($personal_id, $puesto_trabajo_id, $autorizado_por, $fecha_autorizado){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = '
			INSERT INTO `personal_puesto_trabajo`
						(`personal_id`, `puesto_trabajo_id`, `autorizado_por`, `fecha_autorizado`)
			VALUES 		('.$personal_id.', '.$puesto_trabajo_id.', '.$autorizado_por.', "'.$fecha_autorizado.'")';
			//	echo "<br>consulta puestos: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar personal puesto- fall&oacute;: ' . mysql_error());		
		}
	}

	function sacar_datos_puesto_trabajo($personal_id){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT		puesto_trabajo_id
			FROM		`personal_puesto_trabajo`
			WHERE		personal_id = '.$personal_id.'
			ORDER BY	puesto_trabajo_id';
			
			$resultado = mysql_query($consulta) or die('La consulta -sacar datos puesto trabajo- fall&oacute;: ' . mysql_error());

		 	if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row['puesto_trabajo_id']] = $row['puesto_trabajo_id'];
				}
		     	return $respuesta;
			}
		}
	}


	//nombres buscar
	function consulta_lista_personas($nombre_bus){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = '
			SELECT	p.ci,p.apellidos, p.nombres, p.fotografia, p.personal_id,if(p.estado=1,"Habilitado","Deshabilitado") AS estado
			FROM	personal p
			WHERE	(p.nombres like "%'.$nombre_bus.'%") OR
					(p.apellidos like "%'.$nombre_bus.'%")
			ORDER by p.apellidos, p.nombres
			LIMIT 0, 20';

            $resultado = mysql_query($consulta) or die('La consulta -consulta lista personas- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["personal_id"] = $row['personal_id'];
  					$respuesta[$contador]["p_nombre_completo"] = $row['apellidos']." ".$row['nombres'];
  					$respuesta[$contador]["p_fotografia"] = $row['fotografia'];
  					$respuesta[$contador]["p_estado"] = $row['estado'];
					$contador = $contador + 1;
  				}
				return $respuesta;
			}
		 }
      }
	//fin nombres

	//datos de la credencial
	function datos_credencial($pid){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = "
			SELECT p.personal_id AS pid
				   , CONCAT(p.nombres, ' ', p.apellidos) AS nombre
				   , p.tiposangre AS gs
				   , p.fotografia
				   , pt.nombre_puesto_trabajo AS cargo
			FROM	`personal` p, `puesto_trabajo` pt,`personal_puesto_trabajo`ppt
			WHERE	p.personal_id = ppt.personal_id
					AND pt.puesto_trabajo_id = ppt.puesto_trabajo_id
					AND p.personal_id = ".$pid."
			GROUP BY p.personal_id
			ORDER BY pt.puesto_trabajo_id";
			$resultado = mysql_query($consulta) or die('La consulta -datos credencial- fall&oacute;: ' . mysql_error());

			if ($row = mysql_fetch_array($resultado)){
				$lista['pid'] = $row['pid'];
				$lista['nombre'] = $row['nombre']." ".$row['nombres'];
				$lista['gs'] = $row['gs'];
				$lista['fotografia'] = $row['fotografia']." ".$row['nombres'];
				$lista['cargo'] = $row['cargo'];
				return $lista;
			} else {
				return false;
			}			
		}
	}
	
	function sacar_datos_personal($ci,$pid){
		$con = new DBmanejador;
        if($con->conectar()==true){
			$consulta = '
			SELECT	nombres, apellidos, ci, expiracion_ci, fecha_nac, tiposangre,
					fecha_ini, fecha_fin, telefono, estado, auto_permitido, fecha_expiracion_brevet,
					notariado, observaciones, fotografia 
			FROM personal
			WHERE personal_id='.$pid.' and ci = '.$ci;
			$resultado = mysql_query($consulta) or die('La consulta - sacar datos personal- fall&oacute;: ' . mysql_error());

		 	if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["nombresp"] = $row["nombres"];
					$respuesta["apellidosp"] = $row["apellidos"];
					$respuesta["ci"]= $row["ci"];
					$respuesta["ciexpirar"] = $row["expiracion_ci"];
					$respuesta["fecnac"]= $row["fecha_nac"];
					$respuesta["tiposangre"] = $row["tiposangre"];
					$respuesta["fecinicio"] = $row["fecha_ini"];
					$respuesta["fecfin"] = $row["fecha_fin"];
					$respuesta["telefono"] = $row["telefono"];
					$respuesta["habilitado"] = $row["estado"];
					$respuesta["auto_permitido"] = $row["auto_permitido"];
					$respuesta["fexpiracionbrevet"] = $row["fecha_expiracion_brevet"];
					$respuesta["legalizado"] = $row["notariado"];
					$respuesta["observaciones"] = $row["observaciones"];
					$respuesta["fotografia"] = $row["fotografia"];
					/*$tiposangre_aux = $row["tiposangre"];
					$tiposangre_aux = str_replace("\\","",$tiposangre_aux);
					
					$respuesta["tiposangre"] = $tiposangre_aux;*/
					
					
				}
		     	return $respuesta;
			}
		}
	}
	
	function actualizar_personal(	$pid,$nombresp, $apellidosp, $ci, $ciexpirar, $fecnac, $tiposangre, $usuario_id, 
									$fecinicio, $fecfin, $fecactual, $autopermitido, $fechaexpiracionbrevet,$telefono,
									$habilitado, $legalizado, $modificado, $observaciones,$fotografia){ 
										
		$tiposangre_aux = $tiposangre;
		$tiposangre = str_replace('"','\\"',$tiposangre_aux);																		
		$con = new DBmanejador;
        if($con->conectar() == true){
			if ($fotografia == false)
			{
				$consulta = '
				UPDATE `personal`
				SET
					`nombres` = "'.$nombresp.'",
					`apellidos` = "'.$apellidosp.'",
					`expiracion_ci` = "'.$ciexpirar.'",
					`fecha_nac` = "'.$fecnac.'",
					`tiposangre` = "'.$tiposangre.'",
					`fecha_ini` = "'.$fecinicio.'",
					`fecha_fin` = "'.$fecfin.'",
					`telefono` = "'.$telefono.'",
					`estado` = '.$habilitado.',
					`autorizado_por` = '.$usuario_id.',
					`fecha_hoy` = "'.$fecactual.'",
					`auto_permitido` = '.$autopermitido.',
					`fecha_expiracion_brevet` = "'.$fechaexpiracionbrevet.'",
					`reg_modificado` = "'.$modificado.'",
					`notariado` = "'.$legalizado.'",
					`observaciones` = "'.$observaciones.'"
				WHERE
					personal_id='.$pid.' and ci='.$ci; 
			} 
			else 
			{
				$consulta = '
				UPDATE `personal`
				SET
					`nombres` = "'.$nombresp.'",
					`apellidos` = "'.$apellidosp.'",
					`expiracion_ci` = "'.$ciexpirar.'",
					`fecha_nac` = "'.$fecnac.'",
					`tiposangre` = "'.$tiposangre.'",
					`fecha_ini` = "'.$fecinicio.'",
					`fecha_fin` = "'.$fecfin.'",
					`telefono` = "'.$telefono.'",
					`estado` = '.$habilitado.',
					`autorizado_por` = '.$usuario_id.',
					`fecha_hoy` = "'.$fecactual.'",
					`auto_permitido` = '.$autopermitido.',
					`fecha_expiracion_brevet` = "'.$fechaexpiracionbrevet.'",
					`reg_modificado` = "'.$modificado.'",
					`notariado` = "'.$legalizado.'",
					`observaciones` = "'.$observaciones.'",
					`fotografia` = "'.$fotografia.'"
				WHERE
					`personal_id`='.$pid.' and ci= '.$ci; 
			}

			//echo "consulta personal: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -actualizar personal- fall&oacute;: ' . mysql_error());
		}
	}
}
?>