<?php


   include_once('../../clases/includes/dbmanejador.php');
  include_once('../../clases/includes/validador.php');
  class PropiedadProductoNuevo
  {
  
      function obtener_lista_propiedades()
	  {
	        $con = new DBmanejador;
 		    if($con->conectar()==true)
		    {
			    $consulta= "SELECT p.propiedadesproductos_id,p.descripcion,t.descripcion 
				            FROM tpropiedadesproductos p , ttipopropiedades t 
							WHERE p.tipopropiedad_id=t.tipopropiedad_id";
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;:'. mysql_error());

			if (!$resultado) return false;
			else
			{
			        
			        while($row = mysql_fetch_array($resultado))
					{
					    $respuesta[$row[2]][$row[0]]=$row[1];
						
					}
					  
				
						return $respuesta;
			}
	   }
	}
	   
			
    
  
      /*function actualizar_propiedades($propiedad_id,$estilo_id,$clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id)
		 {
		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tpropiedades set estilo_id=".$estilo_id.",clip_id=".$clip_id.",cuero_id=".$cuero_id. 
				            ",color_id=".$color_id.",etiqueta_id=".$etiqueta_id.",sello_id=".$sello_id." where prop_id=".$propiedad_id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			 	if (!$resultado) return false;
			 	else return true;
            }
		 
		    
		 }
	 
	 
	 
      function insertar_propiedades($codigo_orden,$propiedades)
	  {
	       $con = new DBmanejador;
           if($con->conectar()==true)
       	   {
			  
			  for($contador=0;$contador<count($propiedades);$contador++)
			  {
			      $propiedad=$propiedades[$contador];
				  $modelo=split("-",$propiedad);
				  
				   $consulta= "INSERT  into tpropiedadesmodelo(propiedad_id,tabla,orden_producto_id) values(".trim($modelo[1]).",'".trim($modelo[0])."',".$codigo_orden.")";
		         $resultado=mysql_query($consulta) or die('La consulta fall&oacute en tpropiedades; : ' . mysql_error());
			     if (!$resultado) return false;
 	     		 			 
		     }
	           		
			}  
        return true;			     
	  }
			  
 
      function obtener_propiedades($ordenproducto_id)
      {
           $con = new DBmanejador;
 		    if($con->conectar()==true)
		    {
			    $consulta= "SELECT o.ordenproducto_id,c.nombre,												 o.fechasolicitud,o.fechaculminacion,o.fechareprogramacion,o.codigo,o.grafico,o.tipo,o.modelo,o.tarjetero,o.num_ordenproducto,o.estadoproducto,o.detallesadicionales,o.cantidad,o.lugar_mica,o.caracteristicas_exterior,o.caracteristicas_interior,o.material_varios  FROM tordenesproductos o , tclientes c WHERE c.cliente_id=o.cliente_id  and o.ordenproducto_id=".$id;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
			{
			            $row = mysql_fetch_array($resultado);
						$respuesta["ordenproducto_id"]= $row[0];
						$respuesta["clientenombre"]= $row[1];
						$respuesta["fechasolicitud"]= $row[2];
						$respuesta["fechaculminacion"]= $row[3];
						$respuesta["fechareprogramacion"]= $row[4];
						$respuesta["codigo"]= $row[5];
						$respuesta["grafico"]= $row[6];
						$respuesta["tipo"]= $row[7];
						$respuesta["modelo"]= $row[8];
						$respuesta["tarjetero"]= $row[9];
						$respuesta["num_ordenproducto"]= $row[10];
						$respuesta["estadoproducto"]= $row[11];
						$respuesta["detallesadicionales"]= $row[12];
						$respuesta["cantidad"]= $row[13];
						$respuesta["lugar_mica"]= $row[14];
						$respuesta["caracteristicas_exterior"]= $row[15];
						$respuesta["caracteristicas_interior"]= $row[16];
						$respuesta["material_varios"]= $row[17];
						return $respuesta;
			}
	   }
	   }
	   
			
      }

	  	
      function consultar_busqueda($cadena,$opcion)
      {
		      
	  }

	   function consulta_numero_orden()
	   {
	      $con = new DBmanejador;
		  if($con->conectar()==true)
		  {
                 $consulta= 'SELECT num_orden FROM tordenes
							 where orden_id= ( select MAX(orden_id) from tordenes)';

                  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
			{
                $row = mysql_fetch_array($resultado);
				$resultado=trim($row[0]);
                $num_orden=split("_",$resultado);
                $fecha = date("Y");
                if($fecha==$num_orden[2])
                	$num_orden_nueva= "P_".($num_orden[1]+1)."_".$num_orden[2];
                else
                    $num_orden_nueva="P_1_".$fecha;
                return $num_orden_nueva;

			}

		  }

	   }
	   function obtener_num_cup($num_orden)
	   {
             $orden_div=split("_",$num_orden);
             $num_cup= $orden_div[1].substr($orden_div[2],2,2);

             return $num_cup;

	   }
	   
	   
	   
	   
	   
	   function ingresa_nueva_orden($num_orden,$num_cup,$fecha,$fecha_entrega,$cliente_id,$observaciones,$usuario_id)
	   {
	      $validar = new Validador();
	      $fecha1=$validar->cambiaf_a_mysql($fecha);
  	      $fecha2=$validar->cambiaf_a_mysql($fecha_entrega);
	      $con = new DBmanejador;
		  if($con->conectar()==true)
		  {
		       $consulta= "INSERT  into tordenes(num_orden,cliente_id,usuario_id,cup_num,fecha,fechaentrega,observacion) 				                    values('".$num_orden."',".$cliente_id.",".$usuario_id.",".$num_cup.",'".$fecha1."','".$fecha2."','".$observaciones."')";
		     
              $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

  			  if (!$resultado) return false;
 			  else 
			  {
			     //recibo el último id
				$orden_id = mysql_insert_id();
			    return $orden_id;
			  }
			  
 			 	  
		  }
		 
		  
	   }*/
}
?>