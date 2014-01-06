<?php
include_once('../../clases/includes/dbmanejador.php');
include_once('familias.php');

class Detalle_orden {
	function consulta_detalle_orden($id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = 'SELECT	s.producto_id,e.descripcion,c.descripcion,q.tipocuero,o.descripcion,
						 		t.descripcion,l.descripcion,s.estado
             			FROM	tproductos s, tpropiedades p, tcolores o, estilos e, tclips c, tcueros q,
								tetiquetas t, tsellos l
						WHERE   s.prop_id=p.prop_id and p.estilo_id=e.estilo_id and p.clip_id=c.clip_id
						        and p.cuero_id=q.cuero_id and p.color_id=o.color_id and
								p.etiqueta_id=t.etiqueta_id and p.sello_id=l.sello_id';
			
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; consulta detalle orden: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["descripcion"] = $row[0];
					$respuesta[$contador]["estilo"] = $row[1];
					$respuesta[$contador]["clip"] = $row[2];
					$respuesta[$contador]["tipo cuero"] = $row[3];
					$respuesta[$contador]["color"] = $row[4];
					$respuesta[$contador]["etiqueta"] = $row[5];
					$respuesta[$contador]["sello"] = $row[6];
					$respuesta[$contador]["estado"] = $row[7];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	
	function adicionar_producto_detalle($orden_id,$cantidad,$unidad,$prioridad,$obs,$pedido,$propiedad_id,$grabado,$lugar_grabado,$tipo_letra,$obs_interior,$familia_id, $tipo) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "INSERT  into tdetalleordenesproduccion (orden_id,cantidad,unidad,prioridad,observacion,pedido,propiedad_id,grabado,lugargrabado,tipoletra,obs_interior,familia_id, tipo) values(".$orden_id.",".$cantidad.",'".$unidad."',".$prioridad.",'".$obs."','".$pedido."',".$propiedad_id.",'".$grabado."','".$lugar_grabado."','".$tipo_letra."','".$obs_interior."',".$familia_id.",".$tipo.")";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en registrar detalle: ' . mysql_error());
			$detalle_id = mysql_insert_id();
			$consulta = "INSERT  into resultados_asignacion (detalle_id,asignados,pendientes,entregados) values(".$detalle_id.",0,".$cantidad.",0)";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en registrar resultados asignacion: ' . mysql_error());
			$consulta = "INSERT  into hoja (detalle_id,cantidad) values(".$detalle_id.",".$cantidad.")";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en registrar hoja: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	
	function obtener_detalle_orden($orden_id) {
		$con = new DBmanejador;
	    if ($con->conectar()==true) {
			$consulta = '
			SELECT
				  e.nombre_estilo
				, o.descripcion
				, c.descripcion
				, l.descripcion
				, s.descripcion
				, t.descripcion
				, m.nombre_familia
				, d.cantidad
				, d.unidad
				, d.pedido
				, d.observacion
				, d.lugargrabado
				, d.prioridad
				, d.grabado
				, d.tipoletra
				, d.detalle_id
				, d.obs_interior
				, d.tipo
				, it.clase
				,d.bloqueado
			FROM  tdetalleordenesproduccion d
				, estilo e
				, tcueros o
				, tcolores c
				, tclips l
				, tchapas s
				, tetiquetas t
				, tpropiedades p
				, familia m
				, indicadores_tipo it
			WHERE d.orden_id='.$orden_id.' 
				and d.propiedad_id=p.prop_id 
				and m.estilo_id=e.estilo_id 
				and p.cuero_id=o.cuero_id 
				and p.color_id=c.color_id 
				and p.clip_id=l.clip_id 
				and p.sello_id=s.chapa_id 
				and p.etiqueta_id=t.etiqueta_id 
				and d.familia_id=m.familia_id
				AND d.tipo = it.indicadores_tipo_id				
				and d.estado=1 ';
            // echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());
						 
			if (!$resultado)
				return false;
			else {
				$contador = 0;
			
			while ($row = mysql_fetch_array($resultado)) {
				$respuesta[$contador]["Cant"] = $row[7];	
				$respuesta[$contador]["Uni"] = $row[8];
				$respuesta[$contador]["Modelo"] = $row[6];
				$respuesta[$contador]["Estilo"] = $row[0];
				$respuesta[$contador]["OrigenCuero"] = $row[1];
				$respuesta[$contador]["Color"] = $row[2];
				$respuesta[$contador]["Clip"] = $row[3];
				$respuesta[$contador]["Sello"] = $row[4];
				$respuesta[$contador]["Etiqueta"] = $row[5];     				            
				$respuesta[$contador]["Pedido"] = $row[9];
				if ($row['bloqueado']==1)
				$respuesta[$contador]["bloqueado"] ='<div style="background:#CC3300">Cancelado</div>';
				else 
				$respuesta[$contador]["bloqueado"] ='Activo';
				
				$fuente = "";
				if (trim($row[14] != ""))
					$fuente = "(".$row[14].")";
				else
					$fuente = " ";
					$respuesta[$contador]["Grabado"] = $row[13].$fuente;
					$respuesta[$contador]["LugarSellado"] = $row[11];
					
					if ($row['obs_interior'] != "") {
						$obs_internas = "[".$row['obs_interior']."]";
					} else
						$obs_internas = "";
					
					$respuesta[$contador]["tipo"] = $row['clase'];
					$respuesta[$contador]["Observaciones"] = $row[10].$obs_internas;
					$respuesta[$contador]["codigo"] = $row[15];
					
					$contador = $contador+1;
				}
				return $respuesta;
			}
		}
	}
	
	function obtener_detalle_orden_despieces($orden_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = '
			SELECT	e.nombre_estilo,o.descripcion,c.descripcion,l.descripcion,s.descripcion,t.descripcion,m.nombre_familia,d.cantidad,d.unidad,d.observacion,d.lugargrabado,d.grabado,d.tipoletra,d.detalle_id,d.despiece,d.modificar_despiece
			FROM	tdetalleordenesproduccion d, estilo e, tcueros o, tcolores c, tclips l, tchapas s, tetiquetas t, tpropiedades p, familia m
			WHERE	d.orden_id='.$orden_id.' and d.propiedad_id=p.prop_id and m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and p.color_id=c.color_id and p.clip_id=l.clip_id and p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and d.familia_id=m.familia_id and d.estado=1 ';
		
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while ($row = mysql_fetch_array($resultado)) {
					
					$respuesta[$contador]["codigo"]= $row[13];
					$respuesta[$contador]["modificar_despiece"]= $row['modificar_despiece'];
					$respuesta[$contador]["Producto"]=$row[6];
					$respuesta[$contador]["Estilo"]= $row[0];
					$respuesta[$contador]["Origen Cuero"]= $row[1];
					$respuesta[$contador]["Color"]= $row[2];
					$respuesta[$contador]["Clip"]= $row[3];
					$respuesta[$contador]["Sello/Herraje"]= $row[4];
					$respuesta[$contador]["Etiqueta"]= $row[5];     				                         		 $fuente="";
					if(trim($row[12]!=""))
						$fuente="(".$row[12].")";
					$respuesta[$contador]["Observaciones"]=$row[9].$row[11].$fuente;
					$respuesta[$contador]["Lugar Sellado"]= $row[10];
					$respuesta[$contador]["Unidad"]= $row[8];
					$respuesta[$contador]["Cant."]= $row[7];	
					$respuesta[$contador]["Despiece"]= $row[14];
					$contador=$contador+1;
				}
				return $respuesta;
			}
		}
	}
	
	function verificar_existencia_despiece($detalle_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = 'SELECT despiece FROM  tdetalleordenesproduccion WHERE detalle_id='.$detalle_id;
                 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener propiedad id: ' . mysql_error());
					 if (!$resultado) return false;
					 else
					 {
							$row = mysql_fetch_array($resultado);
					  		return $row[0];
	
			         }
		 }  
	  
	
			   
			
			}		 	 
			function obtener_detalle_orden_despiece($orden_id,$detalle_id)
			{
			
			   $con = new DBmanejador;
	          	 if($con->conectar()==true)
    	     	 {
				 
                     $consulta= 'SELECT e.nombre_estilo,o.descripcion,c.descripcion,l.descripcion,s.descripcion,t.descripcion,m.nombre_familia,d.cantidad,d.unidad,d.observacion,d.lugargrabado,d.grabado,d.tipoletra,d.detalle_id
				     FROM  tdetalleordenesproduccion d, estilo e, tcueros o, tcolores c, tclips l, tchapas s, tetiquetas t, tpropiedades p, familia m
					 WHERE d.orden_id='.$orden_id.' and d.detalle_id='.$detalle_id.' and d.propiedad_id=p.prop_id and m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and p.color_id=c.color_id and p.clip_id=l.clip_id and p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and d.familia_id=m.familia_id and d.estado=1 order by m.nombre_familia';
                    
           				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());
						 
						 
           			     if (!$resultado) return false;
						 else
						 {
							
							     $row = mysql_fetch_array($resultado);
								 $respuesta["Cantidad"]= $row[7];	
								 $respuesta["Unidad"]= $row[8];	 
				 		 		 $respuesta["Modelo"]=$row[6];
								 $respuesta["Estilo"]= $row[0];
								 $respuesta["Cuero"]= $row[1];
 								 $respuesta["Color"]= $row[2];
								 $respuesta["Clip"]= $row[3];
								 $respuesta["Sello"]= $row[4];
								 $respuesta["Etiqueta"]= $row[5];     				                                 $fuente="";
								 if(trim($row[12]!=""))
								    $fuente="(".$row[12].")";
								 $respuesta["Observaciones"]=$row[9].$row[11].$fuente;
								 $respuesta["Lugar Sellado"]= $row[10];
								 $respuesta["codigo"]= $row[13];

  
							return $respuesta;
	
			   }
		 }
			
			
			}
			
			
		function deshabilitar_detalle($detalle){
			$con = new DBmanejador;
			if($con->conectar()==true){
				$consulta = "update tdetalleordenesproduccion set estado=0 WHERE detalle_id=".$detalle;
				$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				
				$consulta = "delete from resultados_asignacion where detalle_id=(".$detalle.")";
				$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en eliminar resultados asignacion: ' . mysql_error());
				
				$consulta = "DELETE FROM hoja WHERE detalle_id = ".$detalle;
				$resultado = mysql_query($consulta) or die('La consulta -eliminar hoja- fall&oacute;: ' . mysql_error());

				if (!$resultado)
					return false;
				else
					return true;
	 		}
		}
		
		
			  function obtener_datos_detalle($detalle_id,$orden_id)
			  {
			       $con = new DBmanejador;
	          	 if($con->conectar()==true)
    	     	 {
				      
					 $consulta= 'SELECT e.nombre_estilo,o.descripcion,c.descripcion,l.descripcion,s.descripcion,t.descripcion,m.nombre_familia,d.cantidad,d.unidad,d.pedido,d.observacion,d.lugargrabado,d.prioridad,d.grabado,d.tipoletra,d.detalle_id,d.obs_interior, d.tipo
				     FROM  tdetalleordenesproduccion d,estilo e, tcueros o, tcolores c, tclips l, tchapas s, tetiquetas t, tpropiedades p, familia m
					 WHERE d.orden_id='.$orden_id.' and d.propiedad_id=p.prop_id and m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and p.color_id=c.color_id and p.clip_id=l.clip_id and p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and d.familia_id=m.familia_id and  d.estado=1 and d.detalle_id='.$detalle_id.' order by d.pedido,m.nombre_familia';
					  
                                         
                       
           				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener datos detalle: ' . mysql_error());
						 
						 
           			     if (!$resultado) return false;
						 else
						 {
							$contador=0;
							$row = mysql_fetch_array($resultado);	
							$respuesta["cantidad"]= $row[7];	
							$respuesta["unidad"]= $row[8];	 
				 		 	$respuesta["modelo"]=$row[6];
							$respuesta["estilo"]= $row[0];
							$respuesta["cuero"]= $row[1];
 							$respuesta["color"]= $row[2];
							$respuesta["clip"]= $row[3];
							$respuesta["sello"]= $row[4];
							$respuesta["etiqueta"]= $row[5];     				            
 				            $respuesta["pedido"]= $row[9];
							$respuesta["fuente"]= $row[14];
                            $respuesta["grabado"]=$row[13];
							$respuesta["observaciones"]=$row[10];
							$respuesta["lugargrabado"]= $row[11];
							$respuesta["prioridad"]= $row[12];
							$respuesta["codigo"]= $row[15];
							$respuesta["obs_interior"]= $row[16];
							$respuesta["tipo"]= $row['tipo'];
					  		return $respuesta;
	
			   }
		 }
			    
			  }
			  
	function obtener_propiedad_id($detalle_id)		  
	{
	    
		 $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
				 
                     $consulta= 'SELECT prop_id FROM  tdetalleordenesproduccion WHERE detalle_id='.$detalle_id;
                     $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener propiedad id: ' . mysql_error());
					 if (!$resultado) return false;
					 else
					 {
							$contador=0;
							$row = mysql_fetch_array($resultado);
					  		return $row[0];
	
			         }
		 }  
	  
	
	}
	
	function actualizar_producto_detalle ($detalle_id,$cantidad,$propiedad_id,$unidad,$prioridad,$obs,$pedido,$grabado,$lugar_grabado,$tipo_letra,$obs_interior,$familia_id, $tipo) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "
			UPDATE	tdetalleordenesproduccion
			SET		cantidad = ".$cantidad."
					,propiedad_id = ".$propiedad_id."
					,unidad = '".$unidad."'
					,prioridad = ".$prioridad."
					,observacion = '".$obs."'
					,pedido = '".$pedido."'
					,grabado = '".$grabado."'
					,lugargrabado = '".$lugar_grabado."'
					,tipoletra = '".$tipo_letra."'
					,obs_interior = '".$obs_interior."'
					,familia_id = ".$familia_id."
					,tipo = ".$tipo."
			WHERE	detalle_id = ".$detalle_id;
			$resultado = mysql_query($consulta) or die('La consulta -actualizar detalle- fall&oacute; en actualizar producto detalle: ' . mysql_error());
			
			$consulta = "
			UPDATE	resultados_asignacion
			SET		pendientes = ".$cantidad."
			WHERE	detalle_id = ".$detalle_id;
			$resultado = mysql_query($consulta) or die('La consulta -actualizar resultados asignacion- fall&oacute; en actualizar producto detalle: ' . mysql_error());
			
			$consulta = "
			UPDATE	hoja
			SET		cantidad = ".$cantidad."
			WHERE	detalle_id = ".$detalle_id;
			$resultado = mysql_query($consulta) or die('La consulta -actualizar hoja- fall&oacute; en actualizar producto detalle: ' . mysql_error());			
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
			
	function registrar_despiece($detalle_id,$estado)
	{
	        $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tdetalleordenesproduccion set despiece=".$estado." where detalle_id=".$detalle_id;
				
				//echo "<br>con: ".$consulta;
				
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en actualizar producto detalle:'.mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	
	}
	 function eliminar_detalle($detalle)
	 {
	    $con = new DBmanejador;
		if($con->conectar()==true)
		{
		    $consulta="delete from tdetalleordenesproduccion  WHERE detalle_id=".$detalle; 
	        $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                   
		   // $consulta= "delete from resultados_asignacion where detalle_id=(".$detalle.")";
		//	$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en eliminar resultados asignacion: ' . mysql_error());
					if (!$resultado) return false;
					else return true;
	 	}		  
    }
	
	
