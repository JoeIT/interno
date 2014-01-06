<?php

/**
 * @author Erika Ballesteros
 * @copyright 2011
 */

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "sucursal.php");
require_once(CLAS . "validador.php");

$url_relativa = "/sistema/macaws/sucursal.php";
$url_relativa2 = "/sistema/macaws/lvc.php";

$val = new Validador();
$error = null;

if(isset($_SESSION['usuario_id']))
{
	$_SESSION['req'] = $_POST;
	
	if (!isset($_POST['nombre'])) {
		$error['nombre'] = "Introducir nombre de la sucursal.";
	}
    if (!isset($_POST['direccion'])) {
		$error['direccion'] = "Introducir direccion.";
	}

	if ((isset($error)))
	{
		$_SESSION['errores'] = $error;
	}
	else
	{
		$link = new Coneccion();
		$link = $link->conectiondb();
		$sucursal = new Sucursal($link);
      if($_POST['direccion']!=""  && $_POST['nombre']!="" )
      {
        $sucursal->ingresar_sucursal($_POST['contribuyentes'],$_POST['direccion'],$_POST['telefono'],$_POST['casilla'],$_POST['fax'],$_POST['nombre']);
      }
	}
	header("Location: " . $url_relativa);
}
else
header("Location: " . $url_relativa2);


?>