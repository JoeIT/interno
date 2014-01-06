<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/lugargrabado.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$lugar=new Lugar;
$validar = new Validador();

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  $lista=$lugar->busqueda_lugar_grabados($cadena);
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
    $error['err_descripcion'] = "Ingresa la descripcion del lugar de grabado";
	
	$lugar_id=$lugar->verificar_lugar(trim($_POST['descripcion']));

    if($lugar_id!=-1)
		{$error['err_lugar'] = " El lugar de grabado ya existe";}


  if (isset($error))
	{
	 	$smarty->assign('title','Formulario de registro de un lugar de grabado');
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('errores',$error);
		
		 if ($_GET['popup']==false)  
		 { 
			$smarty->assign('lugar',false);
		 }
		 else
		 {
			$smarty->assign('lugar',true);
		 }
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_lugar.html');
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $resultado=$lugar->nuevo_lugar($descripcion);
   	     if($resultado)
		   {
 			$error['err_confirm'] = "El registro se realizo correctamente";
			$smarty->assign('errores',$error);
			
			$consulta= $lugar->consulta_lista_lugar();
			$smarty->assign('lugar',$consulta);
			
			 if ($_POST['popup']==true)  
			 { 
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_lugar.html');
			 }
			 else
			 {
			 	$smarty->assign('lugar',false);
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_lugar.html');
			 }
			 
			
		   }

	 }
    
  	 
}
else
{
	$smarty->assign('title','Formulario de registro de un lugar de grabado');
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('errores',$error);
	 if ($_GET['popup']==false)  
	 { 
		$smarty->assign('lugar',false);
	 }
	 else
	 {
		$smarty->assign('lugar',true);
	 }
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_lugar.html');
}	

}
?>