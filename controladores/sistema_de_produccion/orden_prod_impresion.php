<?php
session_start();
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/despiece.php');
require_once('../includes/seguridad.php');
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$orden_id = $_GET['orden_id'];
//$despiece=new Despiece;
$orden=new OrdenProd;
$detalle_orden=new Detalle_orden;
$cabecera=$orden->obtener_cabecera_orden($orden_id);
$detalle=$detalle_orden->obtener_detalle_orden($orden_id);
/*for($contador=0;$contador<count($detalle);$contador++)
{
  /***********************VERIFICAR Y CORREGIR*******************/
   /* $despiece->eliminar_componentes(trim($detalle[$contador]['codigo']));
    $detalle_orden->registrar_despiece(trim($detalle[$contador]['codigo']),"0");
	$detalle_id_identico=$despiece->buscar_despiece_identico(trim($detalle[$contador]['codigo']));
	
	if($detalle_id_identico!=-1)
	{
		
	    $despiece->copiar_despiece_identico(trim($detalle[$contador]['codigo']),$detalle_id_identico);
		$detalle_orden->registrar_despiece(trim($detalle[$contador]['codigo']),"1");
		
	}
}*/

$smarty->assign('orden_id',$orden_id);
$smarty->assign('cabecera',$cabecera);
$smarty->assign('detalle',$detalle);	
$smarty->display('sistema_de_produccion/orden_de_produccion/orden_prop_imprimir.html');


?>
