<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');

//include_once('../../clases/validadornc.php');
require_once('../../clases/sistema_de_produccion/area.php');
include_once('../../clases/noconformidad_apertura.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$smarty->assign('fecha', $fecha);

//$validar = new Validador();
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
		$doc=$noconformidad->imprimir_apertura($id);
		$impre=$noconformidad->modificar_print_apertura($id);
		$smarty->assign('doc',$doc);
		$smarty->display('sistema_de_produccion/normas/imprimir_apertura.html');//imprimir_documentoEntero.html');//imprimir_apertura	
	  }
       if($funcion=="acepta")
	 {
		$id=$_GET['elegido'];
        $impre=$noconformidad->modificar_estado_rg40($id,4);
        $aperturada=$noconformidad->listar_reg_aperturadas(4);//para cargar las listas de aperturadas sin aprobar
        $cerradas=$noconformidad->listar_reg_cerradas(5);//para cargar las listas de cerradas sin aprobar
		$smarty->assign('aperturada',$aperturada);
        $smarty->assign('cerradas',$cerradas);
		$smarty->display('sistema_de_produccion/normas/listar_rg_40_pendientes.html');	
	  }
        if($funcion=="ver")
	 {
		$id=$_GET['elegido'];
		$doc=$noconformidad->imprimir_apertura($id);
		$smarty->assign('doc',$doc);
		$smarty->display('sistema_de_produccion/normas/imprimir_apertura.html');	
	  }
	  else
	  {
       if ($funcion == "analisis") 
       {//analisis y accion
			$registro = $_GET["elegido"];
  		    $listapertura=$noconformidad->recuperar_apertura($registro);
  		    $smarty->assign('aperturada', $listapertura);
		    $smarty->display('sistema_de_produccion/normas/NoConformidad/registrar_analisis.html');
	   }
       if($funcion=="eliminar_apertura")
	  	{
			$nro_registro=$_GET["elegido"];
			$consulta=$noconformidad->eliminar_apertura($nro_registro);
            $consulta1=$noconformidad->actualizando_auto();//actualiza el valor del autoincrement en noconformidades
            $aperturada=$noconformidad->listar_reg_aperturadas(4);//para cargar las listas de aperturadas sin aprobar
            $cerradas=$noconformidad->listar_reg_cerradas(5);//para cargar las listas de cerradas sin aprobar
            $smarty->assign('aperturada',$aperturada);
            $smarty->assign('cerradas',$cerradas);
            $smarty->display('sistema_de_produccion/normas/listar_rg_40_pendientes.html');        
		}
		else
		{
           if($funcion=="modificar")
  	       { 
		    echo "<ul>";
                $lista = $area->areas();
            if(count($lista) == 0)
            {
			     echo "<li>No hay resultados</li>";
		    } 
            else 
            {
			for($contador = 0; $contador < count($lista); $contador ++)
            $detalles = $lista[$contador]["nombre_area"];
            echo "</ul>";
            $registro = $_GET["elegido"];
		    $consulta=$noconformidad->consulta_aperturada($registro);
            $smarty->assign('nro_registro',$consulta["nro_registro"]);
            $smarty->assign('nro_revision',$consulta["nro_revision"]);
            $smarty->assign('tipo',$consulta["tipo"]);
            $smarty->assign('accion',$consulta["accion"]);
            
            $areaobservadas =$consulta["area_observada"];
            $observa=explode(',', $areaobservadas);//lista con los id de las areas 
            $smarty->assign('observadas',$observa);
            $smarty->assign('area_observada',$lista);
            
            $areainformadas =$consulta["area_informada"];
            $informa=explode(',', $areainformadas );
            $smarty->assign('informadas',$informa);
            $smarty->assign('area_informada',$lista);
            
            $smarty->assign('cierre',$consulta["cierre"]);
            $smarty->assign('fec_plan_cierre',$consulta["fec_plan_cierre"]);
            $smarty->assign('motivo',$consulta["motivo"]);
            $smarty->assign('descripcion',$consulta["descripcion"]);
            $smarty->assign('responsable_observada',$consulta["responsable_observada"]);
            $smarty->assign('responsable_informada',$consulta["responsable_informada"]);
            $smarty->assign('fec_apertura',$consulta["fec_apertura"]);
	        $smarty->display('sistema_de_produccion/normas/NoConformidad/modificar_apertura.html');
            }
  	        }
			if($funcion=="modificar_apertura")//cuando se modifican los valores
			{
            if($_POST['area_observada']!='' && $_POST['area_informada']!='' && $_POST['documento']!='' && $_POST['descripcion']!='')
            {
            $nro_registro=$_GET['elegido'];    
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
               $noconformidad->modificar_apertura($nro_registro,$_POST['tipo'],$_POST['accion'],$observadas,$informadas,$_POST['cierre'],$_POST['documento'],$_POST['descripcion'],$responsables,$responsablesinf);
	           $smarty->assign('err_firstname', "Los datos fueron actualizados correctamente");
               $aperturadas=$noconformidad->listar_reg_aperturadas();
               $smarty->assign('aperturada', $aperturadas);
               $smarty->display('sistema_de_produccion/normas/listar_aperturas.html');
               }
               else
               {
                echo "<ul>";
                $lista = $area->areas();
            if(count($lista) == 0)
            {
			     echo "<li>No hay resultados</li>";
		    } 
            else 
            {
			for($contador = 0; $contador < count($lista); $contador ++)
            $detalles = $lista[$contador]["nombre_area"];
            echo "</ul>";
            $registro = $_GET["elegido"];
		    $consulta=$noconformidad->consulta_aperturada($registro);
            $smarty->assign('nro_registro',$consulta["nro_registro"]);
            $smarty->assign('nro_revision',$consulta["nro_revision"]);
            $smarty->assign('tipo',$consulta["tipo"]);
            $smarty->assign('accion',$consulta["accion"]);
            
            $areaobservadas =$consulta["area_observada"];
            $observa=explode(',', $areaobservadas); 
            $smarty->assign('observadas',$observa);
            $smarty->assign('area_observada',$lista);
            
            $areainformadas =$consulta["area_informada"];
            $informa=explode(',', $areainformadas );
            $smarty->assign('informadas',$informa);
            $smarty->assign('area_informada',$lista);
            
            $smarty->assign('cierre',$consulta["cierre"]);
            $smarty->assign('fec_plan_cierre',$consulta["fec_plan_cierre"]);
            $smarty->assign('motivo',$consulta["motivo"]);
            $smarty->assign('descripcion',$consulta["descripcion"]);
            $smarty->assign('responsable_observada',$consulta["responsable_observada"]);
            $smarty->assign('responsable_informada',$consulta["responsable_informada"]);
            $smarty->assign('fec_apertura',$consulta["fec_apertura"]);
	        $smarty->display('sistema_de_produccion/normas/NoConformidad/modificar_apertura.html');
            }
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
          	$consulta = $noconformidad->consultar_busqueda($cadena,$opcion);
				if ($consulta != null)
                {
						$smarty->assign('doc',$consulta);
						$smarty->assign('tabu',$_SESSION['tabu']);
                        $smarty->assign('aperturada', $consulta);
                        $smarty->display('sistema_de_produccion/normas/listar_aperturas.html');
				} 
                else 
                {
			    if($opcion == "num_doc")
                {$smarty->assign('mensaje',"No se encontr&oacute; el numero RG-40");}
			    else
                {$smarty->assign('mensaje',"No se encontr&oacute el tipo de registro RG-40");}			  
               $smarty->assign('tabu',$_SESSION['tabu']);  
               $smarty->display('sistema_de_produccion/normas/listar_aperturas.html');
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
               $aperturadas=$noconformidad->listar_reg_aperturadas();
               $smarty->assign('aperturada', $aperturadas);
               $smarty->display('sistema_de_produccion/normas/listar_aperturas.html');
				}
		   }
       }
   }
}
?>