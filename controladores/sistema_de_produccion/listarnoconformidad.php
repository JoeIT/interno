<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

require_once('../../clases/noconformidad_apertura.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$validar = new Validador();
$noconformidad_apertura= new Noconformidad_apertura();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else 
{
    
	switch ($_GET['opcion'])
	{
	case 'todos':{		  
          $aperturadas=$noconformidad_apertura->reporte_RG40(1);//estado=1 es de aperturadas
          $analisis=$noconformidad_apertura->reporte_RG40(2);//estado=2 es de analisis y accion
          $revisiones=$noconformidad_apertura->reporte_RG40(3);//estado=3 es de revision
          $cierres=$noconformidad_apertura->reporte_RG40(0);//estado=0 es de cerradas
          
		  $smarty->assign('aperturadas',$aperturadas);
          $smarty->assign('analisis',$analisis);
          $smarty->assign('revisiones',$revisiones);
          $smarty->assign('cierres',$cierres);
          
          $totalesA=$noconformidad_apertura->totalAperturas(1);
          $totalesAN=$noconformidad_apertura->totalAperturas(2);
          $totalesR=$noconformidad_apertura->totalAperturas(3);
          $totalesC=$noconformidad_apertura->totalAperturas(0);
          $smarty->assign('totalesA', $totalesA);
          $smarty->assign('totalesAN', $totalesAN);
          $smarty->assign('totalesR', $totalesR);
          $smarty->assign('totalesC', $totalesC);
          
          $smarty->display('sistema_de_produccion/normas/documentos_RG40.html');
          }break;
           //para ver el documento solo la parte q requiere ver
case 'ver':{	
			$id = $_GET["elegido"];
            $estado = $_GET["estado"];
            if($estado == 1)//apertura
                $doc=$noconformidad_apertura->imprimir_apertura($id);
            if($estado == 2)//analisis y accion
                $doc=$noconformidad_apertura->imprimir_accion($id);
            if($estado == 3)//revision
                $doc=$noconformidad_apertura->imprimir_revision($id);
            if($estado == 0)//cierre
                $doc=$noconformidad_apertura->imprimir_cierre($id);
            $smarty->assign('doc',$doc);
		    $smarty->display('sistema_de_produccion/normas/imprimir_documentoEntero.html');
	   }break;
	}
}	
?>