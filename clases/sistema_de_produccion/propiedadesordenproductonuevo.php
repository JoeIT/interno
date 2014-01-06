<?php


   include_once('../../clases/includes/dbmanejador.php');
  include_once('../../clases/includes/validador.php');
  class PropiedadOrdenProductoNuevo
  {
      function actualizar_propiedades($propiedad_id,$estilo_id,$clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id)
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
				  
				   $consulta= "INSERT  into tpropiedadesordenes(propiedad_id,orden_producto_id) values(".trim($propiedades[$contador]).",".$codigo_orden.")";
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
			    $consulta= "SELECT t.propiedad_id, d.descripcion, p.descripcion FROM tpropiedadesordenes t,ttipopropiedades p,tpropiedadesproductos d,tordenesproductos o WHERE o.ordenproducto_id=t.orden_producto_id and t.propiedad_id=d.propiedadesproductos_id and d.tipopropiedad_id=p.tipopropiedad_id and o.ordenproducto_id=".$ordenproducto_id;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
			{
			            while($row = mysql_fetch_array($resultado))
					{
					    $respuesta[trim($row[2])][trim($row[0])]=trim($row[1]);
						
					}
					  
				
						return $respuesta;
			}
	   }
	   }
	   
		 function obtener_id_propiedades($ordenproducto_id)
      {
           $con = new DBmanejador;
 		    if($con->conectar()==true)
		    {
			    $consulta= "SELECT t.propiedad_id FROM tpropiedadesordenes t,ttipopropiedades p,tpropiedadesproductos d,tordenesproductos o WHERE o.ordenproducto_id=t.orden_producto_id and t.propiedad_id=d.propiedadesproductos_id and d.tipopropiedad_id=p.tipopropiedad_id and o.ordenproducto_id=".$ordenproducto_id;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
			{        
			        $contador=0;
			        while($row = mysql_fetch_array($resultado))
					{
					    $respuesta[$contador]=trim($row[0]);
						$contador++;
						
					}
					  
				
						return $respuesta;
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
		 
		  
	   }
	   function eliminar_propiedades($orden_producto_id)
	   {
	       
	      
	      $con = new DBmanejador;
		  if($con->conectar()==true)
		  {
		       $consulta= "DELETE FROM tpropiedadesordenes WHERE orden_producto_id=".$orden_producto_id;
		     
              $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

  			  if (!$resultado) return false;
 			  else return true; 
			  
	   }
	   }
}
?>