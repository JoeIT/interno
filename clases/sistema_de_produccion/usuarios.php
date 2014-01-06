<?php

 include_once('../../clases/includes/dbmanejador.php');
  
class Usuarios
{
	function Usuarios()
	{
	}
	
	function comprobar($login, $pass)
	{
		session_start();
		
		$con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta= "select * from tusuarios where nick='".$login."' and password='".$pass."'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;:' . mysql_error());
			$ingreso=0;
	
			if (!$resultado) return false;
		 	else
		 	{
				while($row=mysql_fetch_array($resultado))
				{
					$s_tipo=$row["categoria"];    
					if($row["categoria"]=='administrador')
					{  				
						$_SESSION["login"]=$login;
						$_SESSION["nombre_usuario"]=$row["nombre_usuario"];
						$_SESSION["categoria"]=$row["categoria"];
						
						alert("entro");
						//$t->assign('title', 'Usuario Administrador');
						//$t->assign('messages', array('Por favor, llena el formulario de nuevo cliente'));
						//$t->assign('messages_warning', true);
						//$t->assign('page_template', 'index');
						//$t->display('../../../vistas/templates/adminitrador/page.tpl');
					}
					else
					{
					    echo 'no eres administrador';
					}
				} 
				
				return true;
			}
		}
	}
	  
	function salir_session()
	{
			session_start();
			if(!isset($_SESSION))
			{
				header("location: ../index.php");
			} 
			else 
			{
				//session_unregister($_SESSION);
				$_SESSION = array();
				session_unset();
				session_destroy();
				unset($_SESSION);
				header("location: ../index.php");
			}
	}
	function consulta_lista_usuarios()
	{
	    $con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta= 'SELECT * FROM tusuarios';
	        $resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else  
			{
				$contador=0;

				while($row = mysql_fetch_array($resultado))
					{
					  $respuesta[$contador]["codigo"]=$row['usuario_id'];	
	  				  $respuesta[$contador]["nombre"]= $row['nombre'];
					  $respuesta[$contador]["email"]= $row['email'];
	  				  $respuesta[$contador]["categoria"]= $row['categoria'];
	  		          $contador=$contador+1;
	  				}
			     return $respuesta;
			}
		}
	}
	
	function ver_datos_usuario($id)
	{
		$con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta= "SELECT * FROM tusuarios where usuario_id='$id'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{

						$respuesta[$contador]["codigo"]= $row['usuario_id'];
						$respuesta[$contador]["nombre"]= $row['nombre'];
  				  		$respuesta[$contador]["nick"]= $row['nick'];
  				  		$respuesta[$contador]["categoria"]= $row['categoria'];
  				   		$respuesta[$contador]["email"]= $row['email'];
                  		$contador=$contador+1;

  					}
		     	return $respuesta;
		  	}

		/*if (!$resultado) return false;
			else  
			{
				$contador=0;
				$row = mysql_fetch_array($resultado);
				$respuesta=array("id",$id,"nick",$row['nick'],"nombre",$row['nombre'],"email",$row['email'],"categoria",$row['categoria']); 
	  		    return $respuesta;
			} */
		}
	}
	
	function dar_permiso($usuario,$tarea)
	{
		$con = new DBmanejador;
	    if($con->conectar()==true)
	    {

			$consulta="SELECT * FROM tpermisos WHERE usuario_id='".$usuario."' and tarea_id='".$tarea."'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if(mysql_num_rows($resultado))
			{
				echo "El usuario ya existe en la BD";
			} 
			else 
			{
				mysql_free_result($resultado);
				$consulta="INSERT INTO tpermisos (usuario_id,tarea_id) VALUES ('$usuario','$tarea')";
					$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
					if(mysql_affected_rows())
					{
						return true;
					}
					else 
					{
						return false;
					} 
				
			} 

			
		}
	
	}
		   
	function nuevo_usuario($login,$nombre,$password,$email,$categoria)
	{
		$con = new DBmanejador;
	    if($con->conectar()==true)
	    {
			$consulta="SELECT * FROM tusuarios WHERE nick='".$login."'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if(mysql_num_rows($resultado))
			{
				echo "El usuario ya existe en la BD";
			} 
			else 
			{
				mysql_free_result($resultado);
				$consulta="INSERT INTO tusuarios (nombre,password,email,categoria,nick) VALUES ('$nombre','$password','$email','$categoria','$login')";
					$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
					if(mysql_affected_rows())
					{
						return true;
					}
					else 
					{
						return false;
					} 
				
			} 

		}
	}

	function modificar_usuario($id,$login,$nombre,$email,$categoria)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
	    {
			$consulta="update tusuarios set nick='".$login."', nombre='".$nombre."', email='".$email."', categoria='".$categoria."' WHERE usuario_id='".$id."'";
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
			 	else return true;
		}
	}
	
	function eliminar_usuario($id)
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
	    {
			$consulta= 'delete from tusuarios where usuario_id='.$id;
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado) return false;
			 	else return true;
		}
	}
	
	function sacar_ultimo()
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
	    {
			$consulta= 'SELECT Max(usuario_id)as codigo from tusuarios';
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado) { return false;}
			 	else {
						$row = mysql_fetch_array($resultado);	
						return $row[codigo];
					}
		}
	}
	
	function Quitar($mensaje)
	{
		$mensaje = str_replace("<","<",$mensaje);
		$mensaje = str_replace(">",">",$mensaje);
		$mensaje = str_replace("\'","'",$mensaje);
		//	$mensaje = str_replace('\"',""",$mensaje);
		//	$mensaje = str_replace("\\\\","\",$mensaje);
		return $mensaje;
	}	
	function busqueda_usuarios($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= "select nombres from tusuarios where nombres like '%".$cadena."%'";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		
						$respuesta[$contador]= $row['nombres'];
                  		$contador=$contador+1;
  					}

		     	return $respuesta;
		  	}
		 }
      }
	
	
	
}
?>