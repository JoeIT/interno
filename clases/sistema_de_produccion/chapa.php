<?php

   include_once('../../clases/includes/dbmanejador.php');
  
  class Chapa
  {
      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE COLORES
      function consulta_lista_chapa()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tchapas';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['chapa_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function consultar_chapa($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tchapas where chapa_id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['chapa_id'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  // FUNCION PARA INSERTAR UN NUEVO TIPO DE CHAPA
	  function nuevo_chapa($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into tchapas (descripcion) values('".$descripcion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return "false";
			 else return "true";

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE CHAPA
  	  function eliminar_chapa($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tchapas where chapa_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  function verificar_chapa($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT chapa_id FROM tchapas WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $chapa_id = $row['chapa_id'];
					  $contador++;
				  }
				  if($contador==0) $chapa_id=-1;
			  
			  }
			  
			  return $chapa_id;
			}
	   
	  }
	  
	
	 function modificar_chapa($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tchapas set descripcion='".$descripcion."' where chapa_id=".$id;

				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
		}
		
		
		function busqueda_sellos($cadena)
        {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "
			SELECT	descripcion
			FROM	tchapas
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