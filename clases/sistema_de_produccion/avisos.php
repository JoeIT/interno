<?php

	include_once('../../clases/includes/dbmanejador.php');

  class Aviso
  {

		function obtener_lista_total_avisos($usuario)		
		{	
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			 	$sql="	SELECT a.fecha, a.asunto , concat( u.nombres,' ',u.apellidos),a.aviso_id as codigo
						FROM tavisos a,tusuarios u, tgrupousuario g 
						WHERE  u.usuario_id=a.emisor AND g.grupousuario_id=u.grupo_id AND (a.receptor=(SELECT u1.grupo_id from tusuarios u1 where u1.usuario_id=".$usuario.")
								OR a.receptor=0)
					 				
						order by codigo desc" ;	  				
				$resultado=mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
				if (!$resultado) return false;
				else
				{      $contador=0;
	
						while($row = mysql_fetch_array($resultado))
						{
							$respuesta[$contador]["fecha"]= $row['0'];
							$respuesta[$contador]["emisor"]= $row['2'];
							$respuesta[$contador]["descripcion"]= $row['1'];
							$respuesta[$contador]["codigo"]= $row['3'];
							$contador=$contador+1;
						}
						return $respuesta;
				}
			}
		}

		function obtener_lista_avisos($usuario)		
		{	
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			 	$sql="	SELECT a.aviso_id,a.fecha, a.asunto , concat( u.nombres,' ',u.apellidos) 
						FROM tavisos a,tusuarios u, tgrupousuario g 
						WHERE  u.usuario_id=a.emisor AND g.grupousuario_id=u.grupo_id AND a.receptor=(SELECT u1.grupo_id from tusuarios u1 where u1.usuario_id=".$usuario.") 				
						order by fecha desc limit 0,7" ;	  				
				$resultado=mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
				if (!$resultado) return false;
				else
				{      $contador=0;
	
						while($row = mysql_fetch_array($resultado))
						{
							$respuesta[$contador]["fecha"]= $row['0'];
							$respuesta[$contador]["emisor"]= $row['2'];
							$respuesta[$contador]["descripcion"]= $row['1'];
							$contador=$contador+1;
						}
						return $respuesta;
				}
			}
		}
	  
	  function eliminar_aviso($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tavisos where aviso_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  
	  // FUNCION PARA INSERTAR UN NUEVO MODELO
	 function nuevo_aviso($asunto,$emisor,$receptor)//ojo la fecha
      {
            $con = new DBmanejador;
			$fecha=date("Y-m-d");
			
			if($con->conectar()==true)
         	{ 
		    	$consulta= "INSERT  into tavisos (asunto,fecha,emisor,receptor) values('".$asunto."','".$fecha."','".$emisor."','".$receptor."')";
			
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			 if (!$resultado) return "false";
			 else return "true";

        	}
      }
	  

}	  
?>