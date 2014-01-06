<?php

include_once('../../clases/includes/dbmanejador.php');  

class Grupos
{
	function Grupos()
	{
	}
	
	function obtener_id($cadena)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta= "select grupousuario_id from tgrupousuario where nombre like '%".$cadena."%'";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{
				$row = mysql_fetch_array($resultado);
				return $row['grupousuario_id'];
			}
		}
	}
	
	function busqueda_grupos($cadena)
	{
	
		 $con = new DBmanejador;
		 if($con->conectar()==true)
		 {
			$consulta= "select nombre from tgrupousuario where nombre like '%".$cadena."%'";
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
	
	function obtener_lista_total()
	{
	    $con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta= 'SELECT DISTINCT p.paginas_id as id, p.nombre as pagina, p.url as url, g.nombre as grupo FROM tgrupousuario g, tpaginas p, tpermisos a  WHERE g.grupousuario_id=a.grupousuario_id and p.paginas_id=a.paginas_id';
	        $resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado) return false;
			else  
			{
				$contador=0;

				while($row = mysql_fetch_array($resultado))
					{
						$respuesta[$row["grupo"]][$contador]["pagina_id"]=$row['id'];
					    $respuesta[$row["grupo"]][$contador]["pagina"]=$row['pagina'];
						$respuesta[$row["grupo"]][$contador]["url"]=$row['url'];
	
						$contador++;
						
	  				}
					
			     return $respuesta;
			}
		}
	}
	
	function nuevo_grupo($nombre,$paginas)
	{
		
		$con = new DBmanejador;
		
		if($con->conectar()==true)
		{
			$consulta= "INSERT  into tgrupousuario (nombre,estado) values('".$nombre."',1)";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
			 if (!$resultado) return "false";
	 		 else 
			 {
			 	
			 	$consulta= "select max(grupousuario_id) from tgrupousuario";
				$res=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			 	$row = mysql_fetch_array($res);		
				$id=$row[0];
		
				for($contador=0;$contador<count($paginas);$contador++)
				  {
				  	
					 $consulta= "INSERT into tpermisos(paginas_id,grupousuario_id) values(".trim($paginas[$contador]).",".$id.")";
					 $resultado=mysql_query($consulta) or die('La consulta fall&oacute en tpermisos; : ' . mysql_error());
					 
								 
				 }
			 	return "true";
			 }
	
		}
	}
	
	function consulta_lista_grupos()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tgrupousuario where estado=1';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['grupousuario_id'];
						$respuesta[$contador]["descripcion"]= $row['nombre'];

                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	
	function obtener_grupo($id)
	{
		$con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta= "SELECT * FROM tgrupousuario where grupousuario_id='$id'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{

						$respuesta[$contador]["nombre"]= $row['nombre'];
                 		$contador=$contador+1;

  					}
		     	return $respuesta;
		  	}

		}
	}

	  // FUNCION PARA ELIMINAR TIPO DE tarea
  	  function eliminar_grupo($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tgrupousuario where grupousuario_id=('".$id."')";
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
		
	function modificar_grupo($grupo,$nombre,$paginas)
	{
		echo $grupo;
		$con = new DBmanejador;
		
		if($con->conectar()==true)
		{
			$consulta= "update tgrupousuario set nombre='".$nombre."' where grupousuario_id='".$grupo."'";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
			 if (!$resultado) return "false";
	 		 else 
			 {
			 	
				for($contador=0;$contador<count($paginas);$contador++)
				  {
				  	
					 $consulta= "INSERT into tpermisos(paginas_id,grupousuario_id) values(".trim($paginas[$contador]).",".$grupo.")";
					 $resultado=mysql_query($consulta) or die('La consulta fall&oacute en tpermisos; : ' . mysql_error());
					 
								 
				 }
			 	return "true";
			 }
	
		}
	}
	

	
	
}
?>