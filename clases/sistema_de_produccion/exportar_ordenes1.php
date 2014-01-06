<?php
    include_once('familias.php');
 //   include_once('clases/sistema_de_produccion/familias_estilos.php');
    include_once('detalle_orden.php');
    include_once('ordenprod.php');
	include_once('propiedades.php');
	include_once('despiece.php');
	include_once('clientes.php');
	include_once('posicionpiezas.php');
 
  
  
  class Exportar_Orden
  {
  	   
       function exportar_cabecera($numero_orden)
	   {
	          $conexion=@mysql_connect("192.168.0.100","sistemas","sistema135");
			  mysql_select_db("extra_macaws",$conexion);
 			  $consulta= "SELECT o.nro_orden, o.fecha_orden, o.cup, c.nombre, o.fecha_entrega, o.fecha_reprogramacion, o.cod_usuario, o.obs_orden 	
				FROM orden o,cliente c WHERE o.cod_cliente=c.cod_cliente and  nro_orden LIKE '%".$numero_orden."%'";
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			  $row = mysql_fetch_array($resultado);
			  $orden=new OrdenProd;
			  $cliente=new Cliente;
			  $cliente_id=$cliente->verificar_cliente(trim($row[3]));
			  if($cliente_id==-1)
			  {
			     echo "Cliente no valido";
			   
			  }
			  else
			  {
			      $num_orden_macaws_bd=$orden->consulta_numero_orden();
			      $num_cup=$orden->obtener_num_cup($num_orden_macaws_bd);
				  $conexion=@mysql_connect("localhost","root","");
			      mysql_select_db("macaws_bd",$conexion);
                  $consulta= "INSERT  into tordenesproduccion(num_orden,cliente_id,usuario_id,cup_num,fecha,fechaentrega,observacion,fecharepro) 				                    values('".$num_orden_macaws_bd."',".$cliente_id.",".$row[6].",".$num_cup.",'".$row[1]."','".$row[4]."','".$row[7]."','".$row[5]."')";
	   		      $res=mysql_query($consulta) or die('La consulta fall&oacute; en ingresar nueva orden de produccion: ' . mysql_error());
			      return $num_orden_macaws_bd;
			  
			  }
	   }
	   
	   
	   function exportar_detalle_orden($num_orden,$num_orden_macaws_bd)
	   {
	        $detalle_orden=new Detalle_orden;
		    $familias=new Familia;
			$orden=new OrdenProd;
            $propiedades=new Propiedad;
			$despiece=new Despiece;
			$posiciones=new PosicionPieza;
	        $conexion=@mysql_connect("192.168.0.100","sistemas","sistema135");
			mysql_select_db("extra_macaws",$conexion);
			$consulta= "SELECT o.nro_orden, d.cantidad, d.unidad, p.descripcion, e.estilo_es, d.cod_cuero, d.cod_color, d.cod_forro, d.cod_clip, 		
						d.cod_herraje, d.cod_etiqueta, d.pedido, d.obs_detalle, d.lugar_sellado, d.prioridad, d.precio, d.cod_empaque, d.despachado
						FROM detalle d, orden o, producto p, estilo e
						WHERE d.cod_orden = o.cod_orden AND p.cod_producto = d.cod_producto 
						AND e.cod_estilo = d.cod_estilo AND o.nro_orden LIKE '%".$num_orden."%'";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			$conexion=@mysql_connect("192.168.0.100","localhost","");
			mysql_select_db("macaws_bd",$conexion);
		    $contador=0;
		   while($row = mysql_fetch_array($resultado))
		   {
			   $contador++;
			   $familia= trim($row[3])." ::: ".trim($row[4]);
			   $familia_id=$familias->verifica_familia_valida($familia);
			   if($familia_id==-1)
			   {
	     
			     echo "familia no valida : ".$familia." <br>";
			   }
			   else
			   {
	  		 
	  				 $ordenes=$orden->consultar_busqueda($num_orden_macaws_bd,"num_orden");
		 		     $orden_id=$ordenes[0]["codigo"];
        		     $num_orden=$ordenes[0]["num_orden"];
					 $orden_id=$ordenes[0]["codigo"];
			 		 $clip_id=$row['cod_clip']+1;
					 $etiqueta_id=$row['cod_etiqueta']+1;
	    		     $prop_id=$propiedades->verificar_propiedad($clip_id,$row['cod_cuero'],$row['cod_color'],$etiqueta_id,$row['cod_herraje']);          	         if($prop_id==-1){
	        			  $prop_id=$propiedades->insertar_nueva_propiedad($clip_id,$row['cod_cuero'],$row['cod_color'],$etiqueta_id,$row['cod_herraje']);      							  }
					 //  echo "propiedades_id:".$prop_id."<br>";
		    		$consulta= "INSERT  into tdetalleordenesproduccion 
						(orden_id,cantidad,unidad,prioridad,observacion,pedido,propiedad_id,grabado,lugargrabado,tipoletra,obs_interior,familia_id) 
						values(".$orden_id.",".$row['cantidad'].",'".$row['unidad']."',".$row['prioridad'].",'".$row['obs_detalle']."','".$row['pedido
						']."',".$prop_id.",'".$grabado."','".$row['lugar_sellado']."','".$tipo_letra."','".$obs_interior."',".$familia_id.")";
					//echo $consulta."<br>";
					//echo $row[0];
				 $res=mysql_query($consulta) or die('La consulta fall&oacute; : ' . mysql_error());
				 $detalle_id_nuevo = mysql_insert_id();
				 $consulta= "INSERT  into resultados_asignacion (detalle_id,asignados,pendientes,entregados) values(".$detalle_id_nuevo.",0,".$row['cantidad'].",0)";
				 $res=mysql_query($consulta) or die('La consulta fall&oacute; en registrar resultados asignacion: ' . mysql_error());
				 $consulta= "INSERT  into hoja (detalle_id,cantidad) values(".$detalle_id_nuevo.",".$row['cantidad'].")";
				  $res=mysql_query($consulta) or die('La consulta fall&oacute; en registrar hoja: ' . mysql_error());
				 
			  
			//  	   echo $row[0].'<br>';
	    
	  			}
	
			} 
	   echo "Total items migrados: ".$contador;
   }
   
   function obtener_detalle($orden,$producto,$estilo,$cuero,$color,$clip,$sello,$etiqueta)
   {
          
	      $familias=new Familia;
		  $propiedades=new Propiedad;
	  	  $con=@mysql_connect("localhost","root","");
		  mysql_select_db("macaws_bd",$con);
		  $familia= trim($producto." ::: ".$estilo);
	   	  $familia_id=$familias->verifica_familia_valida($familia);
		  $clip_id=$clip+1;
		  $etiqueta_id=$etiqueta+1;
		  $prop_id=$propiedades->verificar_propiedad($clip_id,$cuero,$color,$etiqueta_id,$sello);  
		  $consulta= "SELECT detalle_id
					  FROM tdetalleordenesproduccion d, tordenesproduccion o
					  WHERE d.orden_id = o.orden_id and o.num_orden='".$orden."' and familia_id=".$familia_id." and propiedad_id=".$prop_id;
		 
		$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		while($row = mysql_fetch_array($resultado))
		{
		   //  echo "dentro de la funcion obtener detalle - numero de detalle en macaws_bd:".$row['detalle_id']."<br>";
		     $detalle_id=$row['detalle_id'];
		}
		return $detalle_id;
	
	}
	
   
   function exportar_despiece($num_orden,$num_orden_macaws_bd)
   {
       $detalle_orden=new Detalle_orden;
  $familias=new Familia;
 
  $propiedades=new Propiedad;
  $despiece=new Despiece;
  $posiciones=new PosicionPieza;
         $conexion=@mysql_connect("192.168.0.100","sistemas","sistema135");
	   mysql_select_db("extra_macaws",$conexion);
	   $consulta= "SELECT d.cod_orden, d.cod_detalle, d.cod_producto, d.cod_estilo, d.cod_cuero, d.cod_color, d.cod_clip, d.cod_herraje, 
	   				d.cod_etiqueta, d.cod_forro, p.descripcion,e.estilo_es 
					FROM orden o, detalle d, producto p, estilo e  
					WHERE p.cod_producto=d.cod_producto and e.cod_estilo=d.cod_estilo and o.cod_orden=d.cod_orden and  o.nro_orden='".$num_orden."'";
	  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	  while($row = mysql_fetch_array($resultado))
	  { 
	     /*echo "-------------------------------------------------------------------------------<br>";
	     echo "DETALLE:".$row['cod_detalle']."<br>";
		 echo "-------------------------------------------------------------------------------<br>";
		 echo "producto:".$row['descripcion']."<br>";
		 echo "estilo:".$row['estilo_es']."<br>";*/
         $cod_articulo=$row['cod_producto']."-".$row['cod_estilo']."-".$row['cod_cuero']."-".$row['cod_color']."-".$row['cod_forro']."-".$row['cod_clip']."-".$row['cod_herraje']."-".$row['cod_etiqueta'];
		
		$detalle_id=$this->obtener_detalle($num_orden_macaws_bd,$row['descripcion'],$row['estilo_es'],$row['cod_cuero'],$row['cod_color'],$row['cod_clip'],$row['cod_herraje'],$row['cod_etiqueta']);
     $conexion=@mysql_connect("192.168.0.100","sistemas","sistema135");
		mysql_select_db("extra_macaws",$conexion);
		$consulta= "SELECT * from componentes c,pieza p where c.cod_pieza=p.cod_pieza and  cod_articulo='".$cod_articulo."'";
     	$resultado2=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		while($row = mysql_fetch_array($resultado2))
		{
		    $posicion_id=$posiciones->verificar_posicion($row['nombre_p']);
			if($posicion_id==-1)
			    $despiece->nueva_posicion($row['nombre_p']);
			$posicion_id=$posiciones->verificar_posicion($row['nombre_p']);
		    $despiece->registrar_componente_nuevo($detalle_id,$row['cod_material'],$posicion_id,$row['largo_p'],$row['ancho_p'],$row['cantidad_p']);
			
			$despiece->realizar_calculos_materiales($detalle_id);
			$detalle_orden->registrar_despiece($detalle_id,'1');
		   /* echo "posicion:".$row['nombre_p']."<br>";
		    echo "largo:".$row['largo_p']."<br>";
		    echo "ancho:".$row['ancho_p']."<br>";
		    echo "cantidad:".$row['cantidad_p']."<br>";
			echo "cod_material:".$row['cod_material']."<br>";
			echo "numero de detalle en macaws_bd:".$detalle_id."<br>";*/
			
			
		}
    }
   
   }
   
  function exportar_orden_produccion($numero_orden)
  {
      $num_orden_macaws_bd=$this->exportar_cabecera($numero_orden);
 	  $this->exportar_detalle_orden($numero_orden,$num_orden_macaws_bd);
	  $this->exportar_despiece($numero_orden,$num_orden_macaws_bd);
  }
}
 
 /************** EXPORTA CABECERAS DE LA ORDEN**************************/ 
