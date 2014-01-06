<?php
require_once('../../clases/includes/dbmanejador.php');

class Modelo {
	function Modelo(){
	}
	
	function consulta_familia($nombre_familia, $modelo_familia, $estilo_id){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = '
			SELECT	f.familia_id, i.marca, i.modelo, e.nombre_estilo, f.nombre_familia
			FROM	`tipo_producto` tp, `familia` f, `estilo` e, `familia_integrante` fi, `integrante` i
			WHERE	tp.producto_id = f.producto_id AND
					f.estilo_id = e.estilo_id AND
					f.familia_id = fi.familia_id AND
					fi.integrante_id = i.integrante_id AND
					f.estado = 1 AND
					e.estado = 1 AND
					i.estado = 1 AND
					(i.marca LIKE "%'.$nombre_familia.'%" or i.modelo LIKE "%'.$modelo_familia.'%") AND
					e.estilo_id = '.$estilo_id.'
			ORDER BY i.marca';

			//echo "<br>consulta es.....".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -consulta familia- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row[0]]["ffamilia_id"] = $row['familia_id'];
					$respuesta[$row[0]]["imarca"][$row[1]] = $row['marca'];
					$respuesta[$row[0]]["imodelo"][$row[1]][] = $row['modelo'];
  					$respuesta[$row[0]]["enombre_estilo"] = $row['nombre_estilo'];
  					$respuesta[$row[0]]["fnombrefamilia"] = $row['nombre_familia'];
  				}
				return $respuesta;
			}
		}
	}

	//verificar estilos
	function verificar_estilo($descrip){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	estilo_id 
			FROM	estilo 
			WHERE	nombre_estilo ='". $descrip ."'";
			$resultado = mysql_query($consulta) or die('La consulta -verificar estilo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$estilo_id = $row['estilo_id'];
					$contador ++;
				}
				
				if($contador == 0)
					$estilo_id = -1;
			}
			return $estilo_id;
		}
	}

	function consulta_familia_existe($nombre_familia, $modelo_familia, $estilo_id){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = '
			SELECT	f.familia_id, i.marca, i.modelo, e.nombre_estilo, f.nombre_familia
			FROM	`tipo_producto` tp, `familia` f, `estilo` e, `familia_integrante` fi, `integrante` i
			WHERE	tp.producto_id = f.producto_id AND
					f.estilo_id = e.estilo_id AND
					f.familia_id = fi.familia_id AND
					fi.integrante_id = i.integrante_id AND
					f.estado = 1 AND
					e.estado = 1 AND
					i.estado = 1 AND
					i.marca = "'.$nombre_familia.'" and i.modelo = "'.$modelo_familia.'" AND
					e.estilo_id = '.$estilo_id.'
			ORDER BY i.marca';
			
			//echo "<br>consulta es.....".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -consultar familia existe- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row[0]]["ffamilia_id"] = $row['familia_id'];
					$respuesta[$row[0]]["imarca"][$row[1]] = $row['marca'];
					$respuesta[$row[0]]["imodelo"][$row[1]][] = $row['modelo'];
  					$respuesta[$row[0]]["enombre_estilo"] = $row['nombre_estilo'];
  					$respuesta[$row[0]]["fnombrefamilia"] = $row['nombre_familia'];
  				}
				return $respuesta;
			}
		}
	}

	function consulta_familia_cadena($familia_id, $estilo_id){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = '
				SELECT	f.familia_id, UPPER(i.marca) as marca , UPPER(i.modelo) as modelo, e.nombre_estilo, f.nombre_familia ,  i.marca as marca1 , i.modelo as modelo1 
				FROM	`tipo_producto` tp, `familia` f, `estilo` e, `familia_integrante` fi, `integrante` i
				WHERE	tp.producto_id = f.producto_id AND
						f.estilo_id = e.estilo_id AND
						f.familia_id = fi.familia_id AND
						fi.integrante_id = i.integrante_id AND
						f.estado = 1 AND
						e.estado = 1 AND
						i.estado = 1 AND
						f.familia_id = '.$familia_id.' AND
						e.estilo_id = '.$estilo_id.'
				ORDER BY marca1, i.modelo';
			
			//echo "<br>consulta es.....".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -consulta familia cadena- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$row[0]]["ffamilia_id"] = $row['familia_id'];
					$respuesta[$row[0]]["imarca"][$row['marca']] = trim($row['marca1']);
					$respuesta[$row[0]]["imodelo"][$row['marca']][] = trim($row['modelo1']);
  					$respuesta[$row[0]]["enombre_estilo"] = $row['nombre_estilo'];
  					$respuesta[$row[0]]["fnombrefamilia"] = $row['nombre_familia'];
  				}
				return $respuesta;
			}
		}
	}
	
	
	function devolver_cadena($valores){
		/*
		echo "<pre>";
		print_r ($valores);
		echo "</pre>";
		*/
		
		$cadena = "";
		foreach($valores as $vitem){
			$familia = "";
			foreach($vitem["imarca"] as $mitem){
				$familia = $familia . $mitem . " ";
				$integrantes = "";
				foreach($vitem["imodelo"][strtoupper($mitem)] as $moitem){
					$integrantes = $integrantes . $moitem . ", ";
				}
				$integrantes = substr ($integrantes, 0, -2);
				$familia = $familia . $integrantes . " | ";
			}
			$familia = substr ($familia, 0, -3);
			$cadena = $cadena . $familia;
		}
		
		//$cadena = substr ($cadena, 0, -3);
		//echo "<br>La cadena es: ". $cadena;
		return $cadena;
	}
	

	function consulta_tipo(){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = '
			SELECT	tp.producto_id, tp.nombre 
			FROM	`tproductos` tp
			ORDER BY tp.nombre';
			//echo "<br>consulta es.....".$consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -consulta tipo- fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["tpproducto_id"] = $row['producto_id'];
					$respuesta[$contador]["tpnombre"] = $row['nombre'];
					$contador ++;
  				}
				return $respuesta;
			}
		}
	}
	
	//insertar a una nueva familia
	function ingresar_tipo($producto_id, $nombre_estilo, $marca, $modelo){
		$con = new DBmanejador;
        if($con->conectar() == true){
		
			$consulta_estilo = '
			SELECT	estilo_id 
			FROM	`estilo` 
			WHERE	nombre_estilo = "'.$nombre_estilo.'"';
			
			//echo "<br>sql tipo: ". $consulta_estilo;
			
			$resultado_estilo = mysql_query($consulta_estilo) or die('La consulta -select estilo id- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado_estilo)){
				$estilo_id = $row[0];
			}

			$insertar_familia = '
			INSERT
			INTO	familia (producto_id, estilo_id)
			VALUES	('.$producto_id.','.$estilo_id.')';
			$resultado = mysql_query($insertar_familia) or die('La consulta -insert into familia- fall&oacute;: ' . mysql_error());
			
			//echo "<br>sql familia: ". $insertar_familia;
			
			$integrante_id = $this->adicionar_integrante($marca, $modelo);
			//echo "<br>integrante id:".$integrante_id;
			
			$consulta_codigo_familia ='	
			SELECT MAX(familia_id)
			FROM familia';
			$resultado_codigo_familia = mysql_query($consulta_codigo_familia) or die('La consulta -select max familia- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado_codigo_familia)){
				$familia_id = $row[0];
			}
			
			$insertar_familia_integrante = "
			INSERT
			INTO	familia_integrante (familia_id, integrante_id)
			VALUES	(".$familia_id.",".$integrante_id.")";
			$resultado = mysql_query($insertar_familia_integrante) or die('La consulta -insert into integrante- fall&oacute;: ' . mysql_error());
			
			//echo "<br>familia integrante: ".$insertar_familia_integrante;
			return $familia_id;
		}
	}
	
	//adicionar a una familia existente
	function adicionar_integrante($marca, $modelo){
		$con = new DBmanejador;
		if($con->conectar() == true){
			//
			$consultar_codigo = '
			SELECT	integrante_id 
			FROM	integrante
			WHERE	marca = "'.$marca.'" and
					modelo = "'.$modelo.'"';
			$resultado_consultar_codigo = mysql_query($consultar_codigo) or die('La consulta -select adicionar integrante- fall&oacute;: ' . mysql_error());

			if ($row = mysql_fetch_array($resultado_consultar_codigo)){
				$codigo_integrante = $row[0];
			} else {
				$ingresar_integrante = '
				INSERT
				INTO	integrante (marca, modelo)
				VALUES	("'.$marca.'","'.$modelo.'")';
				mysql_query($ingresar_integrante) or die('La consulta -insert adicionar integrante - fall&oacute;: ' . mysql_error());
				$codigo_integrante = mysql_insert_id();
			}
			return $codigo_integrante;
			//
		}
	}

	//adicionar a una familia existente
	function adicionar_a_tipo($familia_id, $integrante_id){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$ingresar_familia_integrante = '
			INSERT
			INTO	familia_integrante (familia_id, integrante_id)
			VALUES	('.$familia_id.','.$integrante_id.')';
			$resultado_familia_integrante = mysql_query($ingresar_familia_integrante) or die('La consulta -adicionar a tipo- fall&oacute;: ' . mysql_error());
		}
	}

	//buscar si existe un integrante
	function buscar_integrante($marca, $modelo){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta_integrante = '
			SELECT	integrante_id 
			FROM	`integrante`
			WHERE	marca = "'.$marca.'" and
					modelo = "'.$modelo.'"';
			$resultado_consulta_integrante = mysql_query($consulta_integrante) or die('La consulta -buscar integrante- fall&oacute;: ' . mysql_error());
			if ($row = mysql_fetch_array($resultado_consulta_integrante)){
				return $row['integrante_id'];
			} else {
				return false;
			}				
		}
	}
	
	function actualizar_familia($familia_id, $nombre_familia){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$actualiza_nombre_familia = '
			UPDATE	familia
			SET		nombre_familia = "'.$nombre_familia.'" 
			WHERE	familia_id = '.$familia_id;
			$resultado_nombre_familia = mysql_query($actualiza_nombre_familia) or die('La consulta -actualizar familia- fall&oacute;: ' . mysql_error());	
		}
	}

	function busqueda_estilos2($cadena,$tipo){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	e.nombre_estilo
			FROM	`tipo_producto` tp, `estilo` e
			WHERE	tp.producto_id = e.producto_id AND
					tp.producto_id = ".$tipo." AND
					e.nombre_estilo like '%".$cadena."%'
			ORDER BY e.nombre_estilo
			LIMIT 0, 20";
			$resultado = mysql_query($consulta) or die('La consulta -busqueda estilos 2- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador] = $row['nombre_estilo'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

/************************************************************************************/
	function listar_indicadores_tipo(){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT	it.indicadores_tipo_id, it.clase, it.nombre
			FROM	`indicadores_tipo` it
			ORDER BY it.clase";
			$resultado = mysql_query($consulta) or die('La consulta -listar_indicadores_tipo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['indicadores_tipo_id'] = $row['indicadores_tipo_id'];
					$respuesta[$contador]['clase'] = $row['clase'];
					$respuesta[$contador]['nombre'] = $row['nombre'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
/*********** el siguiente autoincrementable ******/
	function sacar_auto_incremento($tabla_name) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "SHOW TABLE STATUS LIKE '".$tabla_name."'";
			$resultado = mysql_query($consulta) or die('La consulta -auto incremento- fall&oacute;: ' . mysql_error());			
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_assoc($resultado)){
					$auto_incremento = $row['Auto_increment'];
				}
			}
			return $auto_incremento;
		}
	}
/************** insertar nuevo tipo ****************/
	function insertar_nuevo_tipo($clase, $nombre) {
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO indicadores_tipo(clase, nombre)
			VALUES ('".$clase."', '".$nombre."')
			";
			//echo "<br>sql: ". $consulta;
			$resultado = mysql_query($consulta) or die('La consulta -insertar_nuevo_tipo- fall&oacute;: ' . mysql_error());
		}
	}
}
?>