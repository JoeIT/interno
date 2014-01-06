<?php
session_start(); 
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
//$_SESSION['usuario_id']="5241445";
$smarty = new Smarty();
$title = ".:: Libro de Compras y Ventas ::.";
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'templates_c';
$smarty->assign('title', $title);
$smarty->display('lcv.html');
?>