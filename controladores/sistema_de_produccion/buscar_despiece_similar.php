<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/despiece.php');
require_once('../includes/seguridad.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$orden=new OrdenProd;
$detalle_orden=new Detalle_orden;

$funcion=$_GET['funcion'];
if($funcion=="volver")
{
    $orden_id = $_GET['orden_id'];
	$detalle_id=$_GET['detalle_id'];
	$detalle=$detalle_orden->obtener_detalle_orden_despieces($orden_id);
	$cabecera=$orden->obtener_cabecera_orden($orden_id);
	$smarty->assign('orden_id', $orden_id);
	$smarty->assign('detalle', $detalle);
    $smarty->assign('cabecera', $cabecera);
	$smarty->assign('errores',$errores);
	$smarty->assign('lista_materiales',$lista_materiales);
	//$smarty->assign('material',$material);
	$smarty->assign('nombres', $_SESSION["nombres"]);
	$smarty->assign('apellidos', $_SESSION["apellidos"]);
	$smarty->display('sistema_de_produccion/despiece/ver_detalle_orden_despiece.html');
}
else
{
   if($funcion=="similar")
   {
       $despiece=new Despiece;
       $orden_id = $_GET['orden_id'];
	   $detalle_id = $_GET['detalle_id'];
  	   $detalle_elegido = $_GET['elegido'];
	   $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
	   if($detalle_elegido!="ninguno")
	   {	   $despiece->copiar_despiece_identico($detalle_id,$detalle_elegido);
	   		   $detalle_orden->registrar_despiece($detalle_id,"2");
	   }
	   header("Location: ver_detalle_orden_despiece.php?funcion=despiece&orden_id=".$orden_id."&elegido=".$detalle_id);   
   }
   else
   {
    if($funcion=="despiece")
   	{
	   $despiece=new Despiece;
       $orden_id = $_GET['orden_id'];
	   $detalle_id = $_GET['elegido'];
	   $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
	   $lista_materiales=$despiece->obtener_lista_materiales_despiece($detalle_id);
       $smarty->assign('orden_id', $orden_id);
	   $smarty->assign('detalle_id', $detalle_id);
	   $smarty->assign('cabecera', $cabecera);
	   $smarty->assign('lista_materiales',$lista_materiales);
		//$smarty->assign('material',$material);
	   $smarty->display('sistema_de_produccion/despiece/modificar_despiece.html');
	   
	
	}
   	else
   	{
   		  if($funcion=="buscar")
		  {
		     $despiece=new Despiece;
		 	 $orden_id = $_GET['orden_id'];
	      	 $detalle_id = $_GET['detalle_id'];
		   	 $detalle_busqueda = trim($_POST['detalle_busqueda']);
			 if($detalle_busqueda!="")
			 {
			 
	   	     $similares=$despiece->buscar_despieces_varios($detalle_id,$detalle_busqueda);
			if($similares==null)
				     $smarty->assign('mensaje',"La b�squeda no dio ningun resultado");
	   	     $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
		     $smarty->assign('orden_id', $orden_id);
		     $smarty->assign('detalle_id', $detalle_id);
	   	     $smarty->assign('similares', $similares);
	  	     $smarty->assign('cabecera', $cabecera);
			 $smarty->assign('detalle_busqueda', $detalle_busqueda);
  	   	     $smarty->display('sistema_de_produccion/despiece/buscar_despiece_similar.html');
			 }
			 else
			 {
			      $despiece=new Despiece;
		 		  $orden_id = $_GET['orden_id'];
	    		  $detalle_id = $_GET['detalle_id'];
	   			  $similares=$despiece->buscar_despieces_similares($detalle_id);
	   	  		  $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
		 		  $smarty->assign('orden_id', $orden_id);
				   $smarty->assign('error', "Ingrese un parametro");
				  $smarty->assign('detalle_id', $detalle_id);
	   			  $smarty->assign('similares', $similares);
	  	 		  $smarty->assign('cabecera', $cabecera);
  	   	  		  $smarty->display('sistema_de_produccion/despiece/buscar_despiece_similar.html');
			 
			 }
		  }
		  else
		  {
		   $despiece=new Despiece;
		   $orden_id = $_GET['orden_id'];
	       $detalle_id = $_GET['detalle_id'];
	   	   $similares=$despiece->buscar_despieces_similares($detalle_id);
	   	   $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
		   $smarty->assign('orden_id', $orden_id);
		   $smarty->assign('detalle_id', $detalle_id);
	   	   $smarty->assign('similares', $similares);
	  	   $smarty->assign('cabecera', $cabecera);
  	   	   $smarty->display('sistema_de_produccion/despiece/buscar_despiece_similar.html');
	    }
	
	}
  }
}
?>
