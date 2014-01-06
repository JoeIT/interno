<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/instructivos.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/includes/validador.php');

$instructivo = new Instructivo;
$detalle = new Detalle_orden;
$validar = new Validador();
$funcion = $_GET['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates/sistema_de_produccion/instructivos/';
$smarty->compile_dir = '../../templates_c';

if(!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {
	if (isset($_GET["busqueda_ajax"])) {
		$valor = $_POST["value"];
		$cadena = trim($valor);
		echo "<ul>";
		$lista = $instructivo->busqueda_instructivos($cadena);
		if (count($lista) == 0) {
			echo "<li>No hay resultados</li>";
		} else {
			for ($contador = 0; $contador < count($lista); $contador ++) {
				echo "<li>".$lista[$contador]."</li>";
			}
		}
		echo "</ul>";
	} else {
		if ($funcion == "buscar_instructivo") {
			//echo "buscar instructivo";
			if (isset($_POST['buscar'])){
				$nuevo = trim($_POST['nuevo']);
				if ($nuevo == "") {
					$error['err_nuevo'] = "Ingrese descripcion del producto";
				} else {
					$codigo_instructivo = $instructivo->sacar_id_instructivo($nuevo);
					if (($codigo_instructivo != 'No hay resultados') && is_numeric($codigo_instructivo)) {
						$resultado = $instructivo->obtener_url_instructivo($codigo_instructivo);
						$smarty->assign('nuevo', $nuevo);
						$smarty->assign('url', $resultado['url']);
					} else {
						$error['err_nuevo'] = "Ingrese descripcion del producto v&aacute;lido";
					}
				}
				
				if (isset($error)) {
					$smarty->assign('error', $error);
					$smarty->assign('nuevo', $nuevo);
				}
			}
			$smarty->display('busqueda_instructivo.html');
		} else {
			if ($funcion == "registrar") {
				if (!empty($_GET)) {
					$codigo = $_GET['elegido'];
					$orden = $_GET['orden'];
					$bandera = $_GET['bandera'];
					//echo $codigo;
					$consulta = $instructivo->consulta_detalle_orden($codigo);
					$consulta2 = $instructivo->obtener_familia_estilo($codigo);
					$smarty->assign('orden', $orden);
					$similares = $instructivo->obtener_similares($consulta2[0]['f'], $consulta2[0]['e']);
					$ultimo = $instructivo->lista_instructivos($codigo, $consulta2[0]['c'], $consulta2[0]['cu']);
					
					$smarty->assign('lista', $consulta);
					$smarty->assign('detalle', $codigo);
					$smarty->assign('similares', $similares);
					$smarty->assign('nombre_familia', $consulta2[0]['t']);
					$smarty->assign('familia_id', $consulta2[0]['f']);
					$smarty->assign('ultimo', $ultimo);
					$smarty->assign('comodin', $bandera);
					
					$smarty->assign('cliente', $consulta2[0]['cliente']);
					$smarty->assign('cuero', $consulta2[0]['cuero']);
					$smarty->assign('familia', $consulta2[0]['familia']);
					$smarty->assign('estilo', $consulta2[0]['estilo']);
					
					$smarty->assign('cliente_id', $consulta2[0]['c']);
					$smarty->assign('cuero_id', $consulta2[0]['cu']);
					$smarty->display('registrar_instructivo.html');
				}
			} else {
				if ($funcion == "insertar") {
					$codigo = $_POST['elegido'];
					$codigo_instructivo = $_POST['escogido'];
					$lista_similares = $_POST['lista_similares'];
					$familia_id = $_POST['familia_id'];
					$cliente_id = $_POST['cliente_id'];
					$cuero_id = $_POST['cuero_id'];
					$nuevo = $_POST['nuevo'];
					$grafico = $_POST['grafico'];
					$descripcion = $_POST['descripcion'];
					$validacion = $_POST['validacion'];
					$tipo = $_POST['tipo'];
					
					//validaciones, haber como resulta
					if (trim($HTTP_POST_FILES['grafico']['name']) == "" && trim($_POST['nuevo']) == "" && !isset($_POST['escogido'])) {
						$error['err_archivo'] = "Seleccione instructivo";
					}
					
					/*
					if ($HTTP_POST_FILES['grafico']['name']!="" && $nuevo!="") {
						$error['err_archivo'] = "Seleccione solo un instructivo";
					} else {
						if ($tipo) {
							if ($HTTP_POST_FILES['grafico']['name']=="") {
								$error['err_archivo'] = "Seleccione solo un instructivo";
							}
							if ($validar->validarTodo($_POST['descripcion'], 1, 100))
								$error['err_descripcion'] = "Ingresa la descripcion del instructivo";
						} else {
							if ($validacion) {
								if ($codigo_instructivo == "")
									$error['err_archivo'] = "Seleccione solo un instructivo";
							} else {
								if ($HTTP_POST_FILES['grafico']['name']=="" && $nuevo=="") {
									$error['err_archivo'] = "Seleccione solo un instructivo";
								}
								if ($nuevo == "") {
									if ($validar->validarTodo($_POST['descripcion'], 1, 100))
										$error['err_descripcion'] = "Ingresa la descripcion del instructivo";
								}
							}
						}
					}
					*/
					
					if ($error != "") {
						$orden = $_POST['orden'];
						$consulta = $instructivo->consulta_detalle_orden($codigo);
						$consulta2 = $instructivo->obtener_familia_estilo($codigo);
						$smarty->assign('orden', $orden);
						$similares = $instructivo->obtener_similares($consulta2[0]['f'], $consulta2[0]['e']);
						$ultimo = $instructivo->lista_instructivos($codigo, $consulta2[0]['c'], $consulta2[0]['cu']);
											
						$smarty->assign('lista', $consulta);
						$smarty->assign('detalle', $codigo);
						$smarty->assign('similares', $similares);
						$smarty->assign('nombre_familia', $consulta2[0]['t']);
						$smarty->assign('cliente_id', $consulta2[0]['c']);
						$smarty->assign('familia_id', $consulta2[0]['f']);
						$smarty->assign('ultimo', $ultimo);
						$smarty->assign('comodin', $bandera);
						$smarty->assign('mensaje', $error);
						$smarty->assign('nuevo', $nuevo);
						$smarty->assign('descripcion', $descripcion);
						$smarty->assign('cuero_id', $consulta2[0]['cu']);
						$smarty->display('registrar_instructivo.html');
					} else {
						//si no existe errores
						if ($HTTP_POST_FILES['grafico']['name'] != "") {
							// nuevo instructivo
							$nombre_archivo = $HTTP_POST_FILES['grafico']['name'];
							$nombre_archivo_original = $HTTP_POST_FILES['grafico']['name'];
							$tipo_archivo = $HTTP_POST_FILES['grafico']['type'];
							$tamano_archivo = $HTTP_POST_FILES['grafico']['size'];
							
							if (!(strpos($tipo_archivo, "pdf"))) {
								$error = "Tipo de Archivo no valido";
								$consulta = $instructivo->consulta_detalle_orden($codigo);
								$consulta2 = $instructivo->obtener_familia_estilo($codigo);
								$similares = $instructivo->obtener_similares($consulta2[0]['f'], $consulta2[0]['e']);
								$ultimo = $instructivo->lista_instructivos($codigo, $consulta2[0]['c'], $consulta2[0]['cu']);
								
								$smarty->assign('lista', $consulta);
								$smarty->assign('detalle', $codigo);
								$smarty->assign('similares', $similares);
								$smarty->assign('nombre_familia', $consulta2[0]['t']);
								$smarty->assign('cliente_id', $consulta2[0]['c']);
								$smarty->assign('familia_id', $consulta2[0]['f']);
								$smarty->assign('ultimo', $ultimo);
								$smarty->assign('comodin', $bandera);
								$smarty->assign('mensaje', "Intente de nuevo, archivo incorrecto");
								$smarty->display('registrar_instructivo.html');
							} else {
								$prefijo = substr(md5(uniqid(rand())), 0, 6);
								$nombre_archivo="../../instructivos/".$prefijo."_".$nombre_archivo;
								move_uploaded_file($HTTP_POST_FILES['grafico']['tmp_name'], $nombre_archivo);
								
								$consulta = $instructivo->insertar_instructivo($familia_id,$descripcion,$nombre_archivo,$nombre_archivo_original,$cliente_id,$cuero_id);
								$consulta = $instructivo->consulta_detalle_orden($codigo);
								$consulta2 = $instructivo->obtener_familia_estilo($codigo);
								$ultimo = $instructivo->lista_instructivos($codigo, $consulta2[0]['c'], $consulta2[0]['cu']);
								$smarty->assign('detalle', $ultimo);
								$codigo_instructivo = $ultimo[0]['codigo'];
								
								if ($lista_similares == "") {
									$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $codigo);
									$codigo = $_POST['orden'];
									$consulta = $instructivo->obtener_detalle_orden($codigo);
									$smarty->assign('contenido',$consulta);
									$consulta = $instructivo->obtener_detalle_cabeza($codigo);
									$smarty->assign('cabeza', $consulta);
									$smarty->assign('orden', $codigo);
									//$smarty->display('buscador.html');
									$smarty->display('lista_detalles.html');
								} else {
									$tam = sizeof($lista_similares);
									$contador = 0;
									while ($contador <= $tam - 1) {
										$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $lista_similares[$contador]);
										$contador = $contador + 1;
									}
									
									$codigo = $_POST['orden'];
									$consulta = $instructivo->obtener_detalle_orden($codigo);
									$smarty->assign('contenido',$consulta);
									$consulta = $instructivo->obtener_detalle_cabeza($codigo);
									$smarty->assign('cabeza',$consulta);
									$smarty->assign('orden',$codigo);
									$smarty->display('lista_detalles.html');
								}
							}
						} else {
							if ($nuevo != "") {
								$codigo_instructivo = $instructivo->sacar_id_instructivo($nuevo);
								$smarty->assign('mensaje',"Se asigno correctamente");
							}
							// mismo instructivo
							if ($lista_similares == "") {
								$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo,$codigo);
								$codigo = $_POST['orden'];
								$consulta = $instructivo->obtener_detalle_orden($codigo);
								$smarty->assign('contenido',$consulta);
								$consulta = $instructivo->obtener_detalle_cabeza($codigo);
								$smarty->assign('cabeza',$consulta);
								$smarty->assign('orden',$codigo);
								//$smarty->display('buscador.html');
								$smarty->display('lista_detalles.html');
							} else {
								$tam = sizeof($lista_similares);
								$contador = 0;
								while ($contador <= $tam - 1) {
									$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $lista_similares[$contador]);
									$contador = $contador + 1;
								}
								$codigo = $_POST['orden'];
								$consulta = $instructivo->obtener_detalle_orden($codigo);
								$smarty->assign('contenido', $consulta);
								$consulta= $instructivo->obtener_detalle_cabeza($codigo);
								$smarty->assign('cabeza', $consulta);
								$smarty->assign('orden', $codigo);
								$smarty->display('lista_detalles.html');
							}
						}
					}
				} else {
					if ($funcion == "buscar_familia") {
						$id = $_GET['modelo'];
						$consulta = $instructivo->consultar_familia_instructivos($id);
						$smarty->assign('contenido', $consulta);
						$smarty->display('lista_instructivos.html');
					} else {
						if($funcion == "modificar_datos") {
							if (!empty($_GET)) {
								$codigo = $_GET['elegido'];
								$consulta = $instructivo->obtener_instructivos_id($codigo);
								$consulta2 = $instructivo->obtener_familia_estilo2($codigo);
								$similares = $instructivo->obtener_similares2($consulta2[0]['f'], $consulta2[0]['e']);
								$ultimo = $instructivo->lista_instructivos($codigo, $consulta2[0]['c'], $consulta2[0]['cu']);
								
								$smarty->assign('lista', $consulta);
								$smarty->assign('detalle', $codigo);
								$smarty->assign('similares', $similares);
								$smarty->assign('nombre_familia', $consulta2[0]['t']);
								$smarty->assign('familia_id', $consulta2[0]['f']);
								$smarty->assign('ultimo', $ultimo);
								$smarty->assign('comodin', $bandera);
								$smarty->assign('cliente_id', $consulta2[0]['c']);
								$smarty->assign('cuero_id', $consulta2[0]['cu']);
								$smarty->display('actualizar_instructivo.html');
							}
						} else {
							if($funcion == "eliminar") {
								$id = $_GET['elegido'];
								$consulta = $pagina->eliminar_pagina($id);
								$consulta = $pagina->consulta_lista_paginas();
								$smarty->assign('pagina', $consulta);
								$smarty->display('paginas.html');
							} else {
								if ($funcion == "buscar") {
									$id = $_GET['modelo'];
									$consulta = $instructivo->consultar_busqueda($id);
									$smarty->assign('contenido', $consulta);
									$smarty->assign('tabu', '1');
									$smarty->display('instructivos.html');
								} else {
									if ($funcion == "actualizar") {
										$consulta = $instructivo->obtener_con_instructivos();
										$smarty->assign('contenido', $consulta);
										$smarty->display('lista_instructivos.html');
									} else {
										if ($funcion == "detalle") {
											$codigo = $_GET['elegido'];
											$consulta = $instructivo->obtener_detalle_orden($codigo);
											$smarty->assign('contenido', $consulta);
											$consulta = $instructivo->obtener_detalle_cabeza($codigo);
											$smarty->assign('cabeza', $consulta);
											$smarty->assign('orden', $codigo);
											$smarty->display('lista_detalles.html');
										} else {
											if ($funcion == "buscar") {
												$id = $_GET['modelo'];
												$consulta = $instructivo->consultar_busqueda($id);
												$smarty->assign('contenido', $consulta);
												$smarty->assign('tabu', '1');
												$smarty->display('instructivos.html');
											} else {
												if ($funcion == "actualizando") {
													$codigo = $_POST['elegido'];
													$codigo_instructivo = $_POST['escogido'];
													$lista_similares = $_POST['lista_similares'];
													$familia_id = $_POST['familia_id'];
													$cliente_id = $_POST['cliente_id'];
													$cuero_id = $_POST['cuero_id'];
													$nuevo = $_POST['nuevo'];
													$grafico = $_POST['grafico'];
													$descripcion = $_POST['descripcion'];
													$validacion = $_POST['validacion'];
													
													if ($HTTP_POST_FILES['grafico']['name'] == "") {
														$error['err_archivo'] = "Seleccione solo un instructivo";
													}
													if ($validar->validarTodo($_POST['descripcion'], 1, 100))
														$error['err_descripcion'] = "Ingresa la descripcion del instructivo";
													if ($error != "") {
														$codigo = $_POST['elegido'];
														$orden = $_POST['orden'];
														$smarty->assign('orden', $orden);
														$consulta = $instructivo->obtener_instructivos_id($codigo);
														$consulta2 = $instructivo->obtener_familia_estilo2($codigo);
														$similares = $instructivo->obtener_similares2($consulta2[0]['f'], $consulta2[0]['e']);
														$ultimo = $instructivo->lista_instructivos($codigo,$consulta2[0]['c'], $consulta2[0]['cu']);
														$smarty->assign('lista', $consulta);
														$smarty->assign('detalle', $codigo);
														$smarty->assign('similares', $similares);
														$smarty->assign('nombre_familia', $consulta2[0]['t']);
														$smarty->assign('familia_id', $consulta2[0]['f']);
														$smarty->assign('ultimo', $ultimo);
														$smarty->assign('comodin', $bandera);
														$smarty->assign('mensaje', $error);
														$smarty->assign('cliente_id', $consulta2[0]['c']);
														$smarty->assign('cuero_id', $consulta2[0]['cu']);	
														$smarty->display('actualizar_instructivo.html');
													} else {
														if ($HTTP_POST_FILES['grafico']['name'] != "") {
															// nuevo instructivo
															$nombre_archivo = $HTTP_POST_FILES['grafico']['name'];
															$nombre_archivo_original = $HTTP_POST_FILES['grafico']['name'];
															$tipo_archivo = $HTTP_POST_FILES['grafico']['type'];
															$tamano_archivo = $HTTP_POST_FILES['grafico']['size'];
															if (!(strpos($tipo_archivo, "pdf"))) {
																$error['err_archivo'] = "Archivo incorrecto";
																$consulta = $instructivo->obtener_instructivos_id($codigo);
																$consulta2 = $instructivo->obtener_familia_estilo2($codigo);
																$similares = $instructivo->obtener_similares2($consulta2[0]['f'], $consulta2[0]['e']);
																$ultimo=$instructivo->lista_instructivos($codigo,$consulta2[0]['c'], $consulta2[0]['cu']);
																$smarty->assign('lista', $consulta);
																$smarty->assign('detalle', $codigo);
																$smarty->assign('similares', $similares);
																$smarty->assign('nombre_familia', $consulta2[0]['t']);
																$smarty->assign('familia_id', $consulta2[0]['f']);
																$smarty->assign('ultimo', $ultimo);
																$smarty->assign('comodin', $bandera);
																$smarty->assign('mensaje', $error);
																$smarty->assign('cliente_id', $consulta2[0]['c']);
																$smarty->assign('cuero_id', $consulta2[0]['cu']);
																$smarty->display('actualizar_instructivo.html');
															} else {
																$prefijo = substr(md5(uniqid(rand())), 0, 6);
																$nombre_archivo = "../../instructivos/".$prefijo."_".$nombre_archivo;
																move_uploaded_file($HTTP_POST_FILES['grafico']['tmp_name'], $nombre_archivo);
																$consulta = $instructivo->insertar_instructivo($familia_id,$descripcion,$nombre_archivo,$nombre_archivo_original,$cliente_id,$cuero_id);
																$consulta = $instructivo->desactivar_instructivo($codigo);
																$consulta = $instructivo->consulta_detalle_orden($codigo);
																$consulta2 = $instructivo->obtener_familia_estilo2($codigo);
																$ultimo = $instructivo->lista_instructivos($codigo, $consulta2[0]['c'], $consulta2[0]['cu']);
																$smarty->assign('detalle', $ultimo);
																$codigo_instructivo = $ultimo[0]['codigo'];
																if ($lista_similares == "") {
																	$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $codigo);
																	$consulta = $instructivo->obtener_con_instructivos();
																	$smarty->assign('contenido', $consulta);
																	$smarty->display('lista_instructivos.html');
																} else {
																	$tam = sizeof($lista_similares);
																	$contador = 0;
																	while ($contador <= $tam - 1) {
																		$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $lista_similares[$contador]);
																		$contador = $contador + 1;
																	}
																	$consulta = $instructivo->obtener_con_instructivos();
																	$smarty->assign('contenido', $consulta);
																	$smarty->display('lista_instructivos.html');
																}
															}
														} else {
															if ($nuevo != "") {
																$codigo_instructivo = $instructivo->sacar_id_instructivo($nuevo);
																$smarty->assign('mensaje', "Se asigno correctamente");
															}
															// mismo instructivo
															if ($lista_similares == "") {
																$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $codigo);
																$consulta = $instructivo->obtener_con_instructivos();
																$smarty->assign('contenido', $consulta);
																$smarty->display('lista_instructivos.html');
															} else {
																$tam = sizeof($lista_similares);
																$contador = 0;
																while ($contador <= $tam - 1) {
																	$consulta = $instructivo->insertar_instructivo_a_detalle($codigo_instructivo, $lista_similares[$contador]);
																	$contador = $contador + 1;
																}
																$consulta = $instructivo->obtener_con_instructivos();
																$smarty->assign('contenido', $consulta);
																$smarty->display('lista_instructivos.html');
															}
														}
													}
												} else {
													if ($funcion == "buscar_ordenes") {
														//echo"Entro";
														$id = trim($_GET['orden']);
														$consulta= $instructivo->consultar_busqueda($id);
														$smarty->assign('orden', $id);
														$smarty->assign('contenido', $consulta);
														$smarty->assign('tabu', '1');
														$smarty->display('instructivos.html');
													} else {
														if ($funcion == "buscar_modelos") {
															//echo "entro modelos";
															$mod = trim($_GET['modelo']);
															$est = trim($_GET['est']);
															$consulta = $instructivo->consultar_modelos_estilos_ordenes($mod, $est);
															$smarty->assign('modelo', $mod);
															$smarty->assign('est', $est);
															$smarty->assign('contenido', $consulta);
															$smarty->assign('tabu', '1');
															$smarty->display('instructivos.html');
														} else {
															if ($funcion == "listar_instructivos") {
																$consulta = $instructivo->listar_mostrar_instructivos();
																$smarty->assign('contenido', $consulta);
																$smarty->display('lista_mostrar_instructivos.html');
															} else {
																$consulta = $instructivo->consulta_lista_ordenes();
																$smarty->assign('contenido', $consulta);
																$smarty->assign('tabu', '1');
																$smarty->display('instructivos.html');
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}//if ($funcion == "buscar_instructivo")
	}
}
?>