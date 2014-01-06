<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Compra.php");
require_once(CLAS . "validador.php");

$url_relativa = "/sistema/macaws/compra.php";
$url_relativa2 = "/sistema/macaws/lvc.php";
$val = new Validador();
$error = null;

if(isset($_SESSION['usuario_id']))
{
	$_SESSION['req'] = $_POST;
	//if($val->validarNumeros($_POST['diaperiodo'],1,2) )
	if(empty($_POST['diaperiodo']))
	$error['diaperiodo'] = "Seleccione un dia valido.";

	if($val->validarNumeros($_POST['nit'],1,20))
	$error['nit'] = "NIT invalido.";

	if ($val->validarTodo($_POST['rsocial'], 1, 255))
	$error['rsocial'] = "Raz&oacute;n social vacio.";
	
	if($val->validarTextoNumeros2($_POST['alfanumerico'],0,25))
	$error['alfanumerico'] = "N&deg; Alfanumerico invalido.";

	if($val->validarTextoNumeros2($_POST['factura'],1,25))
	$error['factura'] = "Factura invalida.";

	if($val->validarNumeros($_POST['autorizacion'],1,20))
	$error['autorizacion'] = "Autorizaci&oacute;n invalida.";


	if($val->validarTextoNumeros($_POST['control'],0,20))
	$error['control'] = "C&oacute;digo de control invalido.";

	if($val->validarNumerosDecimales($_POST['totalfact'],1,10))
	$error['totalfact'] = "Cantidad Factura invalida.";

	if($val->validarNumerosDecimales($_POST['ice'],0,10))
	$error['ice'] = "Cantindad ICE invalida.";

	if($val->validarNumerosDecimales($_POST['exento'],0,10))
	$error['exento'] = "Cantidad Exentos invalida.";

	if ((isset($error)))
	{
		$_SESSION['errores'] = $error;
	}
	else
	{
		$link = new Coneccion();
		$link = $link->conectiondb();
		$compra = new Compra($link);
		if(!$compra->validarFactura($_POST['factura'],$_POST['autorizacion'],$_POST['nit']))
		{
			$fecha ="".$_POST['gestion']."-".$_POST['periodo']."-".$_POST['diaperiodo'];
			$compra->insert_Compra($fecha,$_POST['nit'],$_POST['rsocial'],$_POST['factura'],$_POST['autorizacion'],$_POST['control'],$_POST['totalfact'],$_POST['ice'],$_POST['exento'],$_POST['totalexento'],$_POST['iva'],$_POST['cod_pg'],$_POST['alfanumerico'],$_SESSION['usuario_id']);
			$_SESSION['macawsregcompra']=$_POST['factura'];
			$vect['cod_pg'] =$_POST['cod_pg'];
			$vect['periodo'] =$_POST['periodo'];
			$vect['gestion'] =$_POST['gestion'];
			unset($_SESSION['req']);			
			$_SESSION['req'] = $vect;
		}
		else
		{
			$error['facturadoble']="Factura duplicada.";
			$_SESSION['errores'] = $error;
		}
	}

	
	header("Location: " . $url_relativa);
}
else
header("Location: " . $url_relativa2);
?>