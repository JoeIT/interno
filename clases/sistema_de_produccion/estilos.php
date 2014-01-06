<?php

	include_once('../../clases/includes/dbmanejador.php');
  
  class Estilo
  {

     

      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE ESTILOS
      function consulta_lista_estilos()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM estilo';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['estilo_id'];
						$respuesta[$contador]["descripcion"]= $row['nombre_estilo'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }

		function consultar_estilo($id)
		  {
	
			 $con = new DBmanejador;
			 if($con->conectar()==true)
			 {
				$consulta= 'SELECT * FROM estilo where estilo_id='.$id ;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
	
				if (!$resultado) return false;
				else
				{      $contador=0;
	
						while($row = mysql_fetch_array($resultado))
						{
							$respuesta[$contador]["id"]= $row['estilo_id'];
							$respuesta[$contador]["descripcion"]= $row['nombre_estilo'];
							$contador=$contador+1;
	
						}
	
					return $respuesta;
				}
			 }
		  }

		//FUNCION PARA INSERTAR UN NUEVO TIPO DE ESTILO
		function nuevo_estilo($descripcion, $tipo_estilo){
			$con = new DBmanejador;
			if ($con->conectar() == true) {
				$consulta = "INSERT  into estilo (nombre_estilo, producto_id) values('".$descripcion."', ".$tipo_estilo.")";
				$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				
				if (!$resultado)
					return false;
				else
					return true;
			}
		}
		
		//FUNCION PARA ELIMINAR TIPO DE ESTILO
		function eliminar_estilo($id) {
			$con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from estilo where estilo_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
      function verificar_estilo($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT estilo_id FROM estilo WHERE nombre_estilo ='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $estilo_id = $row['estilo_id'];
					  $contador++;
				  }
				  if($contador==0) $estilo_id=-1;
			  
			  }
			  
			  return $estilo_id;
			}
	   
	  }
	 

      function verificar_familia($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT familia_id FROM familia WHERE nombre_familia = ". $descrip;
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $estilo_id = $row['estilo_id'];
					  $contador++;
				  }
				  if($contador==0) $estilo_id=-1;
			  
			  }
			  
			  return $estilo_id;
			}
	   
	  }
 
	
   	  function modificar_estilo($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update estilo set nombre_estilo='".$descripcion."' where estilo_id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	  }
      
	

	  
    
	}
?>