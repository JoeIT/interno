<?php

  include_once('../../clases/includes/dbmanejador.php');
  
  class PosicionPieza
  {
      
      
	  function busqueda_posiciones($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "select distinct descripcion from tposicion_pieza where descripcion like '%".$cadena."%' and estado=1 limit 0,5";
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
	  
	  function verificar_posicion($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT posicion_pieza_id FROM   tposicion_pieza WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $posicion_pieza_id = $row['posicion_pieza_id'];
					  $contador++;
				  }
				  if($contador==0) $posicion_pieza_id=-1;
			  
			  }
			  
			  return $posicion_pieza_id;
			}
	   
	  }
	  

	  
	}

?>