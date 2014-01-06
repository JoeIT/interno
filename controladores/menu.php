<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/permisos.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/contenido/';
$smarty->compile_dir = '../templates_c';

$permiso=new Permiso;
            
$lista= $permiso->menu();
$lista2= $permiso->menu_agrupado();	
$smarty->assign('menu', $lista);
$smarty->assign('menu2', $lista2);
//$smarty->assign('grupo', $_SESSION["grupo_id"]);
$smarty->display('menu.html');
?>