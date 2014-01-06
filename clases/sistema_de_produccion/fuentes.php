<?php

   include_once('../../clases/includes/dbmanejador.php');
  
  class Fuente
  {
      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE COLORES
      function consulta_lista_fuente()
      {

          $con = new DBmanejador;
         if($con->conectar()==true)
         {
	    $consulta= 'SELECT * FROM tfuentes';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['fuente_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                 		$contador=$contador+1;
    				}

		     		return $respuesta;
		  	}
		 }
      }
	  
	  function consultar_fuente($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tfuentes where fuente_id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['fuente_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}
		     	return $respuesta;
		  	}
		 }
      }
	  // FUNCION PARA INSERTAR UN NUEVO TIPO DE FUENTE
	  function nuevo_fuente($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into tfuentes (descripcion) values('".$descripcion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return "false";
			 else return "true";

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE FUENTE
  	  function eliminar_fuente($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tfuentes where fuente_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  function verificar_fuente($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT fuente_id FROM tfuentes WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $fuente_id = $row['fuente_id'];
					  $contador++;
				  }
				  if($contador==0) $fuente_id=-1;
			  
			  }
			  
			  return $fuente_id;
			}
	   
	  }
	  
	
	 function modificar_fuente($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tfuentes set descripcion='".$descripcion."' where fuente_id=".$id;

				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
		}
		
		function busqueda_fuentes($cadena)
        {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "
			select descripcion
			from tfuentes
			where descripcion like '%".$cadena."%'
			order by descripcion
			Limit 0,20";
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
		
		
		
	}
?>
