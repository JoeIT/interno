<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/fuentes.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$fuente = new Fuente;
$validar = new Validador();

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  $lista=$fuente-> busqueda_fuentes($cadena);
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
    	   $error['err_descripcion'] = "Ingresa la descripcion del tipo de fuente";
		   
	$fuente_id=$fuente->verificar_fuente(trim($_POST['descripcion']));

    if($fuente_id!=-1)
		{$error['err_fuente'] = " La fuente ya existe";}

 	   
        if (isset($error))
  	 {
	     $smarty->assign('title','Formulario de registro de una fuente');
		if ($_POST['popup']== "true")  
		{ 
			$smarty->assign('fuente',false);
		}
		
		$smarty->assign('descripcion',$_POST['descripcion']);
		$smarty->assign('errores',$error);
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_fuente.html');
   	 }
   	 else
  	 {
     	 $descripcion = $_POST['descripcion'];
		 $resultado=$fuente->nuevo_fuente($descripcion);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			 $consulta= $fuente->consulta_lista_fuente();
			
			$smarty->assign('errores',$error);
			 if ($_POST['popup']== "false")  
			 { 
				 $smarty->assign('fuente',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_fuente.html');
			 }
			 else
			 { 
			 	$smarty->assign('fuente',"false"); 
			 	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_fuente.html');
			 }
			 
			
		   }
       	 }
}
else
{
	$smarty->assign('title','Formulario de registro de una fuente');
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('errores',$error);
	
	 if ($_GET['popup']=="false")  
	 { $smarty->assign('fuente',true); }
	 else
	 { $smarty->assign('fuente',false); }
	
	$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_fuente.html');
}	

}
?>
