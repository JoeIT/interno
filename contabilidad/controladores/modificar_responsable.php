<?php 
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/responsable.php');
include_once('../clases/area.php');
include_once('../clases/includes/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';
$area= new Area();
$responsable= new Responsable();
if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {
	
	$funcion = $_GET['funcion'];
	$resp_id=$_GET['resp_id'];
	if ($funcion == "detalle") 
	{ 
		
		 $resp_id = $_GET['elegido'];
		 $resp=$responsable->mostrar_responsable($resp_id);
		 
		 $nombre=$resp['nombre'];
		 $apellido=$resp['apellido'];
		 $cargo=$resp['cargo'];
		 $ci=$resp['ci'];
		 $celular=$resp['celular'];
		 $telefono=$resp['telefono'];
		 
		 $miarea=$responsable->area($resp_id);
		 $area_id=$miarea['area_id'];
		 $area=$area->listar_areas($area_id);
			 		
		$smarty->assign('resp_id',$resp_id);
		$smarty->assign('miarea',$miarea);
		$smarty->assign('area',$area);
		$smarty->assign('nombre',$nombre);
		$smarty->assign('apellido',$apellido);
		$smarty->assign('cargo',$cargo);
		$smarty->assign('ci',$ci);
		$smarty->assign('telefono',$telefono);
		$smarty->assign('celular',$celular);
		
		$smarty->display('modificar_responsable.html');
		

	}
	if(isset($_GET['modificar']))
	{ 
		if($_GET['funcion']== "validar")
			{     $error="";
		 $validar = new Validador();
		 if ($validar->validarTodo($_GET['nombre'], 1, 100))
			 $error['err_nombre'] = "Ingresa el nombre del responsable";
		 if ($validar->validarTodo($_GET['apellido'], 1, 100))
			 $error['err_apell'] = "Ingresa el apellido del responsable";
		 if ($validar->validarTodo($_GET['cargo'], 1, 100))
			$error['err_cargo'] = "Ingresa el cargo";
			
		   if ($_GET['area']=='selc'||$_GET['area']=='')	
		 	{
			$error['err_area'] = "Selecione area";
	     	}
	     
	     if ($validar->validarTodo($_GET['ci'], 1, 100))
			$error['err_ci'] = "Ingresa el ci ";
			
		 if ($validar->validarNumerosDecimales($_GET['ci'], 1, 100))
	     	{
	     	$error['err_ci'] = "Introduzca  numeros en  ci";
	        }
			
			if ($_GET['telefono']!='')
			{
				
				 if ($validar->validarNumerosDecimales($_GET['telefono'], 1, 100))
	     		{
	     		$error['err_telefono'] = "Introduzca  numeros en telefono";
	    		 }	
				
			}
			
			if ($_GET['celular']!='')
			{
				
				 if ($validar->validarNumerosDecimales($_GET['celular'], 1, 100))
	     		{
	     		$error['err_celular'] = "Introduzca  numeros en  celular";
	    		 }	
				
			}
	     
		if($error!="")
		{
			   $nombre = $_GET['nombre'];
			   $apellido = $_GET['apellido'];
			   $cargo = $_GET['cargo'];
			   $ci=$_GET['ci'];
		       $telefono=$_GET['telefono'];
		       $celular=$_GET['celular'];
		       
			   $resp_id=$_GET['resp_id'];
			   $miarea=$responsable->area($resp_id);
			   $area_id=$miarea['area_id'];
			   $area=$area->listar_areas($area_id); 
				   
			   $smarty->assign('nombre',$nombre);
			   $smarty->assign('apellido',$apellido);
			   $smarty->assign('cargo',$cargo);
			   $smarty->assign('ci',$ci);
			   $smarty->assign('telefono',$telefono);
			   $smarty->assign('celular',$celular);
			   $smarty->assign('area',$area);
			   $smarty->assign('miarea',$miarea);
			   $smarty->assign('resp_id',$resp_id);
			     		
				
			   $smarty->assign('errores',$error);
		 	   $smarty->display('modificar_responsable.html');
		}
		else
		{ 
		     $nombre = $_GET['nombre'];
		 	 $apellido = $_GET['apellido'];
		     $cargo = $_GET['cargo'];
		     $ci=$_GET['ci'];
		     $telefono=$_GET['telefono'];
		     $celular=$_GET['celular'];
		     $area=$_GET['area'];
		     $resp_id=$_GET['resp_id'];
		     
		     
		  //echo "enviamos la informacion a la función de adicionar responsable";
		  	$resultado=$responsable->actualizar_resp($nombre,$apellido,$cargo,$telefono,$celular,$area,$ci,$resp_id);
		  	
		  

		  
			  $error['err_confirm'] = "El registro se realizo correctamente";
			  $smarty->assign('errores',$error);
			  $smarty->display('lista_resp.html');	   
			
		 

		
		 }
 }
}
if ($funcion == "eliminar") 
	{ 
		$resp_id = $_GET['elegido'];
		$resultado=$responsable->eliminar_resp($resp_id);
		$smarty->display('lista_resp.html');	
		
	}		
 

}	
?>