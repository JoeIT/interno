<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/grupos.php');
include_once('../../clases/paginas.php');
include_once('../../clases/includes/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/usuarios/';
$smarty->compile_dir = '../../templates_c';

$grupo=new Grupos;
$pagina=new Paginas;
$validar = new Validador();
$funcion = $_POST['funcion'];



if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if($funcion=="registrar"){
		if (!empty($_POST)){
			if ($validar->validarTodo($_POST['nombre'], 1, 100))
				$error['err_descripcion'] = "Ingresa el grupo";
			if (isset($error)){
				$_SESSION['nombre'] = $_POST['nombre'];
				$_SESSION['error']= $error;
				$error['err_confirm'] = "El registro se realizo correctamente";
				$smarty->assign('errores',$error);						
				$lista= $grupo->consulta_lista_grupos();	
				$smarty->assign('grupo',$lista);
				$paginas= $pagina->lista_paginas();
				$smarty->assign('paginas',$paginas);
				$smarty->display('grupos.html');
			} else {
				$descripcion = $_POST['nombre'];
				$lista_paginas = $_POST['seleccionados'];
				$resultado=$grupo->nuevo_grupo($descripcion,$lista_paginas);
				if($resultado){
					$error['err_confirm'] = "El registro se realizo correctamente";
					$smarty->assign('errores',$error);						
					$lista= $grupo->consulta_lista_grupos();	
					$smarty->assign('grupo',$lista);
					$paginas= $pagina->lista_paginas();
					$smarty->assign('paginas',$paginas);
					$smarty->display('grupos.html');
				}
			}
		}
	} else {
		if($funcion=="modificar"){
			$id=$_POST["elegido"];
			$smarty->assign('grupoId',$id);
			$paginas= $pagina->lista_paginas();
			$smarty->assign('pag',$paginas);
			$lista= $grupo->obtener_grupo($id);	
			$smarty->assign('grupo',$lista[0][nombre]);
			$paginas= $pagina->obtener_paginas($id);
			$smarty->assign('paginas',$paginas);
			$smarty->display('modificar_grupo.html');
		} else {
			if($funcion=="modificar grupo") {
				$id=$_POST["elegido"];
				if (!empty($_POST)){
					if ($validar->validarTodo($_POST['nombre'], 1, 100))
						$error['err_descripcion'] = "Ingresa el grupo";
					if (isset($error)){
						$_SESSION['nombre'] = $_POST['nombre'];
						$_SESSION['error']= $error;
						$error['err_confirm'] = "El registro se realizo correctamente";
						$smarty->assign('errores',$error);						
						$smarty->assign('nombre',$nombre);
						$paginas= $pagina->lista_paginas();
						$smarty->assign('pag',$paginas);
						$lista= $grupo->obtener_grupo($id);	
						$smarty->assign('grupo',$lista[0][nombre]);
						$paginas= $pagina->obtener_paginas($id);
						$smarty->assign('paginas',$paginas);
						$smarty->display('modificar_grupo.html');
					} else {
						$descripcion = $_POST['nombre'];
						$lista_paginas = $_POST['seleccionados'];
						$resultado=$grupo->modificar_grupo($id,$descripcion,$lista_paginas);
						if($resultado) {
							$error['err_confirm'] = "La modificación se realizo correctamente";
							$smarty->assign('errores',$error);						
							$lista= $grupo->consulta_lista_grupos();	
							$smarty->assign('grupo',$lista);
							$paginas= $pagina->lista_paginas();
							$smarty->assign('paginas',$paginas);
							$smarty->display('grupos.html');
						}
					}
				}
			} else {
				if($funcion=="eliminar"){
					$id=$_POST["elegido"];
					$gid=$_POST["gId"];
					$paginas= $pagina->eliminar_pagina_grupo($id,$gid);
					$smarty->assign('grupoId',$gid);
					$paginas= $pagina->lista_paginas();
					$smarty->assign('pag',$paginas);
					$lista= $grupo->obtener_grupo($gid);	
					$smarty->assign('grupo',$lista[0][nombre]);
					$paginas= $pagina->obtener_paginas($gid);
					$smarty->assign('paginas',$paginas);
					$smarty->display('modificar_grupo.html');
				} else {
					if($funcion=="eliminargrupo"){
						$id=$_POST["elegido"];
				
						$lista= $grupo->eliminar_grupo($id);
						$lista= $grupo->consulta_lista_grupos();
						$smarty->assign('grupo',$lista);
						$paginas= $pagina->lista_paginas();
						$smarty->assign('paginas',$paginas);
						$smarty->display('grupos.html');
					} else {
						$lista= $grupo->consulta_lista_grupos();	
						$smarty->assign('grupo',$lista);
						$paginas= $pagina->lista_paginas();
						$smarty->assign('paginas',$paginas);
						$smarty->display('grupos.html');
					}
				}
			}
		}
	}

}
?>