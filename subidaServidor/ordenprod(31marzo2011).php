<?php
include_once('../../clases/includes/dbmanejador.php');

class OrdenProd {
	//function OrdenProd(){}
	
	function consulta_lista_ordenes() {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = 'SELECT	o.orden_id,o.num_orden,c.nombre, o.cup_num,
						        o.fecha,o.fechaentrega,o.fecharepro,o.observacion
  			            FROM	tordenesproduccion o , tclientes c
						WHERE c.cliente_id=o.cliente_id  ORDER BY o.orden_id DESC LIMIT 0 , 30';
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["Usuario"] = $row[3];
					$respuesta[$contador]["Cup Num"] = $row[4];
					$respuesta[$contador]["fecha"] = $row[5];
					$respuesta[$contador]["fecha entrega"] = $row[6];
					$respuesta[$contador]["fecha reprog"] = $row[7];
					$respuesta[$contador]["observaciones"] = $row[8];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

    function consulta_lista_ordenes_anual($año) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "
			SELECT	o.orden_id,o.num_orden,c.nombre, o.cup_num,
					o.fecha,o.fechaentrega,o.fecharepro,o.observacion
			FROM	tordenesproduccion o, tclientes c
			WHERE	c.cliente_id = o.cliente_id
			ORDER BY o.fecha desc, cup_num DESC
			LIMIT 0 , 30";
      
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["Cup Num"] = $row[3];
					$respuesta[$contador]["fecha"] = $row[4];
					$respuesta[$contador]["fecha entrega"] = $row[5];
					$respuesta[$contador]["fecha reprog"] = $row[6];
					$respuesta[$contador]["observaciones"] = $row[7];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consulta_lista_ordenes_despiece() {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT o.orden_id,o.num_orden,c.nombre,
								o.fecha,o.fechaentrega,o.fecharepro,o.observacion, o.cup_num
						FROM	tordenesproduccion o , tclientes c
						WHERE	c.cliente_id=o.cliente_id  ORDER BY o.fecha desc, cup_num DESC LIMIT 0 , 30 ";
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["fecha"] = $row[3];
					$respuesta[$contador]["fecha entrega"] = $row[4];
					$respuesta[$contador]["fecha reprog"] = $row[5];
					$respuesta[$contador]["observaciones"] = $row[6];
					$total = $this->obtener_total_items($row[0]);
					$despiece = $this->obtener_total_items_despiece($row[0]);
					$respuesta[$contador]["total"] = $total;
					$respuesta[$contador]["con despiece"] = $despiece;
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consultar_busqueda_despiece($cadena,$opcion) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			if ($opcion == "num_orden") {
				$consulta = "SELECT o.orden_id,o.num_orden,c.nombre,
									o.fecha,o.fechaentrega,o.fecharepro,o.observacion
							FROM	tordenesproduccion o , tclientes c
							WHERE  c.cliente_id=o.cliente_id and o.num_orden like '%" . $cadena . "%'";
			} else {
				if ($opcion == "usuarios") {
					$consulta = "SELECT o.orden_id,o.num_orden,c.nombre,
										o.fecha,o.fechaentrega,o.fecharepro,o.observacion
								FROM	tordenesproduccion o , tclientes c
								WHERE	c.cliente_id=o.cliente_id and u.nombres like '%" . $cadena . "%'";
				} else {
					$consulta = "SELECT o.orden_id,o.num_orden,c.nombre,
											 o.fecha,o.fechaentrega,o.fecharepro,o.observacion
								FROM	tordenesproduccion o , tclientes c
								WHERE	c.cliente_id=o.cliente_id and c.nombre like '%" . $cadena . "%'";
				}
			}
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["fecha"] = $row[3];
					$respuesta[$contador]["fecha entrega"] = $row[4];
					$respuesta[$contador]["fecha reprog"] = $row[5];
					$respuesta[$contador]["observaciones"] = $row[6];
					$total = $this->obtener_total_items($row[0]);
					$despiece = $this->obtener_total_items_despiece($row[0]);
					$respuesta[$contador]["total"] = $total;
					$respuesta[$contador]["con despiece"] = $despiece;
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function obtener_total_items($orden_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT COUNT(detalle_id)
  			             FROM tordenesproduccion o ,tdetalleordenesproduccion d
						 WHERE d.orden_id=o.orden_id and estado=1 and d.orden_id=".$orden_id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$row = mysql_fetch_array($resultado);
				return $row[0];
			}
		}
	}
	
