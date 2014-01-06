<?php

include_once('../../clases/includes/dbmanejador.php');  

class Paginas
{
	function Paginas()
	{
	}
	
	function busqueda_pestanas($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "select distinct pestana from tpaginas where pestana like '%".$cadena."%'";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		
						$respuesta[$contador]= $row['pestana'];
                  		$contador=$contador+1;
  					}

		     	return $respuesta;
		  	}
		 }
      }
	function nueva_pagina($nombre,$url,$observacion,$icono,$pestana)
	{
		$con = new DBmanejador;
		
		if($con->conectar()==true)
		{
			if ($icono=="" and $pestana=="")
			{
			$consulta= "INSERT into tpaginas (nombre,URL,observaciones,pestana,icono) values('".$nombre."','".$url."','".$observacion."','".$pestana."','".$icono."')";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			}
			else
			{
			$consulta= "SELECT orden FROM tpaginas WHERE pestana='".$pestana."'";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			$row = mysql_fetch_array($resultado);
			if($row[0]==NULL)
			{
			$consulta= "SELECT max(orden) FROM tpaginas ";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			$row = mysql_fetch_array($resultado);
			$new = $row[0]+1;
			$consulta= "INSERT into tpaginas (nombre,URL,observaciones,pestana,icono,menu,orden) values('".$nombre."','".$url."','".$observacion."','".$pestana."','".$icono."',1,'".$new."')";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			}
			else
			{
			$consulta= "INSERT into tpaginas (nombre,URL,observaciones,pestana,icono,menu,orden) values('".$nombre."','".$url."','".$observacion."','".$pestana."','".$icono."',1,'".$row[0]."')";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			}
			}
			 if (!$resultado) return "false";
	 		 else return "true";
	
		}
	}
	
	function consulta_lista_paginas()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tpaginas';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['paginas_id'];
						$respuesta[$contador]["descripcion"]= $row['nombre'];
						$respuesta[$contador]["url"]= $row['URL'];
						$respuesta[$contador]["observaciones"]= $row['observaciones'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function lista_paginas()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tpaginas';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
					
					
					    $respuesta[$row[2]][$row[0]]=$row[1];
						
					    //$respuesta[$row['observaciones']][$row['paginas_id']]= $row['nombre'];
						
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  
	  function consulta_pagina($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tpaginas WHERE paginas_id='.$id;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["id"]= $row['paginas_id'];
						$respuesta[$contador]["descripcion"]= $row['nombre'];
						$respuesta[$contador]["url"]= $row['URL'];
						$respuesta[$contador]["observaciones"]= $row['observaciones'];
						$respuesta[$contador]["icono"]= $row['icono'];
						$respuesta[$contador]["pestana"]= $row['pestana'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  // Paginas de un grupo 
	  function obtener_paginas($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tpaginas p, tpermisos pe WHERE p.paginas_id=pe.paginas_id and grupousuario_id='.$id;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
						$respuesta[$contador]["codigo"]= $row['paginas_id'];
						$respuesta[$contador]["descripcion"]= $row['nombre'];
			       		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
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

	  // FUNCION PARA ELIMINAR TIPO DE tarea
  	  function eliminar_pagina($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tpaginas where paginas_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  
	  function eliminar_pagina_grupo($id,$grupo)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tpermisos where paginas_id=('".$id."') and grupousuario_id=('".$grupo."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	
	
	 function modificar_pagina($id,$nombre,$url,$observaciones,$icono,$pestana)
      {
	
			
		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
				if($icono=="" and $pestana=="")
				{
		    		$consulta= "update tpaginas set nombre='".$nombre."' , URL='".$url."' , observaciones='".$observaciones."' ,menu=0 where paginas_id='".$id."'";
				}
				else
				{
		    		$consulta= "update tpaginas set nombre='".$nombre."' , URL='".$url."' , observaciones='".$observaciones."', icono='".$icono."' , pestana='".$pestana."' ,menu=1 where paginas_id='".$id."'";
					
				}
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
		}
	

	
	
}
?>