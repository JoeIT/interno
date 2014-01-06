<?php
  include_once('../../clases/includes/dbmanejador.php');

  class Cliente
  {
		
       //function Cliente() {}  

      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE CLIENTES
      function consulta_lista_clientes()
      {

	     $con = new DBmanejador;
		 
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tclientes';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['cliente_id'];
						$respuesta[$contador]["nombre"]= $row['nombre'];
  				  		$respuesta[$contador]["pais"]= $row['pais'];
  				  		$respuesta[$contador]["ciudad"]= $row['ciudad'];
						$respuesta[$contador]["direccion"]= $row['direccion'];
  				  		$respuesta[$contador]["telefono"]= $row['telefono'];
  				  		$respuesta[$contador]["email"]= $row['email'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }

      //ADICIONA UN NUEVO CLIENTE A LA BASE DE DATOS
      function adicionar_cliente($nombre,$pais,$ciudad,$direccion,$telefono,$email)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into  tclientes(nombre,pais,ciudad,direccion,telefono,email) 
				                    values('".$nombre."','".$pais."','".$ciudad."','".$direccion."','".$telefono."','".$email."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;

        	}
      }

      //ELIMINA EL CLIENTE CON DEL ID INDICADO, DE LA BASE DE DATOS
      function eliminar_cliente($id)
      {
          $con = new DBmanejador;
          if($con->conectar()==true)
          {
		     	$consulta= 'delete from tclientes where cliente_id='.$id;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			    if (!$resultado) return false;
		    	else return true;

          }
      }
      //MODIFICA LA INFORMACION DEL CLIENTE CON EL ID INDICADO
      function modificar_cliente($id,$nombre,$pais,$ciudad,$direccion,$telefono,$email)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tclientes set nombre='".$nombre."',pais='".$pais."',ciudad='".$ciudad. 
				            "',telefono='".$telefono."',email='".$email."',direccion='".$direccion."' where cliente_id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 	if (!$resultado) return false;
			 	else return true;
            }
		}

	  // RETORNA LA INFORMACION DEL CLIENTE CON EL ID INDICADO
	  function ver_datos_cliente($id)
	  {
	        $con = new DBmanejador;
        if($con->conectar()==true)
        {
		    $consulta= 'SELECT * FROM tclientes where cliente_id='.$id;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{     

						$row = mysql_fetch_array($resultado);
						$respuesta["codigo"]= $row['cliente_id'];
						$respuesta["nombre"]= $row['nombre'];
  				  		$respuesta["pais"]= $row['pais'];
  				  		$respuesta["ciudad"]= $row['ciudad'];
  				  		$respuesta["telefono"]= $row['telefono'];
						$respuesta["direccion"]= $row['direccion'];
  				  		$respuesta["email"]= $row['email'];  					
	  		     	    return $respuesta;
		  	}
		}
		
		}
        function consultar_busqueda($cadena,$opcion)
        {
		       $con = new DBmanejador;
			   if($con->conectar()==true)
				{  	
				     $consulta= "SELECT * FROM   tclientes  
				               WHERE ".$opcion. " like '%" . $cadena . "%'";
			       
                    $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

					if (!$resultado) return false;
					else  
					{
						$contador=0;
                        
						while($row = mysql_fetch_array($resultado))
						{
					   		$respuesta[$contador]["codigo"]= $row['cliente_id'];
					    	$respuesta[$contador]["nombre"]= $row['nombre'];
  				  		    $respuesta[$contador]["pais"]= $row['pais'];
  				  		    $respuesta[$contador]["ciudad"]= $row['ciudad'];
  				  		    $respuesta[$contador]["telefono"]= $row['telefono'];
  				  		    $respuesta[$contador]["email"]= $row['email'];
                  		    $contador=$contador+1;
                        }
  				
    					return $respuesta;
		 		
					}
				}
	  }
	  function verificar_cliente($nombre)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT cliente_id FROM   tclientes WHERE nombre='". $nombre ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $cliente_id = $row['cliente_id'];
					  $contador++;
				  }
				  if($contador==0) $cliente_id=-1;
			  
			  }
			  
			  return $cliente_id;
			}
	   
	  }
	  
	  function busqueda_clientes($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "select nombre from tclientes where nombre like '%".$cadena."%' order by nombre limit 0,20";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		
						$respuesta[$contador]= $row['nombre'];
                  		$contador=$contador+1;
  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  
   }
?>