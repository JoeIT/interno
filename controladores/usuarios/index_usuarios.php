<?php
  
  include_once('../../clases/usuarios.php');
  
  $smarty = new Smarty;
  $usuario=new Usuarios;
  $funcion = $_POST['funcion'];
  
  if($funcion=="ingresar")
  {
	if (!empty($_POST))
	{
		$errors = array();
		if (!isset($_POST['login']) || empty($_POST['login']))
			$errors[] = 'Por favor, ingresa tu login';
		if (!isset($_POST['hash']) || empty($_POST['hash']))
		    $errors[] = 'Por favor, ingresa tu password';
		if (!empty($errors))
		{
			echo 'false';
			foreach ($errors as $error)
			{
				echo ';' . $error;
			}
			exit;
		}
		
		$login = $_POST['login'];
	    $pass = $_POST['hash'];
		$resultado=$usuario->comprobar($login, $pass);
		exit;
		
			/*
			if($resultado)
			{ 
				$consulta= $usuario->consulta_lista_usuario();
				$smarty->assign('usuario',$consulta);
				$smarty->display('../../../vistas/templates/usuarios/index.tpl');
			}*/
	}
	
	ajax_process_call();
	
	$t->assign('title', 'Formulario de entrada');
	$t->assign('messages', array('Por favor, identificate'));
	$t->assign('messages_warning', true);
	$t->assign('page_template', 'login');
	$t->display('../../../vistas/templates/usuarios/page.tpl');
  }
  else
  {
	if($funcion=="registrar")
	{
		$login=$_POST['nick'];
		$nombre= $_POST['nombre'];
		$password= $_POST['hash'];
		
		$email= $_POST['email'];
		$categoria= $_POST['categoria'];
		
		$resultado=$usuario->registrar_usuario($login,$nombre,$password,$email,$categoria);
		
		if($resultado)
		{ 
			$consulta= $usuario->consulta_lista_usuario();
			$smarty->assign('usuario',$consulta);
			$smarty->display('../../../vistas/templates/usuarios/index.tpl');
		}
	}
	else
	{
		if($funcion=="editar") 
		{
			$id=$_POST['id'];
			//nick
			$login=$_POST['3'];
			//nombre
			$nombre= $_POST['5'];
			//email
			$email= $_POST['7'];
			//categoria
			$categoria= $_POST['9'];
			
			$resultado=$usuario->modificar_usuario($id,$login,$nombre,$email,$categoria);
			
			if($resultado)
			{ 
				$consulta= $usuario->consulta_lista_usuario();
				$smarty->assign('usuario',$consulta);
				$smarty->display('../../../vistas/templates/usuarios/index.tpl');
			}	
		}
		else
		{
			if($funcion=="modificar")
			{
				$id=$_POST['elegido'];
				$consulta=$usuario->consulta_datos_usuario($id);
				$smarty->assign('usuario',$consulta);		
				$smarty->display('../../../vistas/templates/usuarios/modificar.tpl');
				
			}
			else
			{
					if($funcion=="eliminar") 
					{
						$id=$_POST['elegido'];
												
						$resultado=$usuario->eliminar_usuario($id);
						
						if($resultado)
						{ 
							$consulta= $usuario->consulta_lista_usuario();
							$smarty->assign('usuario',$consulta);
							$smarty->display('../../../vistas/templates/usuarios/index.tpl');
						}	
					}
					else
					{
						if($funcion=="salir") 
						{
							$usuario->salir_session();
						}
						else
						{
								$consulta= $usuario->consulta_lista_usuario();
							  	$smarty->assign('usuario',$consulta);
								$smarty->display('../../../vistas/templates/usuarios/index.tpl');
											
								
						}
					}
			}
		}
	}
}
?>