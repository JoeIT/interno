<?php


   include_once('../../clases/includes/dbmanejador.php');
  


  class Etiqueta
  {

   
      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE COLORES
      function consulta_lista_etiquetas()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tetiquetas';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['etiqueta_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  function consultar_etiqueta($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tetiquetas where etiqueta_id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['etiqueta_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  // FUNCION PARA INSERTAR UN NUEVO TIPO DE ETIQUETA
	  function nuevo_etiqueta($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into  tetiquetas (descripcion) values('".$descripcion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE ETIQUETA  	
  	  function eliminar_etiqueta($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tetiquetas where etiqueta_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }

   	  function modificar_etiqueta($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tetiquetas set descripcion='".$descripcion."' where etiqueta_id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	  }
	  function verificar_etiqueta($descrip)
       {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT etiqueta_id FROM tetiquetas WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $etiqueta_id = $row['etiqueta_id'];
					  $contador++;
				  }
				  if($contador==0) $etiqueta_id=-1;
			  
			  }
			  
			  return $etiqueta_id;
			}
	   
	  }

      function busqueda_etiquetas($cadena)
        {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "
			SELECT	descripcion
			FROM	tetiquetas
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

	}

?>