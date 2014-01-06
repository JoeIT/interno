<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$cuero=new Cuero;
$validar = new Validador();



if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  
	  $lista=$cuero-> busqueda_cueros($cadena);
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
    $error['err_descripcion'] = "Ingresa la descripcion del cuero";
	
	$cuero_id=$cuero->verificar_cuero(trim($_POST['descripcion']));

    if($cuero_id!=-1)
		{$error['err_cuero'] = " El tipo cuero ya existe";}


  if (isset($error))
	{
	    $smarty->assign('title','Formulario de registro de un cuero');
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('errores',$error);
		
		if ($_GET['popup']== "false")  
		{ 
			$smarty->assign('cuero',true);
		}
		else
		{
			$smarty->assign('cuero',false);
		}
		
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_cuero.html');
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
  	     $resultado=$cuero->nuevo_cuero($descripcion);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			 $smarty->assign('errores',$error);
			 $consulta= $cuero->consulta_lista_cuero();
			 $smarty->assign('cuero',$consulta);
			 if ($_POST['popup']== "true")  
			 { 
				$smarty->assign('cuero',"false");
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_cuero.html');
			 }
			 else
			 {

				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_cuero.html');
			 }
		   }

	 }
    
  	 
}
else
{
	$smarty->assign('title','Formulario de registro de una cuero');
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('errores',$error);
	
		if ($_GET['popup']== "false")  
		{ 
			$smarty->assign('cuero',true);
		}
		else
		{
			$smarty->assign('cuero',false);
		}

	
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_cuero.html');

}	

}

	
?>