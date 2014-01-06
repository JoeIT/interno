<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/sistema_de_produccion/despiece.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/entrega.php');
require('../../controladores/includes/fecha.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$asignacion=new Asignacion;
$entrega = new Entrega;
$validar = new Validador();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	$funcion = $_GET['funcion'];
	if($funcion=="buscar")
	{
		$cadena=$_GET['asignacion'];
		$resultado_asignacion = $entrega->buscar_asignacion($cadena);
		$bandera=$asignacion->existe_asignacion($cadena);
	
		if($bandera)
		{
			$bandera1=$asignacion->ver_entrega($cadena);
		
			if($bandera1==0)
			{
				$consulta= $asignacion->sacar_materiales($cadena);
				//$consulta2=$asignacion->sacar_materiales_corte($cadena);
				$cantidad = $resultado_asignacion["cantidad"];
				$consulta2 = $entrega->resumen_del_despiece($cadena, $cantidad);
				
				if ($consulta2!="")
				{
					$smarty->assign('materiales',$consulta);
					$smarty->assign('materiales2',$consulta2);
					$smarty->assign('asignacion',$cadena);
					$smarty->assign('imprimir',1);
					$smarty->assign('fecha',$fecha);
				}
				else
				{	
						$smarty->assign('asignacion',$cadena);
						$smarty->assign('aviso',"No tiene despiece");
				}
				
				$smarty->display('sistema_de_produccion/almacen/entrega_material.html');	
			}
			else
			{
				$consulta=$asignacion->sacar_materiales($cadena);
				//$consulta2=$asignacion->sacar_materiales_corte($cadena);
				$cantidad = $resultado_asignacion["cantidad"];
				$consulta2 = $entrega->resumen_del_despiece($cadena, $cantidad);
				
				$smarty->assign('materiales',$consulta);
				$smarty->assign('materiales2',$consulta2);
				$smarty->assign('imprimir',0);
				$smarty->assign('fecha',$fecha);
				$smarty->assign('aviso',"Ya se realizo la entrega");
				$smarty->display('sistema_de_produccion/almacen/entrega_material.html');
			}
		}
		else
		{
	
			$smarty->assign('aviso',"No existe la asignación");
			$smarty->assign('imprimir',0);
			$smarty->assign('fecha',$fecha);
			$smarty->display('sistema_de_produccion/almacen/entrega_material.html');
		}
		
	}
	else
	{
		if($funcion=="imprimir")
		{
			$cadena=$_GET['asignacion'];
			$resultado_asignacion = $entrega->buscar_asignacion($cadena);
			$consulta= $asignacion->sacar_materiales($cadena);
			// guardar el historial de almacen
			$descripcion = $asignacion->concatenar_materiales($cadena);
			$codigillo= $asignacion->cambiar_entrega($cadena,$descripcion);
			/////////////////////////////////////////////////////////////////
			$cantidad = $resultado_asignacion["cantidad"];
			$consulta2 = $entrega->resumen_del_despiece($cadena, $cantidad);
					
			$smarty->assign('materiales',$consulta);
			$smarty->assign('materiales2',$consulta2);
			
			$smarty->assign('fecha',$fecha);
			$smarty->display('sistema_de_produccion/almacen/hoja_impresion.html');
		}
		else
		{
			if($funcion=="cambio")
			{
				
				$cadena=$_GET['asignacion'];
				$resultado_asignacion = $entrega->buscar_asignacion($cadena);
				$descripcion = $asignacion->concatenar_materiales($cadena);
				$consulta= $asignacion->cambiar_entrega($cadena,$descripcion);
				$smarty->assign('aviso',"");
				$smarty->assign('fecha',$fecha);
				$smarty->display('sistema_de_produccion/almacen/entrega_material.html');
			}
			else
			{
				$smarty->assign('material',"");
				$smarty->assign('fecha',$fecha);
				$smarty->display('sistema_de_produccion/almacen/entrega_material.html');
			}
		}
	}

}
?>