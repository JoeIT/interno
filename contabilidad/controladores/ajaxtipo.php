<?php
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");


include_once('../clases/moneda.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$moneda= new Moneda();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';


$fecha=$_GET["fecha"];
$moneda=$moneda->seleccionar_tipo($fecha);


/*echo "<pre>";
print_r($moneda);
echo "</pre>";*/

$dolar=$moneda[1]['tipo_cambio'];
$ufv=$moneda[0]['tipo_cambio'];

$smarty->assign('dolar', $dolar);
$smarty->assign('ufv', $ufv);

$smarty->display('tipo_cambio.html');
?>