<?php
define('CLAS',"../class/");
/*define('SMARTY_DIR',"../smarty/");*/

	require('include2.php');

require_once(CLAS."databasextramacaws.php");
require_once(CLAS."orderstatusmacaws.php");
require_once(CLAS."conexionSistemaInterno.php");
require_once(CLAS."conexionLocal.php");
require_once(CLAS."shipment.php");

/*require_once(SMARTY_DIR.'SmartyML.php');

$smarty = new smartyML();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c/';*/

/*$link=new DBMacaws();
$link=$link->conectiondb();
$ordenmcw=new OrdenStatus($link);
$despachos=$ordenmcw->showLatestDespachos(20);
*/

$linkSI=new DB_SI_Macaws();
$linkSI=$linkSI->conectionSI();
$ordenmcw_SI=new OrdenStatus($linkSI);
$despachos=$ordenmcw_SI->showLatestDespachos_SI(20);

if(isset($_POST['send']))
{
	$link2=new Coneccion();
	$link2=$link2->conectiondb();
	$dispatch=new Shipment($link2);

	if($_POST['iddespacho']!=0)
	{
		if(!$dispatch->existsMigration($_POST['iddespacho']))
		{
			$nrosales=$ordenmcw_SI->getOrdersFromDespacho_SI($_POST['iddespacho']);
			//$nrosales=$ordenmcw->getOrdersFromDespacho($_POST['iddespacho']);
			$fecha=date("m-d-Y");
			$dispatch->addDispatch($nrosales,$_POST['iddespacho'],"Despacho Especiales : ".$fecha,$_POST['obs'],0);
			$smarty->assign('mensaje',"Despacho Migrado EXITOSAMENTE");
		}
		else
			$smarty->assign('mensaje_err',"No puede Migrar el mismo Despacho");
	}
	else
		$smarty->assign('mensaje_err',"Error de Selección");

}
$smarty->assign_by_ref('despachos',$despachos);
$smarty->display('show_despachos.html');
?>