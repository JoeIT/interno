<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

require_once('../../clases/sistema_de_produccion/area.php');
include_once('../../clases/noconformidad_apertura.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$validar = new Validador();
$area= new Area();
$noconformidad = new NoConformidad_apertura();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else
	 {
	$funcion = $_GET['funcion'];
	 if($funcion=="detalle")
	 {
		$id=$_GET['elegido'];
		$doc=$noconformidad->imprimir_revision($id);
		$impre=$noconformidad->modificar_print_revision($id);
		$smarty->assign('doc',$doc);
		$smarty->display('sistema_de_produccion/normas/imprimir_revision.html');				
	  }
	  else
	  {
         if ($funcion == "cierre") 
         {
			$registro = $_GET["elegido"]; 
  		    $listarevision=$noconformidad->recuperar_revision($registro);
  		    $smarty->assign('revision', $listarevision);
		    $smarty->display('sistema_de_produccion/normas/NoConformidad/registrar_cierre.html');
	     } 
         else
         {
            if($funcion=="modificar")
  	       {
            $registro = $_GET["elegido"];
		    $consulta=$noconformidad->consulta_revision($registro);
            $smarty->assign('nro_registro',$consulta["nro_registro"]);
            $smarty->assign('responsable_cumplimiento',$consulta["responsable_cumplimiento"]);
            $smarty->assign('fec_cumplimiento',$consulta["fec_cumplimiento"]);
            $smarty->assign('fec_ver_cumplimiento',$consulta["fec_ver_cumplimiento"]);
            $smarty->assign('fec_extension',$consulta["fec_extension"]);
            $smarty->assign('fec_ver_extension',$consulta["fec_ver_extension"]);
            $smarty->assign('efectividad',$consulta["efectividad"]);
            $smarty->assign('efectividad_ext',$consulta["efectividad_ext"]);
            $smarty->assign('causa_extension',$consulta["causa_extension"]);
	        $smarty->display('sistema_de_produccion/normas/NoConformidad/modificar_revision.html');
            }
			if($funcion=="modificar_apertura")
			{
			 if($_POST['fec_cumplimiento']!='' && $_POST['fec_ver_cumplimiento']!='')
             {
               $nro_registro=$_GET['elegido'];
               if ($_POST['maquinista_id'] != '')
                $noconformidad->modificar_revision($nro_registro,$_POST['maquinista_id'],$_POST['fec_cumplimiento'],$_POST['fec_ver_cumplimiento'],$_POST['fec_extension'],$_POST['fec_ver_extension'],$_POST['efectividad'],$_POST['efectividad_ext'],$_POST['causa_extension']);
               else
                $noconformidad->modificar_revisionS($nro_registro,$_POST['fec_cumplimiento'],$_POST['fec_ver_cumplimiento'],$_POST['fec_extension'],$_POST['fec_ver_extension'],$_POST['efectividad'],$_POST['efectividad_ext'],$_POST['causa_extension']);
               $smarty->assign('err_firstname', "Los datos fueron actualizados correctamente");
               $listarevision=$noconformidad->listar_reg_revisiones();
               $smarty->assign('revisiones', $listarevision);
               $smarty->display('sistema_de_produccion/normas/listar_revisiones.html');
               }
               else
               {
                $registro = $_GET["elegido"];
		    $consulta=$noconformidad->consulta_revision($registro);
            $smarty->assign('nro_registro',$consulta["nro_registro"]);
            $smarty->assign('responsable_cumplimiento',$consulta["responsable_cumplimiento"]);
            $smarty->assign('fec_cumplimiento',$consulta["fec_cumplimiento"]);
            $smarty->assign('fec_ver_cumplimiento',$consulta["fec_ver_cumplimiento"]);
            $smarty->assign('fec_extension',$consulta["fec_extension"]);
            $smarty->assign('fec_ver_extension',$consulta["fec_ver_extension"]);
            $smarty->assign('efectividad',$consulta["efectividad"]);
            $smarty->assign('efectividad_ext',$consulta["efectividad_ext"]);
            $smarty->assign('causa_extension',$consulta["causa_extension"]);
	        $smarty->display('sistema_de_produccion/normas/NoConformidad/modificar_revision.html');
               }
               
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
          	$consulta = $noconformidad->consultar_busqueda_revision($cadena,$opcion);
					if ($consulta != null)
                    {
						$smarty->assign('doc',$consulta);
						$smarty->assign('tabu',$_SESSION['tabu']);
                        $smarty->assign('revisiones', $consulta);
                        $smarty->display('sistema_de_produccion/normas/listar_revisiones.html');
					} 
                    else 
                    {
						if($opcion == "num_doc")
                        {$smarty->assign('mensaje',"No se encontr&oacute; el numero RG-40");}
						else
                        {$smarty->assign('mensaje',"No se encontr&oacute el tipo de registro RG-40");}
						  
                  $smarty->assign('tabu',$_SESSION['tabu']);
                  
               $smarty->display('sistema_de_produccion/normas/listar_revisiones.html');
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
               $listarevision=$noconformidad->listar_reg_revisiones();
               $smarty->assign('revisiones', $listarevision);
               $smarty->display('sistema_de_produccion/normas/listar_revisiones.html');
				}
		   }
       }
	 }
}
?>