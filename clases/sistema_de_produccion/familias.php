<?php

   include_once('../../clases/includes/dbmanejador.php');

  class Familia
  {
			function consulta_lista_familias()
     		{
			     $con = new DBmanejador;
        		 if($con->conectar()==true)
		         {
				     $consulta="select descripcion,familia from tmodelos";
   			         $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				     if (!$resultado) return false;
					 else
					 {
					        if(mysql_num_rows($resultado) == 0)
							{
							   return false;
							}
							else
							{
							          for($a=0;$a<(mysql_num_rows($resultado));$a++)
									  {
									 		$reg = mysql_fetch_array($resultado);
											$modelos[$reg["familia"]][]=$reg["descripcion"];
		  							  }
									  foreach($modelos as $indice => $valor)
								      {
								          $contador=0;
								          $array_modelos="";
								          $cadena_modelos="";
								          foreach($modelos[$indice] as $modelo)
										  {

								              if($contador==0)
								              {
									                 $array_modelos=split(" ",$modelo);
								              }
								              else
								              {
								                     $modelo=split(" ",$modelo);
								                     for ($contador=0;$contador<count($modelo);$contador++)
								                     {
								                          if($modelo[$contador]!= $array_modelos[$contador])
                                								 $array_modelos[$contador]= $array_modelos[$contador].",".$modelo[$contador];

													 }

			  								}

											  $contador++;
								          }
								          for ($contador=0;$contador<count($array_modelos);$contador++)
								          {
							                  $cadena_modelos=$cadena_modelos." ".$array_modelos[$contador];
										  }
								          $lista_modelos[$indice]=$cadena_modelos;
									}
							   return $lista_modelos;
							}
					 }
				 }  			
        }
	  
	  /* function verifica_familia_valida($cadena_familia)
	   {
			   $lista_familias=$this->consulta_lista_familias();
			   $familia_id=-1;
			   foreach($lista_familias as $indice => $valor )
			   {
				  if (trim($lista_familias[$indice])==$cadena_familia)
					     $familia_id=$indice;
			   }
						  
			  return $familia_id;							  
	  }*/
	  function verifica_familia_valida($cadena_familia)
	  {
	      $cadena_separada=split(":::",$cadena_familia);
          $familia=trim($cadena_separada[0]);		  
          $estilo=trim($cadena_separada[1]);
        
	      $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "select f.familia_id from familia f, estilo e
						where f.estilo_id = e.estilo_id AND  UPPER(e.nombre_estilo) ='" .strtoupper($estilo)."' and UPPER(f.nombre_familia)='".strtoupper($familia)."'";
             //echo $consulta."<br>";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

                  
		 	if (!$resultado)
				return false;
		 	else{
				$familia_id=-1;
				 
				while($row = mysql_fetch_array($resultado))
				{
				
					$familia_id= $row['familia_id'];
  				}
				return $familia_id;
		  	}
		 }
	  
	  
	  }
	  
	  
		//*****************  OBTIENE UNA CADENA DE MODELOS DE ACUERDO A UN ID***********//	
	  function Obtener_cadena_modelos($familia)
	  {
	           $con = new DBmanejador;
        		 if($con->conectar()==true)
		         {
				     $consulta="select f.familia_id, f.nombre,m.num from tfamilias f,tfamilia_modelo u,tmodelos m where f.familia_id=u.familia_id and u.modelo_id=m.modelo_id and f.familia_id=".$familia;
   			         $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				     if (!$resultado) return false;
					 else
					 {
					        if(mysql_num_rows($resultado) == 0)
							{
							   return "No existen esa familia registrada";
							}
							else
							{
							          $modelos="";
							          for($a=0;$a<(mysql_num_rows($resultado));$a++)
									  {
									 
											$reg = mysql_fetch_array($resultado);
											$familia= $reg["nombre"];
											if($modelos=="") 
											   $modelos=$reg['num'];
											else
											   $modelos= $modelos.",".$reg["num"];
											
		  							  }
									  
									  
									  
									
							    return $familia." ".$modelos;
							}
					 
					 }
				 
				 }  
			   
	   
	  
	  }			
	  // OBTENEMOS LA FAMILIA APARTIR DE UN ID DE MODELO
	  function obtener_familia($id)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta='select f.nombre,m.num from tfamilias f,tfamilia_modelo u,tmodelos m where f.familia_id=u.familia_id and u.modelo_id=m.modelo_id and  modelo_Id='.$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else 
				{
					$row = mysql_fetch_array($resultado);
					$cadena[0]['familia']=$this->Obtener_cadena_modelos($row['familia']);
					return $cadena;
				}
            }
	  }
	  // OBTENEMOS EL ID DE LA FAMILIA APARTIR DE LA CADENA CONCATENADA.
	  function obtener_id_familia($cadena)
      {
	  		list($cadena1,$cadena2)=split(" ",$cadena,2);	
	  		list($cadena3,$cadena4)=split("\,",$cadena2,2);	
			$modelo=$cadena1." ".$cadena3;

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
				$producto='SELECT familia from tmodelos where descripcion="'.trim($modelo).'"';
				$identificador=mysql_query($producto) or die('La consulta fall&oacute;: ' . mysql_error());
				if (!$identificador) return false;
			 	else 
				{
					$row = mysql_fetch_array($identificador);
					return $row['familia'];
				}
            }
	  }
	  
  	  function nueva_familia($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
				$id= "SELECT max(familia) as mayor from tmodelos";
				$resultado=mysql_query($id) or die('La consulta fall&oacute;: ' . mysql_error());
				$row = mysql_fetch_array($resultado);
				$mayor=$row['mayor']+1;
				$consulta= "INSERT  into tmodelos (descripcion,familia) values('".$descripcion."','".$mayor."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		  	    if (!$resultado) return "false";
				else return "true";
        	}
      }
	  function cambiar_familia($descripcion)
	  {
	  		$con = new DBmanejador;
         	if($con->conectar()==true)
         	{
				$id= "SELECT max(familia) as mayor from tmodelos";
				$resultado=mysql_query($id) or die('La consulta fall&oacute;: ' . mysql_error());
				$row = mysql_fetch_array($resultado);
				$mayor=$row['mayor']+1;				
				$consulta= "update tmodelos set familia=".$mayor." where descripcion='".$descripcion."'";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		  	    if (!$resultado) return "false";
				else return "true";

			}	
	  }
	  
	  function cambiar_nueva_familia($descripcion,$fam)
	  {
	  		$con = new DBmanejador;
         	if($con->conectar()==true)
         	{
				$consulta= "update tmodelos set familia='".$fam."' where descripcion='".$descripcion."'";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		  	    if (!$resultado) return "false";
				else return "true";

			}	
	  }
		// Obtenemos la lista de modelos apartir de un id familia 
		
		function obtener_lista_modelos($fam)		
		{
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			 	$sql="SELECT * FROM tmodelos WHERE familia=".$fam;	  				
				$resultado=mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
				if (!$resultado) return false;
				else
				{      $contador=0;
	
						while($row = mysql_fetch_array($resultado))
						{
							$respuesta[$contador]["codigo"]= $row['modelo_Id'];
							$respuesta[$contador]["descripcion"]= $row['descripcion'];
							$contador=$contador+1;
						}
						return $respuesta;
				}
			}
		}
	  
	  /* ******************  FUNCIONES DE MODELOS  ********************/
	  
	  function consulta_lista_modelo()
      {
	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tmodelos order by familia';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['modelo_Id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;
  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function consultar_modelo($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tmodelos where modelo_Id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['modelo_Id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
						$respuesta[$contador]["familia"]= $row['familia'];
                  		$contador=$contador+1;
  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function modificar_modelo($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tmodelos set descripcion='".$descripcion."' where modelo_Id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	  }
	  
	  function verificar_modelo($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT modelo_Id FROM tmodelos WHERE descripcion='". $descrip ."'";
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			  
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $modelo_id = $row['modelo_Id'];
					  $contador++;
				  }
				  if($contador==0) $modelo_id=-1;
			  
			  }
			  
			  return $modelo_id;
			}
	   
	  }
	  
	  function eliminar_modelo($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tmodelos where modelo_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  
	  // FUNCION PARA INSERTAR UN NUEVO MODELO
	  function nuevo_modelo($descripcion,$familia)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
				if ($familia==" ")
				{
					$id= "SELECT max(familia) as mayor from tmodelos";
					$resultado=mysql_query($id) or die('La consulta fall&oacute;: ' . mysql_error());
					$row = mysql_fetch_array($resultado);
					$mayor=$row['mayor']+1;
					$consulta= "INSERT  into tmodelos (descripcion,familia) values('".$descripcion."','".$mayor."')";
					$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				}
				else
				{
					$f=$this->obtener_id_familia($familia);
					$consulta= "INSERT  into tmodelos (descripcion,familia) values('".$descripcion."','".$f."')";
					$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				}


			 if (!$resultado) return "false";
			 else return "true";

        	}
      }
	  
	  function buscar($opcion,$cadena)
        {
		       $con = new DBmanejador;
			   if($con->conectar()==true)
				{  	
				     $consulta= "SELECT * FROM tmodelos WHERE ".$opcion. " like '%" . $cadena . "%'";
			         $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

					if (!$resultado) return false;
					else  
					{
						$contador=0;
                        
						while($row = mysql_fetch_array($resultado))
						{
					   		$respuesta[$contador]["codigo"]= $row['modelo_Id'];
					    	$respuesta[$contador]["descripcion"]= $row['descripcion'];
  				  		    
                  		    $contador=$contador+1;
                        }
  				
    					return $respuesta;
		 		
					}
				}
	  }
	  
	  /*
	  function busqueda_familias($cadena)
	  {
	             $con = new DBmanejador;
        		 if($con->conectar()==true)
		         {
				     $consulta="select f.familia_id,f.nombre,m.num from tfamilias f,tfamilia_modelo u,tmodelos m where f.familia_id=u.familia_id and u.modelo_id=m.modelo_id and (f.nombre like '%".$cadena."%' or m.num like '%".$cadena."%')";
   			         $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				     if (!$resultado) return false;
					 else
					 {
					        if(mysql_num_rows($resultado) == 0)
							{
							   return "No existen esa familia registrada";
							}
							else
							{
							          $modelos="";
							          for($a=0;$a<(mysql_num_rows($resultado));$a++)
									  {
									 
											$reg = mysql_fetch_array($resultado);
											if(trim($lista[$reg["familia_id"]][trim($reg["nombre"])]['modelos'])=="")
											{
											   
											   $lista[$reg["familia_id"]][trim($reg["nombre"])]['modelos']=$reg["num"];
											   
											}
											else
											{
										
											$lista[$reg["familia_id"]][trim($reg["nombre"])]['modelos']=$lista[$reg["familia_id"]][trim($reg["nombre"])]['modelos'].",". $reg["num"];
											 }
											
										
		  							  }
									   $contador=0;
									  foreach($lista as $familia_id => $modelos)
								      { 
									     
								          
								          foreach($modelos as $modelo => $numeros)
										  {
										      
										       $lista_modelos[$contador]=$modelo." ".$numeros['modelos'];
											   $contador++;
										  
									      }
									 }
									return $lista_modelos;
							}
					 
					 }
				 
				 }  
			   
	  }		*/
	  function busqueda_familias($cadena)
	  {
	     $cadena=str_replace(':::',' ', $cadena);
		 $cadena=split(' ', $cadena);
	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "
			select	f.nombre_familia,e.nombre_estilo from familia f, estilo e
			where	f.estilo_id = e.estilo_id" ;
			for($contador=0; $contador < count($cadena);$contador++)
			{
			   str_replace(' ', '%', $cadena[$contador]);
			   $consulta=$consulta." AND (e.nombre_estilo like '%".$cadena[$contador]."%' or f.nombre_familia like '%".$cadena[$contador]."%')";
			   
			}
				
			$consulta=$consulta." order by f.nombre_familia limit 0,20";
            
             
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

                  
		 	if (!$resultado)
				return false;
		 	else{
				$contador=0;
				
				while($row = mysql_fetch_array($resultado)){
				
					$respuesta[$contador]= $row['nombre_familia']." ::: ".$row['nombre_estilo'];
                  	$contador=$contador+1;
  				}
				return $respuesta;
		  	}
		 }
     }
}	  
?>