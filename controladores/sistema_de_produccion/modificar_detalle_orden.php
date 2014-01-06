<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");

include_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../includes/seguridad.php');
include_once('../includes/fecha.php');
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
include_once('../../clases/sistema_de_produccion/modelos.php');

header("Content-Type: text/html; charset=iso-8859-1");
$detalle_orden = new Detalle_orden;
$modelo = new Modelo;
$orden = new OrdenProd;
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
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('fecha',$fecha);

$funcion = $_GET["funcion"];
if (isset($_GET["busqueda_ajax"])) {
	$valor = $_POST["value"];
	$cadena = utf8_decode(trim($valor));
	header("Content-Type: text/html; charset=iso-8859-1");
	echo "<ul>";
	
	if ($_GET['busqueda_ajax'] == 'clips')
		$lista = $clips-> busqueda_clips($cadena);
	else {
		if ($_GET['busqueda_ajax'] == 'cueros')
			$lista = $cueros-> busqueda_cueros($cadena);
		else {
			if ($_GET['busqueda_ajax'] == 'colores')
				$lista = $colores-> busqueda_colores($cadena);
			else {
				if ($_GET['busqueda_ajax'] == 'sellos')
					$lista = $sellos-> busqueda_sellos($cadena);
				else {
					if ($_GET['busqueda_ajax'] == 'etiquetas')
						$lista = $etiquetas->busqueda_etiquetas($cadena);
					else {
						if ($_GET['busqueda_ajax'] == 'lugar_grabado') {
							echo "<li></li>";
							$lista = $grabados->busqueda_lugar_grabados($cadena);
						} else {
							if ($_GET['busqueda_ajax'] == 'fuentes') {
								echo "<li></li>";
								$lista = $fuentes->busqueda_fuentes($cadena);
							} else {
								if($_GET['busqueda_ajax'] == 'modelos')
									$lista = $familias->busqueda_familias($cadena);
							}
						}
					}
				}
			}
		}
	}
	
	if(count($lista) == 0){
		echo "<li>No hay resultados</li>";
	} else {
		for ($contador = 0; $contador < count($lista); $contador ++) {
			echo "<li>".$lista[$contador]."</li>";
		}
	}
	
	echo "</ul>";
} else {
	if ($funcion == "registrar") {
		if ($validar->validarTodo($_POST['texto_modelo'], 1, 100)) {
			$error['err_texto_modelo'] = "Debe ingresar la descripción del modelo";
		} else {
			$familia_id = $familias->verifica_familia_valida(trim($_POST['texto_modelo']));
			if ($familia_id == -1) {
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
			if ($cuero_id == -1) {
				$error['err_texto_cuero'] = "Cuero no valido";
			}
		}
		if ($validar->validarTodo($_POST['texto_color'], 1, 100)) {
			$error['err_texto_color'] = "Debe ingresar un color";
		} else {
			$color_id = $colores->verificar_color(trim($_POST['texto_color']));
			if ($color_id == -1) {
				$error['err_texto_color'] = "Color no valido";
			}
		}
		if ($validar->validarTodo($_POST['texto_etiqueta'], 1, 100)){
			$error['err_texto_etiqueta'] = "Debe ingresar una etiqueta";
		} else {
			$etiqueta_id = $etiquetas->verificar_etiqueta(trim($_POST['texto_etiqueta']));
			if ($etiqueta_id == -1) {
				$error['err_texto_etiqueta'] = "Etiqueta no valida";
			}
		}
		if ($validar->validarTodo($_POST['texto_sello'], 1, 100)){
			$error['err_texto_sello'] = "Debe ingresar un sello o chapa";
		} else {
			$sello_id = $sellos->verificar_chapa(trim($_POST['texto_sello']));
			if($sello_id == -1){
				$error['err_texto_sello'] = "Sello/Chapa no valido";
			}
		}
		if ($validar->validarTodo($_POST['texto_cantidad'], 1, 100)) {
			$error['err_texto_cantidad'] = "Debe ingresar una cantidad";
		} else {
			if ($validar->validarNumeros($_POST['texto_cantidad'], 1, 3)) {
				$error['err_texto_cantidad'] = "Debe ingresar una cantidad valida";
			}
		}
		if ($validar->validarTodo($_POST['texto_unidad'], 1, 100)) {
			$error['err_texto_unidad'] = "Debe ingresar la unidad";
		}
		if (!$validar->validarTodo($_POST['texto_lugar_grabado'], 1, 100)) {
			if ($validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
				$error['err_texto_grabado'] = "Debe ingresar un grabado";
			}
			$grabado_id = $grabados->verificar_lugar(trim($_POST['texto_lugar_grabado']));
			if($grabado_id == -1){
				$error['err_texto_lugar_grabado'] = "Lugar de grabado no válido";
			}
		}
		if (!$validar->validarTodo($_POST['texto_fuente'], 1, 100)) {
			if ($validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
				$error['err_texto_grabado'] = "Debe ingresar un grabado";
			}
			$fuente_id = $fuentes->verificar_fuente(trim($_POST['texto_fuente']));
			if ($fuente_id==-1){
				$error['err_texto_fuente'] = "Tipo de fuente no valido";
			}
		}
		if (!$validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
			if ($validar->validarTodo($_POST['texto_fuente'], 1, 100) and $validar->validarTodo($_POST['texto_lugar_grabado'], 1, 100)) {
				$error['err_texto_lugar_grabado'] = "Debe ingresar el tipo de fuente o el lugar de grabado";
			}
		}
		if ($validar->validarTodo($_POST['texto_prioridad'], 1, 100)) {
			$error['err_texto_prioridad'] = "Debe ingresar la prioridad";
		} else {
			if ($validar->validarNumeros($_POST['texto_prioridad'], 1, 3)) {
				$error['err_texto_prioridad'] = "Debe ingresar una prioridad valida";
			}
		}
		
		if (isset($error)) {
			$orden_id = $_POST['orden_id'];
			$detalle = $detalle_orden->obtener_detalle_orden($orden_id);
			$cabecera = $orden->obtener_cabecera_orden($orden_id);
			$smarty->assign('pagina_envio', $_POST['pagina_envio']);
			$smarty->assign('cabecera', $cabecera);
			$smarty->assign('detalle', $detalle);
			$smarty->assign('errores',$error);
			$smarty->assign('modelo',$_POST['texto_modelo']);
			$smarty->assign('clip',$_POST['texto_clip']);
			$smarty->assign('color',$_POST['texto_color']);
			$smarty->assign('cuero',$_POST['texto_cuero']);
			$smarty->assign('etiqueta',$_POST['texto_etiqueta']);
			$smarty->assign('sello',$_POST['texto_sello']);
			$smarty->assign('tipo',$_POST['tipo']);
			if (!get_magic_quotes_gpc()) {
				$smarty->assign('observaciones', $_POST['observaciones']);
				$smarty->assign('observaciones_internas', $_POST['observaciones_internas']);
				$smarty->assign('grabado', $_POST['texto_grabado']);
			} else { 
				$smarty->assign('observaciones',stripslashes($_POST['observaciones']));
				$smarty->assign('observaciones_internas',stripslashes($_POST['observaciones_internas']));
				$smarty->assign('grabado',stripslashes($_POST['texto_grabado']));
			}
			
			//
			$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
			$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
			//
			
			$smarty->assign('cantidad',$_POST['texto_cantidad']);
			$smarty->assign('unidad',$_POST['texto_unidad']);
			$smarty->assign('pedido',$_POST['texto_pedido']);
			$smarty->assign('fuente',$_POST['texto_fuente']);
			$smarty->assign('lugar_grabado',$_POST['texto_lugar_grabado']);
			$smarty->assign('prioridad',$_POST['texto_prioridad']);
			$smarty->assign('detalle',$detalle);
			$smarty->assign('nombres', $_SESSION["nombres"]);
			$smarty->assign('apellidos', $_SESSION["apellidos"]);
			$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
		} else {
			$prop_id = $propiedades->verificar_propiedad($clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id);
			if ($prop_id == -1) {
				$prop_id = $propiedades->insertar_nueva_propiedad($clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id);
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
			
			$detalle_orden->adicionar_producto_detalle($_POST['orden_id'],$_POST['texto_cantidad'],$_POST['texto_unidad'],$_POST['texto_prioridad'],$observaciones,$_POST['texto_pedido'],$prop_id,$texto_grabado,$_POST['texto_lugar_grabado'],$_POST['texto_fuente'],$observaciones_internas,$familia_id,$_POST['tipo']);
			$orden_id = $_POST['orden_id'];
			header("location: modificar_detalle_orden.php?funcion=modificar&elegido=".$orden_id);
		}
	} else {
		if ($funcion == "modificar") {
			//para mostrar los tipos de producto indicadores
			$orden_id = $_GET["elegido"];
			
			if (isset($_POST['enviar'])) {
				$tipo = $_POST['tipo'];
				$detalle_orden->actualizar_tipo_detalle($orden_id, $tipo);
			}
			
			$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
			$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);//
			//
  		    $detalle = $detalle_orden->obtener_detalle_orden($orden_id);
			$detalle_asignaciones = $detalle_orden->obtener_asignaciones_detalle_orden($orden_id);
  		    $cabecera = $orden->obtener_cabecera_orden($orden_id);
  			$smarty->assign('pagina_envio',"modificar");
			$smarty->assign('orden_id', $orden_id);
			$smarty->assign('detalle_asignaciones', $detalle_asignaciones);
			$smarty->assign('cabecera', $cabecera);
        	$smarty->assign('detalle', $detalle);
            $smarty->assign('nombres', $_SESSION["nombres"]);
			$smarty->assign('apellidos', $_SESSION["apellidos"]);
		    $smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
	   } else {
			if ($funcion == "modificar_fecha") {
				$fecha = $_POST["date2"];
				$orden_id = $_POST["orden_id"];
				$orden->modificar_fecha_reprogramacion($orden_id,$fecha);
				$detalle = $detalle_orden->obtener_detalle_orden($orden_id);
				$cabecera = $orden->obtener_cabecera_orden($orden_id);
				$fecha = split("-",$cabecera["fecha_reprogramacion"]);
				$fecha1 = $fecha[2]."-".$fecha[1]."-".$fecha[0];
				$cabecera["fecha_reprogramacion"] = $fecha1;
				$smarty->assign('orden_id', $orden_id);
				$smarty->assign('cabecera', $cabecera);
				$smarty->assign('detalle', $detalle);
				$smarty->assign('pagina_envio',"modificar");
				$smarty->assign('nombres', $_SESSION["nombres"]);
				$smarty->assign('apellidos', $_SESSION["apellidos"]);
				$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
			} else {
				if ($funcion == "eliminar_detalle") {
				 	$orden_id = $_GET["orden_id"];
		  			$detalle_id = $_GET["elegido"];
		  		    $detalle_orden->deshabilitar_detalle($detalle_id);
		 	        $detalle = $detalle_orden->obtener_detalle_orden($orden_id);
  		            $cabecera = $orden->obtener_cabecera_orden($orden_id);
            		$fecha = split("-",$cabecera["fecha_reprogramacion"]);
 		            $fecha1 = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		            $cabecera["fecha_reprogramacion"] = $fecha1;
					
					//*******
					$detalle_asignaciones = $detalle_orden->obtener_asignaciones_detalle_orden($orden_id);
					$smarty->assign('detalle_asignaciones', $detalle_asignaciones);
					//*******
					
 		            $smarty->assign('orden_id', $orden_id);
			   		$smarty->assign('cabecera', $cabecera);
  		      	    $smarty->assign('detalle', $detalle);
 			        $smarty->assign('pagina_envio',$_GET['pagina_envio']);
					$smarty->assign('nombres', $_SESSION["nombres"]);
			        $smarty->assign('apellidos', $_SESSION["apellidos"]);
			        $smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
				} else {
					if ($funcion == "modificar_detalle") {
						$orden_id = $_GET["orden_id"];
						$detalle_id = $_GET["elegido"];
						
						$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
						$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
						$cabecera = $orden->obtener_cabecera_orden($orden_id);
						$detalle_datos = $detalle_orden->obtener_datos_detalle($detalle_id,$orden_id);
						$fecha = split("-",$cabecera["fecha_reprogramacion"]);
						$fecha1 = $fecha[2]."-".$fecha[1]."-".$fecha[0];
						$cabecera["fecha_reprogramacion"] = $fecha1;
						$smarty->assign('orden_id', $orden_id);
						$smarty->assign('cabecera', $cabecera);
						$smarty->assign('detalle_id', $detalle_id);
						$smarty->assign('detalle', $detalle_datos);
						$smarty->assign('pagina_envio',$_GET['pagina_envio']);
						$smarty->assign('nombres', $_SESSION["nombres"]);
						$smarty->assign('apellidos', $_SESSION["apellidos"]);
						$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_producto_detalle_orden.html');
					} else {
						if ($funcion == "tool_tip") {
							$orden_id = $_GET["orden_id"];
					    	$detalle_id = $_GET["elegido"];
							$detalle_datos = $detalle_orden->obtener_datos_detalle($detalle_id,$orden_id);
							$smarty->assign('titulo', "Observaciones");
							$smarty->assign('mensaje', $detalle_datos['observaciones']);
							$smarty->assign('titulo2', "Obs. Internas");
							$smarty->assign('mensaje2', $detalle_datos['obs_interior']);
							$smarty->display('sistema_de_produccion/tool_tips/plantilla.html');
						} else {
							if ($funcion == "buscar_pedido") {
								$orden_id = $_POST["orden_id"];
								$pedido = $_POST["texto_buscar_pedido"];
								$cabecera = $orden->obtener_cabecera_orden($orden_id);
								$detalle_datos = $detalle_orden->obtener_datos_pedido($pedido);
								$smarty->assign('detalle_datos', $detalle_datos);
								
								//
								$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
								$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
								//
								
								$detalle=$detalle_orden->obtener_detalle_orden($orden_id);
								$smarty->assign('detalle', $detalle);
								$smarty->assign('pagina_envio', $_POST['pagina_envio']);
								$smarty->assign('cabecera', $cabecera);
								$smarty->assign('texto_buscar_pedido', $pedido);
		          			    $smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
							} else {
								if ($funcion == "copiar_identico") {
									$detalle_id = $_GET['deid'];
									
									//
									$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
									$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
									//
									
									$orden_id = $_GET["oid"];
									$cabecera = $orden->obtener_cabecera_orden($orden_id);
									$detalle = $detalle_orden->obtener_detalle_orden($orden_id);
									$smarty->assign('cabecera', $cabecera);
									$smarty->assign('detalle', $detalle);
									//******************************************************************
									$detalle_datos = $detalle_orden->obtener_datos_desde_detalle($detalle_id);
									$smarty->assign('modelo', $detalle_datos['modelo']." ::: ".$detalle_datos['estilo']);
									$smarty->assign('clip', $detalle_datos['clip']);
									$smarty->assign('color', $detalle_datos['color']);
									$smarty->assign('cuero', $detalle_datos['cuero']);
									$smarty->assign('etiqueta', $detalle_datos['etiqueta']);
									$smarty->assign('sello', $detalle_datos['sello']);
									$smarty->assign('pedido', $detalle_datos['pedido']);
									$smarty->assign('unidad', $detalle_datos['unidad']);
									$smarty->assign('prioridad', $detalle_datos['prioridad']);
									$smarty->assign('lugar_grabado', $detalle_datos['lugargrabado']);
									$smarty->assign('observaciones', $detalle_datos['observaciones']);
									$smarty->assign('tipo', $detalle_datos['tipo']);
									
									$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');
								}
							}
						}
					}
				}
			}
		}
	}
}
?>