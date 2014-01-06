<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/paginas.php');
include_once('../../clases/includes/validador.php');

$pagina=new Paginas;
$validar = new Validador();
$funcion = $_GET['funcion'];


$smarty = new Smarty();
$smarty->template_dir = '../../templates/usuarios/';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	if(isset($_GET["busqueda_ajax"]))
	 {    
		  $valor = $_POST["value"];
		  $cadena= trim($valor);
		  echo "<ul>";
		  $lista=$pagina->busqueda_pestanas($cadena);
		  if(count($lista)==0)
		  {
			   echo "<li>No hay resultados</li>";
		  }
		  else
		  {
			for($contador=0;$contador<count($lista);$contador++)
				{
					echo "<li>".$lista[$contador]."</li>";
			  
				}
		  
		  }
		  echo "</ul>";
	 }
	else
	{
	
	if($funcion=="registrar")
	{
	
		if (!empty($_GET))
		{
	
			if ($validar->validarTodo($_GET['nombre'], 1, 100))
				$error['err_descripcion'] = "Ingresa la pagina";
				
		  if (isset($error))
			{
				 $smarty->assign('nombre',$_GET['nombre']);
				 $smarty->assign('errores',$error);
				 $consulta= $pagina->consulta_lista_paginas();
				 $smarty->assign('pagina',$consulta);
				 $smarty->display('paginas.html');
			}
		   else
			 {
	
				 $descripcion = $_GET['nombre'];
				 $icono = $_GET['icono'];
							
				$nuevo_icono="";
			
				 for($i=strlen($icono)-1; $i>=0 ; $i--)
				 {
					
					if(	$icono{$i}=='\\' || $icono{$i}=='/' )
					{break;}
					else
					{	$nuevo_icono = $icono{$i}.$nuevo_icono; }
				 }
	
				 $icono =$nuevo_icono;		
	
				 $pestana = $_GET['pestana'];
				 $url = $_GET['url'];	
				 $nuevo_url="";
			
				 for($i=strlen($url)-1; $i>=0 ; $i--)
				 {
					
					if(	$url{$i}=='\\' || $url{$i}=='/' )
					{break;}
					else
					{	$nuevo_url = $url{$i}.$nuevo_url; }
				 }
			
				 $url = "../controladores/sistema_de_produccion/".$nuevo_url;	
				
				 
				 $observaciones = $_GET['observaciones'];
				 
				 $resultado=$pagina->nueva_pagina($descripcion,$url,$observaciones,$icono,$pestana);
			 
				   if($resultado)
				   {
					 $error['err_confirm'] = "El registro se realizo correctamente";
					 $smarty->assign('errores',$error);
							
					 $consulta= $pagina->consulta_lista_paginas();
					$smarty->assign('pagina',$consulta);
					$smarty->display('paginas.html');
	
				   }
			 }
		}
	}
	else
	{
		if($funcion=="modificar")
		{
		   $id=$_GET['elegido'];
		   //consultamos los datos del cliente
		   $lista=$pagina->consulta_pagina($id);
		   $smarty->assign('id',$lista[0][id]);
		   $smarty->assign('descripcion',$lista[0][descripcion]);
		   $smarty->assign('url',$lista[0][url]);
		   $smarty->assign('obs',$lista[0][observaciones]);
		   $smarty->assign('icono',$lista[0][icono]);
		   $smarty->assign('pestana',$lista[0][pestana]);
		   $smarty->display('modificar_pagina.html');
		
		}
		else
		{
			if($funcion=="modificar_datos")
			{
				
				$validar = new Validador();
				if ($validar->validarTodo($_GET['nombre'], 1, 100))
						$error['err_nombre'] = "Ingresa el nombre de la pagina";
				if ($validar->validarTodo($_GET['url'], 1, 100))
						$error['err_url'] = "Ingresa la url de la pagina";
				//if ($validar->validarTodo($_GET['obs'], 1, 100))
					//	$error['err_obs'] = "Ingresa las observaciones de la pagina";
				
	
				if (isset($error))
				{
	
					   $smarty->assign('id',$_GET['elegido']);
					   $smarty->assign('descripcion',$_GET['nombre']);
					   $smarty->assign('url',$_GET['url']);
					   $smarty->assign('obs',$_GET['obs']);
					   $smarty->assign('errores',$error);
					    $smarty->display('modificar_pagina.html');

	
				}
				else
				{
				
				   //obtenemos toda la información del formulario de modificación
				   $id=$_GET['elegido'];
			
				   $nombre = $_GET['nombre'];
				   $url = $_GET['url'];
				   $obs = $_GET['obs'];
				   $icono = $_GET['icono'];
				   $pestana = $_GET['pestana'];
				   //enviamos la información a la función de modificar chapa
				   $resultado=$pagina->modificar_pagina($id,$nombre,$url,$obs,$icono,$pestana);
				   //obtenemos la lista de clientes y la enviamos a la plantilla
				   $consulta= $pagina->consulta_lista_paginas();
					$smarty->assign('pagina',$consulta);
					$smarty->display('paginas.html');
	
				  }
			}
			else
			{
				if($funcion=="eliminar")
				{
				   $id=$_GET['elegido'];
		
				   $consulta=$pagina->eliminar_pagina($id);
				   $consulta=$pagina->consulta_lista_paginas();
									$smarty->assign('pagina',$consulta);
						$smarty->display('paginas.html');
				   
				}
				else
				{
					$consulta= $pagina->consulta_lista_paginas();
					$smarty->assign('pagina',$consulta);
					$smarty->display('paginas.html');
	
				}
			}
		}
		}
	}

}
?>