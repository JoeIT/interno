<?php
include_once('../../clases/includes/dbmanejador.php');
class Instructivo { 
	//dado un ID de instructivo saca la URL
	function obtener_url_instructivo($instructivo_id) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "
			SELECT	url
			FROM	tinstructivos
			WHERE	instructivo_id = ".$instructivo_id;

            $resultado = mysql_query($consulta) or die('La consulta -obtener_url_instructivo- fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else {
				if ($row = mysql_fetch_array($resultado)) {
				  $respuesta["url"] = $row['url'];
  				}
				return $respuesta;
			}
		}
	}
	
	function desactivar_instructivo($codigo) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "update tinstructivos set actual=0 WHERE instructivo_id=".$codigo; 
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; : ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	// ajax
	
	function sacar_id_instructivo($cadena) {
		$final = split(":::",$cadena); 
		return trim($final[0]);
	}
	
    function busqueda_instructivos($cadena) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT	i.instructivo_id as instructivo,
								i.descripcion as descripcion,
								cli.nombre as cliente,
								c.descripcion as cuero
						 FROM 	familia f, 
								estilo e,
								tinstructivos i,
								tclientes cli,
								tcueros c
						 WHERE 	i.familia_id=f.familia_id AND
								i.cuero_id=c.cuero_id AND
								cli.cliente_id=i.cliente_id AND
								i.actual != 0 AND
								f.estilo_id = e.estilo_id AND
								(e.nombre_estilo like '%".$cadena."%' or f.nombre_familia like '%".$cadena."%' or cli.nombre like '%".$cadena."%' or i.descripcion like '%".$cadena."%' or i.instructivo_id like '%".$cadena."%') 
						 LIMIT 	0,10";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador] = $row['instructivo']." ::: ".$row['descripcion']." ::: ".$row['cuero']." ::: ".$row['cliente'];
					$contador = $contador + 1;
  				}
				return $respuesta;
			}
		}
	}
	
	// busqueda de ordenes
	function sacar_codigo() {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT max(instructivo_id)
						from tinstructivos";
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row[0];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consultar_modelos_ordenes($cadena) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT DISTINCT 
						o.orden_id,
						o.num_orden,
						c.nombre, 
						o.fecha,
						o.fechaentrega,
						o.fecharepro,
						o.observacion,
						o.cup_num
						FROM tordenesproduccion AS o,tclientes AS c,familia AS f, tdetalleordenesproduccion AS d
						WHERE c.cliente_id=o.cliente_id 
							 AND f.nombre_familia LIKE '%".$cadena."%'
							 AND f.familia_id=d.familia_id
							 AND d.orden_id=o.orden_id
						ORDER BY o.cup_num DESC LIMIT 0,20
						";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["fecha"] = $row[3];
					$respuesta[$contador]["fecha entrega"] = $row[4];
					$respuesta[$contador]["fecha reprog"] = $row[5];
					$respuesta[$contador]["observaciones"] = $row[6];
					$respuesta[$contador]["instructivo"] = $this->obtener_numero_instructivo($row[0]);
					$respuesta[$contador]["total"] = $this->obtener_cantidad($row[0]);
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consultar_modelos_estilos_ordenes($cadena,$cadena2) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT  DISTINCT
						o.orden_id,
						o.num_orden,
						c.nombre, 
						o.fecha,
						o.fechaentrega,
						o.fecharepro,
						o.observacion,
						o.cup_num
						FROM	tordenesproduccion AS o,
								tclientes AS c,familia AS f, 
								tdetalleordenesproduccion AS d,
								estilo AS e
						WHERE c.cliente_id=o.cliente_id 
							 AND f.nombre_familia LIKE '%".$cadena."%'
							 AND e.nombre_estilo LIKE '%".$cadena2."%'
							 AND f.estilo_id=e.estilo_id
							 AND f.familia_id=d.familia_id
							 AND d.orden_id=o.orden_id
						ORDER BY o.cup_num DESC LIMIT 0,20
						";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		 	if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["codigo"] = $row[0];
					$respuesta[$contador]["Orden Prod."] = $row[1];
					$respuesta[$contador]["Cliente"] = $row[2];
					$respuesta[$contador]["fecha"] = $row[3];
					$respuesta[$contador]["fecha entrega"] = $row[4];
					$respuesta[$contador]["fecha reprog"] = $row[5];
					$respuesta[$contador]["observaciones"] = $row[6];
					$respuesta[$contador]["instructivo"] = $this->obtener_numero_instructivo($row[0]);
					$respuesta[$contador]["total"] = $this->obtener_cantidad($row[0]);
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function consultar_familia_instructivos($cadena) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$anio = date("Y");
			$mes = date("m") - 01;
			$dia = date("d");
			$fec = $anio."-".$mes."-".$dia;
					
		    $consulta = "SELECT i.instructivo_id, concat(f.nombre_familia,' ::: ',e.nombre_estilo ) as producto,
								cl.nombre as cliente,i.archivo,i.descripcion,i.fecha as fec, cu.descripcion
						FROM    estilo AS e, familia AS f, tclientes AS cl, tinstructivos AS i, tcueros AS cu
						WHERE   i.cliente_id=cl.cliente_id AND i.familia_id=f.familia_id AND e.estilo_id=f.estilo_id AND i.actual=1 AND cu.cuero_id=i.cuero_id AND f.nombre_familia LIKE '%".$cadena."%'
						ORDER BY producto, cliente";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                 
		 	if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['codigo'] = $row['0'];
					$respuesta[$contador]['modelo'] = $row['1'];
					$respuesta[$contador]['cuero'] = $row['6'];
					$respuesta[$contador]['cliente'] = $row['2'];
					$respuesta[$contador]['archivo'] = $row['3'];
					$respuesta[$contador]['descripcion'] = $row['4'];
                  	$contador = $contador+1;
  				}
				return $respuesta;
			}
		}
	}
	
	function consultar_busqueda($cadena) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT o.orden_id,o.num_orden,c.nombre, o.fecha,o.fechaentrega,o.fecharepro,o.observacion,o.cup_num
  			            FROM tordenesproduccion o , tclientes c
						WHERE c.cliente_id=o.cliente_id AND o.num_orden LIKE '%".$cadena."%' 
						ORDER BY o.cup_num DESC LIMIT 0,20
						";
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
					$respuesta[$contador]["instructivo"] = $this->obtener_numero_instructivo($row[0]);
					$respuesta[$contador]["total"] = $this->obtener_cantidad($row[0]);
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	// lista de detalles
	function obtener_detalle_orden($orden_id) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT		d.detalle_id AS codigo, 
									o.descripcion AS cuero,
									c.descripcion AS color,
									l.descripcion AS clip,
									concat(m.nombre_familia,' ::: ',e.nombre_estilo) AS producto ,
									d.cantidad AS cantidad,
									d.instructivo_id AS instructivo
						FROM  		tdetalleordenesproduccion d, estilo e, 
									tcueros o, tcolores c, tclips l, 
									tchapas s, tetiquetas t, tpropiedades p, familia m
						WHERE 		d.orden_id=".$orden_id." and d.propiedad_id=p.prop_id and
									m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and 
									p.color_id=c.color_id and p.clip_id=l.clip_id and 
									p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and
									d.familia_id=m.familia_id and d.estado=1
						ORDER BY 	producto,cuero,color,clip";
			// echo $consulta;
           	$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());
						
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"] = $row['codigo'];
					$respuesta[$contador]["Cant."] = $row['cantidad'];	
					$respuesta[$contador]["Modelo"] = $row['producto'];
					$respuesta[$contador]["Origen Cuero"] = $row['cuero'];
					$respuesta[$contador]["Color"] = $row['color'];
					$respuesta[$contador]["Clip"] = $row['clip'];
					$respuesta[$contador]["instructivo"] = $row['instructivo'];
					$respuesta[$contador]["bueno"] = $row['bueno'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	// saca la cabeza para la lista de detalles
	function obtener_detalle_cabeza($codigo) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT	o.orden_id,o.num_orden,c.nombre, 
			                   	o.fecha,o.fechaentrega,o.fecharepro,o.observacion
						FROM 	tordenesproduccion o , tclientes c
						WHERE 	c.cliente_id=o.cliente_id AND
								o.orden_id=".$codigo."
						ORDER BY o.fecha DESC LIMIT 0,20";
			
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta["codigo"] = $row[0];
					$respuesta["Orden"] = $row[1];
					$respuesta["cliente"] = $row[2];
					$respuesta["fecha"] = $row[3];
					$respuesta["fechaentrega"] = $row[4];
					$respuesta["fecharepro"] = $row[5];
					$respuesta["observaciones"] = $row[6];
				}
				return $respuesta;
			}
		}
	}
	
	// numero de instructivos en una orden
	function obtener_numero_instructivo($orden) {
	     $con = new DBmanejador;
         if($con->conectar() == true) {
		 	$consulta = "SELECT COUNT(detalle_id) as cantidad
		  			 	FROM tdetalleordenesproduccion AS d
						WHERE d.orden_id=".$orden." AND d.instructivo_id <> 0
						";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				$row = mysql_fetch_array($resultado);
				return $row[0];
			}
		}
	}
	
	// cantidad total de una orden
	function obtener_cantidad($orden) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT COUNT(detalle_id) as cantidad
						 FROM tdetalleordenesproduccion AS d
						 WHERE d.orden_id=".$orden." 
						";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				$row = mysql_fetch_array($resultado);
				return $row[0];
			}
		}
	}
	
	// lista de ordenesss
	function consulta_lista_ordenes() {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = 'SELECT	o.orden_id,o.num_orden,c.nombre,
						 		o.fecha,o.fechaentrega,o.fecharepro,o.observacion,o.cup_num
						 FROM	tordenesproduccion o , tclientes c
						 WHERE	c.cliente_id=o.cliente_id  ORDER BY o.fecha DESC, o.cup_num DESC LIMIT 0,20';
			
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["codigo"]= $row[0];
					$respuesta[$contador]["Orden Prod."]= $row[1];
					$respuesta[$contador]["Cliente"]= $row[2];
					$respuesta[$contador]["fecha"]= $row[3];
					$respuesta[$contador]["fecha entrega"]= $row[4];
					$respuesta[$contador]["fecha reprog"]= $row[5];
					$respuesta[$contador]["observaciones"]= $row[6];
					$respuesta[$contador]["instructivo"]=$this->obtener_numero_instructivo($row[0]);
					$respuesta[$contador]["total"]=$this->obtener_cantidad($row[0]);
					$contador=$contador+1;
  				}
				return $respuesta;
			}
		}
	}

	function ultimo_instructivo_link($codigo) {
		$con = new DBmanejador;
        if($con->conectar() == true) {
			$consulta = "SELECT	t.url, t.instructivo_id
						 FROM	tinstructivos AS t
						 WHERE	t.fecha = (
						 		SELECT	MAX( t1.fecha )
								FROM	tinstructivos AS t1, tdetalleordenesproduccion AS d1, tdetalle_asignacion AS d2
								WHERE	d2.detalle_id = d1.detalle_id
										AND t1.familia_id = d1.familia_id
										AND d2.asignacion_detalle_id ='".$codigo."'
								)";
			
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[0]['url'] = $row['0'];
					$respuesta[0]['instructivo_id'] = $row['instructivo_id'];
				}
				return $respuesta;
			}
		}
	}
	
	function lista_instructivos($codigo, $cliente, $cuero) {
		$con = new DBmanejador;
        if($con->conectar() == true) {
			$consulta = "SELECT	MAX(i1.instructivo_id) AS instructivo,
								i1.descripcion AS descripcion,
								i1.url AS url,
								i1.archivo AS archivo,
								f.nombre_familia AS modelo,
								e.nombre_estilo AS estilo,
								cu.descripcion AS cuero,
								cli.nombre AS cliente
						FROM    tinstructivos AS i1,
								familia AS f,
								tcueros AS cu,
								tclientes AS cli,
								estilo AS e
						WHERE   f.familia_id=i1.familia_id AND
								f.estilo_id=e.estilo_id AND
								cu.cuero_id=i1.cuero_id AND
								cli.cliente_id=i1.cliente_id AND        
								i1.actual=1 AND
								i1.familia_id=(
											SELECT  d.familia_id
											FROM    tdetalleordenesproduccion AS d
											WHERE   d.detalle_id='".$codigo."')
						GROUP BY modelo,estilo,cuero,cliente
						ORDER BY instructivo DESC,modelo,estilo,cuero,cliente";
			
			//echo "<br>sql: ".$consulta;
			
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                  
		 	if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['codigo'] = $row['0'];
					$respuesta[$contador]['descripcion'] = $row['1'];
					$respuesta[$contador]['url'] = $row['2'];	
					$respuesta[$contador]['archivo'] = $row['3'];
					$respuesta[$contador]['modelo'] = $row['4'];
					$respuesta[$contador]['estilo'] = $row['5'];
					$respuesta[$contador]['cuero'] = $row['6'];
					$respuesta[$contador]['cliente'] = $row['7'];
					$contador++;
  				}
				return $respuesta;
		  	}
		}
	}
	
	function obtener_familia_estilo($codigo) {
		$con = new DBmanejador;
        if($con->conectar() == true) {			
		    $consulta= "SELECT 	f.familia_id,
							    e.estilo_id, 
								concat(f.nombre_familia,' ::: ',e.nombre_estilo,' ::: ',c.nombre ),
								c.cliente_id,
								p.cuero_id,
								f.nombre_familia,
								e.nombre_estilo,
								c.nombre,
								cu.descripcion
						FROM 	estilo AS e,
								familia AS f, 
								tdetalleordenesproduccion AS d, 
								tordenesproduccion AS o, 
								tclientes AS c,
								tpropiedades AS p,
								tcueros AS cu
						WHERE 	cu.cuero_id=p.cuero_id AND
								d.orden_id=o.orden_id AND 
								o.cliente_id=c.cliente_id AND 
								d.familia_id=f.familia_id AND 
								e.estilo_id=f.estilo_id AND 
								p.prop_id=d.propiedad_id AND
								d.detalle_id='".$codigo."'";
             
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
		 	if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta[0]['f'] = $row['0'];
					$respuesta[0]['e'] = $row['1'];
					$respuesta[0]['t'] = $row['2'];
					$respuesta[0]['c'] = $row['3'];
					$respuesta[0]['cu'] = $row['4'];
					$respuesta[0]['familia'] = $row['5'];
					$respuesta[0]['estilo'] = $row['6'];
					$respuesta[0]['cliente'] = $row['7'];
					$respuesta[0]['cuero'] = $row['8'];
  				}
				return $respuesta;
			}
		}
	}
 	
	function obtener_similares($familia, $estilo) {
		$con = new DBmanejador;
        if($con->conectar() == true) {
			// saco la fecha y le resto un mes
		 	$anio = date("Y");
			$mes = date("m")-01;
			$dia = date("d");
			$fec = $anio."-".$mes."-".$dia;
		    $consulta = "SELECT	o.num_orden,
								o.fecha, 
								concat(f.nombre_familia,' ::: ',e.nombre_estilo ) as producto, 
								c.descripcion, 
								cu.descripcion as cuero,
								d.detalle_id,
								cli.nombre as cliente
						FROM 	estilo AS e, 
								familia AS f, 
								tordenesproduccion AS o, 
								tdetalleordenesproduccion AS d, 
								tcolores AS c, 
								tcueros AS cu, 
								tpropiedades AS p, 
								tclientes AS cli
						WHERE 	o.cliente_id=cli.cliente_id AND d.orden_id=o.orden_id AND 
								d.familia_id=f.familia_id AND p.color_id=c.color_id AND 
								p.cuero_id=cu.cuero_id AND d.propiedad_id=p.prop_id AND 
								e.estilo_id=f.estilo_id AND d.aprobado_instructivo=0 AND 
								o.fecha>'".$fec."' AND d.familia_id=".$familia." AND f.estilo_id=".$estilo."
					   Order by  producto,cuero,cliente";
			
			
			//echo "<br>sql: ".$consulta;
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador=0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['0'];
					$respuesta[$contador]['fecha'] = $row['1'];
					$respuesta[$contador]['familia'] = $row['2'];
					$respuesta[$contador]['color'] = $row['3'];
					$respuesta[$contador]['cuero'] = $row['4'];
					$respuesta[$contador]['codigo'] = $row['5'];
					$respuesta[$contador]['cliente'] = $row['6'];
                  	$contador = $contador + 1;
  				}
				return $respuesta;
		  	}
		}
	}
	
	function insertar_instructivo_a_detalle($id, $detalle) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "update tdetalleordenesproduccion set instructivo_id='".$id."', aprobado_instructivo=1 WHERE detalle_id=".$detalle; 
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; : ' . mysql_error());
		if (!$resultado)
			return false;
		else
			return true;
		}
   	}
	
	function insertar_instructivo($familia, $descripcion, $url, $nombre, $cliente, $cuero) {
		$con = new DBmanejador;
        if($con->conectar() == true) {
			$consulta = "INSERT  into tinstructivos (familia_id,descripcion,archivo,url,fecha,usuario,cliente_id,cuero_id) values('".$familia."','".$descripcion."','".$nombre."','".$url."','".date("Y-m-d H:i:s")."','".$_SESSION['usuario_id']."','".$cliente."','".$cuero."')";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
			 	return "false";
			else
				return "true";
		}
	}
	
	function consulta_detalle_orden($id) { 
	     $con = new DBmanejador;
         if($con->conectar() == true) {
		 	$consulta = "SELECT	o.num_orden,o.fecha, concat(f.nombre_familia,' ::: ',e.nombre_estilo,' ::: ',cli.nombre ), c.descripcion, cu.descripcion, cl.descripcion , et.descripcion , ch.descripcion ,d.detalle_id 
						 FROM	estilo AS e, familia AS f, tordenesproduccion AS o, tdetalleordenesproduccion AS d, tcolores AS c, tcueros AS cu, tpropiedades AS p, tclips AS cl, tetiquetas AS et, tchapas AS ch, tclientes AS cli
						 WHERE	cli.cliente_id=o.cliente_id AND d.orden_id=o.orden_id AND d.familia_id=f.familia_id AND p.color_id=c.color_id AND p.cuero_id=cu.cuero_id AND p.sello_id=ch.chapa_id AND p.etiqueta_id=et.etiqueta_id AND p.clip_id=cl.clip_id AND d.propiedad_id=p.prop_id AND e.estilo_id=f.estilo_id AND d.detalle_id=".$id;
			
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute; consulta detalle orden: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["orden"] = $row[0];
					$respuesta[$contador]["fecha"] = $row[1];
  					$respuesta[$contador]["familia"] = $row[2];
  					$respuesta[$contador]["color"] = $row[3];
  					$respuesta[$contador]["cuero"] = $row[4];
					$respuesta[$contador]["clip"] = $row[5];
        		    $respuesta[$contador]["etiqueta"] = $row[6];
					$respuesta[$contador]["chapa"] = $row[7];
					$respuesta[$contador]["codigo"] = $row[8];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	function obtener_sin_instructivos() {
		$con = new DBmanejador;
        if($con->conectar() == true) {
			$anio = date("Y");
			$mes = date("m") - 01;
			$dia = date("d");
			$fec = $anio."-".$mes."-".$dia;
		    $consulta = "SELECT	o.num_orden,o.fecha, concat(f.nombre_familia,' ::: ',e.nombre_estilo ),cl.nombre, c.descripcion, cu.descripcion ,d.detalle_id
						 FROM	estilo AS e, familia AS f, tordenesproduccion AS o, tdetalleordenesproduccion AS d, tcolores AS c, tcueros AS cu, tpropiedades AS p, tclientes AS cl
						 WHERE	o.cliente_id=cl.cliente_id AND d.orden_id=o.orden_id AND d.familia_id=f.familia_id AND p.color_id=c.color_id AND p.cuero_id=cu.cuero_id AND d.propiedad_id=p.prop_id AND e.estilo_id=f.estilo_id AND d.aprobado_instructivo=0 AND o.fecha>'".$fec."'";
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['0'];
					$respuesta[$contador]['familia'] = $row['2'];
					$respuesta[$contador]['cliente'] = $row['3'];
					$respuesta[$contador]['color'] = $row['4'];
					$respuesta[$contador]['cuero'] = $row['5'];
					$respuesta[$contador]['codigo'] = $row['6'];
                  	$contador = $contador + 1;
  				}
				return $respuesta;
			}
		}
	}
	
	function obtener_instructivos_buscando($modelo) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$anio = date("Y");
			$mes = date("m") - 01;
			$dia = date("d");
			$fec = $anio."-".$mes."-".$dia;
		    $consulta = "SELECT	o.num_orden,o.fecha, concat(f.nombre_familia,' ::: ',e.nombre_estilo ),cl.nombre, c.descripcion, cu.descripcion ,d.detalle_id
						 FROM	estilo AS e, familia AS f, tordenesproduccion AS o, tdetalleordenesproduccion AS d, tcolores AS c, tcueros AS cu, tpropiedades AS p, tclientes AS cl
						 WHERE	o.cliente_id=cl.cliente_id AND d.orden_id=o.orden_id AND d.familia_id=f.familia_id AND p.color_id=c.color_id AND p.cuero_id=cu.cuero_id AND d.propiedad_id=p.prop_id AND e.estilo_id=f.estilo_id AND d.aprobado_instructivo=0 AND (e.nombre_estilo LIKE '%".$modelo."%' OR f.nombre_familia LIKE '%".$modelo."%') AND  o.fecha>'".$fec."'";
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP']= $row['0'];
					$respuesta[$contador]['familia']= $row['2'];
					$respuesta[$contador]['cliente']= $row['3'];
					$respuesta[$contador]['color']= $row['4'];
					$respuesta[$contador]['cuero']= $row['5'];
					$respuesta[$contador]['codigo']= $row['6'];
                  	$contador=$contador+1;
  				}
				return $respuesta;
		  	}
		}
	}
	
	function obtener_instructivos_familia($modelo) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "SELECT	concat(f.nombre_familia,' ::: ',e.nombre_estilo ),cl.nombre, i.instructivo_id, i.archivo,i.descripcion
						 FROM	estilo AS e, familia AS f, tclientes AS cl, tinstructivos AS i
						 WHERE	i.cliente_id=cl.cliente_id AND i.familia_id=f.familia_id AND e.estilo_id=f.estilo_id AND f.nombre_familia LIKE '%".$modelo."%'";             
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

		 	if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['modelo'] = $row['0'];
					$respuesta[$contador]['cliente'] = $row['1'];
					$respuesta[$contador]['codigo'] = $row['2'];
					$respuesta[$contador]['archivo'] = $row['3'];
					$respuesta[$contador]['descripcion'] = $row['4'];
                  	$contador = $contador + 1;
  				}
				return $respuesta;
			}
		}
	}
	
	function obtener_con_instructivos() {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$anio = date("Y");
			$mes = date("m") - 02;
			$dia = date("d");
			$fec = $anio."-".$mes."-".$dia;
		    $consulta = "SELECT	i.instructivo_id
								, concat(f.nombre_familia
								, ' ::: ',e.nombre_estilo ) as producto
								, cl.nombre as cliente
								, i.archivo
								, i.descripcion
								, i.fecha as fec
								, cu.descripcion
						 FROM	  estilo AS e
								, familia AS f
								, tclientes AS cl
								, tinstructivos AS i
								, tcueros AS cu
						WHERE       i.cliente_id=cl.cliente_id 
								AND i.familia_id=f.familia_id 
								AND e.estilo_id=f.estilo_id 
								AND i.actual=1
								AND cu.cuero_id=i.cuero_id
						ORDER BY producto, cliente";
			
//			echo "consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['codigo'] = $row['0'];
					$respuesta[$contador]['modelo'] = $row['1'];
					$respuesta[$contador]['cuero'] = $row['6'];
					$respuesta[$contador]['cliente'] = $row['2'];
					$respuesta[$contador]['archivo'] = $row['3'];
					$respuesta[$contador]['descripcion'] = $row['4'];
                  	$contador = $contador + 1;
				}
				return $respuesta;
		  	}
		}
	}
	
	function obtener_instructivos_id($modelo) {
		$con = new DBmanejador;
		if($con->conectar() == true) {
			$consulta = "SELECT	concat(f.nombre_familia,' ::: ',e.nombre_estilo )
								, cu.descripcion
								, cl.nombre
								, i.instructivo_id
								, i.archivo
								, i.descripcion
								, i.fecha
								, concat(u.nombres,' ',u.apellidos )
								, i.url
						FROM	estilo AS e
								, familia AS f
								, tclientes AS cl
								, tinstructivos AS i
								, tusuarios AS u
								, tcueros AS cu
						WHERE	i.cliente_id = cl.cliente_id
								AND i.familia_id=f.familia_id 
								AND e.estilo_id=f.estilo_id 
								AND cu.cuero_id=i.cuero_id 
								AND u.usuario_id=i.usuario 
								AND i.instructivo_id=".$modelo."";
             
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		 	if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["familia"] = $row[0];
					$respuesta[$contador]["cuero"] = $row[1];
					$respuesta[$contador]["cliente"] = $row[2];
  					$respuesta[$contador]["codigo"] = $row[3];
					$respuesta[$contador]["url"] = $row[8];
  					$respuesta[$contador]["instructivo"] = $row[4];
  					$respuesta[$contador]["descripcion"] = $row[5];
					$respuesta[$contador]["fecha"] = $row[6];
           			$respuesta[$contador]["usuario"] = $row[7];
					$contador = $contador + 1;
                  	$contador = $contador + 1;
  				}
				return $respuesta;
			}
		}
	}
	
	function obtener_familia_estilo2($codigo) {
		$con = new DBmanejador;
        if($con->conectar() == true) {			
			$consulta = "SELECT	f.familia_id,
								e.estilo_id,
								c.cliente_id,
								i.cuero_id
						 FROM 	estilo AS e, 
								familia AS f, 
								tclientes AS c, 
								tinstructivos AS i
						 WHERE 	i.cliente_id=c.cliente_id AND 
								i.familia_id=f.familia_id AND 
								e.estilo_id=f.estilo_id AND 
								i.instructivo_id='".$codigo."'";
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                  
		 	if (!$resultado)
				return false;
		 	else {
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[0]['f'] = $row['0'];
					$respuesta[0]['e'] = $row['1'];
					$respuesta[0]['c'] = $row['2'];
					$respuesta[0]['cu'] = $row['3'];
  				}
				return $respuesta;
		  	}
		}
	}
	
	function obtener_similares2($familia, $estilo) {
		$con = new DBmanejador;
        if($con->conectar() == true) {
			$anio = date("Y");
			$mes = date("m")-01;
			$dia = date("d");
			$fec = $anio."-".$mes."-".$dia;
		    $consulta = "SELECT	o.num_orden,
								o.fecha, 
								concat(f.nombre_familia,' ::: ',e.nombre_estilo ), 
								c.descripcion, cu.descripcion ,
								d.detalle_id,
								cli.nombre,
								d.instructivo_id
						 FROM	estilo AS e, familia AS f, tordenesproduccion AS o, 
								tcolores AS c, tcueros AS cu, tpropiedades AS p, tclientes AS cli,
								tdetalleordenesproduccion AS d LEFT JOIN tdetalle_asignacion AS da ON
								d.detalle_id=da.detalle_id  AND da.cerrada!=1
						 WHERE	o.cliente_id=cli.cliente_id AND d.orden_id=o.orden_id AND 
								d.familia_id=f.familia_id AND p.color_id=c.color_id AND 
								p.cuero_id=cu.cuero_id AND d.propiedad_id=p.prop_id AND 
        						e.estilo_id=f.estilo_id  AND d.familia_id=".$familia." AND f.estilo_id=".$estilo;
			//echo $consulta;
            $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		 	if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['OP'] = $row['0'];
					$respuesta[$contador]['fecha'] = $row['1'];
					$respuesta[$contador]['familia'] = $row['2'];
					$respuesta[$contador]['color'] = $row['3'];
					$respuesta[$contador]['cuero'] = $row['4'];
					$respuesta[$contador]['codigo'] = $row['5'];
					$respuesta[$contador]['cliente'] = $row['6'];
					$respuesta[$contador]['instructivo'] = $row['7'];
                  	$contador = $contador + 1;
  				}
				return $respuesta;
		  	}
		}
	}
	
	
	
	function listar_mostrar_instructivos () {
		$con = new DBmanejador;
		if($con->conectar() == true) {
		    $consulta = "SELECT	i.instructivo_id, f.nombre_familia
								, e.nombre_estilo, cl.nombre as cliente
								, i.archivo, i.descripcion, i.url
								, cu.descripcion AS cuero
						 FROM	  estilo AS e
								, familia AS f
								, tclientes AS cl
								, tinstructivos AS i
								, tcueros AS cu
						WHERE       i.cliente_id=cl.cliente_id 
								AND i.familia_id=f.familia_id 
								AND e.estilo_id=f.estilo_id 
								AND i.actual=1
								AND cu.cuero_id=i.cuero_id
						ORDER BY f.nombre_familia, e.nombre_estilo, cliente";
			
//			echo "consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]['codigo'] = $row['instructivo_id'];
					$respuesta[$contador]['url'] = $row['url'];					
					$respuesta[$contador]['producto'] = $row['nombre_familia'];
					$respuesta[$contador]['estilo'] = $row['nombre_estilo'];
					$respuesta[$contador]['cuero'] = $row['cuero'];
					$respuesta[$contador]['cliente'] = $row['cliente'];
					$respuesta[$contador]['archivo'] = $row['archivo'];
					$respuesta[$contador]['descripcion'] = $row['descripcion'];
                  	$contador = $contador + 1;
				}
				return $respuesta;
		  	}
		}
	}
	function listar_un_instructivos ($cod_instru)
	 {
		$con = new DBmanejador;
		if($con->conectar() == true) {
		    $consulta = "SELECT	i.instructivo_id, f.nombre_familia
								, e.nombre_estilo, cl.nombre as cliente
								, i.archivo, i.descripcion, i.url
								, cu.descripcion AS cuero
						 FROM	  estilo AS e
								, familia AS f
								, tclientes AS cl
								, tinstructivos AS i
								, tcueros AS cu
						WHERE       i.cliente_id=cl.cliente_id 
								AND i.familia_id=f.familia_id 
								AND e.estilo_id=f.estilo_id 
								AND i.actual=1
								AND cu.cuero_id=i.cuero_id and instructivo_id='".$cod_instru."'
						";
			
			//echo "consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
					
				
  				
			if (!$resultado)
				return false;
		 	else {
				
				if ($row = mysql_fetch_array($resultado)) {
					$respuesta['codigo'] = $row['instructivo_id'];
					$respuesta['url'] = $row['url'];					
					$respuesta['producto'] = $row['nombre_familia'];
					$respuesta['estilo'] = $row['nombre_estilo'];
					$respuesta['cuero'] = $row['cuero'];
					$respuesta['cliente'] = $row['cliente'];
					$respuesta['archivo'] = $row['archivo'];
					$respuesta['descripcion'] = $row['descripcion'];
                  	
				}
				return $respuesta;
		  	}
		}
	}
	
}	
?>