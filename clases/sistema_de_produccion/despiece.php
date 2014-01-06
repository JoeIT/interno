<?php
  include_once('../../clases/includes/dbmanejador.php');
  include_once('posicionpiezas.php');
  
  class Despiece
  {
  
  	  	function busqueda_tipos($cadena)
	{
	
	 $con = new DBmanejador;
	 if($con->conectar()==true)
	 {
		$consulta= "select descripcion from tipo_material where descripcion like '%".$cadena."%' ";
		$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
	
		if (!$resultado) return false;
		else
		{      $contador=0;
	
				while($row = mysql_fetch_array($resultado))
				{
					
					$respuesta[$contador]= $row['descripcion'];
					$contador=$contador+1;
				}
	
			return $respuesta;
		}
	 }
	}
	function obtener_lista_materiales_despiece($detalle_id)
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
			$consulta = "
			SELECT
				  DISTINCT m.material_id
				, m.nombre
				, m.descripcion
				, m.unidad
				, l.largo
				, l.ancho
				, m.desperdicio
				, m.dimension_id
				, m.tipo_material_id
			FROM
				  tdetalleordenesproduccion d
				, tcomponentes c
				, tpiezas p
				, tmateriales m LEFT JOIN tdimensiones l ON m.dimension_id = l.dimension_id
			WHERE
					d.detalle_id = c.detalle_id
				AND c.pieza_id = p.pieza_id
				AND p.material_id = m.material_id
				AND d.detalle_id=".$detalle_id."
			ORDER BY m.nombre
			";					 
					 
                     $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
					 if (!$resultado) return false;
					 else
					 {
							$contador=0;
							while($row = mysql_fetch_array($resultado))
							{
							    
								$lista_materiales[$contador]["material_id"]=$row[0];
								$lista_materiales[$contador]["nombre"]=$row[1];
								$lista_materiales[$contador]["descripcion"]=$row[2];
								$lista_materiales[$contador]["unidad"]=$row[3];
								$lista_materiales[$contador]["largo"]=$row[4];
							    $lista_materiales[$contador]["ancho"]=$row[5];
								$lista_materiales[$contador]["desperdicio"]=$row[6];
								if($row[7]== null)
									$lista_materiales[$contador]["dimension"]=false;
								else
								   	$lista_materiales[$contador]["dimension"]=true;
							    
								$lista_materiales[$contador]["tipo_material_id"] =  $row['tipo_material_id'];
								
								$contador++;
							}
					  		return $lista_materiales;
	
			         }
		 }  
	    
	
	}
	function obtener_datos_pieza_despiece($componente_id)
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
				 
                     $consulta= 'SELECT c.componente_id, d.descripcion, l.largo, l.ancho,c.cantidad 	
								 FROM tcomponentes c,tposicion_pieza d ,tpiezas p
								 LEFT JOIN tdimensiones l ON p.dimension_id = l.dimension_id
								WHERE  c.pieza_id = p.pieza_id
										AND p.posicion_pieza_id = d.posicion_pieza_id
										AND c.componente_id='.$componente_id. ' order by d.descripcion';
			//	echo $consulta."<br>";
                     $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener_datos_pieza_despiece: ' . mysql_error());
					 if (!$resultado) return false;
					 else
					 {
							
							$row = mysql_fetch_array($resultado);
							$datos_pieza["componente_id"]=$row[0];
							$datos_pieza["descripcion"]=$row[1];
							$datos_pieza["largo"]=$row[2];
							$datos_pieza["ancho"]=$row[3];
							$datos_pieza["cantidad"]=$row[4];
							return $datos_pieza;
	
			         }
		 }  
	
	
	
	
	
	
	
	}
	function obtener_datos_material_despiece($detalle_id,$material_id)
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
				 
                     $consulta= 'SELECT m.material_id, m.nombre, m.descripcion, m.unidad, l.largo, l.ancho, 	
								 m.desperdicio 
								 FROM tdetalleordenesproduccion d, tcomponentes c, tpiezas p, tmateriales m 
								 LEFT JOIN tdimensiones l ON m.dimension_id = l.dimension_id
								WHERE d.detalle_id = c.detalle_id
										AND c.pieza_id = p.pieza_id
										AND p.material_id = m.material_id
										AND d.detalle_id='.$detalle_id. ' AND m.material_id='.$material_id;
					 
					 
                     $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
					 if (!$resultado) return false;
					 else
					 {
							
							$row = mysql_fetch_array($resultado);
							$datos_materiales["material_id"]=$row[0];
							$datos_materiales["nombre"]=$row[1];
							$datos_materiales["descripcion"]=$row[2];
							$datos_materiales["unidad"]=$row[3];
							$datos_materiales["largo"]=$row[4];
							$datos_materiales["ancho"]=$row[5];
							$datos_materiales["desperdicio"]=$row[6];
							
							
					  		return $datos_materiales;
	
			         }
		 }  
	
	
	
	
	}
	
	function obtener_lista_piezas_material($detalle_id,$material_id)
	{
	      $con = new DBmanejador;
	      if($con->conectar()==true)
    	  {
				 
              $consulta= 'SELECT c.componente_id,p.pieza_id,l.descripcion, e.largo,e.ancho, c.cantidad 
								 FROM tdetalleordenesproduccion d, tcomponentes c, tposicion_pieza l, tmateriales m, tpiezas p 
								 LEFT JOIN tdimensiones e ON p.dimension_id = e.dimension_id
  								 WHERE  d.detalle_id = c.detalle_id
										AND c.pieza_id = p.pieza_id
										AND p.material_id = m.material_id
										AND p.posicion_pieza_id=l.posicion_pieza_id
										AND p.posicion_pieza_id is not null
										AND d.detalle_id='.$detalle_id.' AND m.material_id='.$material_id. ' Order by l.descripcion' ;
					 
					
                     $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en obtener_lista_piezas_material: ' . mysql_error());
					 if (!$resultado) return false;
					 else
					 {
							$contador=0;
							while($row = mysql_fetch_array($resultado))
							{
							    
								$lista_piezas[$contador]["componente_id"]=$row[0];
								$lista_piezas[$contador]["pieza_id"]=$row[1];
								$lista_piezas[$contador]["Pieza"]=$row[2];
								$lista_piezas[$contador]["Largo"]=$row[3];
								$lista_piezas[$contador]["Ancho"]=$row[4];
								$lista_piezas[$contador]["Cantidad"]=$row[5];
							    $contador++;
							}
					  		return $lista_piezas;
	
			         }
		 }  
	    
	
	
	}
	
	
	
	function buscar_componente_registrado($detalle_id,$pieza_id)
	{
	    $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		       
		         $consulta= 'SELECT componente_id FROM tcomponentes
							 WHERE detalle_id='.$detalle_id." and pieza_id=".$pieza_id;
				 
		          $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				  
				  if (!$resultado) return -1;
				  else
				  {
				     $contador=0;
				     while($row = mysql_fetch_array($resultado))
					 { //echo "ingreso";
					     $contador++;
						 return $row[0];
					 }
				     if($contador==0)
					    return -1;
				  }
				  
		 }
	
	}
	
	
	function registrar_material($detalle_id,$material_id)
	{  
		 $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		       
		         $consulta= 'SELECT dimension_id FROM tmateriales 
							 WHERE material_id='.$material_id;
				 
		          $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				  if (!$resultado) return false;
				  else
				  {
				     $row = mysql_fetch_array($resultado);
				     $dimension_id=$row[0];  
					 
					
					 if(trim($dimension_id)=="")
					 		 $pieza_id=$this->verificar_existencia_pieza("null",$material_id,"null"); 
					 else
					         $pieza_id=$this->verificar_existencia_pieza($dimension_id,$material_id,"null");
					
				     if($pieza_id==-1)
				     {
					      if(trim($dimension_id)=="")
 						  {   
						   $pieza_id=$this->registrar_pieza("null",$material_id,"null");}
					      else
						  {   
						      $pieza_id=$this->registrar_pieza($dimension_id,$material_id,"null");
						  }
					 }
					  
					 $cantidad=1;
					 //echo "p:".$this->buscar_componente_registrado($detalle_id,$pieza_id);
					 if($this->buscar_componente_registrado($detalle_id,$pieza_id)==-1)
							 $componente_id=$this->registrar_componente($detalle_id,$pieza_id,$cantidad);
					 else
					     $componente_id=$this->buscar_componente_registrado($detalle_id,$pieza_id);
					 return $componente_id;
				  }
		 
		 
		 }
	
	}
	
	function verificar_existencia_pieza($dimension_id,$material_id,$posicion_pieza_id)
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		       
			     if($posicion_pieza_id=="null")
			     {     
				    if($dimension_id=="null") 
				     {  $consulta= 'SELECT pieza_id FROM tpiezas
							 WHERE material_id='.$material_id.' and dimension_id is null and  posicion_pieza_id is null';
					  }
					  else
					  {
					      $consulta= 'SELECT pieza_id FROM tpiezas
							 WHERE material_id='.$material_id.' and dimension_id='.$dimension_id.' and  posicion_pieza_id is null';
							
					  }
		          }

				  else
				  {
				     if($dimension_id=="null") 
				     { 
				       $consulta= 'SELECT pieza_id FROM tpiezas
							 WHERE material_id='.$material_id.' and dimension_id is null and  posicion_pieza_id='.$posicion_pieza_id;
				     }
					 else
					 {
					       $consulta= 'SELECT pieza_id FROM tpiezas
							 WHERE material_id='.$material_id.' and dimension_id='.$dimension_id.' and  posicion_pieza_id='.$posicion_pieza_id;
					 }
				  }
				//echo $consulta;
				  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en verificar existencia de pieza: ' . mysql_error());
				  if (!$resultado) return false;
				  else
				  {
				     $contador=0;
				     while($row = mysql_fetch_array($resultado))
					 {
					  $pieza_id=$row[0];
					  $contador++;
					 }
				     if($contador==0) $pieza_id=-1; 
                     
				     return $pieza_id;
				  
				  }
		 
		 
		 }
	
	}
	
	function verificar_existencia_dimension($largo,$ancho)
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		       
			     
			$consulta= 'SELECT dimension_id FROM tdimensiones
							 WHERE largo='.trim($largo).' and ancho='.trim($ancho);
				//	echo $consulta."<br>";			
				  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en verificar la existencia de la dimension: ' . mysql_error());

				  if (!$resultado) return false;
				  else
				  {
				     $contador=0;
				     while($row = mysql_fetch_array($resultado))
					 {
					  $dimension_id=$row[0];
					  $contador++;
					 }
				     if($contador==0) $dimension_id=-1; 
                    // echo $dimension_id."<br>";
				     return $dimension_id;
				  
				  }
		 
		 
		 }
	
	}
	
	
	function registrar_pieza($dimension_id,$material_id,$posicion_id)		  
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		     if($posicion_id=="null")
		     { 
			    if($dimension_id=="null")
				{ 
				    $consulta= "INSERT  into tpiezas 
						  (material_id) values(".$material_id.")";
				
				}
				else
			     {  
				    $consulta= "INSERT  into tpiezas 
						  (dimension_id,material_id) values(".$dimension_id.",".$material_id.")";
				  }
		     }
			 else
			 {
			    if($dimension_id=="null")
				{
				     $consulta="INSERT  into tpiezas 
					  (material_id,posicion_pieza_id) values(".$material_id.",".$posicion_id.")";
				}
				else
			    { $consulta="INSERT  into tpiezas 
					  (dimension_id,material_id,posicion_pieza_id) values(".$dimension_id.",".$material_id.",".$posicion_id.")";
				}
			 }
			 // echo $consulta;
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar pieza: ' . mysql_error());
			     if (!$resultado) return false;
 	     		 else
				 { 
				      $pieza_id = mysql_insert_id();
					  return $pieza_id;
		     	 }
		 }
	
	}
	function registrar_dimension($largo,$ancho)		  
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		    
			  $consulta= "INSERT  into tdimensiones
						  (largo,ancho) values(".$largo.",".$ancho.")";
		     
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		     if (!$resultado) return false;
 	     		 else
				 { 
				      $dimension_id = mysql_insert_id();
					  return $dimension_id;
		     	 }
		 }
	
	}
	function registrar_componente($detalle_id,$pieza_id,$cantidad)
	{
	   $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		     
			  $consulta= "INSERT  into tcomponentes 
						  (detalle_id,pieza_id,cantidad) values(".$detalle_id.",".$pieza_id.",".$cantidad.")";
		    
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		     if (!$resultado) return false;
 	     		 else
				 { 
				      $componente_id = mysql_insert_id();
					  return $componente_id;
		     	 }
		 }
	
	  
	
	}
	
	function registrar_componente_nuevo($detalle_id,$material_id,$posicion_id,$largo,$ancho,$cantidad)
	{

	     $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 {
		     $dimension_id=$this->verificar_existencia_dimension($largo,$ancho);
		     if($dimension_id==-1)
			     {$dimension_id=$this->registrar_dimension($largo,$ancho);}
		     $pieza_id=$this->verificar_existencia_pieza($dimension_id,$material_id,$posicion_id);
				     if($pieza_id==-1)
				     {$pieza_id=$this->registrar_pieza($dimension_id,$material_id,$posicion_id);}
		     
		     $consulta='SELECT c.componente_id 
			 			FROM tcomponentes c,tpiezas p, tdetalleordenesproduccion d, tmateriales m
						WHERE d.detalle_id=c.detalle_id and p.pieza_id=c.pieza_id and p.material_id=m.material_id 
						and d.detalle_id='.$detalle_id.' and m.material_id='.$material_id.' and p.posicion_pieza_id is null';
			//echo $consulta;
			 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());		
 			 //$registros= mysql_num_fields($resultado);
             $registros=mysql_num_rows($resultado);
			 if($registros==1)
			 {
			      $row = mysql_fetch_array($resultado);
				  $componente_id=$row[0];
			      $consulta="update tcomponentes set pieza_id=".$pieza_id.", cantidad=".$cantidad." WHERE componente_id=".$componente_id;    
			      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());	
				 if (!$resultado) return false;
 	     		 else
				 { 
				      $componente_id = mysql_insert_id();
					 
		     	 }
				   
			 }
		     else
			 {   
			     $componente_id=$this->registrar_componente($detalle_id,$pieza_id,$cantidad);
			     
			 }
			  return $componente_id;
		 }
	
	}
	function actualizar_datos_componente($componente_id,$largo,$ancho,$cantidad,$posicion_id,$material_id)
	{
	    $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 {
	         $dimension_id=$this->verificar_existencia_dimension($largo,$ancho);
		     if($dimension_id==-1)
			     {$dimension_id=$this->registrar_dimension($largo,$ancho);}
				 
		     $pieza_id=$this->verificar_existencia_pieza($dimension_id,$material_id,$posicion_id);
		      if($pieza_id==-1)
				     {$pieza_id=$this->registrar_pieza($dimension_id,$material_id,$posicion_id);}
		      $consulta="update tcomponentes set pieza_id=".$pieza_id.", cantidad=".$cantidad." WHERE componente_id=".$componente_id;    
			      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());	
				 if (!$resultado) return false;
 	     		 else
				 { 
				    
					 return $componente_id ;
		     	 }
				 
		 
		 }
	
	
	}
	function obtener_dimension_material($material_id)
	{
	     $con = new DBmanejador;
	     if($con->conectar()==true)
    	 {
		       
			     
			$consulta= 'SELECT dimension_id FROM tmateriales
						 WHERE material_id='.$material_id;
			//echo $consulta;	
				  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				  if (!$resultado) return false;
				  else
				  {
				     $contador=0;
				     while($row = mysql_fetch_array($resultado))
					 {
					  $dimension_id=$row[0];
					  $contador++;
					 }
				     if($contador==0) $dimension_id=-1; 
                     
				     return $dimension_id;
				  
				  }
		 
		 
		 }  
	
	}
	function eliminar_pieza_componente($componente_id,$material_id,$detalle_id)
	{
	     $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 { 
		      
  		     $lista_piezas=$this->obtener_lista_piezas_material($detalle_id,$material_id);
			 if(count($lista_piezas)==1)
			 {
			     $dimension_id=$this->obtener_dimension_material($material_id);
             /***************************/
			     if($dimension_id == "")
				 $pieza_id=$this->verificar_existencia_pieza("null",$material_id,"null"); 
				 else
				 {
				    $pieza_id=$this->verificar_existencia_pieza($dimension_id,$material_id,"null"); 
				 }
				 
				 $consulta="update tcomponentes set pieza_id=".$pieza_id.", cantidad=1 WHERE componente_id=".$componente_id;    
			
			      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());	
				   if (!$resultado) return false;
		    	   else return true;
			 }
		     else
			 {
			    $consulta= 'delete from tcomponentes where componente_id='.$componente_id;
				
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			    if (!$resultado) return false;
		    	else return true;
			 
			 }
		 
		 }
	}
	function modificar_material_despiece($detalle_id,$material_id_anterior,$material_id)
	{
        //echo "material_anterior:".$material_id_anterior."<br>";
        //echo "material_nuevo:".$material_id."<br>";
	    $lista_piezas=$this->obtener_lista_piezas_material($detalle_id,$material_id_anterior);   
				
		for($contador=0; $contador<count($lista_piezas);$contador++)
		{
		   $largo=$lista_piezas[$contador]["Largo"];
		  
		   $ancho=$lista_piezas[$contador]["Ancho"];
   		   $cantidad=$lista_piezas[$contador]["Cantidad"];
		   $componente_id=$lista_piezas[$contador]["componente_id"];
		   $posicion_pieza=$lista_piezas[$contador]["Pieza"];
		   $posiciones=new PosicionPieza;
		   $posicion_id=$posiciones->verificar_posicion(trim($posicion_pieza));
		   $dimension_id=$this->verificar_existencia_dimension($largo,$ancho);
		   
	       $pieza_id=$this->verificar_existencia_pieza($dimension_id,$material_id,$posicion_id);
		   if($pieza_id==-1)
			   {$pieza_id=$this->registrar_pieza($dimension_id,$material_id,$posicion_id);}
		$consulta="update tcomponentes set pieza_id=".$pieza_id.", cantidad=".$cantidad." WHERE componente_id=".$componente_id;    
		$resultado=mysql_query($consulta) or die('La consulta fall&oacute; en modificar material despiece: ' . mysql_error());	
		   
		}
	    if($contador==0)
		{
		         
		     $consulta='SELECT c.componente_id 
			 			FROM tcomponentes c,tpiezas p, tdetalleordenesproduccion d, tmateriales m
						WHERE d.detalle_id=c.detalle_id and p.pieza_id=c.pieza_id and p.material_id=m.material_id 
						and d.detalle_id='.$detalle_id.' and m.material_id='.$material_id_anterior.' and p.posicion_pieza_id is null';
			 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());		
 			 //$registros= mysql_num_fields($resultado);
             $registros=mysql_num_rows($resultado);
			 if($registros==1)
			 {   
			      $row = mysql_fetch_array($resultado);
				  $componente_id=$row[0];
				  $consulta= 'SELECT dimension_id FROM tmateriales 
							 WHERE material_id='.$material_id;
		          $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				  //echo $consulta;
				 $row = mysql_fetch_array($resultado);
				  $dimension_id=$row[0];  
				  //echo "dimension: ".$dimension_id;
				  if((trim($dimension_id)=="null") || (trim($dimension_id)=="") )
				  {  //echo "1";
					 $pieza_id=$this->verificar_existencia_pieza("null",$material_id,"null");
				  }
				  else
				  {  //echo "2";
				      $pieza_id=$this->verificar_existencia_pieza($dimension_id,$material_id,"null");
				  }
				  //echo "dimension".$dimension;
				  if($pieza_id==-1)
				  {   
				       if($dimension_id=="null" || $dimension_id=="")
					     {$pieza_id=$this->registrar_pieza("null",$material_id,"null");}
					   else
				  	   {$pieza_id=$this->registrar_pieza($dimension_id,$material_id,"null");}
				  }
			      $consulta="update tcomponentes set pieza_id=".$pieza_id.", cantidad=1 WHERE componente_id=".$componente_id;    
			      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());	
				 
				  
			 }
		
		}
	}
	function eliminar_material_despiece($detalle_id,$material_id)
	{
	    //echo "ingreso a eliminar";
	    $lista_piezas=$this->obtener_lista_piezas_material($detalle_id,$material_id);   
		
		for($contador=0; $contador<count($lista_piezas);$contador++)
		{
		   $consulta= 'delete from tcomponentes where componente_id='.$lista_piezas[$contador]["componente_id"];
		   
           $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error()); 
		   
		}
		if($contador==0)
		{
		       $consulta='SELECT c.componente_id 
			 			FROM tcomponentes c,tpiezas p, tdetalleordenesproduccion d, tmateriales m
						WHERE d.detalle_id=c.detalle_id and p.pieza_id=c.pieza_id and p.material_id=m.material_id 
						and d.detalle_id='.$detalle_id.' and m.material_id='.$material_id.' and p.posicion_pieza_id is null';
			 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());		
 	         
			 //$registros= mysql_num_fields($resultado);
             $registros=mysql_num_rows($resultado);
		/*	 echo "<pre>";
		   print_r($registros);
		echo "</pre>";*/
			 if($registros>=1)
			 {   
			      while($row = mysql_fetch_array($resultado))
				  {
				  // echo "componente";
				  $componente_id=$row[0];
			      $consulta= 'delete from tcomponentes where componente_id='.$componente_id;    
				 
			      $resul=mysql_query($consulta) or die('La consulta fall&oacute; en eliminar material despiece: ' . mysql_error());	
				  }
			 }
		
		}
		return true;
	}
	
	
	 /* SELECT m.nombre, m.descripcion, m.unidad, m.dimension_id, d.largo, d.ancho, m.desperdicio
FROM tmateriales m
LEFT JOIN tdimensiones d ON m.dimension_id = d.dimension_id*/
	function realizar_calculos_materiales($detalle_id)
	{
	  /********************************************************/
	    // se añadio el dia 27 de julio
	    $this->eliminar_calculos_despiece($detalle_id);
		
	/*************************************************************/	
	    $con = new DBmanejador;
		
	     if($con->conectar()==true)
    	 {  
              $consulta= 'delete from tdespiece where detalle_id='.$detalle_id;    
		      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en eliminar material despiece: ' . mysql_error());	
		      $consulta="SELECT d.cantidad AS detalle_cantidad, c.cantidad AS cantidad_componente, p.material_id AS 	
			  material_id, s.largo AS largo_pieza, s.ancho AS ancho_pieza, m.desperdicio AS material_desperdicio, 
			  m.dimension_id AS material_dimension FROM tdetalleordenesproduccion d, tcomponentes c, tmateriales m, 
			  tpiezas p LEFT JOIN tdimensiones s ON p.dimension_id = s.dimension_id WHERE d.detalle_id = c.detalle_id
			  AND c.pieza_id = p.pieza_id AND p.material_id = m.material_id and d.detalle_id=".$detalle_id ;
			
		     $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());		
		      while($row = mysql_fetch_array($resultado))
			  { 
			     
			     if(trim($row['largo_pieza'])!="")
			     { 
				
				  $superficie= ($row['largo_pieza']*$row['ancho_pieza'])*$row['cantidad_componente'];
				  
				  $superficie_con_desperdicio= $superficie + (($row['material_desperdicio']*$superficie)/100);
				  if($requerimento[$row['material_id']]['requerido']=="")
				      $requerimento[$row['material_id']]['requerido']= $superficie_con_desperdicio;
				  else
				  $requerimento[$row['material_id']]['requerido']= $requerimento[$row['material_id']]['requerido'] + $superficie_con_desperdicio;
			
				  $requerimento[$row['material_id']]['dimension_id']=$row['material_dimension'];
				  $requerimento[$row['material_id']]['cantidad_pedida']=$row['detalle_cantidad'];
				 }
				 else
				 {
				    if($requerimento[$row['material_id']]['requerido']=="")
					    $requerimento[$row['material_id']]['requerido']=1;
					else
					{
					   //se añadio el campo requerido en la suma;
					    $requerimento[$row['material_id']]['requerido']=$requerimento[$row['material_id']]['requerido']+1;
					}
				    $requerimento[$row['material_id']]['dimension_id']=$row['material_dimension'];
 				    $requerimento[$row['material_id']]['cantidad_pedida']=$row['detalle_cantidad'];
				    
				 }
			  }
			/*  echo '<pre>';
			     print_r($requerimento);
			  echo '</pre>';*/
			  foreach($requerimento as $material_id => $resultados)
			  {
			  
			  
			   $total_cantidad_material= $requerimento[$material_id]['requerido'] *  $requerimento[$material_id]['cantidad_pedida'];
		        if($requerimento[$material_id]['dimension_id']!="")		  
		        {
				   $superficie_material=$this->obtener_superficie_material($requerimento[$material_id]['dimension_id']);
				   $total_cantidad_piezas=$total_cantidad_material/$superficie_material;
				 }
				 else
				 {
                     
				    $total_cantidad_piezas=$total_cantidad_material;
				 }
				 
				  $consulta="INSERT  into tdespiece
						  (detalle_id,material_id,requerimiento,total_piezas_material) values(".$detalle_id.",".$material_id.",".$requerimento[$material_id]['requerido'].",".$total_cantidad_piezas.")";

			     $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 2:'.mysql_error());		  
				 //echo 'cantidad requerida='. $total_cantidad_material;
				 //echo 'total piezas='. $total_cantidad_piezas;
				 
		      }
		 }
	   
	
	}
	function obtener_superficie_material($dimension_id)
	{
	     $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 { 
		 
		     $consulta='SELECT (largo * ancho) AS superficie FROM tdimensiones where dimension_id='.$dimension_id;
			 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());		
		     $row = mysql_fetch_array($resultado);
			 return $row['superficie'];
			 
		 }
	
	}
	function resultados_calculos_detalle($detalle_id)
	{
	     $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 { 
		      $consulta="SELECT m.nombre AS NOMBRE,m.descripcion AS DESCRIPCION,m.unidad as UNIDAD ,p.requerimiento as REQUERIMIENTO 
			  ,p.total_piezas_material as TOTAL, m.dimension_id as DIMENSION FROM tdetalleordenesproduccion d, tdespiece p, tmateriales m WHERE 
			  d.detalle_id=p.detalle_id and m.material_id=p.material_id and d.detalle_id=".$detalle_id." ORDER BY m.nombre";
		  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());
		  $contador=0;
		      while($row = mysql_fetch_array($resultado))
			  {
			                  if($row[5]!="")
					               $superficie_material=$this->obtener_superficie_material($row[5]);
							  else
							       $superficie_material=1;
							$datos_materiales[$contador]["Producto"]=$row[0];
							$datos_materiales[$contador]["Observaciones"]=$row[1];
							$datos_materiales[$contador]["Unidad"]=$row[2];
							$datos_materiales[$contador]["xUnidad"]=$superficie_material;
							$datos_materiales[$contador]["Requerimiento"]=$row[3];
							$datos_materiales[$contador]["TOTAL"]=$row[4];
							$contador++;
			
		      }
			  return $datos_materiales;
		 }
	
	
	}
	function buscar_despiece_identico($detalle_id)
	{
	   
	  	  $con = new DBmanejador;
		  if($con->conectar()==true)
    	  { 
		      $consulta="SELECT d.detalle_id,d.familia_id,d.propiedad_id FROM tdetalleordenesproduccion d WHERE d.detalle_id=".$detalle_id;
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar busqueda de despieces identicos:'.mysql_error());
			  $row = mysql_fetch_array($resultado);
			  $id=$row['detalle_id'];
			  $familia_id=$row['familia_id'];
			  $propiedad_id=$row['propiedad_id'];
			  $consulta="SELECT MAX(d.detalle_id) FROM tdetalleordenesproduccion d WHERE 
			             d.familia_id=".$familia_id." and d.propiedad_id=".$propiedad_id." and d.despiece=1 and d.detalle_id !=".$detalle_id;
			  //echo $consulta;
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());
			  $row = mysql_fetch_array($resultado);
			  $detalle_id_identico=$row[0];
			  if($detalle_id_identico == "") 
			        $detalle_id_identico=-1;
					
		      return $detalle_id_identico;
		}
	
	}
	function copiar_despiece_identico($detalle_id,$detalle_id_identico)
	{
	    $con = new DBmanejador;
		if($con->conectar()==true)
    	{ 
		     $consulta="SELECT c.componente_id,c.pieza_id,c.cantidad FROM tcomponentes c WHERE 
			             c.detalle_id=".$detalle_id_identico;
			 $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en copiar despiece identico:'.mysql_error());
			 while($row = mysql_fetch_array($resultado))
			 {
			    
			     $this->registrar_componente($detalle_id,trim($row['pieza_id']),trim($row['cantidad']));
			 
			 }
			 
			 $this->realizar_calculos_materiales($detalle_id);
			 return true;

		}
	}
	
	function buscar_despieces_similares($detalle_id)
	{
	     $con = new DBmanejador;
		  if($con->conectar()==true)
    	  { 
		      $consulta="SELECT d.familia_id,d.propiedad_id,p.clip_id,p.cuero_id,p.color_id,p.etiqueta_id,p.sello_id FROM 	
			  			 tdetalleordenesproduccion d, tpropiedades p WHERE d.detalle_id=".$detalle_id;
			//echo"<br>c1: ". $consulta;
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());
			  $row = mysql_fetch_array($resultado);
			  $familia_id=$row['familia_id'];
			  $propiedad_id=$row['propiedad_id'];
			  $clip_id=$row['clip_id'];
			  $cuero_id=$row['cuero_id'];
			  $color_id=$row['color_id'];
			  $etiqueta_id=$row['etiqueta_id'];
			  $sello_id=$row['sello_id'];
			    
			  
			  $consulta="SELECT MAX(d.detalle_id), f.nombre_familia,e.nombre_estilo,c.descripcion,r.descripcion,l.descripcion,q.descripcion, h.descripcion FROM tdetalleordenesproduccion d, tpropiedades p,familia f, estilo e, tclips c , tcolores l, tcueros r, tetiquetas q , tchapas h WHERE d.propiedad_id=p.prop_id  and d.familia_id=f.familia_id and f.estilo_id=e.estilo_id and p.clip_id=c.clip_id and p.color_id=l.color_id and p.cuero_id=r.cuero_id and p.etiqueta_id=q.etiqueta_id and h.chapa_id=p.sello_id and d.despiece=1 and d.familia_id=".$familia_id." and (p.clip_id=".$clip_id." or p.cuero_id=".$cuero_id. " or p.color_id=".$color_id." or p.etiqueta_id=".$etiqueta_id." or sello_id=".$sello_id.") GROUP BY f.nombre_familia, e.nombre_estilo, c.descripcion, r.descripcion, l.descripcion, q.descripcion, h.descripcion";
			  /*$consulta="
			  SELECT	MAX(d.detalle_id)
			  			, f.nombre_familia,e.nombre_estilo
						, c.descripcion, r.descripcion,l.descripcion
						, q.descripcion, h.descripcion
			  FROM		tdetalleordenesproduccion d
			  			, tpropiedades p,familia f, estilo e, tclips c
						, tcolores l, tcueros r, tetiquetas q , tchapas h
			  WHERE		d.propiedad_id=p.prop_id
			  			and d.familia_id=f.familia_id
						and f.estilo_id=e.estilo_id
						and p.clip_id=c.clip_id
						and p.color_id=l.color_id
						and p.cuero_id=r.cuero_id
						and p.etiqueta_id=q.etiqueta_id
						and h.chapa_id=p.sello_id
						and d.despiece=1
						and d.familia_id=".$familia_id."
			  GROUP BY f.nombre_familia, e.nombre_estilo, c.descripcion, r.descripcion, l.descripcion, q.descripcion, h.descripcion LIMIT 0,20";
			  */
			/*  $consulta="
			  SELECT	MAX(d.detalle_id)
			  			, f.nombre_familia,e.nombre_estilo
						, c.descripcion, r.descripcion,l.descripcion
						, q.descripcion, h.descripcion
			  FROM		tdetalleordenesproduccion d
			  			, tpropiedades p,familia f, estilo e, tclips c
						, tcolores l, tcueros r, tetiquetas q , tchapas h
			  WHERE		d.propiedad_id=p.prop_id
			  			and d.familia_id=f.familia_id
						and f.estilo_id=e.estilo_id
						and p.clip_id=c.clip_id
						and p.color_id=l.color_id
						and p.cuero_id=r.cuero_id
						and p.etiqueta_id=q.etiqueta_id
						and h.chapa_id=p.sello_id
						and d.despiece=1
						and (d.familia_id=".$familia_id."
						or d.familia_id = 1005)
			  GROUP BY f.nombre_familia, e.nombre_estilo, c.descripcion, r.descripcion, l.descripcion, q.descripcion, h.descripcion LIMIT 0,20";		*/
 			//echo"<br>c2: ". $consulta; 
		
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());
			  $contador=0;
			  while($row = mysql_fetch_array($resultado))
			  {
			      $detalle_similares[$contador]['codigo']=$row[0];
  			      $detalle_similares[$contador]['producto']=$row[1];
				  $detalle_similares[$contador]['estilo']=$row[2];
				  $detalle_similares[$contador]['clip']=$row[3];
				  $detalle_similares[$contador]['cuero']=$row[4];
				  $detalle_similares[$contador]['color']=$row[5];
				  $detalle_similares[$contador]['etiqueta']=$row[6];
				  $detalle_similares[$contador]['sello']=$row[7];
				  $contador++;
			  }
			  return $detalle_similares;
	   }
	}
	function eliminar_calculos_despiece($detalle_id)
	{
	    $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 {  
              $consulta= 'delete from tdespiece where detalle_id='.$detalle_id;    
		      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en eliminar material despiece: ' . mysql_error			());	
			  return true;
		 }
		 else
		     return false;
	}
	function eliminar_componentes($detalle_id)
	{
	    $con = new DBmanejador;
		 
	     if($con->conectar()==true)
    	 {  
              $consulta= 'delete from tcomponentes where detalle_id='.$detalle_id;    
		      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en eliminar material despiece: ' . mysql_error			());	
			  return true;
		 }
		 else
		     return false;
	}
	
	function nuevo_material($nombre,$descripcion,$unidad,$desperdicio){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "INSERT  into  tmateriales (nombre,descripcion,unidad,desperdicio) values('".$nombre."','".$descripcion."','".$unidad."','".$desperdicio."')";
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
	  
	  function nueva_posicion($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into  tposicion_pieza (descripcion) values('".$descripcion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;

        	}
      }
	  function resultados_resumen_despiece($orden_id)
	  {
	       $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			
			    $consulta= 'SELECT e.nombre_estilo as ESTILO , o.descripcion AS CUERO, c.descripcion AS COLOR , l.descripcion AS CLIP , s.descripcion 
									AS SELLO, t.descripcion AS ETIQUETA, m.nombre_familia AS PRODUCTO, d.cantidad AS Cantidad_producto, d.unidad AS 
									Unidad_producto, d.detalle_id, materiales.nombre AS Nombre_material, materiales.descripcion AS 
									Descripcion_material , materiales.unidad as Unidad_material, despiece.total_piezas_material as TOTAL  
							FROM  tdetalleordenesproduccion d, estilo e, tcueros o, tcolores c, tclips l, tchapas s, tetiquetas t, tpropiedades p, 
							      familia m, tdespiece despiece, tmateriales materiales
						    WHERE d.orden_id='.$orden_id.' and d.propiedad_id=p.prop_id and m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and 
								  p.color_id=c.color_id and p.clip_id=l.clip_id and p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and 
								  d.familia_id=m.familia_id and d.estado=1 and d.detalle_id=despiece.detalle_id and 
								  materiales.material_id=despiece.material_id';
				//echo $consulta;	 
			 	$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				$contador=0;
				while($row = mysql_fetch_array($resultado))
			  	{
//				    $resumen_materiales[$contador]=$row['Nombre_material']."-".$row['Descripcion_material'];
					$resumen_materiales[$row['Nombre_material']."-".$row['Descripcion_material']]=$row['Unidad_material'];
					$resumen_modelos[$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['CLIP']]=$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['CLIP'];
				    $resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']]['unidad']=$row['Unidad_material'];
					$resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']]['productos'][$contador]['cantidad']=$row['Cantidad_producto'];
				    $resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']]['productos'][$contador]['detalle']=$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['CLIP'];
				   $resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']]['productos'][$contador]['total']=$row['TOTAL'] ;
				   $contador++;
				} 
			   
			    $resumen[0]= $resumen_materiales;
			    $resumen[1]= $resumen_modelos;
				$resumen[2]= $resumen_despiece;
			  // echo "<pre>";
			//	print_r($resumen_despiece);
			//	echo "</pre>";
			 return $resumen;
			}      
	  }
	  
	  
	  
	  
      function resultados_resumen_despiece1($orden_id)
	  {
	       $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			
			  $consulta= 'SELECT e.nombre_estilo as ESTILO , o.descripcion AS CUERO, 
			  			  c.descripcion AS COLOR , l.descripcion AS CLIP , s.descripcion 
 						  AS SELLO, t.descripcion AS ETIQUETA, m.nombre_familia AS 
						  PRODUCTO, d.cantidad AS Cantidad_producto, d.unidad AS 
						  Unidad_producto, d.detalle_id, materiales.nombre AS 
						  Nombre_material, materiales.descripcion AS Descripcion_material ,
						  materiales.unidad as Unidad_material,						
						  despiece.total_piezas_material as TOTAL 
						  FROM  tdetalleordenesproduccion d, estilo e, tcueros o, tcolores 
						  c, tclips l, tchapas s, tetiquetas t, tpropiedades p, familia m, 
						  tdespiece despiece, tmateriales materiales
 					      WHERE d.orden_id='.$orden_id.' and d.propiedad_id=p.prop_id and 
						  m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and 						
						  p.color_id=c.color_id and p.clip_id=l.clip_id and 
						  p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and 
						  d.familia_id=m.familia_id and d.estado=1 and 
						  d.detalle_id=despiece.detalle_id and 						
						  materiales.material_id=despiece.material_id order by 
						  Nombre_material,PRODUCTO';
		 
			 	$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				$contador=0;
			while($row = mysql_fetch_array($resultado))
			{

			$resumen_materiales[$row['Nombre_material']."-".$row['Descripcion_material']]['Unidad']=$row['Unidad_material'];
				
			$resumen_modelos[$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['modelo'] 
			=$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO'];
				
			$resumen_modelos[$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['cantidad']
			=$row['Cantidad_producto'];	
					
								
			$resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']]['unidad']=$row['Unidad_material'];
				
				
			if($resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']][$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['cantidad_material'] == null)
			{
			   $resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']][$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['cantidad_material'] =$row['TOTAL'];
					
			}
			else
			{
			   $resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']][$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['cantidad_material'] =  $resumen_despiece[$row['Nombre_material']."-".$row['Descripcion_material']][$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['cantidad_material'] + $row['TOTAL'];
					
			}
			
			$resumen_materiales[$row['Nombre_material']."-".$row['Descripcion_material']]['total']=$resumen_materiales[$row['Nombre_material']."-".$row['Descripcion_material']]['total']+$row['TOTAL'] ;
				   $contador++;
			} 
			$contador=1;
				
			foreach($resumen_materiales as $material => $resultados_materiales)
			{
			   $materiales[$contador]['material']=$material;
			   $materiales[$contador]['unidad']=$resultados_materiales['Unidad'];
			   $materiales[$contador]['total']=$resultados_materiales['total'];
			   $contador++;
			}
			$contador=1;
		
			  $consulta= 'SELECT e.nombre_estilo as ESTILO , o.descripcion AS CUERO, 
			  			  c.descripcion AS COLOR , l.descripcion AS CLIP , s.descripcion 
 						  AS SELLO, t.descripcion AS ETIQUETA, m.nombre_familia AS 
						  PRODUCTO, SUM(d.cantidad) AS Cantidad_producto
						  FROM  tdetalleordenesproduccion d, estilo e, tcueros o, tcolores 
						  c, tclips l, tchapas s, tetiquetas t, tpropiedades p, familia m
 					      WHERE d.orden_id='.$orden_id.' and d.propiedad_id=p.prop_id and 
						  m.estilo_id=e.estilo_id and p.cuero_id=o.cuero_id and 						
						  p.color_id=c.color_id and p.clip_id=l.clip_id and 
						  p.sello_id=s.chapa_id and p.etiqueta_id=t.etiqueta_id and 
						  d.familia_id=m.familia_id and d.estado=1 
						  GROUP BY e.nombre_estilo, o.descripcion ,
						  c.descripcion , l.descripcion , s.descripcion , t.descripcion ,m.nombre_familia';
		
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			while($row = mysql_fetch_array($resultado))
			{ 
			   	$resumen_modelos[$row['PRODUCTO']." ".$row['ESTILO']." ".$row['CUERO']." ".$row['COLOR']." ".$row['CLIP']." ".$row['SELLO']]['cantidad']	=$row['Cantidad_producto'];	
				
			}
				
				
			foreach($resumen_modelos as $modelo => $resultados_modelos)
			{
				   
				   $modelos[$contador]['modelo']= $modelo;
				   $modelos[$contador]['cantidad']= $resumen_modelos[$modelo]['cantidad'];
				   $contador++;
			}
		
                $resumen[0]= $materiales;
				$resumen[1]= $modelos;
				$resumen[2]= $resumen_despiece;
		        return $resumen;
			}      
	  }
	  
	  function actualizar_fecha_fin_despiece($orden_id)
	  {
	      
		  $fecha = date("d-m-Y");
		  $validar = new Validador();
  	      $fecha_fin_despiece=$validar->cambiaf_a_mysql($fecha);
		  $hora= date("H:i:s");
		  $fecha_fin_despiece=$fecha_fin_despiece." ".$hora;
		 // echo $fecha_fin_despiece;
	      $consulta="update tordenesproduccion set fecha_fin_despiece='".$fecha_fin_despiece."' WHERE $orden_id=".$orden_id." and fecha_fin_despiece is null";                                        
	      $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_componente_nuevo: ' . mysql_error());	
          if (!$resultado) return false;
		  else return true;
	  
	  }
	  function actualizar_fecha_fin_despiece_detalle($detalle_id)
	  {
	  $fecha = date("d-m-Y");
	  $hora= date("H:i:s");
	  $validar = new Validador();
	  $fecha_fin_despiece=$validar->cambiaf_a_mysql($fecha);
	  $fecha_fin_despiece=$fecha_fin_despiece." ".$hora;
      $consulta="update tdetalleordenesproduccion set fecha_fin_despiece='".$fecha_fin_despiece."' WHERE detalle_id=".$detalle_id." and fecha_fin_despiece is null";   
	//  echo $consulta;                                     
	  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en registrar_fecha_fin_despiece_detalle: ' . mysql_error());	
      if (!$resultado) return false;
	  else return true;
	  
	  
	  }
	  
	  function buscar_despieces_varios($detalle_id,$detalle_busqueda)
	{
	     $con = new DBmanejador;
		  if($con->conectar()==true)
    	  { 
		      $consulta="SELECT d.familia_id,d.propiedad_id,p.clip_id,p.cuero_id,p.color_id,p.etiqueta_id,p.sello_id FROM 	
			  			 tdetalleordenesproduccion d, tpropiedades p WHERE d.detalle_id=".$detalle_id;
			//echo"<br>c1: ". $consulta;
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());
			  $row = mysql_fetch_array($resultado);
			  $familia_id=$row['familia_id'];
			  $propiedad_id=$row['propiedad_id'];
			  $clip_id=$row['clip_id'];
			  $cuero_id=$row['cuero_id'];
			  $color_id=$row['color_id'];
			  $etiqueta_id=$row['etiqueta_id'];
			  $sello_id=$row['sello_id'];
			  $consulta="
			  SELECT	MAX(d.detalle_id)
			  			, f.nombre_familia,e.nombre_estilo
						, c.descripcion, r.descripcion,l.descripcion
						, q.descripcion, h.descripcion
			  FROM		tdetalleordenesproduccion d
			  			, tpropiedades p,familia f, estilo e, tclips c
						, tcolores l, tcueros r, tetiquetas q , tchapas h
			  WHERE		d.propiedad_id=p.prop_id
			  			and d.familia_id=f.familia_id
						and f.estilo_id=e.estilo_id
						and p.clip_id=c.clip_id
						and p.color_id=l.color_id
						and p.cuero_id=r.cuero_id
						and p.etiqueta_id=q.etiqueta_id
						and h.chapa_id=p.sello_id
						and d.despiece=1
						and (f.nombre_familia like '%".$detalle_busqueda."%'
						or e.nombre_estilo like '%".$detalle_busqueda."%')
			  GROUP BY f.nombre_familia, e.nombre_estilo, c.descripcion, r.descripcion, l.descripcion, q.descripcion, h.descripcion ";		
 			//echo"<br>c2: ". $consulta; 
		
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en realizar calculos 1:'.mysql_error());
			  $contador=0;
			  while($row = mysql_fetch_array($resultado))
			  {
			      $detalle_similares[$contador]['codigo']=$row[0];
  			      $detalle_similares[$contador]['producto']=$row[1];
				  $detalle_similares[$contador]['estilo']=$row[2];
				  $detalle_similares[$contador]['clip']=$row[3];
				  $detalle_similares[$contador]['cuero']=$row[4];
				  $detalle_similares[$contador]['color']=$row[5];
				  $detalle_similares[$contador]['etiqueta']=$row[6];
				  $detalle_similares[$contador]['sello']=$row[7];
				  $contador++;
			  }
			  return $detalle_similares;
	   }
	}
	  
//*********************************************************
	function nuevo_material_completo($nombre,$descripcion,$unidad,$desperdicio,$material_id,$nombre_tipo,$ancho,$largo){
		//vemos si es articulo, insumo
		
		//echo "<br> nom tipo: ".$nombre_tipo;
		
		if ($nombre_tipo == "insumo"){
			//si es insumo=corte debe tener: largo, ancho, desperdicio, categoria
			/*verificamos si existen el largo y ancho*/
			$dimension_id = $this->verificar_existencia_dimension($largo,$ancho);
			if ($dimension_id == -1){
				//no se encontro las dimensiones
				//insertamos las dimensiones
				$dimension_id = $this->registrar_dimension($largo,$ancho);
			}
			$consulta = "INSERT into tmateriales (dimension_id, nombre, descripcion, unidad, desperdicio, tipo_material_id) values(".$dimension_id.",'".$nombre."','".$descripcion."','".$unidad."',".$desperdicio.",".$material_id.")";
		} else {
		
		    if(trim($largo)=="" && trim($ancho)=="" )
			    $dimension_id='null';
            else
			{
			       $dimension_id = $this->verificar_existencia_dimension($largo,$ancho);
					if ($dimension_id == -1){
						//no se encontro las dimensiones
						//insertamos las dimensiones
						$dimension_id = $this->registrar_dimension($largo,$ancho);
					}
			
			}
			if(trim($desperdicio)=="")
			    $desperdicio='null';	
			$consulta = "INSERT into tmateriales (nombre,descripcion,unidad,desperdicio,dimension_id) values('".$nombre."','".$descripcion."','".$unidad."',".$desperdicio.",".$dimension_id.")";
		}
		
		//echo "<br> c1: ".$consulta;
		
		$con = new DBmanejador;
		if($con->conectar() == true){

			$resultado = mysql_query($consulta) or die('La consulta -nuevo material completo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else
				return true;
		}
	}
//*********************************************************

function resumen_despiece_modificar($orden_id)
{
	$con = new DBmanejador;
		if($con->conectar() == true){
			
			$consulta = "
			
			UPDATE	tdetalleordenesproduccion
			SET		modificar_despiece = '1'
					
			WHERE	orden_id = ".$orden_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_valor_indicador- fall&oacute;: ' . mysql_error());
		}


}


}		
?>