<?php

 include_once('../../clases/includes/dbmanejador.php');  
class Tareas
{
	function Tareas()
	{
	}
	
	function ver_datos_tarea($id)
	{
		$con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta= "SELECT ttarea FROM tttareas where tarea_id='$id'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{

						$respuesta[$contador]["ttarea"]= $row['ttarea'];
                 		$contador=$contador+1;

  					}
		     	return $respuesta;
		  	}

		}
	}
	function nuevo_tarea($descripcion,$area)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		
				$id= "SELECT area_id from tmacawsareas where area='".$area."'";

				$resultado=mysql_query($id) or die('La consulta fall&oacute;: ' . mysql_error());
				$row = mysql_fetch_array($resultado);
				$area_id=$row['area_id'];
				
		    	$consulta= "INSERT  into ttareas (tarea,area_id) values('".$descripcion."','".$area_id."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return "false";
			 else return "true";

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE tarea
  	  function eliminar_tarea($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from ttareas where tarea_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  function verificar_tarea($descrip)
      {
	       
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta= "SELECT tarea_id FROM ttareas WHERE descripcion='". $descrip ."'";
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $tarea_id = $row['tarea_id'];
					  $contador++;
				  }
				  if($contador==0) $tarea_id=-1;
			  
			  }
			  
			  return $tarea_id;
			}
	   
	  }
	  
	
	 function modificar_tarea($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update ttareas set descripcion='".$descripcion."' where tarea_id=".$id;

				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
		}
	

	
	
}
?>