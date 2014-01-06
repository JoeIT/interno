<?php
require_once('../../clases/includes/dbmanejador.php');

class Asignacion{
	function Asignacion(){
	}

	function cambiar_entrega($did)
	{
		$con = new DBmanejador;
		
		if($con->conectar()==true)
		{
			$consulta = "update `tdetalle_asignacion` set entrega_almacen=1 where asignacion_detalle_id=".$did;

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener actualizacion: ' . mysql_error());

			if (!$resultado)
				return false;
			else 
			{
					return true;
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

			if (!$resultado)
				return false;
			else 
				return true;	
				 
			
		}
	}
	
	function sacar_materiales($did)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta = "
			SELECT top.num_orden AS orden, tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo,
				   tda.cantidad_asignada, CONCAT(m.nombre,' - ', m.descripcion), CONCAT(di.ancho,'x', di.largo) AS dimension,
				   CONCAT(f.nombre_familia,' ',te.descripcion,' ',tc.descripcion,' ',tcl.descripcion) AS producto
				   
			FROM `tdetalle_asignacion` tda, `personal` p, tdespiece d, tmateriales m,tcomponentes c, tpiezas pie, tdimensiones di,
				  tpropiedades tp, familia f, tdetalleordenesproduccion tdo, testilos te, tcolores tc, tclips tcl, tordenesproduccion top
				  
			WHERE tda.personal_id = p.personal_id AND tda.asignacion_detalle_id =".$did." AND 
				  tda.detalle_id = d.detalle_id AND tda.detalle_id = c.detalle_id AND 
				  d.material_id = m.material_id AND c.pieza_id = pie.pieza_id AND 
				  di.dimension_id = pie.dimension_id AND tdo.detalle_id = tda.detalle_id AND
				  tp.prop_id=tdo.propiedad_id AND f.familia_id=tdo.familia_id AND
				  f.estilo_id=te.estilo_id AND tp.color_id=tc.color_id AND tp.clip_id=tcl.clip_id AND top.orden_id=tdo.orden_id AND
				  m.tipo_material_id is null AND tda.entrega_almacen=0
				  
			ORDER BY tda.fecha_asignacion DESC";


			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
				
				while($row = mysql_fetch_array($resultado))
				{
					$respuesta[$contador]["Orden"]= $row[0];
					$respuesta[$contador]["Asignacion"]= $row[1];
					$respuesta[$contador]["Personal"]= $row[2];	 
					$respuesta[$contador]["Cantidad"]=$row[3];
					$respuesta[$contador]["Material"]= $row[4];
					$respuesta[$contador]["Dimension"]= $row[5];
					$respuesta[$contador]["Producto"]= $row[6];
					
					
					$contador ++;					
				}
				return $respuesta;
			}
		}
	}

	 function resumen_inicial($op_id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
			$consulta= "
			SELECT tdop.detalle_id, tdop.cantidad, f.nombre_familia, e.nombre_estilo, tco.descripcion AS color, tcli.descripcion AS clip, ra.asignados, ra.entregados, ra.pendientes
			
			FROM
			`tordenesproduccion` top, `tdetalleordenesproduccion` tdop, `familia` f, `resultados_asignacion` ra, `tpropiedades` tp,
			`estilo` e, `tcolores` tco, `tclips` tcli
			
			WHERE
			 top.orden_id = tdop.orden_id AND
			 tdop.familia_id = f.familia_id AND
			 tdop.detalle_id = ra.detalle_id AND
			 tdop.propiedad_id = tp.prop_id AND
			 tp.color_id = tco.color_id AND
			 tp.clip_id = tcli.clip_id AND
			 f.estilo_id = e.estilo_id AND
			 top.orden_id = ".$op_id."
			ORDER by f.nombre_familia ";

            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else {
				$contador=0;

				while($row = mysql_fetch_array($resultado)){
				  $respuesta[$contador]["detalle_id"]= $row[0];
  				  $respuesta[$contador]["cantidad"]= $row[1];
  				  $respuesta[$contador]["nombre_familia"]= $row[2];
				  $respuesta[$contador]["nombre_estilo"]= $row[3];
  				  $respuesta[$contador]["color"]= $row[4];
  				  $respuesta[$contador]["clip"]= $row[5];
  				  $respuesta[$contador]["asignados"]= $row[6];
				  $respuesta[$contador]["entregados"]= $row[7];
                  $respuesta[$contador]["pendientes"]= $row[8];

				  $contador = $contador + 1;
  				}


				return $respuesta;

			}
		 }
      }
	  
	  
	  
	function obtener_detalle_orden($op_id, $de_id){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= '
SELECT  e.nombre_estilo,o.descripcion,c.descripcion,l.descripcion,s.descripcion,t.descripcion,m.nombre_familia,d.cantidad,d.unidad,d.pedido,d.observacion,d.lugargrabado,d.prioridad,d.grabado,d.tipoletra,d.detalle_id, ra.asignados, ra.entregados, ra.pendientes
FROM  tdetalleordenesproduccion d, estilo e, tcueros o, tcolores c, tclips l, tchapas s, tetiquetas t, tpropiedades p, familia m, `resultados_asignacion` ra
WHERE
 d.propiedad_id=p.prop_id and m.estilo_id=e.estilo_id and
 p.cuero_id=o.cuero_id and p.color_id=c.color_id and
 p.clip_id=l.clip_id and p.sello_id=s.chapa_id and
 p.etiqueta_id=t.etiqueta_id and d.familia_id=m.familia_id and
 d.estado=1 and
 ra.detalle_id = d.detalle_id and
 
 d.orden_id = '.$op_id.' and
 d.detalle_id = '.$de_id;

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["Cantidad"]= $row[7];
					$respuesta["Unidad"]= $row[8];	 
					$respuesta["Modelo"]=$row[6];
					$respuesta["Estilo"]= $row[0];
					$respuesta["Origen Cuero"]= $row[1];
					$respuesta["Color"]= $row[2];
					$respuesta["Clip"]= $row[3];
					$respuesta["Sello/Herraje"]= $row[4];
					$respuesta["Etiqueta"]= $row[5];

					$fuente="";
					if(trim($row[14]!=""))
						$fuente="(".$row[14].")";
					$respuesta["Observaciones"]=$row[10].$row[13].$fuente;

					$respuesta["Lugar Sellado"]= $row[11];															
					$respuesta["detalle_id"]= $row[15];
					
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
		if($con->conectar()==true){
			$consulta= "
			SELECT p.personal_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase
			FROM
			personal p, personal_puesto_trabajo ppt
			
			WHERE
			p.personal_id = ppt.personal_id AND
			ppt.puesto_trabajo_id = ".$puesto." AND
			CONCAT(p.apellidos,' ', p.nombres) LIKE '%".$nombre."%'
			
			ORDER BY
			CONCAT(p.apellidos,' ', p.nombres)
			LIMIT 0,7
			";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
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
		if($con->conectar()==true){
			$consulta= "
			insert into tdetalle_asignacion (detalle_id, personal_id, cantidad_asignada, cantidad_muestra, fecha_asignacion, responsable_asignacion, fecha_inicio, fecha_medio, fecha_finalizacion, observaciones) values ($detalle_id, $personal_id, $cantidad_asignada, $cantidad_muestra, '".$fecha_asignacion."', $responsable_asignacion, '".$fecha_inicio."', '".$fecha_medio."', '".$fecha_finalizacion."', '".$observaciones."')
			";
			//echo "<br>La consulta: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			$consulta= "
			update resultados_asignacion
			set
				asignados = asignados + ".$cantidad_asignada.", pendientes = pendientes - ".$cantidad_asignada."
			where
				detalle_id = ".$detalle_id;

			//echo "<br>La consulta: ".$consulta;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		}
      }



	function ver_detalle_resultados($did){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "
			SELECT tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo,
				   tda.cantidad_asignada, tda.fecha_inicio, tda.fecha_finalizacion, tda.impresion_num, tda.impresion_max
			FROM
			`tdetalle_asignacion` tda, `personal` p
			WHERE
			tda.personal_id = p.personal_id AND
			tda.detalle_id = ".$did." ORDER BY tda.fecha_asignacion DESC";

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				$contador = 0;
			
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["asignacion_detalle_id"]= $row[0];
					$respuesta[$contador]["completo"]= $row[1];	 
					$respuesta[$contador]["catidad_asignada"]=$row[2];
					$respuesta[$contador]["fecha_inicio"]= $row[3];
					$respuesta[$contador]["fecha_finalizacion"]= $row[4];
					$respuesta[$contador]["impresion_num"]= $row[5];
					$respuesta[$contador]["impresion_max"]= $row[6];
					
					$contador ++;					
				}
				return $respuesta;
			}
		}
	}


	function ver_modificar_detalle_resultados($daid){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "
			SELECT tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase, tda.cantidad_asignada, tda.cantidad_muestra, tda.fecha_inicio, tda.fecha_finalizacion, tda.observaciones, tda.personal_id
			FROM
			`tdetalle_asignacion` tda, `personal` p
			WHERE
			tda.personal_id = p.personal_id AND
			tda.asignacion_detalle_id = ".$daid;

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["asignacion_detalle_id"]= $row[0];
					$respuesta["completo"]= $row[1];	 
					$respuesta["clase"]=$row[2];
					$respuesta["cantidad_asignada"]= $row[3];
					$respuesta["cantidad_muestra"]= $row[4];
					$respuesta["fecha_inicio"]= $row[5];
					$respuesta["fecha_finalizacion"]= $row[6];
					$respuesta["observaciones"]= $row[7];
					$respuesta["personal_id"]= $row[8];
				}
				return $respuesta;
			}
		}
	}


	function sacar_cantidad($daid){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "
			SELECT tda.cantidad_asignada
			FROM
			`tdetalle_asignacion` tda
			WHERE
			tda.asignacion_detalle_id = ".$daid;

			//echo "<br>La consulta verificar personal: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["cantidad_asignada"] = $row[0];
				}
				return $respuesta;
			}
		}
	}

	function sacar_pendientes($did){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "
			SELECT pendientes FROM `resultados_asignacion` WHERE detalle_id = ".$did;

			//echo "<br>La consulta verificar personal: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["asignados"] = $row[0];
				}
				return $respuesta;
			}
		}
	}


	  //reestablecer valores
	  function reestablecer_asignar_detalle_resultados($detalle_id, $cantidad_asignada){
	  	$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "
			update resultados_asignacion
			set
				asignados = asignados - ".$cantidad_asignada.", pendientes = pendientes + ".$cantidad_asignada."
			where
				detalle_id = ".$detalle_id;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		}
      }
	  
	  
	  //eliminamos de tdetalle asignacion
	  function eliminar_asignar_detalle($daid){
	  	$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "
			delete from `tdetalle_asignacion`
			where
			asignacion_detalle_id = ".$daid;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		}
      }
	  
	  
	  //
	  function modificar_detalle_resultados($detalle_id, $daid, $personal_id, $cantidad_asignada, $cantidad_muestra, $fecha_asignacion, $responsable_asignacion, $fecha_inicio, $fecha_medio, $fecha_finalizacion, $observaciones){
	  	$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "
			update tdetalle_asignacion
			set
			personal_id = ".$personal_id.",
			cantidad_asignada = ".$cantidad_asignada.",
			cantidad_muestra = ".$cantidad_muestra.",
			fecha_asignacion = '".$fecha_asignacion."',
			responsable_asignacion = ".$responsable_asignacion.",
			fecha_inicio = '".$fecha_inicio."',
			fecha_medio = '".$fecha_medio."',
			fecha_finalizacion = '".$fecha_finalizacion."',
			observaciones = '".$observaciones."' 
			where
			asignacion_detalle_id = ".$daid;
           
			echo "<br>La consulta: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			$consulta= "
			update resultados_asignacion
			set
				asignados = asignados + ".$cantidad_asignada.", pendientes = pendientes - ".$cantidad_asignada."
			where
				detalle_id = ".$detalle_id;

			echo "<br>La consulta2: ".$consulta;
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		}
      }

	  //modificar cada vez que se imprima
	  function modificar_impresion($daid){
	  	$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "
			update tdetalle_asignacion
			set
				impresion_num = 1
			where
				asignacion_detalle_id = ".$daid;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		}
      }


	function reporte_modificar_detalle_resultados($daid){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "
			SELECT tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, tda.cantidad_asignada,
				   tda.cantidad_muestra, tda.fecha_inicio, tda.fecha_finalizacion, f.nombre_familia, e.nombre_estilo,
				   tcol.descripcion AS color, tcli.descripcion AS clip, tcu.descripcion AS cuero, tdop.observacion,
				   top.num_orden, tda.fecha_reprogramacion
			FROM
			`tordenesproduccion` top, `tdetalle_asignacion` tda, `personal` p, `tdetalleordenesproduccion` tdop,
			`familia` f, `estilo` e, `tpropiedades` tpr, `tcolores` tcol, `tclips` tcli, `tcueros` tcu
			WHERE
			top.orden_id = tdop.orden_id AND
			tda.personal_id = p.personal_id AND
			tda.detalle_id = tdop.detalle_id AND
			tdop.familia_id = f.familia_id AND
			f.estilo_id = e.estilo_id AND
			tdop.propiedad_id = tpr.prop_id AND
			tpr.color_id = tcol.color_id AND
			tpr.clip_id = tcli.clip_id AND
			tpr.cuero_id = tcu.cuero_id AND
			
			tda.asignacion_detalle_id = ".$daid;

			$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener detalle orden: ' . mysql_error());

			if (!$resultado)
				return false;
			else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta["asignacion_detalle_id"]= $row[0];
					$respuesta["completo"]= $row[1];	 
					$respuesta["cantidad_asignada"]= $row[2];
					$respuesta["cantidad_muestra"]= $row[3];
					$respuesta["fecha_inicio"]= $row[4];
					$respuesta["fecha_finalizacion"]= $row[5];
					$respuesta["nombre_familia"]= $row[6];
					$respuesta["nombre_estilo"]= $row[7];
					$respuesta["color"]= $row[8];
					$respuesta["clip"]= $row[9];
					$respuesta["cuero"]= $row[10];
					$respuesta["observacion"]= $row[11];
					$respuesta["num_orden"]= $row[12];
					$respuesta["fecha_reprogramacion"]= $row[13];
				}
				return $respuesta;
			}
		}
	}


	  function busqueda_personal_reprogramacion($numero, $puesto){
	  	$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "
			SELECT tda.asignacion_detalle_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase, tda.fecha_inicio, tda.fecha_finalizacion, tda.fecha_reprogramacion
			FROM
			personal p, personal_puesto_trabajo ppt, `tdetalle_asignacion` tda
			
			WHERE
			p.personal_id = ppt.personal_id AND
			ppt.puesto_trabajo_id = ".$puesto." AND
			p.personal_id = tda.personal_id AND
			tda.asignacion_detalle_id = ".$numero."
			
			ORDER BY tda.asignacion_detalle_id
			";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				while($row = mysql_fetch_array($resultado)){
					$respuesta['asignacion_detalle_id'] = $row[0];
					$respuesta['completo'] = $row[1];
					$respuesta['clase'] = $row[2];
					$respuesta['fecha_inicio'] = $row[3];
					$respuesta['fecha_finalizacion'] = $row[4];
					$respuesta['fecha_reprogramacion'] = $row[5];
				}
				return $respuesta;
		  	}
		}
      }


	  //modificar fecha de reprogramacion
	  function modificar_fecha_reprogramacion($daid, $fec_reprog, $responsable_reprogramacion){
	  	$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "
			update tdetalle_asignacion
			set
				fecha_reprogramacion = '".$fec_reprog."',
				responsable_reprogramacion = ".$responsable_reprogramacion."
			where
				asignacion_detalle_id = ".$daid;
			
			//echo "<br>La consulta: ".$consulta;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		}
      }
}
?>