<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$nombrearchivo=$_GET['nombrearchivo'];
//Pasando variable de smarty a html
		$_SESSION['nombrearchivo'] =$nombrearchivo ;
		$smarty->display('galeria.html');

?>