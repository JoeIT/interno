<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');
$clip=new Clip;
$validar = new Validador();

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  $lista=$clip-> busqueda_clips($cadena);
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
    $error['err_descripcion'] = "Ingresa la descripcion del clip";
	
	$clip_id=$clip->verificar_clip(trim($_POST['descripcion']));
    if($clip_id!=-1)
		{$error['err_clip'] = " El clip ya existe";}

  if (isset($error))
	{
	     $smarty->assign('title','Formulario de registro de un clip');
		if ($_POST['popup']== "true")  
		{ 
			$smarty->assign('clip',false);
		}
		
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('errores',$error);
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_clip.html');
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $resultado=$clip->nuevo_clip($descripcion);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			 
			 $smarty->assign('errores',$error);
			 
			$consulta= $clip->consulta_lista_clip();
			
			
			if ($_POST['popup']== "true")  
			 { 
				$smarty->assign('clip',"false");
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_clip.html');
			 }
			 else
			 {
			 	$smarty->assign('clip',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_clip.html');
			 }
			
		   }

	 }
    
  	 
}
else
{
	$smarty->assign('title','Formulario de registro de un clip');
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('errores',$errores);
	
	 if ($_GET['popup']== "false")  
	 { 
		$smarty->assign('clip',true);
	 }
	 else
	 {
	 	$smarty->assign('clip',false);
	 }
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_clip.html');
}	
}
?>