	function obtener_total_items_despiece($orden_id) {
		$con = new DBmanejador;
			if ($con->conectar() == true) {
				$consulta = "SELECT	COUNT(detalle_id)
							FROM	tordenesproduccion o ,tdetalleordenesproduccion d
							WHERE	d.orden_id=o.orden_id and estado=1 and d.despiece=1 and d.orden_id=".$orden_id;
				$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$row = mysql_fetch_array($resultado);
				return $row[0];
			}
		}
	}
	
	function consultar_busqueda_pedido($cadena) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			if (trim($cadena) == "total") {
				$consulta="	SELECT o.orden_id,o.num_orden,c.nombre,o.cup_num,
								o.fecha,o.fechaentrega,o.fecharepro,o.observacion, desp.fecha_despacho, tda.fecha_asignacion,
									tda.fecha_finalizacion
							FROM   tordenesproduccion o , tclientes c, tdetalleordenesproduccion d LEFT JOIN
									`tdetalle_asignacion` tda ON tda.detalle_id=d.detalle_id
								   LEFT JOIN `despacho_detalle` dd ON dd.asignacion_id=tda.asignacion_detalle_id
								   LEFT JOIN `despacho` desp ON desp.despacho_id=dd.despacho_id
							WHERE  c.cliente_id=o.cliente_id
								   and o.orden_id=d.orden_id
								   and d.estado != 0
							GROUP BY o.num_orden
							ORDER BY o.cup_num desc limit 0,10";
			} else {
				$consulta="	SELECT o.orden_id,o.num_orden,c.nombre,o.cup_num,
								o.fecha,o.fechaentrega,o.fecharepro,o.observacion, desp.fecha_despacho, tda.fecha_asignacion,
									tda.fecha_finalizacion
							FROM   tordenesproduccion o , tclientes c, tdetalleordenesproduccion d LEFT JOIN
									`tdetalle_asignacion` tda ON tda.detalle_id=d.detalle_id
								   LEFT JOIN `despacho_detalle` dd ON dd.asignacion_id=tda.asignacion_detalle_id
								   LEFT JOIN `despacho` desp ON desp.despacho_id=dd.despacho_id
							WHERE  c.cliente_id=o.cliente_id
								   and o.orden_id=d.orden_id
								   and (d.pedido='".$cadena."' or d.observacion='".$cadena."')
								   and d.estado != 0
							ORDER BY o.cup_num desc";
			}
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row['orden_id'];
					$respuesta[$contador]["Orden Prod."] = $row['num_orden'];
					$respuesta[$contador]["Cliente"] = $row['nombre'];
					//$respuesta[$contador]["Cup Num"]= $row['cup_num'];
					$respuesta[$contador]["fecha"] = $row['fecha'];
					$respuesta[$contador]["fecha entrega"] = $row['fechaentrega'];
					$respuesta[$contador]["fecha reprog"] = $row['fecharepro'];
					$fecha_asig = split(' ',$row['fecha_asignacion']);
					$respuesta[$contador]["fecha asignacion"] = $fecha_asig[0];
					$respuesta[$contador]["fecha finalizacion"] = $row['fecha_finalizacion'];
					$respuesta[$contador]["fecha despacho"] = $row['fecha_despacho'];
					//$respuesta[$contador]["observaciones"]= $row['observacion'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consultar_busqueda($cadena,$opcion) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			if ($opcion == "num_orden") {
				$consulta = "SELECT o.orden_id,o.num_orden,c.nombre,o.cup_num,
									o.fecha,o.fechaentrega,o.fecharepro,o.observacion
							 FROM	tordenesproduccion o , tclientes c WHERE  c.cliente_id=o.cliente_id 
									and o.num_orden like '%" . $cadena . "%'";
			} else {
				if ($opcion == "usuarios") {
					$consulta = "SELECT o.orden_id,o.num_orden,c.nombre, o.cup_num,
										o.fecha,o.fechaentrega,o.fecharepro,o.observacion
								FROM	tordenesproduccion o , tclientes c
								WHERE	c.cliente_id=o.cliente_id and u.nombres like '%" . $cadena . "%'";
				} else {
					$consulta = "SELECT o.orden_id,o.num_orden,c.nombre, o.cup_num,
										o.fecha,o.fechaentrega,o.fecharepro,o.observacion
								FROM	tordenesproduccion o , tclientes c
								WHERE	c.cliente_id=o.cliente_id and c.nombre like '%" . $cadena . "%'";
				}
			}
			
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["Cup Num"] = $row[3];
					$respuesta[$contador]["fecha"] = $row[4];
					$respuesta[$contador]["fecha entrega"] = $row[5];
					$respuesta[$contador]["fecha reprogramacion"] = $row[6];
					$respuesta[$contador]["observaciones"] = $row[7];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consulta_numero_orden() {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			//$consulta = 'SELECT num_orden FROM tordenesproduccion where cup_num= ( select MAX(cup_num) from tordenesproduccion)';
			
			$consulta = '
				SELECT	num_orden
				FROM	`tordenesproduccion` top
				ORDER BY fecha DESC, cup_num DESC
				LIMIT 0,1';
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$row = mysql_fetch_array($resultado);
				$resultado = trim($row[0]);
				$num_orden = split("_",$resultado);
				$fecha = date("Y");
				if ($fecha == $num_orden[2])
					$num_orden_nueva = "P_".str_pad(($num_orden[1]+1), 2, "0", STR_PAD_LEFT)."_".$num_orden[2];
				else
					$num_orden_nueva = "P_01_".$fecha;
				//echo "--".$num_orden[1];
				return $num_orden_nueva;
			}
		}
	}
	
	function obtener_num_cup($num_orden) {
		$orden_div = split("_", $num_orden);
		$num_cup = $orden_div[1].substr($orden_div[2], 2, 2);
		return $num_cup;
	}
	
	function ingresa_nueva_orden($num_orden,$num_cup,$fecha,$fecha_entrega,$cliente_id,$observaciones,$usuario_id) {
		$validar = new Validador();
		$fecha1 = $validar->cambiaf_a_mysql($fecha);
		$fecha2 = $validar->cambiaf_a_mysql($fecha_entrega);
		$con = new DBmanejador;
		
		if ($con->conectar() == true) {
			$consulta = "INSERT  into tordenesproduccion(num_orden,cliente_id,usuario_id,cup_num,fecha,fechaentrega,observacion) 				                    values('".$num_orden."',".$cliente_id.",".$usuario_id.",".$num_cup.",'".$fecha1."','".$fecha2."','".$observaciones."')";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en ingresar neuva orden de produccion: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			     //recibo el último id 
				$orden_id = mysql_insert_id();
			    return $orden_id;
			}
		}
	}
	
	function obtener_cabecera_orden($id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT o.orden_id,o.num_orden,c.nombre, o.cup_num, o.fecha,o.fechaentrega,o.fecharepro,o.observacion,o.cliente_id FROM tordenesproduccion o , tclientes c WHERE c.cliente_id=o.cliente_id  and o.orden_id=".$id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en obtener la cabecera de la orden: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$row = mysql_fetch_array($resultado);
				$respuesta["codigo"] = $row[0];
				$respuesta["numero_orden"] = $row[1];
				$respuesta["cliente"] = $row[2];
				$respuesta["cup_num"] = $row[3];
				$respuesta["fecha"] = $row[4];
				$respuesta["fecha_entrega"] = $row[5];
				$respuesta["fecha_reprogramacion"] = $row[6];
				$respuesta["observaciones"] = $row[7];
				$respuesta["cliente_id"] = $row[8];
				return $respuesta;
			}
		}
	}
	
	function modificar_fecha_reprogramacion($orden_id,$fecha) {
		$con = new DBmanejador;
		$validar = new Validador();
		$fecha_rep = $validar->cambiaf_a_mysql($fecha);
		if ($con->conectar() == true) {
			$consulta = "update tordenesproduccion set fecharepro='".$fecha_rep."' WHERE orden_id=".$orden_id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en modificar fecha reprogramacion: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function busqueda_ordenes($cadena) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "select num_orden from tordenesproduccion where num_orden like '%".$cadena."%' limit 0,5";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute; busqueda ordenes: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador] = $row['num_orden'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function verificar_orden_produccion($numero) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "select orden_id from tordenesproduccion where num_orden='".$numero."'";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; busqueda ordenes: ' . mysql_error());
			
			if (!$resultado)
				return -1;
			else {
				while ($row = mysql_fetch_array($resultado)) {
					$orden_id = $row['orden_id'];
					return $orden_id;
				}
			    return -1;
			}
		}
	}
	
	function eliminar_orden($orden_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "delete from tordenesproduccion  WHERE orden_id=".$orden_id; 
	        $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			//$consulta= "delete from resultados_asignacion where detalle_id=(".$detalle.")";
			//$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en eliminar resultados asignacion: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
}
?>