//$familia_estilo=new FamiliaEstilos;
//$propiedad_familia_estilo=new PropiedadFamiliaEstilo;


/*$conexion=@mysql_connect("localhost","root","");
mysql_select_db("extra_macaws",$conexion);

	$consulta= "SELECT nro_orden, fecha_orden, cup, cod_cliente, fecha_entrega, fecha_reprogramacion, cod_usuario, obs_orden 	
				FROM orden WHERE nro_orden LIKE '%2007%'";
    
	$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	mysql_select_db("macaws_bd",$conexion);
    while($row = mysql_fetch_array($resultado))
	{

	   $consulta= "INSERT  into tordenesproduccion(num_orden,cliente_id,usuario_id,cup_num,fecha,fechaentrega,observacion,fecharepro) 				                    values('".$row[0]."',".$row[3].",".$row[6].",".$row[2].",'".$row[1]."','".$row[4]."','".$row[7]."','".$row[5]."')";
	   
	   
              $res=mysql_query($consulta) or die('La consulta fall&oacute; en ingresar nueva orden de produccion: ' . mysql_error());
			  
			  	   echo $row[0].'<br>';
	  
	
	}
	
	
	*/
	/*************** exporta detalle orden********************************/
	/*$conexion=@mysql_connect("localhost","root","");
	mysql_select_db("extra_macaws",$conexion);
	$consulta= "SELECT o.nro_orden, d.cantidad, d.unidad, p.descripcion, e.estilo_es, d.cod_cuero, d.cod_color, d.cod_forro, d.cod_clip, d.cod_herraje, d.cod_etiqueta, d.pedido, d.obs_detalle, d.lugar_sellado, d.prioridad, d.precio, d.cod_empaque, d.despachado
FROM detalle d, orden o, producto p, estilo e
WHERE d.cod_orden = o.cod_orden
AND p.cod_producto = d.cod_producto
AND e.cod_estilo = d.cod_estilo
AND o.nro_orden LIKE '%2007%'
";
$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
mysql_select_db("macaws_bd",$conexion);

	    $contador=0;

    while($row = mysql_fetch_array($resultado))
	{
	   $contador++;
	   $familia= trim($row[3])." ::: ".trim($row[4]);
	   $familia_id=$familias->verifica_familia_valida($familia);
	   if($familia_id==-1)
	   {
	     
	     echo "familia no valida : ".$familia." <br>";
	   }
	   else
	   {
	  		 
	  		 $ordenes=$orden->consultar_busqueda($row[0],"num_orden");
 		     $orden_id=$ordenes[0]["codigo"];
             $num_orden=$ordenes[0]["num_orden"];
			 $orden_id=$ordenes[0]["codigo"];
	 		 $clip_id=$row['cod_clip']+1;
			 $etiqueta_id=$row['cod_etiqueta']+1;
	         $prop_id=$propiedades->verificar_propiedad($clip_id,$row['cod_cuero'],$row['cod_color'],$etiqueta_id,$row['cod_herraje']);        
	  	     if($prop_id==-1)
  	 		 {
	       
				  $prop_id=$propiedades->insertar_nueva_propiedad($clip_id,$row['cod_cuero'],$row['cod_color'],$etiqueta_id,$row['cod_herraje']);      		  
	  		 }
		 //  echo "propiedades_id:".$prop_id."<br>";
	    		$consulta= "INSERT  into tdetalleordenesproduccion 
						(orden_id,cantidad,unidad,prioridad,observacion,pedido,propiedad_id,grabado,lugargrabado,tipoletra,obs_interior,familia_id) 
						values(".$orden_id.",".$row['cantidad'].",'".$row['unidad']."',".$row['prioridad'].",'".$row['obs_detalle']."','".$row['pedido
						']."',".$prop_id.",'".$grabado."','".$row['lugar_sellado']."','".$tipo_letra."','".$obs_interior."',".$familia_id.")";
				//echo $consulta."<br>";
				//echo $row[0];
				 $res=mysql_query($consulta) or die('La consulta fall&oacute; : ' . mysql_error());
			  
			//  	   echo $row[0].'<br>';
	    
	  }
	
	}
	//  echo "contador:".$contador."<br>";
/*******************************************************************************/
/************************/
/**exportar familias***/
/*************************/
/*$conexion=@mysql_connect("localhost","root","");
	mysql_select_db("extra_macaws",$conexion);
	$consulta= "SELECT DISTINCT p.descripcion, p.nombre, d.cod_estilo
FROM detalle d, producto p
WHERE d.cod_producto = p.cod_producto";
$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
mysql_select_db("macaws_bd",$conexion);

    while($row = mysql_fetch_array($resultado))
	{ 
             $insertar_familia = "insert into familia (nombre_familia,observaciones,estilo_id) values ('".$row[0]."','".$row[1]."',".$row[2].")";
			echo "<br>insertar familia: ". $insertar_familia;
			$res = mysql_query($insertar_familia) or die('La consulta fall&oacute;: ' . mysql_error());
		 
		 
    }*/
	
