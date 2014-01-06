<?php


   include_once('../../clases/includes/dbmanejador.php');
  
  class Cuero
  {

     

      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE COLORES
      function consulta_lista_cuero()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tcueros';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['cuero_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }

	 function consultar_cuero($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tcueros where cuero_id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['cuero_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  // FUNCION PARA INSERTAR UN NUEVO TIPO DE CUERO
	  function nuevo_cuero($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into  tcueros (descripcion) values('".$descripcion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE CUERO
  	  function eliminar_cuero($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tcueros where cuero_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  function verificar_cuero($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT cuero_id FROM tcueros WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $cuero_id = $row['cuero_id'];
					  $contador++;
				  }
				  if($contador==0) $cuero_id=-1;
			  
			  }
			  
			  return $cuero_id;
			}
	   
	  }
	  
	  function busqueda_cueros($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "
			SELECT	descripcion
			FROM	tcueros
			WHERE	descripcion like '%".$cadena."%'
			ORDER BY descripcion
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
   	  function modificar_cuero($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tcueros set descripcion='".$descripcion."' where cuero_id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	  }

	  
	}
?>