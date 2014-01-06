<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../../clases/sistema_de_produccion/etiquetas.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$validar = new Validador();
$etiqueta=new Etiqueta;

session_start();

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  $lista=$etiqueta-> busqueda_etiquetas($cadena);
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
    $error['err_descripcion'] = "Ingresa la descripcion de la etiqueta";
	
	$etiqueta_id=$etiqueta->verificar_etiqueta(trim($_POST['descripcion']));

    if($etiqueta_id!=-1)
		{$error['err_etiqueta'] = " La etiqueta ya existe";}


  if (isset($error))
	{
	   $smarty->assign('title','Formulario de registro de una etiqueta');
		if ($_POST['popup']== "true")  
		{ 
			$smarty->assign('etiqueta',false);
		}
		
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('errores',$error);
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_etiqueta.html');
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
	     $resultado=$etiqueta->nuevo_etiqueta($descripcion);

			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			  $smarty->assign('errores',$error);
			$consulta= $etiqueta->consulta_lista_etiquetas();
			
			
			 if ($_POST['popup']== "true")  
			 { 
				$smarty->assign('etiqueta',"false");
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_etiqueta.html');
			 }
			 else
			 {
			 	$smarty->assign('etiqueta',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_etiqueta.html');
			 }
			
		   }

	 }
    
  	 
}
else
{
	
	$smarty->assign('title','Formulario de registro de una etiqueta');
	
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('errores',$error);
	if ($_GET['popup']== "false")
	{
		$smarty->assign('etiqueta',true);	
	}
	else
	{
		$smarty->assign('etiqueta',false);
	}
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_etiqueta.html');
}	

}
?>