/*******************************/
/*** exportar despiece *********/
/*******************************/
/*	function obtener_detalle($orden,$producto,$estilo,$cuero,$color,$clip,$sello,$etiqueta)
	{
	      $familias=new Familia;
		  $propiedades=new Propiedad;
	  	  $con=@mysql_connect("localhost","root","");
		  mysql_select_db("macaws_bd",$con);
		  $familia= trim($producto." ::: ".$estilo);
	   	  $familia_id=$familias->verifica_familia_valida($familia);
		  $clip_id=$clip+1;
		  $etiqueta_id=$etiqueta+1;
		  $prop_id=$propiedades->verificar_propiedad($clip_id,$cuero,$color,$etiqueta_id,$sello);  
		  $consulta= "SELECT detalle_id
					  FROM tdetalleordenesproduccion d, tordenesproduccion o
					  WHERE d.orden_id = o.orden_id and o.num_orden='".$orden."' and familia_id=".$familia_id." and propiedad_id=".$prop_id;
		 
		$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		while($row = mysql_fetch_array($resultado))
		{
		     echo "dentro de la funcion obtener detalle - numero de detalle en macaws_bd:".$row['detalle_id']."<br>";
		     $detalle_id=$row['detalle_id'];
		}
		return $detalle_id;
	
	}
	
	
	$conexion=@mysql_connect("localhost","root","");
	mysql_select_db("extra_macaws",$conexion);
	$consulta= "SELECT d.cod_orden, d.cod_detalle, d.cod_producto,d.cod_estilo,d.cod_cuero,d.cod_color,d.cod_clip,d.cod_herraje,d.cod_etiqueta,d.cod_forro, p.descripcion,e.estilo_es 
	FROM orden o, detalle d, producto p, estilo e  
	WHERE p.cod_producto=d.cod_producto and e.cod_estilo=d.cod_estilo and o.cod_orden=d.cod_orden and  o.nro_orden='P_02_2007'";
	$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	while($row = mysql_fetch_array($resultado))
	{ 
	     echo "-------------------------------------------------------------------------------<br>";
	     echo "DETALLE:".$row['cod_detalle']."<br>";
		 echo "-------------------------------------------------------------------------------<br>";
		 echo "producto:".$row['descripcion']."<br>";
		 echo "estilo:".$row['estilo_es']."<br>";
        $cod_articulo=$row['cod_producto']."-".$row['cod_estilo']."-".$row['cod_cuero']."-".$row['cod_color']."-".$row['cod_forro']."-".$row['cod_clip']."-".$row['cod_herraje']."-".$row['cod_etiqueta'];
		
		$detalle_id=obtener_detalle('P_02_2007',$row['descripcion'],$row['estilo_es'],$row['cod_cuero'],$row['cod_color'],$row['cod_clip'],$row['cod_herraje'],$row['cod_etiqueta']);
		mysql_select_db("extra_macaws",$conexion);
		$consulta= "SELECT * from componentes c,pieza p where c.cod_pieza=p.cod_pieza and  cod_articulo='".$cod_articulo."'";
     	$resultado2=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		while($row = mysql_fetch_array($resultado2))
		{
		    $posicion_id=$posiciones->verificar_posicion($row['nombre_p']);
			if($posicion_id==-1)
			    $despiece->nueva_posicion($row['nombre_p']);
			$posicion_id=$posiciones->verificar_posicion($row['nombre_p']);
		    $despiece->registrar_componente_nuevo($detalle_id,$row['cod_material'],$posicion_id,$row['largo_p'],$row['ancho_p'],$row['cantidad_p']);
			
			$despiece->realizar_calculos_materiales($detalle_id);
			$detalle_orden->registrar_despiece($detalle_id,'1');
		    echo "posicion:".$row['nombre_p']."<br>";
		    echo "largo:".$row['largo_p']."<br>";
		    echo "ancho:".$row['ancho_p']."<br>";
		    echo "cantidad:".$row['cantidad_p']."<br>";
			echo "cod_material:".$row['cod_material']."<br>";
			echo "numero de detalle en macaws_bd:".$detalle_id."<br>";
			
			
		}
    }
	*/
	/****************Inicializa las tabla hoja***************/
/*	$conexion=@mysql_connect("localhost","root","");
	mysql_select_db("macaws_bd",$conexion);
	$consulta= "SELECT d.orden_id, d.detalle_id,d.cantidad 
	FROM tordenesproduccion o, tdetalleordenesproduccion d
	WHERE o.orden_id=d.orden_id and  o.num_orden='P_02_2007'";
	$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	while($row = mysql_fetch_array($resultado))
	{
	     $consulta= "INSERT  into hoja (detalle_id,cantidad) values(".$row['detalle_id'].",".$row['cantidad'].")";
	     $result=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	}
	/******************************************************/
?>