<?php
include_once('../../clases/includes/dbmanejador.php');
include_once('../../clases/includes/validador.php');

class OrdenProductoNuevo {
	//function OrdenProd()
	function consulta_lista_ordenes($estado) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			if (trim($estado) == "") {
				$consulta = 'SELECT o.ordenproducto_id,o.num_ordenproducto,o.modelo,c.nombre
							, o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.estadoproducto
							FROM tordenesproductos o , tclientes c
							WHERE c.cliente_id=o.cliente_id and o.estado_orden=1 ORDER BY o.fechasolicitud DESC';
			} else {
				$consulta = "SELECT o.ordenproducto_id,o.num_ordenproducto,o.modelo,c.nombre
							, o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.estadoproducto
							FROM tordenesproductos o , tclientes c
							WHERE o.estado_orden=1 and c.cliente_id=o.cliente_id and o.estadoproducto='".trim($estado)."' ORDER BY o.fechasolicitud DESC";
			}
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"]= $row[0];
					$respuesta[$contador]["Numero orden"]= $row[1];
					$respuesta[$contador]["Modelo"]= $row[2];
					$respuesta[$contador]["Cliente"]= $row[3];
					$respuesta[$contador]["Fecha Solicitud"]= $row[4];
					$respuesta[$contador]["Fecha Culminacion"]= $row[5];
					$respuesta[$contador]["Fecha Reprogramacion"]= $row[6];
					$respuesta[$contador]["Estado"]= $row[7];
					$contador=$contador+1;
				}
				return $respuesta;
			}
		}
	}

	function consulta_lista_ordenes_anual($año) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT o.orden_id,o.num_orden,c.nombre,u.nombre, o.cup_num
						, o.fecha,o.fechaentrega,o.fecharepro,o.observacion
						FROM tordenesproduccion o , tclientes c,tusuarios u
						WHERE c.cliente_id=o.cliente_id and u.usuario_id=o.usuario_id and o.fecha>= '".$año."-01-01' ORDER BY o.orden_id desc";
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"]= $row[0];
					$respuesta[$contador]["Numero orden"]= $row[1];
					$respuesta[$contador]["Cliente"]= $row[2];
					$respuesta[$contador]["Usuario"]= $row[3];
					$respuesta[$contador]["Cup Num"]= $row[4];
					$respuesta[$contador]["fecha"]= $row[5];
					$respuesta[$contador]["fecha entrega"]= $row[6];
					$respuesta[$contador]["fecha reprogramacion"]= $row[7];
					$respuesta[$contador]["observaciones"]= $row[8];
					$contador=$contador+1;
				}
				return $respuesta;
			}
		}
	}
	
	function consultar_busqueda($cadena,$opcion,$estado) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			if ($opcion == "num_orden") {
				if ($estado == "") {
					$consulta = "SELECT o.ordenproducto_id,o.num_ordenproducto,o.modelo,c.nombre
								, o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.estadoproducto
								FROM tordenesproductos o , tclientes c
								WHERE c.cliente_id=o.cliente_id and o.num_ordenproducto like '%" . $cadena . "%' ORDER BY o.ordenproducto_id DESC";
				} else {
					$consulta = "SELECT o.ordenproducto_id,o.num_ordenproducto,o.modelo,c.nombre
								, o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.estadoproducto
								FROM tordenesproductos o , tclientes c
								WHERE c.cliente_id=o.cliente_id and o.estadoproducto='".$estado."' and o.num_ordenproducto like '%" . $cadena . "%' ORDER BY o.ordenproducto_id DESC";
				}
			} else {
				if ($estado == "") {
					$consulta = "SELECT o.ordenproducto_id,o.num_ordenproducto,o.modelo,c.nombre
								, o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.estadoproducto
								FROM tordenesproductos o , tclientes c
								WHERE c.cliente_id=o.cliente_id and c.nombre like '%" . $cadena . "%' ORDER BY o.ordenproducto_id DESC";
				} else {
					$consulta = "SELECT o.ordenproducto_id,o.num_ordenproducto,o.modelo,c.nombre
								, o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.estadoproducto
								FROM tordenesproductos o , tclientes c
								WHERE c.cliente_id=o.cliente_id and o.estadoproducto='".$estado."' and c.nombre like '%" . $cadena . "%' ORDER BY o.ordenproducto_id DESC";
				}
			}
			
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"]= $row[0];
  					$respuesta[$contador]["Numero orden"]= $row[1];
			  		$respuesta[$contador]["Modelo"]= $row[2];
			  		$respuesta[$contador]["Cliente"]= $row[3];
				  	$respuesta[$contador]["Fecha Solicitud"]= $row[4];
        		    $respuesta[$contador]["Fecha Culminacion"]= $row[5];
					$respuesta[$contador]["Fecha Reprogramacion"]= $row[6];
					$respuesta[$contador]["Estado"]= $row[7];
					$contador=$contador+1;
				}
				return $respuesta;
			}
		}
	}
	
	function consulta_numero_orden() {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = 'SELECT num_ordenproducto FROM tordenesproductos
						where ordenproducto_id= ( select MAX(ordenproducto_id) from tordenesproductos)';
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$row = mysql_fetch_array($resultado);
				$resultado = trim($row[0]);
				$num_orden = split("/",$resultado);
				$fecha = date("Y"); 
				$fecha = substr($fecha,2,2);
				if ($fecha == $num_orden[1])
					$num_orden_nueva = ($num_orden[0]+1)."/".$num_orden[1];
				else
					$num_orden_nueva = "1/".$fecha;
				return $num_orden_nueva;
			}
		}
	}
	
	function ingresa_nueva_orden($cliente_id,$num_orden,$fecha1,$fecha2,$modelo,$tarjeteros,$estado_producto,$detalles_adicionales,$cantidad,$mica,$exterior,$interior,$varios,$estilo_id,$clip_id, $tipo) {
		$validar = new Validador();
		$fecha_solicitud = $validar->cambiaf_a_mysql($fecha1);
		$fecha_culminacion=$validar->cambiaf_a_mysql($fecha2);
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "INSERT  into tordenesproductos(cliente_id,fechasolicitud,fechaculminacion, modelo,tarjetero,num_ordenproducto,estadoproducto,detallesadicionales,cantidad,lugar_mica,caracteristicas_interior,caracteristicas_exterior,material_varios,estilo_id,clip_id, tipo) values(".$cliente_id.",'".$fecha_solicitud."','".$fecha_culminacion."','".$modelo."',".$tarjeteros.",'".$num_orden."','".$estado_producto."','".$detalles_adicionales."',".$cantidad.",'".$mica."','".$interior."','".$exterior."','".$varios."',".$estilo_id.",".$clip_id.",".$tipo.")";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				//recibo el último id
				$orden_id = mysql_insert_id();
			    return $orden_id;
			}
		}
	}
	
	function obtener_detalle($ordenproducto_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT	o.ordenproducto_id, c.nombre, o.fechasolicitud
						, o.fechaculminacion, o.fechareprogramacion, o.codigo
						, o.grafico, o.modelo, o.tarjetero, o.num_ordenproducto
						, o.estadoproducto, o.detallesadicionales, o.cantidad
						, o.lugar_mica, o.caracteristicas_exterior, o.caracteristicas_interior
						, o.material_varios, e.nombre_estilo, l.descripcion
						, o.num_ordenproducto, o.tipo
						FROM	tordenesproductos o , tclientes c, estilo e, tclips l 
						WHERE	c.cliente_id=o.cliente_id and e.estilo_id=o.estilo_id 
								and o.clip_id=l.clip_id and o.ordenproducto_id=".$ordenproducto_id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en la orden: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$row = mysql_fetch_array($resultado);
				$respuesta["ordenproducto_id"]= $row[0];
				$respuesta["clientenombre"]= $row[1];
				$respuesta["fechasolicitud"]= $row[2];
				$respuesta["fechaculminacion"]= $row[3];
				$respuesta["fechareprogramacion"]= $row[4];
				$respuesta["codigo"]= $row[5];
				$respuesta["grafico"]= $row[6];
				$respuesta["tipo"]= $row['tipo'];
				$respuesta["modelo"]= $row[7];
				$respuesta["tarjetero"]= $row[8];
				$respuesta["num_ordenproducto"]= $row[9];
				$respuesta["estadoproducto"]= $row[10];
				$respuesta["detallesadicionales"]= $row[11];
				$respuesta["cantidad"]= $row[12];
				$respuesta["lugar_mica"]= $row[13];
				$respuesta["caracteristicas_exterior"]= $row[14];
				$respuesta["caracteristicas_interior"]= $row[15];
				$respuesta["material_varios"]= $row[16];
				$respuesta["estilo"]= $row[17];
				$respuesta["clip"]= $row[18];
				$respuesta["num_ordenproducto"]= $row[19];
				return $respuesta;
			}
		}
	}
	
	function obtener_lista_materiales($ordenproducto_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT d.materialesordenprodnuevo_id, d.fecha,m.nombre,m.descripcion,m.unidad,d.cantidad_solicitada,d.fecha_entrega,d.cantidad_utilizada , d.cantidad_devuelta FROM tordenesproductos o, tmaterialesordenprodnuevo d, tmateriales m WHERE d.estado=1 and o.ordenproducto_id=d.ordenproducto_id and d.material_id=m.material_id and o.ordenproducto_id=".$ordenproducto_id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en la orden: ' . mysql_error());
			if (!$resultado)
				return false;
			else {	
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"]= $row[0];
					$respuesta[$contador]["fecha"]= $row[1];
					$respuesta[$contador]["nombre"]= $row[2];
					$respuesta[$contador]["descripcion"]= $row[3];
					$respuesta[$contador]["unidad"]= $row[4];
					$respuesta[$contador]["cantidad solicitada"]= $row[5];
					$respuesta[$contador]["fecha entrega"]= $row[6];
					$respuesta[$contador]["cantidad utilizada"]= $row[7];
					$respuesta[$contador]["cantidad devuelta"]= $row[8];
					$contador++;
				}
				return $respuesta;
			}
		}
	}
	
	function modificar_fecha_reprogramacion($orden_id,$fecha) {
		$con = new DBmanejador;
		$validar = new Validador();
  	        $fecha_rep=$validar->cambiaf_a_mysql($fecha);
 		    if($con->conectar()==true)
		    {
			    $consulta="update tordenesproduccion set fecharepro='".$fecha_rep."' WHERE orden_id=".$orden_id; 
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else return true;
	 		}
    }  
    function actualizar_estado($ordenproducto_id,$estado) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tordenesproductos set estadoproducto='".$estado."' WHERE ordenproducto_id=".$ordenproducto_id; 
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function actualizar_grafico($ordenproducto_id,$grafico) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tordenesproductos set grafico='".$grafico."' WHERE ordenproducto_id=".$ordenproducto_id; 
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function actualizar_codigo($ordenproducto_id,$codigo) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tordenesproductos set codigo='".$codigo."' WHERE ordenproducto_id=".$ordenproducto_id; 
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function actualiza_orden($orden_id,$cliente_id,$fecha1,$fecha2,$modelo,$tarjeteros,$detalles_adicionales,$cantidad,$mica,$exterior,$interior,$varios,$estilo_id,$clip_id,$tipo) {
		$validar = new Validador();
		$fecha_solicitud=$validar->cambiaf_a_mysql($fecha1);
		$fecha_culminacion=$validar->cambiaf_a_mysql($fecha2);
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "UPDATE tordenesproductos set cliente_id=".$cliente_id.",fechasolicitud='".$fecha_solicitud."',fechaculminacion='".$fecha_culminacion."',modelo='".$modelo."',tarjetero=".$tarjeteros.",detallesadicionales='".$detalles_adicionales."',cantidad=".$cantidad.",lugar_mica='".$mica."',caracteristicas_interior='".$interior."',caracteristicas_exterior='".$exterior."',material_varios='".$varios."',estilo_id=".$estilo_id.",clip_id=".$clip_id.",estadoproducto='normas', tipo=".$tipo." where ordenproducto_id=".$orden_id;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function registrar_material($ordenproducto_id,$material_id,$cantidad,$usuario) {
		$fecha = date("d-m-Y");
		$validar = new Validador();
		$fecha_solicitud=$validar->cambiaf_a_mysql($fecha);
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "INSERT into tmaterialesordenprodnuevo(ordenproducto_id,material_id,fecha,usuario_solicitud,cantidad_solicitada) values(".$ordenproducto_id.",".$material_id.",'".$fecha_solicitud."',".$usuario.",".$cantidad.")";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				//recibo el último id 
				$materialordenprodnuevo_id = mysql_insert_id();
			    return $materialordenprodnuevo_id;
			}
		}
	}
	
	function obtener_material($materialesordenprodnuevo_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = 'SELECT d.materialesordenprodnuevo_id,m.nombre,d.cantidad_solicitada
						FROM  tmaterialesordenprodnuevo d,tmateriales m
						WHERE d.materialesordenprodnuevo_id='.$materialesordenprodnuevo_id.' and m.material_id=d.material_id';
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en obtener material: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				$row = mysql_fetch_array($resultado);
				$respuesta["materialesordenprodnuevo_id"] = $row[0];
				$respuesta["material"] = $row[1];
				$respuesta["cantidad"] = $row[2];
				return $respuesta;
			}
		}
	}
	
	function modificar_material($id,$material_id,$cantidad,$usuario) {
		$fecha = date("d-m-Y");
		$validar = new Validador();
		$fecha_solicitud = $validar->cambiaf_a_mysql($fecha);
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tmaterialesordenprodnuevo set cantidad_solicitada=".$cantidad.",usuario_solicitud=".$usuario.",fecha='".$fecha_solicitud."',material_id=".$material_id." where materialesordenprodnuevo_id=".$id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function deshabilitar_material($materialesordenprodnuevo_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tmaterialesordenprodnuevo set estado=0 where materialesordenprodnuevo_id=".$materialesordenprodnuevo_id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function deshabilitar_orden($orden_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tordenesproductos set estado_orden=0 where ordenproducto_id=".$orden_id;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
}
?>