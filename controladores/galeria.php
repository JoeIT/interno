<?php
//*****************************************************
//GALERIA DE FOTOGRAFIAS
//*****************************************************
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

require("../clases/galeria.class.php");
//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

$objGaleria = new Galeria();

$categoria=$_GET['nombrearchivo'];
$directorios = $objGaleria->getDirectorios($categoria);
/*echo "<pre>";
print_r ($directorios);
echo "</pre>";*/
 
 if (!isset($_GET["mes"]))
 {
    $ultimoMes = count($directorios)-1;
    $mes = $directorios[$ultimoMes];
 }
 else
   $mes= $_GET["mes"];
 
 
 $fotos = $objGaleria->getPhotosDirectorio($categoria,$mes);

$smarty->assign("categoria",$categoria);
$smarty->assign("mes",$mes);
$smarty->assign("directorios",$directorios); 
$smarty->assign("item",$fotos);
 /*echo "<pre>";
 print_r($directorios);
 echo "</pre>";*/

 $smarty->display("../templates/galerias.html"); 
?>