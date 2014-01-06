<?php
require_once('../../clases/includes/dbmanejador.php');
  
class Rechazo{


	function Rechazo(){
	}
	
	//muestra el detalle de la orden, sacando los datos de la tabla hoja
	function buscar_asignacion($num_asignacion){
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta = "
			SELECT
			topr.cup_num AS cup,
			tda.asignacion_detalle_id AS asignacion,
			CONCAT(p.apellidos, ' ', p.nombres) AS completo,
			p.clase AS categoria,
			tda.fecha_inicio AS fini,
			tda.fecha_finalizacion AS ffin,
			tda.fecha_reprogramacion AS frep,
			tda.cantidad_asignada As cantidad,
			f.nombre_familia AS modelo,
			es.nombre_estilo AS tipo,
			col.descripcion AS color,
			clip.descripcion AS clip,
			cu.descripcion AS cuero,
			tda.observaciones,
			tda.entrega_corte AS entregado,
			p.personal_id,
			tda.fecha_entrega_corte,
			tda.usuario_entrega_corte,
			tda.usuario_entrega,tda.cerrada
			FROM
			`tdetalle_asignacion` tda, `personal` p, `tdetalleordenesproduccion` tdop,
			`tordenesproduccion` topr, `familia` f, estilo es, `tpropiedades` tpro,
			`tcolores` col, `tclips` clip, `tcueros` cu
			WHERE
			tda.personal_id = p.personal_id
            AND tda.detalle_id = tdop.detalle_id
            AND tdop.orden_id = topr.orden_id
            AND tdop.familia_id = f.familia_id
            AND f.estilo_id = es.estilo_id
            AND tdop.propiedad_id = tpro.prop_id
            AND tpro.color_id = col.color_id
            AND tpro.clip_id = clip.clip_id
            AND tpro.cuero_id = cu.cuero_id
            AND tda.asignacion_detalle_id = ".$num_asignacion."
			";
			///echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta -buscar asignacion- fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
				if ($row = mysql_fetch_array($resultado)){
					$respuesta["cup"] = $row['cup'];
					$respuesta["asignacion"] = $row['asignacion'];
					$respuesta["completo"] = $row['completo'];
					$respuesta["categoria"] = $row['categoria'];
					$respuesta["fini"] = $row['fini'];
					$respuesta["ffin"] = $row['ffin'];
					$respuesta["frep"] = $row['frep'];
					$respuesta["cantidad"] = $row['cantidad'];
					$respuesta["modelo"] = $row['modelo'];
					$respuesta["tipo"] = $row['tipo'];
					$respuesta["color"] = $row['color'];
					$respuesta["clip"] = $row['clip'];
					$respuesta["cuero"] = $row['cuero'];
					$respuesta["observaciones"] = $row['observaciones'];
					$respuesta["entregado"] = $row['entregado'];
					$respuesta["personal_id"] = $row['personal_id'];
					$respuesta["fecha_entrega_corte"] = $row['fecha_entrega_corte'];
					$respuesta["usuario_entrega_corte"] = $row['usuario_entrega_corte'];
					$respuesta["usuario_entrega"] = $row['usuario_entrega'];
					$respuesta["cerrada"] = $row['cerrada'];
				}
				return $respuesta;
			}
		 }
	}
	
	
    
	function resumen_rechazo($num_asignacion)
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT r.rechazo_id, r.asignacion_id, r.fecha_rechazo, p.nombres,p.apellidos,r.cantidad,r.area_responsable,r.detalle_fallo,a.fecha_finalizacion_arreglo
			FROM tarreglos a,trechazo r LEFT JOIN personal p ON r.responsable_id=p.personal_id 
			WHERE a.rechazo_id=r.rechazo_id and  r.asignacion_id=".$num_asignacion." order by r.fecha_rechazo";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - resumen_rechazos - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
			    $contador=0;
				while ($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["rechazo_id"] = $row['rechazo_id'];
					$respuesta[$contador]["asignacion_id"] = $row['asignacion_id'];
					$respuesta[$contador]["fecha"] = $row['fecha_rechazo'];
					$respuesta[$contador]["responsable"] =trim($row['apellidos']." ".$row['nombres']);
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["area_responsable"] = $row['area_responsable'];
					$respuesta[$contador]["tipo_fallo"] = $row['nombre_fallo'];
					$respuesta[$contador]["detalle"] = $row['detalle_fallo'];
					$respuesta[$contador]["fecha_finalizacion_arreglo"] = $row['fecha_finalizacion_arreglo'];
					$contador++;
					
				}
				return $respuesta;
			}
		
		}
	}
	
	function total_arreglado($num_asignacion)
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT SUM( a.cantidad )
			FROM tarreglos AS a, trechazo AS r
			WHERE a.rechazo_id = r.rechazo_id
			AND a.fecha_finalizacion_arreglo IS NOT NULL
			AND r.asignacion_id =".$num_asignacion;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - total_arreglado - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return 0;
			else {
			    $total=0;
				$row = mysql_fetch_array($resultado);
				$total=$total+$row[0];
                return $total;					
				
			}
		
		}
	}
	function total_rechazado($num_asignacion)
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT SUM(cantidad)
			FROM trechazo
			WHERE asignacion_id=".$num_asignacion;
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - total_rechazado - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return 0;
			else {
			    $total=0;
				$row = mysql_fetch_array($resultado);
				$total=$total+$row[0];
                return $total;					
				
			}
		
		}
	}
	function registrar_rechazo($num_asignacion,$cantidad,$falla,$usuario_rechazador,$fecha,$responsable_fallo,$responsable_arreglo)
	{

		$con = new DBmanejador;
		if($con->conectar()==true)
		{
		       $consulta= "INSERT  into 
			   			 trechazo(asignacion_id,fecha_rechazo,responsable_id,cantidad,area_responsable,usuario_rechazador) 					
						 values(".$num_asignacion.",'".$fecha."',".$responsable_fallo.",".$cantidad.",'".$falla."',".$usuario_rechazador.")";
						 
			//  echo $consulta;
              $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en ingresar rechazo ' . mysql_error());
		  	  $rechazo_id = mysql_insert_id();
               $consulta= "INSERT  into 
			   			 tarreglos(rechazo_id,fecha_inicio_arreglo,cantidad,responsable_arreglo) 					
						 values(".$rechazo_id.",'".$fecha."',".$cantidad.",".$responsable_arreglo.")";
				  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en ingresar arreglo ' . mysql_error());		 
  			  if (!$resultado) return false;
 			  else return true;
			 
		     }
	}
	
	function resumen_arreglos($num_asignacion)
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT a.arreglo_id, a.fecha_inicio_arreglo, a.fecha_finalizacion_arreglo, p.nombres,p.apellidos,a.cantidad
			FROM  trechazo r, tarreglos a LEFT JOIN personal p on a.responsable_arreglo=p.personal_id
			WHERE r.rechazo_id=a.rechazo_id and r.asignacion_id=".$num_asignacion." order by fecha_inicio_arreglo";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - resumen_rechazos - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
			    $contador=0;
				while ($row = mysql_fetch_array($resultado)){
				
					$respuesta[$contador]["arreglo_id"] = $row['arreglo_id'];
					$respuesta[$contador]["fecha"] = $row['fecha_inicio_arreglo'];
					$respuesta[$contador]["fecha_finalizacion_arreglo"] = $row['fecha_finalizacion_arreglo'];
					$respuesta[$contador]["responsable"] = $row['apellidos']." ".$row['nombres'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$contador++;
					
				}
				return $respuesta;
			}
		
		}
	}
	
	function registrar_confirmacion_arreglo($num_arreglo,$fecha)
	{
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {
			    $consulta="update tarreglos set fecha_finalizacion_arreglo='".$fecha."' WHERE arreglo_id=".$num_arreglo; 
				//echo $consulta;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar confirmacion de arreglo: ' . mysql_error());

			if (!$resultado) return false;
			else return true;
	 		}
	
	}
	
	function resumen_rechazo_actualizar($num_asignacion)
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT r.rechazo_id, r.asignacion_id, r.fecha_rechazo, CONCAT( p.nombres, ' ', p.apellidos ) AS responsable_fallo, 	
			r.cantidad, r.area_responsable, detalle_fallo, CONCAT( p1.nombres, ' ', p1.apellidos ) AS responsable_arreglo
			FROM trechazo r
			LEFT JOIN personal p ON r.responsable_id = p.personal_id, tarreglos a
			LEFT JOIN personal p1 ON a.responsable_arreglo = p1.personal_id
			WHERE r.rechazo_id = a.rechazo_id
			AND r.asignacion_id =".$num_asignacion." ORDER BY r.fecha_rechazo";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - resumen_rechazos - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
			    $contador=0;
				while ($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]["rechazo_id"] = $row['rechazo_id'];
					$respuesta[$contador]["asignacion_id"] = $row['asignacion_id'];
					$respuesta[$contador]["fecha"] = $row['fecha_rechazo'];
					$respuesta[$contador]["responsable_fallo"] =$row['responsable_fallo'];
					$respuesta[$contador]["cantidad"] = $row['cantidad'];
					$respuesta[$contador]["area_responsable"] = $row['area_responsable'];
					$respuesta[$contador]["tipo_fallo"] = $row['nombre_fallo'];
					$respuesta[$contador]["responsable_arreglo"] =$row['responsable_arreglo'];
					$respuesta[$contador]["detalle"] = $row['detalle_fallo'];
					$contador++;
					
				}
				return $respuesta;
			}
		
		}
	}
	
	function resumen_rechazo_actualizar_id($rechazo_id)
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT r.rechazo_id, r.asignacion_id, r.fecha_rechazo, CONCAT(p.clase,' - ',p.apellidos, ' ', p.nombres ) AS responsable_fallo, 	
			r.cantidad, r.area_responsable, r.detalle_fallo, CONCAT(p1.clase,' - ', p1.apellidos, ' ', p1.nombres ) AS responsable_arreglo,r.clasificacion_fallo_id
			FROM trechazo r
			LEFT JOIN personal p ON r.responsable_id = p.personal_id, tarreglos a
			LEFT JOIN personal p1 ON a.responsable_arreglo = p1.personal_id
			WHERE r.rechazo_id = a.rechazo_id
			AND r.rechazo_id =".$rechazo_id." ORDER BY r.fecha_rechazo";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - resumen_rechazos_actualizar_id - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
			   
					$row = mysql_fetch_array($resultado);
					$respuesta["rechazo_id"] = $row['rechazo_id'];
					$respuesta["asignacion_id"] = $row['asignacion_id'];
					$respuesta["fecha"] = $row['fecha_rechazo'];
					$respuesta["responsable_fallo"] =$row['responsable_fallo'];
					$respuesta["cantidad"] = $row['cantidad'];
					$respuesta["area_responsable"] = $row['area_responsable'];
					$respuesta["tipo_fallo"] = $row['nombre_fallo'];
					$respuesta["responsable_arreglo"] =$row['responsable_arreglo'];
					$respuesta["descripcion_fallo"] = $row['detalle_fallo'];
					$respuesta["clasificacion_fallo_id"] = $row['clasificacion_fallo_id'];
					return $respuesta;
			}
		
		}
	}
	
	function obtener_categoria_fallos()
	{
	     $con = new DBmanejador;
		if($con->conectar() == true)
		{
		    $consulta = "
			SELECT * 
			FROM tclasificacion_fallos";
			//echo "<br>La consulta: ".$consulta;
			$resultado = mysql_query($consulta) or die('La consulta - categoria fallos - fall&oacute;: ' . mysql_error());
		
			if (!$resultado)
				return false;
			else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado))
				{
					$respuesta[$contador]["clasificacion_fallo_id"] = $row['clasificacion_fallo_id'];
					$respuesta[$contador]["nombre_fallo"] = $row['nombre_fallo'];
					$respuesta[$contador]["descripcion_fallo"] = $row['descripcion_fallo'];
					$contador++;
				}
				return $respuesta;
			}
		
		}
	}
	
	function busqueda_personal($nombre, $num_asignacion,$tabla)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT		p.personal_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase
			FROM 		personal p, personal_puesto_trabajo ppt, detalle_".$tabla." c, hoja h, tdetalle_asignacion das, 
						tdetalleordenesproduccion dop
			WHERE		c.hoja_id=h.hoja_id and h.detalle_id=das.detalle_id and dop.detalle_id=das.detalle_id and 
						das.asignacion_detalle_id=".$num_asignacion." and 
						c.personal_id=p.personal_id and p.personal_id = ppt.personal_id AND
						CONCAT(p.apellidos,' ', p.nombres) LIKE '%".$nombre."%'
			ORDER BY	CONCAT(p.apellidos,' ', p.nombres)
			LIMIT 0,20
			";
			//echo "<li>".$consulta."</li>";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda personal fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return "";
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
	
	
	
	
	function busqueda_personal_maquinista($nombre, $num_asignacion)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT		p.personal_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase
			FROM 		personal p, personal_puesto_trabajo ppt, tdetalle_asignacion das
			WHERE		das.asignacion_detalle_id=".$num_asignacion." and das.personal_id=p.personal_id and
						p.personal_id = ppt.personal_id AND
						CONCAT(p.apellidos,' ', p.nombres) LIKE '%".$nombre."%'
			ORDER BY	CONCAT(p.apellidos,' ', p.nombres)
			LIMIT 0,20
			";
			//echo $consulta;
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
	
	function busqueda_personal_limpieza($nombre, $num_asignacion)
	{
		$con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT		p.personal_id, CONCAT(p.apellidos,' ', p.nombres) AS completo, p.clase
			FROM 		personal p, personal_puesto_trabajo ppt, tlimpieza li
			WHERE		li.asignacion_detalle_id=".$num_asignacion." and li.limpiador_id=p.personal_id and 
						p.personal_id = ppt.personal_id AND
						CONCAT(p.apellidos,' ', p.nombres) LIKE '%".$nombre."%'
			ORDER BY	CONCAT(p.apellidos,' ', p.nombres)
			LIMIT 0,20
			";
			//echo $consulta;
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
	
	
	function verificar_maquinista_valido($responsable_fallo,$num_asignacion)
	{
	            
	    $con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT		p.personal_id
			FROM 		personal p, personal_puesto_trabajo ppt, tdetalle_asignacion das
			WHERE		das.asignacion_detalle_id=".$num_asignacion." and das.personal_id=p.personal_id and
						p.personal_id = ppt.personal_id AND
						CONCAT(p.apellidos,' ', p.nombres) = '".$responsable_fallo."'";
			//echo $consulta;
            $resultado = mysql_query($consulta) or die ('La consulta busqueda personal fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return -1;
		 	else {
			        $contador=0;
				    while($row =mysql_fetch_array($resultado))
				  	{
						$retorno=$row['personal_id'];
						$contador++;
				  	}
					if($contador==0)
		                return -1;				
					else
					    return $retorno;
				}

		  	
		}
	
	}
	
	
	function verificar_limpiador_valido($responsable,$num_asignacion)
	{
	
	    $con = new DBmanejador;
		if($con->conectar() == true){
			$consulta= "
			SELECT		p.personal_id
			FROM 		personal p, personal_puesto_trabajo ppt, tlimpieza li
			WHERE		li.asignacion_detalle_id=".$num_asignacion." and li.limpiador_id=p.personal_id and 
						p.personal_id = ppt.personal_id AND
						CONCAT(p.apellidos,' ', p.nombres) = '".$responsable."'";
//			echo $consulta;
            $resultado = mysql_query($consulta) or die ('La consulta busqueda personal fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					
					$respuesta = $row['personal_id'];
					$contador ++;
				}
				if($contador==0)
				   return -1;
				else
				 	return $respuesta;
		  	}
		}
	
	
	}
	
	function verificar_empleado_valido($responsable)
	{
	     $con = new DBmanejador;
		  if($con->conectar() == true)
		  {
			$consulta= "
			SELECT		p.personal_id
			FROM 		personal p 
			WHERE		CONCAT(p.apellidos,' ', p.nombres) = '".$responsable."'";
	//		echo $consulta;
            $resultado = mysql_query($consulta) or die ('La consulta busqueda personal fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					
					$respuesta = $row['personal_id'];
					$contador ++;}
				if($contador==0)
				   return -1;
				else
				 	return $respuesta;
				}
		}
   }
	
	function actualizacion_informacion_rechazos($rechazo_id,$responsable_id,$cantidad,$area_responsable,$clasificacion_fallo_id,$detalle_fallo)
	{
	   
		    $con = new DBmanejador;
		   if($con->conectar()==true)
		   {
			    $consulta="update trechazo set cantidad=".$cantidad." , responsable_id=".$responsable_id." , area_responsable='".$area_responsable."' , detalle_fallo='".$detalle_fallo."' , clasificacion_fallo_id=".$clasificacion_fallo_id." WHERE rechazo_id=".$rechazo_id; 
				//echo $consulta;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en actualizacion_informacion_rechazos: ' . mysql_error());

			if (!$resultado) return false;
			else return true;
	 		}
	
	
	}
	
	
	function actualizacion_informacion_arreglos($rechazo_id,$responsable_arreglo,$cantidad)
	{
	
	        $con = new DBmanejador;
		   if($con->conectar()==true)
		   {
			    $consulta="update tarreglos set cantidad=".$cantidad." , responsable_arreglo=".$responsable_arreglo." WHERE 			
						   rechazo_id=".$rechazo_id; 
				//echo $consulta;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en actualizacion_informacion_arreglos: ' . mysql_error());

				if (!$resultado) return false;
				else return true;
	 		}
	}
	
}
?>