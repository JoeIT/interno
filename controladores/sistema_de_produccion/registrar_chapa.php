<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../../clases/sistema_de_produccion/chapa.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$chapa=new Chapa;
$validar = new Validador();

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  $lista=$chapa->busqueda_sellos($cadena);
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

if (!empty($_POST))
{
	if ($validar->validarTodo($_POST['descripcion'], 1, 100))
	    $error['err_descripcion'] = "Ingresa la descripcion de la chapa";
		
	$chapa_id=$chapa->verificar_chapa(trim($_POST['descripcion']));

    if($chapa_id!=-1)
		{$error['err_chapa'] = " La chapa ya existe";}

  if (isset($error))
	{
	 	$smarty->assign('title','Formulario de registro de una chapa');
		if ($_POST['popup']== "true")  
		{ 
			$smarty->assign('chapa',false);
		}
		
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('errores',$error);
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_chapa.html');

    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $resultado=$chapa->nuevo_chapa($descripcion);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			 		 
			 
			$consulta= $chapa->consulta_lista_chapa();
			$smarty->assign('errores',$error);
			if ($_POST['popup']== "true")  
			 { 
				$smarty->assign('chapa',"false");
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_chapa.html');
			 }
			 else
			 {
				$smarty->assign('chapa',$consulta); 
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_chapa.html');
			 }
			
	
		   }

	 }
    
  	 
}
else
{
	
	
	$smarty->assign('title','Formulario de registro de una chapa');
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('errores',$errores);
	
	if ($_GET['popup']== "false")  
	{ 
		$smarty->assign('chapa',true);
	}
	else
	{
		$smarty->assign('chapa',false);
	}	
	
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_chapa.html');

}

}	
?>