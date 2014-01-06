<?php

  include_once('../../clases/includes/dbmanejador.php');
  
  class Color
  {

      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE COLORES
      function consulta_lista_colores()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tcolores';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['color_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
						$respuesta[$contador]["coleccion"]= $row['coleccion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function consultar_color($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tcolores where color_id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['color_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
						$respuesta[$contador]["coleccion"]= $row['coleccion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function busqueda_colores($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "
			SELECT	descripcion
			FROM	tcolores
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
	  
	  function busqueda_colecciones($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "select coleccion from tcolores where coleccion like '%".$cadena."%' Limit 0,5";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		
						$respuesta[$contador]= $row['coleccion'];
                  		$contador=$contador+1;
  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  
	  
	  // FUNCION PARA INSERTAR UN NUEVO TIPO DE COLOR
	  function nuevo_color($descripcion ,$coleccion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT into tcolores (descripcion, coleccion) values('".$descripcion."','".$coleccion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE COLOR  	
  	  function eliminar_color($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tcolores where color_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }

       function verificar_color($descrip)
       {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT color_id FROM   tcolores WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $color_id = $row['color_id'];
					  $contador++;
				  }
				  if($contador==0) $color_id=-1;
			  
			  }
			  
			  return $color_id;
			}
	   
	  }
	 
	  function modificar_color($id,$descripcion,$coleccion)
      {
		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tcolores set descripcion='".$descripcion."', coleccion='".$coleccion."' where color_id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	  }

      

	}

?>