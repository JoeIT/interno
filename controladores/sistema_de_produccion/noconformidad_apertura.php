<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/noconformidad_apertura.php');
include_once('../../clases/sistema_de_produccion/personal.php');
//PARA SERVIDOR DE CORREOS
include_once('../../clases/enviar.php');

include_once('../../clases/sistema_de_produccion/area.php');

include_once('../../clases/validador.php');
require("../includes/fecha.php");

//PARA SERVIDOR DE CORREOS
$enviar = new Enviar();

$areas = new Area();
$personal = new Personal();
$validar = new Validador();
$noconformidad = new NoConformidad_apertura();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else 
{
$fecha_hoy = date("Y-m-d");
if(isset($_GET["busqueda_ajax"]))
{    
      $valor = $_POST["value"];
	  $cadena= utf8_decode(trim($valor));
	  echo "<ul>";
	  if($_GET["busqueda_ajax"]=='personal')
	    $lista=$personal->busqueda_personal($cadena);
	  if(count($lista)==0)
	  {
		   echo "<li>No hay resultados</li>";
	  }
	  else
	  {
		for($contador=0;$contador<count($lista);$contador++)
	     	{
		        $detalles = $lista[$contador]["personal_id"];
                echo '<li id="'.$detalles.'">&nbsp;'.$lista[$contador]["completo"].'</li>';
		 	}
	  }
	  echo "</ul>";
    }		
    else {
    switch ($_GET['opcion']){
	case '1' :{//registrar apertura de no conformidad
              if ($_GET['funcion'] == "validar") 
              { 
                if($_POST['area_observada']!='' && $_POST['area_informada']!='' && $_POST['documento']!='' && $_POST['descripcion']!='')
                {
                foreach($_POST['area_observada'] as $indice => $valor) 
				{
                  if ($indice == 0)
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada);
                     $observadas=$observa[0];
                     $responsables=$observa[1];
                  }
                  else
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada); 
                     $observadas=$observadas.",".$observa[0];
                     $responsables =$responsables.",".$observa[1];
                  }
                    
				}
               foreach($_POST['area_informada'] as $apunta => $valor) 
				{
                  if ($apunta == 0)
                  {
                     $informada=$valor;
                     $informa=explode('-', $informada);
                     $informadas=$informa[0];
                     $responsablesinf=$informa[1];
                  }
                  else
                  {
                     $informada=$valor;
                     $informa=explode('-', $informada); 
                     $informadas=$informadas.",".$informa[0];
                     $responsablesinf =$responsablesinf.",".$informa[1];
                  }
				}
               $noconformidad->ingresar_apertura($_POST['nro_revision'],$_POST['tipo'],$_POST['accion'],$observadas,$informadas,$_POST['cierre'],$_POST['documento'],$_POST['descripcion'],$responsables,$responsablesinf);
	           $smarty->assign('err_firstname', "Los datos fueron almacenados correctamente");
			   //PARA SERVIDOR DE CORREOS
			    $enviar->SendMAIL("peter@macaws.net","PENDIENTE RG-40 de APERTURA","<font color=red>Ingreso un nuevo registro RG-40 por APERTURAR</font><br/><br/><br/><table border=".'1'." style=".'border-bottom-style:solid'." cellpadding=".'0'." cellspacing=".'0'."><tr><td align=".'right'."><font color=blue>TIPO:</font></td><td >".$_POST['tipo']."</td></tr><tr><td align=".'right'."><font color=blue>ACCION:</font></td><td>".$_POST['accion']."</td></tr><tr><td align=".'right'."><font color=blue>CIERRE PLANEADO:</font></td><td>".$_POST['cierre']."</td></tr></table><br/><a href=".'http://192.168.0.100/sistema/controladores/index.php'."><strong>&gt;&gt; A P R O B A R &lt;&lt;</strong></a><br/><br/>Atte. Normas Macaws","Sistema Interno","RG-40@normas.com","Sistema Interno Macaws");

               }
               $lista = $areas->areas();
               if(count($lista) == 0)
               {
			     echo "<li>No hay resultados</li>";
		       } 
               else 
               {
		        for($contador = 0; $contador < count($lista); $contador ++)
                {
                   $detalles = $lista[$contador]["nombre_area"]."-".$lista[$contador]["completo"];
			    }
               echo "</ul>";
               $smarty->assign('area_observada',$lista);
               $smarty->assign('area_informada',$lista);
               }
               //para q cargue q numero de registro sera el actual
                $ultimo=$noconformidad->ultima_aperturada();
                $smarty->assign('ultimo', $ultimo);
			   $smarty->display('sistema_de_produccion/normas/NoConformidad/registrar_apertura.html');
			   }
               else 
               {
                echo "<ul>";
                $lista = $areas->areas();
                if(count($lista) == 0)
                {
			     echo "<li>No hay resultados</li>";
		        } 
                else 
                {
			    for($contador = 0; $contador < count($lista); $contador ++)
                  $detalles = $lista[$contador]["nombre_area"];
                echo "</ul>";
             
                //para q cargue q numero de registro sera el actual
                $ultimo=$noconformidad->ultima_aperturada();
                $smarty->assign('ultimo', $ultimo);
                $smarty->assign('area_observada',$lista);
                $smarty->assign('area_informada',$lista);
			    $smarty->assign('tipo', $_POST['tipo']);
                $smarty->assign('accion', $_POST['accion']);
                $smarty->assign('cierre',$_POST['cierre']);
                $smarty->assign('documento',$_POST['documento']);
                $smarty->assign('descripcion',$_POST['descripcion']);
                $smarty->assign('responsable_observada',$_POST['responsable_observada']);
                $smarty->assign('responsable_informada',$_POST['responsable_informada']);
			    $smarty->display('sistema_de_produccion/normas/NoConformidad/registrar_apertura.html');
		        }
            }
			break;
			}
    case 2 :{
			if ($_GET['funcion'] == "validar") 
            {
               $nro_registro = $_GET["elegido"]; 
               if($_POST['analisis_causa']!='' && $_POST['accion_inmediata']!='')
                {
               $noconformidad->ingresar_analisis($nro_registro,$_POST['disposicion'],$_POST['analisis_causa'],$_POST['accion_inmediata'],$_POST['accion_otras']);
	           $smarty->assign('err_firstname', "Los datos fueron almacenados correctamente");
			   }
               $aperturadas=$noconformidad->listar_reg_aperturadas(1);
               $smarty->assign('aperturada', $aperturadas);
               $smarty->display('sistema_de_produccion/normas/listar_aperturas.html');
	       }else
               {
               $aperturadas=$noconformidad->listar_reg_aperturadas(1);
               $smarty->assign('aperturada', $aperturadas);
               $smarty->display('sistema_de_produccion/normas/listar_aperturas.html');
              }
			  break;
			}
	case 3 :{
             if ($_GET['funcion'] == "validar") 
             {
                $nro_registro = $_GET["elegido"];
                if($_POST['maquinista_id']!='' && $_POST['fec_cumplimiento']!='' && $_POST['fec_ver_cumplimiento']!='')
                {
               $noconformidad->ingresar_revision($nro_registro,$_POST['maquinista_id'],$_POST['fec_cumplimiento'],$_POST['fec_ver_cumplimiento'],$_POST['fec_extension'],$_POST['fec_ver_extension'],$_POST['efectividad'],$_POST['efectividad_ext'],$_POST['causa_extension']);
	           $smarty->assign('err_firstname', "Los datos fueron almacenados correctamente");
               }
               $analisis=$noconformidad->listar_reg_analisis();
               $smarty->assign('analisis', $analisis);
               $smarty->display('sistema_de_produccion/normas/listar_analisis.html');
			   }else
               {
               $analisis=$noconformidad->listar_reg_analisis();
               $smarty->assign('analisis', $analisis);
               $smarty->display('sistema_de_produccion/normas/listar_analisis.html');
              }
				break;
			}
	case 4 :{
				if ($_GET['funcion'] == "validar") 
               {
               $nro_registro = $_GET["elegido"];
               if($_POST['accion_resultado']!='')
             {
               $noconformidad->ingresar_cierre($nro_registro,$_POST['accion_resultado'],$_POST['aplica_comunicacion'],$_POST['comunicacion_cliente'],$_POST['responsable_contacto'],$_POST['fec_contacto']);
	           $smarty->assign('err_firstname', "Los datos fueron almacenados correctamente");
			   //PARA SERVIDOR DE CORREOS
			    $enviar->SendMAIL("peter@macaws.net","PENDIENTE RG-40 de CIERRE","<font color=red>Ingreso un nuevo registro RG-40 por CERRAR </font><br/><br/><br/><table border=".'1'." style=".'border-bottom-style:solid'." cellpadding=".'0'." cellspacing=".'0'."><tr><td align=".'right'."><font color=blue>Nº REGISTRO:</font></td><td >".$nro_registro."</td></tr><tr><td align=".'right'."><font color=blue>ACCION RESULTADO:</font></td><td>".$_POST['accion_resultado']."</td></tr></table><br/><a href=".'http://192.168.0.100/sistema/controladores/index.php'."><strong>&gt;&gt; C E R R A R &lt;&lt;</strong></a><br/><br/>Atte. Normas Macaws","Sistema Interno","RG-40@normas.com","Sistema Interno Macaws");
               }
               $revisiones=$noconformidad->listar_reg_revisiones();
               $smarty->assign('revisiones', $revisiones);
               $smarty->display('sistema_de_produccion/normas/listar_revisiones.html');
			   }else
               {
               $revisiones=$noconformidad->listar_reg_revisiones();
               $smarty->assign('revisiones', $revisiones);
               $smarty->display('sistema_de_produccion/normas/listar_revisiones.html');
              }
				break;
			}
	case 5 :{
               $cerradas=$noconformidad->listar_reg_cerradas(0);
               $smarty->assign('cerradas', $cerradas);
               $smarty->display('sistema_de_produccion/normas/listar_cerradas.html');
				break;
			}
	case 6 :{
				//reporte RG-40 vigentes
				$reporte_noconformidad=$noconformidad_apertura->reporte_RG40(1);
				$smarty->assign('reporte_noconformidad',$reporte_noconformidad);
				$smarty->display('sistema_de_produccion/noconformidad_apertura/reporte_noconformidad.html');
				break;
			}
	case 7 :{
				//reporte RG-40 cerrados
				$reporte_noconformidad=$noconformidad_apertura->reporte_RG40(0);
				$smarty->assign('reporte_noconformidad',$reporte_noconformidad);
				$smarty->display('sistema_de_produccion/noconformidad_apertura/reporte_noconformidad.html');
				break;
			}
	case 8 :{
               $aperturadas=$noconformidad->listar_reg_aperturadas(4);
               $smarty->assign('aperturada', $aperturadas);
               
               
               $cerradas=$noconformidad->listar_reg_cerradas(5);
               $smarty->assign('cerradas', $cerradas);
               $smarty->display('sistema_de_produccion/normas/listar_rg_40_pendientes.html'); 
               
               

			break;
			}
	case 9 :{   
                //INGRESANDO CODIGO PARA CONFORMIDADES INDIVIDUALES             
               $revisiones=$noconformidad->consulta_noconformidad($_SESSION["grupo_id"],1);          
               $smarty->assign('revisiones', $revisiones);
               $cierres=$noconformidad->consulta_noconformidad($_SESSION["grupo_id"],0);
               $smarty->assign('cierres', $cierres);
               $smarty->display('sistema_de_produccion/normas/verCargarRevisionesRG40.html');
				break;
			}
		}       
	}
}
?>