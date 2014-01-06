<?php
session_start();
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/despiece.php');

$orden_id = $_GET['orden_id'];
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

//$orden=new OrdenProd;
//$detalle_orden=new Detalle_orden;


$orden_id = $_GET['orden_id'];
$despiece=new Despiece;
$orden=new OrdenProd;
$detalle_orden=new Detalle_orden;
$cabecera=$orden->obtener_cabecera_orden($orden_id);
$detalle=$detalle_orden->obtener_detalle_orden_despieces($orden_id);
for($contador=0;$contador<count($detalle);$contador++)
{
    //if($detalle[$contador]['Despiece']!=1 && $detalle[$contador]['Despiece']!=2)
	if($detalle[$contador]['Despiece']!=1)
	{  /***********************VERIFICAR Y CORREGIR*******************/
	    $despiece->eliminar_componentes(trim($detalle[$contador]['codigo']));
    	$detalle_orden->registrar_despiece(trim($detalle[$contador]['codigo']),"0");
		$detalle_id_identico=$despiece->buscar_despiece_identico(trim($detalle[$contador]['codigo']));
	
		if($detalle_id_identico!=-1)
		{
		
		    $despiece->copiar_despiece_identico(trim($detalle[$contador]['codigo']),$detalle_id_identico);
			$detalle_orden->registrar_despiece(trim($detalle[$contador]['codigo']),"2");
		
		}
	}
}

$cabecera=$orden->obtener_cabecera_orden($orden_id);
$detalle=$detalle_orden->obtener_detalle_orden($orden_id);

$smarty->assign('orden_id',$orden_id);
$smarty->assign('cabecera',$cabecera);
$smarty->assign('detalle',$detalle);	
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$smarty->display('sistema_de_produccion/orden_de_produccion/confirmar_orden.html');
?>
