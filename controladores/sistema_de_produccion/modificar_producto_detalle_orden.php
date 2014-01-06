<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/colores.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/chapa.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/sistema_de_produccion/etiquetas.php');
include_once('../../clases/sistema_de_produccion/propiedades.php');
include_once('../../clases/sistema_de_produccion/familias.php');
include_once('../../clases/sistema_de_produccion/fuentes.php');
include_once('../../clases/sistema_de_produccion/lugargrabado.php');

require_once('../../clases/sistema_de_produccion/modelos.php');

//include_once('../../clases/sistema_de_produccion/familia_estilos.php');
//include_once('../../clases/sistema_de_produccion/propiedad_familia_estilo.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
header("Content-Type: text/html; charset=iso-8859-1");
$detalle_orden=new Detalle_orden;
//$familia_estilo=new FamiliaEstilos;
//$propiedad_familia_estilo=new PropiedadFamiliaEstilo;
$orden = new OrdenProd();
$fuentes = new Fuente;
$grabados = new Lugar;
$estilos = new Estilo;
$validar = new Validador();
$colores = new Color;
$clips = new Clip;
$sellos = new Chapa;
$cueros = new Cuero;
$etiquetas = new Etiqueta;
$propiedades = new Propiedad;
$familias = new Familia;

$modelo = new Modelo;

