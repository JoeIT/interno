<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

include_once('../../clases/noconformidad_apertura.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

//Variables para cabecera
$smarty->assign('fecha', $fecha);


$validar = new Validador();
$noconformidad = new NoConformidad_apertura();
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} 
else
{
	$funcion = $_GET['funcion'];
      if($funcion=="reviso")
      {
        $id=$_GET['elegido'];
        $impre=$noconformidad->modificar_estado_rg40($id,5);
        $aperturada=$noconformidad->listar_reg_aperturadas(4);
        $cerradas=$noconformidad->listar_reg_cerradas(5);
        $smarty->assign('aperturada',$aperturada);
		$smarty->assign('revisiones',$cerradas);
		$smarty->display('sistema_de_produccion/normas/listar_rg_40_pendientes.html');	
      }
     if($funcion=="ver")
	 {
        	$id = $_GET["elegido"];
                $doc=$noconformidad->imprimir_apertura($id);
                $doc=$noconformidad->imprimir_accion($id);
                $doc=$noconformidad->imprimir_revision($id);
                $doc=$noconformidad->imprimir_cierre($id);
            $smarty->assign('doc',$doc);
		    $smarty->display('sistema_de_produccion/normas/imprimir_documentoEntero.html');
	  }
	 if($funcion=="detalle")
	 {
		$id=$_GET['elegido'];
		$doc=$noconformidad->imprimir_cierre($id);
		$impre=$noconformidad->modificar_print_cierre($id);
		$smarty->assign('doc',$doc);
		$smarty->display('sistema_de_produccion/normas/imprimir_cierre.html');						
	  }
	  else
	  {
        if($funcion=="buscar")
		 {
			$cadena = trim($_POST['doc']);
			$opcion = trim($_POST['tipo']);
			$_SESSION['tabu'] = $_POST['tabu'];
			if ($validar->validarTodo($cadena, 1, 100))
            {
				if ($opcion == "num_doc")
						$error['err_nombre'] = "Ingresar numero de registro RG-40";
				else 
						$error['err_nombre'] = "Ingresar el tipo de registro RG-40";
			}
			$smarty->assign('doc',$cadena);
          	$consulta = $noconformidad->consultar_busqueda_cerradas($cadena,$opcion);
					if ($consulta != null)
                    {
						$smarty->assign('doc',$consulta);
						$smarty->assign('tabu',$_SESSION['tabu']);
                        $smarty->assign('cerradas', $consulta);
                        $smarty->display('sistema_de_produccion/normas/listar_cerradas.html');
					} 
                    else 
                    {
						if($opcion == "num_doc")
                        {$smarty->assign('mensaje',"No se encontr&oacute; el numero RG-40");}
						else
                        {$smarty->assign('mensaje',"No se encontr&oacute el tipo de registro RG-40");}
						  
                  $smarty->assign('tabu',$_SESSION['tabu']);
                  
               $smarty->display('sistema_de_produccion/normas/listar_cerradas.html');
				   }
		}
		else
	   {
		   if($funcion=='cambio_tab')
		   {
			  if ($_GET['tabu'])
              {
			   $_SESSION['tabu'] = $_GET['tabu'];
		       $smarty->assign('tabu',$_GET['tabu']);
               $cerradas=$noconformidad->listar_reg_cerradas();
               $smarty->assign('cerradas', $cerradas);
               $smarty->display('sistema_de_produccion/normas/listar_cerradas.html');
				}
		   }
       }
	 }
}
?>