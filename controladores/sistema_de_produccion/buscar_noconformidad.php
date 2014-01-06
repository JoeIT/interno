<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

require_once('../../clases/noconformidad_apertura.php');
include_once('../../clases/validador.php');
require("../includes/fecha.php");

$validar = new Validador();
$noconformidad = new NoConformidad_apertura();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	$fecha_inicio = date("Y-m-d"); 
	$fecha_fin = date("Y-m-d"); 
	
	$smarty->assign('fecha_inicio',$fecha_inicio);
	$smarty->assign('fecha_fin',$fecha_fin);
   
	if (!empty($_POST)){
          if ($funcion==$_POST['buscar']){
          $fecha_hoy=date("Y-m-d");
          $fecha_inicio = trim($_POST['fecha_inicio']);
		  $fecha_fin = trim($_POST['fecha_fin']);
		  $estado = trim($_POST['estado']);
		  $accion = trim($_POST['accion']);
          $tiporeporte=trim($_POST['tipreporte']);
          $tipo=trim($_POST['tipo']);
          $cierre=trim($_POST['cierre']);
            
            if ($fecha_fin > $fecha_hoy)
            {
				$error['fecha'] = "La fecha de finalizaci&oacute;n no debe ser mayor de fecha actual.";
			    $smarty->assign('error',$error);
             } 
            if ($fecha_inicio > $fecha_fin)
            {
				$error['fecha'] = "La fecha de inicio no debe ser mayor de fecha fin.";
			    $smarty->assign('error',$error);
		     }
			if (isset($error))
				$smarty->assign('errores', $error);
            else 
            {
				$resultado = $noconformidad->buscar_noconformidad($fecha_inicio,$fecha_fin,$estado,$accion,$tiporeporte,$tipo,$cierre);
				if ($resultado != null)
                {
					$smarty->assign('resultado', $resultado);
                    $smarty->assign('estado', $estado);
				} 
                else 
					$smarty->assign('mensaje', "No se encontr&oacute; registro RG-40 con esas caracter&iacute;sticas");
			}
            $smarty->display('sistema_de_produccion/normas/NoConformidad/buscar_noconformidad.html');
            }
	     }
        else
        {
            $smarty->display('sistema_de_produccion/normas/NoConformidad/buscar_noconformidad.html');
        }
}
?>