if (isset($_GET["busqueda_ajax"])) {
	$valor = $_POST["value"];
	$cadena = utf8_decode(trim($valor));
	echo "<ul>";
	if ($_GET['busqueda_ajax']=='clips')
		$lista = $clips-> busqueda_clips($cadena);
	else {
		if ($_GET['busqueda_ajax']=='cueros')
			$lista = $cueros-> busqueda_cueros($cadena);
		else {
			if ($_GET['busqueda_ajax']=='colores')
				$lista=$colores-> busqueda_colores($cadena);
			else {
				if ($_GET['busqueda_ajax']=='sellos')
					$lista=$sellos-> busqueda_sellos($cadena);
				else {
					if ($_GET['busqueda_ajax']=='etiquetas')
						$lista=$etiquetas->busqueda_etiquetas($cadena);
					else {
						if ($_GET['busqueda_ajax']=='lugar_grabados') {
							echo "<li></li>";
							$lista=$grabados->busqueda_lugar_grabados($cadena);
						} else {
							if ($_GET['busqueda_ajax']=='fuentes'){
								echo "<li></li>";
								$lista=$fuentes->busqueda_fuentes($cadena);
							} else {
								if ($_GET['busqueda_ajax']=='modelos')
									$lista=$familias->busqueda_familias($cadena);
								}
							}
						}
					}
				}
			}
		}
	
	if (count($lista) == 0) {
		echo "<li>No hay resultados</li>";
	} else {
		for ($contador=0;$contador<count($lista);$contador++) {
			echo "<li>".$lista[$contador]."</li>";
		}
	}
	echo "</ul>";
} else {
	if ($validar->validarTodo($_POST['texto_modelo'], 1, 100)) {
		$error['err_texto_modelo'] = "Debe ingresar la descripción del modelo";
	} else {
		$familia_id = $familias->verifica_familia_valida(trim($_POST['texto_modelo']));
		if ($familia_id == -1){
			$error['err_texto_modelo'] = "Descripción del producto no válida";
		}
	}
	if ($validar->validarTodo($_POST['texto_clip'], 1, 100)) {
    	$error['err_texto_clip'] = "Debe ingresar un clip";
	} else {
		$clip_id = $clips->verificar_clip(trim($_POST['texto_clip']));
		if ($clip_id == -1) {
			$error['err_texto_clip'] = "Clip no valido";
		}
	}
	if ($validar->validarTodo($_POST['texto_cuero'], 1, 100)) {
    	$error['err_texto_cuero'] = "Debe ingresar un tipo de cuero";
	} else {
		$cuero_id = $cueros->verificar_cuero(trim($_POST['texto_cuero']));
		if ($cuero_id == -1){
			$error['err_texto_cuero'] = "Cuero no valido";
		}
	}
	if ($validar->validarTodo($_POST['texto_color'], 1, 100)) {
    	$error['err_texto_color'] = "Debe ingresar un color";
	} else {
		$color_id = $colores->verificar_color(trim($_POST['texto_color']));
		if ($color_id == -1){
			$error['err_texto_color'] = "Color no valido";
		}
	}
	if ($validar->validarTodo($_POST['texto_etiqueta'], 1, 100)) {
    	$error['err_texto_etiqueta'] = "Debe ingresar una etiqueta";}
	else {
		$etiqueta_id = $etiquetas->verificar_etiqueta(trim($_POST['texto_etiqueta']));
		if ($etiqueta_id == -1) {
			$error['err_texto_etiqueta'] = "Etiqueta no valida";
		}
	}
	if ($validar->validarTodo($_POST['texto_sello'], 1, 100)) {
    	$error['err_texto_sello'] = "Debe ingresar un sello o chapa";}	
	else {
		$sello_id = $sellos->verificar_chapa(trim($_POST['texto_sello']));
		if ($sello_id == -1){
			$error['err_texto_sello'] = "Sello/Chapa no valido";
		}
	}
	if ($validar->validarTodo($_POST['texto_cantidad'], 1, 100)) {
    	$error['err_texto_cantidad'] = "Debe ingresar una cantidad";}							 
	else {
		if ($validar->validarNumeros($_POST['texto_cantidad'], 1, 3)) {
        	$error['err_texto_cantidad'] = "Debe ingresar una cantidad valida";
		}
	}
	if ($validar->validarTodo($_POST['texto_unidad'], 1, 100)) {
    	$error['err_texto_unidad'] = "Debe ingresar la unidad";}	
	//if ($validar->validarTodo($_POST['texto_pedido'], 1, 100)) {
    	//$error['err_texto_pedido'] = "Debe ingresar el codigo de pedido";}	
	if (!$validar->validarTodo($_POST['texto_lugar_grabado'], 1, 100)) {
	    if ($validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
	    	$error['err_texto_grabado'] = "Debe ingresar un grabado";
		}
    	$grabado_id = $grabados->verificar_lugar(trim($_POST['texto_lugar_grabado']));
		if ($grabado_id == -1){
			$error['err_texto_lugar_grabado'] = "Lugar de grabado no válido";
		}
	}
	if (!$validar->validarTodo($_POST['texto_fuente'], 1, 100)) {
		if ($validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
	    	$error['err_texto_grabado'] = "Debe ingresar un grabado";
		}
    	$fuente_id = $fuentes->verificar_fuente(trim($_POST['texto_fuente']));
        if ($fuente_id == -1){
			$error['err_texto_fuente'] = "Tipo de fuente no valido";
		}
	}
	if (!$validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
	     if ($validar->validarTodo($_POST['texto_fuente'], 1, 100) and $validar->validarTodo($_POST['texto_lugar_grabado'], 1, 100)) {
			$error['err_texto_lugar_grabado'] = "Debe ingresar el tipo de fuente o el lugar de grabado"; 
		 }
	}	
	if ($validar->validarTodo($_POST['texto_prioridad'], 1, 100)) {
    	$error['err_texto_prioridad'] = "Debe ingresar la prioridad";}							 
	else {
		if ($validar->validarNumeros($_POST['texto_prioridad'], 1, 3)) {
        	$error['err_texto_prioridad'] = "Debe ingresar una prioridad valida";
		}
	}						

    if (isset($error)) {
	    $orden_id = $_POST['orden_id'];
		$detalle_id = $_POST['detalle_id'];
		$cabecera = $orden->obtener_cabecera_orden($orden_id);
		$detalle['modelo'] = $_POST['texto_modelo'];
  	    $detalle['estilo'] = "";
		$detalle['clip']=  $_POST['texto_clip'];
		$detalle['cuero']= $_POST['texto_cuero'];
		$detalle['color']= $_POST['texto_color'];
		$detalle['etiqueta']= $_POST['texto_etiqueta'];
		$detalle['sello']= $_POST['texto_sello'];
		if (!get_magic_quotes_gpc()) 
		{
		     $detalle['observaciones']= $_POST['observaciones'];
			 $detalle['obs_interior']= $_POST['observaciones_internas'];
			 $detalle['grabado']= $_POST['texto_grabado'];	
		}
		else
		{ 
		     $detalle['observaciones']= stripslashes($_POST['observaciones']);
			 $detalle['obs_interior']= stripslashes($_POST['observaciones_internas']);
			 $detalle['grabado']= stripslashes($_POST['texto_grabado']);	
		}
		
		$detalle['cantidad']= $_POST['texto_cantidad'];
		$detalle['unidad']= $_POST['texto_unidad'];	
		$detalle['pedido']= $_POST['texto_pedido'];
		$detalle['prioridad']= $_POST['texto_prioridad'];
		$detalle['fuente']= $_POST['texto_fuente'];
		$detalle['lugargrabado']= $_POST['texto_lugar_grabado'];
		$detalle['tipo']= $_POST['tipo'];
		
		//
		$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
		$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
		//
		
		$smarty->assign('nombres', $_SESSION["nombres"]);
		$smarty->assign('apellidos', $_SESSION["apellidos"]);
		$smarty->assign('orden_id',$orden_id);
		$smarty->assign('detalle_id',$detalle_id);
		$smarty->assign('detalle',$detalle);
		$smarty->assign('errores',$error);
		$smarty->assign('cabecera',$cabecera);
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_producto_detalle_orden.html');
	} else {
		$detalle_id = $_POST['detalle_id'];
		$prop_id = $propiedades->verificar_propiedad($clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id);        
		if ($prop_id == -1) {
			$prop_id=$propiedades->insertar_nueva_propiedad($clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id);
		}
		
		if (!get_magic_quotes_gpc()) {
    				$texto_grabado = addslashes($_POST['texto_grabado']);
					$observaciones = addslashes($_POST['observaciones']);
					$observaciones_internas = addslashes($_POST['observaciones_internas']);
			} else {
   					$texto_grabado = $_POST['texto_grabado'];
					$observaciones = $_POST['observaciones'];
					$observaciones_internas = $_POST['observaciones_internas'];
			}
		
		//aqui ya se actualizan 'detalle', 'resultados asignaicon', 'hoja'
		$detalle_orden->actualizar_producto_detalle($detalle_id,$_POST['texto_cantidad'],$prop_id,$_POST['texto_unidad'],$_POST['texto_prioridad'],$observaciones,$_POST['texto_pedido'],$texto_grabado,$_POST['texto_lugar_grabado'],$_POST['texto_fuente'],$observaciones_internas,$familia_id, $_POST['tipo']);
        $detalle_orden->registrar_despiece($detalle_id,"2");
		$orden_id=$_POST['orden_id'];
  		$detalle=$detalle_orden->obtener_detalle_orden($orden_id);
  		$cabecera=$orden->obtener_cabecera_orden($orden_id);
			
		$smarty->assign('nombres', $_SESSION["nombres"]);
		$smarty->assign('apellidos', $_SESSION["apellidos"]);
		$smarty->assign('orden_id',$orden_id);
		$smarty->assign('detalle',$detalle);
    	//$smarty->assign('errores',$error);
		$smarty->assign('cabecera',$cabecera);
		$smarty->assign('pagina_envio',$_POST['pagina_envio']);
		
		
		$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
		$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
		
		//*******
		$detalle_asignaciones = $detalle_orden->obtener_asignaciones_detalle_orden($orden_id);
		$smarty->assign('detalle_asignaciones', $detalle_asignaciones);
		//*******
		
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
	}   
}
?>