///mio
			  function obtener_observacion_detalle($detalle_id)
			  {
			       $con = new DBmanejador;
	          	 if($con->conectar()==true)
    	     	 {
				      
					 $consulta= 'SELECT d.observacion,d.obs_interior
				     FROM  tdetalleordenesproduccion d
					 WHERE d.detalle_id='.$detalle_id;
					  
                                         
                       
           				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener datos detalle: ' . mysql_error());
						 
						 
           			     if (!$resultado) return false;
						 else
						 {
							$contador=0;
							$row = mysql_fetch_array($resultado);	
							
							$respuesta["observaciones"]=$row[0];
							
							$respuesta["obs_interior"]= $row[1];
					  		return $respuesta;
	
			   }
		 }
			    
			  }	
	


function generar_excel_orden($oid){
	$con = new DBmanejador;
	if($con->conectar()==true){
		$consulta = "
		SELECT
			  d.cantidad
			, m.nombre_familia AS modelo
			, e.nombre_estilo AS tipo
			, o.descripcion AS origencuero
			, c.descripcion AS color
			, l.descripcion AS clip
			, s.descripcion AS sello
			, d.pedido
			, d.observacion AS observaciones
			, d.grabado AS grabado
			, d.lugargrabado AS lugargrabado
		FROM
			  tdetalleordenesproduccion d
			  , estilo e, tcueros o, tcolores c, tclips l, tchapas s, tetiquetas t
			  , tpropiedades p, familia m
		WHERE
				d.propiedad_id=p.prop_id
			and m.estilo_id=e.estilo_id
			and p.cuero_id=o.cuero_id
			and p.color_id=c.color_id
			and p.clip_id=l.clip_id
			and p.sello_id=s.chapa_id
			and p.etiqueta_id=t.etiqueta_id
			and d.familia_id=m.familia_id
			and d.estado=1
			
			AND d.orden_id=".$oid."
		ORDER BY modelo, tipo";
		
		$resultado=mysql_query($consulta) or die('La consulta -excel- fall&oacute;: ' . mysql_error());
		
		if (!$resultado)
			return false;
		else {
			$contador=0;
			while($row = mysql_fetch_array($resultado)){
				$respuesta[$contador]["cantidad"]= $row["cantidad"];
  				$respuesta[$contador]["modelo"]= $row["modelo"];
  				$respuesta[$contador]["tipo"]= $row["tipo"];
  				$respuesta[$contador]["origencuero"]= $row["origencuero"];
                $respuesta[$contador]["color"]= $row["color"];
				$respuesta[$contador]["clip"]= $row["clip"];
				$respuesta[$contador]["sello"]= $row["sello"];
  				$respuesta[$contador]["pedido"]= $row["pedido"];
  				$respuesta[$contador]["observaciones"]= $row["observaciones"];
  				$respuesta[$contador]["grabado"]= $row["grabado"];
				$respuesta[$contador]["lugargrabado"]= $row["lugargrabado"];
				
				$contador=$contador+1;
  			}
			return $respuesta;
		}
	}
}


	function obtener_asignaciones_detalle_orden($orden_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = '
			SELECT	d.detalle_id, r.asignados
			FROM	tdetalleordenesproduccion d, resultados_asignacion r
			WHERE	d.orden_id='.$orden_id.' and d.detalle_id=r.detalle_id';
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('Fall&oacute; obtener_asignaciones_detalle_orden-asignaciones-: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$row[0]]["asignados"] = $row[1];
					$contador = $contador + 1;
				}
				
				if ($contador > 0) {
					foreach ($respuesta as $detalle => $valor) {
						$consulta = '
						SELECT	COUNT(d.detalle_id) as numero_hojas
						FROM	tdetalleordenesproduccion d, hoja h
						WHERE	d.detalle_id='.$detalle.' and d.detalle_id=h.detalle_id';
						//echo $consulta;
						$resultado = mysql_query($consulta) or die('La consulta fall&oacute; obtener_asignaciones_detalle_orden-hojas-: ' . mysql_error());
						$num_hojas = mysql_fetch_array($resultado);
						$num_hojas = $num_hojas[0];
						//echo "numero de hojas:".$num_hojas;
						if ($num_hojas != 1)
							$respuesta[$detalle]['corte'] = 1;
						else {
							$row = mysql_fetch_array($resultado);
							$consulta = '
							SELECT	COUNT(c.personal_id)
							FROM	tdetalleordenesproduccion d, hoja h, detalle_corte c
							WHERE	d.detalle_id='.$detalle.' and d.detalle_id=h.detalle_id and c.hoja_id=h.hoja_id and c.personal_id!=0 GROUP BY c.hoja_id';
							//echo $consulta;
							$res_corte = mysql_query($consulta) or die('fall&oacute; obtener_asignaciones_detalle_orden-corte-: ' . mysql_error());
							$num_asignaciones = mysql_fetch_array($res_corte);
							//echo "numero de corte:".$num_asignaciones;
							$num_asignaciones = $num_asignaciones[0];
							//echo "numero de corte:".$num_asignaciones;
							if ($num_asignaciones != 0)
								$respuesta[$detalle]['corte'] = 1;
							else
								$respuesta[$detalle]['corte'] = 0;
						}
					}
					return $respuesta;
				}
			}
		}
	}
	
	
     function obtener_detalle_orden_almacen($orden_id)
			{
			     $con = new DBmanejador;
	          	 if($con->conectar()==true)
    	     	 {
				 
                     $consulta= " SELECT tdop.detalle_id,top.cup_num as Orden, tdop.cantidad as Cantidad, f.nombre_familia as Modelo, e.nombre_estilo as Tipo
       , c.descripcion as Clip, col.descripcion as Color, tdop.pedido as Pedido,
       CONCAT(tdop.observacion, ' ', tdop.obs_interior) as obs,tda.asignacion_detalle_id as num_asignacion, CONCAT(per.nombres,' ',per.apellidos) as Maquinista, tda.cantidad_asignada as Asignada
FROM   tordenesproduccion top , familia f, estilo e, tclips c, `tpropiedades` prop, `tcolores` col ,
       tdetalleordenesproduccion tdop LEFT JOIN
       `tdetalle_asignacion` tda ON tda.detalle_id=tdop.detalle_id LEFT JOIN `personal` per
       ON tda.personal_id=per.personal_id
WHERE  top.orden_id=tdop.orden_id
       and tdop.familia_id=f.familia_id
       and f.estilo_id=e.estilo_id
       and prop.clip_id=c.clip_id
       and prop.color_id=col.color_id
       and prop.prop_id=tdop.propiedad_id
       and top.orden_id='".$orden_id."'
ORDER BY modelo, tipo, clip, color, maquinista desc";

                  //  echo $consulta;
           				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());
						 
						 
           			     if (!$resultado) return false;
						 else
						 {
							$contador=0;
							while($row = mysql_fetch_array($resultado))
							{
							     $respuesta[$contador]["detalle_id"]= $row['detalle_id'];
								 $respuesta[$contador]["cantidad"]= $row['Cantidad'];
							     $respuesta[$contador]["modelo"]=$row['Modelo'];
								 $respuesta[$contador]["tipo"]= $row['Tipo'];
								 $respuesta[$contador]["clip"]= $row['Clip'];
 								 $respuesta[$contador]["color"]= $row['Color'];
								 $respuesta[$contador]["pedido"]= $row['Pedido'];
								 $respuesta[$contador]["observaciones"]= $row['obs'];
								 $respuesta[$contador]["orden"]= $row['Orden'];
								 $respuesta[$contador]["asignacion"]= $row['num_asignacion'];
								 $respuesta[$contador]["maquinista"]= $row['Maquinista'];
								 $respuesta[$contador]["asignada"]= $row['Asignada'];
								 $contador=$contador+1;
  		            		}
  
							return $respuesta;
	
			   }
		 }
				 
				 
				 
				 }   	

	function obtener_datos_pedido($pedido) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = '
				SELECT	  e.nombre_estilo
						, o.descripcion
						, c.descripcion
						, l.descripcion
						, s.descripcion
						, t.descripcion
						, m.nombre_familia
						, d.cantidad
						, d.unidad
						, d.pedido
						, d.observacion
						, d.lugargrabado
						, d.prioridad
						, d.grabado
						, d.tipoletra
						, d.detalle_id
						, d.obs_interior
						, d.detalle_id
						, top.num_orden
				FROM	  tdetalleordenesproduccion d
						, estilo e
						, tcueros o
						, tcolores c
						, tclips l
						, tchapas s
						, tetiquetas t
						, tpropiedades p
						, familia m
						, tordenesproduccion top
				WHERE	    d.propiedad_id = p.prop_id
						and d.orden_id = top.orden_id
						and m.estilo_id = e.estilo_id
						and p.cuero_id = o.cuero_id
						and p.color_id = c.color_id
						and p.clip_id = l.clip_id
						and p.sello_id = s.chapa_id
						and p.etiqueta_id = t.etiqueta_id
						and d.familia_id = m.familia_id
						and d.estado = 1
						and (d.pedido="'.$pedido.'" or d.observacion="'.$pedido.'")
				ORDER BY	d.detalle_id desc
				LIMIT 0, 20';
				
					  
			// echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute; en obtener datos detalle: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$respuesta[$contador]["cantidad"] = $row[7];	
					$respuesta[$contador]["unidad"] = $row[8];	 
				 	$respuesta[$contador]["modelo"] =$row[6];
					$respuesta[$contador]["estilo"] = $row[0];
					$respuesta[$contador]["cuero"] = $row[1];
 					$respuesta[$contador]["color"] = $row[2];
					$respuesta[$contador]["clip"] = $row[3];
					$respuesta[$contador]["sello"] = $row[4];
					$respuesta[$contador]["etiqueta"] = $row[5];     				            
 				    $respuesta[$contador]["pedido"] = $row[9];
					$respuesta[$contador]["fuente"] = $row[14];
					$respuesta[$contador]["grabado"] = $row[13];
					$respuesta[$contador]["observaciones"] = $row[10];
					$respuesta[$contador]["lugargrabado"] = $row[11];
					$respuesta[$contador]["prioridad"] = $row[12];
					$respuesta[$contador]["codigo"] = $row[15];
					$respuesta[$contador]["obs_interior"] = $row[16];
					$respuesta[$contador]["detalle_id"] = $row['detalle_id'];
					$respuesta[$contador]["num_orden"] = $row['num_orden'];
					
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}
	
	
	/////////////////////////////////////////////////
	function obtener_datos_desde_detalle($detalle_id) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = '
				SELECT	  e.nombre_estilo
						, o.descripcion
						, c.descripcion
						, l.descripcion
						, s.descripcion
						, t.descripcion
						, m.nombre_familia
						, d.cantidad
						, d.unidad
						, d.pedido
						, d.observacion
						, d.lugargrabado
						, d.prioridad
						, d.grabado
						, d.tipoletra
						, d.detalle_id
						, d.obs_interior
						, d.detalle_id
						, d.tipo
				FROM	  tdetalleordenesproduccion d
						, estilo e
						, tcueros o
						, tcolores c
						, tclips l
						, tchapas s
						, tetiquetas t
						, tpropiedades p
						, familia m
				
				WHERE	    d.propiedad_id = p.prop_id
						and m.estilo_id = e.estilo_id
						and p.cuero_id = o.cuero_id
						and p.color_id = c.color_id
						and p.clip_id = l.clip_id
						and p.sello_id = s.chapa_id
						and p.etiqueta_id = t.etiqueta_id
						and d.familia_id = m.familia_id
						and d.estado = 1
						and d.detalle_id = '.$detalle_id;
					  
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta -obtener_datos_desde_detalle- fall&oacute; en obtener datos detalle: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)) {
					$respuesta["cantidad"] = $row[7];	
					$respuesta["unidad"] = $row[8];	 
				 	$respuesta["modelo"] =$row[6];
					$respuesta["estilo"] = $row[0];
					$respuesta["cuero"] = $row[1];
 					$respuesta["color"] = $row[2];
					$respuesta["clip"] = $row[3];
					$respuesta["sello"] = $row[4];
					$respuesta["etiqueta"] = $row[5];     				            
 				    $respuesta["pedido"] = $row[9];
					$respuesta["fuente"] = $row[14];
					$respuesta["grabado"] = $row[13];
					$respuesta["observaciones"] = $row[10];
					$respuesta["lugargrabado"] = $row[11];
					$respuesta["prioridad"] = $row[12];
					$respuesta["codigo"] = $row[15];
					$respuesta["obs_interior"] = $row[16];
					$respuesta["detalle_id"] = $row['detalle_id'];
					$respuesta["tipo"] = $row['tipo'];
				}
				return $respuesta;
			}
		}
	}
//para los indicadores
	function actualizar_tipo_detalle($orden_id, $tipo) {
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			$consulta = "
			UPDATE	tdetalleordenesproduccion
			SET		tipo = ".$tipo."
			WHERE	orden_id = ".$orden_id;
					  
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta -obtener_datos_desde_detalle- fall&oacute; en obtener datos detalle: ' . mysql_error());
		}
	}
	
}		
?>