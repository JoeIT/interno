<?php
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/moneda.php');
include_once('../clases/includes/validador.php');
include_once('../clases/tipocambio.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$moneda= new Moneda();
$tcambio= new TipoCambio();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	$mone=$moneda->listar_moneda();
	$fecha_inicio=date("Y/m/d");
	$smarty->assign('fecha_inicio', $fecha_inicio);
	$smarty->assign('moneda', $mone);
	
	if($_GET['funcion']== "validar")
	{    $error="";
		 $validar = new Validador();
		 if ($validar->validarTodo($_GET['valor'], 1, 100))
			 $error['err_valor'] = "Ingresa el valor";
			 
		 if ($_GET['tipo']=='selc')	
		 {
			$error['err_tipo'] = "Selecione tipo";
	     }
	     if ($validar->validarNumerosDecimales($_GET['tipo_cambio'], 1, 100))
	     {
	     	$error['err_valor'] = "Introduzca  numeros en  tipo cambio";
	     }
	  
			 		
		if($error!="")
		{
			   $tipo_cambio=$_GET['valor'];
		 	   $tipo_id=$_GET['tipo'];
		 	   $fecha_ini= trim($_GET["fecha_inicio"]);	
			   $fecha_fin='0000-00-00';
			    
			   $smarty->assign('valor',$valor);
			   $smarty->assign('tipo',$tipo);
			
			 
		  		if ($_GET['popup'] == "true") 
			  	$smarty->assign('menu',"false");
				
			  	$smarty->assign('errores',$error);
		 		$smarty->display('actualizar_tipo.html');
		}
		else
		{
		      $tipo_cambio=$_GET['valor'];
		 	  $tipo_id=$_GET['tipo'];
		 	  $id1=$moneda->seleccionar_id($tipo_id);
		 	  $id=$id1[0];
		 	  
			  $fecha_ini= trim($_GET["fecha_inicio"]);	
			  $fecha_fin='0000-00-00';
		   	  //enviamos la informacion a la función de adicionar responsable
		  	  $resultado=$tcambio->actualizar_tipocambio($tipo_cambio,$fecha_ini,$fecha_fin,$id);
		      $anteriortipo=$tcambio->cambio_fecha($tipo_id,$fecha_ini);
		  
		   if($resultado!=0)
		   {
			  $error['err_confirm'] = "El registro se realizo correctamente";
			 //$_SESSION['error']= $error;
			 	if ($_GET['popup'] == "true")  
				 { 
			  	 $smarty->assign('menu',"true");
				
				 }
			 	 $smarty->assign('cerrar', 'yes');
				 $smarty->assign('errores',$error);
				 $smarty->display('actualizar_tipo.html');
			
		   }
		   
	
		 }
	}
	else
	{ 
	   
		if($_GET['popup']=="true")
		   $smarty->assign('menu',"false");
		else
			  $smarty->assign('menu',"true");
		
		$smarty->display('actualizar_tipo.html');
	}

}
?>