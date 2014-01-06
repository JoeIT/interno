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
} else
	 {
	$funcion = $_GET['funcion'];
	 if($funcion=="detalle")
	 {
		$id=$_GET['elegido'];
		$doc=$noconformidad->imprimir_accion($id);
		$impre=$noconformidad->modificar_print_analisis($id);
		$smarty->assign('doc',$doc);	
		$smarty->display('sistema_de_produccion/normas/imprimir_analisis_accion.html');//imprimir_accion.html');					
	  }
	  else
	  {
         if ($funcion == "revision") 
         {
			$registro = $_GET["elegido"];
  		    $listanalisis=$noconformidad->recuperar_analisis($registro);
  		    $smarty->assign('analisis', $listanalisis);
		    $smarty->display('sistema_de_produccion/normas/NoConformidad/registrar_revision.html');
	    }
        if($funcion=="modificar")//cuando se carga el formulario de edicion
  	       {
            $registro = $_GET["elegido"];
		    $consulta=$noconformidad->consulta_analisis($registro);
            $smarty->assign('nro_registro',$consulta["nro_registro"]);
            $smarty->assign('disposicion',$consulta["disposicion"]);
            $smarty->assign('analisis_causa',$consulta["analisis_causa"]);
            $smarty->assign('accion_inmediata',$consulta["accion_inmediata"]);
            $smarty->assign('accion_otras',$consulta["accion_otras"]);
            $smarty->assign('fec_analisis',$consulta["fec_analisis"]);
	        $smarty->display('sistema_de_produccion/normas/NoConformidad/modificar_analisis.html');
            }
			if($funcion=="modificar_apertura")
			{
             if($_POST['analisis_causa']!='' && $_POST['accion_inmediata']!='')
            {
               $nro_registro=$_GET['elegido'];
               $noconformidad->modificar_analisis($nro_registro,$_POST['disposicion'],$_POST['analisis_causa'],$_POST['accion_inmediata'],$_POST['accion_otras']);
	           $smarty->assign('err_firstname', "Los datos fueron actualizados correctamente");
               $listanalisis=$noconformidad->listar_reg_analisis();
               $smarty->assign('analisis', $listanalisis);
               $smarty->display('sistema_de_produccion/normas/listar_analisis.html');
            }
            else
            {
                $registro = $_GET["elegido"];
		    $consulta=$noconformidad->consulta_analisis($registro);
            $smarty->assign('nro_registro',$consulta["nro_registro"]);
            $smarty->assign('disposicion',$consulta["disposicion"]);
            $smarty->assign('analisis_causa',$consulta["analisis_causa"]);
            $smarty->assign('accion_inmediata',$consulta["accion_inmediata"]);
            $smarty->assign('accion_otras',$consulta["accion_otras"]);
            $smarty->assign('fec_analisis',$consulta["fec_analisis"]);
	        $smarty->display('sistema_de_produccion/normas/NoConformidad/modificar_analisis.html');
            }
}
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
          	$consulta = $noconformidad->consultar_busqueda_analisis($cadena,$opcion);
					if ($consulta != null)
                    {
						$smarty->assign('doc',$consulta);
						$smarty->assign('tabu',$_SESSION['tabu']);
                        $smarty->assign('analisis', $consulta);
                        $smarty->display('sistema_de_produccion/normas/listar_analisis.html');
					} 
                    else 
                    {
						if($opcion == "num_doc")
                        {$smarty->assign('mensaje',"No se encontr&oacute; el numero RG-40");}
						else
                        {$smarty->assign('mensaje',"No se encontr&oacute el tipo de registro RG-40");}
						  
                  $smarty->assign('tabu',$_SESSION['tabu']);
                  
               $smarty->display('sistema_de_produccion/normas/listar_analisis.html');
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
               $listanalisis=$noconformidad->listar_reg_analisis();
               $smarty->assign('analisis', $listanalisis);
               $smarty->display('sistema_de_produccion/normas/listar_analisis.html');
				}
		   }
       }
	 }
}
?>