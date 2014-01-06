<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/colores.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');
$color=new Color;
$validar = new Validador();

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena= utf8_decode(trim($valor));
	  echo "<ul>";
	  if($_GET["busqueda_ajax"]=='colores')
	    $lista=$color-> busqueda_colores($cadena);
	  else
	    $lista=$color-> busqueda_colecciones($cadena);
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
    $error['err_descripcion'] = "Ingresa la descripcion de la color";
	if ($validar->validarTodo($_POST['coleccion'], 1, 100))
    $error['err_coleccion'] = "Ingresa la coleccion a la que pertenece el color";
	
	$color_id=$color->verificar_color(trim($_POST['descripcion']));

    if($color_id!=-1)
		{$error['err_color'] = " El color ya existe";}


  if (isset($error))
	{
  	 	$smarty->assign('title','Formulario de registro de una color');
		if ($_POST['popup']== "true")  
		{ 
			$smarty->assign('color',false);
		}
		else
		{
			$smarty->assign('color',true);
		}
		
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('coleccion',$_POST['coleccion']);
		$smarty->assign('errores',$error);
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_color.html');
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
	     $coleccion = $_POST['coleccion'];
		 $resultado=$color->nuevo_color($descripcion,$coleccion);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			$smarty->assign('errores',$error);
			$consulta= $color->consulta_lista_colores();
			
			if ($_POST['popup']== "true")  
			 { 
				$smarty->assign('color',"false");
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_color.html');
			 }
			 else
			 {
			 	$smarty->assign('color',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_color.html');
			 }

		   }

	 }
    
  	 
}
else
{
	$smarty->assign('title','Formulario de registro de un color');
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('coleccion',$coleccion);
	$smarty->assign('errores',$error);
	
	 if ($_GET['popup']== "false")  
	 { 
		$smarty->assign('color',true); 
	 }
	 else
	 {
	 	$smarty->assign('color',false); 
	 }
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_color.html');

}	

}

?>